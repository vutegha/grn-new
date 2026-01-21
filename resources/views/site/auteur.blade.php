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
    .hero-pattern {
        background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 50%, #ddd6fe 100%);
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .profile-avatar {
        position: relative;
        border: 5px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }
    
    .profile-avatar::after {
        content: '';
        position: absolute;
        inset: -5px;
        border-radius: 50%;
        padding: 5px;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6, #ec4899);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .profile-avatar:hover::after {
        opacity: 1;
    }
    
    .stat-card {
        background: white;
        border: 2px solid rgba(59, 130, 246, 0.3);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(59, 130, 246, 0.25);
        border-color: rgba(59, 130, 246, 0.5);
    }
    
    .social-link {
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .social-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }
    
    .social-link:hover::before {
        left: 100%;
    }
    
    .social-link:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    
    .publication-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .publication-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: linear-gradient(180deg, #3b82f6, #8b5cf6);
        transform: scaleY(0);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .publication-card:hover::before {
        transform: scaleY(1);
    }
    
    .publication-card:hover {
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        transform: translateX(8px);
    }
    
    .badge-orcid {
        background: white;
        color: #1e7e34;
        border: 2px solid #28a745;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        transition: all 0.3s;
        font-weight: 700;
    }
    
    .badge-orcid:hover {
        background: linear-gradient(135deg, #a6ce39 0%, #5cb85c 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(166, 206, 57, 0.5);
        transform: translateY(-2px);
    }
    
    .badge-orcid:hover svg path:first-child {
        fill: white;
    }
    
    .section-title {
        position: relative;
        padding-bottom: 12px;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        border-radius: 2px;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/30">
    
    <!-- Hero Header avec effet de profondeur -->
    <div class="hero-pattern relative overflow-hidden pb-4">
        <!-- Animated background elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-400/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        </div>
        
        <div class="relative container mx-auto px-4 py-8">
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    
                    <!-- Photo de profil avec effet glassmorphism -->
                    <div class="flex-shrink-0 animate-fade-in">
                        @if($auteur->photo)
                            <img src="{{ asset('storage/' . $auteur->photo) }}" 
                                 alt="{{ $auteur->prenom }} {{ $auteur->nom }}"
                                 class="profile-avatar w-48 h-48 rounded-full object-cover">
                        @else
                            <div class="profile-avatar w-48 h-48 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <i class="fas fa-user text-7xl text-white/90"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Informations principales -->
                    <div class="flex-1 text-left animate-fade-in" style="animation-delay: 0.2s;">
                        <h1 class="text-5xl md:text-6xl font-bold mb-3 text-gray-900">
                            {{ $auteur->prenom }} {{ $auteur->nom }}
                        </h1>
                        
                        @if($auteur->titre_professionnel)
                            <p class="text-2xl text-gray-700 mb-4 font-light">
                                {{ $auteur->titre_professionnel }}
                            </p>
                        @endif
                        
                        @if($auteur->institution)
                            <p class="text-xl text-gray-700 mb-2 flex items-center gap-3">
                                <i class="fas fa-university text-gray-600"></i>
                                {{ $auteur->institution }}
                            </p>
                        @endif
                        
                        <!-- ORCID Badge -->
                        @if($auteur->orcid)
                            <div>
                                <a href="https://orcid.org/{{ $auteur->orcid }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center gap-3 badge-orcid px-6 py-3 rounded-full font-semibold text-lg">
                                    <svg width="24" height="24" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
                                        <path fill="#5cb85c" d="M256 128c0 70.7-57.3 128-128 128S0 198.7 0 128S57.3 0 128 0s128 57.3 128 128z"/>
                                        <path fill="#FFF" d="M86.3 186.2H70.9V79.1h15.4v107.1zM108.9 79.1h41.6c39.6 0 57 28.3 57 53.6c0 27.5-21.5 53.6-56.8 53.6h-41.8V79.1zm15.4 93.3h24.5c34.9 0 42.9-26.5 42.9-39.7c0-21.5-13.7-39.7-43.7-39.7h-23.7v79.4z"/>
                                        <circle fill="#FFF" cx="78.2" cy="59" r="10"/>
                                    </svg>
                                    <span>{{ $auteur->orcid }}</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contenu principal -->
    <div class="container mx-auto px-4 -mt-20 relative z-10">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                
                <!-- Colonne principale -->
                <div class="lg:col-span-3 space-y-2">
                    
                    <!-- Biographie -->
                    <div class="glass-card rounded-2xl shadow-xl p-4 animate-fade-in" style="animation-delay: 0.3s;">
                        @if($auteur->biographie)
                            <div class="text-gray-900 leading-relaxed text-sm">
                                {!! nl2br(e($auteur->biographie)) !!}
                            </div>
                        @else
                            <div class="text-center py-6">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-user-edit text-gray-400 text-lg"></i>
                                </div>
                                <p class="text-gray-500 text-sm">Biographie non renseignée</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Publications -->
                    <div class="glass-card rounded-2xl shadow-xl p-6 animate-fade-in" style="animation-delay: 0.4s;">
                        <h2 class="section-title text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <i class="fas fa-book text-white text-lg"></i>
                            </div>
                            Publications
                            <span class="ml-auto text-base font-semibold text-blue-600">({{ $totalPublications }})</span>
                        </h2>
                        
                        @if($auteur->publications->count() > 0)
                            <ul class="space-y-2">
                                @foreach($auteur->publications as $publication)
                                    <li class="flex items-start gap-3 py-1.5 px-2 rounded hover:bg-blue-50 transition-colors group">
                                        <i class="fas fa-circle text-blue-500 text-xs mt-1.5 flex-shrink-0"></i>
                                        <a href="{{ route('publication.show', $publication->slug) }}" 
                                           class="text-gray-900 hover:text-blue-600 transition-colors text-sm leading-tight flex-1 font-medium">
                                            {{ $publication->titre }}
                                            @if($publication->annee)
                                                <span class="text-gray-500 text-xs ml-2 font-normal">({{ $publication->annee }})</span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            
                            @if($totalPublications > 10)
                                <div class="mt-4 pt-3 border-t border-gray-200">
                                    <p class="text-gray-600 text-xs text-center">
                                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                        Affichage de 10 publications sur <span class="font-bold">{{ $totalPublications }}</span>
                                    </p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-6">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-book-open text-gray-400 text-lg"></i>
                                </div>
                                <p class="text-gray-500 text-sm">Aucune publication disponible.</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="space-y-4">
                    
                    <!-- Coordonnées -->
                    @if($auteur->email || $auteur->telephone)
                        <div class="glass-card rounded-2xl shadow-xl p-5 animate-fade-in" style="animation-delay: 0.5s;">
                            <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                                <i class="fas fa-address-card text-blue-600"></i>
                                Contact
                            </h3>
                            <div class="space-y-2">
                                @if($auteur->email)
                                    <div class="flex items-start gap-2 p-2 rounded-lg hover:bg-blue-50 transition-colors">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-envelope text-blue-600 text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Email</p>
                                            <a href="mailto:{{ $auteur->email }}" 
                                               class="text-blue-600 hover:text-blue-700 font-medium break-all text-xs">
                                                {{ $auteur->email }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($auteur->telephone)
                                    <div class="flex items-start gap-2 p-2 rounded-lg hover:bg-blue-50 transition-colors">
                                        <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-phone text-green-600 text-sm"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Téléphone</p>
                                            <a href="tel:{{ $auteur->telephone }}" 
                                               class="text-gray-900 hover:text-blue-600 font-medium text-xs">
                                                {{ $auteur->telephone }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <!-- Liens sociaux -->
                    @if($auteur->linkedin || $auteur->twitter || $auteur->facebook || $auteur->instagram || $auteur->github || $auteur->researchgate || $auteur->website)
                        <div class="glass-card rounded-2xl shadow-xl p-5 animate-fade-in" style="animation-delay: 0.6s;">
                            <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                                <i class="fas fa-share-alt text-purple-600"></i>
                                Réseaux
                            </h3>
                            <div class="grid grid-cols-1 gap-2">
                                
                                @if($auteur->linkedin)
                                    <a href="{{ $auteur->linkedin }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="social-link flex items-center gap-2 bg-[#0077B5] text-white px-3 py-2 rounded-lg font-medium text-sm">
                                        <i class="fab fa-linkedin text-lg"></i>
                                        <span>LinkedIn</span>
                                        <i class="fas fa-external-link-alt text-xs ml-auto opacity-70"></i>
                                    </a>
                                @endif
                                
                                @if($auteur->twitter)
                                    <a href="{{ $auteur->twitter }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="social-link flex items-center gap-2 bg-[#1DA1F2] text-white px-3 py-2 rounded-lg font-medium text-sm">
                                        <i class="fab fa-twitter text-lg"></i>
                                        <span>Twitter</span>
                                        <i class="fas fa-external-link-alt text-xs ml-auto opacity-70"></i>
                                    </a>
                                @endif
                                
                                @if($auteur->facebook)
                                    <a href="{{ $auteur->facebook }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="social-link flex items-center gap-2 bg-[#1877F2] text-white px-3 py-2 rounded-lg font-medium text-sm">
                                        <i class="fab fa-facebook text-lg"></i>
                                        <span>Facebook</span>
                                        <i class="fas fa-external-link-alt text-xs ml-auto opacity-70"></i>
                                    </a>
                                @endif
                                
                                @if($auteur->instagram)
                                    <a href="{{ $auteur->instagram }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="social-link flex items-center gap-2 bg-gradient-to-r from-purple-500 via-pink-500 to-orange-500 text-white px-3 py-2 rounded-lg font-medium text-sm">
                                        <i class="fab fa-instagram text-lg"></i>
                                        <span>Instagram</span>
                                        <i class="fas fa-external-link-alt text-xs ml-auto opacity-70"></i>
                                    </a>
                                @endif
                                
                                @if($auteur->github)
                                    <a href="{{ $auteur->github }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="social-link flex items-center gap-2 bg-[#24292e] text-white px-3 py-2 rounded-lg font-medium text-sm">
                                        <i class="fab fa-github text-lg"></i>
                                        <span>GitHub</span>
                                        <i class="fas fa-external-link-alt text-xs ml-auto opacity-70"></i>
                                    </a>
                                @endif
                                
                                @if($auteur->researchgate)
                                    <a href="{{ $auteur->researchgate }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="social-link flex items-center gap-2 bg-[#00D0B1] text-white px-3 py-2 rounded-lg font-medium text-sm">
                                        <i class="fab fa-researchgate text-lg"></i>
                                        <span>ResearchGate</span>
                                        <i class="fas fa-external-link-alt text-xs ml-auto opacity-70"></i>
                                    </a>
                                @endif
                                
                                @if($auteur->website)
                                    <a href="{{ $auteur->website }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="social-link flex items-center gap-2 bg-gradient-to-r from-gray-700 to-gray-900 text-white px-3 py-2 rounded-lg font-medium text-sm">
                                        <i class="fas fa-globe text-lg"></i>
                                        <span>Site Web</span>
                                        <i class="fas fa-external-link-alt text-xs ml-auto opacity-70"></i>
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
