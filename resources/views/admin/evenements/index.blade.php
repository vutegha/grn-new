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
                <span class="text-white">Gestion des Événements</span>
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
                    <h1 class="text-2xl font-bold text-white">Gestion des Événements</h1>
                    <p class="text-white/80 mt-1">Organisation et gestion des événements GRN-UCBC</p>
                </div>
                @can('create_evenements')
                <a href="{{ route('admin.evenements.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white text-iri-primary rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Nouvel Événement
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
                                <i class="fas fa-calendar-alt text-iri-primary"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-iri-gray">Total Événements</p>
                            <p class="text-2xl font-semibold text-iri-dark">{{ $evenements->total() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-iri-gray">À venir</p>
                            <p class="text-2xl font-semibold text-iri-dark">{{ $evenements->where('date_evenement', '>', now())->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-play-circle text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-iri-gray">En cours</p>
                            <p class="text-2xl font-semibold text-iri-dark">{{ $evenements->where('date_evenement', '<=', now())->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-gray-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-iri-gray">Passés</p>
                            <p class="text-2xl font-semibold text-iri-dark">{{ $evenements->where('date_evenement', '<', now())->count() }}</p>
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
            <form method="GET" action="{{ route('admin.evenements.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Recherche -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-iri-gray mb-2">Recherche</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Rechercher par titre..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary">
                    </div>
                    
                    <!-- État -->
                    <div>
                        <label class="block text-sm font-medium text-iri-gray mb-2">État</label>
                        <select name="etat" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary">
                            <option value="">Tous</option>
                            <option value="a_venir" {{ request('etat') === 'a_venir' ? 'selected' : '' }}>À venir</option>
                            <option value="en_cours" {{ request('etat') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="passe" {{ request('etat') === 'passe' ? 'selected' : '' }}>Passés</option>
                        </select>
                    </div>
                    
                    <!-- Année -->
                    <div>
                        <label class="block text-sm font-medium text-iri-gray mb-2">Année</label>
                        <select name="annee" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary">
                            <option value="">Toutes</option>
                            @if(isset($anneesDisponibles))
                                @foreach($anneesDisponibles as $annee)
                                    <option value="{{ $annee }}" {{ request('annee') == $annee ? 'selected' : '' }}>{{ $annee }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Rechercher
                    </button>
                    <a href="{{ route('admin.evenements.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des événements -->

    <!-- Liste des événements -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-list mr-3"></i>
                Liste des événements
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-iri-gray uppercase tracking-wider">Événement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-iri-gray uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-iri-gray uppercase tracking-wider">Lieu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-iri-gray uppercase tracking-wider">État</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-iri-gray uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($evenements as $evenement)
                        <tr class="hover:bg-iri-primary/5 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-iri-primary/20 to-iri-secondary/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-iri-primary"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-iri-dark">{{ Str::limit($evenement->titre, 40) }}</div>
                                        @if($evenement->resume)
                                            <div class="text-sm text-iri-gray">{{ Str::limit($evenement->resume, 60) }}</div>
                                        @elseif($evenement->description)
                                            <div class="text-sm text-iri-gray">{{ Str::limit($evenement->description, 60) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-iri-dark">
                                    {{ $evenement->date_evenement ? $evenement->date_evenement->format('d/m/Y') : 'Non définie' }}
                                </div>
                                <div class="text-xs text-iri-gray">
                                    {{ $evenement->date_evenement ? $evenement->date_evenement->format('H:i') : '' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-iri-dark">{{ $evenement->lieu ?? 'Non spécifié' }}</div>
                                @if($evenement->adresse)
                                    <div class="text-xs text-iri-gray">{{ Str::limit($evenement->adresse, 30) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-evenement-badge :evenement="$evenement" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    @can('view_evenements')
                                    <a href="{{ route('admin.evenements.show', $evenement) }}" 
                                       class="inline-flex items-center p-2 text-iri-primary hover:text-iri-secondary hover:bg-iri-primary/10 rounded-lg transition-all duration-200"
                                       title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endcan
                                    
                                    @can('update_evenements')
                                    <!-- Action Mettre en vedette / Retirer de la vedette -->
                                    <form action="{{ route('admin.evenements.toggle-featured', $evenement) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="inline-flex items-center p-2 {{ $evenement->en_vedette ? 'text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50' : 'text-gray-400 hover:text-yellow-600 hover:bg-yellow-50' }} rounded-lg transition-all duration-200"
                                                title="{{ $evenement->en_vedette ? 'Retirer de la vedette' : 'Mettre en vedette' }}">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                    @endcan
                                    
                                    @can('update_evenements')
                                    <a href="{{ route('admin.evenements.edit', $evenement) }}" 
                                       class="inline-flex items-center p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-200"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('delete_evenements')
                                    <form action="{{ route('admin.evenements.destroy', $evenement) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-iri-primary/10 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-calendar-alt text-iri-primary text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-iri-dark mb-2">Aucun événement trouvé</h3>
                                    <p class="text-iri-gray mb-4">Commencez par créer votre premier événement.</p>
                                    @can('create_evenements')
                                    <a href="{{ route('admin.evenements.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200">
                                        <i class="fas fa-plus mr-2"></i>
                                        Créer un événement
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($evenements->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $evenements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
