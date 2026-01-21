@extends('layouts.admin')

@section('title', 'Détails Catégorie')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <a href="{{ route('admin.categorie.index') }}" class="text-white/70 hover:text-white">Catégories</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">{{ $categorie->nom }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $categorie->nom }}</h1>
            <p class="text-gray-600">Détails de la catégorie</p>
        </div>
        <div class="flex items-center space-x-3">
            @can('update', $categorie)
                <a href="{{ route('admin.categorie.edit', $categorie) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
            @endcan
            
            @can('delete', $categorie)
                <button type="button" 
                        onclick="confirmDelete({{ $categorie->id }})"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>
                    Supprimer
                </button>
            @endcan

            <a href="{{ route('admin.categorie.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations principales -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informations générales</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $categorie->nom }}</p>
                    </div>

                    @if($categorie->slug)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Slug</label>
                        <p class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded">{{ $categorie->slug }}</p>
                    </div>
                    @endif

                    @if($categorie->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $categorie->description }}</p>
                    </div>
                    @endif

                    @if($categorie->couleur)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Couleur</label>
                        <div class="mt-1 flex items-center space-x-2">
                            <div class="w-6 h-6 rounded border border-gray-300" style="background-color: {{ $categorie->couleur }}"></div>
                            <span class="text-sm text-gray-900 font-mono">{{ $categorie->couleur }}</span>
                        </div>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $categorie->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-{{ $categorie->active ? 'check' : 'times' }} mr-1"></i>
                            {{ $categorie->active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actualités associées -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Actualités associées</h2>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $categorie->actualites_count ?? 0 }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    @if($categorie->actualites && $categorie->actualites->count() > 0)
                        <div class="space-y-3">
                            @foreach($categorie->actualites->take(5) as $actualite)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $actualite->titre }}</h4>
                                        <p class="text-xs text-gray-500">{{ $actualite->created_at->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $actualite->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $actualite->is_published ? 'Publié' : 'Brouillon' }}
                                        </span>
                                        @can('view', $actualite)
                                            <a href="{{ route('admin.actualite.show', $actualite) }}" 
                                               class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($categorie->actualites->count() > 5)
                                <div class="text-center pt-3">
                                    <a href="{{ route('admin.actualite.index', ['categorie' => $categorie->id]) }}" 
                                       class="text-sm text-blue-600 hover:text-blue-800">
                                        Voir toutes les actualités ({{ $categorie->actualites->count() }})
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-newspaper text-gray-400 text-3xl mb-3"></i>
                            <p class="text-gray-500">Aucune actualité associée à cette catégorie</p>
                            @can('create', \App\Models\Actualite::class)
                                <a href="{{ route('admin.actualite.create', ['categorie' => $categorie->id]) }}" 
                                   class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-600 bg-blue-100 hover:bg-blue-200 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Créer une actualité
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistiques -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-indigo-600"></i>
                        Statistiques
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 flex items-center">
                            <i class="fas fa-newspaper mr-2 text-blue-500"></i>
                            Total actualités
                        </span>
                        <span class="text-lg font-semibold text-blue-600">{{ $categorie->actualites_count ?? 0 }}</span>
                    </div>
                    
                    @if($categorie->actualites_count > 0)
                        <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 flex items-center">
                                    <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                    Publiées
                                </span>
                                <span class="font-medium text-green-600">
                                    {{ $categorie->actualites->where('is_published', true)->count() }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 flex items-center">
                                    <i class="fas fa-edit mr-2 text-yellow-500"></i>
                                    Brouillons
                                </span>
                                <span class="font-medium text-yellow-600">
                                    {{ $categorie->actualites->where('is_published', false)->count() }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="border-t border-gray-200 pt-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-calendar-plus mr-2 text-gray-500"></i>
                                Créée le
                            </span>
                            <span class="text-sm text-gray-900">{{ $categorie->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-calendar-edit mr-2 text-gray-500"></i>
                                Modifiée le
                            </span>
                            <span class="text-sm text-gray-900">{{ $categorie->updated_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center">
                                <i class="fas fa-{{ $categorie->active ? 'toggle-on text-green-500' : 'toggle-off text-red-500' }} mr-2"></i>
                                Statut
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $categorie->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $categorie->active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions de modération -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-shield-alt mr-2 text-blue-600"></i>
                        Actions de modération
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    @can('update', $categorie)
                        @if($categorie->active)
                            <form method="POST" action="{{ route('admin.categorie.update', $categorie) }}" class="w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="nom" value="{{ $categorie->nom }}">
                                <input type="hidden" name="description" value="{{ $categorie->description }}">
                                <input type="hidden" name="active" value="0">
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 transition-colors">
                                    <i class="fas fa-eye-slash mr-2"></i>
                                    Désactiver la catégorie
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.categorie.update', $categorie) }}" class="w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="nom" value="{{ $categorie->nom }}">
                                <input type="hidden" name="description" value="{{ $categorie->description }}">
                                <input type="hidden" name="active" value="1">
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors">
                                    <i class="fas fa-eye mr-2"></i>
                                    Activer la catégorie
                                </button>
                            </form>
                        @endif
                    @endcan

                    @can('delete', $categorie)
                        <button type="button" 
                                onclick="confirmDelete({{ $categorie->id }})"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer définitivement
                        </button>
                    @endcan

                    @if($categorie->actualites_count > 0)
                        @can('moderate', \App\Models\Actualite::class)
                            <div class="pt-3 border-t border-gray-200">
                                <p class="text-xs text-gray-500 mb-2">Actions sur les actualités :</p>
                                <div class="space-y-2">
                                    <a href="{{ route('admin.actualite.index', ['categorie' => $categorie->id, 'status' => 'draft']) }}" 
                                       class="w-full inline-flex items-center justify-center px-3 py-2 border border-yellow-300 rounded-md shadow-sm text-xs font-medium text-yellow-700 bg-yellow-50 hover:bg-yellow-100 transition-colors">
                                        <i class="fas fa-edit mr-2"></i>
                                        Modérer les brouillons
                                    </a>
                                    <a href="{{ route('admin.actualite.index', ['categorie' => $categorie->id, 'status' => 'published']) }}" 
                                       class="w-full inline-flex items-center justify-center px-3 py-2 border border-green-300 rounded-md shadow-sm text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Gérer les publiées
                                    </a>
                                </div>
                            </div>
                        @endcan
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt mr-2 text-purple-600"></i>
                        Actions rapides
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    @can('create', \App\Models\Actualite::class)
                        <a href="{{ route('admin.actualite.create', ['categorie' => $categorie->id]) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Nouvelle actualité
                        </a>
                    @endcan

                    @can('viewAny', \App\Models\Actualite::class)
                        <a href="{{ route('admin.actualite.index', ['categorie' => $categorie->id]) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-list mr-2"></i>
                            Voir les actualités
                        </a>
                    @endcan

                    @can('update', $categorie)
                        <a href="{{ route('admin.categorie.edit', $categorie) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Modifier la catégorie
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
@can('delete', $categorie)
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-hidden="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Confirmer la suppression
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Êtes-vous sûr de vouloir supprimer la catégorie "{{ $categorie->nom }}" ? 
                                @if($categorie->actualites_count > 0)
                                    <strong class="text-red-600">Attention : {{ $categorie->actualites_count }} actualité(s) sont associées à cette catégorie.</strong>
                                @endif
                                Cette action est irréversible.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" method="POST" action="{{ route('admin.categorie.destroy', $categorie) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Supprimer
                    </button>
                </form>
                <button type="button" 
                        onclick="closeDeleteModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>
@endcan

@push('scripts')
<script>
function confirmDelete(id) {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection
