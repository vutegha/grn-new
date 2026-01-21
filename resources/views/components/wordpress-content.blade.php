{{-- 
    Composant pour afficher le contenu HTML WordPress avec style amélioré
    
    Usage:
    <x-wordpress-content :content="$actualite->texte" />
    
    Ou avec attributs personnalisés:
    <x-wordpress-content 
        :content="$actualite->texte" 
        max-width="max-w-4xl"
        class="my-custom-class" 
    />
--}}

@props([
    'content' => '',
    'maxWidth' => 'max-w-3xl',
])

@php
    // Nettoyer le contenu WordPress et corriger les URLs d'images
    $processedContent = clean_wordpress_content($content);
    $processedContent = fix_wordpress_image_urls($processedContent);
    // Améliorer les médias (YouTube, liens de téléchargement)
    $processedContent = enhance_wordpress_media($processedContent);
@endphp

<div {{ $attributes->merge(['class' => 'wordpress-content-wrapper ' . $maxWidth . ' mx-auto px-4 sm:px-6 lg:px-8']) }}>
    <div class="wp-content prose prose-lg max-w-none">
        {!! $processedContent !!}
    </div>
</div>

<style>
/* ========================================
   STYLES POUR CONTENU WORDPRESS
   ======================================== */

.wp-content {
    color: #1f2937;
    line-height: 1.8;
    font-size: 1.0625rem; /* 17px */
}

/* ========================================
   PARAGRAPHES
   ======================================== */
.wp-content p {
    margin-top: 1.25em;
    margin-bottom: 1.25em;
    line-height: 1.8;
    color: #374151;
}

/* Premier paragraphe plus grand */
.wp-content > p:first-of-type {
    font-size: 1.125rem;
    line-height: 1.75;
    color: #1f2937;
}

/* Paragraphes vides - réduire l'espace */
.wp-content p:empty {
    margin: 0.5em 0;
    min-height: 0;
}

/* Supprimer les marges excessives entre paragraphes consécutifs vides */
.wp-content p:empty + p:empty {
    display: none;
}

/* ========================================
   TITRES
   ======================================== */
.wp-content h1,
.wp-content h2,
.wp-content h3,
.wp-content h4,
.wp-content h5,
.wp-content h6,
.wp-content .wp-block-heading {
    margin-top: 2em;
    margin-bottom: 0.75em;
    font-weight: 700;
    line-height: 1.3;
    color: #111827;
}

.wp-content h1 { font-size: 2.25rem; }
.wp-content h2 { font-size: 1.875rem; }
.wp-content h3 { font-size: 1.5rem; }
.wp-content h4 { font-size: 1.25rem; }
.wp-content h5 { font-size: 1.125rem; }
.wp-content h6 { font-size: 1rem; }

/* Premier titre sans marge top */
.wp-content > h1:first-child,
.wp-content > h2:first-child,
.wp-content > h3:first-child,
.wp-content > h4:first-child {
    margin-top: 0;
}

/* ========================================
   LISTES
   ======================================== */
.wp-content ul,
.wp-content ol {
    margin-top: 1.5em;
    margin-bottom: 1.5em;
    padding-left: 1.75em;
}

.wp-content ul {
    list-style-type: disc;
}

.wp-content ol {
    list-style-type: decimal;
}

.wp-content li {
    margin-top: 0.5em;
    margin-bottom: 0.5em;
    line-height: 1.75;
    color: #374151;
}

/* Listes imbriquées */
.wp-content ul ul,
.wp-content ol ul {
    list-style-type: circle;
    margin-top: 0.5em;
    margin-bottom: 0.5em;
}

.wp-content ul ul ul,
.wp-content ol ul ul {
    list-style-type: square;
}

.wp-content ol ol {
    list-style-type: lower-alpha;
}

.wp-content ol ol ol {
    list-style-type: lower-roman;
}

/* Espacement entre éléments de liste */
.wp-content li > p {
    margin-top: 0.5em;
    margin-bottom: 0.5em;
}

/* ========================================
   LIENS
   ======================================== */
.wp-content a {
    color: #0891b2;
    text-decoration: underline;
    text-decoration-color: rgba(8, 145, 178, 0.3);
    text-underline-offset: 2px;
    transition: all 0.2s ease;
}

.wp-content a:hover {
    color: #0e7490;
    text-decoration-color: rgba(14, 116, 144, 0.6);
    text-underline-offset: 4px;
}

.wp-content a:focus {
    outline: 2px solid #0891b2;
    outline-offset: 2px;
    border-radius: 2px;
}

/* ========================================
   IMAGES - BLOCS WORDPRESS
   ======================================== */
.wp-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Bloc image WordPress */
.wp-content .wp-block-image {
    margin: 2.5em 0;
    text-align: center;
}

.wp-content .wp-block-image img {
    margin: 0 auto;
}

/* Images alignées */
.wp-content .wp-block-image.alignleft,
.wp-content .alignleft {
    float: left;
    margin: 0.5em 2em 1.5em 0;
    max-width: 50%;
}

.wp-content .wp-block-image.alignright,
.wp-content .alignright {
    float: right;
    margin: 0.5em 0 1.5em 2em;
    max-width: 50%;
}

.wp-content .wp-block-image.aligncenter,
.wp-content .aligncenter {
    display: block;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
}

/* Tailles d'images WordPress */
.wp-content .wp-block-image.size-large {
    max-width: 100%;
}

.wp-content .wp-block-image.size-medium {
    max-width: 600px;
}

.wp-content .wp-block-image.size-thumbnail {
    max-width: 300px;
}

/* ========================================
   FIGURES ET LÉGENDES
   ======================================== */
.wp-content figure {
    margin: 2.5em 0;
    text-align: center;
}

.wp-content figcaption,
.wp-content .wp-caption-text {
    margin-top: 0.75em;
    font-size: 0.875rem;
    color: #6b7280;
    font-style: italic;
    text-align: center;
    padding: 0.5em 1em;
    background-color: #f9fafb;
    border-left: 3px solid #0891b2;
    border-radius: 0.25rem;
}

/* ========================================
   GALERIES WORDPRESS
   ======================================== */
.wp-content .wp-block-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin: 2.5em 0;
    padding: 0;
    list-style: none;
}

.wp-content .wp-block-gallery img {
    width: 100%;
    height: auto;
    object-fit: contain;
    border-radius: 0.5rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.wp-content .wp-block-gallery img:hover {
    transform: scale(1.02);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Galerie classique WordPress */
.wp-content .gallery {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin: 2.5em 0;
}

.wp-content .gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 0.5rem;
}

.wp-content .gallery-item img {
    width: 100%;
    height: auto;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.wp-content .gallery-item:hover img {
    transform: scale(1.05);
}

/* ========================================
   CITATIONS
   ======================================== */
.wp-content blockquote,
.wp-content .wp-block-quote {
    margin: 2em 0;
    padding: 1.25em 1.5em;
    border-left: 4px solid #0891b2;
    background: linear-gradient(to right, #f0fdfa, #ffffff);
    border-radius: 0.375rem;
    font-style: italic;
    color: #374151;
    position: relative;
}

.wp-content blockquote p,
.wp-content .wp-block-quote p {
    margin-top: 0.5em;
    margin-bottom: 0.5em;
}

.wp-content blockquote p:first-child::before {
    content: '"';
    font-size: 2em;
    color: #0891b2;
    opacity: 0.3;
    position: absolute;
    left: 0.5em;
    top: 0.25em;
    font-family: Georgia, serif;
}

.wp-content blockquote cite {
    display: block;
    margin-top: 1em;
    font-size: 0.875rem;
    color: #6b7280;
    font-style: normal;
}

/* ========================================
   CODE
   ======================================== */
.wp-content code {
    background-color: #f3f4f6;
    color: #ef4444;
    padding: 0.2em 0.4em;
    border-radius: 0.25rem;
    font-size: 0.875em;
    font-family: 'Monaco', 'Courier New', monospace;
}

.wp-content pre {
    margin: 2em 0;
    padding: 1.25em;
    background-color: #1f2937;
    color: #f9fafb;
    border-radius: 0.5rem;
    overflow-x: auto;
    font-size: 0.875rem;
    line-height: 1.7;
}

.wp-content pre code {
    background-color: transparent;
    color: inherit;
    padding: 0;
    font-size: inherit;
}

/* ========================================
   TABLEAUX
   ======================================== */
.wp-content table {
    width: 100%;
    margin: 2em 0;
    border-collapse: collapse;
    font-size: 0.9375rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}

.wp-content thead {
    background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
}

.wp-content th {
    padding: 0.875rem 1rem;
    text-align: left;
    font-weight: 600;
    color: #111827;
    border-bottom: 2px solid #e5e7eb;
}

.wp-content td {
    padding: 0.875rem 1rem;
    color: #374151;
    border-bottom: 1px solid #e5e7eb;
}

.wp-content tbody tr:hover {
    background-color: #f9fafb;
}

.wp-content tbody tr:last-child td {
    border-bottom: none;
}

/* ========================================
   SÉPARATEURS
   ======================================== */
.wp-content hr,
.wp-content .wp-block-separator {
    margin: 3em auto;
    border: none;
    border-top: 2px solid #e5e7eb;
    max-width: 60%;
}

/* ========================================
   FORMATAGE DE TEXTE
   ======================================== */
.wp-content strong,
.wp-content b {
    font-weight: 700;
    color: #111827;
}

.wp-content em,
.wp-content i {
    font-style: italic;
    color: #4b5563;
}

.wp-content u {
    text-decoration: underline;
    text-underline-offset: 2px;
}

.wp-content s,
.wp-content del {
    text-decoration: line-through;
    color: #9ca3af;
}

.wp-content mark {
    background-color: #fef3c7;
    color: #92400e;
    padding: 0.125em 0.25em;
    border-radius: 0.125rem;
}

/* ========================================
   BOUTONS WORDPRESS
   ======================================== */
.wp-content .wp-block-button {
    margin: 1.5em 0;
}

.wp-content .wp-block-button__link {
    display: inline-block;
    padding: 0.75em 1.5em;
    background-color: #0891b2;
    color: white !important;
    text-decoration: none !important;
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}

.wp-content .wp-block-button__link:hover {
    background-color: #0e7490;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* ========================================
   CLEARFIX POUR IMAGES FLOTTANTES
   ======================================== */
.wp-content::after,
.wp-content p::after {
    content: "";
    display: table;
    clear: both;
}

/* ========================================
   COMMENTAIRES WORDPRESS (masqués)
   ======================================== */
.wp-content .wp-block-comment {
    display: none;
}

/* ========================================
   RESPONSIVE
   ======================================== */
@media (max-width: 768px) {
    .wp-content {
        font-size: 1rem;
    }
    
    .wp-content h1 { font-size: 1.875rem; }
    .wp-content h2 { font-size: 1.5rem; }
    .wp-content h3 { font-size: 1.25rem; }
    
    .wp-content .wp-block-image.alignleft,
    .wp-content .alignleft,
    .wp-content .wp-block-image.alignright,
    .wp-content .alignright {
        float: none;
        max-width: 100%;
        margin: 1.5em 0;
    }
    
    .wp-content .wp-block-gallery {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .wp-content table {
        font-size: 0.875rem;
    }
    
    .wp-content th,
    .wp-content td {
        padding: 0.625rem 0.75rem;
    }
}

@media (max-width: 480px) {
    .wp-content {
        font-size: 0.9375rem;
    }
    
    .wp-content .wp-block-gallery {
        grid-template-columns: 1fr;
    }
}

/* ========================================
   IMPRESSION
   ======================================== */
@media print {
    .wp-content {
        color: #000;
        font-size: 12pt;
    }
    
    .wp-content a {
        color: #000;
        text-decoration: underline;
    }
    
    .wp-content img {
        max-width: 100%;
        page-break-inside: avoid;
    }
    
    .wp-content blockquote {
        page-break-inside: avoid;
    }
}

/* ========================================
   VIDÉOS YOUTUBE EMBARQUÉES
   ======================================== */
.youtube-embed-wrapper {
    margin: 2.5em 0;
}

.youtube-embed-wrapper .relative {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2px;
}

.youtube-embed-wrapper iframe {
    border-radius: 0.75rem;
}

.youtube-embed-wrapper:hover .relative {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* ========================================
   LIENS DE TÉLÉCHARGEMENT
   ======================================== */
.download-link-wrapper {
    margin: 1.5em 0;
}

.download-link-wrapper a {
    text-decoration: none !important;
}

.download-link-wrapper a:hover {
    transform: translateY(-2px);
}

/* Animation bounce pour l'icône de téléchargement */
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

.group:hover .animate-bounce {
    animation: bounce 1s ease-in-out infinite;
}

/* ========================================
   LIENS EXTERNES
   ======================================== */
.external-link {
    text-decoration: none;
    transition: all 0.3s ease;
}

.external-link:hover {
    text-decoration: none;
}

/* ========================================
   RESPONSIVE YOUTUBE
   ======================================== */
@media (max-width: 640px) {
    .youtube-embed-wrapper {
        margin: 1.5em -1rem;
    }
    
    .download-link-wrapper a {
        padding: 1rem;
    }
}
</style>
