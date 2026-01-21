@props(['content', 'limit' => 150, 'class' => ''])

@php
    // Nettoyer le contenu HTML et limiter le nombre de caractères
    $cleanContent = strip_tags($content ?? '');
    $truncated = Str::limit($cleanContent, $limit);
    
    // Si le contenu original contient des balises HTML, on peut utiliser une approche plus sophistiquée
    if (($content ?? '') !== $cleanContent && strlen($cleanContent) > $limit) {
        // Préserver certaines balises importantes
        $allowedTags = '<strong><em><b><i><u><br>';
        $contentWithTags = strip_tags($content ?? '', $allowedTags);
        
        // Tronquer en préservant les mots complets
        $words = explode(' ', $contentWithTags);
        $result = '';
        $charCount = 0;
        
        foreach ($words as $word) {
            $wordLength = strlen(strip_tags($word));
            if ($charCount + $wordLength > $limit) {
                break;
            }
            $result .= $word . ' ';
            $charCount += $wordLength + 1;
        }
        
        $displayContent = trim($result) . '...';
    } else {
        $displayContent = $truncated;
    }
@endphp

<div class="{{ $class }}">
    {!! $displayContent !!}
</div>
