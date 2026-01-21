@extends('layouts.admin')

@section('title', 'Publications')
@section('subtitle', 'Gérez vos articles et publications')

@section('content')

<!-- Header avec bouton d'ajout -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Publications</h2>
        <p class="text-gray-600">Gérez et organisez vos publications</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('admin.publication.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-coral text-white text-sm font-medium rounded-md hover:bg-coral-dark transition-colors duration-200">
            <i class="bi bi-plus-circle mr-2"></i>
            Nouvelle publication
        </a>
    </div>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Publications -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-coral bg-opacity-10 rounded-lg flex items-center justify-center">
                    <i class="bi bi-journal-text text-coral text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Publications</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $publications->total() }}</p>
            </div>
        </div>
    </div>

    <!-- À la Une -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-star text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">À la Une</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $publications->where('a_la_une', true)->count() }}</p>
            </div>
        </div>
    </div>

    <!-- En Vedette -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-olive bg-opacity-10 rounded-lg flex items-center justify-center">
                    <i class="bi bi-gem text-olive text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">En Vedette</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $publications->where('en_vedette', true)->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Avec Thumbnail -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-image text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Avec Image</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $publications->whereNotNull('thundernail')->count() }}</p>
            </div>
        </div>
    </div>

</div>

<!-- Filtres -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="bi bi-funnel mr-2"></i>
            Filtres
        </h3>
    </div>
    <div class="p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Filtre Auteur -->
            <div>
                <label for="auteur" class="block text-sm font-medium text-gray-700 mb-2">Auteur</label>
                <select name="auteur" id="auteur" class="w-full border-gray-300 rounded-md shadow-sm focus:border-coral focus:ring-coral">
                    <option value="">-- Tous --</option>
                    @foreach ($auteurs as $auteur)
                        <option value="{{ $auteur->id }}" {{ request('auteur') == $auteur->id ? 'selected' : '' }}>
                            {{ $auteur->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtre Catégorie -->
            <div>
                <label for="categorie" class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                <select name="categorie" id="categorie" class="w-full border-gray-300 rounded-md shadow-sm focus:border-coral focus:ring-coral">
                    <option value="">-- Toutes --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Bouton de filtre -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-coral text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-coral-dark transition-colors duration-200">
                    <i class="bi bi-search mr-2"></i>
                    Filtrer
                </button>
            </div>

        </form>
    </div>
</div>

<!-- Table des publications -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="bi bi-table mr-2"></i>
            Liste des Publications
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Image
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Publication
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Auteur(s)
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Catégorie
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($publications as $pub)
                <tr class="hover:bg-gray-50">
                    <!-- Image -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($pub->thundernail)
                            <img src="{{ asset('storage/' . $pub->thundernail) }}" 
                                 alt="Thumbnail" 
                                 class="w-16 h-16 object-cover rounded-lg shadow-sm">
                        @else
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="bi bi-image text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                    </td>

                    <!-- Publication Info -->
                    <td class="px-6 py-4">
                        <div class="max-w-xs">
                            <div class="text-sm font-medium text-gray-900 line-clamp-2">
                                {{ $pub->title }}
                            </div>
                            @if($pub->sub_title)
                                <div class="text-xs text-gray-500 mt-1 line-clamp-1">
                                    {{ $pub->sub_title }}
                                </div>
                            @endif
                            <div class="text-xs text-gray-400 mt-1">
                                {{ $pub->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </td>

                    <!-- Auteurs -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            @if($pub->auteurs->count() > 0)
                                @foreach($pub->auteurs->take(2) as $auteur)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-olive bg-opacity-10 text-olive mr-1 mb-1">
                                        {{ $auteur->nom }}
                                    </span>
                                @endforeach
                                @if($pub->auteurs->count() > 2)
                                    <span class="text-xs text-gray-500">+{{ $pub->auteurs->count() - 2 }}</span>
                                @endif
                            @else
                                <span class="text-xs text-gray-400">Aucun auteur</span>
                            @endif
                        </div>
                    </td>

                    <!-- Catégorie -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($pub->categorie)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-coral bg-opacity-10 text-coral">
                                {{ $pub->categorie->nom }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400">Non catégorisée</span>
                        @endif
                    </td>

                    <!-- Statut -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col space-y-1">
                            @if($pub->a_la_une)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="bi bi-star mr-1"></i>À la Une
                                </span>
                            @endif
                            @if($pub->en_vedette)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="bi bi-gem mr-1"></i>En Vedette
                                </span>
                            @endif
                            @if(!$pub->a_la_une && !$pub->en_vedette)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Standard
                                </span>
                            @endif
                        </div>
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('admin.publication.show', $pub) }}" 
                               class="text-coral hover:text-coral-dark" 
                               title="Voir">
                                <i class="bi bi-eye text-lg"></i>
                            </a>
                            <a href="{{ route('admin.publication.edit', $pub) }}" 
                               class="text-olive hover:text-olive-dark" 
                               title="Modifier">
                                <i class="bi bi-pencil text-lg"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.publication.destroy', $pub) }}" 
                                  class="inline" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800" 
                                        title="Supprimer">
                                    <i class="bi bi-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="bi bi-journal-text text-gray-300 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune publication trouvée</h3>
                            <p class="text-gray-500 mb-4">Commencez par créer votre première publication.</p>
                            <a href="{{ route('admin.publication.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-coral text-white text-sm font-medium rounded-md hover:bg-coral-dark transition-colors duration-200">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Nouvelle publication
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($publications->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Affichage de {{ $publications->firstItem() }} à {{ $publications->lastItem() }} 
                sur {{ $publications->total() }} résultats
            </div>
            <div>
                {{ $publications->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@section('scripts')
<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
