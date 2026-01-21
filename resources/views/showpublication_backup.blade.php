@extends('layouts.iri')

@section('title', 'Publication - ' . $publication->titre)

@section('content')
<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <!-- Breadcrumb Overlay -->
        @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
            <nav class="absolute top-4 left-0 right-0 z-10" aria-label="Breadcrumb">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li>
                            <a href="{{ url('/') }}" class="text-white/70 hover:text-white transition-colors duration-200">
                                <i class="fas fa-home mr-1"></i>
                                Accueil
                            </a>
                        </li>
                        
                        @foreach($breadcrumbs as $breadcrumb)
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-white/50 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                @if($loop->last)
                                    <span class="text-white font-medium">{{ $breadcrumb['title'] }}</span>
                                @else
                                    <a href="{{ $breadcrumb['url'] }}" class="text-white/70 hover:text-white transition-colors duration-200">
                                        {{ $breadcrumb['title'] }}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </div>
            </nav>
        @endif
        
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
                        @if(optional($autresPublications)->count() > 0)
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

                        <!-- PDF Viewer Simple -->
                        @if($publication->fichier_pdf)
                            @php
                                $fileUrl = asset('storage/' . $publication->fichier_pdf);
                                $extension = pathinfo($publication->fichier_pdf, PATHINFO_EXTENSION);
                            @endphp
                            
                            @if($extension === 'pdf')
                                <!-- Visualiseur PDF -->
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                    <!-- Header -->
                                    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-200">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                                <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                                Document PDF
                                            </h3>
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ $fileUrl }}" 
                                                   target="_blank" 
                                                   class="inline-flex items-center px-4 py-2 bg-iri-primary text-white text-sm font-medium rounded-lg hover:bg-iri-secondary transition-colors duration-200">
                                                    <i class="fas fa-external-link-alt mr-2"></i>Ouvrir
                                                </a>
                                                <a href="{{ $fileUrl }}" 
                                                   download 
                                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                                    <i class="fas fa-download mr-2"></i>Télécharger
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PDF Content -->
                                    <div class="p-6">
                                        <iframe src="{{ $fileUrl }}" 
                                                width="100%" 
                                                height="600" 
                                                class="border rounded-lg shadow-sm"
                                                title="Visualiseur PDF">
                                            <p class="text-center text-gray-600 p-6">
                                                Votre navigateur ne prend pas en charge l'affichage des PDF. 
                                                <a href="{{ $fileUrl }}" target="_blank" class="text-iri-primary hover:underline">
                                                    Cliquez ici pour ouvrir le PDF.
                                                </a>
                                            </p>
                                        </iframe>
                                    </div>
                                </div>
                            @else
                                <!-- Autres types de fichiers -->
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden p-8 text-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-file text-2xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Fichier {{ strtoupper($extension) }}</h3>
                                    <p class="text-gray-600 mb-4">Ce type de fichier ne peut pas être affiché dans le navigateur.</p>
                                    <a href="{{ $fileUrl }}" 
                                       download 
                                       class="inline-flex items-center px-6 py-3 bg-iri-primary text-white font-medium rounded-lg hover:bg-iri-secondary transition-colors duration-200">
                                        <i class="fas fa-download mr-2"></i>Télécharger le fichier
                                    </a>
                                </div>
                            @endif
                        @else
                            <!-- Aucun fichier -->
                            <div class="bg-amber-50 border border-amber-200 text-amber-800 p-8 rounded-xl text-center">
                                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-exclamation-triangle text-2xl text-amber-600"></i>
                                </div>
                                <h3 class="text-xl font-semibold mb-3">Aucun fichier disponible</h3>
                                <p class="text-lg">Cette publication n'a pas de fichier associé.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

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
        
        // Simple PDF preview fallback
        const previewCanvas = document.getElementById('pdf-preview');
        if (previewCanvas) {
            previewCanvas.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
            previewCanvas.style.display = 'flex';
            previewCanvas.style.alignItems = 'center';
            previewCanvas.style.justifyContent = 'center';
            previewCanvas.innerHTML = '<i class="fas fa-file-pdf text-white text-6xl"></i>';
        }
    });
</script>

<!-- Styles pour le visualiseur PDF -->
<style>
    iframe {
        background: #f8f9fa;
        transition: all 0.3s ease;
    }
    
    iframe:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    @media (max-width: 768px) {
        iframe {
            height: 400px !important;
        }
    }
</style>
@endsection
        const loaderDiv = document.getElementById('pdfLoader');
        const viewerDiv = document.getElementById('pdfViewer');
        const pageCountDisplay = document.getElementById('pageCount');
        const fileSizeDisplay = document.getElementById('fileSize');
        const zoomLevelDisplay = document.getElementById('zoomLevel');
        const loadingProgress = document.getElementById('loadingProgress');
        const loadingBar = document.getElementById('loadingBar');
        const loadingText = document.getElementById('loadingText');
        const loadingPercent = document.getElementById('loadingPercent');
        
        let pdfDocument = null;
        let currentPage = 1;
        let scale = 1.2;
        let searchMatches = [];
        let currentMatchIndex = -1;

        // Show loader initially
        showLoader();
        updateProgress(0, 'Initialisation...');

        // Load PDF document
        const loadingOptions = {
            url: url,
            disableWorker: false,
            cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
            cMapPacked: true,
            disableAutoFetch: false,
            disableStream: false
        };

        const loadingTask = pdfjsLib.getDocument(loadingOptions);
        
        // Progress tracking
        loadingTask.onProgress = function(progress) {
            if (progress.loaded && progress.total) {
                const percent = Math.round((progress.loaded / progress.total) * 100);
                const loadedMB = (progress.loaded / (1024 * 1024)).toFixed(1);
                const totalMB = (progress.total / (1024 * 1024)).toFixed(1);
                updateProgress(percent, `Chargement... ${loadedMB} MB / ${totalMB} MB`);
                
                // Update file size display
                if (fileSizeDisplay) {
                    fileSizeDisplay.innerHTML = `<i class="fas fa-hdd mr-2 text-iri-primary"></i><span class="font-medium">${totalMB} MB</span>`;
                }
            }
        };

        loadingTask.promise.then(function(pdf) {
            pdfDocument = pdf;
            updateProgress(100, 'Rendu en cours...');
            
            setTimeout(() => {
                setupPdfViewer(pdf);
                setupControls(pdf);
                showViewer();
            }, 500);
        }).catch(function(error) {
            console.error('Erreur lors du chargement du PDF:', error);
            showError('Impossible de charger le document PDF. Le fichier peut être corrompu.');
        });

        function setupPdfViewer(pdf) {
            console.log('Configuration du visualiseur PDF, pages:', pdf.numPages);
            
            // Update UI elements
            if (pageCountDisplay) {
                pageCountDisplay.innerHTML = `<i class="fas fa-file-alt mr-2 text-iri-primary"></i><span class="font-medium">${pdf.numPages} pages</span>`;
            }
            
            const totalPagesElements = document.querySelectorAll('#totalPages, #floatingTotalPages');
            totalPagesElements.forEach(el => el.textContent = pdf.numPages);
            
            const pageInputElements = document.querySelectorAll('#pageInput, #floatingPageInput');
            pageInputElements.forEach(el => {
                el.value = currentPage;
                el.max = pdf.numPages;
            });
            
            // Clear viewer
            viewerDiv.innerHTML = '';
            
            // Render all pages
            renderAllPages(pdf);
        }

        function setupControls(pdf) {
            // Zoom controls
            const zoomInBtn = document.getElementById('zoomIn');
            const zoomOutBtn = document.getElementById('zoomOut');
            const fitToWidthBtn = document.getElementById('fitToWidth');
            
            if (zoomInBtn) {
                zoomInBtn.addEventListener('click', function() {
                    scale = Math.min(scale * 1.25, 3.0);
                    updateZoomLevel();
                    rerenderAllPages(pdf);
                });
            }
            
            if (zoomOutBtn) {
                zoomOutBtn.addEventListener('click', function() {
                    scale = Math.max(scale / 1.25, 0.5);
                    updateZoomLevel();
                    rerenderAllPages(pdf);
                });
            }
            
            if (fitToWidthBtn) {
                fitToWidthBtn.addEventListener('click', function() {
                    const container = document.getElementById('pdfViewer');
                    const containerWidth = container.clientWidth - 48; // padding
                    pdf.getPage(1).then(function(page) {
                        const viewport = page.getViewport({scale: 1});
                        scale = containerWidth / viewport.width;
                        updateZoomLevel();
                        rerenderAllPages(pdf);
                    });
                });
            }

            // Navigation controls
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const pageInput = document.getElementById('pageInput');
            
            if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                    if (currentPage > 1) {
                        currentPage--;
                        scrollToPage(currentPage);
                        updatePageInfo();
                    }
                });
            }
            
            if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                    if (currentPage < pdf.numPages) {
                        currentPage++;
                        scrollToPage(currentPage);
                        updatePageInfo();
                    }
                });
            }
            
            if (pageInput) {
                pageInput.addEventListener('change', function() {
                    const pageNum = parseInt(pageInput.value);
                    if (pageNum >= 1 && pageNum <= pdf.numPages) {
                        currentPage = pageNum;
                        scrollToPage(currentPage);
                        updatePageInfo();
                    } else {
                        pageInput.value = currentPage;
                    }
                });
                
                pageInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const pageNum = parseInt(pageInput.value);
                        if (pageNum >= 1 && pageNum <= pdf.numPages) {
                            currentPage = pageNum;
                            scrollToPage(currentPage);
                            updatePageInfo();
                        } else {
                            pageInput.value = currentPage;
                        }
                    }
                });
            }

            // Search functionality
            const searchBtn = document.getElementById('searchBtn');
            const searchInput = document.getElementById('searchText');
            const prevMatchBtn = document.getElementById('prevMatch');
            const nextMatchBtn = document.getElementById('nextMatch');
            const clearSearchBtn = document.getElementById('clearSearch');
            
            if (searchBtn && searchInput) {
                searchBtn.addEventListener('click', function() {
                    performSearch(searchInput.value.trim(), pdf);
                });
                
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        performSearch(searchInput.value.trim(), pdf);
                    }
                });
            }

            if (prevMatchBtn) {
                prevMatchBtn.addEventListener('click', function() {
                    navigateSearchResults(-1);
                });
            }

            if (nextMatchBtn) {
                nextMatchBtn.addEventListener('click', function() {
                    navigateSearchResults(1);
                });
            }

            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function() {
                    clearSearch();
                });
            }

            // Other controls
            const printBtn = document.getElementById('printBtn');
            const fullscreenBtn = document.getElementById('fullscreenBtn');
            
            if (printBtn) {
                printBtn.addEventListener('click', function() {
                    window.open(url, '_blank');
                });
            }

            if (fullscreenBtn) {
                fullscreenBtn.addEventListener('click', function() {
                    const container = document.getElementById('pdfContainer');
                    if (container.requestFullscreen) {
                        container.requestFullscreen();
                    } else if (container.webkitRequestFullscreen) {
                        container.webkitRequestFullscreen();
                    } else if (container.msRequestFullscreen) {
                        container.msRequestFullscreen();
                    }
                });
            }
        }

        function updateZoomLevel() {
            if (zoomLevelDisplay) {
                zoomLevelDisplay.textContent = Math.round(scale * 100) + '%';
            }
        }

        function rerenderAllPages(pdf) {
            clearHighlights();
            viewerDiv.innerHTML = '';
            renderAllPages(pdf);
        }

        function renderAllPages(pdf) {
            console.log(`Rendu de ${pdf.numPages} pages`);
            
            // Create all page containers
            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                const pageContainer = document.createElement('div');
                pageContainer.className = 'pdf-page-container relative mb-8 text-center';
                pageContainer.id = `page-${pageNum}`;
                
                // Page label
                const pageLabel = document.createElement('div');
                pageLabel.className = 'text-center text-gray-500 text-sm mb-3 font-medium';
                pageLabel.textContent = `Page ${pageNum}`;
                pageContainer.appendChild(pageLabel);
                
                // Loading placeholder
                const loadingDiv = document.createElement('div');
                loadingDiv.className = 'page-loading bg-gray-100 p-8 rounded-lg border-2 border-dashed border-gray-300';
                loadingDiv.innerHTML = `
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Chargement page ${pageNum}...</p>
                `;
                pageContainer.appendChild(loadingDiv);
                
                viewerDiv.appendChild(pageContainer);
            }
            
            // Render pages sequentially
            renderPagesSequentially(pdf, 1);
        }

        async function renderPagesSequentially(pdf, pageNum) {
            if (pageNum > pdf.numPages) {
                console.log('Rendu de toutes les pages terminé');
                return;
            }
            
            const pageContainer = document.getElementById(`page-${pageNum}`);
            const loadingDiv = pageContainer.querySelector('.page-loading');
            
            try {
                const page = await pdf.getPage(pageNum);
                const viewport = page.getViewport({ scale: scale });
                
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                canvas.className = 'rounded-xl shadow-lg border border-gray-200 max-w-full h-auto transition-all duration-300';
                
                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                
                await page.render(renderContext).promise;
                
                // Replace loading with canvas
                if (loadingDiv) {
                    loadingDiv.remove();
                }
                pageContainer.appendChild(canvas);
                
                console.log(`Page ${pageNum} rendue avec succès`);
                
            } catch (error) {
                console.error(`Erreur lors du rendu de la page ${pageNum}:`, error);
                
                if (loadingDiv) {
                    loadingDiv.className = 'bg-red-100 text-red-800 p-4 rounded-lg border border-red-300';
                    loadingDiv.innerHTML = `
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl mb-2"></i>
                        <p>Erreur lors du chargement de la page ${pageNum}</p>
                    `;
                }
            }
            
            // Continue with next page
            renderPagesSequentially(pdf, pageNum + 1);
        }

        function scrollToPage(pageNumber) {
            const pageElement = document.getElementById(`page-${pageNumber}`);
            if (pageElement) {
                pageElement.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start'
                });
            }
        }

        function updatePageInfo() {
            const pageInputElements = document.querySelectorAll('#pageInput, #floatingPageInput');
            pageInputElements.forEach(el => el.value = currentPage);
        }

        function showLoader() {
            if (loaderDiv) loaderDiv.classList.remove('hidden');
            if (viewerDiv) viewerDiv.classList.add('hidden');
            if (loadingProgress) loadingProgress.classList.remove('hidden');
        }

        function showViewer() {
            if (loaderDiv) loaderDiv.classList.add('hidden');
            if (viewerDiv) viewerDiv.classList.remove('hidden');
            if (loadingProgress) loadingProgress.classList.add('hidden');
        }

        function updateProgress(percent, text) {
            if (loadingBar) loadingBar.style.width = percent + '%';
            if (loadingText) loadingText.textContent = text;
            if (loadingPercent) loadingPercent.textContent = percent + '%';
            
            // Also update the main progress bar
            const mainProgressBar = document.getElementById('pdfProgressBar');
            const mainProgressText = document.getElementById('pdfProgressText');
            if (mainProgressBar) mainProgressBar.style.width = percent + '%';
            if (mainProgressText) mainProgressText.textContent = text;
        }

        function performSearch(searchTerm, pdf) {
            if (!searchTerm) return;
            
            console.log('Recherche de:', searchTerm);
            const searchResults = document.getElementById('searchResults');
            const matchCountDisplay = document.getElementById('matchCount');
            
            // Reset previous search
            clearSearch();
            
            if (searchResults) {
                searchResults.classList.remove('hidden');
                if (matchCountDisplay) {
                    matchCountDisplay.textContent = 'Recherche en cours...';
                }
            }
            
            // Simulate search (in real implementation, you would search through PDF text)
            setTimeout(() => {
                const mockMatches = Math.floor(Math.random() * 15) + 1;
                if (matchCountDisplay) {
                    matchCountDisplay.textContent = `${mockMatches} résultat(s) trouvé(s)`;
                }
                searchMatches = Array.from({length: mockMatches}, (_, i) => ({
                    page: Math.floor(Math.random() * pdf.numPages) + 1, 
                    index: i
                }));
                currentMatchIndex = 0;
                updateSearchNavigation();
                
                if (searchMatches.length > 0) {
                    navigateToMatch();
                }
            }, 800);
        }

        function navigateSearchResults(direction) {
            if (searchMatches.length === 0) return;
            
            currentMatchIndex += direction;
            if (currentMatchIndex < 0) currentMatchIndex = searchMatches.length - 1;
            if (currentMatchIndex >= searchMatches.length) currentMatchIndex = 0;
            
            updateSearchNavigation();
            navigateToMatch();
        }

        function navigateToMatch() {
            if (searchMatches.length === 0) return;
            
            const match = searchMatches[currentMatchIndex];
            currentPage = match.page;
            scrollToPage(currentPage);
            updatePageInfo();
            
            // Highlight the page temporarily
            const pageElement = document.getElementById(`page-${match.page}`);
            if (pageElement) {
                pageElement.classList.add('active-glow');
                setTimeout(() => {
                    pageElement.classList.remove('active-glow');
                }, 2000);
            }
        }

        function updateSearchNavigation() {
            const prevBtn = document.getElementById('prevMatch');
            const nextBtn = document.getElementById('nextMatch');
            const matchCountDisplay = document.getElementById('matchCount');
            
            if (searchMatches.length > 0) {
                if (prevBtn) prevBtn.disabled = false;
                if (nextBtn) nextBtn.disabled = false;
                if (matchCountDisplay) {
                    matchCountDisplay.textContent = `${currentMatchIndex + 1} sur ${searchMatches.length}`;
                }
            } else {
                if (prevBtn) prevBtn.disabled = true;
                if (nextBtn) nextBtn.disabled = true;
            }
        }

        function clearSearch() {
            const searchResults = document.getElementById('searchResults');
            const searchInput = document.getElementById('searchText');
            
            if (searchResults) searchResults.classList.add('hidden');
            if (searchInput) searchInput.value = '';
            
            searchMatches = [];
            currentMatchIndex = -1;
            clearHighlights();
        }

        function clearHighlights() {
            // Remove glow effects
            const glowElements = document.querySelectorAll('.active-glow');
            glowElements.forEach(el => el.classList.remove('active-glow'));
        }

        function showError(message) {
            if (loaderDiv) {
                loaderDiv.innerHTML = `
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-exclamation-triangle text-3xl text-red-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Erreur de chargement</h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">${message}</p>
                        <a href="${url}" target="_blank" 
                           class="inline-flex items-center px-6 py-3 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors shadow-md hover:shadow-lg">
                            <i class="fas fa-external-link-alt mr-2"></i>Ouvrir dans un nouvel onglet
                        </a>
                    </div>
                `;
            }
        }

        function formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    }

        function setupPdfControls(pdf) {
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const pageInput = document.getElementById('pageInput');
            const fullscreenBtn = document.getElementById('fullscreenBtn');

            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    scrollToPage(currentPage);
                    updatePageInfo();
                }
            };

            nextBtn.onclick = () => {
                if (currentPage < pdf.numPages) {
                    currentPage++;
                    scrollToPage(currentPage);
                    updatePageInfo();
                }
            };

            // Handle direct page input
            pageInput.addEventListener('change', () => {
                const pageNum = parseInt(pageInput.value);
                if (pageNum >= 1 && pageNum <= pdf.numPages) {
                    currentPage = pageNum;
                    scrollToPage(currentPage);
                    updatePageInfo();
                } else {
                    pageInput.value = currentPage; // Reset to current page if invalid
                }
            });

            pageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const pageNum = parseInt(pageInput.value);
                    if (pageNum >= 1 && pageNum <= pdf.numPages) {
                        currentPage = pageNum;
                        scrollToPage(currentPage);
                        updatePageInfo();
                    } else {
                        pageInput.value = currentPage; // Reset to current page if invalid
                    }
                }
            });

            fullscreenBtn.onclick = () => {
                const container = document.getElementById('pdfContainer');
                if (container.requestFullscreen) {
                    container.requestFullscreen();
                } else if (container.webkitRequestFullscreen) {
                    container.webkitRequestFullscreen();
                } else if (container.msRequestFullscreen) {
                    container.msRequestFullscreen();
                }
            };
        }

        function scrollToPage(pageNumber) {
            const pageElement = document.getElementById(`page-${pageNumber}`);
            if (pageElement) {
                console.log(`Défilement vers la page ${pageNumber}`);
                
                // For page 1, scroll to the very top
                if (pageNumber === 1) {
                    viewerDiv.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                    // Also use window scroll as fallback
                    window.scrollTo({
                        top: viewerDiv.offsetTop,
                        behavior: 'smooth'
                    });
                } else {
                    // For other pages, scroll into view
                    pageElement.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start',
                        inline: 'nearest'
                    });
                }
            } else {
                console.warn(`Élément page-${pageNumber} introuvable`);
            }
        }

        function setupSearchControls(pdf) {
            const searchText = document.getElementById('searchText');
            const searchBtn = document.getElementById('searchBtn');
            const resetBtn = document.getElementById('resetBtn');
            const prevMatch = document.getElementById('prevMatch');
            const nextMatch = document.getElementById('nextMatch');
            const matchCount = document.getElementById('matchCount');

            console.log('Initialisation des contrôles de recherche');

            // Vérifier que tous les éléments existent
            if (!searchText || !searchBtn) {
                console.error('Éléments de recherche introuvables');
                return;
            }

            searchBtn.onclick = () => {
                console.log('Bouton de recherche cliqué');
                performSearch(pdf);
            };
            
            resetBtn.onclick = () => {
                console.log('Reset de recherche');
                resetSearch();
            };
            
            searchText.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    console.log('Recherche par Enter');
                    performSearch(pdf);
                }
            });

            prevMatch.onclick = () => {
                console.log('Résultat précédent');
                if (currentMatch > 0) {
                    currentMatch--;
                    goToMatch(pdf);
                }
            };

            nextMatch.onclick = () => {
                console.log('Résultat suivant');
                if (currentMatch < searchMatches.length - 1) {
                    currentMatch++;
                    goToMatch(pdf);
                }
            };

            async function performSearch(pdf) {
                const query = searchText.value.trim();
                if (!query) {
                    alert('Veuillez saisir un terme à rechercher');
                    return;
                }

                // Show loading indicator
                searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                searchBtn.disabled = true;

                // Clear previous highlights
                clearHighlights();

                searchMatches = [];
                currentMatch = 0;

                try {
                    console.log(`Début de la recherche pour "${query}"`);
                    
                    // Search all pages with optimized processing
                    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                        const page = await pdf.getPage(pageNum);
                        const textContent = await page.getTextContent();
                        
                        // Quick text extraction without storing positions initially
                        let pageText = '';
                        textContent.items.forEach(item => {
                            if (item.str && item.str.trim()) {
                                pageText += item.str + ' ';
                            }
                        });

                        // Search with case-insensitive regex
                        const regex = new RegExp(query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi');
                        let match;
                        
                        // Only process positions for pages that have matches
                        const pageMatches = [];
                        while ((match = regex.exec(pageText)) !== null) {
                            pageMatches.push({
                                index: match.index,
                                text: match[0],
                                context: pageText.substring(Math.max(0, match.index - 50), match.index + match[0].length + 50)
                            });
                        }
                        
                        // Only extract detailed positioning if there are matches on this page
                        if (pageMatches.length > 0) {
                            const textItems = textContent.items.filter(item => item.str && item.str.trim());
                            let textMap = [];
                            let currentIndex = 0;
                            
                            textItems.forEach((item, itemIndex) => {
                                const text = item.str;
                                textMap.push({
                                    itemIndex: itemIndex,
                                    startIndex: currentIndex,
                                    endIndex: currentIndex + text.length,
                                    text: text,
                                    transform: item.transform,
                                    bbox: {
                                        x: item.transform[4],
                                        y: item.transform[5],
                                        width: item.width,
                                        height: item.height
                                    }
                                });
                                pageText += text + ' ';
                                currentIndex += text.length + 1;
                            });
                            
                            // Now map matches to text items
                            pageMatches.forEach(pageMatch => {
                                const matchingItems = textMap.filter(item => 
                                    pageMatch.index < item.endIndex && (pageMatch.index + pageMatch.text.length) > item.startIndex
                                );

                                searchMatches.push({
                                    page: pageNum,
                                    text: pageMatch.text,
                                    index: pageMatch.index,
                                    context: pageMatch.context,
                                    textItems: matchingItems,
                                    matchStart: pageMatch.index,
                                    matchEnd: pageMatch.index + pageMatch.text.length
                                });
                            });
                        }
                    }

                    console.log(`Recherche "${query}" terminée - ${searchMatches.length} résultats trouvés`);

                    if (searchMatches.length > 0) {
                        // Highlight all matches
                        await highlightAllMatches(pdf);
                        showSearchResults();
                        goToMatch(pdf);
                    } else {
                        alert(`Aucun résultat trouvé pour "${query}"`);
                    }
                } catch (error) {
                    console.error('Erreur lors de la recherche:', error);
                    alert('Erreur lors de la recherche. Veuillez réessayer.');
                } finally {
                    // Restore search button
                    searchBtn.innerHTML = '<i class="fas fa-search"></i>';
                    searchBtn.disabled = false;
                }
            }

            function goToMatch(pdf) {
                if (searchMatches.length === 0) return;
                
                const match = searchMatches[currentMatch];
                currentPage = match.page;
                
                console.log(`Navigation vers le résultat ${currentMatch + 1}/${searchMatches.length} sur la page ${match.page}`);
                
                // Scroll to the page
                scrollToPage(currentPage);
                
                // Update UI
                updatePageInfo();
                updateMatchInfo();
                
                // Highlight current match with special styling
                highlightCurrentMatch(match);
            }

            async function highlightAllMatches(pdf) {
                console.log('Début du surlignage de tous les résultats');
                
                // Group matches by page for efficiency
                const matchesByPage = {};
                searchMatches.forEach(match => {
                    if (!matchesByPage[match.page]) {
                        matchesByPage[match.page] = [];
                    }
                    matchesByPage[match.page].push(match);
                });
                
                // Process pages with matches only
                for (const pageNum of Object.keys(matchesByPage)) {
                    const pageMatches = matchesByPage[pageNum];
                    const pageContainer = document.getElementById(`page-${pageNum}`);
                    if (!pageContainer) continue;

                    // Remove existing highlight overlay for this page
                    const existingOverlay = pageContainer.querySelector('.search-highlight-overlay');
                    if (existingOverlay) {
                        existingOverlay.remove();
                    }

                    try {
                        const page = await pdf.getPage(parseInt(pageNum));
                        const viewport = page.getViewport({ scale: scale });
                        const canvas = pageContainer.querySelector('canvas');
                        
                        if (!canvas) continue;

                        // Create highlight overlay
                        const highlightOverlay = document.createElement('div');
                        highlightOverlay.className = 'search-highlight-overlay';
                        highlightOverlay.style.cssText = `
                            position: absolute;
                            top: ${canvas.offsetTop}px;
                            left: ${canvas.offsetLeft}px;
                            width: ${canvas.offsetWidth}px;
                            height: ${canvas.offsetHeight}px;
                            pointer-events: none;
                            z-index: 10;
                        `;

                        // Create highlights for all matches on this page
                        pageMatches.forEach((match, matchIndex) => {
                            if (match.textItems && match.textItems.length > 0) {
                                match.textItems.forEach((textItem, index) => {
                                    const highlightRect = document.createElement('div');
                                    highlightRect.className = 'search-highlight-rect';
                                    highlightRect.dataset.matchIndex = matchIndex;
                                    
                                    // Calculate position relative to canvas
                                    const x = textItem.bbox.x * scale;
                                    const y = viewport.height - (textItem.bbox.y * scale) - (textItem.bbox.height * scale);
                                    const width = textItem.bbox.width * scale;
                                    const height = textItem.bbox.height * scale;

                                    highlightRect.style.cssText = `
                                        position: absolute;
                                        left: ${x}px;
                                        top: ${y}px;
                                        width: ${width}px;
                                        height: ${height}px;
                                        background-color: rgba(255, 255, 0, 0.3);
                                        border: 1px solid rgba(255, 193, 7, 0.8);
                                        border-radius: 2px;
                                        transition: all 0.3s ease;
                                    `;

                                    highlightOverlay.appendChild(highlightRect);
                                });
                            }
                        });

                        // Make container relative for proper overlay positioning
                        pageContainer.style.position = 'relative';
                        pageContainer.appendChild(highlightOverlay);

                    } catch (error) {
                        console.error(`Erreur lors du surlignage de la page ${pageNum}:`, error);
                    }
                }
                
                console.log('Surlignage de tous les résultats terminé');
            }

            function highlightCurrentMatch(match) {
                // Remove previous current match highlighting
                document.querySelectorAll('.current-match-highlight').forEach(el => el.remove());
                
                const pageContainer = document.getElementById(`page-${match.page}`);
                if (!pageContainer) return;

                // Add special highlighting for current match
                const currentHighlights = pageContainer.querySelectorAll('.search-highlight-rect');
                if (currentHighlights.length > 0) {
                    // Find the highlight that corresponds to current match
                    // For simplicity, highlight the first one with a different color
                    const currentRect = currentHighlights[0];
                    if (currentRect) {
                        const currentMarker = currentRect.cloneNode(true);
                        currentMarker.className = 'current-match-highlight';
                        currentMarker.style.cssText += `
                            background-color: rgba(255, 0, 0, 0.4) !important;
                            border: 2px solid rgba(220, 38, 127, 0.9) !important;
                            box-shadow: 0 0 10px rgba(220, 38, 127, 0.5);
                            z-index: 15;
                        `;
                        
                        const overlay = pageContainer.querySelector('.search-highlight-overlay');
                        if (overlay) {
                            overlay.appendChild(currentMarker);
                        }
                    }
                }

                // Add page border highlight for current match
                pageContainer.style.border = '3px solid #dc2626';
                pageContainer.style.borderRadius = '8px';
                pageContainer.style.transition = 'all 0.3s ease';
                
                // Remove page highlight after navigation
                setTimeout(() => {
                    pageContainer.style.border = '';
                    pageContainer.style.borderRadius = '';
                }, 3000);
            }

            function clearHighlights() {
                console.log('Suppression de tous les surlignages');
                
                // Remove all search highlight overlays efficiently
                const overlays = document.querySelectorAll('.search-highlight-overlay');
                overlays.forEach(overlay => overlay.remove());
                
                // Remove current match highlights
                const currentHighlights = document.querySelectorAll('.current-match-highlight');
                currentHighlights.forEach(highlight => highlight.remove());
                
                // Remove page border highlights
                const pages = document.querySelectorAll('.pdf-page-container');
                pages.forEach(page => {
                    page.style.border = '';
                    page.style.borderRadius = '';
                });
            }

            function highlightSearchTerm(searchTerm) {
                // This function is now replaced by the more advanced highlighting system
                const pageElement = document.getElementById(`page-${currentPage}`);
                if (pageElement) {
                    pageElement.style.border = '3px solid #f59e0b';
                    pageElement.style.borderRadius = '8px';
                    
                    // Remove highlight after 2 seconds
                    setTimeout(() => {
                        pageElement.style.border = '';
                        pageElement.style.borderRadius = '';
                    }, 2000);
                }
            }

            function showSearchResults() {
                resetBtn.classList.remove('hidden');
                prevMatch.classList.remove('hidden');
                nextMatch.classList.remove('hidden');
                matchCount.classList.remove('hidden');
                updateMatchInfo();
                
                // Update floating navigation search info
                if (window.updateFloatingSearchInfo) {
                    window.updateFloatingSearchInfo();
                }
            }

            function resetSearch() {
                console.log('Reset de la recherche');
                
                // Clear all highlights first
                clearHighlights();
                
                searchMatches = [];
                currentMatch = 0;
                searchText.value = '';
                resetBtn.classList.add('hidden');
                prevMatch.classList.add('hidden');
                nextMatch.classList.add('hidden');
                matchCount.classList.add('hidden');
                
                // Update floating navigation search info
                if (window.updateFloatingSearchInfo) {
                    window.updateFloatingSearchInfo();
                }
            }

            function updateMatchInfo() {
                if (searchMatches.length > 0) {
                    const matchText = `${currentMatch + 1} / ${searchMatches.length}`;
                    matchCount.textContent = matchText;
                    console.log(`Affichage du résultat: ${matchText}`);
                    
                    // Update floating navigation search info
                    if (window.updateFloatingSearchInfo) {
                        window.updateFloatingSearchInfo();
                    }
                }
            }
        }

        function renderPage(pdf, pageNumber) {
            // Cette fonction n'est plus utilisée car nous rendons toutes les pages
            renderAllPages(pdf);
        }

        function renderAllPages(pdf) {
            // Clear any existing highlights only if they exist
            if (searchMatches.length > 0) {
                clearHighlights();
            }
            
            viewerDiv.innerHTML = '';
            
            console.log(`Rendu de ${pdf.numPages} pages en ordre séquentiel`);
            
            // Reset scroll position to top
            viewerDiv.scrollTop = 0;
            
            // Créer tous les conteneurs de pages d'abord pour maintenir l'ordre
            const pageContainers = [];
            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                const pageContainer = document.createElement('div');
                pageContainer.className = 'pdf-page-container mb-4';
                pageContainer.id = `page-${pageNum}`;
                
                // Add page number indicator
                const pageLabel = document.createElement('div');
                pageLabel.className = 'text-center text-gray-500 text-sm mb-2';
                pageLabel.textContent = `Page ${pageNum}`;
                pageContainer.appendChild(pageLabel);
                
                // Add loading placeholder
                const loadingDiv = document.createElement('div');
                loadingDiv.className = 'bg-gray-100 p-8 rounded-lg text-center';
                loadingDiv.innerHTML = `
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Chargement de la page ${pageNum}...</p>
                `;
                pageContainer.appendChild(loadingDiv);
                
                pageContainers.push(pageContainer);
                viewerDiv.appendChild(pageContainer);
            }
            
            // Maintenant rendre chaque page dans son conteneur
            renderPagesSequentially(pdf, pageContainers, 1);
        }

        async function renderPagesSequentially(pdf, pageContainers, pageNum) {
            if (pageNum > pdf.numPages) {
                console.log('Rendu de toutes les pages terminé');
                // Scroll to the first page when all pages are rendered
                setTimeout(() => {
                    scrollToPage(1);
                    currentPage = 1;
                    updatePageInfo();
                }, 500);
                return;
            }
            
            const pageContainer = pageContainers[pageNum - 1];
            const loadingDiv = pageContainer.querySelector('.bg-gray-100');
            
            try {
                const page = await pdf.getPage(pageNum);
                const viewport = page.getViewport({ scale: scale });
                
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                
                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport,
                    intent: 'display'
                };
                
                await page.render(renderContext).promise;
                
                // Remplacer le placeholder par le canvas
                if (loadingDiv) {
                    pageContainer.removeChild(loadingDiv);
                }
                pageContainer.appendChild(canvas);
                
                console.log(`Page ${pageNum} rendue avec succès`);
                
            // Rendre la page suivante
            renderPagesSequentially(pdf, pageContainers, pageNum + 1);
            
        } catch (error) {
            console.error(`Erreur lors du rendu de la page ${pageNum}:`, error);
            
            // Remplacer le placeholder par un message d'erreur
            if (loadingDiv) {
                loadingDiv.className = 'bg-red-100 text-red-800 p-4 rounded-lg text-center';
                loadingDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Erreur lors du chargement de la page ${pageNum}</p>
                `;
            }
            
            // Continuer avec la page suivante même en cas d'erreur
            renderPagesSequentially(pdf, pageContainers, pageNum + 1);
        }
    }

        function updatePageInfo() {
            document.getElementById('pageInput').value = currentPage;
            document.getElementById('floatingPageInput').value = currentPage;
        }

        function setupFloatingNavigation(pdf) {
            const floatingNav = document.getElementById('floatingNav');
            const pdfContainer = document.getElementById('pdfContainer');
            const pdfHeader = pdfContainer.previousElementSibling;
            
            // Floating navigation controls
            const floatingPrevPage = document.getElementById('floatingPrevPage');
            const floatingNextPage = document.getElementById('floatingNextPage');
            const floatingPageInput = document.getElementById('floatingPageInput');
            const floatingFullscreen = document.getElementById('floatingFullscreen');
            const floatingPrevMatch = document.getElementById('floatingPrevMatch');
            const floatingNextMatch = document.getElementById('floatingNextMatch');
            
            // Sync floating controls with main controls
            floatingPrevPage.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    scrollToPage(currentPage);
                    updatePageInfo();
                }
            };

            floatingNextPage.onclick = () => {
                if (currentPage < pdf.numPages) {
                    currentPage++;
                    scrollToPage(currentPage);
                    updatePageInfo();
                }
            };

            floatingPageInput.addEventListener('change', () => {
                const pageNum = parseInt(floatingPageInput.value);
                if (pageNum >= 1 && pageNum <= pdf.numPages) {
                    currentPage = pageNum;
                    scrollToPage(currentPage);
                    updatePageInfo();
                } else {
                    floatingPageInput.value = currentPage;
                }
            });

            floatingPageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const pageNum = parseInt(floatingPageInput.value);
                    if (pageNum >= 1 && pageNum <= pdf.numPages) {
                        currentPage = pageNum;
                        scrollToPage(currentPage);
                        updatePageInfo();
                    } else {
                        floatingPageInput.value = currentPage;
                    }
                }
            });

            floatingFullscreen.onclick = () => {
                const container = document.getElementById('pdfContainer');
                if (container.requestFullscreen) {
                    container.requestFullscreen();
                } else if (container.webkitRequestFullscreen) {
                    container.webkitRequestFullscreen();
                } else if (container.msRequestFullscreen) {
                    container.msRequestFullscreen();
                }
            };

            // Search navigation in floating bar
            floatingPrevMatch.onclick = () => {
                if (currentMatch > 0) {
                    currentMatch--;
                    goToMatch(pdf);
                }
            };

            floatingNextMatch.onclick = () => {
                if (currentMatch < searchMatches.length - 1) {
                    currentMatch++;
                    goToMatch(pdf);
                }
            };

            // Scroll detection for showing/hiding floating nav
            let lastScrollY = 0;
            let ticking = false;
            let isFloatingVisible = false;
            let scrollTimeout = null;
            let userIsScrolling = false;
            let scrollStopTimeout = null;
            
            function updateFloatingNav() {
                const scrollY = window.pageYOffset;
                const headerRect = pdfHeader.getBoundingClientRect();
                const isHeaderVisible = headerRect.bottom > 80; // Add buffer zone
                const scrollDirection = scrollY > lastScrollY ? 'down' : 'up';
                const scrollDelta = Math.abs(scrollY - lastScrollY);
                
                // Mark that user is scrolling
                userIsScrolling = true;
                
                // Clear any existing timeouts
                if (scrollTimeout) {
                    clearTimeout(scrollTimeout);
                }
                if (scrollStopTimeout) {
                    clearTimeout(scrollStopTimeout);
                }
                
                // Set timeout to detect when scrolling stops
                scrollStopTimeout = setTimeout(() => {
                    userIsScrolling = false;
                }, 150);
                
                // Only show floating nav if:
                // 1. Header is completely out of view (with buffer)
                // 2. User has scrolled down significantly (at least 100px past header)
                // 3. We're in the PDF viewer area
                // 4. Scroll delta is significant (avoid micro-scrolls)
                const pdfContainerRect = pdfContainer.getBoundingClientRect();
                const isInPdfArea = pdfContainerRect.top < window.innerHeight && pdfContainerRect.bottom > 0;
                
                if (!isHeaderVisible && scrollY > 100 && isInPdfArea && scrollDirection === 'down' && scrollDelta > 5) {
                    // Show floating nav with a delay to avoid flickering
                    if (!isFloatingVisible) {
                        scrollTimeout = setTimeout(() => {
                            if (!userIsScrolling || scrollDirection === 'down') {
                                showFloatingNav();
                                isFloatingVisible = true;
                            }
                        }, 200);
                    }
                } else if (isHeaderVisible || (scrollDirection === 'up' && scrollDelta > 10) || !isInPdfArea) {
                    // Hide floating nav when:
                    // - Header becomes visible
                    // - Scrolling up with significant movement
                    // - Outside PDF area
                    if (isFloatingVisible) {
                        hideFloatingNav();
                        isFloatingVisible = false;
                    }
                }
                
                lastScrollY = scrollY;
                ticking = false;
            }
            
            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(updateFloatingNav);
                    ticking = true;
                }
            }
            
            // Use throttled scroll event for better performance
            let scrollTimer = null;
            window.addEventListener('scroll', function() {
                if (scrollTimer) {
                    clearTimeout(scrollTimer);
                }
                scrollTimer = setTimeout(requestTick, 16); // ~60fps
            }, { passive: true });
            
            function showFloatingNav() {
                if (!isFloatingVisible) {
                    floatingNav.classList.remove('hidden');
                    floatingNav.classList.add('show');
                    floatingNav.classList.remove('hide');
                    document.body.classList.add('floating-nav-visible');
                    console.log('Floating nav shown');
                }
            }
            
            function hideFloatingNav() {
                if (isFloatingVisible) {
                    floatingNav.classList.add('hide');
                    floatingNav.classList.remove('show');
                    document.body.classList.remove('floating-nav-visible');
                    console.log('Floating nav hidden');
                    
                    setTimeout(() => {
                        if (floatingNav.classList.contains('hide')) {
                            floatingNav.classList.add('hidden');
                        }
                    }, 400); // Match CSS transition duration
                }
            }
            
            // Update floating search info when search state changes
            window.updateFloatingSearchInfo = function() {
                const floatingSearchInfo = document.getElementById('floatingSearchInfo');
                const floatingMatchCount = document.getElementById('floatingMatchCount');
                
                if (searchMatches.length > 0) {
                    floatingSearchInfo.classList.remove('hidden');
                    floatingMatchCount.textContent = `${currentMatch + 1} / ${searchMatches.length}`;
                } else {
                    floatingSearchInfo.classList.add('hidden');
                }
            };
        }

        function updateProgress(percent, message) {
            const progressBar = document.getElementById('pdfProgressBar');
            const progressText = document.getElementById('pdfProgressText');
            
            if (progressBar) progressBar.style.width = percent + '%';
            if (progressText) progressText.textContent = message;
        }

        function showLoader() {
            loaderDiv.classList.remove('hidden');
            loaderDiv.classList.add('flex');
            viewerDiv.classList.add('hidden');
        }

        function showViewer() {
            loaderDiv.classList.add('hidden');
            viewerDiv.classList.remove('hidden');
        }

        function formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    }

    // Initialize viewer when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        const pdfUrl = getAbsolutePdfUrl();
        if (pdfUrl) {
            console.log('Initialisation du visualiseur PDF avec URL:', pdfUrl);
            autoLoadPdfViewer(pdfUrl);
        } else {
            console.error('URL du PDF non trouvée');
            const loaderDiv = document.getElementById('pdfLoader');
            if (loaderDiv) {
                loaderDiv.innerHTML = `
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-exclamation-triangle text-3xl text-red-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">URL du PDF non disponible</h3>
                        <p class="text-gray-600">Impossible de charger le document PDF.</p>
                    </div>
                `;
            }
        }

        // Setup additional features
        setupResponsiveFeatures();
        setupKeyboardShortcuts();
    });

    function setupResponsiveFeatures() {
        // Handle resize events
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Adjust layout for new screen size
                const floatingNav = document.getElementById('floatingNavigation');
                if (window.innerWidth >= 768) {
                    // On larger screens, ensure floating nav is hidden by default
                    if (floatingNav && !floatingNav.classList.contains('translate-x-full')) {
                        floatingNav.classList.add('translate-x-full');
                        const toggleBtn = document.getElementById('toggleFloatingNav');
                        if (toggleBtn) {
                            toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                        }
                    }
                }
            }, 250);
        });
    }

    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', function(e) {
            // Only activate shortcuts if not typing in input fields
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                return;
            }

            switch(e.key) {
                case 'ArrowLeft':
                case 'ArrowUp':
                    e.preventDefault();
                    document.getElementById('prevPage')?.click();
                    break;
                case 'ArrowRight':
                case 'ArrowDown':
                case ' ':
                    e.preventDefault();
                    document.getElementById('nextPage')?.click();
                    break;
                case '+':
                case '=':
                    e.preventDefault();
                    document.getElementById('zoomIn')?.click();
                    break;
                case '-':
                    e.preventDefault();
                    document.getElementById('zoomOut')?.click();
                    break;
                case 'f':
                    if (e.ctrlKey) {
                        e.preventDefault();
                        const searchInput = document.getElementById('searchText');
                        if (searchInput) {
                            searchInput.focus();
                        }
                    }
                    break;
                case 'Escape':
                    document.getElementById('clearSearch')?.click();
                    break;
            }
        });
    }

    function getAbsolutePdfUrl() {
        const pdfUrlElement = document.getElementById('pdfUrl');
        if (pdfUrlElement) {
            const url = pdfUrlElement.value;
            console.log('URL trouvée:', url);
            return url;
        }
        return null;
    }
</script>
@endsection
