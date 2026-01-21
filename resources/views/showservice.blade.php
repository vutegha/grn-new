@extends('layouts.iri')

@section('title', $service->exists ? 'Service - ' . $service->nom : 'Service introuvable')

@push('styles')
<!-- Feuille de style CKEditor - PRIORITAIRE pour le contenu des services -->
<link rel="stylesheet" href="{{ asset('css/ckeditor-content.css') }}" data-priority="high">
@endpush

@section('breadcrumb')
@if($service->exists)
    <x-breadcrumb-overlay :items="[
        ['title' => 'Services', 'url' => route('site.services')],
        ['title' => Str::limit($service->nom, 50), 'url' => null]
    ]" />
@endif
@endsection

@section('content')
@if($service->exists)
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
                            
                            @if($service->resume)
                                <p class="text-xl md:text-2xl text-gray-700 leading-relaxed mb-8 font-medium border-l-4 border-iri-primary pl-6 bg-gray-50 py-4 rounded-r-lg">
                                    {{ $service->resume }}
                                </p>
                            @endif
                            
                            @if($service->contenu)
                                <div class="article-content">
                                    <x-rich-text-display :content="$service->contenu" />
                                </div>
                            @elseif($service->description)
                                <div class="article-content">
                                    <x-rich-text-display :content="$service->description" />
                                </div>
                            @else
                                <div class="text-gray-700 text-lg leading-relaxed space-y-4">
                                    <p>Ce service fait partie intégrante de notre mission d'accompagnement et de développement. Notre équipe expérimentée met tout en œuvre pour fournir des solutions adaptées et de qualité.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Actualités Associées -->
                        @if(optional($service->actualites)->count() > 0)
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mt-8">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-3xl font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-newspaper text-iri-accent mr-3"></i>
                                        Actualités Associées
                                    </h2>
                                    @if(optional($service->actualites)->count() > 8)
                                        <a href="{{ route('site.actualites') }}" 
                                           class="text-sm text-iri-primary hover:text-iri-secondary font-semibold">
                                            Voir tout →
                                        </a>
                                    @endif
                                </div>

                                <div class="space-y-4">
                                    @foreach($service->actualites->take(8) as $actualite)
                                        <article class="border-b border-gray-100 pb-4 last:border-0 hover:bg-gray-50 p-3 rounded-lg transition-all duration-200">
                                            <div class="flex items-start gap-3">
                                                <!-- Image -->
                                                @if($actualite->image)
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ asset('storage/'.$actualite->image) }}" 
                                                             alt="{{ $actualite->titre }}" 
                                                             class="w-16 h-16 object-cover rounded-lg">
                                                    </div>
                                                @else
                                                    <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-iri-accent to-iri-gold rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-newspaper text-white text-xl"></i>
                                                    </div>
                                                @endif

                                                <!-- Informations -->
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-sm font-bold text-gray-900 mb-1 line-clamp-2">
                                                        <a href="{{ route('site.actualite.show', $actualite->slug) }}" 
                                                           class="hover:text-iri-primary transition-colors">
                                                            {{ $actualite->titre }}
                                                        </a>
                                                    </h4>
                                                    
                                                    @if($actualite->contenu)
                                                        <p class="text-xs text-gray-600 mb-2 line-clamp-2">
                                                            {{ Str::limit(strip_tags($actualite->contenu), 80) }}
                                                        </p>
                                                    @endif

                                                    <div class="flex items-center gap-3 text-xs text-gray-500">
                                                        <span class="flex items-center">
                                                            <i class="fas fa-calendar mr-1"></i>
                                                            {{ $actualite->created_at->format('d M Y') }}
                                                        </span>
                                                        
                                                        @if($actualite->en_vedette)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                <i class="fas fa-star mr-1"></i>
                                                                Vedette
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        @endif
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

                            <!-- Projets liés au service -->
                            @if(optional($service->projets)->count() > 0)
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Projets récents</h3>
                                    <div class="space-y-4">
                                        @foreach($service->projets->take(3) as $projet)
                                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                                @if($projet->statut)
                                                    <div class="mb-2">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                                            {{ $projet->statut === 'en_cours' ? 'bg-green-100 text-green-800' : '' }}
                                                            {{ $projet->statut === 'termine' ? 'bg-blue-100 text-blue-800' : '' }}
                                                            {{ $projet->statut === 'planifie' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                                            <i class="fas fa-circle mr-1" style="font-size: 6px;"></i>
                                                            {{ ucfirst(str_replace('_', ' ', $projet->statut)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                
                                                <h4 class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2">
                                                    {{ $projet->nom }}
                                                </h4>
                                                
                                                @if($projet->resume)
                                                    <p class="text-xs text-gray-600 mb-2 line-clamp-2">
                                                        {{ Str::limit(strip_tags($projet->resume), 100) }}
                                                    </p>
                                                @endif
                                                
                                                @if($projet->date_debut)
                                                    <p class="text-xs text-gray-500 mb-2">
                                                        <i class="fas fa-calendar-alt mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($projet->date_debut)->format('d M Y') }}
                                                    </p>
                                                @endif
                                                
                                                @if($projet->slug)
                                                    <a href="{{ route('site.projet.show', $projet->slug) }}" 
                                                       class="text-iri-primary hover:text-iri-secondary text-xs font-semibold">
                                                        Voir le projet →
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @if(optional($service->projets)->count() > 3)
                                        <div class="mt-4 text-center">
                                            <a href="{{ route('site.projets') }}" 
                                               class="text-iri-primary hover:text-iri-secondary text-sm font-semibold">
                                                Voir tous les projets →
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Service Stats -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-bold text-gray-900">Statistiques</h3>
                                
                                <div class="space-y-4">
                                    @if(optional($service->projets)->count() > 0)
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

                                    @if(optional($service->actualites)->count() > 0)
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

{{-- Boutons de partage social pour les services --}}
@if($service->exists)
<x-global-social-share 
    position="fixed-left"
    style="outline"
    size="medium"
    :showLabels="false"
    platforms="facebook,twitter,linkedin,whatsapp,email"
    customTitle="{{ $service->nom }} - GRN-UCBC"
    customDescription="{{ Str::limit(strip_tags($service->resume ?? $service->description ?? ''), 150) }}"
    customImage="{{ $service->image ? asset('storage/' . $service->image) : asset('assets/img/logos/iri-logo.png') }}"
    :analytics="true" />
@endif
@endsection
