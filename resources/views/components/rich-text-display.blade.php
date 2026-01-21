{{-- Composant pour afficher le contenu riche CKEditor et WordPress --}}
@props([
    'content' => '',
    'class' => '',
    'title' => null,
    'cleanWordPress' => true
])

@php
    // Nettoyer le contenu WordPress si activé
    $processedContent = $content;
    if ($cleanWordPress && !empty($content)) {
        $processedContent = clean_wordpress_content_preserve_urls($content, [
            'remove_empty_paragraphs' => true,
            'remove_wp_comments' => true,
            'remove_wp_classes' => true,
            'make_images_responsive' => true,
            'image_classes' => 'mx-auto my-6 rounded-lg shadow-lg max-w-full h-auto object-cover transition-all duration-300 hover:shadow-xl',
        ]);
    }
@endphp

<div {{ $attributes->merge(['class' => 'rich-text-content article-content ' . $class]) }}>
    @if($title)
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="h-5 w-5 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            {{ $title }}
        </h3>
    @endif
    
    <div class="content-body">
        {!! $processedContent !!}
    </div>
</div>

<style>
/* Styles spécifiques pour le contenu CKEditor */
.rich-text-content {
    /* Tables CKEditor */
    --tw-prose-tables: theme(colors.gray.700);
    --tw-prose-table-heading: theme(colors.gray.900);
    --tw-prose-table-body: theme(colors.gray.700);
    --tw-prose-table-borders: theme(colors.gray.300);
}

.rich-text-content table {
    @apply w-full border-collapse border border-gray-300 mb-4;
}

.rich-text-content table th {
    @apply bg-gray-100 border border-gray-300 px-4 py-2 text-left font-semibold text-gray-800;
}

.rich-text-content table td {
    @apply border border-gray-300 px-4 py-2 text-gray-700;
}

.rich-text-content table tr:nth-child(even) {
    @apply bg-gray-50;
}

/* Listes CKEditor */
.rich-text-content ul {
    @apply list-disc pl-6 mb-4 space-y-1;
}

.rich-text-content ol {
    @apply list-decimal pl-6 mb-4 space-y-1;
}

.rich-text-content li {
    @apply text-gray-700 leading-relaxed;
}

/* Images CKEditor - Styles avancés */
.rich-text-content img {
    @apply rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl;
    max-width: 100%;
    height: auto;
    border: 2px solid rgba(255, 255, 255, 0.8);
}

.rich-text-content figure {
    @apply mb-8 overflow-hidden;
    position: relative;
}

.rich-text-content figure img {
    @apply transition-transform duration-300 hover:scale-105;
}

.rich-text-content figcaption {
    @apply text-sm text-gray-600 mt-3 px-2 py-1 bg-gray-50 rounded italic font-medium border-l-4 border-iri-primary;
}

/* Images avec alignement et habillage de texte */
.rich-text-content .image-style-align-left {
    float: left;
    margin: 0 24px 16px 0;
    max-width: 50%;
    min-width: 200px;
}

.rich-text-content .image-style-align-right {
    float: right;
    margin: 0 0 16px 24px;
    max-width: 50%;
    min-width: 200px;
}

.rich-text-content .image-style-align-center {
    display: block;
    margin: 24px auto;
    max-width: 80%;
    text-align: center;
}

/* Tailles d'images prédéfinies */
.rich-text-content .image-style-side {
    max-width: 300px;
    float: left;
    margin: 0 20px 16px 0;
}

.rich-text-content .image-style-side-right {
    max-width: 300px;
    float: right;
    margin: 0 0 16px 20px;
}

.rich-text-content .image-style-full-width {
    width: 100%;
    max-width: 100%;
    margin: 32px 0;
}

.rich-text-content .image-style-block-align-left {
    display: block;
    float: left;
    margin: 0 24px 16px 0;
    max-width: 40%;
}

.rich-text-content .image-style-block-align-right {
    display: block;
    float: right;
    margin: 0 0 16px 24px;
    max-width: 40%;
}

/* Styles pour les images WordPress importées */
.rich-text-content .wp-block-image {
    @apply mb-6;
}

.rich-text-content .wp-block-image.alignleft {
    float: left;
    margin: 0 24px 16px 0;
    max-width: 45%;
}

.rich-text-content .wp-block-image.alignright {
    float: right;
    margin: 0 0 16px 24px;
    max-width: 45%;
}

.rich-text-content .wp-block-image.aligncenter {
    display: block;
    margin: 24px auto;
    text-align: center;
}

.rich-text-content .wp-block-image.size-large {
    max-width: 90%;
}

.rich-text-content .wp-block-image.size-medium {
    max-width: 70%;
}

.rich-text-content .wp-block-image.size-thumbnail {
    max-width: 150px;
}

/* Galeries d'images */
.rich-text-content .wp-block-gallery {
    @apply grid gap-4 mb-8;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
}

.rich-text-content .wp-block-gallery img {
    @apply w-full h-48 object-cover;
}

/* Clearfix pour éviter les problèmes de flottement */
.rich-text-content::after,
.rich-text-content p::after {
    content: "";
    display: table;
    clear: both;
}

/* Conteneurs avec images flottantes */
.rich-text-content .with-floated-image {
    overflow: auto;
    zoom: 1; /* IE fix */
}

/* Amélioration de la lisibilité */
.rich-text-content {
    line-height: 1.7;
    word-spacing: 0.1em;
}

/* Styles pour les légendes et crédits photo */
.rich-text-content .image-credit {
    @apply text-xs text-gray-500 mt-1 text-right italic;
}

.rich-text-content .image-source {
    @apply text-xs text-gray-400 mt-1 text-center;
}

/* Hover effects pour les images */
.rich-text-content img:hover {
    @apply shadow-2xl;
    transform: translateY(-2px);
}

/* Styles pour les images avec bordures spéciales */
.rich-text-content .bordered-image {
    @apply border-4 border-white shadow-xl;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(0, 0, 0, 0.05);
}

/* Conteneur pour images avec texte en colonnes */
.rich-text-content .text-with-image {
    @apply grid gap-6 items-start mb-8;
    grid-template-columns: 1fr 300px;
}

.rich-text-content .text-with-image.reverse {
    grid-template-columns: 300px 1fr;
}

.rich-text-content .text-with-image img {
    @apply w-full rounded-lg shadow-lg;
    margin: 0;
}

@media (max-width: 768px) {
    .rich-text-content .text-with-image,
    .rich-text-content .text-with-image.reverse {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}

/* Liens CKEditor */
.rich-text-content a {
    @apply text-iri-primary hover:text-iri-secondary transition-colors duration-200;
}

.rich-text-content a:hover {
    @apply underline;
}

/* Code blocks CKEditor */
.rich-text-content pre {
    @apply bg-gray-100 border rounded-lg p-4 overflow-x-auto mb-4;
}

.rich-text-content code {
    @apply text-sm font-mono;
}

/* Blocs de citation */
.rich-text-content blockquote {
    @apply border-l-4 border-iri-primary bg-gray-50 pl-4 pr-4 py-2 mb-4 italic;
}

/* Titres dans le contenu */
.rich-text-content h1 {
    @apply text-2xl font-bold text-gray-800 mb-4 mt-6;
}

.rich-text-content h2 {
    @apply text-xl font-bold text-gray-800 mb-3 mt-5;
}

.rich-text-content h3 {
    @apply text-lg font-semibold text-gray-800 mb-2 mt-4;
}

.rich-text-content h4 {
    @apply text-base font-semibold text-gray-800 mb-2 mt-3;
}

/* Paragraphes */
.rich-text-content p {
    @apply text-gray-700 leading-relaxed mb-4;
    text-align: justify;
    hyphens: auto;
}

.rich-text-content p:has(img) {
    @apply mb-6;
}

/* Paragraphes avec images flottantes */
.rich-text-content p img[style*="float: left"],
.rich-text-content p img.alignleft {
    float: left;
    margin: 0 20px 16px 0;
    max-width: 45%;
}

.rich-text-content p img[style*="float: right"],
.rich-text-content p img.alignright {
    float: right;
    margin: 0 0 16px 20px;
    max-width: 45%;
}

/* Espacement intelligent autour des images */
.rich-text-content p:has(img[style*="float"]) {
    overflow: auto;
    margin-bottom: 20px;
}

.rich-text-content p + p:has(img) {
    margin-top: 8px;
}

/* Styles pour les éléments spéciaux CKEditor */
.rich-text-content .text-big {
    @apply text-lg;
}

.rich-text-content .text-small {
    @apply text-sm;
}

.rich-text-content .text-tiny {
    @apply text-xs;
}

.rich-text-content .text-huge {
    @apply text-xl;
}

/* Alignements */
.rich-text-content .ck-align-left {
    @apply text-left;
}

.rich-text-content .ck-align-center {
    @apply text-center;
}

.rich-text-content .ck-align-right {
    @apply text-right;
}

.rich-text-content .ck-align-justify {
    @apply text-justify;
}

/* Styles pour les mentions et hashtags si utilisés */
.rich-text-content .mention {
    @apply bg-blue-100 text-blue-800 px-2 py-1 rounded;
}

/* Responsive pour mobile */
@media (max-width: 768px) {
    .rich-text-content {
        @apply text-sm;
    }
    
    .rich-text-content table {
        @apply text-xs;
    }
    
    .rich-text-content table th,
    .rich-text-content table td {
        @apply px-2 py-1;
    }
    
    /* Sur mobile, annuler les flottements et centrer les images */
    .rich-text-content img,
    .rich-text-content .image-style-align-left,
    .rich-text-content .image-style-align-right,
    .rich-text-content .image-style-side,
    .rich-text-content .image-style-side-right,
    .rich-text-content .image-style-block-align-left,
    .rich-text-content .image-style-block-align-right,
    .rich-text-content .wp-block-image.alignleft,
    .rich-text-content .wp-block-image.alignright,
    .rich-text-content p img[style*="float"] {
        float: none !important;
        display: block !important;
        margin: 16px auto !important;
        max-width: 95% !important;
        width: auto !important;
    }
    
    .rich-text-content .wp-block-gallery {
        grid-template-columns: 1fr;
    }
    
    .rich-text-content p {
        text-align: left;
    }
}

@media (max-width: 480px) {
    .rich-text-content img {
        max-width: 100% !important;
        margin: 12px auto !important;
    }
    
    .rich-text-content figure {
        @apply mb-4;
    }
    
    .rich-text-content figcaption {
        @apply text-xs px-2 py-1;
    }
}
</style>
