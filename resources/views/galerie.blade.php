@extends('layouts.iri')
@section('title', 'Galerie')

@section('content')
<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        
@section('breadcrumb')
    <x-breadcrumb-overlay :items="[
        ['title' => 'Galerie', 'url' => null]
    ]" />
@endsection
        
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
                        {{-- Afficher seulement les médias publiés et publics --}}
                        @if($media->status === 'published' && $media->is_public)
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
                                <div class="relative aspect-square overflow-hidden bg-gray-900">
                                    @if($isVideo)
                                        <!-- Vignette vidéo avec première frame -->
                                        <video class="w-full h-full object-cover video-thumbnail" 
                                               data-video-src="{{ $url }}"
                                               preload="metadata"
                                               muted
                                               playsinline>
                                            <source src="{{ $url }}#t=0.5" type="{{ $media->mime_type ?? 'video/mp4' }}">
                                        </video>
                                        
                                        <!-- Play Overlay -->
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/30 transition-colors duration-300">
                                            <div class="bg-gradient-to-br from-red-500 to-red-600 border-4 border-white rounded-full p-6 transform group-hover:scale-125 transition-all duration-300 shadow-2xl">
                                                <i class="fas fa-play text-white text-3xl ml-1.5"></i>
                                            </div>
                                        </div>
                                        
                                        <!-- Video Badge -->
                                        <div class="absolute top-3 left-3 bg-red-500/90 text-white text-xs font-bold px-3 py-1 rounded-full">
                                            <i class="fas fa-play mr-1"></i>
                                            Vidéo
                                        </div>
                                        
                                        <!-- Download Button -->
                                        <a href="{{ route('site.media.download', $media->id) }}" 
                                           class="absolute top-3 right-3 bg-green-500/90 hover:bg-green-600/90 text-white p-2 rounded-full transition-colors duration-200"
                                           title="Télécharger la vidéo"
                                           @click.stop>
                                            <i class="fas fa-download text-xs"></i>
                                        </a>
                                        
                                        <!-- Titre en overlay (vidéo) -->
                                        @if($media->titre)
                                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 via-black/70 to-transparent p-4 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                                <h3 class="font-bold text-white text-sm line-clamp-2 drop-shadow-lg">
                                                    {{ $media->titre }}
                                                </h3>
                                            </div>
                                        @endif
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
                                        
                                        <!-- Download Button -->
                                        <a href="{{ route('site.media.download', $media->id) }}" 
                                           class="absolute top-3 right-3 bg-green-500/90 hover:bg-green-600/90 text-white p-2 rounded-full transition-colors duration-200"
                                           title="Télécharger l'image"
                                           @click.stop>
                                            <i class="fas fa-download text-xs"></i>
                                        </a>
                                        
                                        <!-- Titre en overlay (image) -->
                                        @if($media->titre)
                                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 via-black/70 to-transparent p-4 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                                <h3 class="font-bold text-white text-sm line-clamp-2 drop-shadow-lg">
                                                    {{ $media->titre }}
                                                </h3>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif {{-- Fin de la condition pour les médias publiés et publics --}}
                    @endforeach
                </div>

                <!-- Lightbox Modal -->
                <div x-show="lightboxOpen" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
                     @click.self="lightboxOpen = false"
                     @keydown.escape.window="lightboxOpen = false"
                     style="display: none;">
                    
                    <div class="relative max-w-5xl w-full">
                        <!-- Close Button -->
                        <button @click="lightboxOpen = false" 
                                class="absolute -top-12 right-0 text-white hover:text-gray-300 text-2xl z-10">
                            <i class="fas fa-times"></i>
                        </button>
                        
                        <!-- Download Button -->
                        @foreach($medias as $index => $media)
                            @if($media->status === 'published' && $media->is_public)
                                <a x-show="currentMedia === {{ $index }}" 
                                   href="{{ route('site.media.download', $media->id) }}" 
                                   class="absolute -top-12 right-12 text-white hover:text-green-400 text-2xl z-10"
                                   title="Télécharger ce média">
                                    <i class="fas fa-download"></i>
                                </a>
                            @endif
                        @endforeach

                        <!-- Media Display -->
                        @foreach($medias as $index => $media)
                            @if($media->status === 'published' && $media->is_public)
                                @php
                                    $file = $media->medias;
                                    $isVideo = Str::endsWith(strtolower($file), ['.mp4', '.webm', '.ogg', '.mov', '.m4v', '.qt']);
                                    $url = asset('storage/' . $file);
                                @endphp
                                
                                <div x-show="currentMedia === {{ $index }}" class="text-center">
                                    @if($isVideo)
                                        <video class="max-w-full max-h-[80vh] mx-auto rounded-lg shadow-2xl" 
                                               controls 
                                               autoplay 
                                               muted
                                               playsinline
                                               preload="metadata">
                                            <source src="{{ $url }}" type="{{ $media->mime_type ?? 'video/mp4' }}">
                                            <div class="bg-red-600/90 text-white p-6 rounded-lg">
                                                <p class="text-lg font-bold mb-2">
                                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                                    Impossible de lire cette vidéo
                                                </p>
                                                <p class="mb-4">Votre navigateur ne supporte pas ce format de vidéo.</p>
                                                <a href="{{ route('site.media.download', $media->id) }}" 
                                                   class="inline-flex items-center bg-white text-red-600 font-bold py-2 px-4 rounded hover:bg-gray-100 transition-colors">
                                                    <i class="fas fa-download mr-2"></i>
                                                    Télécharger la vidéo
                                                </a>
                                            </div>
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
                            @endif
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
    /* Alpine.js x-cloak - Cache les éléments avant initialisation */
    [x-cloak] {
        display: none !important;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .aspect-square {
        aspect-ratio: 1 / 1;
    }
    
    /* Styles pour les vignettes vidéo */
    .video-thumbnail {
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    
    .video-thumbnail:hover {
        transform: scale(1.05);
    }
    
    /* Hide controls for video thumbnails in grid (not in lightbox) */
    .media-item video::-webkit-media-controls-panel,
    .video-thumbnail::-webkit-media-controls-panel {
        display: none !important;
    }
    
    .media-item video::-webkit-media-controls-play-button,
    .video-thumbnail::-webkit-media-controls-play-button {
        display: none !important;
    }
    
    .media-item video::-webkit-media-controls-start-playback-button,
    .video-thumbnail::-webkit-media-controls-start-playback-button {
        display: none !important;
    }
    
    /* Ensure lightbox videos keep their controls */
    .lightbox-content video {
        background: #000;
    }
    
    /* Effet de chargement pour vignettes vidéo */
    .video-thumbnail::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(45deg, #1f2937 25%, transparent 25%, transparent 75%, #1f2937 75%, #1f2937),
                    linear-gradient(45deg, #1f2937 25%, transparent 25%, transparent 75%, #1f2937 75%, #1f2937);
        background-size: 20px 20px;
        background-position: 0 0, 10px 10px;
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .video-thumbnail.loading::before {
        opacity: 0.5;
    }
</style>

<!-- JavaScript pour améliorer la lecture vidéo -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎬 Initialisation du lecteur vidéo de la galerie');
    
    // Gérer les vignettes vidéo (thumbnails)
    const videoThumbnails = document.querySelectorAll('.video-thumbnail');
    
    videoThumbnails.forEach(videoThumb => {
        // Ajouter classe de chargement
        videoThumb.classList.add('loading');
        
        // Quand les métadonnées sont chargées (première frame visible)
        videoThumb.addEventListener('loadeddata', function() {
            console.log('✅ Vignette vidéo chargée');
            this.classList.remove('loading');
            
            // Chercher à 0.5 seconde pour avoir une meilleure frame
            this.currentTime = 0.5;
        });
        
        // Quand on a cherché la bonne position
        videoThumb.addEventListener('seeked', function() {
            console.log('📸 Frame de la vignette capturée');
        });
        
        // Empêcher la lecture au clic (le lightbox s'occupe de ça)
        videoThumb.addEventListener('click', function(e) {
            e.preventDefault();
            this.pause();
        });
    });
    
    // Gérer les erreurs de chargement vidéo
    document.querySelectorAll('video').forEach(video => {
        video.addEventListener('error', function(e) {
            console.error('❌ Erreur de chargement vidéo:', e);
            
            // Si c'est une vignette, afficher placeholder par défaut
            if (this.classList.contains('video-thumbnail')) {
                const placeholder = document.createElement('div');
                placeholder.className = 'absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900';
                placeholder.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-video text-white/30 text-6xl mb-4"></i>
                        <p class="text-white/50 text-sm">Aperçu non disponible</p>
                    </div>
                `;
                
                if (this.parentElement) {
                    this.parentElement.insertBefore(placeholder, this);
                    this.style.display = 'none';
                }
            } else {
                // Pour les vidéos du lightbox
                const errorDiv = document.createElement('div');
                errorDiv.className = 'absolute inset-0 flex items-center justify-center bg-red-900/90 text-white p-4';
                errorDiv.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                        <p class="font-bold">Impossible de charger la vidéo</p>
                        <p class="text-sm opacity-80">Le fichier est peut-être corrompu ou introuvable</p>
                    </div>
                `;
                
                if (this.parentElement) {
                    this.parentElement.appendChild(errorDiv);
                    this.style.display = 'none';
                }
            }
        });
        
        // Log quand la vidéo est prête
        video.addEventListener('loadedmetadata', function() {
            console.log('✅ Vidéo chargée:', this.src);
        });
        
        // Activer le son au premier clic (après autoplay muté)
        video.addEventListener('click', function() {
            if (this.muted) {
                this.muted = false;
                // Afficher une notification temporaire
                const notification = document.createElement('div');
                notification.className = 'absolute top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg';
                notification.innerHTML = '<i class="fas fa-volume-up mr-2"></i>Son activé';
                this.parentElement.appendChild(notification);
                
                setTimeout(() => notification.remove(), 2000);
            }
        });
    });
    
    console.log('✅ Lecteur vidéo initialisé');
});

// Alpine.js : Pause les vidéos quand le lightbox se ferme
document.addEventListener('alpine:initialized', () => {
    // Cette fonction sera appelée après l'initialisation d'Alpine
    console.log('🔧 Alpine.js initialisé - Surveillance du lightbox activée');
});
</script>
@endsection
