@extends('layouts.iri')

@section('title', $auteur->prenom . ' ' . $auteur->nom . ' - Profil Auteur')

@push('meta')
    <meta name="description" content="Profil de {{ $auteur->prenom }} {{ $auteur->nom }}{{ $auteur->institution ? ' - ' . $auteur->institution : '' }}. {{ $auteur->biographie ? Str::limit(strip_tags($auteur->biographie), 160) : 'Découvrez ses publications et contributions.' }}">
    <meta name="keywords" content="{{ $auteur->prenom }} {{ $auteur->nom }}, auteur, chercheur, GRN, UCBC, RDC{{ $auteur->institution ? ', ' . $auteur->institution : '' }}">
    <link rel="canonical" href="{{ route('site.auteur.show', $auteur->getSlug()) }}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $auteur->prenom }} {{ $auteur->nom }} - Profil Auteur">
    <meta property="og:description" content="{{ $auteur->biographie ? Str::limit(strip_tags($auteur->biographie), 160) : 'Profil et publications de ' . $auteur->prenom . ' ' . $auteur->nom }}">
    <meta property="og:url" content="{{ route('site.auteur.show', $auteur->getSlug()) }}">
    <meta property="og:type" content="profile">
    @if($auteur->photo)
    <meta property="og:image" content="{{ asset('storage/' . $auteur->photo) }}">
    @endif
@endpush

@push('styles')
<style>
    .author-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    }
    
    .social-link {
        transition: all 0.3s ease;
    }
    
    .social-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .publication-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .publication-card:hover {
        border-left-color: #3b82f6;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        transform: translateX(4px);
    }
    
    .badge-orcid {
        background: linear-gradient(135deg, #a6ce39 0%, #8bc34a 100%);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    
    <!-- Header avec photo et infos principales -->
    <div class="author-header text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                    
                    <!-- Photo de profil -->
                    <div class="flex-shrink-0">
                        @if($auteur->photo)
                            <img src="{{ asset('storage/' . $auteur->photo) }}" 
                                 alt="{{ $auteur->prenom }} {{ $auteur->nom }}"
                                 class="w-40 h-40 rounded-full object-cover border-4 border-white shadow-2xl">
                        @else
                            <div class="w-40 h-40 rounded-full bg-white/20 border-4 border-white shadow-2xl flex items-center justify-center">
                                <i class="fas fa-user text-6xl text-white/70"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Informations principales -->
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-4xl font-bold mb-2">
                            {{ $auteur->prenom }} {{ $auteur->nom }}
                        </h1>
                        
                        @if($auteur->titre_professionnel)
                            <p class="text-xl text-blue-100 mb-3">
                                {{ $auteur->titre_professionnel }}
                            </p>
                        @endif
                        
                        @if($auteur->institution)
                            <p class="text-lg text-blue-100 mb-4 flex items-center justify-center md:justify-start gap-2">
                                <i class="fas fa-university"></i>
                                {{ $auteur->institution }}
                            </p>
                        @endif
                        
                        <!-- ORCID -->
                        @if($auteur->orcid)
                            <a href="https://orcid.org/{{ $auteur->orcid }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 badge-orcid text-white px-4 py-2 rounded-full hover:opacity-90 transition-opacity">
                                <svg width="20" height="20" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor" d="M256 128c0 70.7-57.3 128-128 128S0 198.7 0 128S57.3 0 128 0s128 57.3 128 128z"/>
                                    <path fill="#FFF" d="M86.3 186.2H70.9V79.1h15.4v107.1zM108.9 79.1h41.6c39.6 0 57 28.3 57 53.6c0 27.5-21.5 53.6-56.8 53.6h-41.8V79.1zm15.4 93.3h24.5c34.9 0 42.9-26.5 42.9-39.7c0-21.5-13.7-39.7-43.7-39.7h-23.7v79.4z"/>
                                    <circle fill="#FFF" cx="78.2" cy="59" r="10"/>
                                </svg>
                                <span class="font-semibold">{{ $auteur->orcid }}</span>
                            </a>
                        @endif
                        
                        <!-- Statistiques -->
                        <div class="mt-6 flex flex-wrap items-center justify-center md:justify-start gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold">{{ $totalPublications }}</div>
                                <div class="text-sm text-blue-100">Publication{{ $totalPublications > 1 ? 's' : '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contenu principal -->
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Colonne principale -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Biographie -->
                    @if($auteur->biographie)
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-user-circle text-blue-600"></i>
                                Biographie
                            </h2>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($auteur->biographie)) !!}
                            </div>
                        </div>
                    @endif
                    
                    <!-- Publications -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <i class="fas fa-book text-blue-600"></i>
                            Publications
                            <span class="text-sm font-normal text-gray-500 ml-2">({{ $totalPublications }})</span>
                        </h2>
                        
                        @if($auteur->publications->count() > 0)
                            <div class="space-y-4">
                                @foreach($auteur->publications as $publication)
                                    <div class="publication-card bg-gray-50 rounded-lg p-4">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                            <a href="{{ route('publication.show', $publication->slug) }}" 
                                               class="hover:text-blue-600 transition-colors">
                                                {{ $publication->titre }}
                                            </a>
                                        </h3>
                                        
                                        @if($publication->annee)
                                            <p class="text-sm text-gray-500 mb-2">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ $publication->annee }}
                                            </p>
                                        @endif
                                        
                                        @if($publication->resume)
                                            <p class="text-gray-600 text-sm line-clamp-2">
                                                {{ Str::limit(strip_tags($publication->resume), 150) }}
                                            </p>
                                        @endif
                                        
                                        <div class="mt-3">
                                            <a href="{{ route('publication.show', $publication->slug) }}" 
                                               class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium text-sm">
                                                Lire la suite
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($totalPublications > 10)
                                <div class="mt-6 text-center">
                                    <p class="text-gray-600 text-sm">
                                        Affichage de 10 publications sur {{ $totalPublications }}
                                    </p>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500 text-center py-8">
                                <i class="fas fa-info-circle text-gray-400 text-2xl mb-2"></i><br>
                                Aucune publication disponible pour le moment.
                            </p>
                        @endif
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Coordonnées -->
                    @if($auteur->email || $auteur->telephone)
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">
                                <i class="fas fa-address-card text-blue-600"></i>
                                Contact
                            </h3>
                            <div class="space-y-3">
                                @if($auteur->email)
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-envelope text-gray-400 mt-1"></i>
                                        <a href="mailto:{{ $auteur->email }}" 
                                           class="text-blue-600 hover:underline break-all">
                                            {{ $auteur->email }}
                                        </a>
                                    </div>
                                @endif
                                
                                @if($auteur->telephone)
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-phone text-gray-400 mt-1"></i>
                                        <a href="tel:{{ $auteur->telephone }}" 
                                           class="text-blue-600 hover:underline">
                                            {{ $auteur->telephone }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <!-- Liens sociaux -->
                    @if($auteur->linkedin || $auteur->twitter || $auteur->facebook || $auteur->instagram || $auteur->github || $auteur->researchgate || $auteur->website)
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">
                                <i class="fas fa-share-alt text-blue-600"></i>
                                Réseaux sociaux
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                
                                @if($auteur->linkedin)
                                    <a href="{{ $auteur->linkedin }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="social-link flex items-center gap-2 bg-[#0077B5] text-white px-3 py-2 rounded-lg hover:opacity-90">
                                        <i class="fab fa-linkedin text-xl"></i>
                                        <span class="text-sm font-medium">LinkedIn</span>
                                    </a>
                                @endif
                                
                                @if($auteur->twitter)
                                    <a href="{{ $auteur->twitter }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="social-link flex items-center gap-2 bg-[#1DA1F2] text-white px-3 py-2 rounded-lg hover:opacity-90">
                                        <i class="fab fa-twitter text-xl"></i>
                                        <span class="text-sm font-medium">Twitter</span>
                                    </a>
                                @endif
                                
                                @if($auteur->facebook)
                                    <a href="{{ $auteur->facebook }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="social-link flex items-center gap-2 bg-[#1877F2] text-white px-3 py-2 rounded-lg hover:opacity-90">
                                        <i class="fab fa-facebook text-xl"></i>
                                        <span class="text-sm font-medium">Facebook</span>
                                    </a>
                                @endif
                                
                                @if($auteur->instagram)
                                    <a href="{{ $auteur->instagram }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="social-link flex items-center gap-2 bg-gradient-to-r from-purple-500 via-pink-500 to-orange-500 text-white px-3 py-2 rounded-lg hover:opacity-90">
                                        <i class="fab fa-instagram text-xl"></i>
                                        <span class="text-sm font-medium">Instagram</span>
                                    </a>
                                @endif
                                
                                @if($auteur->github)
                                    <a href="{{ $auteur->github }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="social-link flex items-center gap-2 bg-[#333] text-white px-3 py-2 rounded-lg hover:opacity-90">
                                        <i class="fab fa-github text-xl"></i>
                                        <span class="text-sm font-medium">GitHub</span>
                                    </a>
                                @endif
                                
                                @if($auteur->researchgate)
                                    <a href="{{ $auteur->researchgate }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="social-link flex items-center gap-2 bg-[#00D0B1] text-white px-3 py-2 rounded-lg hover:opacity-90">
                                        <i class="fab fa-researchgate text-xl"></i>
                                        <span class="text-sm font-medium">ResearchGate</span>
                                    </a>
                                @endif
                                
                                @if($auteur->website)
                                    <a href="{{ $auteur->website }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="social-link flex items-center gap-2 bg-gray-700 text-white px-3 py-2 rounded-lg hover:opacity-90">
                                        <i class="fas fa-globe text-xl"></i>
                                        <span class="text-sm font-medium">Website</span>
                                    </a>
                                @endif
                                
                            </div>
                        </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
