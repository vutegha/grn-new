@extends('layouts.iri')
@section('title', 'Domaines d\'intervention')
@section('content')

<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        
@section('breadcrumb')
    <x-breadcrumb-overlay :items="[
        ['title' => 'Domaines d\'intervention', 'url' => null]
    ]" />
@endsection
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 drop-shadow-2xl">
                Domaines d'intervention
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed drop-shadow-lg">
                Découvrez l'ensemble de nos domaines d'intervention et d'expertise du programme GRN-UCBC
            </p>
        </div>
    </section>

    <!-- Domaines d'intervention Grid -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($services->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($services as $service)
                        <article class="group">
                            <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                                <!-- Service Image -->
                                <div class="relative h-64 overflow-hidden">
                                    @if($service->image)
                                        <img src="{{ asset('storage/' . $service->image) }}" 
                                             alt="{{ $service->nom }}" 
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent flex items-center justify-center">
                                            <i class="fas fa-cogs text-white text-4xl opacity-70"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="absolute bottom-4 left-4 right-4">
                                            <a href="{{ route('site.service.show', ['slug' => $service->slug]) }}" 
                                               class="inline-flex items-center justify-center w-full bg-white/20 backdrop-blur-sm text-white font-medium py-2 px-4 rounded-lg border border-white/30 hover:bg-white/30 transition-all duration-200">
                                                <i class="fas fa-eye mr-2"></i>
                                                Découvrir ce domaine
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6">
                                    <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-iri-primary transition-colors duration-200">
                                        <a href="{{ route('site.service.show', ['slug' => $service->slug]) }}" class="hover:underline">
                                            {{ $service->nom }}
                                        </a>
                                    </h2>

                                    <p class="text-gray-600 mb-4 line-clamp-3">
                                        {{ Str::limit($service->resume ?? $service->description, 120) }}
                                    </p>

                                    <!-- Service Statistics -->
                                    <div class="grid grid-cols-2 gap-3 mb-6 pt-4 border-t border-gray-100">
                                        @if(optional($service->projets)->count() > 0)
                                            <a href="{{ route('site.service.projets', $service->slug) }}" 
                                               class="flex items-center justify-center bg-blue-50 hover:bg-blue-100 text-blue-600 hover:text-blue-800 px-3 py-2 rounded-lg transition-all duration-200 group/link">
                                                <i class="fas fa-project-diagram mr-2 text-sm"></i>
                                                <span class="text-sm font-medium">{{ optional($service->projets)->count() ?? 0 }} projet{{ (optional($service->projets)->count() ?? 0) > 1 ? 's' : '' }}</span>
                                                <i class="fas fa-arrow-right ml-2 text-xs transform group-hover/link:translate-x-1 transition-transform duration-200"></i>
                                            </a>
                                        @else
                                            <div class="flex items-center justify-center bg-gray-50 text-gray-400 px-3 py-2 rounded-lg">
                                                <i class="fas fa-project-diagram mr-2 text-sm"></i>
                                                <span class="text-sm">0 projet</span>
                                            </div>
                                        @endif

                                        @if(optional($service->actualites)->count() > 0)
                                            <a href="{{ route('site.service.actualites', $service->slug) }}" 
                                               class="flex items-center justify-center bg-green-50 hover:bg-green-100 text-green-600 hover:text-green-800 px-3 py-2 rounded-lg transition-all duration-200 group/link">
                                                <i class="fas fa-newspaper mr-2 text-sm"></i>
                                                <span class="text-sm font-medium">{{ optional($service->actualites)->count() ?? 0 }} actualité{{ (optional($service->actualites)->count() ?? 0) > 1 ? 's' : '' }}</span>
                                                <i class="fas fa-arrow-right ml-2 text-xs transform group-hover/link:translate-x-1 transition-transform duration-200"></i>
                                            </a>
                                        @else
                                            <div class="flex items-center justify-center bg-gray-50 text-gray-400 px-3 py-2 rounded-lg">
                                                <i class="fas fa-newspaper mr-2 text-sm"></i>
                                                <span class="text-sm">0 actualité</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Action Button -->
                                    <div class="flex items-center justify-center">
                                        <a href="{{ route('site.service.show', ['slug' => $service->slug]) }}"
                                           class="inline-flex items-center bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-3 px-6 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200 w-full justify-center">
                                            <span>Découvrir ce domaine</span>
                                            <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform duration-200"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="bg-gradient-to-br from-iri-primary to-iri-secondary w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-cogs text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucun domaine d'intervention disponible</h3>
                        <p class="text-gray-600 mb-6">
                            Les domaines d'intérêt seront publiés ici dès qu'ils seront disponibles.
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
</style>

{{-- Boutons de partage social pour la page des services --}}
<x-global-social-share 
    position="fixed-left"
    style="outline"
    size="medium"
    :showLabels="false"
    platforms="facebook,twitter,linkedin,whatsapp,email"
    customTitle="Domaines d'intervention - GRN-UCBC"
    customDescription="Découvrez nos domaines d'expertise en gouvernance foncière, développement durable et gestion des ressources naturelles"
    :analytics="true" />
@endsection
