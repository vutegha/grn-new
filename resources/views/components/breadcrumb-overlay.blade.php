@props(['items' => []])

<nav class="flex items-center space-x-2 text-sm text-white/90" aria-label="Breadcrumb">
    <a href="{{ route('site.home') }}" class="hover:text-white transition-colors">
        <i class="fas fa-home mr-1"></i> Accueil
    </a>
    
    @foreach($items as $item)
        <span class="text-white/60">›</span>
        @if($loop->last)
            <span class="text-white font-medium">{{ $item['title'] }}</span>
        @else
            <a href="{{ $item['url'] }}" class="hover:text-white transition-colors">
                {{ $item['title'] }}
            </a>
        @endif
    @endforeach
</nav>
