@extends('layouts.iri')

@section('title', $service->exists ? 'Service - ' . $service->nom : 'Service introuvable')

@section('content')
@if($service->exists)
    <!-- Breadcrumb -->
    @include('partials.breadcrumb', [
        'breadcrumbs' => [
            ['title' => 'Services', 'url' => route('site.services')],
            ['title' => $service->nom, 'url' => null]
        ]
    ])

    <!-- Main Content -->
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        <!-- Hero Section -->
        <section class="relative">
            <div class="relative h-96 bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent overflow-hidden">
                @if($service->image)
                    <img src="{{ asset('storage/'.$service->image) }}" 
                         alt="{{ $service->nom }}" 
                         class="absolute inset-0 w-full h-full object-cover mix-blend-overlay">
                @endif
                <div class="absolute inset-0 bg-black/20"></div>
                
                <div class="relative z-10 flex items-center justify-center h-full">
                    <div class="text-center text-white px-6 max-w-4xl mx-auto">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 drop-shadow-2xl">
                            {{ $service->nom }}
                        </h1>
                        @if($service->resume)
                            <p class="text-xl md:text-2xl text-white/90 leading-relaxed drop-shadow-lg">
                                {{ $service->resume }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Service Description -->
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Main Content -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">À propos de ce service</h2>
                            
                            @if($service->contenu)
                                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                    {!! nl2br(e($service->contenu)) !!}
                                </div>
                            @elseif($service->description)
                                <div class="text-gray-700 text-lg leading-relaxed space-y-4">
                                    <p>{{ $service->description }}</p>
                                    <p>Ce service fait partie intégrante de notre mission d'accompagnement et de développement. Notre équipe expérimentée met tout en œuvre pour fournir des solutions adaptées et de qualité.</p>
                                </div>
                            @else
                                <div class="text-gray-700 text-lg leading-relaxed">
                                    <p>{{ $service->resume }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-6">
                            <!-- Back Button -->
                            <div class="mb-6">
                                <a href="{{ route('site.services') }}" 
                                   class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-semibold transition-colors duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Retour aux services
                                </a>
                            </div>

                            <!-- Service Stats -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-bold text-gray-900">Statistiques</h3>
                                
                                <div class="space-y-4">
                                    @if(optional($service->projets)->count() ?? 0 > 0)
                                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                                            <div class="flex items-center">
                                                <div class="bg-blue-500 p-2 rounded-lg mr-3">
                                                    <i class="fas fa-project-diagram text-white"></i>
                                                </div>
                                                <span class="text-gray-700 font-medium">Projets</span>
                                            </div>
                                            <span class="text-blue-600 font-bold text-lg">{{ optional($service->projets)->count() ?? 0 }}</span>
                                        </div>
                                    @endif

                                    @if(optional($service->actualites)->count() ?? 0 > 0)
                                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                                            <div class="flex items-center">
                                                <div class="bg-green-500 p-2 rounded-lg mr-3">
                                                    <i class="fas fa-newspaper text-white"></i>
                                                </div>
                                                <span class="text-gray-700 font-medium">Actualités</span>
                                            </div>
                                            <span class="text-green-600 font-bold text-lg">{{ optional($service->actualites)->count() ?? 0 }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Contact for Service -->
                            <div class="mt-8 p-4 bg-gradient-to-r from-iri-primary to-iri-secondary rounded-lg text-white">
                                <h4 class="font-bold mb-2">Intéressé par ce service ?</h4>
                                <p class="text-sm mb-4 text-white/90">Contactez-nous pour plus d'informations</p>
                                <a href="{{ route('site.contact') }}" 
                                   class="inline-flex items-center bg-white/20 backdrop-blur-sm border border-white/30 text-white font-semibold py-2 px-4 rounded-lg hover:bg-white/30 transition-all duration-200 w-full justify-center">
                                    <i class="fas fa-envelope mr-2"></i>
                                    Nous contacter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related Projects -->
        @if(optional($service->projets)->count() ?? 0 > 0)
            <section class="py-16 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Projets associés à ce service
                        </h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                            Découvrez les projets que nous avons réalisés dans le cadre de ce service
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($service->projets->take(6) as $projet)
                            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100">
                                @if($projet->image)
                                    <div class="h-48 overflow-hidden">
                                        <img src="{{ asset('storage/'.$projet->image) }}" 
                                             alt="{{ $projet->nom }}" 
                                             class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                                    </div>
                                @else
                                    <div class="h-48 bg-gradient-to-br from-iri-primary to-iri-secondary flex items-center justify-center">
                                        <i class="fas fa-project-diagram text-white text-3xl opacity-70"></i>
                                    </div>
                                @endif

                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                        {{ $projet->nom }}
                                    </h3>
                                    
                                    @if($projet->description)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                            {{ Str::limit(strip_tags($projet->description), 100) }}
                                        </p>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $projet->created_at->format('Y') }}
                                        </span>
                                        
                                        @if($projet->slug)
                                            <a href="{{ route('site.projet.show', $projet->slug) }}" 
                                               class="text-iri-primary hover:text-iri-secondary font-semibold text-sm transition-colors duration-200">
                                                Voir plus →
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(optional($service->projets)->count() ?? 0 > 6)
                        <div class="text-center mt-12">
                            <a href="{{ route('site.projets') }}" 
                               class="inline-flex items-center bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-3 px-8 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-eye mr-2"></i>
                                Voir tous les projets
                            </a>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        <!-- Related News -->
        @if(optional($service->actualites)->count() ?? 0 > 0)
            <section class="py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Actualités liées à ce service
                        </h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                            Restez informé des dernières nouvelles concernant ce service
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($service->actualites->take(6) as $actualite)
                            <article class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100">
                                @if($actualite->image)
                                    <div class="h-48 overflow-hidden">
                                        <img src="{{ asset('storage/'.$actualite->image) }}" 
                                             alt="{{ $actualite->titre }}" 
                                             class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                                    </div>
                                @else
                                    <div class="h-48 bg-gradient-to-br from-iri-accent to-iri-gold flex items-center justify-center">
                                        <i class="fas fa-newspaper text-white text-3xl opacity-70"></i>
                                    </div>
                                @endif

                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                        {{ $actualite->titre }}
                                    </h3>
                                    
                                    @if($actualite->contenu)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                            {{ Str::limit(strip_tags($actualite->contenu), 120) }}
                                        </p>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $actualite->created_at->format('d M Y') }}
                                        </span>
                                        
                                        @if($actualite->slug)
                                            <a href="{{ route('site.actualite.show', $actualite->slug) }}" 
                                               class="text-iri-primary hover:text-iri-secondary font-semibold text-sm transition-colors duration-200">
                                                Lire plus →
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if(optional($service->actualites)->count() ?? 0 > 6)
                        <div class="text-center mt-12">
                            <a href="{{ route('site.actualites') }}" 
                               class="inline-flex items-center bg-gradient-to-r from-iri-accent to-iri-gold text-white font-semibold py-3 px-8 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-newspaper mr-2"></i>
                                Voir toutes les actualités
                            </a>
                        </div>
                    @endif
                </div>
            </section>
        @endif
    </div>

@else
    <!-- Service Not Found -->
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white flex items-center justify-center">
        <div class="max-w-md mx-auto text-center px-4">
            <div class="bg-red-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Service introuvable</h1>
            <p class="text-gray-600 mb-8">
                Le service que vous recherchez n'existe pas ou a été supprimé.
            </p>
            <div class="space-y-4">
                <a href="{{ route('site.services') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-3 px-6 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-cogs mr-2"></i>
                    Voir tous les services
                </a>
                <div>
                    <a href="{{ route('site.home') }}" 
                       class="text-iri-primary hover:text-iri-secondary font-semibold transition-colors duration-200">
                        <i class="fas fa-home mr-1"></i>
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Styles -->
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .prose {
        color: #374151;
        max-width: none;
    }
    .prose p {
        margin-bottom: 1rem;
        line-height: 1.7;
    }
    .prose h1, .prose h2, .prose h3, .prose h4 {
        color: #111827;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
</style>
@endsection
