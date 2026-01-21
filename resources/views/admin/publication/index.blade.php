@extends('layouts.admin')

@section('title', 'Gestion des Publications')

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
    
    <!-- En-tête avec actions -->
    <div class="bg-gradient-to-r from-iri-primary to-iri-secondary rounded-xl shadow-sm p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">
                    <i class="fas fa-newspaper mr-3"></i>Gestion des Publications
                </h1>
                <p class="text-green-100">Gérez et modérez les publications de votre plateforme</p>
            </div>
            <div class="mt-4 sm:mt-0">
                @can('create', \App\Models\Publication::class)
                <a href="{{ route('admin.publication.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white text-iri-primary font-semibold rounded-lg shadow-md hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Nouvelle Publication
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-newspaper text-blue-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Publiées</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['published'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <i class="fas fa-clock text-orange-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">En attente</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-gray-100 rounded-lg">
                    <i class="fas fa-edit text-gray-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Brouillons</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['draft'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-star text-yellow-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">En vedette</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['featured'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-calendar text-purple-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">Ce mois</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['this_month'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('admin.publication.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" 
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Rechercher une publication..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary">
            </div>
            <div class="flex gap-2">
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary">
                    <option value="">Tous les statuts</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publiées</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Brouillons</option>
                </select>
                
                <button type="submit" class="px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors duration-200">
                    <i class="fas fa-search mr-1"></i>Rechercher
                </button>
                
                @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.publication.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-times mr-1"></i>Reset
                </a>
                @endif
                
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button type="button" id="grid-view" class="px-3 py-1 rounded-md bg-white text-iri-primary font-medium shadow-sm">
                        <i class="fas fa-th"></i>
                    </button>
                    <button type="button" id="list-view" class="px-3 py-1 rounded-md text-gray-600 hover:text-iri-primary">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </form>
        
        @if(request()->hasAny(['search', 'status']))
        <div class="mt-3 text-sm text-gray-600">
            <i class="fas fa-info-circle mr-1"></i>
            {{ $publications->total() }} résultat(s) trouvé(s)
            @if(request('search'))
            pour "<strong>{{ request('search') }}</strong>"
            @endif
            @if(request('status'))
            avec le statut "<strong>{{ ucfirst(request('status')) }}</strong>"
            @endif
        </div>
        @endif
    </div>

    <!-- Vue en grille (par défaut) -->
    <div id="grid-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($publications as $publication)
        <div class="publication-card bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
            <!-- Image -->
            <div class="relative h-48 bg-gray-100 rounded-t-xl overflow-hidden">
                @if($publication->fichier_pdf && Storage::disk('public')->exists($publication->fichier_pdf))
                    <!-- Miniature PDF haute résolution générée avec PDF.js -->
                    <div data-pdf-thumbnail="{{ Storage::url($publication->fichier_pdf) }}" 
                         data-width="300" 
                         data-height="192"
                         class="w-full h-full bg-gray-50 rounded-t-xl overflow-hidden pdf-thumbnail-container">
                        <!-- Placeholder amélioré pendant le chargement -->
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gradient-to-br from-blue-50 to-blue-100">
                            <i class="fas fa-file-pdf text-4xl mb-2 text-blue-500 animate-pulse"></i>
                            <span class="text-sm font-medium text-blue-600">Génération HD...</span>
                            <span class="text-xs text-blue-500 mt-1">Résolution optimisée</span>
                        </div>
                    </div>
                    <!-- Badge PDF amélioré -->
                    <div class="absolute bottom-2 left-2 z-10">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-red-600 text-white text-xs font-medium shadow-lg">
                            <i class="fas fa-file-pdf mr-1"></i>PDF HD
                        </span>
                    </div>
                @elseif($publication->fichier_pdf && !Storage::disk('public')->exists($publication->fichier_pdf))
                    <!-- PDF manquant -->
                    <div class="w-full h-full flex flex-col items-center justify-center text-orange-500 bg-gradient-to-br from-orange-50 to-orange-100">
                        <i class="fas fa-exclamation-triangle text-4xl mb-2"></i>
                        <span class="text-sm font-medium">PDF manquant</span>
                        <span class="text-xs text-orange-600 mt-1">{{ basename($publication->fichier_pdf) }}</span>
                    </div>
                @elseif($publication->image)
                    <img src="{{ Storage::url($publication->image) }}" 
                         alt="{{ $publication->titre }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gradient-to-br from-gray-50 to-gray-100">
                        <i class="fas fa-image text-4xl mb-2"></i>
                        <span class="text-sm text-gray-500">Aucune image</span>
                    </div>
                @endif
                
                <!-- Badges -->
                <div class="absolute top-3 left-3 flex flex-col gap-1">
                    @if($publication->is_published)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Publiée
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            <i class="fas fa-clock mr-1"></i>En attente
                        </span>
                    @endif
                    
                    @if($publication->en_vedette)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-star mr-1"></i>Vedette
                        </span>
                    @endif
                </div>
                
                <!-- Menu actions -->
                <div class="absolute top-3 right-3">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="p-2 bg-white rounded-full shadow-sm hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-ellipsis-v text-gray-600"></i>
                        </button>
                        
                        <div x-show="open" @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                             style="z-index: 9999 !important;"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95">
                            @can('view_publications')
                            <a href="{{ route('admin.publication.show', $publication) }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-eye mr-2"></i>Voir
                            </a>
                            @endcan
                            @can('update_publications')
                            <a href="{{ route('admin.publication.edit', $publication) }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-edit mr-2"></i>Modifier
                            </a>
                            @endcan
                            @can('delete_publications')
                            <hr class="my-1">
                            <button onclick="deletePublication({{ $publication->id }})"
                                    class="w-full flex items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                <i class="fas fa-trash mr-2"></i>Supprimer
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contenu -->
            <div class="p-4">
                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $publication->titre }}</h3>
                
                @if($publication->resume)
                    <p class="text-gray-600 text-sm mb-3 line-clamp-3">{{ $publication->resume }}</p>
                @endif
                
                <!-- Métadonnées -->
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <div class="flex items-center">
                        <i class="fas fa-user mr-1"></i>
                        @if($publication->auteurs->count() > 0)
                            {{ $publication->auteurs->pluck('nom')->join(', ') }}
                        @else
                            Anonyme
                        @endif
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ $publication->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Vue en liste (masquée par défaut) -->
    <div id="list-container" class="hidden">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <i class="fas fa-newspaper mr-2"></i>Publication
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Auteur
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>Statut
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <i class="fas fa-calendar mr-2"></i>Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <i class="fas fa-eye mr-2"></i>Vues
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($publications as $publication)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-16 w-16">
                                    @if($publication->fichier_pdf && Storage::disk('public')->exists($publication->fichier_pdf))
                                        <!-- Miniature PDF haute résolution générée avec PDF.js -->
                                        <div data-pdf-thumbnail="{{ Storage::url($publication->fichier_pdf) }}" 
                                             data-width="64" 
                                             data-height="64"
                                             class="h-16 w-16 bg-gray-50 rounded-xl overflow-hidden pdf-thumbnail-container relative">
                                            <!-- Placeholder optimisé pendant le chargement -->
                                            <div class="h-16 w-16 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 flex flex-col items-center justify-center border border-blue-200">
                                                <i class="fas fa-file-pdf text-blue-500 text-lg animate-pulse"></i>
                                                <span class="text-xs text-blue-600 mt-0.5 font-medium">HD</span>
                                            </div>
                                            <!-- Badge PDF miniature amélioré -->
                                            <div class="absolute -bottom-1 -right-1">
                                                <span class="inline-flex items-center px-1 py-0.5 rounded text-xs bg-red-600 text-white shadow-sm">
                                                    <i class="fas fa-file-pdf"></i>
                                                </span>
                                            </div>
                                        </div>
                                    @elseif($publication->fichier_pdf && !Storage::disk('public')->exists($publication->fichier_pdf))
                                        <!-- PDF manquant -->
                                        <div class="h-16 w-16 rounded-xl bg-gradient-to-br from-orange-50 to-orange-100 flex items-center justify-center border border-orange-200"
                                             title="PDF manquant: {{ basename($publication->fichier_pdf) }}">
                                            <i class="fas fa-exclamation-triangle text-orange-500 text-lg"></i>
                                        </div>
                                    @elseif($publication->image)
                                        <img class="h-16 w-16 rounded-xl object-cover shadow-sm border border-gray-200" 
                                             src="{{ Storage::url($publication->image) }}" 
                                             alt="{{ $publication->titre }}">
                                    @else
                                        <div class="h-16 w-16 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex flex-col items-center justify-center border border-gray-200">
                                            <i class="fas fa-image text-gray-400 text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="text-sm font-semibold text-gray-900 mb-1 line-clamp-2">
                                        {{ $publication->titre }}
                                    </div>
                                    @if($publication->resume)
                                        <div class="text-sm text-gray-600 line-clamp-2 mb-2">
                                            {{ Str::limit($publication->resume, 80) }}
                                        </div>
                                    @endif
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-tag mr-1"></i>
                                        <span class="mr-3">{{ $publication->categorie->nom ?? 'Non catégorisé' }}</span>
                                        @if($publication->mots_cles)
                                            <i class="fas fa-tags mr-1"></i>
                                            <span>{{ Str::limit($publication->mots_cles, 30) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @php
                                        $firstAuteur = $publication->auteurs->first();
                                    @endphp
                                    @if($firstAuteur && $firstAuteur->photo)
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                             src="{{ Storage::url($firstAuteur->photo) }}" 
                                             alt="{{ $firstAuteur->nom }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-iri-primary flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">
                                                {{ substr($firstAuteur->nom ?? 'A', 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        @if($publication->auteurs->count() > 0)
                                            {{ $publication->auteurs->pluck('nom')->join(', ') }}
                                            @if($publication->auteurs->count() > 1)
                                                <span class="text-xs text-gray-500">(+{{ $publication->auteurs->count() - 1 }} autres)</span>
                                            @endif
                                        @else
                                            Anonyme
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $firstAuteur->email ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-2">
                                @if($publication->is_published)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        <i class="fas fa-check-circle mr-1"></i>Publiée
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">
                                        <i class="fas fa-clock mr-1"></i>En attente
                                    </span>
                                @endif
                                
                                @if($publication->en_vedette)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <i class="fas fa-star mr-1"></i>Vedette
                                    </span>
                                @endif
                                
                                @if($publication->isDraft())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        <i class="fas fa-edit mr-1"></i>Brouillon
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <div class="font-medium">{{ $publication->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $publication->created_at->format('H:i') }}</div>
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $publication->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $publication->vues ?? 0 }}
                            </div>
                            <div class="text-xs text-gray-500">vues</div>
                        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right">
            <div class="flex items-center justify-end space-x-2">
                @can('view_publications')
                <a href="{{ route('admin.publication.show', $publication) }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-iri-primary text-white text-xs font-medium rounded-lg hover:bg-iri-secondary transition-colors duration-200"
                   title="Voir les détails">
                    <i class="fas fa-eye mr-1"></i>Voir
                </a>
                @endcan
                @can('update_publications')
                <a href="{{ route('admin.publication.edit', $publication) }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200"
                   title="Modifier">
                    <i class="fas fa-edit mr-1"></i>Modifier
                </a>
                @endcan
                                
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="inline-flex items-center px-2 py-1.5 bg-gray-600 text-white text-xs font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200"
                                            title="Plus d'actions">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    
                                    <div x-show="open" @click.away="open = false"
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95">
                                        @can('update_publications')
                                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-copy mr-2"></i>Dupliquer
                                        </a>
                                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-download mr-2"></i>Exporter
                                        </a>
                                        @endcan
                                        @can('delete_publications')
                                        <hr class="my-1">
                                        <form method="POST" action="{{ route('admin.publication.destroy', $publication) }}" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ? Cette action est irréversible.')"
                                              class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                <i class="fas fa-trash mr-2"></i>Supprimer
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $publications->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle entre les vues
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const gridContainer = document.getElementById('grid-container');
    const listContainer = document.getElementById('list-container');
    
    gridView.addEventListener('click', function() {
        gridContainer.classList.remove('hidden');
        listContainer.classList.add('hidden');
        gridView.classList.add('bg-white', 'text-iri-primary', 'shadow-sm');
        gridView.classList.remove('text-gray-600');
        listView.classList.remove('bg-white', 'text-iri-primary', 'shadow-sm');
        listView.classList.add('text-gray-600');
        
        // Régénérer les miniatures PDF pour la vue grille
        if (window.pdfThumbnailGenerator) {
            setTimeout(() => {
                window.pdfThumbnailGenerator.generateAllThumbnails();
            }, 100);
        }
    });
    
    listView.addEventListener('click', function() {
        listContainer.classList.remove('hidden');
        gridContainer.classList.add('hidden');
        listView.classList.add('bg-white', 'text-iri-primary', 'shadow-sm');
        listView.classList.remove('text-gray-600');
        gridView.classList.remove('bg-white', 'text-iri-primary', 'shadow-sm');
        gridView.classList.add('text-gray-600');
        
        // Régénérer les miniatures PDF pour la vue liste
        if (window.pdfThumbnailGenerator) {
            setTimeout(() => {
                window.pdfThumbnailGenerator.generateAllThumbnails();
            }, 100);
        }
    });
});
</script>
@endsection