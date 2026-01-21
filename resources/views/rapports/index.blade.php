@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- En-tête de la page -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Rapports</h1>
                <p class="text-gray-600">Consultez et téléchargez nos rapports officiels</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-2">
                    <span class="text-blue-800 font-medium">{{ $rapports->total() }}</span>
                    <span class="text-blue-600 text-sm">rapport(s) disponible(s)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form method="GET" action="{{ route('rapports.index') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                <!-- Recherche -->
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Titre, description..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Catégorie -->
                <div class="flex-1">
                    <label for="categorie_id" class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                    <select id="categorie_id" 
                            name="categorie_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ request('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Année -->
                <div class="flex-1">
                    <label for="annee" class="block text-sm font-medium text-gray-700 mb-2">Année</label>
                    <select id="annee" 
                            name="annee" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Toutes les années</option>
                        @for($year = date('Y'); $year >= 2020; $year--)
                            <option value="{{ $year }}" {{ request('annee') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Boutons -->
                <div class="flex space-x-2">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 flex items-center">
                        <i class="fas fa-search mr-2"></i>
                        Rechercher
                    </button>
                    @if(request()->hasAny(['search', 'categorie_id', 'annee']))
                        <a href="{{ route('rapports.index') }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200 flex items-center">
                            <i class="fas fa-times mr-2"></i>
                            Effacer
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des rapports -->
    @if($rapports->count() > 0)
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($rapports as $rapport)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
                    <!-- Zone de miniature ou icône -->
                    <div class="relative h-48 {{ $rapport->getFileColor() }} border-b">
                        @if($rapport->canHaveThumbnail())
                            <!-- Miniature PDF avec PDF.js -->
                            <div class="w-full h-full flex items-center justify-center">
                                <div id="thumbnail-{{ $rapport->id }}" 
                                     class="pdf-thumbnail-container w-full h-full flex items-center justify-center"
                                     data-pdf-url="{{ $rapport->getDownloadUrl() }}">
                                    <!-- Chargement -->
                                    <div class="loading-spinner flex flex-col items-center">
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600 mb-2"></div>
                                        <span class="text-sm text-gray-600">Génération de la miniature...</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Icône pour les autres types de fichiers -->
                            <div class="w-full h-full flex flex-col items-center justify-center">
                                <i class="{{ $rapport->getFileIcon() }} text-6xl mb-3"></i>
                                <div class="text-center">
                                    <span class="block text-lg font-medium text-gray-700">{{ $rapport->getFileType() }}</span>
                                    @if($rapport->getFileSize())
                                        <span class="block text-sm text-gray-500">{{ $rapport->getFileSize() }}</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Contenu de la carte -->
                    <div class="p-6">
                        <!-- En-tête -->
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $rapport->titre }}
                            </h3>
                            @if($rapport->description)
                                <p class="text-gray-600 text-sm line-clamp-3">
                                    {{ $rapport->description }}
                                </p>
                            @endif
                        </div>

                        <!-- Métadonnées -->
                        <div class="space-y-2 mb-4">
                            @if($rapport->categorie)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-folder-open mr-2 text-blue-500"></i>
                                    <span>{{ $rapport->categorie->nom }}</span>
                                </div>
                            @endif
                            
                            @if($rapport->date_publication)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar mr-2 text-green-500"></i>
                                    <span>{{ $rapport->date_publication->format('d/m/Y') }}</span>
                                </div>
                            @endif

                            @if($rapport->getFileSize())
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-file-download mr-2 text-purple-500"></i>
                                    <span>{{ $rapport->getFileSize() }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-2">
                            @if($rapport->fichier)
                                <a href="{{ $rapport->getDownloadUrl() }}" 
                                   target="_blank"
                                   class="flex-1 px-4 py-2 bg-blue-600 text-white text-center rounded-md hover:bg-blue-700 transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-download mr-2"></i>
                                    Télécharger
                                </a>

                                @if($rapport->isPdf())
                                    <a href="{{ $rapport->getDownloadUrl() }}" 
                                       target="_blank"
                                       class="flex-1 px-4 py-2 bg-green-600 text-white text-center rounded-md hover:bg-green-700 transition duration-200 flex items-center justify-center">
                                        <i class="fas fa-eye mr-2"></i>
                                        Visualiser
                                    </a>
                                @endif
                            @else
                                <div class="flex-1 px-4 py-2 bg-gray-300 text-gray-500 text-center rounded-md cursor-not-allowed">
                                    <i class="fas fa-ban mr-2"></i>
                                    Fichier indisponible
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($rapports->hasPages())
            <div class="mt-8">
                {{ $rapports->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <!-- Message vide -->
        <div class="text-center py-12">
            <div class="bg-gray-50 rounded-lg p-8">
                <i class="fas fa-file-alt text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun rapport trouvé</h3>
                <p class="text-gray-600 mb-4">
                    @if(request()->hasAny(['search', 'categorie_id', 'annee']))
                        Aucun rapport ne correspond à vos critères de recherche.
                    @else
                        Il n'y a actuellement aucun rapport disponible.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'categorie_id', 'annee']))
                    <a href="{{ route('rapports.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-refresh mr-2"></i>
                        Voir tous les rapports
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Styles CSS pour les miniatures -->
<style>
.pdf-thumbnail-container canvas {
    max-width: 100%;
    max-height: 100%;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    image-rendering: crisp-edges;
}

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

.loading-spinner {
    min-height: 120px;
}

/* Animation pour les cartes */
.bg-white {
    transition: all 0.3s ease;
}

.bg-white:hover {
    transform: translateY(-2px);
}
</style>

<!-- Intégration PDF.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="{{ asset('js/pdf-thumbnail-hd.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser PDF.js
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    
    // Créer l'instance du générateur de miniatures
    const thumbnailGenerator = new PdfThumbnailGenerator();
    
    // Générer les miniatures pour tous les PDFs
    const thumbnailContainers = document.querySelectorAll('.pdf-thumbnail-container');
    
    thumbnailContainers.forEach(container => {
        const pdfUrl = container.dataset.pdfUrl;
        if (pdfUrl) {
            thumbnailGenerator.generateThumbnail(pdfUrl, container)
                .then(() => {
                    console.log('Miniature générée avec succès pour:', pdfUrl);
                })
                .catch(error => {
                    console.error('Erreur lors de la génération de la miniature:', error);
                    
                    // Afficher un message d'erreur à l'utilisateur
                    container.innerHTML = `
                        <div class="flex flex-col items-center justify-center h-full text-red-600">
                            <i class="fas fa-exclamation-triangle text-4xl mb-2"></i>
                            <span class="text-sm">Erreur de miniature</span>
                        </div>
                    `;
                });
        }
    });
});
</script>
@endsection
