@props([
    'title',
    'description',
    'image' => null,
    'url',
    'imageAlt' => null,
    'publishedAt' => null,
    'modifiedAt' => null,
    'author' => null,
    'section' => null,
    'tags' => []
])

@php
use App\Support\UrlHelper;
use Illuminate\Support\Facades\Storage;

// Construction de l'URL absolue de l'image
$imageUrl = null;
if ($image) {
    // Si l'image est un chemin relatif ou storage
    if (str_starts_with($image, 'storage/') || !str_starts_with($image, 'http')) {
        // Utiliser Storage pour l'URL publique
        $imagePath = str_replace('storage/', '', $image);
        if (Storage::disk('public')->exists($imagePath)) {
            $imageUrl = UrlHelper::absolute(Storage::url($imagePath));
        }
    } else {
        // L'image est déjà une URL complète
        $imageUrl = UrlHelper::absolute($image);
    }
}

// Image de fallback si pas d'image
if (!$imageUrl) {
    $defaultImage = config('share.default_image');
    if ($defaultImage) {
        $imageUrl = UrlHelper::absolute($defaultImage);
    }
}

// URL canonique absolue
$canonicalUrl = UrlHelper::absolute($url);

// Alt text pour l'image
$finalImageAlt = $imageAlt ?: $title;

// Configuration du site
$siteName = config('share.site.name', config('app.name'));
$imageWidth = config('share.social_image.width', 1200);
$imageHeight = config('share.social_image.height', 630);
@endphp

{{-- Open Graph Meta Tags --}}
<meta property="og:type" content="article">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
@if($imageUrl)
<meta property="og:image" content="{{ $imageUrl }}">
<meta property="og:image:width" content="{{ $imageWidth }}">
<meta property="og:image:height" content="{{ $imageHeight }}">
<meta property="og:image:alt" content="{{ $finalImageAlt }}">
@endif
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:site_name" content="{{ $siteName }}">

{{-- Article Meta Tags --}}
@if($publishedAt)
<meta property="article:published_time" content="{{ $publishedAt }}">
@endif
@if($modifiedAt)
<meta property="article:modified_time" content="{{ $modifiedAt }}">
@endif
@if($author)
<meta property="article:author" content="{{ $author }}">
@endif
@if($section)
<meta property="article:section" content="{{ $section }}">
@endif
@if(is_array($tags) && count($tags) > 0)
@foreach($tags as $tag)
<meta property="article:tag" content="{{ $tag }}">
@endforeach
@endif

{{-- Twitter Card Meta Tags --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
@if($imageUrl)
<meta name="twitter:image" content="{{ $imageUrl }}">
<meta name="twitter:image:alt" content="{{ $finalImageAlt }}">
@endif

{{-- Canonical Link --}}
<link rel="canonical" href="{{ $canonicalUrl }}">
