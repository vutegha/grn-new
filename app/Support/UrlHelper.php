<?php

namespace App\Support;

use Illuminate\Support\Facades\URL;

class UrlHelper
{
    /**
     * Convertit un chemin ou URL en URL absolue HTTPS
     *
     * @param string $pathOrUrl
     * @return string
     */
    public static function absolute(string $pathOrUrl): string
    {
        // Si c'est déjà une URL absolue
        if (filter_var($pathOrUrl, FILTER_VALIDATE_URL)) {
            return self::forceHttps($pathOrUrl);
        }

        // Si c'est un chemin relatif, le convertir en URL absolue
        $absoluteUrl = url($pathOrUrl);
        
        return self::forceHttps($absoluteUrl);
    }

    /**
     * Force HTTPS sur une URL
     *
     * @param string $url
     * @return string
     */
    public static function forceHttps(string $url): string
    {
        // Si APP_URL est défini et en HTTPS, forcer HTTPS
        $appUrl = config('app.url');
        if ($appUrl && str_starts_with($appUrl, 'https://')) {
            return str_replace('http://', 'https://', $url);
        }

        // En production, toujours forcer HTTPS
        if (app()->environment('production')) {
            return str_replace('http://', 'https://', $url);
        }

        return $url;
    }

    /**
     * Génère l'URL canonique pour une actualité
     *
     * @param \App\Models\Actualite $actualite
     * @return string
     */
    public static function canonicalUrl($actualite): string
    {
        $url = route('site.actualite.show', $actualite->slug);
        return self::absolute($url);
    }

    /**
     * Génère l'URL canonique pour une publication
     *
     * @param mixed $publication
     * @return string
     */
    public static function canonicalUrlForPublication($publication): string
    {
        // À adapter selon votre modèle Publication
        $url = route('publications.show', $publication->slug);
        return self::absolute($url);
    }

    /**
     * Génère une URL d'image sociale avec cache-busting
     *
     * @param string $path
     * @param int|null $timestamp
     * @return string
     */
    public static function socialImageUrl(string $path, ?int $timestamp = null): string
    {
        $url = self::absolute($path);
        
        if ($timestamp) {
            $separator = str_contains($url, '?') ? '&' : '?';
            $url .= $separator . 'v=' . $timestamp;
        }
        
        return $url;
    }
}
