<!-- Breadcrumb Component -->
<nav class="bg-gradient-to-r from-iri-primary via-iri-secondary to-iri-accent py-4" aria-label="Breadcrumb">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ url('/') }}" class="text-white/70 hover:text-white transition-colors duration-200">
                    <i class="fas fa-home mr-1"></i>
                    Accueil
                </a>
            </li>
            
            @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
                @foreach($breadcrumbs as $breadcrumb)
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-white/50 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        @if($loop->last)
                            <span class="text-white font-medium">{{ $breadcrumb['title'] }}</span>
                        @else
                            <a href="{{ $breadcrumb['url'] }}" class="text-white/70 hover:text-white transition-colors duration-200">
                                {{ $breadcrumb['title'] }}
                            </a>
                        @endif
                    </li>
                @endforeach
            @endif
        </ol>
    </div>
</nav>
