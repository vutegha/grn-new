<?php

if (!function_exists('storage_url')) {
    /**
     * Generate URL for storage files with fallback for shared hosting
     *
     * @param string|null $path
     * @return string
     */
    function storage_url($path = null)
    {
        if (is_null($path)) {
            return asset('storage');
        }

        // Remove leading slash if present
        $path = ltrim($path, '/');

        // Check if we're in production and symlink doesn't exist
        $publicStorage = public_path('storage');
        
        if (config('app.env') === 'production' && !is_link($publicStorage)) {
            // Use direct public/storage path
            return asset('storage/' . $path);
        }

        // Standard Laravel approach
        return asset('storage/' . $path);
    }
}

if (!function_exists('secure_filename')) {
    /**
     * Generate a secure filename from uploaded file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string|null $prefix
     * @return string
     */
    function secure_filename($file, $prefix = null)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        
        // Sanitize filename
        $filename = \Illuminate\Support\Str::slug($filename);
        
        // Add prefix if provided
        if ($prefix) {
            $filename = $prefix . '-' . $filename;
        }
        
        // Add timestamp to avoid conflicts
        $filename .= '-' . time();
        
        // Add UUID for extra security
        $filename .= '-' . \Illuminate\Support\Str::random(8);
        
        return $filename . '.' . $extension;
    }
}

if (!function_exists('validate_image_mime')) {
    /**
     * Validate image MIME type (not just extension)
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return bool
     */
    function validate_image_mime($file)
    {
        $allowedMimes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
        ];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        return in_array($mimeType, $allowedMimes);
    }
}

if (!function_exists('format_file_size')) {
    /**
     * Format file size in human readable format
     *
     * @param int $bytes
     * @return string
     */
    function format_file_size($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}

if (!function_exists('clean_wordpress_content')) {
    /**
     * Clean WordPress content and convert to clean HTML
     *
     * @param string|null $content
     * @return string
     */
    function clean_wordpress_content($content)
    {
        if (empty($content)) {
            return '';
        }

        // Nettoyer les shortcodes WordPress entre crochets [shortcode]
        $content = preg_replace('/\[([^\]]+)\]/', '', $content);
        
        // Nettoyer les balises <!-- wp: --> spécifiques à Gutenberg
        $content = preg_replace('/<!-- wp:(.+?) -->/', '', $content);
        $content = preg_replace('/<!-- \/wp:(.+?) -->/', '', $content);
        
        // Nettoyer les commentaires HTML
        $content = preg_replace('/<!--(.|\s)*?-->/', '', $content);
        
        // Nettoyer les balises WordPress spécifiques
        $content = str_replace(['[caption]', '[/caption]'], '', $content);
        
        // Convertir les entités HTML WordPress
        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Nettoyer les attributs de style inline excessifs de WordPress
        $content = preg_replace('/style="[^"]*"/', '', $content);
        
        // Nettoyer les classes WordPress inutiles mais garder les utiles
        $keepClasses = ['alignleft', 'alignright', 'aligncenter', 'wp-caption', 'wp-caption-text', 'gallery'];
        $content = preg_replace_callback(
            '/class="([^"]*)"/',
            function($matches) use ($keepClasses) {
                $classes = explode(' ', $matches[1]);
                $filtered = array_filter($classes, function($class) use ($keepClasses) {
                    foreach ($keepClasses as $keep) {
                        if (strpos($class, $keep) !== false) {
                            return true;
                        }
                    }
                    return false;
                });
                return !empty($filtered) ? 'class="' . implode(' ', $filtered) . '"' : '';
            },
            $content
        );
        
        // Nettoyer les attributs data-* de WordPress
        $content = preg_replace('/data-[a-z\-]+=("[^"]*"|\'[^\']*\')/i', '', $content);
        
        // Supprimer les balises <figure> vides
        $content = preg_replace('/<figure[^>]*>\s*<\/figure>/', '', $content);
        
        // Nettoyer les espaces multiples
        $content = preg_replace('/\s+/', ' ', $content);
        
        // Nettoyer les balises vides
        $content = preg_replace('/<(\w+)[^>]*>\s*<\/\1>/', '', $content);
        
        // Convertir les doubles <br> en paragraphes
        $content = preg_replace('/<br\s*\/?>\s*<br\s*\/?>/', '</p><p>', $content);
        
        // S'assurer que le contenu est dans des paragraphes
        if (!preg_match('/^<p/', trim($content))) {
            $content = '<p>' . $content . '</p>';
        }
        
        return trim($content);
    }
}

if (!function_exists('format_rich_text')) {
    /**
     * Format rich text content for display (supports WordPress content)
     *
     * @param string|null $content
     * @param bool $cleanWordPress Clean WordPress specific tags
     * @return string
     */
    function format_rich_text($content, $cleanWordPress = true)
    {
        if (empty($content)) {
            return '';
        }

        // Nettoyer le contenu WordPress si demandé
        if ($cleanWordPress) {
            $content = clean_wordpress_content($content);
        }
        
        // Autoriser seulement les balises sûres
        $allowedTags = '<p><br><strong><b><em><i><u><s><del><a><ul><ol><li><blockquote><code><pre><h1><h2><h3><h4><h5><h6><img><table><thead><tbody><tr><th><td><div><span><hr><figure><figcaption>';
        
        $content = strip_tags($content, $allowedTags);
        
        // Nettoyer les attributs dangereux des balises autorisées
        $content = preg_replace('/<([a-z]+)([^>]*?)on\w+\s*=\s*["\'][^"\']*["\']([^>]*?)>/i', '<$1$2$3>', $content);
        
        return $content;
    }
}

if (!function_exists('format_wordpress_excerpt')) {
    /**
     * Format WordPress content for excerpt display (preserves formatting)
     *
     * @param string|null $content
     * @param int $length Maximum length
     * @param bool $stripImages Strip images from excerpt
     * @return string
     */
    function format_wordpress_excerpt($content, $length = 200, $stripImages = true)
    {
        if (empty($content)) {
            return '';
        }

        // Nettoyer les shortcodes WordPress
        $content = preg_replace('/\[([^\]]+)\]/', '', $content);
        
        // Nettoyer les commentaires Gutenberg
        $content = preg_replace('/<!-- wp:(.+?) -->/', '', $content);
        $content = preg_replace('/<!-- \/wp:(.+?) -->/', '', $content);
        
        // Retirer les images si demandé (pour les extraits)
        if ($stripImages) {
            $content = preg_replace('/<img[^>]+>/i', '', $content);
            $content = preg_replace('/<figure[^>]*>.*?<\/figure>/is', '', $content);
        } else {
            // Garder les attributs WordPress utiles sur les images
            $content = preg_replace('/(<img[^>]+class=")([^"]*)"/', '$1$2 wordpress-image"', $content);
        }
        
        // Autoriser les balises de formatage importantes
        $allowedTags = '<p><br><strong><b><em><i><u><a><ul><ol><li>';
        $content = strip_tags($content, $allowedTags);
        
        // Nettoyer les espaces multiples et retours à la ligne
        $content = preg_replace('/\s+/', ' ', $content);
        $content = trim($content);
        
        // Limiter la longueur tout en préservant les balises
        if (mb_strlen(strip_tags($content)) > $length) {
            // Tronquer intelligemment
            $content = mb_substr($content, 0, $length);
            
            // Trouver le dernier espace pour éviter de couper un mot
            $lastSpace = mb_strrpos($content, ' ');
            if ($lastSpace !== false) {
                $content = mb_substr($content, 0, $lastSpace);
            }
            
            $content .= '...';
        }
        
        return $content;
    }
}

if (!function_exists('fix_wordpress_image_urls')) {
    /**
     * Fix WordPress image URLs to work with Laravel storage system
     * Converts relative URLs and WordPress upload paths to Laravel storage URLs
     *
     * @param string|null $content
     * @return string
     */
    function fix_wordpress_image_urls($content)
    {
        if (empty($content)) {
            return '';
        }

        // Pattern pour trouver toutes les balises img
        $content = preg_replace_callback(
            '/<img([^>]*?)src=["\']([^"\']+)["\']([^>]*?)>/i',
            function ($matches) {
                $before = $matches[1];
                $src = $matches[2];
                $after = $matches[3];
                
                // Si l'URL est déjà absolue (http/https), la garder telle quelle
                if (preg_match('/^https?:\/\//i', $src)) {
                    return $matches[0];
                }
                
                // Si l'URL commence par /storage/, la garder mais s'assurer qu'elle est accessible
                if (strpos($src, '/storage/') === 0) {
                    $fixedSrc = asset($src);
                    return '<img' . $before . 'src="' . $fixedSrc . '"' . $after . '>';
                }
                
                // Si l'URL commence par storage/ (sans slash initial)
                if (strpos($src, 'storage/') === 0) {
                    $fixedSrc = asset('/' . $src);
                    return '<img' . $before . 'src="' . $fixedSrc . '"' . $after . '>';
                }
                
                // Chemins WordPress typiques : wp-content/uploads/...
                if (preg_match('/wp-content\/uploads\/(.+)$/i', $src, $uploadMatches)) {
                    // Essayer de trouver l'image dans storage/uploads/
                    $filename = basename($src);
                    $fixedSrc = asset('storage/uploads/' . $filename);
                    return '<img' . $before . 'src="' . $fixedSrc . '"' . $after . '>';
                }
                
                // Chemins relatifs simples (../uploads/, ./images/, etc.)
                if (preg_match('/^\.\.?\/(.+)$/i', $src, $relativeMatches)) {
                    $filename = basename($src);
                    $fixedSrc = asset('storage/uploads/' . $filename);
                    return '<img' . $before . 'src="' . $fixedSrc . '"' . $after . '>';
                }
                
                // Si le chemin ne commence pas par / ni http, le considérer comme relatif
                if ($src[0] !== '/' && !preg_match('/^https?:\/\//i', $src)) {
                    $fixedSrc = asset('storage/uploads/' . $src);
                    return '<img' . $before . 'src="' . $fixedSrc . '"' . $after . '>';
                }
                
                // Par défaut, essayer de construire une URL asset
                $fixedSrc = asset($src);
                return '<img' . $before . 'src="' . $fixedSrc . '"' . $after . '>';
            },
            $content
        );
        
        return $content;
    }
}

if (!function_exists('clean_wordpress_html')) {
    /**
     * Nettoie le HTML WordPress pour affichage (conserve les URLs d'images externes)
     * 
     * @param string|null $content
     * @param array $options Options de nettoyage
     * @return string
     */
    function clean_wordpress_html($content, array $options = [])
    {
        if (empty($content)) {
            return '';
        }

        // Options par défaut
        $defaults = [
            'remove_comments' => true,
            'remove_empty_paragraphs' => true,
            'remove_wordpress_classes' => true,
            'style_images' => true,
            'image_classes' => 'mx-auto my-4 rounded-lg shadow max-w-full h-auto',
            'wrap_images_in_figure' => true,
            'preserve_external_urls' => true,
        ];
        $options = array_merge($defaults, $options);

        // 1. Supprimer tous les commentaires WordPress Gutenberg
        if ($options['remove_comments']) {
            $content = preg_replace('/<!-- wp:(.+?) -->/', '', $content);
            $content = preg_replace('/<!-- \/wp:(.+?) -->/', '', $content);
            $content = preg_replace('/<!--(.|\s)*?-->/', '', $content);
        }

        // 2. Supprimer les shortcodes WordPress [shortcode]
        $content = preg_replace('/\[([^\]]+)\]/', '', $content);

        // 3. Supprimer les paragraphes vides
        if ($options['remove_empty_paragraphs']) {
            // Supprimer <p></p>, <p> </p>, <p>&nbsp;</p>, etc.
            $content = preg_replace('/<p>(\s|&nbsp;)*<\/p>/i', '', $content);
            $content = preg_replace('/<p>\s*<\/p>/i', '', $content);
        }

        // 4. Nettoyer et styliser les images
        if ($options['style_images']) {
            $content = preg_replace_callback(
                '/<img([^>]*?)>/i',
                function ($matches) use ($options) {
                    $imgTag = $matches[0];
                    $attributes = $matches[1];
                    
                    // Extraire le src
                    preg_match('/src=["\']([^"\']+)["\']/i', $imgTag, $srcMatch);
                    $src = $srcMatch[1] ?? '';
                    
                    // Extraire le alt s'il existe
                    preg_match('/alt=["\']([^"\']+)["\']/i', $imgTag, $altMatch);
                    $alt = $altMatch[1] ?? '';
                    
                    // Supprimer les classes WordPress inutiles si demandé
                    $newTag = '<img';
                    
                    if ($options['remove_wordpress_classes']) {
                        // Ne garder que les classes utiles (align)
                        if (preg_match('/class=["\']([^"\']*)(alignleft|alignright|aligncenter)([^"\']*)["\']/i', $imgTag, $classMatch)) {
                            $alignClass = $classMatch[2];
                            $newTag .= ' class="' . $options['image_classes'] . ' ' . $alignClass . '"';
                        } else {
                            $newTag .= ' class="' . $options['image_classes'] . '"';
                        }
                    } else {
                        // Garder les classes existantes et ajouter les nouvelles
                        preg_match('/class=["\']([^"\']+)["\']/i', $imgTag, $existingClass);
                        if (!empty($existingClass[1])) {
                            $newTag .= ' class="' . $existingClass[1] . ' ' . $options['image_classes'] . '"';
                        } else {
                            $newTag .= ' class="' . $options['image_classes'] . '"';
                        }
                    }
                    
                    $newTag .= ' src="' . $src . '"';
                    
                    if ($alt) {
                        $newTag .= ' alt="' . htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') . '"';
                    }
                    
                    $newTag .= ' loading="lazy"'; // Performance bonus
                    $newTag .= ' />';
                    
                    // Envelopper dans figure si demandé
                    if ($options['wrap_images_in_figure']) {
                        // Chercher une légende potentielle
                        $caption = '';
                        if (preg_match('/<figcaption[^>]*>([^<]+)<\/figcaption>/i', $imgTag, $captionMatch)) {
                            $caption = $captionMatch[1];
                        }
                        
                        $figureTag = '<figure class="my-6 text-center">';
                        $figureTag .= $newTag;
                        if ($caption) {
                            $figureTag .= '<figcaption class="mt-2 text-sm text-gray-600 italic">' . htmlspecialchars($caption, ENT_QUOTES, 'UTF-8') . '</figcaption>';
                        }
                        $figureTag .= '</figure>';
                        
                        return $figureTag;
                    }
                    
                    return $newTag;
                },
                $content
            );
        }

        // 5. Supprimer les balises figure WordPress vides (si images déjà enveloppées)
        $content = preg_replace('/<figure[^>]*class="[^"]*wp-block-[^"]*"[^>]*>/i', '', $content);
        $content = str_replace('</figure>', '', $content);

        // 6. Nettoyer les classes WordPress des autres éléments
        if ($options['remove_wordpress_classes']) {
            $content = preg_replace('/class="[^"]*wp-block-[^"]*"/i', '', $content);
            $content = preg_replace('/class="[^"]*wp-image-[^"]*"/i', '', $content);
        }

        // 7. Nettoyer les attributs de style inline excessifs
        $content = preg_replace('/style="[^"]*"/i', '', $content);

        // 8. Nettoyer les espaces multiples et lignes vides
        $content = preg_replace('/\n\s*\n\s*\n/', "\n\n", $content);
        $content = preg_replace('/[ \t]+/', ' ', $content);

        // 9. Décoder les entités HTML
        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim($content);
    }
}

if (!function_exists('get_cleaned_wordpress_content')) {
    /**
     * Raccourci pour obtenir du contenu WordPress nettoyé et stylisé
     * Parfait pour utiliser comme accessor dans un modèle
     * 
     * @param string|null $content
     * @return string
     */
    function get_cleaned_wordpress_content($content)
    {
        return clean_wordpress_html($content, [
            'image_classes' => 'mx-auto my-4 rounded-lg shadow-md max-w-full h-auto',
            'wrap_images_in_figure' => true,
        ]);
    }
}

if (!function_exists('clean_wordpress_content_preserve_urls')) {
    /**
     * Nettoie le contenu WordPress en préservant les URLs d'images externes
     * Spécifiquement conçu pour les imports WordPress avec URLs complètes
     *
     * @param string|null $content Contenu HTML brut
     * @param array $options Options de nettoyage
     * @return string Contenu HTML nettoyé et stylisé
     */
    function clean_wordpress_content_preserve_urls($content, $options = [])
    {
        if (empty($content)) {
            return '';
        }

        // Options par défaut
        $options = array_merge([
            'remove_empty_paragraphs' => true,
            'remove_wp_comments' => true,
            'remove_wp_classes' => true,
            'make_images_responsive' => true,
            'wrap_images_in_figure' => false,
            'image_classes' => 'mx-auto my-6 rounded-lg shadow-lg max-w-full h-auto object-contain transition-all duration-300 hover:shadow-xl',
            'allowed_tags' => '<p><br><strong><b><em><i><u><s><del><a><ul><ol><li><blockquote><code><pre><h1><h2><h3><h4><h5><h6><img><figure><figcaption><table><thead><tbody><tr><th><td><div><span><hr><iframe>',
        ], $options);

        // 1. Supprimer les commentaires WordPress Gutenberg
        if ($options['remove_wp_comments']) {
            $content = preg_replace('/<!-- wp:(.+?) -->/', '', $content);
            $content = preg_replace('/<!-- \/wp:(.+?) -->/', '', $content);
        }

        // 2. Supprimer les paragraphes vides
        if ($options['remove_empty_paragraphs']) {
            $content = preg_replace('/<p>\s*<\/p>/i', '', $content);
            $content = preg_replace('/<p>\s*&nbsp;\s*<\/p>/i', '', $content);
            $content = preg_replace('/<p>\s*<br\s*\/?>\s*<\/p>/i', '', $content);
        }

        // 3. Traiter les images (SANS modifier les URLs)
        if ($options['make_images_responsive']) {
            $content = preg_replace_callback(
                '/<img([^>]*?)>/i',
                function ($matches) use ($options) {
                    $imgTag = $matches[0];
                    $attributes = $matches[1];
                    
                    // Extraire l'URL src (on la garde telle quelle)
                    preg_match('/src=["\']([^"\']+)["\']/i', $attributes, $srcMatch);
                    $src = $srcMatch[1] ?? '';
                    
                    // Extraire l'attribut alt s'il existe
                    preg_match('/alt=["\']([^"\']*)["\']/i', $attributes, $altMatch);
                    $alt = $altMatch[1] ?? '';
                    
                    // Supprimer les classes WordPress si demandé
                    if ($options['remove_wp_classes']) {
                        $attributes = preg_replace('/class=["\'](.*?)wp-image-\d+\s*(.*?)["\']/i', '', $attributes);
                        $attributes = preg_replace('/class=["\'](.*?)wp-block-image\s*(.*?)["\']/i', '', $attributes);
                        $attributes = preg_replace('/class=["\']\s*["\']/i', '', $attributes);
                    }
                    
                    // Reconstruire la balise img avec les nouvelles classes
                    $newImg = '<img src="' . $src . '"';
                    if ($alt) {
                        $newImg .= ' alt="' . htmlspecialchars($alt, ENT_QUOTES) . '"';
                    }
                    if ($options['image_classes']) {
                        $newImg .= ' class="' . $options['image_classes'] . '"';
                    }
                    $newImg .= ' loading="lazy"'; // Lazy loading pour performance
                    $newImg .= ' />';
                    
                    return $newImg;
                },
                $content
            );
        }

        // 4. Nettoyer les classes WordPress sur d'autres éléments
        if ($options['remove_wp_classes']) {
            $content = preg_replace('/class=["\'][^"\']*wp-block-[^"\']*["\']/i', '', $content);
            $content = preg_replace('/class=["\'][^"\']*wp-element-[^"\']*["\']/i', '', $content);
        }

        // 5. Autoriser seulement les balises sûres
        $content = strip_tags($content, $options['allowed_tags']);

        // 6. Nettoyer les attributs dangereux (XSS protection)
        $content = preg_replace('/<([a-z]+)([^>]*?)on\w+\s*=\s*["\'][^"\']*["\']([^>]*?)>/i', '<$1$2$3>', $content);
        
        // 7. Nettoyer les espaces multiples
        $content = preg_replace('/\s+/', ' ', $content);
        
        // 8. Nettoyer les lignes vides multiples
        $content = preg_replace('/(<\/p>\s*){2,}/', '</p>', $content);
        
        return trim($content);
    }
}

if (!function_exists('enhance_wordpress_media')) {
    /**
     * Améliore le contenu WordPress en convertissant les liens YouTube en vidéos embarquées
     * et en stylisant les liens de téléchargement
     *
     * @param string|null $content Contenu HTML
     * @return string Contenu amélioré
     */
    function enhance_wordpress_media($content)
    {
        if (empty($content)) {
            return '';
        }

        // 1a. Convertir les URLs YouTube en texte brut (pas dans des balises <a>)
        // Supporte: https://www.youtube.com/watch?v=ABC123 et https://youtu.be/ABC123
        $content = preg_replace_callback(
            '/(?<!href=["\'])(?<!src=["\'])(https?:\/\/(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11}))([^\s<]*)/i',
            function ($matches) {
                $videoId = $matches[1];
                
                // Log pour débogage (à retirer plus tard)
                error_log("YouTube URL détectée: Video ID = " . $videoId);
                
                return '
<div class="youtube-embed-wrapper my-8 max-w-4xl mx-auto">
    <div class="aspect-video w-full overflow-hidden rounded-xl shadow-2xl border-2 border-gray-200 bg-black relative">
        <iframe 
            class="w-full h-full"
            style="aspect-ratio: 16/9; min-height: 400px;"
            src="https://www.youtube.com/embed/' . htmlspecialchars($videoId, ENT_QUOTES) . '?rel=0&modestbranding=1" 
            title="Vidéo YouTube"
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen
            loading="lazy">
        </iframe>
    </div>
    <div class="mt-3 flex items-center justify-center gap-4">
        <p class="text-center text-sm text-gray-600 italic">
            <i class="fab fa-youtube text-red-600 mr-2"></i>Vidéo YouTube
        </p>
        <a href="https://www.youtube.com/watch?v=' . htmlspecialchars($videoId, ENT_QUOTES) . '" 
           target="_blank" 
           rel="noopener noreferrer"
           class="inline-flex items-center text-xs text-iri-primary hover:text-iri-secondary font-medium transition-colors">
            <i class="fas fa-external-link-alt mr-1"></i>
            Voir sur YouTube
        </a>
    </div>
</div>';
            },
            $content
        );

        // 1b. Convertir les liens YouTube dans des balises <a>
        $content = preg_replace_callback(
            '/<a[^>]*href=["\']https?:\/\/(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})[^"\']*["\'][^>]*>(.*?)<\/a>/i',
            function ($matches) {
                $videoId = $matches[1];
                $linkText = strip_tags($matches[2]);
                
                return '
                <div class="youtube-embed-wrapper my-8">
                    <div class="relative pb-[56.25%] h-0 overflow-hidden rounded-xl shadow-2xl border-2 border-gray-200">
                        <iframe 
                            class="absolute top-0 left-0 w-full h-full"
                            src="https://www.youtube.com/embed/' . $videoId . '?rel=0&modestbranding=1" 
                            title="' . htmlspecialchars($linkText, ENT_QUOTES) . '"
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen
                            loading="lazy">
                        </iframe>
                    </div>
                    <p class="text-center mt-3 text-sm text-gray-600 italic">
                        <i class="fab fa-youtube text-red-600 mr-2"></i>' . htmlspecialchars($linkText, ENT_QUOTES) . '
                    </p>
                </div>';
            },
            $content
        );

        // 2. Styliser les liens de téléchargement (PDF, DOC, XLS, etc.)
        $content = preg_replace_callback(
            '/<a[^>]*href=["\']([^"\']*\.(pdf|doc|docx|xls|xlsx|ppt|pptx|zip|rar))["\'][^>]*>(.*?)<\/a>/i',
            function ($matches) {
                $url = $matches[1];
                $extension = strtoupper($matches[2]);
                $linkText = strip_tags($matches[3]);
                
                // Icônes selon l'extension
                $icons = [
                    'PDF' => 'fa-file-pdf text-red-600',
                    'DOC' => 'fa-file-word text-blue-600',
                    'DOCX' => 'fa-file-word text-blue-600',
                    'XLS' => 'fa-file-excel text-green-600',
                    'XLSX' => 'fa-file-excel text-green-600',
                    'PPT' => 'fa-file-powerpoint text-orange-600',
                    'PPTX' => 'fa-file-powerpoint text-orange-600',
                    'ZIP' => 'fa-file-archive text-gray-600',
                    'RAR' => 'fa-file-archive text-gray-600',
                ];
                
                $icon = $icons[$extension] ?? 'fa-file text-gray-600';
                
                return '
                <div class="download-link-wrapper my-6">
                    <a href="' . htmlspecialchars($url, ENT_QUOTES) . '" 
                       class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-white border-2 border-gray-200 rounded-xl hover:shadow-lg hover:border-iri-primary transition-all duration-300 group"
                       download
                       target="_blank">
                        <div class="flex-shrink-0 w-12 h-12 bg-white rounded-lg flex items-center justify-center mr-4 shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas ' . $icon . ' text-2xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-iri-primary transition-colors truncate">
                                ' . htmlspecialchars($linkText, ENT_QUOTES) . '
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-download mr-1"></i>
                                Télécharger (' . $extension . ')
                            </p>
                        </div>
                        <div class="flex-shrink-0 ml-4">
                            <i class="fas fa-arrow-down text-gray-400 group-hover:text-iri-primary group-hover:animate-bounce transition-colors"></i>
                        </div>
                    </a>
                </div>';
            },
            $content
        );

        // 3. Styliser les liens externes (qui ne sont pas des téléchargements)
        $content = preg_replace_callback(
            '/<a[^>]*href=["\']((https?:\/\/)[^"\']+)["\']([^>]*)>(.*?)<\/a>/i',
            function ($matches) {
                $url = $matches[1];
                $attributes = $matches[3];
                $linkText = $matches[4];
                
                // Ignorer si c'est déjà traité (YouTube ou fichier)
                if (strpos($url, 'youtube.com') !== false || 
                    strpos($url, 'youtu.be') !== false ||
                    preg_match('/\.(pdf|doc|docx|xls|xlsx|ppt|pptx|zip|rar)$/i', $url)) {
                    return $matches[0];
                }
                
                // Vérifier si c'est un lien externe
                $currentDomain = $_SERVER['HTTP_HOST'] ?? '';
                $isExternal = !empty($currentDomain) && strpos($url, $currentDomain) === false;
                
                if ($isExternal) {
                    return '<a href="' . htmlspecialchars($url, ENT_QUOTES) . '" 
                               class="external-link inline-flex items-center text-iri-primary hover:text-iri-secondary font-medium underline decoration-2 underline-offset-4 hover:decoration-iri-secondary transition-all"
                               target="_blank"
                               rel="noopener noreferrer" ' . $attributes . '>
                               ' . $linkText . '
                               <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                            </a>';
                }
                
                return $matches[0];
            },
            $content
        );

        return $content;
    }
}
