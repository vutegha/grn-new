/**
 * PDF Lazy Loader with Advanced Performance Optimization
 * Optimized for high-resolution PDF rendering with lazy loading
 * 
 * Features:
 * - Intersection Observer API for efficient lazy loading
 * - Canvas pooling to reduce memory usage
 * - Progressive loading with priority queue
 * - Cache management for rendered pages
 * - Responsive scaling and quality adaptation
 */

class PDFLazyLoader {
    constructor(options = {}) {
        this.options = {
            rootMargin: '300px', // Load 300px before viewport
            threshold: 0.1,
            maxConcurrentRenders: 3,
            cacheSize: 10,
            qualityScale: 1.5,
            ...options
        };
        
        this.renderQueue = [];
        this.activeRenders = 0;
        this.pageCache = new Map();
        this.canvasPool = [];
        this.observer = null;
        this.pdf = null;
        
        this.init();
    }

    init() {
        // Initialize Intersection Observer
        this.observer = new IntersectionObserver(
            this.handleIntersection.bind(this),
            {
                rootMargin: this.options.rootMargin,
                threshold: this.options.threshold
            }
        );
    }

    /**
     * Set PDF document and start observing pages
     * @param {PDFDocumentProxy} pdf - PDF.js document proxy
     */
    setPDF(pdf) {
        this.pdf = pdf;
        this.observePages();
    }

    /**
     * Observe all page containers for lazy loading
     */
    observePages() {
        if (!this.pdf) return;

        const pageContainers = document.querySelectorAll('[id^="page-"]');
        pageContainers.forEach(container => {
            if (container.dataset.loadStatus === 'pending') {
                this.observer.observe(container);
            }
        });
    }

    /**
     * Handle intersection observer callback
     * @param {IntersectionObserverEntry[]} entries 
     */
    handleIntersection(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting && entry.target.dataset.loadStatus === 'pending') {
                const pageNumber = this.extractPageNumber(entry.target.id);
                if (pageNumber) {
                    this.queuePageRender(pageNumber, entry.target);
                }
            }
        });
    }

    /**
     * Extract page number from container ID
     * @param {string} containerId 
     * @returns {number|null}
     */
    extractPageNumber(containerId) {
        const match = containerId.match(/page-(\d+)/);
        return match ? parseInt(match[1]) : null;
    }

    /**
     * Queue a page for rendering with priority
     * @param {number} pageNumber 
     * @param {HTMLElement} container 
     * @param {number} priority - Lower number = higher priority
     */
    queuePageRender(pageNumber, container, priority = 1) {
        // Check if already in queue or rendered
        if (this.renderQueue.some(item => item.pageNumber === pageNumber) ||
            container.dataset.loadStatus !== 'pending') {
            return;
        }

        this.renderQueue.push({
            pageNumber,
            container,
            priority,
            timestamp: Date.now()
        });

        // Sort by priority, then by timestamp
        this.renderQueue.sort((a, b) => {
            if (a.priority !== b.priority) return a.priority - b.priority;
            return a.timestamp - b.timestamp;
        });

        this.processQueue();
    }

    /**
     * Process render queue with concurrency control
     */
    async processQueue() {
        while (this.renderQueue.length > 0 && this.activeRenders < this.options.maxConcurrentRenders) {
            const item = this.renderQueue.shift();
            this.activeRenders++;
            
            try {
                await this.renderSinglePage(item.pageNumber, item.container);
            } catch (error) {
                console.error(`Error rendering page ${item.pageNumber}:`, error);
            } finally {
                this.activeRenders--;
                // Continue processing queue
                setTimeout(() => this.processQueue(), 10);
            }
        }
    }

    /**
     * Render a single page with optimizations
     * @param {number} pageNumber 
     * @param {HTMLElement} container 
     */
    async renderSinglePage(pageNumber, container) {
        if (!this.pdf || !container || container.dataset.loadStatus !== 'pending') {
            return;
        }

        console.log(`🎨 Rendering page ${pageNumber} with lazy loading`);
        container.dataset.loadStatus = 'loading';

        try {
            // Check cache first
            let cachedData = this.pageCache.get(pageNumber);
            
            if (!cachedData) {
                const page = await this.pdf.getPage(pageNumber);
                cachedData = { page };
                
                // Cache management
                if (this.pageCache.size >= this.options.cacheSize) {
                    const oldestKey = this.pageCache.keys().next().value;
                    this.pageCache.delete(oldestKey);
                }
                this.pageCache.set(pageNumber, cachedData);
            }

            // Calculate optimal viewport and scale
            const viewport = cachedData.page.getViewport({ scale: 1 });
            const containerWidth = container.offsetWidth || 800;
            const scale = Math.min(
                (containerWidth - 40) / viewport.width,
                this.options.qualityScale
            );
            const scaledViewport = cachedData.page.getViewport({ scale });

            // Get or create canvas
            const canvas = this.getCanvas();
            const ctx = canvas.getContext('2d');
            
            // Configure canvas
            canvas.width = scaledViewport.width;
            canvas.height = scaledViewport.height;
            canvas.className = 'w-full h-auto shadow-lg rounded-lg';

            // High-quality rendering options
            const renderContext = {
                canvasContext: ctx,
                viewport: scaledViewport,
                enableWebGL: true,
                renderInteractiveForms: false
            };

            // Render page
            await cachedData.page.render(renderContext).promise;

            // Update container with rendered content
            this.updateContainer(container, canvas, pageNumber);
            
            console.log(`✅ Page ${pageNumber} rendered successfully`);

        } catch (error) {
            console.error(`❌ Error rendering page ${pageNumber}:`, error);
            this.showErrorState(container, pageNumber, error);
        }
    }

    /**
     * Get canvas from pool or create new one
     * @returns {HTMLCanvasElement}
     */
    getCanvas() {
        if (this.canvasPool.length > 0) {
            return this.canvasPool.pop();
        }
        return document.createElement('canvas');
    }

    /**
     * Return canvas to pool for reuse
     * @param {HTMLCanvasElement} canvas 
     */
    returnCanvas(canvas) {
        if (this.canvasPool.length < 5) { // Limit pool size
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            this.canvasPool.push(canvas);
        }
    }

    /**
     * Update container with rendered canvas
     * @param {HTMLElement} container 
     * @param {HTMLCanvasElement} canvas 
     * @param {number} pageNumber 
     */
    updateContainer(container, canvas, pageNumber) {
        // Create page wrapper
        const pageWrapper = document.createElement('div');
        pageWrapper.className = 'pdf-page-wrapper';
        pageWrapper.innerHTML = `
            <div class="pdf-page-header text-center mb-3">
                <span class="inline-block px-3 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full shadow-sm">
                    Page ${pageNumber}
                </span>
            </div>
        `;
        
        // Add canvas to wrapper
        pageWrapper.appendChild(canvas);
        
        // Replace container content
        container.innerHTML = '';
        container.appendChild(pageWrapper);
        container.dataset.loadStatus = 'loaded';
        
        // Add loading animation class removal
        container.classList.add('pdf-page-loaded');
        
        // Stop observing this container
        this.observer.unobserve(container);
    }

    /**
     * Show error state in container
     * @param {HTMLElement} container 
     * @param {number} pageNumber 
     * @param {Error} error 
     */
    showErrorState(container, pageNumber, error) {
        container.innerHTML = `
            <div class="flex items-center justify-center h-96 bg-red-50 rounded-lg border border-red-200">
                <div class="text-center p-6">
                    <svg class="mx-auto h-12 w-12 text-red-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <h3 class="text-sm font-medium text-red-800 mb-2">Erreur de chargement</h3>
                    <p class="text-sm text-red-600 mb-3">Page ${pageNumber}</p>
                    <button onclick="window.pdfLazyLoader.retryPage(${pageNumber})" 
                            class="inline-flex items-center px-3 py-2 text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 transition-colors duration-200">
                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Réessayer
                    </button>
                </div>
            </div>
        `;
        container.dataset.loadStatus = 'error';
    }

    /**
     * Retry rendering a failed page
     * @param {number} pageNumber 
     */
    retryPage(pageNumber) {
        const container = document.getElementById(`page-${pageNumber}`);
        if (container) {
            container.dataset.loadStatus = 'pending';
            this.queuePageRender(pageNumber, container, 0); // High priority
        }
    }

    /**
     * Preload pages around current visible page
     * @param {number} currentPage 
     * @param {number} range - Number of pages to preload around current
     */
    preloadAround(currentPage, range = 2) {
        const totalPages = this.pdf ? this.pdf.numPages : 0;
        
        for (let i = Math.max(1, currentPage - range); 
             i <= Math.min(totalPages, currentPage + range); 
             i++) {
            
            const container = document.getElementById(`page-${i}`);
            if (container && container.dataset.loadStatus === 'pending') {
                const priority = Math.abs(i - currentPage); // Closer pages have higher priority
                this.queuePageRender(i, container, priority);
            }
        }
    }

    /**
     * Clear all caches and reset state
     */
    clearCache() {
        this.pageCache.clear();
        this.renderQueue = [];
        this.activeRenders = 0;
        
        // Return all canvases to pool
        this.canvasPool.forEach(canvas => {
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });
    }

    /**
     * Destroy loader and clean up resources
     */
    destroy() {
        if (this.observer) {
            this.observer.disconnect();
        }
        this.clearCache();
        this.canvasPool = [];
        this.pdf = null;
    }
}

// Export for global use
window.PDFLazyLoader = PDFLazyLoader;
