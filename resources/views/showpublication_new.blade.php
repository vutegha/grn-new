@extends('layouts.iri')

@section('title', 'Publication - ' . $publication->titre)

@section('content')
<!-- Breadcrumb -->
@include('partials.breadcrumb', [
    'breadcrumbs' => [
        ['title' => 'Publications', 'url' => route('site.publications')],
        ['title' => $publication->titre, 'url' => null]
    ]
])

<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <!-- Content -->
                <div class="lg:col-span-2">
                    <!-- Category Badge -->
                    @php
                        $categoryName = $publication->categorie->nom ?? 'Non catégorisé';
                        $badgeClass = match ($categoryName) {
                            'Rapport' => 'bg-blue-500/20 text-blue-100 border-blue-300/30',
                            'Article' => 'bg-yellow-500/20 text-yellow-100 border-yellow-300/30',
                            'Document' => 'bg-purple-500/20 text-purple-100 border-purple-300/30',
                            'Publication scientifique' => 'bg-emerald-500/20 text-emerald-100 border-emerald-300/30',
                            'Actualité' => 'bg-red-500/20 text-red-100 border-red-300/30',
                            default => 'bg-white/20 text-white border-white/30',
                        };
                    @endphp
                    
                    <div class="inline-flex items-center {{ $badgeClass }} border backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-6">
                        <i class="fas fa-tag mr-2"></i>
                        {{ $categoryName }}
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                        {{ $publication->titre }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-6 text-white/90 mb-8">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>{{ $publication->created_at->format('d M Y') }}</span>
                        </div>
                        @if($publication->auteur)
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                <span>{{ $publication->auteur->nom }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <button onclick="showToastAgain()"
                                class="bg-white/20 backdrop-blur-sm border border-white/30 text-white font-semibold py-3 px-6 rounded-lg hover:bg-white/30 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-book-open mr-2"></i>
                            Lire le résumé
                        </button>
                        
                        @if($publication->fichier_pdf)
                            <a href="{{ asset('storage/'.$publication->fichier_pdf) }}" 
                               target="_blank"
                               class="bg-iri-gold/80 backdrop-blur-sm border border-iri-gold/50 text-white font-semibold py-3 px-6 rounded-lg hover:bg-iri-gold transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger PDF
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Publication Preview -->
                <div class="lg:col-span-1">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 p-6 shadow-2xl">
                        @if($publication->fichier_pdf)
                            <canvas id="pdf-preview" 
                                    class="w-full rounded-lg shadow-lg" 
                                    data-pdf-url="{{ asset('storage/'.$publication->fichier_pdf) }}">
                            </canvas>
                        @else
                            <div class="aspect-[3/4] bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-alt text-white/50 text-6xl"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Resume Toast/Modal -->
    <div id="resumeToast"
         class="fixed left-6 top-[20vh] bg-white rounded-2xl shadow-2xl border border-gray-200 max-w-md w-full overflow-hidden z-50 hidden"
         style="max-height: 70vh;">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-iri-primary to-iri-secondary text-white p-4 flex justify-between items-center">
            <h4 class="text-lg font-bold flex items-center">
                <i class="fas fa-book-open mr-2"></i>
                Résumé de la publication
            </h4>
            <button onclick="closeToast()" class="text-white/80 hover:text-white text-xl">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto" style="max-height: calc(70vh - 80px);">
            <p class="text-gray-700 leading-relaxed">
                {{ $publication->resume ?? 'Aucun résumé disponible pour cette publication.' }}
            </p>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button onclick="showToastAgain()"
            class="fixed bottom-6 left-6 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-full shadow-lg w-14 h-14 flex items-center justify-center hover:shadow-xl transform hover:scale-110 transition-all duration-200 z-[60]">
        <i class="fas fa-book-open text-lg"></i>
    </button>

    <!-- Main Content Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-6">
                        <!-- Back Button -->
                        <div class="mb-6">
                            <a href="{{ route('site.publications') }}" 
                               class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-semibold transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Retour aux publications
                            </a>
                        </div>

                        <!-- Publication Info -->
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Informations</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-calendar-alt mr-3 text-iri-primary w-4"></i>
                                    <span>{{ $publication->created_at->format('d M Y') }}</span>
                                </div>
                                @if($publication->auteur)
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-user mr-3 text-iri-primary w-4"></i>
                                        <span>{{ $publication->auteur->nom }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-tag mr-3 text-iri-primary w-4"></i>
                                    <span>{{ $publication->categorie->nom ?? 'Non catégorisé' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Citation -->
                        @if($publication->citation)
                            <div class="mb-6 p-4 bg-gray-50 rounded-lg border-l-4 border-iri-primary">
                                <h4 class="font-semibold text-gray-900 mb-2">Comment citer :</h4>
                                <p class="text-sm text-gray-700 italic">{{ $publication->citation }}</p>
                            </div>
                        @endif

                        <!-- Related Publications -->
                        @if($autresPublications->count() > 0)
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 mb-4">Publications similaires</h4>
                                <div class="space-y-3">
                                    @foreach($autresPublications->take(3) as $otherPub)
                                        <a href="{{ route('publication.show', $otherPub->slug) }}" 
                                           class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                            <h5 class="font-medium text-gray-900 text-sm mb-1 line-clamp-2">
                                                {{ $otherPub->titre }}
                                            </h5>
                                            <p class="text-xs text-gray-500">
                                                {{ $otherPub->created_at->format('d M Y') }}
                                            </p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-gray-50 to-white p-6 border-b border-gray-200">
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $publication->titre }}</h2>
                            
                            @if($publication->description)
                                <div class="prose prose-gray max-w-none">
                                    {!! $publication->description !!}
                                </div>
                            @endif
                        </div>

                        <!-- PDF Viewer Controls -->
                        @if ($extension === 'pdf')
                            @php
                                $file = Storage::url($publication->fichier_pdf ?? '');
                            @endphp
                            <div class="bg-gray-50 p-4 border-b border-gray-200">
                                <div class="flex flex-wrap items-center gap-4">
                                    <div class="flex items-center gap-2">
                                        <input type="text" id="searchText" 
                                               placeholder="Rechercher dans le document..." 
                                               class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                                        <button id="searchBtn" 
                                                class="bg-iri-primary text-white px-4 py-2 rounded-lg hover:bg-iri-secondary transition-colors duration-200">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <button id="resetBtn" 
                                                class="hidden bg-gray-500 text-white px-3 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                                            <i class="fas fa-times mr-1"></i>Reset
                                        </button>
                                        <button id="prevMatch" 
                                                class="hidden bg-iri-accent text-white px-3 py-2 rounded-lg hover:bg-iri-gold transition-colors duration-200">
                                            ←
                                        </button>
                                        <button id="nextMatch" 
                                                class="hidden bg-iri-accent text-white px-3 py-2 rounded-lg hover:bg-iri-gold transition-colors duration-200">
                                            →
                                        </button>
                                        <span id="matchCount" class="hidden text-sm text-gray-600"></span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 ml-auto">
                                        <span id="pageCount" class="text-sm text-gray-600"></span>
                                        <button id="fullscreenBtn" 
                                                class="bg-iri-secondary text-white px-3 py-2 rounded-lg hover:bg-iri-primary transition-colors duration-200">
                                            <i class="fas fa-expand"></i>
                                        </button>
                                        <button id="downloadBtn" 
                                                class="bg-iri-gold text-white px-3 py-2 rounded-lg hover:bg-iri-accent transition-colors duration-200">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- PDF Loading -->
                            <div id="pdfLoader" class="flex flex-col items-center justify-center p-12 bg-gray-50">
                                <div class="bg-gradient-to-r from-iri-primary to-iri-secondary w-16 h-16 rounded-full flex items-center justify-center mb-4 animate-pulse">
                                    <i class="fas fa-file-pdf text-white text-2xl"></i>
                                </div>
                                <p class="text-gray-700 font-medium text-lg mb-4">Chargement du document PDF...</p>
                                <div class="w-full max-w-md">
                                    <div id="pdfProgressContainer" class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                        <div id="pdfProgressBar" class="bg-gradient-to-r from-iri-primary to-iri-secondary h-3 rounded-full transition-all duration-300" style="width: 0%;"></div>
                                    </div>
                                    <div id="pdfProgressText" class="text-sm text-gray-600 text-center">0%</div>
                                </div>
                            </div>

                            <!-- PDF Viewer -->
                            <div id="pdfViewer" class="p-6 space-y-6 bg-gray-50"></div>
                        @else
                            <div class="p-12 text-center">
                                <div class="bg-red-100 text-red-800 p-6 rounded-lg">
                                    <i class="fas fa-exclamation-triangle text-3xl mb-4"></i>
                                    <p class="text-lg">Ce type de fichier n'est pas pris en charge pour l'aperçu direct.</p>
                                    @if($publication->fichier_pdf)
                                        <a href="{{ asset('storage/'.$publication->fichier_pdf) }}" 
                                           target="_blank"
                                           class="inline-flex items-center mt-4 bg-red-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-700 transition-colors duration-200">
                                            <i class="fas fa-download mr-2"></i>
                                            Télécharger le fichier
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Styles -->
<style>
    #pdfViewer canvas {
        display: block;
        margin: 0 auto;
        width: 100% !important;
        height: auto !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }
    .highlighted {
        background-color: rgba(255, 255, 0, 0.4) !important;
        color: inherit !important;
        padding: 1px 2px;
        border-radius: 2px;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .prose {
        color: #374151;
        max-width: none;
    }
    .prose p {
        margin-bottom: 1rem;
        line-height: 1.7;
    }
    .prose h1, .prose h2, .prose h3, .prose h4 {
        color: #111827;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
</style>

<!-- Scripts -->
<script>
    function closeToast() {
        const toast = document.getElementById('resumeToast');
        toast.classList.add('hidden');
    }

    function showToastAgain() {
        const toast = document.getElementById('resumeToast');
        toast.classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Auto-show resume toast after 3 seconds
        setTimeout(function () {
            showToastAgain();
        }, 3000);
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        // Render PDF preview in hero section
        const previewCanvas = document.getElementById('pdf-preview');
        if (previewCanvas) {
            const url = previewCanvas.getAttribute('data-pdf-url');
            const ctx = previewCanvas.getContext('2d');
            const containerWidth = previewCanvas.parentElement.offsetWidth;

            pdfjsLib.getDocument(url).promise.then(pdf => {
                return pdf.getPage(1);
            }).then(page => {
                const viewport = page.getViewport({ scale: 1 });
                const scale = containerWidth / viewport.width;
                const scaledViewport = page.getViewport({ scale });

                previewCanvas.width = scaledViewport.width;
                previewCanvas.height = scaledViewport.height;

                return page.render({ canvasContext: ctx, viewport: scaledViewport }).promise;
            }).catch(err => {
                console.error("Erreur lors du rendu du PDF preview :", err);
            });
        }

        // PDF Viewer functionality (existing code would go here)
        // ... PDF rendering and search functionality
    });
</script>
@endsection
