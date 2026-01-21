/**
 * Générateur de miniatures PDF utilisant PDF.js
 * Extrait et affiche la première page d'un PDF comme miniature
 */
class PdfThumbnailGenerator {
    constructor() {
        this.loadedPdfs = new Map();
        this.initializePdfJs();
    }

    /**
     * Initialise PDF.js avec la configuration appropriée
     */
    initializePdfJs() {
        if (typeof pdfjsLib !== 'undefined') {
            // Configuration PDF.js
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            console.log('✅ PDF.js initialisé');
        } else {
            console.error('❌ PDF.js non trouvé. Assurez-vous que la bibliothèque est chargée.');
        }
    }

    /**
     * Génère une miniature pour un PDF donné
     * @param {string} pdfUrl - URL du fichier PDF
     * @param {HTMLElement} targetElement - Élément où afficher la miniature
     * @param {Object} options - Options de configuration
     */
    async generateThumbnail(pdfUrl, targetElement, options = {}) {
        const config = {
            width: options.width || 300,
            height: options.height || 400,
            scale: options.scale || 3.0, // Augmentation significative de la résolution
            backgroundColor: options.backgroundColor || '#ffffff',
            showPlaceholder: options.showPlaceholder !== false,
            quality: options.quality || 0.95, // Qualité JPEG élevée
            ...options
        };

        try {
            // Afficher un placeholder pendant le chargement
            if (config.showPlaceholder) {
                this.showLoadingPlaceholder(targetElement, config);
            }

            // Charger le PDF
            const pdf = await this.loadPdf(pdfUrl);
            
            // Extraire la première page
            const page = await pdf.getPage(1);
            
            // Générer la miniature haute résolution
            const canvas = await this.renderPageToCanvas(page, config);
            
            // Remplacer le placeholder par la miniature
            this.displayThumbnail(targetElement, canvas, config);
            
            console.log(`✅ Miniature haute résolution générée pour: ${pdfUrl}`);
            
        } catch (error) {
            console.error(`❌ Erreur génération miniature pour ${pdfUrl}:`, error);
            this.showErrorPlaceholder(targetElement, config);
        }
    }

    /**
     * Charge un PDF avec mise en cache
     * @param {string} pdfUrl 
     * @returns {Promise<PDFDocumentProxy>}
     */
    async loadPdf(pdfUrl) {
        if (this.loadedPdfs.has(pdfUrl)) {
            return this.loadedPdfs.get(pdfUrl);
        }

        const loadingTask = pdfjsLib.getDocument({
            url: pdfUrl,
            cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
            cMapPacked: true
        });

        const pdf = await loadingTask.promise;
        this.loadedPdfs.set(pdfUrl, pdf);
        return pdf;
    }

    /**
     * Rend une page PDF sur un canvas haute résolution
     * @param {PDFPageProxy} page 
     * @param {Object} config 
     * @returns {Promise<HTMLCanvasElement>}
     */
    async renderPageToCanvas(page, config) {
        // Obtenir les dimensions originales de la page
        const originalViewport = page.getViewport({ scale: 1.0 });
        
        // Calculer le facteur d'échelle pour atteindre la taille désirée avec haute résolution
        const targetScale = Math.min(
            (config.width * config.scale) / originalViewport.width,
            (config.height * config.scale) / originalViewport.height
        );
        
        // Créer le viewport haute résolution
        const viewport = page.getViewport({ scale: targetScale });
        
        // Créer le canvas avec les dimensions haute résolution
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        
        // Définir les dimensions du canvas (haute résolution)
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        
        // Améliorer la qualité du rendu
        context.imageSmoothingEnabled = true;
        context.imageSmoothingQuality = 'high';
        
        // Remplir le fond blanc pour éviter la transparence
        context.fillStyle = config.backgroundColor;
        context.fillRect(0, 0, canvas.width, canvas.height);
        
        // Configuration du rendu avec optimisations
        const renderContext = {
            canvasContext: context,
            viewport: viewport,
            enableWebGL: true, // Activer WebGL si disponible
            renderInteractiveForms: false, // Désactiver les formulaires pour de meilleures performances
        };
        
        // Rendre la page PDF
        await page.render(renderContext).promise;
        
        console.log(`📐 Canvas généré: ${canvas.width}x${canvas.height}px (échelle: ${targetScale.toFixed(2)})`);
        
        return canvas;
    }

    /**
     * Affiche la miniature dans l'élément cible
     * @param {HTMLElement} targetElement 
     * @param {HTMLCanvasElement} canvas 
     * @param {Object} config 
     */
    displayThumbnail(targetElement, canvas, config) {
        // Nettoyer le contenu existant
        targetElement.innerHTML = '';
        
        // Créer l'image à partir du canvas haute résolution
        const img = document.createElement('img');
        img.src = canvas.toDataURL('image/png', config.quality); // Utiliser PNG pour meilleure qualité
        img.alt = 'Première page du PDF';
        img.className = 'pdf-thumbnail w-full h-full object-cover rounded-lg transition-transform duration-200';
        
        // Définir les dimensions d'affichage (pas les dimensions natives)
        img.style.width = config.width + 'px';
        img.style.height = config.height + 'px';
        img.style.objectFit = 'cover';
        img.style.objectPosition = 'top center';
        
        // Optimisations pour un rendu net
        img.style.imageRendering = 'crisp-edges';
        img.style.imageRendering = '-webkit-optimize-contrast';
        
        // Ajouter l'image à l'élément
        targetElement.appendChild(img);
        
        // Ajouter une classe pour indiquer que la miniature est chargée
        targetElement.classList.add('pdf-thumbnail-loaded');
        targetElement.classList.remove('pdf-thumbnail-loading', 'pdf-thumbnail-error');
        
        console.log(`🖼️  Image affichée: ${config.width}x${config.height}px depuis canvas ${canvas.width}x${canvas.height}px`);
    }
    }

    /**
     * Affiche un placeholder de chargement
     * @param {HTMLElement} targetElement 
     * @param {Object} config 
     */
    showLoadingPlaceholder(targetElement, config) {
        targetElement.innerHTML = `
            <div class="pdf-thumbnail-placeholder bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center" 
                 style="width: ${config.width}px; height: ${config.height}px;">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
                <div class="text-gray-500 text-sm">Chargement PDF...</div>
            </div>
        `;
        targetElement.classList.add('pdf-thumbnail-loading');
    }

    /**
     * Affiche un placeholder d'erreur
     * @param {HTMLElement} targetElement 
     * @param {Object} config 
     */
    showErrorPlaceholder(targetElement, config) {
        targetElement.innerHTML = `
            <div class="pdf-thumbnail-placeholder bg-red-50 border-2 border-dashed border-red-300 rounded-lg flex flex-col items-center justify-center" 
                 style="width: ${config.width}px; height: ${config.height}px;">
                <svg class="w-12 h-12 text-red-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <div class="text-red-500 text-sm text-center">Erreur<br>chargement PDF</div>
            </div>
        `;
        targetElement.classList.add('pdf-thumbnail-error');
        targetElement.classList.remove('pdf-thumbnail-loading');
    }

    /**
     * Génère les miniatures pour tous les éléments avec l'attribut data-pdf-thumbnail
     */
    generateAllThumbnails() {
        const elements = document.querySelectorAll('[data-pdf-thumbnail]');
        console.log(`🔄 Génération de ${elements.length} miniature(s) PDF...`);
        
        if (elements.length === 0) {
            console.log('ℹ️  Aucun élément avec data-pdf-thumbnail trouvé');
            return;
        }
        
        elements.forEach((element, index) => {
            const pdfUrl = element.getAttribute('data-pdf-thumbnail');
            const width = parseInt(element.getAttribute('data-width')) || 300;
            const height = parseInt(element.getAttribute('data-height')) || 400;
            
            console.log(`📄 PDF ${index + 1}/${elements.length}: ${pdfUrl} (${width}x${height})`);
            
            // Délai progressif pour éviter de surcharger le navigateur
            setTimeout(() => {
                this.generateThumbnail(pdfUrl, element, { width, height });
            }, index * 200); // Augmenté le délai pour éviter les conflits
        });
    }

    /**
     * Nettoie la mémoire en libérant les PDFs chargés
     */
    cleanup() {
        this.loadedPdfs.clear();
        console.log('🧹 Cache PDF nettoyé');
    }
}

// Instance globale
let pdfThumbnailGenerator = null;

// Initialisation automatique quand PDF.js est disponible
document.addEventListener('DOMContentLoaded', function() {
    // Attendre que PDF.js soit chargé
    const checkPdfJs = () => {
        if (typeof pdfjsLib !== 'undefined') {
            pdfThumbnailGenerator = new PdfThumbnailGenerator();
            
            // Rendre accessible globalement
            window.pdfThumbnailGenerator = pdfThumbnailGenerator;
            
            // Générer automatiquement les miniatures existantes
            pdfThumbnailGenerator.generateAllThumbnails();
            
            console.log('🎯 Générateur de miniatures PDF prêt');
        } else {
            // Réessayer dans 100ms
            setTimeout(checkPdfJs, 100);
        }
    };
    
    checkPdfJs();
});

// Fonction utilitaire globale
window.generatePdfThumbnail = function(pdfUrl, targetElement, options = {}) {
    if (pdfThumbnailGenerator) {
        return pdfThumbnailGenerator.generateThumbnail(pdfUrl, targetElement, options);
    } else {
        console.error('❌ Générateur de miniatures PDF non initialisé');
    }
};

// Nettoyage automatique avant le déchargement de la page
window.addEventListener('beforeunload', function() {
    if (pdfThumbnailGenerator) {
        pdfThumbnailGenerator.cleanup();
    }
});
