/**
 * Générateur de miniatures PDF utilisant PDF.js
 * Version haute résolution pour miniatures lisibles
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
            console.log('✅ PDF.js initialisé en haute résolution');
        } else {
            console.error('❌ PDF.js non trouvé. Assurez-vous que la bibliothèque est chargée.');
        }
    }

    /**
     * Génère une miniature haute résolution pour un PDF donné
     * @param {string} pdfUrl - URL du fichier PDF
     * @param {HTMLElement} targetElement - Élément où afficher la miniature
     * @param {Object} options - Options de configuration
     */
    async generateThumbnail(pdfUrl, targetElement, options = {}) {
        const config = {
            width: options.width || 300,
            height: options.height || 400,
            scale: options.scale || 4.0, // Très haute résolution
            backgroundColor: options.backgroundColor || '#ffffff',
            showPlaceholder: options.showPlaceholder !== false,
            quality: options.quality || 0.95,
            ...options
        };

        try {
            // Afficher un placeholder pendant le chargement
            if (config.showPlaceholder) {
                this.showLoadingPlaceholder(targetElement, config);
            }

            // Vérifier d'abord si le fichier existe
            const fileCheck = await this.checkFileExists(pdfUrl);
            if (!fileCheck.exists) {
                console.warn(`⚠️ Fichier PDF inaccessible: ${pdfUrl} (${fileCheck.status})`);
                this.showErrorPlaceholder(targetElement, config, fileCheck.status, fileCheck.statusText);
                return;
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
            const status = error.status || 0;
            const statusText = error.message || 'Erreur inconnue';
            console.error(`❌ Erreur génération miniature pour ${pdfUrl}:`, error.name || error.constructor.name);
            this.showErrorPlaceholder(targetElement, config, status, statusText);
        }
    }

    /**
     * Vérifie si un fichier PDF existe et est accessible
     * @param {string} pdfUrl 
     * @returns {Promise<{exists: boolean, status: number, statusText: string}>}
     */
    async checkFileExists(pdfUrl) {
        try {
            const response = await fetch(pdfUrl, { method: 'HEAD' });
            return {
                exists: response.ok,
                status: response.status,
                statusText: response.statusText
            };
        } catch (error) {
            return {
                exists: false,
                status: 0,
                statusText: 'Network error'
            };
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
            cMapPacked: true,
            useSystemFonts: true, // Utiliser les polices système pour une meilleure qualité
            disableFontFace: false, // Permettre les polices personnalisées
            disableStream: false,
            disableAutoFetch: false
        });

        const pdf = await loadingTask.promise;
        this.loadedPdfs.set(pdfUrl, pdf);
        return pdf;
    }

    /**
     * Rend une page PDF sur un canvas ultra haute résolution
     * @param {PDFPageProxy} page 
     * @param {Object} config 
     * @returns {Promise<HTMLCanvasElement>}
     */
    async renderPageToCanvas(page, config) {
        // Obtenir les dimensions originales de la page
        const originalViewport = page.getViewport({ scale: 1.0 });
        
        // Calculer le facteur d'échelle pour la taille désirée avec très haute résolution
        const targetScale = Math.min(
            (config.width * config.scale) / originalViewport.width,
            (config.height * config.scale) / originalViewport.height
        );
        
        // Créer le viewport haute résolution
        const viewport = page.getViewport({ scale: targetScale });
        
        // Créer le canvas avec les dimensions haute résolution
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        
        // Définir les dimensions du canvas (ultra haute résolution)
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        
        // Optimisations du contexte pour la qualité maximale
        context.imageSmoothingEnabled = true;
        context.imageSmoothingQuality = 'high';
        context.textRenderingOptimization = 'optimizeQuality';
        
        // Remplir le fond blanc pour éviter la transparence
        context.fillStyle = config.backgroundColor;
        context.fillRect(0, 0, canvas.width, canvas.height);
        
        // Configuration du rendu avec toutes les optimisations
        const renderContext = {
            canvasContext: context,
            viewport: viewport,
            enableWebGL: true,
            renderInteractiveForms: false,
            intent: 'display' // Optimiser pour l'affichage
        };
        
        // Rendre la page PDF
        await page.render(renderContext).promise;
        
        console.log(`📐 Canvas ultra HD généré: ${canvas.width}x${canvas.height}px (échelle: ${targetScale.toFixed(2)})`);
        
        return canvas;
    }

    /**
     * Affiche la miniature haute résolution dans l'élément cible
     * @param {HTMLElement} targetElement 
     * @param {HTMLCanvasElement} canvas 
     * @param {Object} config 
     */
    displayThumbnail(targetElement, canvas, config) {
        // Nettoyer le contenu existant
        targetElement.innerHTML = '';
        
        // Créer l'image à partir du canvas haute résolution
        const img = document.createElement('img');
        
        // Utiliser PNG pour la qualité maximale (pas de compression JPEG)
        img.src = canvas.toDataURL('image/png');
        img.alt = 'Première page du PDF';
        img.className = 'pdf-thumbnail w-full h-full object-cover rounded-lg transition-transform duration-200';
        
        // Définir les dimensions d'affichage
        img.style.width = config.width + 'px';
        img.style.height = config.height + 'px';
        img.style.objectFit = 'cover';
        img.style.objectPosition = 'top center';
        
        // Optimisations CSS pour un rendu ultra net
        img.style.imageRendering = 'crisp-edges';
        img.style.imageRendering = '-webkit-optimize-contrast';
        img.style.imageRendering = 'pixelated'; // Pour les navigateurs qui le supportent
        img.style.filter = 'contrast(1.1) brightness(1.05)'; // Légère amélioration du contraste
        
        // Ajouter l'image à l'élément
        targetElement.appendChild(img);
        
        // Ajouter une classe pour indiquer que la miniature est chargée
        targetElement.classList.add('pdf-thumbnail-loaded');
        targetElement.classList.remove('pdf-thumbnail-loading', 'pdf-thumbnail-error');
        
        console.log(`🖼️  Miniature HD affichée: ${config.width}x${config.height}px depuis canvas ${canvas.width}x${canvas.height}px`);
    }

    /**
     * Affiche un placeholder de chargement
     * @param {HTMLElement} targetElement 
     * @param {Object} config 
     */
    showLoadingPlaceholder(targetElement, config) {
        targetElement.innerHTML = `
            <div class="pdf-thumbnail-placeholder bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-dashed border-blue-300 rounded-lg flex flex-col items-center justify-center" 
                 style="width: ${config.width}px; height: ${config.height}px;">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
                <div class="text-blue-600 text-sm font-medium">Génération HD...</div>
                <div class="text-blue-500 text-xs mt-1">Résolution optimisée</div>
            </div>
        `;
        targetElement.classList.add('pdf-thumbnail-loading');
    }

    /**
     * Affiche un placeholder d'erreur avec message spécifique
     * @param {HTMLElement} targetElement 
     * @param {Object} config 
     * @param {number} status - Code HTTP de l'erreur
     * @param {string} statusText - Texte de l'erreur
     */
    showErrorPlaceholder(targetElement, config, status = 0, statusText = '') {
        // Déterminer le message selon le code d'erreur
        let errorIcon, errorTitle, errorMessage, bgColor, borderColor, textColor;
        
        switch(status) {
            case 403:
                errorIcon = `<svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>`;
                errorTitle = 'Fichier non trouvé';
                errorMessage = 'PDF manquant';
                bgColor = 'bg-orange-50';
                borderColor = 'border-orange-300';
                textColor = 'text-orange-600';
                break;
                
            case 404:
                errorIcon = `<svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M6 18L18 6M6 6l12 12" stroke-dasharray="2,2"></path>
                </svg>`;
                errorTitle = 'Fichier introuvable';
                errorMessage = 'Le PDF n\'existe pas';
                bgColor = 'bg-gray-50';
                borderColor = 'border-gray-300';
                textColor = 'text-gray-500';
                break;
                
            case 0:
                errorIcon = `<svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3"></path>
                </svg>`;
                errorTitle = 'Hors ligne';
                errorMessage = 'Connexion impossible';
                bgColor = 'bg-yellow-50';
                borderColor = 'border-yellow-300';
                textColor = 'text-yellow-600';
                break;
                
            default:
                errorIcon = `<svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>`;
                errorTitle = 'Erreur chargement';
                errorMessage = 'PDF non accessible';
                bgColor = 'bg-red-50';
                borderColor = 'border-red-300';
                textColor = 'text-red-500';
        }
        
        targetElement.innerHTML = `
            <div class="pdf-thumbnail-placeholder ${bgColor} border-2 border-dashed ${borderColor} rounded-lg flex flex-col items-center justify-center p-3" 
                 style="width: ${config.width}px; height: ${config.height}px;">
                <div class="${textColor}">${errorIcon}</div>
                <div class="${textColor} text-sm text-center font-medium">${errorTitle}</div>
                <div class="${textColor} opacity-75 text-xs text-center mt-1">${errorMessage}</div>
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
        console.log(`🔄 Génération de ${elements.length} miniature(s) PDF haute résolution...`);
        
        if (elements.length === 0) {
            console.log('ℹ️  Aucun élément avec data-pdf-thumbnail trouvé');
            return;
        }
        
        elements.forEach((element, index) => {
            const pdfUrl = element.getAttribute('data-pdf-thumbnail');
            const width = parseInt(element.getAttribute('data-width')) || 300;
            const height = parseInt(element.getAttribute('data-height')) || 400;
            
            console.log(`📄 PDF HD ${index + 1}/${elements.length}: ${pdfUrl} (${width}x${height})`);
            
            // Délai progressif pour éviter de surcharger le navigateur
            setTimeout(() => {
                this.generateThumbnail(pdfUrl, element, { width, height });
            }, index * 150);
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
            
            console.log('🎯 Générateur de miniatures PDF HD prêt');
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
