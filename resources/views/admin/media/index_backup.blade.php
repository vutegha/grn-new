@extends('layouts.admin')

@section('title', 'Médiathèque GRN-UCBC')

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
                <span class="text-white">Médias</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-iri-primary to-iri-accent rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-photo-video text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-iri-primary to-iri-accent bg-clip-text text-transparent">
                                Médiathèque GRN-UCBC
                            </h1>
                            <p class="text-gray-600">Gérez vos images et vidéos en toute simplicité</p>
                        </div>
                    </div>
                </div>
                @can('create', \App\Models\Media::class)
                <div class="mt-6 lg:mt-0 lg:ml-4">
                    <a href="{{ route('admin.media.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-accent text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter un média
                    </a>
                </div>
                @endcan
            </div>
        </div>

        {{-- Statistics Dashboard --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-images text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">Total Médias</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-image text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">Images</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['images'] ?? 0 }}</p>
                        <p class="text-xs text-green-600">{{ $imageStats['published'] ?? 0 }} publiées</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-video text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">Vidéos</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['videos'] ?? 0 }}</p>
                        <p class="text-xs text-purple-600">{{ $videoStats['published'] ?? 0 }} publiées</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">En attente</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                        <p class="text-xs text-orange-600">Modération</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search and Filters --}}
        <div class="bg-white rounded-xl shadow-lg mb-8">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-search mr-2 text-iri-primary"></i>
                    Recherche et Filtres
                </h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('admin.media.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Titre, description..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                            <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                                <option value="">Tous les types</option>
                                <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Images</option>
                                <option value="video" {{ request('type') === 'video' ? 'selected' : '' }}>Vidéos</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                                <option value="">Tous les statuts</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approuvé</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeté</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-accent text-white font-semibold rounded-lg hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-search mr-2"></i>
                                Rechercher
                            </button>
                        </div>
                    </div>
                    
                    @if(request()->hasAny(['search', 'type', 'status']))
                    <div class="flex justify-end">
                        <a href="{{ route('admin.media.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Réinitialiser
                        </a>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Media Grid --}}
        <div class="bg-white rounded-xl shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-images mr-2 text-iri-primary"></i>
                        Médias ({{ $medias->total() }})
                    </h3>
                    
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Vue:</span>
                        <button type="button" id="grid-view" class="p-2 rounded-lg bg-iri-primary text-white">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" id="list-view" class="p-2 rounded-lg text-gray-400 hover:bg-gray-100">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                @if($medias->count() > 0)
                    <div id="media-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($medias as $media)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-200 overflow-hidden">
                                {{-- Media Preview --}}
                                <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                                    @if($media->type === 'image' && $media->medias)
                                        <img src="{{ asset('storage/' . $media->medias) }}" 
                                             alt="{{ $media->titre }}"
                                             class="w-full h-48 object-cover">
                                    @elseif($media->type === 'video' && $media->medias)
                                        <div class="w-full h-48 bg-gray-900 flex items-center justify-center relative">
                                            <i class="fas fa-play-circle text-white text-4xl"></i>
                                            <video class="absolute inset-0 w-full h-full object-cover opacity-50">
                                                <source src="{{ asset('storage/' . $media->medias) }}" type="video/mp4">
                                            </video>
                                        </div>
                                    @else
                                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                            <i class="fas fa-file text-gray-400 text-3xl"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Media Info --}}
                                <div class="p-4">
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="font-semibold text-gray-900 truncate">
                                            {{ $media->titre ?: 'Sans titre' }}
                                        </h4>
                                        <div class="flex items-center space-x-1 ml-2">
                                            @if($media->type === 'image')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-image mr-1"></i>
                                                    Image
                                                </span>
                                            @elseif($media->type === 'video')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <i class="fas fa-video mr-1"></i>
                                                    Vidéo
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($media->description)
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                            {{ Str::limit($media->description, 80) }}
                                        </p>
                                    @endif
                                    
                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                        <span>{{ $media->created_at->format('d/m/Y') }}</span>
                                        @if($media->projet)
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                {{ $media->projet->nom }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    {{-- Status Badge --}}
                                    <div class="flex items-center justify-between mb-4">
                                        @switch($media->status)
                                            @case('approved')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Approuvé
                                                </span>
                                                @break
                                            @case('pending')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    En attente
                                                </span>
                                                @break
                                            @case('rejected')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    Rejeté
                                                </span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Non défini
                                                </span>
                                        @endswitch
                                        
                                        @if($media->is_public)
                                            <i class="fas fa-eye text-green-500" title="Public"></i>
                                        @else
                                            <i class="fas fa-eye-slash text-gray-400" title="Privé"></i>
                                        @endif
                                    </div>
                                    
                                    {{-- Actions --}}
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            @can('view', $media)
                                                <a href="{{ route('admin.media.show', $media) }}" 
                                                   class="text-blue-600 hover:text-blue-800 transition-colors duration-200"
                                                   title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            
                                            @can('update', $media)
                                                <a href="{{ route('admin.media.edit', $media) }}" 
                                                   class="text-green-600 hover:text-green-800 transition-colors duration-200"
                                                   title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            
                                            @can('delete', $media)
                                                <form method="POST" action="{{ route('admin.media.destroy', $media) }}" 
                                                      class="inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce média ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                        
                                        @if($media->medias)
                                            <a href="{{ asset('storage/' . $media->medias) }}" 
                                               target="_blank"
                                               class="text-gray-600 hover:text-gray-800 transition-colors duration-200"
                                               title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-images text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun média trouvé</h3>
                        <p class="text-gray-500 mb-6">
                            @if(request()->hasAny(['search', 'type', 'status']))
                                Aucun média ne correspond à vos critères de recherche.
                            @else
                                Commencez par ajouter votre premier média.
                            @endif
                        </p>
                        @can('create', \App\Models\Media::class)
                            <a href="{{ route('admin.media.create') }}"
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-accent text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Ajouter un média
                            </a>
                        @endcan
                    </div>
                @endif
            </div>
            
            {{-- Pagination --}}
            @if($medias->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                    {{ $medias->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const mediaGrid = document.getElementById('media-grid');
    
    if (gridView && listView && mediaGrid) {
        gridView.addEventListener('click', function() {
            mediaGrid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6';
            gridView.className = 'p-2 rounded-lg bg-iri-primary text-white';
            listView.className = 'p-2 rounded-lg text-gray-400 hover:bg-gray-100';
        });
        
        listView.addEventListener('click', function() {
            mediaGrid.className = 'grid grid-cols-1 gap-4';
            listView.className = 'p-2 rounded-lg bg-iri-primary text-white';
            gridView.className = 'p-2 rounded-lg text-gray-400 hover:bg-gray-100';
        });
    }
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">En attente</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                                <p class="text-xs text-gray-500">Modération requise</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Barre de recherche et filtres modernes --}}
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 mb-8">
            <form method="GET" action="{{ route('admin.media.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Recherche --}}
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search mr-1"></i>Recherche
                        </label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Rechercher par titre ou description..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                    </div>

                    {{-- Filtre par type --}}
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-filter mr-1"></i>Type
                        </label>
                        <select id="type" 
                                name="type" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                            <option value="">Tous les types</option>
                            <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>
                                <i class="fas fa-image mr-2"></i>Images
                            </option>
                            <option value="video" {{ request('type') === 'video' ? 'selected' : '' }}>
                                <i class="fas fa-video mr-2"></i>Vidéos
                            </option>
                        </select>
                    </div>

                    {{-- Filtre par statut --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-flag mr-1"></i>Statut
                        </label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approuvé</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publié</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeté</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-4 border-t">
                    <div class="flex space-x-3">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-primary text-white font-medium rounded-lg transition-all duration-200">
                            <i class="fas fa-search mr-2"></i>
                            Rechercher
                        </button>
                        <a href="{{ route('admin.media.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Réinitialiser
                        </a>
                    </div>
                    
                    <div class="mt-3 sm:mt-0 text-sm text-gray-500">
                        {{ $medias->total() }} média(s) trouvé(s)
                    </div>
                </div>
            </form>
        </div>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un média..." 
                               class="w-full pl-10 pr-4 py-3 bg-white/50 border border-iri-gray/20 rounded-xl focus:ring-2 focus:ring-iri-accent focus:border-transparent transition-all duration-300">
                    </div>
                </div>
                
                {{-- Filtres --}}
                <div class="flex gap-3">
                    <form method="GET" class="flex gap-3">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="type" onchange="this.form.submit()" 
                                class="px-4 py-3 bg-white/50 border border-iri-gray/20 rounded-xl focus:ring-2 focus:ring-iri-accent focus:border-transparent transition-all duration-300">
                            <option value="">Tous les types</option>
                            <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                            <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Vidéos</option>
                        </select>
                        
                        <select name="status" onchange="this.form.submit()" 
                                class="px-4 py-3 bg-white/50 border border-iri-gray/20 rounded-xl focus:ring-2 focus:ring-iri-accent focus:border-transparent transition-all duration-300">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publié</option>
                        </select>
                    </form>
                    
                    {{-- Boutons de vue --}}
                    <div class="flex bg-white/50 rounded-xl p-1 border border-iri-gray/20">
                        <button class="p-2 rounded-lg bg-iri-primary text-white transition-all duration-300" title="Vue grille">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z"/>
                            </svg>
                        </button>
                        <button class="p-2 rounded-lg text-iri-gray hover:text-iri-primary transition-all duration-300" title="Vue liste">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    {{-- Grille de médias --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($medias as $item)
            <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-white/20 hover:border-iri-accent/30">
                {{-- Image/Vidéo --}}
                <div class="relative h-48 overflow-hidden bg-gradient-to-br from-iri-light to-white">
                    @if ($item->type === 'image')
                        <img src="{{ asset('storage/' . $item->medias) }}" 
                             alt="{{ $item->titre }}" 
                             class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300">
                    @elseif ($item->type === 'video')
                        <video class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" muted>
                            <source src="{{ asset('storage/' . $item->medias) }}" type="video/mp4">
                        </video>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-iri-primary/80 rounded-full p-3">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Overlay avec actions --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-3 left-3 right-3">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-iri-accent text-white">
                                    @if($item->type === 'image')
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
                                        </svg>
                                    @endif
                                    {{ ucfirst($item->type) }}
                                </span>
                                <div class="flex gap-1">
                                    <a href="{{ route('admin.media.show', $item) }}" 
                                       class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200"
                                       title="Voir les détails">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <button onclick="copyToClipboard('{{ asset('storage/' . $item->medias) }}')" 
                                            class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200"
                                            title="Copier le lien">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Informations --}}
                <div class="p-4">
                    <h3 class="font-semibold text-iri-dark text-sm mb-2 line-clamp-2">{{ $item->titre }}</h3>
                    <div class="flex items-center justify-between text-xs text-iri-gray mb-2">
                        <span class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $item->created_at->format('d/m/Y') }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                            @if($item->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($item->status === 'approved') bg-green-100 text-green-800
                            @elseif($item->status === 'rejected') bg-red-100 text-red-800
                            @elseif($item->status === 'published') bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst($item->status ?? 'pending') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        @if($item->creator)
                            <span class="text-xs text-iri-gray">
                                Par {{ $item->creator->name }}
                            </span>
                        @endif
                        <div class="flex gap-1">
                            @can('update', $item)
                            <button onclick="editMedia('{{ route('admin.media.edit', $item) }}')" 
                                    class="text-iri-accent hover:text-iri-gold transition-colors p-1 rounded">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                </svg>
                            </button>
                            @endcan
                            @can('delete', $item)
                            <form method="POST" action="{{ route('admin.media.destroy', $item) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Supprimer ce média ?')"
                                        class="text-red-500 hover:text-red-700 transition-colors p-1 rounded">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                    </svg>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Modal moderne --}}
    <div id="mediaModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 overflow-hidden border border-iri-accent/20">
            {{-- En-tête du modal --}}
            <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 id="modalTitle" class="text-xl font-semibold text-white"></h3>
                    <button onclick="closeModal()" 
                            class="text-white/70 hover:text-white hover:bg-white/10 rounded-full p-2 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Contenu du modal --}}
            <div class="p-6">
                <div id="modalContent" class="mb-6 rounded-xl overflow-hidden"></div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-iri-gray/20">
                    <div class="flex gap-3">
                        <a id="modalEditBtn" href="#" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-accent to-iri-gold hover:from-iri-gold hover:to-iri-accent text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Modifier
                        </a>
                        <form id="modalDeleteForm" method="POST" action="" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Supprimer ce média ?')"
                                    class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                    <a id="modalDownload" href="#" download
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-primary text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Télécharger
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const mediaGrid = document.getElementById('media-grid');
    
    if (gridView && listView && mediaGrid) {
        gridView.addEventListener('click', function() {
            mediaGrid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6';
            gridView.className = 'p-2 rounded-lg bg-iri-primary text-white';
            listView.className = 'p-2 rounded-lg text-gray-400 hover:bg-gray-100';
        });
        
        listView.addEventListener('click', function() {
            mediaGrid.className = 'grid grid-cols-1 gap-4';
            listView.className = 'p-2 rounded-lg bg-iri-primary text-white';
            gridView.className = 'p-2 rounded-lg text-gray-400 hover:bg-gray-100';
        });
    }
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
<script>
    function openModal(fileUrl, title, editUrl, deleteUrl) {
        document.getElementById('mediaModal').classList.remove('hidden');
        document.getElementById('modalTitle').textContent = title;

        const ext = fileUrl.split('.').pop().toLowerCase();
        let content = '';

        if (['jpg', 'jpeg', 'png', 'gif', 'svg'].includes(ext)) {
            content = `<img src="${fileUrl}" class="w-full rounded">`;
        } else if (['mp4', 'webm', 'mov'].includes(ext)) {
            content = `<video controls class="w-full rounded"><source src="${fileUrl}" type="video/mp4">Votre navigateur ne prend pas en charge la vidéo.</video>`;
        } else {
            content = `<p>Fichier non supporté.</p>`;
        }

        document.getElementById('modalContent').innerHTML = content;
        document.getElementById('modalEditBtn').href = editUrl;
        document.getElementById('modalDeleteForm').action = deleteUrl;
        document.getElementById('modalDownload').href = fileUrl;
    }

    function closeModal() {
        document.getElementById('mediaModal').classList.add('hidden');
    }

    function editMedia(editUrl) {
        window.location.href = editUrl;
    }
    
    function copyToClipboard(url) {
        navigator.clipboard.writeText(url).then(function() {
            showNotification('Lien copié dans le presse-papiers !', 'success');
        }).catch(function(err) {
            console.error('Erreur lors de la copie:', err);
            
            // Fallback pour les navigateurs plus anciens
            const textArea = document.createElement('textarea');
            textArea.value = url;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showNotification('Lien copié dans le presse-papiers !', 'success');
        });
    }
    
    function showNotification(message, type = 'info') {
        // Créer la notification
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-xl shadow-lg transform translate-x-full transition-transform duration-300 z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-iri-primary text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animer l'entrée
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Animer la sortie
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection