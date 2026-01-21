@extends('layouts.app')

@section('title', $rapport->titre)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Navigation retour -->
    <div class="mb-6">
        <a href="{{ route('rapports.index') }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800 transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour aux rapports
        </a>
    </div>

    <!-- En-tête du rapport -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="p-8">
            <div class="flex flex-col lg:flex-row lg:items-start lg:space-x-8">
                <!-- Zone de miniature ou icône -->
                <div class="lg:w-1/3 mb-6 lg:mb-0">
                    <div class="relative h-64 {{ $rapport->getFileColor() }} rounded-lg border-2">
                        @if($rapport->canHaveThumbnail())
                            <!-- Miniature PDF avec PDF.js -->
                            <div class="w-full h-full flex items-center justify-center rounded-lg overflow-hidden">
                                <div id="thumbnail-{{ $rapport->id }}" 
                                     class="pdf-thumbnail-container w-full h-full flex items-center justify-center"
                                     data-pdf-url="{{ $rapport->getDownloadUrl() }}">
                                    <!-- Chargement -->
                                    <div class="loading-spinner flex flex-col items-center">
                                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600 mb-3"></div>
                                        <span class="text-sm text-gray-600">Génération de la miniature...</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Icône pour les autres types de fichiers -->
                            <div class="w-full h-full flex flex-col items-center justify-center rounded-lg">
                                <i class="{{ $rapport->getFileIcon() }} text-8xl mb-4"></i>
                                <div class="text-center">
                                    <span class="block text-xl font-medium text-gray-700">{{ $rapport->getFileType() }}</span>
                                    @if($rapport->getFileSize())
                                        <span class="block text-sm text-gray-500 mt-1">{{ $rapport->getFileSize() }}</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Informations du rapport -->
                <div class="lg:w-2/3">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $rapport->titre }}</h1>
                    
                    @if($rapport->description)
                        <div class="prose max-w-none mb-6">
                            <p class="text-gray-700 leading-relaxed">{{ $rapport->description }}</p>
                        </div>
                    @endif

                    <!-- Métadonnées -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        @if($rapport->categorie)
                            <div class="flex items-center">
                                <i class="fas fa-folder-open text-blue-500 mr-3"></i>
                                <div>
                                    <span class="text-sm font-medium text-gray-900">Catégorie</span>
                                    <p class="text-gray-600">{{ $rapport->categorie->nom }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($rapport->date_publication)
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-green-500 mr-3"></i>
                                <div>
                                    <span class="text-sm font-medium text-gray-900">Date de publication</span>
                                    <p class="text-gray-600">{{ $rapport->date_publication->format('d F Y') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($rapport->getFileSize())
                            <div class="flex items-center">
                                <i class="fas fa-file-download text-purple-500 mr-3"></i>
                                <div>
                                    <span class="text-sm font-medium text-gray-900">Taille du fichier</span>
                                    <p class="text-gray-600">{{ $rapport->getFileSize() }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-orange-500 mr-3"></i>
                            <div>
                                <span class="text-sm font-medium text-gray-900">Type de document</span>
                                <p class="text-gray-600">{{ $rapport->getFileType() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        @if($rapport->fichier)
                            <a href="{{ route('rapports.download', $rapport) }}" 
                               class="flex-1 px-6 py-3 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition duration-200 flex items-center justify-center font-medium">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger le rapport
                            </a>

                            @if($rapport->isPdf())
                                <a href="{{ route('rapports.preview', $rapport) }}" 
                                   target="_blank"
                                   class="flex-1 px-6 py-3 bg-green-600 text-white text-center rounded-lg hover:bg-green-700 transition duration-200 flex items-center justify-center font-medium">
                                    <i class="fas fa-eye mr-2"></i>
                                    Visualiser dans le navigateur
                                </a>
                            @endif
                        @else
                            <div class="flex-1 px-6 py-3 bg-gray-300 text-gray-500 text-center rounded-lg cursor-not-allowed font-medium">
                                <i class="fas fa-ban mr-2"></i>
                                Fichier indisponible
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rapports similaires -->
    @if($rapport->categorie)
        @php
            $rapportsSimilaires = \App\Models\Rapport::where('categorie_id', $rapport->categorie_id)
                ->where('id', '!=', $rapport->id)
                ->where('is_published', true)
                ->orderBy('date_publication', 'desc')
                ->limit(3)
                ->get();
        @endphp

        @if($rapportsSimilaires->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Rapports similaires</h2>
                
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($rapportsSimilaires as $rapportSimilaire)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-300">
                            <!-- Miniature compacte -->
                            <div class="relative h-32 {{ $rapportSimilaire->getFileColor() }} rounded-lg border mb-4">
                                @if($rapportSimilaire->canHaveThumbnail())
                                    <div class="w-full h-full flex items-center justify-center rounded-lg overflow-hidden">
                                        <div id="thumbnail-{{ $rapportSimilaire->id }}-small" 
                                             class="pdf-thumbnail-container w-full h-full flex items-center justify-center"
                                             data-pdf-url="{{ $rapportSimilaire->getDownloadUrl() }}">
                                            <div class="loading-spinner-small flex items-center justify-center">
                                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-red-600"></div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center rounded-lg">
                                        <i class="{{ $rapportSimilaire->getFileIcon() }} text-3xl mb-2"></i>
                                        <span class="text-xs text-gray-600">{{ $rapportSimilaire->getFileType() }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Contenu -->
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $rapportSimilaire->titre }}
                            </h3>
                            
                            @if($rapportSimilaire->date_publication)
                                <p class="text-sm text-gray-600 mb-3">
                                    {{ $rapportSimilaire->date_publication->format('d/m/Y') }}
                                </p>
                            @endif

                            <a href="{{ route('publication.show', ['slug' => $rapportSimilaire->slug]) }}" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Voir le rapport
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>

<!-- Styles CSS -->
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

.loading-spinner {
    min-height: 120px;
}

.loading-spinner-small {
    min-height: 60px;
}

.prose {
    line-height: 1.75;
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
            // Configuration différente pour les miniatures principales vs compactes
            const isSmall = container.id.includes('-small');
            const config = isSmall ? { scale: 2 } : { scale: 4 }; // Résolution réduite pour les miniatures compactes
            
            thumbnailGenerator.generateThumbnail(pdfUrl, container, config)
                .then(() => {
                    console.log('Miniature générée avec succès pour:', pdfUrl);
                })
                .catch(error => {
                    console.error('Erreur lors de la génération de la miniature:', error);
                    
                    // Afficher un message d'erreur à l'utilisateur
                    container.innerHTML = `
                        <div class="flex flex-col items-center justify-center h-full text-red-600">
                            <i class="fas fa-exclamation-triangle ${isSmall ? 'text-2xl' : 'text-4xl'} mb-2"></i>
                            <span class="text-xs">Erreur de miniature</span>
                        </div>
                    `;
                });
        }
    });
});
</script>
@endsection
