@extends('layouts.iri')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
@endpush

<div>

    <!-- HERO avec background -->
    <div class="relative w-full h-screen bg-cover bg-center"
         style="background-image: url('{{ asset('assets/img/research.jpg') }}');">

        <!-- Overlay pour assombrir l'image -->
        <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/50 to-black/70"></div>
        
        <!-- Actualités en vedette et à la une - Section supérieure -->
        <div class="absolute top-0 left-0 right-0 z-20 px-4 sm:px-6 lg:px-8 pt-0 hidden lg:block">
            <!-- Barre horizontale des actualités - Container full width -->
            <div class="bg-black/40 backdrop-blur-sm rounded-lg p-2 shadow-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-2">
                    @foreach($actualites as $actualite)
                        <div class="flex items-center space-x-2 hover:bg-white/10 rounded-lg p-2 transition-all duration-300 min-h-[60px]">
                            <!-- Image circulaire -->
                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-orange-400 shadow-md flex-shrink-0">
                                <img src="{{ asset('storage/'.$actualite->image) }}" 
                                     alt="{{ $actualite->titre }}"
                                     class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Titre avec wrap -->
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('site.actualite', ['slug' => $actualite->slug]) }}" 
                                   class="text-white hover:text-orange-300 font-medium text-sm transition-colors duration-200 break-words leading-tight">
                                    {{ $actualite->titre }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Contenu principal centré -->
        <div class="relative z-10 flex items-center justify-center h-full">
            <div class="text-center text-white max-w-5xl mx-auto px-6">
                <div class="bg-gradient-to-r from-black/60 via-black/40 to-black/60 backdrop-blur-sm p-8 lg:p-12 rounded-2xl shadow-2xl">
                    <!-- Titre principal -->
                    <h1 class="text-3xl sm:text-4xl lg:text-6xl font-bold leading-tight mb-6">
                        <span class="bg-gradient-to-r from-iri-accent via-iri-gold to-iri-accent bg-clip-text text-transparent">
                            La recherche appliquée
                        </span>
                        <br>
                        <span class="text-white">
                            en réponse aux besoins sociétaux
                        </span>
                    </h1>

                    <!-- Sous-titre -->
                    <p class="text-lg sm:text-xl lg:text-2xl text-gray-200 max-w-4xl mx-auto mb-8 leading-relaxed">
                        Nous développons des solutions concrètes, ancrées dans les réalités congolaises,
                        pour bâtir des communautés résilientes et durables.
                    </p>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ url('/about') }}"
                           class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-iri-accent to-iri-gold hover:from-iri-gold hover:to-iri-accent text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            Qui sommes-nous ?
                        </a>
                        
                        <a href="{{ url('/services') }}" 
                           class="inline-flex items-center px-8 py-4 border-2 border-white text-white hover:bg-white hover:text-iri-primary font-bold rounded-lg transition-all duration-300 transform hover:scale-105">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            Nos services
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MISSION & SECTEURS -->
    <section id="aboutus" class="py-20 bg-gradient-to-b from-white via-gray-50 to-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-20 left-10 w-32 h-32 bg-iri-accent rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-40 h-40 bg-iri-gold rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <!-- Header Section -->
            <div class="text-center mb-20">
                <div class="inline-flex items-center px-8 py-4 mission-badge rounded-full mb-8 shadow-xl transform hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-bullseye mr-3 text-white"></i>
                    <span class="text-white font-bold text-base uppercase tracking-wider">
                        Notre Mission & Domaines d'intervention
                    </span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold primary-text mb-6 font-poppins">
                    Recherche <span class="text-iri-accent">Appliquée</span> & Innovation
                </h2>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Découvrez notre engagement pour la recherche scientifique et nos domaines d'expertise 
                    au service du développement durable en République Démocratique du Congo
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-20">

            <!-- Mission Card Moderne -->
            <div class="relative overflow-hidden rounded-3xl shadow-2xl group hover:shadow-3xl transition-all duration-700 transform hover:-translate-y-3 mission-card-bg h-[500px]">
                <img src="{{ asset('assets/img/iri.jpg') }}" alt="Mission" class="w-full h-64 object-cover">
                <div class="p-6 space-y-4">
                    <h3 class="text-2xl font-bold">Notre Mission</h3>
                    <p class="text-md font-light mt-2">
                        Le Centre de Gouvernance des Ressources Naturelles à l'Université Chrétienne Bilingue du Congo (GRN-UCBC) a pour mission de promouvoir la recherche scientifique, l'innovation et le développement durable au service de la société congolaise et africaine. À travers des projets interdisciplinaires, le centre vise à renforcer les capacités, encourager la collaboration et produire des connaissances utiles pour répondre aux défis locaux au sein des communautés locales en République Démocratique du Congo.
                    </p>
                </div>
                
            </div>

            <!-- Secteurs -->
            <div class="space-y-6">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-br from-iri-accent to-iri-gold rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-cogs text-white text-xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold primary-text">Nos Secteurs</h4>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($services as $service)
                    <a href="{{ route('site.service.show', ['slug' => $service->slug]) }}" 
                       class="group cursor-pointer block">
                        
                        <div class="relative overflow-hidden rounded-2xl bg-white border-2 border-gray-100 hover:border-iri-accent/30 transition-all duration-300 hover:shadow-xl hover:-translate-y-2 transform">
                            <!-- Header moderne avec couleurs IRI -->
                            <div class="p-5 relative overflow-hidden bg-gradient-to-r from-iri-primary to-iri-secondary">
                                <div class="relative flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0 border border-white/30">
                                        <img src="{{ asset('storage/' . $service->icone) }}"
                                             alt="{{ $service->nom }}"
                                             class="w-6 h-6 object-contain filter brightness-0 invert">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h5 class="font-bold text-white text-base leading-tight group-hover:text-iri-gold transition-colors duration-300">
                                            {{ $service->nom }}
                                        </h5>
                                        <div class="w-12 h-1 bg-iri-gold rounded-full mt-2 transform group-hover:w-20 transition-all duration-300"></div>
                                    </div>
                                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-iri-gold/30 transition-colors duration-300">
                                        <i class="fas fa-arrow-right text-white text-sm transform group-hover:translate-x-1 transition-transform duration-300"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contenu -->
                            <div class="p-5">
                                <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 group-hover:text-gray-700 transition-colors duration-300">
                                    {{ Str::limit($service->description, 100) }}
                                </p>
                                
                                <!-- Action indicator modernisé -->
                                <div class="flex items-center justify-between mt-4">
                                    <span class="text-xs text-gray-400 group-hover:text-iri-accent transition-colors duration-300">Cliquez pour explorer</span>
                                    <div class="w-8 h-8 bg-gradient-to-r from-iri-accent to-iri-gold rounded-full flex items-center justify-center shadow-md transform group-hover:scale-110 transition-all duration-300">
                                        <i class="fas fa-arrow-right text-white text-sm"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hover effect overlay avec couleurs IRI -->
                            <div class="absolute inset-0 bg-gradient-to-r from-iri-accent/5 to-iri-gold/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Publications & Événements -->
    <section id="projets" class="py-20 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
        <!-- Background decoratif -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-32 left-20 w-24 h-24 bg-iri-primary rounded-full blur-2xl"></div>
            <div class="absolute bottom-32 right-20 w-32 h-32 bg-iri-accent rounded-full blur-2xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <!-- Header principal -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-8 py-4 mission-badge rounded-full mb-8 shadow-xl transform hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-newspaper mr-3 text-white"></i>
                    <span class="text-white font-bold text-base uppercase tracking-wider">
                        Publications & Événements
                    </span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold primary-text mb-6 font-poppins">
                    Nos <span class="text-iri-accent">Ressources</span> & Actualités
                </h2>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Découvrez nos dernières publications de recherche, rapports d'activités et événements marquants de l'IRI-UCBC
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Publications Section - 2/3 de l'espace -->
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                <i class="fas fa-file-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold primary-text">Publications & Rapports</h3>
                                <p class="text-sm text-gray-500">Nos dernières recherches et ressources</p>
                            </div>
                        </div>
                        <a href="{{ url('/publications') }}" class="text-iri-accent hover:text-iri-primary transition-colors duration-300 font-semibold text-sm flex items-center">
                            Voir tout <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>

                    <!-- Carousel des publications modernisé -->
                    <div class="splide publication-carousel" id="project-carousel">
                        <div class="splide__track">
                            <ul class="splide__list">
                                @foreach($publications as $pub)
                                <li class="splide__slide">
                                    <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 bg-white border border-gray-100">
                                        <!-- Preview PDF -->
                                        <div class="relative h-64 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                                            <canvas id="pdf-canvas-{{$pub->id}}" 
                                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                                                    data-pdf-url="{{ asset('storage/'.$pub->fichier_pdf) }}">
                                            </canvas>
                                            <!-- Overlay avec gradient -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                            
                                            <!-- Badge type de document -->
                                            <div class="absolute top-4 left-4">
                                                <span class="bg-iri-primary/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full">
                                                    <i class="fas fa-file-pdf mr-1"></i>
                                                    PDF
                                                </span>
                                            </div>
                                            
                                            <!-- Action overlay -->
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <a href="{{ route('publication.show', ['slug' => $pub->slug]) }}" 
                                                   class="bg-white/20 backdrop-blur-md border border-white/30 text-white px-6 py-3 rounded-full font-semibold hover:bg-white hover:text-iri-primary transition-all duration-300 flex items-center">
                                                    <i class="fas fa-eye mr-2"></i>
                                                    Consulter
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- Contenu -->
                                        <div class="p-6">
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="text-xs text-iri-accent font-semibold uppercase tracking-wider">
                                                    {{ $pub->created_at->format('d M Y') }}
                                                </span>
                                                <div class="w-6 h-6 bg-gradient-to-r from-iri-accent to-iri-gold rounded-full flex items-center justify-center">
                                                    <i class="fas fa-download text-white text-xs"></i>
                                                </div>
                                            </div>
                                            
                                            <h4 class="font-bold text-gray-900 text-lg leading-tight mb-3 group-hover:text-iri-primary transition-colors duration-300">
                                                <a href="{{ route('publication.show', ['slug' => $pub->slug]) }}" class="hover:underline">
                                                    {{ $pub->titre }}
                                                </a>
                                            </h4>
                                            
                                            <!-- Tags/Catégories -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-2">
                                                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">Recherche</span>
                                                </div>
                                                <span class="text-xs text-gray-400">{{ $pub->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Événements -->
                <aside class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <!-- En-tête des événements -->
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-iri-accent to-iri-gold rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-alt text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold primary-text">Événements</h3>
                                <p class="text-xs text-gray-500">Nos prochaines activités</p>
                            </div>
                        </div>
                        
                        <!-- Liste simple des événements -->
                        <div class="space-y-4 mb-8">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-iri-primary rounded-lg flex items-center justify-center text-white">
                                        <span class="text-xs font-bold">JUL</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-sm">Conférence IRI 2025</h4>
                                        <p class="text-xs text-gray-500">25 Juillet 2025</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-iri-secondary rounded-lg flex items-center justify-center text-white">
                                        <span class="text-xs font-bold">AUG</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-sm">Formation Recherche</h4>
                                        <p class="text-xs text-gray-500">15 Août 2025</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Twitter -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fab fa-twitter text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-blue-900 text-sm">Actualités Twitter</h4>
                                    <p class="text-xs text-blue-600">Suivez-nous @IRI_UCBC</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="bg-white rounded-lg p-3 shadow-sm border border-blue-100">
                                    <p class="text-xs text-gray-700 leading-relaxed">
                                        "Nouveau partenariat stratégique avec l'Université de Kinshasa 🤝 #Innovation #Recherche"
                                    </p>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs text-blue-500 font-medium">@IRI_UCBC</span>
                                        <span class="text-xs text-gray-400">Il y a 2h</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <a href="https://twitter.com/IRI_UCBC" target="_blank" 
                                   class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 font-semibold transition-colors">
                                    <i class="fab fa-twitter mr-1"></i>
                                    Suivre sur Twitter
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>

        </div>
    </div>
</section>

<!-- Section partenaires -->
<section class="bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-center text-2xl font-semibold text-gray-800 mb-6">Nos partenaires</h2>
        <div class="overflow-hidden relative">
            <div class="flex animate-scroll-infinite space-x-8 hover:[animation-play-state:paused]">
                <!-- Logos répétés pour boucle fluide -->
                @for ($i = 0; $i < 2; $i++)
                    <img src="assets/img/logos/logo-conaref.png" alt="Partenaire 1" class="h-16 object-contain"/>
                    <img src="assets/img/logos/logo-iri.jpg" alt="Partenaire 1" class="h-16 object-contain"/>
                    <img src="assets/img/logos/logo-gltn.png" alt="Partenaire 1" class="h-16 object-contain"/>
                    <img src="assets/img/logos/logo-fig.jpg" alt="Partenaire 1" class="h-16 object-contain"/>
                    <img src="assets/img/logos/logo-ucbc.png" alt="Partenaire 1" class="h-16 object-contain"/>
                @endfor
            </div>
        </div>
    </div>
</section>

</div> <!-- Fermeture du conteneur principal -->

@endsection

@push('scripts')
<!-- Splide JS -->
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Splide('#project-carousel', {
        type: 'loop',
        perPage: 3,
        gap: '1rem',
        autoplay: true,
        interval: 3000,
        pagination: false,
        breakpoints: {
            1024: { perPage: 2 },
            640 : { perPage: 1 },
        }
    }).mount();

    // Simple modal functions (optional)
    window.showModal = function(title, content) {
        const modal = document.getElementById('simple-modal');
        if (modal) {
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-content').textContent = content;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeModal = function() {
        const modal = document.getElementById('simple-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    };

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
});
</script>

<!-- Simple Modal (hidden by default) -->
<div id="simple-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg max-w-md w-full mx-4 p-6 relative">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <h3 id="modal-title" class="text-lg font-semibold mb-4 text-iri-primary"></h3>
        <p id="modal-content" class="text-gray-600 mb-6"></p>
        <div class="flex justify-end">
            <button onclick="closeModal()" class="px-4 py-2 bg-iri-primary text-white rounded hover:bg-iri-secondary transition">
                Fermer
            </button>
        </div>
    </div>
</div>
@endpush
