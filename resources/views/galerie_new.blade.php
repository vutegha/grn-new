@extends('layouts.iri')
@section('title', 'Galerie')

@section('content')
<!-- Breadcrumb -->
@include('partials.breadcrumb', [
    'breadcrumbs' => [
        ['title' => 'Galerie', 'url' => null]
    ]
])

<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 drop-shadow-2xl">
                Galerie
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed drop-shadow-lg">
                Découvrez nos activités à travers notre collection d'images et de vidéos
            </p>
        </div>
    </section>

    <!-- Gallery Content -->
    <section class="py-16" x-data="{ activeFilter: 'all', lightboxOpen: false, currentMedia: null }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter Buttons -->
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <button @click="activeFilter = 'all'" 
                        :class="{ 
                            'bg-gradient-to-r from-iri-primary to-iri-secondary text-white shadow-lg': activeFilter === 'all',
                            'bg-white text-gray-700 hover:bg-gray-50': activeFilter !== 'all'
                        }"
                        class="px-6 py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105 border border-gray-200">
                    <i class="fas fa-th mr-2"></i>
                    Tout afficher
                </button>
                
                <button @click="activeFilter = 'Image'" 
                        :class="{ 
                            'bg-gradient-to-r from-iri-accent to-iri-gold text-white shadow-lg': activeFilter === 'Image',
                            'bg-white text-gray-700 hover:bg-gray-50': activeFilter !== 'Image'
                        }"
                        class="px-6 py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105 border border-gray-200">
                    <i class="fas fa-image mr-2"></i>
                    Images
                </button>
                
                <button @click="activeFilter = 'Vidéo'" 
                        :class="{ 
                            'bg-gradient-to-r from-iri-secondary to-iri-primary text-white shadow-lg': activeFilter === 'Vidéo',
                            'bg-white text-gray-700 hover:bg-gray-50': activeFilter !== 'Vidéo'
                        }"
                        class="px-6 py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105 border border-gray-200">
                    <i class="fas fa-play mr-2"></i>
                    Vidéos
                </button>
            </div>

            <!-- Media Grid -->
            @if(isset($medias) && optional($medias)->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($medias as $index => $media)
                        @php
                            $file = $media->medias;
                            $isVideo = Str::endsWith(strtolower($file), ['.mp4', '.webm', '.ogg', '.mov', '.m4v', '.qt']);
                            $url = asset('storage/' . $file);
                            $type = $isVideo ? 'Vidéo' : 'Image';
                        @endphp
                        
                        <div class="group media-item" 
                             data-type="{{ $type }}"
                             x-show="activeFilter === 'all' || activeFilter === '{{ $type }}'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100">
                            
                            <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100 cursor-pointer"
                                 @click="currentMedia = {{ $index }}; lightboxOpen = true">
                                
                                <!-- Media Container -->
                                <div class="relative aspect-square overflow-hidden">
                                    @if($isVideo)
                                        <video class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" 
                                               poster="{{ $url }}" muted>
                                            <source src="{{ $url }}" type="video/mp4">
                                        </video>
                                        
                                        <!-- Play Overlay -->
                                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <div class="bg-white/20 backdrop-blur-sm border border-white/30 rounded-full p-4">
                                                <i class="fas fa-play text-white text-2xl ml-1"></i>
                                            </div>
                                        </div>
                                        
                                        <!-- Video Badge -->
                                        <div class="absolute top-3 left-3 bg-red-500/90 text-white text-xs font-bold px-3 py-1 rounded-full">
                                            <i class="fas fa-play mr-1"></i>
                                            Vidéo
                                        </div>
                                    @else
                                        <img src="{{ $url }}" 
                                             alt="{{ $media->titre ?? 'Media' }}" 
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                        
                                        <!-- Image Overlay -->
                                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <div class="bg-white/20 backdrop-blur-sm border border-white/30 rounded-full p-4">
                                                <i class="fas fa-search-plus text-white text-xl"></i>
                                            </div>
                                        </div>
                                        
                                        <!-- Image Badge -->
                                        <div class="absolute top-3 left-3 bg-blue-500/90 text-white text-xs font-bold px-3 py-1 rounded-full">
                                            <i class="fas fa-image mr-1"></i>
                                            Image
                                        </div>
                                    @endif
                                </div>

                                <!-- Media Info -->
                                @if($media->titre || $media->description)
                                    <div class="p-4">
                                        @if($media->titre)
                                            <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-iri-primary transition-colors duration-200">
                                                {{ $media->titre }}
                                            </h3>
                                        @endif
                                        
                                        @if($media->description)
                                            <p class="text-gray-600 text-sm line-clamp-2">
                                                {{ $media->description }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Lightbox Modal -->
                <div x-show="lightboxOpen" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
                     @click.self="lightboxOpen = false"
                     @keydown.escape.window="lightboxOpen = false">
                    
                    <div class="relative max-w-5xl w-full">
                        <!-- Close Button -->
                        <button @click="lightboxOpen = false" 
                                class="absolute -top-12 right-0 text-white hover:text-gray-300 text-2xl z-10">
                            <i class="fas fa-times"></i>
                        </button>

                        <!-- Media Display -->
                        @foreach($medias as $index => $media)
                            @php
                                $file = $media->medias;
                                $isVideo = Str::endsWith(strtolower($file), ['.mp4', '.webm', '.ogg', '.mov', '.m4v', '.qt']);
                                $url = asset('storage/' . $file);
                            @endphp
                            
                            <div x-show="currentMedia === {{ $index }}" class="text-center">
                                @if($isVideo)
                                    <video class="max-w-full max-h-[80vh] mx-auto rounded-lg shadow-2xl" 
                                           controls autoplay>
                                        <source src="{{ $url }}" type="video/mp4">
                                        Votre navigateur ne supporte pas la lecture de vidéos.
                                    </video>
                                @else
                                    <img src="{{ $url }}" 
                                         alt="{{ $media->titre ?? 'Media' }}" 
                                         class="max-w-full max-h-[80vh] mx-auto rounded-lg shadow-2xl">
                                @endif

                                <!-- Media Info in Lightbox -->
                                @if($media->titre || $media->description)
                                    <div class="mt-6 text-white text-center">
                                        @if($media->titre)
                                            <h3 class="text-2xl font-bold mb-2">{{ $media->titre }}</h3>
                                        @endif
                                        @if($media->description)
                                            <p class="text-gray-300">{{ $media->description }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <!-- Navigation Arrows -->
                        @if(optional($medias)->count() > 1)
                            <button @click="currentMedia = currentMedia > 0 ? currentMedia - 1 : {{ $medias->count() - 1 }}" 
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 text-3xl">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            
                            <button @click="currentMedia = currentMedia < {{ $medias->count() - 1 }} ? currentMedia + 1 : 0" 
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 text-3xl">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="bg-gradient-to-br from-iri-primary to-iri-secondary w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-images text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucun média disponible</h3>
                        <p class="text-gray-600 mb-6">
                            Les images et vidéos seront publiées ici dès qu'elles seront disponibles.
                        </p>
                        <a href="{{ route('site.home') }}" 
                           class="inline-flex items-center bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-3 px-6 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-home mr-2"></i>
                            Retour à l'accueil
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>

<!-- Alpine.js Script -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Styles -->
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .aspect-square {
        aspect-ratio: 1 / 1;
    }
    
    /* Hide scrollbar for video elements */
    video::-webkit-media-controls-panel {
        display: none !important;
    }
    
    video::-webkit-media-controls-play-button {
        display: none !important;
    }
    
    video::-webkit-media-controls-start-playback-button {
        display: none !important;
    }
</style>
@endsection
