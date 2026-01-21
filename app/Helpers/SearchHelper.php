<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class SearchHelper
{
    /**
     * Extrait un snippet de texte et met en évidence le mot-clé recherché
     *
     * @param string $text Le texte source
     * @param string $keyword Le mot-clé à mettre en évidence
     * @param int $length La longueur du snippet
     * @return string Le snippet avec mise en évidence
     */
    public static function extractAndHighlight($text, $keyword, $length = 150)
    {
        // Nettoyer le texte d'entrée
        $text = strip_tags($text);
        $keyword = trim($keyword);
        
        if (!$keyword || !$text) {
            return Str::limit($text, $length);
        }

        // Trouver la position du mot-clé (insensible à la casse)
        $pos = stripos($text, $keyword);
        if ($pos === false) {
            return Str::limit($text, $length);
        }

        // Calculer le début du snippet en centrant sur le mot-clé
        $start = max($pos - intval($length / 2), 0);
        $snippet = substr($text, $start, $length);
        
        // Ajouter des ellipses si nécessaire
        if ($start > 0) {
            $snippet = '...' . $snippet;
        }
        if ($start + $length < strlen($text)) {
            $snippet .= '...';
        }
        
        // Échapper le contenu pour éviter XSS puis mettre en évidence
        $snippet = e($snippet);
        $escapedKeyword = e($keyword);
        
        // Mettre en évidence le mot-clé (insensible à la casse)
        return preg_replace(
            "/(" . preg_quote($escapedKeyword, '/') . ")/i", 
            '<mark class="search-highlight">$1</mark>', 
            $snippet
        );
    }

    /**
     * Valide et nettoie une query de recherche
     *
     * @param string $query
     * @return string
     */
    public static function sanitizeQuery($query)
    {
        if (!is_string($query)) {
            return '';
        }

        return trim(strip_tags($query));
    }

    /**
     * Génère un résumé sécurisé pour les résultats de recherche
     *
     * @param object $item
     * @param string $query
     * @return string
     */
    public static function getItemSummary($item, $query)
    {
        $content = '';

        switch ($item->type_global ?? '') {
            case 'Publication':
            case 'Rapport':
                $content = $item->resume ?? '';
                break;
            case 'Actualité':
                $content = $item->contenu ?? '';
                break;
            case 'Projet':
                $content = $item->resume ?? $item->description ?? '';
                break;
        }

        if (!$content) {
            return 'Aucune description disponible.';
        }

        return self::extractAndHighlight($content, $query);
    }
}
