@extends('layouts.admin')

@section('title', 'Publications')
@section('subtitle', 'Gestion des publications scientifiques')

@section('breadcrumbs')
    <li aria-current="page">
        <div class="flex items-center">
            <i class="fas fa-chevron-right mx-2 text-iri-gray/50"></i>
            <span class="text-iri-primary font-medium">Publications</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header moderne avec actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="px-6 py-6 bg-gradient-to-r from-iri-primary to-iri-secondary">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="bi bi-journal-text text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Publications Scientifiques</h1>
                        <p class="text-white/80 text-sm mt-1">Gestion des publications de recherche</p>
                    </div>
                </div>
                <div class="mt-4 lg:mt-0 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.publication.create') }}" 
                       class="bg-white text-iri-primary px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors duration-200 inline-flex items-center justify-center font-medium">
                        <i class="bi bi-plus-circle mr-2"></i>
                        Nouvelle Publication
                    </a>
                    <button onclick="exportPublications()" 
                            class="bg-iri-accent text-white px-4 py-2 rounded-lg hover:bg-iri-accent/90 transition-colors duration-200 inline-flex items-center justify-center">
                        <i class="bi bi-download mr-2"></i>
                        Exporter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Publications</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-iri-primary/10 rounded-lg flex items-center justify-center">
                    <i class="bi bi-journal-text text-iri-primary"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Publiées</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['published'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-check-circle text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Brouillons</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['draft'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-pencil text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Articles</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['articles'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-journal-text text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Conférences</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['conferences'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-people text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Cette Année</p>
                    <p class="text-2xl font-bold text-iri-accent">{{ $stats['this_year'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-calendar text-iri-accent"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.publication.index') }}" class="space-y-4" id="filter-form">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                        <input type="search" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}"
                               placeholder="Titre, auteur, DOI..."
                               class="w-full rounded-lg border-gray-300 focus:border-iri-primary focus:ring-2 focus:ring-iri-primary/20 transition-colors duration-200">
                    </div>
                    
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select name="statut" 
                                id="statut" 
                                class="w-full rounded-lg border-gray-300 focus:border-iri-primary focus:ring-2 focus:ring-iri-primary/20 transition-colors duration-200">
                            <option value="">Tous</option>
                            <option value="published" {{ request('statut') == 'published' ? 'selected' : '' }}>Publié</option>
                            <option value="draft" {{ request('statut') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                        </select>
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" 
                                id="type" 
                                class="w-full rounded-lg border-gray-300 focus:border-iri-primary focus:ring-2 focus:ring-iri-primary/20 transition-colors duration-200">
                            <option value="">Tous</option>
                            <option value="article" {{ request('type') == 'article' ? 'selected' : '' }}>Article</option>
                            <option value="conference" {{ request('type') == 'conference' ? 'selected' : '' }}>Conférence</option>
                            <option value="livre" {{ request('type') == 'livre' ? 'selected' : '' }}>Livre</option>
                            <option value="chapitre" {{ request('type') == 'chapitre' ? 'selected' : '' }}>Chapitre</option>
                            <option value="rapport" {{ request('type') == 'rapport' ? 'selected' : '' }}>Rapport</option>
                        </select>
                    </div>

                    <div>
                        <label for="annee" class="block text-sm font-medium text-gray-700 mb-2">Année</label>
                        <select name="annee" 
                                id="annee" 
                                class="w-full rounded-lg border-gray-300 focus:border-iri-primary focus:ring-2 focus:ring-iri-primary/20 transition-colors duration-200">
                            <option value="">Toutes</option>
                            @for($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ request('annee') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" 
                            class="bg-iri-primary text-white px-4 py-2 rounded-lg hover:bg-iri-secondary transition-colors duration-200 inline-flex items-center justify-center">
                        <i class="bi bi-funnel mr-2"></i>
                        Filtrer
                    </button>
                    <a href="{{ route('admin.publication.index') }}" 
                       class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200 inline-flex items-center justify-center">
                        <i class="bi bi-arrow-counterclockwise mr-2"></i>
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Toggle des vues -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <span class="text-sm font-medium text-gray-700">{{ $publications->total() }} publication(s)</span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="toggleView('grid')" 
                    id="grid-btn"
                    class="view-toggle-btn bg-iri-primary text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                <i class="bi bi-grid-3x3-gap mr-1"></i>
                Grille
            </button>
            <button onclick="toggleView('list')" 
                    id="list-btn"
                    class="view-toggle-btn bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                <i class="bi bi-list-ul mr-1"></i>
                Liste
            </button>
        </div>
    </div>

    <!-- Vue grille -->
    <div id="grid-view" class="view-content">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($publications as $publication)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-lg flex items-center justify-center">
                                <i class="bi bi-file-earmark-text text-white"></i>
                            </div>
                            <div>
                                @if($publication->is_published)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <div class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></div>
                                        Publié
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-2"></div>
                                        Brouillon
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="text-gray-400 hover:text-gray-600 p-1 rounded-md hover:bg-gray-100 transition-colors duration-200">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <div x-show="open" 
                                 @click.away="open = false" 
                                 x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
                                <a href="{{ route('admin.publication.show', $publication) }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="bi bi-eye mr-2"></i>Voir
                                </a>
                                <a href="{{ route('admin.publication.edit', $publication) }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="bi bi-pencil mr-2"></i>Modifier
                                </a>
                                @if($publication->is_published)
                                    <button onclick="unpublishPublication({{ $publication->id }})" 
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="bi bi-eye-slash mr-2"></i>Dépublier
                                    </button>
                                @else
                                    <button onclick="publishPublication({{ $publication->id }})" 
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="bi bi-check-circle mr-2"></i>Publier
                                    </button>
                                @endif
                                <hr class="my-1">
                                <form action="{{ route('admin.publication.destroy', $publication) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="bi bi-trash mr-2"></i>Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                        {{ $publication->titre }}
                    </h3>
                    
                    @if($publication->resume)
                        <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                            {{ Str::limit($publication->resume, 150) }}
                        </p>
                    @endif
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="bi bi-person mr-2"></i>
                            {{ $publication->auteur_principal }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="bi bi-calendar mr-2"></i>
                            {{ $publication->created_at->format('d/m/Y') }}
                        </div>
                        @if($publication->doi)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="bi bi-link-45deg mr-2"></i>
                                {{ Str::limit($publication->doi, 30) }}
                            </div>
                        @endif
                    </div>
                    
                    @php
                        $typeColors = [
                            'article' => 'bg-green-100 text-green-800',
                            'conference' => 'bg-blue-100 text-blue-800',
                            'livre' => 'bg-purple-100 text-purple-800',
                            'chapitre' => 'bg-orange-100 text-orange-800',
                            'rapport' => 'bg-gray-100 text-gray-800'
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $typeColors[$publication->type] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($publication->type) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Vue liste -->
    <div id="list-view" class="view-content hidden">
        <x-admin-table>
            <x-slot name="header">
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Publication
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Auteur
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Type
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </x-slot>

            @foreach($publications as $publication)
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-lg flex items-center justify-center">
                                <i class="bi bi-file-earmark-text text-white text-lg"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm font-medium text-gray-900 group-hover:text-iri-primary transition-colors duration-200 line-clamp-2">
                                    {{ $publication->titre }}
                                </h3>
                                @if($publication->resume)
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                        {{ Str::limit($publication->resume, 120) }}
                                    </p>
                                @endif
                                @if($publication->doi)
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="bi bi-link-45deg mr-1"></i>
                                            DOI: {{ $publication->doi }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 bg-iri-accent rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-medium">
                                    {{ substr($publication->auteur_principal, 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $publication->auteur_principal }}
                                </div>
                                @if($publication->auteurs_secondaires)
                                    <div class="text-xs text-gray-500">
                                        +{{ count(explode(',', $publication->auteurs_secondaires)) }} co-auteur(s)
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $typeColors = [
                                'article' => 'bg-green-100 text-green-800',
                                'conference' => 'bg-blue-100 text-blue-800',
                                'livre' => 'bg-purple-100 text-purple-800',
                                'chapitre' => 'bg-orange-100 text-orange-800',
                                'rapport' => 'bg-gray-100 text-gray-800'
                            ];
                            $typeIcons = [
                                'article' => 'bi-journal-text',
                                'conference' => 'bi-people',
                                'livre' => 'bi-book',
                                'chapitre' => 'bi-bookmark',
                                'rapport' => 'bi-file-earmark-ruled'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $typeColors[$publication->type] ?? 'bg-gray-100 text-gray-800' }}">
                            <i class="bi {{ $typeIcons[$publication->type] ?? 'bi-file' }} mr-1"></i>
                            {{ ucfirst($publication->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($publication->is_published)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <div class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></div>
                                Publié
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-2"></div>
                                Brouillon
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ $publication->created_at->format('d/m/Y') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $publication->created_at->format('H:i') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.publication.show', $publication) }}" 
                               class="text-iri-primary hover:text-iri-secondary p-1 rounded-md hover:bg-iri-primary/10 transition-colors duration-200"
                               title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.publication.edit', $publication) }}" 
                               class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-50 transition-colors duration-200"
                               title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($publication->is_published)
                                <button onclick="unpublishPublication({{ $publication->id }})" 
                                        class="text-orange-600 hover:text-orange-900 p-1 rounded-md hover:bg-orange-50 transition-colors duration-200"
                                        title="Dépublier">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            @else
                                <button onclick="publishPublication({{ $publication->id }})" 
                                        class="text-green-600 hover:text-green-900 p-1 rounded-md hover:bg-green-50 transition-colors duration-200"
                                        title="Publier">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                            @endif
                            <form action="{{ route('admin.publication.destroy', $publication) }}" 
                                  method="POST" 
                                  class="inline-block"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition-colors duration-200"
                                        title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-admin-table>
    </div>

    <!-- Pagination -->
    @if($publications->hasPages())
        <div class="mt-8">
            {{ $publications->links() }}
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    // Gestion des vues
    function toggleView(view) {
        const gridView = document.getElementById('grid-view');
        const listView = document.getElementById('list-view');
        const gridBtn = document.getElementById('grid-btn');
        const listBtn = document.getElementById('list-btn');
        
        if (view === 'grid') {
            gridView.classList.remove('hidden');
            listView.classList.add('hidden');
            gridBtn.classList.add('bg-iri-primary', 'text-white');
            gridBtn.classList.remove('bg-gray-200', 'text-gray-700');
            listBtn.classList.add('bg-gray-200', 'text-gray-700');
            listBtn.classList.remove('bg-iri-primary', 'text-white');
        } else {
            gridView.classList.add('hidden');
            listView.classList.remove('hidden');
            listBtn.classList.add('bg-iri-primary', 'text-white');
            listBtn.classList.remove('bg-gray-200', 'text-gray-700');
            gridBtn.classList.add('bg-gray-200', 'text-gray-700');
            gridBtn.classList.remove('bg-iri-primary', 'text-white');
        }
        
        // Sauvegarder la préférence
        localStorage.setItem('publicationView', view);
    }
    
    // Restaurer la vue préférée
    document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('publicationView') || 'grid';
        toggleView(savedView);
        
        // Soumission automatique du formulaire de filtre
        const filterForm = document.querySelector('#filter-form');
        if (filterForm) {
            const filterInputs = filterForm.querySelectorAll('select, input[type="search"]');
            filterInputs.forEach(input => {
                input.addEventListener('change', function() {
                    filterForm.submit();
                });
            });
        }
    });

    // Fonctions de modération
    function publishPublication(id) {
        if (confirm('Êtes-vous sûr de vouloir publier cette publication ?')) {
            fetch(`/admin/publication/${id}/publish`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                alert('Erreur lors de la publication');
                console.error('Error:', error);
            });
        }
    }

    function unpublishPublication(id) {
        if (confirm('Êtes-vous sûr de vouloir dépublier cette publication ?')) {
            fetch(`/admin/publication/${id}/unpublish`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                alert('Erreur lors de la dépublication');
                console.error('Error:', error);
            });
        }
    }

    function exportPublications() {
        window.location.href = '/admin/publication/export';
    }
</script>
@endpush
