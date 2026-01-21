/**
 * Utilitaire de compression d'images
 * Compresse automatiquement les images avant upload
 */

class ImageCompressor {
    constructor(options = {}) {
        this.options = {
            maxWidth: options.maxWidth || 1920,
            maxHeight: options.maxHeight || 1080,
            quality: options.quality || 0.8,
            maxSizeKB: options.maxSizeKB || 500, // Taille max en KB après compression
            format: options.format || 'image/jpeg', // Format de sortie
            ...options
        };
    }

    /**
     * Compresse un fichier image
     * @param {File} file - Le fichier image à compresser
     * @returns {Promise<File>} - Le fichier compressé
     */
    async compressImage(file) {
        return new Promise((resolve, reject) => {
            // Vérifier si c'est une image
            if (!file.type.startsWith('image/')) {
                resolve(file); // Retourner le fichier original si ce n'est pas une image
                return;
            }

            // Pour les SVG, ne pas compresser
            if (file.type === 'image/svg+xml') {
                resolve(file);
                return;
            }

            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();

            img.onload = () => {
                try {
                    // Calculer les nouvelles dimensions
                    const { width, height } = this.calculateDimensions(img.width, img.height);
                    
                    // Configuration du canvas
                    canvas.width = width;
                    canvas.height = height;

                    // Dessiner l'image redimensionnée
                    ctx.drawImage(img, 0, 0, width, height);

                    // Déterminer le format de sortie
                    let outputFormat = this.options.format;
                    if (file.type === 'image/png' && this.hasTransparency(canvas, ctx)) {
                        outputFormat = 'image/png'; // Conserver PNG pour la transparence
                    }

                    // Commencer avec la qualité par défaut
                    let quality = this.options.quality;
                    
                    this.compressWithQuality(canvas, outputFormat, quality, file.name, file.lastModified)
                        .then(resolve)
                        .catch(reject);

                } catch (error) {
                    console.error('Erreur lors de la compression:', error);
                    resolve(file); // Retourner le fichier original en cas d'erreur
                }
            };

            img.onerror = () => {
                console.error('Erreur lors du chargement de l\'image');
                resolve(file); // Retourner le fichier original en cas d'erreur
            };

            img.src = URL.createObjectURL(file);
        });
    }

    /**
     * Compresse avec ajustement dynamique de la qualité
     */
    async compressWithQuality(canvas, format, quality, fileName, lastModified) {
        return new Promise((resolve) => {
            const tryCompress = (currentQuality) => {
                canvas.toBlob((blob) => {
                    if (!blob) {
                        // En cas d'échec, retourner une version avec qualité réduite
                        if (currentQuality > 0.1) {
                            tryCompress(currentQuality - 0.1);
                        } else {
                            // Dernière tentative avec qualité minimale
                            canvas.toBlob((finalBlob) => {
                                const compressedFile = new File([finalBlob || blob], fileName, {
                                    type: format,
                                    lastModified: lastModified
                                });
                                resolve(compressedFile);
                            }, format, 0.1);
                        }
                        return;
                    }

                    const fileSizeKB = blob.size / 1024;
                    
                    // Si la taille est acceptable ou si on a atteint la qualité minimale
                    if (fileSizeKB <= this.options.maxSizeKB || currentQuality <= 0.1) {
                        const compressedFile = new File([blob], fileName, {
                            type: format,
                            lastModified: lastModified
                        });
                        resolve(compressedFile);
                    } else {
                        // Réduire la qualité et réessayer
                        const newQuality = Math.max(0.1, currentQuality - 0.1);
                        tryCompress(newQuality);
                    }
                }, format, currentQuality);
            };

            tryCompress(quality);
        });
    }

    /**
     * Calcule les nouvelles dimensions en respectant le ratio
     */
    calculateDimensions(originalWidth, originalHeight) {
        let { maxWidth, maxHeight } = this.options;
        
        // Si l'image est déjà plus petite, conserver les dimensions originales
        if (originalWidth <= maxWidth && originalHeight <= maxHeight) {
            return { width: originalWidth, height: originalHeight };
        }

        const ratio = Math.min(maxWidth / originalWidth, maxHeight / originalHeight);
        
        return {
            width: Math.round(originalWidth * ratio),
            height: Math.round(originalHeight * ratio)
        };
    }

    /**
     * Vérifie si l'image a de la transparence
     */
    hasTransparency(canvas, ctx) {
        try {
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;
            
            // Vérifier les valeurs alpha
            for (let i = 3; i < data.length; i += 4) {
                if (data[i] < 255) {
                    return true; // Transparence détectée
                }
            }
            return false;
        } catch (error) {
            console.warn('Impossible de détecter la transparence:', error);
            return false;
        }
    }

    /**
     * Compresse plusieurs images
     */
    async compressImages(files) {
        const compressionPromises = Array.from(files).map(file => this.compressImage(file));
        return Promise.all(compressionPromises);
    }

    /**
     * Obtient des informations sur la compression
     */
    getCompressionInfo(originalFile, compressedFile) {
        const originalSizeKB = originalFile.size / 1024;
        const compressedSizeKB = compressedFile.size / 1024;
        const compressionRatio = ((originalSizeKB - compressedSizeKB) / originalSizeKB * 100);
        
        return {
            originalSize: originalSizeKB,
            compressedSize: compressedSizeKB,
            compressionRatio: compressionRatio,
            spaceSaved: originalSizeKB - compressedSizeKB
        };
    }
}

// Configuration par défaut pour l'application
const defaultCompressor = new ImageCompressor({
    maxWidth: 1920,
    maxHeight: 1080,
    quality: 0.85,
    maxSizeKB: 800,
    format: 'image/jpeg'
});

// Configuration pour les miniatures
const thumbnailCompressor = new ImageCompressor({
    maxWidth: 400,
    maxHeight: 300,
    quality: 0.8,
    maxSizeKB: 150,
    format: 'image/jpeg'
});

// Utilitaires globaux
window.ImageCompressor = ImageCompressor;
window.defaultCompressor = defaultCompressor;
window.thumbnailCompressor = thumbnailCompressor;

// Fonction utilitaire pour formater la taille des fichiers
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Fonction pour afficher les informations de compression
function showCompressionInfo(originalFile, compressedFile, container) {
    if (!container) return;
    
    const info = defaultCompressor.getCompressionInfo(originalFile, compressedFile);
    
    const infoElement = document.createElement('div');
    infoElement.className = 'mt-2 p-3 bg-green-50 border border-green-200 rounded-lg text-sm';
    infoElement.innerHTML = `
        <div class="flex items-center text-green-800">
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">Image compressée</span>
        </div>
        <div class="mt-1 text-green-700">
            <div class="flex justify-between">
                <span>Taille originale:</span>
                <span>${formatFileSize(originalFile.size)}</span>
            </div>
            <div class="flex justify-between">
                <span>Taille compressée:</span>
                <span>${formatFileSize(compressedFile.size)}</span>
            </div>
            <div class="flex justify-between font-medium">
                <span>Économie:</span>
                <span>${info.compressionRatio.toFixed(1)}% (${formatFileSize(info.spaceSaved * 1024)})</span>
            </div>
        </div>
    `;
    
    // Supprimer l'ancienne info si elle existe
    const existingInfo = container.querySelector('.compression-info');
    if (existingInfo) {
        existingInfo.remove();
    }
    
    infoElement.classList.add('compression-info');
    container.appendChild(infoElement);
}

window.formatFileSize = formatFileSize;
window.showCompressionInfo = showCompressionInfo;
