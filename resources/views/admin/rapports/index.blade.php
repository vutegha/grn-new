@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">Gestion des Rapports</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-admin.alert />
    
    <!-- En-tête avec statistiques -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Gestion des Rapports</h1>
                    <p class="text-white/80 mt-1">Bibliothèque institutionnelle des rapports et documents officiels</p>
                </div>
                @can('create', App\Models\Rapport::class)
                <a href="{{ route('admin.rapports.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white text-iri-primary rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Nouveau Rapport
                </a>
                @endcan
            </div>
        </div>
        
        <!-- Statistiques -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-gradient-to-r from-iri-primary/10 to-iri-secondary/10 rounded-lg p-4 border border-iri-primary/20">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-iri-primary/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-alt text-iri-primary"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-iri-gray">Total Rapports</p>
                            <p class="text-2xl font-semibold text-iri-dark">{{ $rapports->total() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-check text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-iri-gray">Cette Année</p>
                            <p class="text-2xl font-semibold text-iri-dark">
                                {{ $rapports->filter(function($r) { return $r->date_publication && \Carbon\Carbon::parse($r->date_publication)->year == now()->year; })->count() }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tags text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-iri-gray">Catégories</p>
                            <p class="text-2xl font-semibold text-iri-dark">{{ $categories->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-orange-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-iri-gray">Récents (30j)</p>
                            <p class="text-2xl font-semibold text-iri-dark">
                                {{ $rapports->filter(function($r) { return $r->created_at && $r->created_at >= now()->subDays(30); })->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-filter mr-3"></i>
                Filtres et recherche
            </h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.rapports.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Recherche -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-iri-gray mb-2">Recherche</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Rechercher par titre, description..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary">
                    </div>
                    
                    <!-- Année -->
                    <div>
                        <label class="block text-sm font-medium text-iri-gray mb-2">Année</label>
                        <select name="annee" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary">
                            <option value="">Toutes</option>
                            @foreach($annees as $annee)
                                <option value="{{ $annee }}" {{ request('annee') == $annee ? 'selected' : '' }}>{{ $annee }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Catégorie -->
                    <div>
                        <label class="block text-sm font-medium text-iri-gray mb-2">Catégorie</label>
                        <select name="categorie" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary">
                            <option value="">Toutes</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}" {{ request('categorie') == $categorie->id ? 'selected' : '' }}>
                                    {{ $categorie->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Rechercher
                    </button>
                    <a href="{{ route('admin.rapports.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Contenu principal avec design IRI moderne -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        @if($rapports->count() > 0)
            <!-- Vue en grille avec charte IRI -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($rapports as $rapport)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden group">
                        <!-- En-tête avec gradient IRI -->
                        <div class="bg-gradient-to-r from-iri-primary to-iri-secondary p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-file-alt text-white text-xl"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-bold text-white truncate font-poppins">
                                            {{ $rapport->titre }}
                                        </h3>
                                        @if($rapport->categorie)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-iri-accent text-white mt-2">
                                                <i class="fas fa-tag mr-1"></i>
                                                {{ $rapport->categorie->nom }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Corps de la carte -->
                        <div class="p-6">
                            @if($rapport->description)
                                <p class="text-iri-gray text-sm mb-6 line-clamp-3 leading-relaxed">
                                    {{ Str::limit($rapport->description, 120) }}
                                </p>
                            @endif

                            <!-- Informations de publication avec icônes IRI -->
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center text-sm text-iri-primary">
                                    <div class="w-8 h-8 bg-iri-light rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-calendar-alt text-iri-primary"></i>
                                    </div>
                                    <div>
                                        @if($rapport->date_publication)
                                            <span class="font-medium">Publié le {{ \Carbon\Carbon::parse($rapport->date_publication)->format('d/m/Y') }}</span>
                                        @else
                                            <span class="text-iri-gray">Date non définie</span>
                                        @endif
                                    </div>
                                </div>
                                @if($rapport->created_at)
                                    <div class="flex items-center text-sm text-iri-secondary">
                                        <div class="w-8 h-8 bg-iri-light rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-plus-circle text-iri-secondary"></i>
                                        </div>
                                        <span>Créé le {{ $rapport->created_at->format('d/m/Y') }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Informations du fichier avec design IRI -->
                            @if($rapport->fichier)
                                <div class="bg-gradient-to-r from-iri-light to-white rounded-xl p-4 mb-6 border border-iri-primary/20">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-file-pdf text-white"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-iri-primary truncate">
                                                    {{ basename($rapport->fichier) }}
                                                </p>
                                                @if(file_exists(public_path($rapport->fichier)))
                                                    @php
                                                        $fileSize = filesize(public_path($rapport->fichier));
                                                        $fileSizeFormatted = $fileSize > 1024 * 1024 
                                                            ? round($fileSize / (1024 * 1024), 1) . ' MB'
                                                            : round($fileSize / 1024, 1) . ' KB';
                                                    @endphp
                                                    <p class="text-xs text-iri-gray">{{ $fileSizeFormatted }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-4 mb-6">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-triangle text-amber-500 mr-3"></i>
                                        <p class="text-sm text-amber-700 font-medium">Aucun fichier attaché</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Actions avec design IRI -->
                        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-iri-light border-t border-gray-100">
                            <div class="flex items-center justify-between space-x-3">
                                <div class="flex items-center space-x-2">
                                    @can('view', $rapport)
                                        @if($rapport->fichier && file_exists(public_path($rapport->fichier)))
                                            <a href="{{ asset($rapport->fichier) }}" target="_blank"
                                               class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-iri-accent to-iri-gold rounded-lg hover:from-iri-gold hover:to-iri-accent transition-all duration-300 shadow-md hover:shadow-lg"
                                               title="Télécharger">
                                                <i class="fas fa-download mr-2"></i>
                                                Télécharger
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.rapports.show', $rapport) }}" 
                                           class="inline-flex items-center px-4 py-2 text-sm font-semibold text-iri-primary bg-iri-primary/10 rounded-lg hover:bg-iri-primary hover:text-white transition-all duration-300 border border-iri-primary/20"
                                           title="Voir le détail">
                                            <i class="fas fa-eye mr-2"></i>
                                            Voir
                                        </a>
                                    @endcan
                                </div>
                                
                                <div class="flex items-center space-x-1">
                                    @can('update', $rapport)
                                        <a href="{{ route('admin.rapports.edit', $rapport) }}" 
                                           class="inline-flex items-center p-2 text-blue-600 hover:text-white hover:bg-blue-600 rounded-lg transition-all duration-300 border border-blue-200 hover:border-blue-600"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $rapport)
                                        <form action="{{ route('admin.rapports.destroy', $rapport) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ? Cette action est irréversible.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-lg transition-all duration-300 border border-red-200 hover:border-red-600"
                                                    title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination moderne -->
            @if($rapports->hasPages())
                <div class="mt-16 flex justify-center">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                        {{ $rapports->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- État vide avec design IRI -->
            <div class="text-center py-20">
                <div class="mx-auto w-32 h-32 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-full flex items-center justify-center mb-8 shadow-xl">
                    <i class="fas fa-file-alt text-white text-5xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-iri-primary mb-6 font-poppins">Aucun rapport trouvé</h3>
                <p class="text-xl text-iri-gray mb-10 max-w-2xl mx-auto leading-relaxed">
                    @if(request()->hasAny(['search', 'annee', 'categorie']))
                        Aucun rapport ne correspond à vos critères de recherche. Essayez de modifier vos filtres ou d'élargir votre recherche.
                    @else
                        Commencez par ajouter votre premier rapport institutionnel pour enrichir la bibliothèque documentaire.
                    @endif
                </p>
                @can('create', App\Models\Rapport::class)
                    <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                        <a href="{{ route('admin.rapports.create') }}" 
                           class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-bold rounded-xl shadow-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-plus-circle mr-3 text-xl"></i>
                            <span class="text-lg">Créer un rapport</span>
                        </a>
                        @if(request()->hasAny(['search', 'annee', 'categorie']))
                            <a href="{{ route('admin.rapports.index') }}" 
                               class="inline-flex items-center px-8 py-4 bg-iri-gray text-white font-bold rounded-xl hover:bg-iri-dark transition-all duration-300 shadow-lg">
                                <i class="fas fa-redo mr-3"></i>
                                <span class="text-lg">Réinitialiser les filtres</span>
                            </a>
                        @endif
                    </div>
                @endcan
            </div>
        @endif
    </div>
</div>

<style>
    /* Animations personnalisées IRI */
    .group:hover .group-hover\:animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Effets de glassmorphism */
    .backdrop-blur-sm {
        backdrop-filter: blur(8px);
    }
    
    /* Styles pour les sélecteurs et inputs */
    select:focus,
    input:focus {
        box-shadow: 0 0 0 3px rgba(30, 71, 47, 0.1);
    }
    
    /* Animation des cartes */
    @keyframes cardHover {
        from { transform: translateY(0) scale(1); }
        to { transform: translateY(-8px) scale(1.02); }
    }
    
    .group:hover {
        animation: cardHover 0.3s ease-out forwards;
    }
</style>

<script>
    // Amélioration de l'UX avec des animations
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des cartes au scroll
        const cards = document.querySelectorAll('.group');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    });
</script>
@endsection

