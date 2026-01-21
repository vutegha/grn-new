@extends('layouts.admin')

@section('title', 'Gestion des Médias - IRI UCBC')

@section('styles')
<style>
    :root {
        --iri-primary: #1e472f;
        --iri-secondary: #2d5a3d;
        --iri-accent: #d2691e;
        --iri-gold: #b8860b;
        --iri-light: #f0f8f0;
        --iri-gray: #64748b;
        --iri-dark: #1a1a1a;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-iri-light via-white to-blue-50">
    <div class="container mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-3xl font-bold text-iri-dark mb-2">
                        <i class="fas fa-images mr-3 text-iri-primary"></i>
                        Gestion des Médias
                    </h1>
                    <p class="text-iri-gray">Gérez votre bibliothèque multimédia avec style</p>
                </div>
                @can('create', \App\Models\Media::class)
                <a href="{{ route('admin.media.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-primary text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter un média
                </a>
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
@endsection
