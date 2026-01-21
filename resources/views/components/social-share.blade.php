@props(['url' => '', 'title' => '', 'size' => 'md', 'style' => 'hero'])

@php
    $socialLinks = App\Models\SocialLink::active()->ordered()->get();
    $currentUrl = $url ?: request()->fullUrl();
    $currentTitle = $title ?: (isset($actualite) ? $actualite->titre : '');
    
    $sizeClasses = [
        'sm' => 'p-2 text-sm',
        'md' => 'p-2',
        'lg' => 'p-3 text-lg'
    ];
    
    $styleClasses = [
        'hero' => 'bg-white/20 backdrop-blur-sm border border-white/30 text-white hover:bg-white/30',
        'light' => 'bg-gray-100 text-gray-600 hover:bg-gray-200',
        'primary' => 'bg-iri-primary text-white hover:bg-iri-secondary'
    ];
    
    $baseClasses = $sizeClasses[$size] . ' ' . $styleClasses[$style] . ' rounded-lg transition-all duration-200';
@endphp

@if($socialLinks->count() > 0)
    <div class="flex items-center gap-2">
        @foreach($socialLinks as $social)
            @php
                $shareUrl = '';
                switch($social->platform) {
                    case 'facebook':
                        $shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($currentUrl);
                        break;
                    case 'twitter':
                        $shareUrl = 'https://twitter.com/intent/tweet?url=' . urlencode($currentUrl) . '&text=' . urlencode($currentTitle);
                        break;
                    case 'linkedin':
                        $shareUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($currentUrl);
                        break;
                    default:
                        $shareUrl = $social->url;
                }
            @endphp
            
            <a href="{{ $shareUrl }}" 
               target="_blank" 
               rel="noopener noreferrer"
               class="{{ $baseClasses }}"
               title="Partager sur {{ $social->name }}">
                <i class="{{ $social->icon }}"></i>
            </a>
        @endforeach
    </div>
@endif
