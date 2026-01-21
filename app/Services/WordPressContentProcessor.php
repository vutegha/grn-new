<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;

class WordPressContentProcessor
{
    /**
     * Traite et améliore le contenu HTML importé de WordPress
     */
    public static function processContent($content)
    {
        if (empty($content)) {
            return $content;
        }

        // Créer un DOMDocument pour parser le HTML
        $dom = new DOMDocument('1.0', 'UTF-8');
        
        // Supprimer les erreurs de parsing HTML
        libxml_use_internal_errors(true);
        
        // Charger le contenu avec les entités UTF-8
        $dom->loadHTML('<?xml encoding="UTF-8">' . '<div>' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        // Créer un XPath pour la navigation
        $xpath = new DOMXPath($dom);
        
        // Traiter les images
        self::processImages($xpath, $dom);
        
        // Traiter les paragraphes
        self::processParagraphs($xpath, $dom);
        
        // Traiter les blocs WordPress
        self::processWordPressBlocks($xpath, $dom);
        
        // Traiter les galeries
        self::processGalleries($xpath, $dom);
        
        // Nettoyer le code généré
        self::cleanupContent($xpath, $dom);
        
        // Récupérer le contenu traité
        $processedContent = '';
        $bodyNodes = $xpath->query('//div')->item(0)->childNodes;
        
        foreach ($bodyNodes as $node) {
            $processedContent .= $dom->saveHTML($node);
        }
        
        // Restaurer la gestion d'erreurs
        libxml_clear_errors();
        libxml_use_internal_errors(false);
        
        return $processedContent;
    }

    /**
     * Traite les images pour améliorer leur présentation
     */
    private static function processImages($xpath, $dom)
    {
        $images = $xpath->query('//img');
        
        foreach ($images as $img) {
            // Améliorer les images alignées à gauche
            if (self::hasClass($img, 'alignleft') || self::hasStyle($img, 'float:left') || self::hasStyle($img, 'float: left')) {
                self::addClass($img, 'image-style-align-left');
                self::addImageContainer($img, $dom);
            }
            
            // Améliorer les images alignées à droite
            if (self::hasClass($img, 'alignright') || self::hasStyle($img, 'float:right') || self::hasStyle($img, 'float: right')) {
                self::addClass($img, 'image-style-align-right');
                self::addImageContainer($img, $dom);
            }
            
            // Améliorer les images centrées
            if (self::hasClass($img, 'aligncenter')) {
                self::addClass($img, 'image-style-align-center');
            }
            
            // Déterminer la taille et appliquer les classes appropriées
            $width = $img->getAttribute('width');
            if ($width) {
                if ($width < 300) {
                    self::addClass($img, 'small-image');
                } elseif ($width > 600) {
                    self::addClass($img, 'large-image');
                    self::addClass($img, 'image-style-full-width');
                }
            }
            
            // Ajouter la classe zoomable pour les grandes images
            if (!self::hasClass($img, 'no-zoom')) {
                self::addClass($img, 'zoomable');
            }
            
            // Améliorer l'alt text si manquant
            if (!$img->getAttribute('alt') && $img->getAttribute('title')) {
                $img->setAttribute('alt', $img->getAttribute('title'));
            }
        }
    }

    /**
     * Traite les paragraphes pour une meilleure présentation
     */
    private static function processParagraphs($xpath, $dom)
    {
        $paragraphs = $xpath->query('//p');
        
        foreach ($paragraphs as $p) {
            // Paragraphes contenant des images flottantes
            $floatedImages = $xpath->query('.//img[contains(@class, "alignleft") or contains(@class, "alignright") or contains(@style, "float")]', $p);
            
            if ($floatedImages->length > 0) {
                self::addClass($p, 'paragraph-with-floated-image');
            }
            
            // Paragraphes vides - les supprimer ou les marquer
            if (trim($p->textContent) === '' && $p->childNodes->length === 0) {
                self::addClass($p, 'empty-paragraph');
            }
        }
    }

    /**
     * Traite les blocs spécifiques WordPress
     */
    private static function processWordPressBlocks($xpath, $dom)
    {
        // Traiter les blocs de citation
        $blockquotes = $xpath->query('//blockquote');
        foreach ($blockquotes as $blockquote) {
            self::addClass($blockquote, 'enhanced-blockquote');
        }
        
        // Traiter les blocs de code
        $codeBlocks = $xpath->query('//pre');
        foreach ($codeBlocks as $pre) {
            self::addClass($pre, 'enhanced-code-block');
        }
        
        // Traiter les listes
        $lists = $xpath->query('//ul | //ol');
        foreach ($lists as $list) {
            self::addClass($list, 'enhanced-list');
        }
    }

    /**
     * Traite les galeries d'images
     */
    private static function processGalleries($xpath, $dom)
    {
        // Chercher les galeries WordPress
        $galleries = $xpath->query('//div[contains(@class, "wp-block-gallery")]');
        
        foreach ($galleries as $gallery) {
            self::addClass($gallery, 'interactive-gallery');
            
            // Traiter chaque image de la galerie
            $galleryImages = $xpath->query('.//img', $gallery);
            foreach ($galleryImages as $img) {
                // Créer un conteneur pour chaque image
                $galleryItem = $dom->createElement('div');
                $galleryItem->setAttribute('class', 'gallery-item');
                
                // Déplacer l'image dans le conteneur
                $img->parentNode->insertBefore($galleryItem, $img);
                $galleryItem->appendChild($img);
                
                // Ajouter une légende si disponible
                $figcaption = $xpath->query('./following-sibling::figcaption', $img);
                if ($figcaption->length > 0) {
                    $caption = $dom->createElement('div', $figcaption->item(0)->textContent);
                    $caption->setAttribute('class', 'caption');
                    $galleryItem->appendChild($caption);
                    $figcaption->item(0)->parentNode->removeChild($figcaption->item(0));
                }
            }
        }
    }

    /**
     * Nettoie le contenu final
     */
    private static function cleanupContent($xpath, $dom)
    {
        // Supprimer les paragraphes vides
        $emptyParagraphs = $xpath->query('//p[contains(@class, "empty-paragraph")]');
        foreach ($emptyParagraphs as $p) {
            $p->parentNode->removeChild($p);
        }
        
        // Nettoyer les attributs WordPress inutiles
        $elements = $xpath->query('//*[@id or @data-id]');
        foreach ($elements as $element) {
            if ($element->hasAttribute('id') && strpos($element->getAttribute('id'), 'wp-') === 0) {
                $element->removeAttribute('id');
            }
            if ($element->hasAttribute('data-id')) {
                $element->removeAttribute('data-id');
            }
        }
    }

    /**
     * Ajoute un conteneur autour d'une image
     */
    private static function addImageContainer($img, $dom)
    {
        $container = $dom->createElement('div');
        $container->setAttribute('class', 'image-container');
        
        $img->parentNode->insertBefore($container, $img);
        $container->appendChild($img);
        
        return $container;
    }

    /**
     * Vérifie si un élément a une classe spécifique
     */
    private static function hasClass($element, $className)
    {
        $classes = explode(' ', $element->getAttribute('class'));
        return in_array($className, $classes);
    }

    /**
     * Ajoute une classe à un élément
     */
    private static function addClass($element, $className)
    {
        $currentClass = $element->getAttribute('class');
        $classes = $currentClass ? explode(' ', $currentClass) : [];
        
        if (!in_array($className, $classes)) {
            $classes[] = $className;
            $element->setAttribute('class', implode(' ', array_filter($classes)));
        }
    }

    /**
     * Vérifie si un élément a un style spécifique
     */
    private static function hasStyle($element, $style)
    {
        $styleAttr = $element->getAttribute('style');
        return strpos($styleAttr, $style) !== false;
    }

    /**
     * Convertit les URLs d'images WordPress
     */
    public static function convertWordPressImageUrls($content)
    {
        // Convertir les URLs WordPress vers les URLs locales si nécessaire
        $content = preg_replace('/https:\/\/iriucbcdotorg\.wordpress\.com\/wp-content\/uploads\//', '/storage/uploads/', $content);
        
        // Supprimer les paramètres de taille WordPress (?w=xxx)
        $content = preg_replace('/\?w=\d+/', '', $content);
        
        return $content;
    }

    /**
     * Optimise les images pour la performance
     */
    public static function optimizeImagesForPerformance($content)
    {
        // Ajouter lazy loading aux images
        $content = preg_replace('/<img([^>]+)src=/', '<img$1loading="lazy" src=', $content);
        
        // Ajouter des attributs de dimension si manquants
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        $images = $dom->getElementsByTagName('img');
        foreach ($images as $img) {
            if (!$img->getAttribute('width') && !$img->getAttribute('height')) {
                // On pourrait ici récupérer les dimensions réelles de l'image
                // Pour l'instant, on ajouste des dimensions par défaut raisonnables
                $img->setAttribute('style', $img->getAttribute('style') . '; max-width: 100%; height: auto;');
            }
        }
        
        libxml_clear_errors();
        libxml_use_internal_errors(false);
        
        return $dom->saveHTML();
    }
}
