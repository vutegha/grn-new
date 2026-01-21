@extends('layouts.admin')

@section('title', 'Services')
@section('subtitle', 'Gestion des services GRN-UCBC')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header avec bouton d'action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Gestion des Services</h1>
            <p class="text-gray-600 flex items-center">
                <i class="fas fa-cogs text-blue-500 mr-2"></i>
                {{ optional($services)->count() ?? 0 }} service(s) enregistré(s)
            </p>
        </div>
        @can('create', \App\Models\Service::class)
        <a href="{{ route('admin.service.create') }}" 
           class="group relative inline-flex items-center px-8 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-xl hover:from-iri-secondary hover:to-iri-primary transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 border border-iri-accent/20">
            <i class="fas fa-plus-circle mr-3 text-lg group-hover:rotate-90 transition-transform duration-300"></i>
            <span class="relative">
                Nouveau Service
                <div class="absolute inset-0 bg-gradient-to-r from-iri-gold/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded"></div>
            </span>
        </a>
        @endcan
    </div>

    <!-- Cartes statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
        <!-- Total Services -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 iri-shine">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-iri-primary/20 rounded-lg flex items-center justify-center border border-iri-primary/30">
                        <i class="fas fa-cogs text-iri-primary text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Total Services</p>
                    <p class="text-2xl font-bold text-iri-primary">{{ optional($services)->count() ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Services Publiés -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 iri-shine">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-iri-accent/20 rounded-lg flex items-center justify-center border border-iri-accent/30">
                        <i class="fas fa-check-circle text-iri-accent text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Publiés</p>
                    <p class="text-2xl font-bold text-iri-accent">{{ optional($services)->where('is_published', true)->count() ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- En Attente -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 iri-shine">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-iri-gold/20 rounded-lg flex items-center justify-center border border-iri-gold/30">
                        <i class="fas fa-clock text-iri-gold text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">En Attente</p>
                    <p class="text-2xl font-bold text-iri-gold">{{ optional($services)->where('is_published', false)->count() ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Services Menu -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 iri-shine">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center border border-purple-500/30">
                        <i class="fas fa-list-ul text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Services Menu</p>
                    <p class="text-2xl font-bold text-purple-600">
                        {{ optional($services)->filter(function($service) {
                            return $service->is_published && 
                                   $service->show_in_menu;
                        })->count() ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Avec Description -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 iri-shine">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-coral/20 rounded-lg flex items-center justify-center border border-coral/40">
                        <i class="fas fa-align-left text-coral text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Avec Description</p>
                    <p class="text-2xl font-bold text-coral">{{ optional($services)->whereNotNull('description')->count() ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grille des services -->
    @if(optional($services)->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($services as $service)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                    <!-- Image du service -->
                    <div class="relative">
                        <div class="h-48 bg-gray-100 flex items-center justify-center overflow-hidden">
                            <img src="{{ $service->image_url }}" 
                                 class="w-full h-full object-cover" 
                                 alt="Image du service {{ $service->nom }}"
                                 loading="lazy">
                        </div>
                        
                        <!-- Badges de statut -->
                        <div class="absolute top-3 left-3 flex space-x-2">
                                                        @if($service->is_published)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-iri-accent/20 text-iri-accent border border-iri-accent/30">
                                    <i class="fas fa-check-circle mr-1"></i>Publié
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-iri-gold/20 text-iri-gold border border-iri-gold/30">
                                    <i class="fas fa-clock mr-1"></i>Brouillon
                                </span>
                            @endif
                            
                            @if($service->nom_menu)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-iri-secondary/20 text-iri-secondary border border-iri-secondary/30">
                                    <i class="fas fa-bars mr-1"></i>Menu
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Contenu de la carte -->
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">
                                {{ $service->nom }}
                            </h3>
                        </div>
                        
                        @if($service->nom_menu)
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <i class="fas fa-tag mr-2 text-iri-accent"></i>
                                <span>Menu: {{ $service->nom_menu }}</span>
                            </div>
                        @endif
                        
                        @if($service->resume)
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                {{ Str::limit($service->resume, 100) }}
                            </p>
                        @endif
                        
                        @if($service->description)
                            <p class="text-gray-500 text-xs mb-4 line-clamp-3">
                                {{ Str::limit($service->description, 150) }}
                            </p>
                        @endif
                        
                        <!-- Actions -->
                        <div class="flex justify-center space-x-3 pt-4 border-t border-gray-100">
                            @can('view', $service)
                            <!-- Voir -->
                            <a href="{{ route('admin.service.show', $service) }}" 
                               class="group relative inline-flex items-center justify-center w-12 h-12 bg-gradient-to-r from-iri-accent to-iri-gold text-white rounded-xl hover:from-iri-gold hover:to-iri-accent transform hover:scale-110 transition-all duration-300 shadow-lg hover:shadow-xl border-2 border-iri-accent/20"
                               title="Voir le service">
                                <i class="fas fa-eye text-lg group-hover:rotate-12 transition-transform duration-300"></i>
                                <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap shadow-lg z-10">
                                    Voir le service
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                </div>
                            </a>
                            @endcan
                            
                            @can('update', $service)
                            <!-- Modifier -->
                            <a href="{{ route('admin.service.edit', $service) }}" 
                               class="group relative inline-flex items-center justify-center w-12 h-12 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-xl hover:from-iri-secondary hover:to-iri-primary transform hover:scale-110 transition-all duration-300 shadow-lg hover:shadow-xl border-2 border-iri-primary/20"
                               title="Modifier le service">
                                <i class="fas fa-edit text-lg group-hover:rotate-12 transition-transform duration-300"></i>
                                <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap shadow-lg z-10">
                                    Modifier le service
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                </div>
                            </a>
                            @endcan
                            
                            @can('delete', $service)
                            <!-- Supprimer -->
                            <form action="{{ route('admin.service.destroy', $service) }}" method="POST" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')" 
                                  class="inline-block">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" 
                                        class="group relative inline-flex items-center justify-center w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 transform hover:scale-110 transition-all duration-300 shadow-lg hover:shadow-xl border-2 border-red-500/20"
                                        title="Supprimer le service">
                                    <i class="fas fa-trash text-lg group-hover:rotate-12 transition-transform duration-300"></i>
                                    <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap shadow-lg z-10">
                                        Supprimer le service
                                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                    </div>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- État vide -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-cogs text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun service trouvé</h3>
                <p class="text-gray-600 mb-6">Commencez par créer votre premier service pour le GRN-UCBC.</p>
                <a href="{{ route('admin.service.create') }}" 
                   class="group relative inline-flex items-center px-8 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-xl hover:from-iri-secondary hover:to-iri-primary transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-plus-circle mr-3 text-lg group-hover:rotate-90 transition-transform duration-300"></i>
                    <span class="relative">
                        Créer un service
                        <div class="absolute inset-0 bg-gradient-to-r from-iri-gold/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded"></div>
                    </span>
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Styles CSS pour les animations et effets IRI -->
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

/* Animation pour les cartes au hover avec thème IRI */
.transform {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Effet de survol sur les cartes statistiques IRI */
.hover\:shadow-md:hover {
    box-shadow: 0 8px 25px rgba(30, 71, 47, 0.15);
}

/* Gradients pour les cartes statistiques avec couleurs IRI */
.bg-gradient-to-br {
    background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
}

/* Animation spéciale pour les boutons IRI */
.iri-button-glow:hover {
    box-shadow: 0 0 20px rgba(30, 71, 47, 0.4);
}

/* Effets avancés pour les boutons d'action */
.group:hover .fas {
    transform: scale(1.1);
}

/* Effet de lueur pour le bouton modifier */
.bg-gradient-to-r.from-iri-primary:hover {
    box-shadow: 0 0 20px rgba(30, 71, 47, 0.6);
}

/* Effet de lueur pour le bouton supprimer */
.bg-gradient-to-r.from-red-500:hover {
    box-shadow: 0 0 20px rgba(239, 68, 68, 0.6);
}

/* Animation de pulsation pour attirer l'attention */
@keyframes iri-pulse-attention {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(30, 71, 47, 0.7);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(30, 71, 47, 0);
    }
}

@keyframes red-pulse-attention {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
    }
}

/* Effet de pulsation pour les éléments interactifs */
@keyframes iri-pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

.iri-pulse {
    animation: iri-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Effet de brillance IRI */
.iri-shine {
    position: relative;
    overflow: hidden;
}

.iri-shine::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(184, 134, 11, 0.3), transparent);
    transition: left 0.5s ease-in-out;
}

.iri-shine:hover::before {
    left: 100%;
}

/* Effet de shake au hover pour les boutons de suppression */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-2px); }
    75% { transform: translateX(2px); }
}

.group:hover .from-red-500 {
    animation: shake 0.5s ease-in-out;
}

/* Amélioration de la visibilité des tooltips */
.group:hover [class*="absolute"] {
    z-index: 50;
}
</style>
@endsection
