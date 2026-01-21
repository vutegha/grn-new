{{-- PDF Viewer Component Optimized --}}
{{-- 
    Usage: @include('components.pdf-viewer', ['publication' => $publication])
--}}

<div class="pdf-container" id="pdfContainer">
    <!-- PDF Viewer Header -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <h1 class="text-lg font-semibold text-gray-900">{{ $publication->titre }}</h1>
                    <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-500">
                        <span id="currentPageDisplay">Page 1</span>
                        <span>sur</span>
                        <span id="totalPagesDisplay">--</span>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <!-- Download Button -->
                    <a href="{{ Storage::url($publication->fichier_pdf) }}" 
                       download="{{ Str::slug($publication->titre) }}.pdf"
                       class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m-4-4V6a2 2 0 112 2v6"></path>
                        </svg>
                        Télécharger
                    </a>
                    
                    <!-- Close Button -->
                    <button onclick="window.history.back()" 
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- PDF Content Area -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Loading State -->
        <div id="loadingIndicator" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mb-4"></div>
            <p class="text-gray-600">Chargement du document...</p>
        </div>

        <!-- Error State -->
        <div id="errorContainer" class="hidden text-center py-12">
            <div class="mx-auto h-12 w-12 text-red-400 mb-4">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Erreur de chargement</h3>
            <p class="text-gray-500 mb-4">Impossible de charger le document PDF.</p>
            <button onclick="window.location.reload()" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Réessayer
            </button>
        </div>

        <!-- PDF Pages Container -->
        <div id="pdfPagesContainer" class="hidden space-y-6">
            {{-- Pages will be dynamically generated here --}}
        </div>
    </div>

    <!-- Floating Vertical Navigation -->
    <div id="verticalFloatingNav" class="fixed top-1/2 right-4 transform -translate-y-1/2 z-50 bg-white rounded-lg shadow-lg border border-gray-200 p-3 hidden">
        <!-- Navigation Controls -->
        <div class="space-y-3">
            <!-- Page Navigation -->
            <div class="nav-section">
                <div class="flex items-center justify-between mb-2">
                    <button id="prevPageBtn" class="px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded">
                        ←
                    </button>
                    <input type="number" id="pageInput" class="w-12 px-1 py-1 text-xs text-center border rounded" min="1" value="1">
                    <button id="nextPageBtn" class="px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded">
                        →
                    </button>
                </div>
                <div class="text-center text-xs text-gray-500" id="pageInfo">Page 1 / --</div>
            </div>

            <!-- Zoom Controls -->
            <div class="nav-section">
                <div class="space-y-1">
                    <button id="zoomInBtn" class="w-full px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded">
                        Zoom +
                    </button>
                    <button id="zoomOutBtn" class="w-full px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded">
                        Zoom -
                    </button>
                    <button id="zoomResetBtn" class="w-full px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded">
                        100%
                    </button>
                </div>
            </div>

            <!-- Search -->
            <div class="nav-section">
                <input type="text" id="searchInput" placeholder="Rechercher..." class="w-full px-2 py-1 text-xs border rounded mb-1">
                <button id="searchBtn" class="w-full px-2 py-1 text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 rounded">
                    Rechercher
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// PDF Viewer Initialization with Lazy Loading
document.addEventListener('DOMContentLoaded', function() {
    const pdfUrl = '{{ Storage::url($publication->fichier_pdf) }}';
    let currentPdf = null;
    let currentPage = 1;
    let totalPages = 0;
    let currentScale = 1.5;
    
    // Initialize PDF Lazy Loader
    window.pdfLazyLoader = new PDFLazyLoader({
        rootMargin: '300px',
        threshold: 0.1,
        maxConcurrentRenders: 3,
        cacheSize: 10,
        qualityScale: currentScale
    });

    // Load PDF
    loadPDF();

    async function loadPDF() {
        try {
            console.log('📄 Loading PDF:', pdfUrl);
            
            const loadingTask = pdfjsLib.getDocument(pdfUrl);
            currentPdf = await loadingTask.promise;
            totalPages = currentPdf.numPages;
            
            console.log(`✅ PDF loaded successfully: ${totalPages} pages`);
            
            // Update UI
            document.getElementById('totalPagesDisplay').textContent = totalPages;
            document.getElementById('pageInfo').textContent = `Page 1 / ${totalPages}`;
            document.getElementById('pageInput').max = totalPages;
            
            // Set up lazy loader
            window.pdfLazyLoader.setPDF(currentPdf);
            
            // Generate page containers
            generatePageContainers();
            
            // Show PDF container and hide loading
            document.getElementById('loadingIndicator').classList.add('hidden');
            document.getElementById('pdfPagesContainer').classList.remove('hidden');
            document.getElementById('verticalFloatingNav').classList.remove('hidden');
            
            // Set up navigation
            setupNavigation();
            
        } catch (error) {
            console.error('❌ Error loading PDF:', error);
            showError();
        }
    }

    function generatePageContainers() {
        const container = document.getElementById('pdfPagesContainer');
        container.innerHTML = '';
        
        for (let pageNum = 1; pageNum <= totalPages; pageNum++) {
            const pageContainer = document.createElement('div');
            pageContainer.id = `page-${pageNum}`;
            pageContainer.className = 'pdf-page-container border border-gray-200 rounded-lg bg-white shadow-sm';
            pageContainer.dataset.loadStatus = 'pending';
            pageContainer.innerHTML = `
                <div class="pdf-loading-placeholder h-96 flex items-center justify-center">
                    <div class="text-center">
                        <div class="animate-pulse bg-gray-200 h-64 w-full mb-4 rounded"></div>
                        <span class="text-sm text-gray-500">Page ${pageNum}</span>
                    </div>
                </div>
            `;
            container.appendChild(pageContainer);
        }
    }

    function setupNavigation() {
        // Previous page
        document.getElementById('prevPageBtn').addEventListener('click', () => {
            if (currentPage > 1) {
                navigateToPage(currentPage - 1);
            }
        });

        // Next page  
        document.getElementById('nextPageBtn').addEventListener('click', () => {
            if (currentPage < totalPages) {
                navigateToPage(currentPage + 1);
            }
        });

        // Page input
        document.getElementById('pageInput').addEventListener('change', (e) => {
            const pageNum = parseInt(e.target.value);
            if (pageNum >= 1 && pageNum <= totalPages) {
                navigateToPage(pageNum);
            }
        });

        // Zoom controls
        document.getElementById('zoomInBtn').addEventListener('click', () => {
            currentScale = Math.min(currentScale * 1.2, 3);
            updateZoom();
        });

        document.getElementById('zoomOutBtn').addEventListener('click', () => {
            currentScale = Math.max(currentScale / 1.2, 0.5);
            updateZoom();
        });

        document.getElementById('zoomResetBtn').addEventListener('click', () => {
            currentScale = 1.5;
            updateZoom();
        });

        // Search functionality
        document.getElementById('searchBtn').addEventListener('click', performSearch);
        document.getElementById('searchInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }

    function navigateToPage(pageNum) {
        currentPage = pageNum;
        document.getElementById('currentPageDisplay').textContent = `Page ${pageNum}`;
        document.getElementById('pageInfo').textContent = `Page ${pageNum} / ${totalPages}`;
        document.getElementById('pageInput').value = pageNum;
        
        // Scroll to page
        const pageElement = document.getElementById(`page-${pageNum}`);
        if (pageElement) {
            pageElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Preload surrounding pages
            window.pdfLazyLoader.preloadAround(pageNum, 2);
        }
    }

    function updateZoom() {
        // Update lazy loader scale
        window.pdfLazyLoader.options.qualityScale = currentScale;
        // Clear cache to force re-render with new scale
        window.pdfLazyLoader.clearCache();
        // Re-generate containers
        generatePageContainers();
        window.pdfLazyLoader.observePages();
    }

    function performSearch() {
        const query = document.getElementById('searchInput').value.trim();
        if (!query) return;
        
        console.log('🔍 Searching for:', query);
        // Implement search functionality here
        // This would require PDF.js text extraction capabilities
    }

    function showError() {
        document.getElementById('loadingIndicator').classList.add('hidden');
        document.getElementById('errorContainer').classList.remove('hidden');
    }

    // Intersection Observer for current page tracking
    const pageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && entry.intersectionRatio > 0.5) {
                const pageNum = parseInt(entry.target.id.replace('page-', ''));
                if (pageNum !== currentPage) {
                    currentPage = pageNum;
                    document.getElementById('currentPageDisplay').textContent = `Page ${pageNum}`;
                    document.getElementById('pageInfo').textContent = `Page ${pageNum} / ${totalPages}`;
                    document.getElementById('pageInput').value = pageNum;
                }
            }
        });
    }, { threshold: 0.5 });

    // Observe page containers for current page tracking
    setTimeout(() => {
        const pageContainers = document.querySelectorAll('[id^="page-"]');
        pageContainers.forEach(container => {
            pageObserver.observe(container);
        });
    }, 1000);
});
</script>
