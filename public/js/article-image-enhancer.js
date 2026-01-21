/**
 * Script JavaScript pour améliorer l'affichage des images dans les articles
 * Fichier: public/js/article-image-enhancer.js
 */

class ArticleImageEnhancer {
    constructor() {
        this.init();
    }

    init() {
        this.setupImageZoom();
        this.setupImageLazyLoading();
        this.setupImageSliders();
        this.setupBeforeAfterSliders();
        this.detectImageAlignments();
        this.addImageInteractions();
    }

    /**
     * Configuration du zoom sur les images
     */
    setupImageZoom() {
        const images = document.querySelectorAll('.rich-text-content img');
        const overlay = this.createImageOverlay();

        images.forEach(img => {
            // Ignorer les petites images (probablement des icônes)
            if (img.naturalWidth < 200 || img.naturalHeight < 200) return;

            img.classList.add('zoomable');
            img.addEventListener('click', (e) => {
                e.preventDefault();
                this.showImageOverlay(img.src, img.alt, overlay);
            });
        });
    }

    /**
     * Créer l'overlay pour le zoom des images
     */
    createImageOverlay() {
        const overlay = document.createElement('div');
        overlay.className = 'image-overlay';
        overlay.innerHTML = `
            <img src="" alt="" />
            <button class="close" aria-label="Fermer">&times;</button>
        `;

        overlay.addEventListener('click', (e) => {
            if (e.target === overlay || e.target.classList.contains('close')) {
                this.hideImageOverlay(overlay);
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && overlay.classList.contains('active')) {
                this.hideImageOverlay(overlay);
            }
        });

        document.body.appendChild(overlay);
        return overlay;
    }

    /**
     * Afficher l'image en zoom
     */
    showImageOverlay(src, alt, overlay) {
        const img = overlay.querySelector('img');
        img.src = src;
        img.alt = alt;
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    /**
     * Masquer l'overlay de zoom
     */
    hideImageOverlay(overlay) {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    /**
     * Configuration du lazy loading pour les images
     */
    setupImageLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                            img.classList.add('fade-in-up');
                        }
                        observer.unobserve(img);
                    }
                });
            });

            const images = document.querySelectorAll('.rich-text-content img[data-src]');
            images.forEach(img => imageObserver.observe(img));
        }
    }

    /**
     * Configuration des sliders d'images
     */
    setupImageSliders() {
        const sliders = document.querySelectorAll('.image-slider');

        sliders.forEach(slider => {
            const container = slider.querySelector('.slider-container');
            const slides = slider.querySelectorAll('.slide');
            const prevBtn = slider.querySelector('.prev');
            const nextBtn = slider.querySelector('.next');
            const indicators = slider.querySelectorAll('.indicator');

            let currentSlide = 0;

            const updateSlider = () => {
                container.style.transform = `translateX(-${currentSlide * 100}%)`;

                indicators.forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === currentSlide);
                });
            };

            prevBtn?.addEventListener('click', () => {
                currentSlide = currentSlide > 0 ? currentSlide - 1 : slides.length - 1;
                updateSlider();
            });

            nextBtn?.addEventListener('click', () => {
                currentSlide = currentSlide < slides.length - 1 ? currentSlide + 1 : 0;
                updateSlider();
            });

            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    currentSlide = index;
                    updateSlider();
                });
            });

            // Auto-play optionnel
            if (slider.dataset.autoplay === 'true') {
                setInterval(() => {
                    currentSlide = currentSlide < slides.length - 1 ? currentSlide + 1 : 0;
                    updateSlider();
                }, parseInt(slider.dataset.interval) || 5000);
            }
        });
    }

    /**
     * Configuration des sliders avant/après
     */
    setupBeforeAfterSliders() {
        const containers = document.querySelectorAll('.before-after-container');

        containers.forEach(container => {
            const afterImage = container.querySelector('.after-image');
            const divider = container.querySelector('.divider');
            let isDragging = false;

            const updatePosition = (clientX) => {
                const rect = container.getBoundingClientRect();
                const position = ((clientX - rect.left) / rect.width) * 100;
                const clampedPosition = Math.max(0, Math.min(100, position));

                afterImage.style.clipPath = `inset(0 ${100 - clampedPosition}% 0 0)`;
                divider.style.left = `${clampedPosition}%`;
            };

            container.addEventListener('mousedown', (e) => {
                isDragging = true;
                updatePosition(e.clientX);
            });

            document.addEventListener('mousemove', (e) => {
                if (isDragging) {
                    updatePosition(e.clientX);
                }
            });

            document.addEventListener('mouseup', () => {
                isDragging = false;
            });

            // Support tactile
            container.addEventListener('touchstart', (e) => {
                isDragging = true;
                updatePosition(e.touches[0].clientX);
            });

            container.addEventListener('touchmove', (e) => {
                if (isDragging) {
                    e.preventDefault();
                    updatePosition(e.touches[0].clientX);
                }
            });

            container.addEventListener('touchend', () => {
                isDragging = false;
            });
        });
    }

    /**
     * Détecter et améliorer les alignements d'images WordPress
     */
    detectImageAlignments() {
        const content = document.querySelector('.rich-text-content');
        if (!content) return;

        // Détecter les images avec alignement WordPress
        const alignedImages = content.querySelectorAll('img[style*="float"], .alignleft, .alignright, .aligncenter');

        alignedImages.forEach(img => {
            const paragraph = img.closest('p');
            if (paragraph) {
                paragraph.classList.add('with-floated-image');

                // Ajouter des marges adaptatives
                const rect = img.getBoundingClientRect();
                if (rect.width < 250) {
                    img.classList.add('small-floated-image');
                } else if (rect.width > 500) {
                    img.classList.add('large-floated-image');
                }
            }
        });

        // Optimiser l'espacement autour des images flottantes
        this.optimizeImageSpacing();
    }

    /**
     * Optimiser l'espacement autour des images
     */
    optimizeImageSpacing() {
        const floatedImages = document.querySelectorAll('.rich-text-content img[style*="float"], .rich-text-content .alignleft, .rich-text-content .alignright');

        floatedImages.forEach(img => {
            const nextParagraphs = [];
            let nextElement = img.parentElement.nextElementSibling;

            // Trouver les paragraphes suivants
            while (nextElement && nextElement.tagName === 'P') {
                nextParagraphs.push(nextElement);
                nextElement = nextElement.nextElementSibling;

                // Arrêter si on trouve une autre image ou si on a assez de contenu
                if (nextParagraphs.length >= 3 || nextElement?.querySelector('img')) {
                    break;
                }
            }

            // Ajouter une classe pour le styling spécial
            nextParagraphs.forEach((p, index) => {
                if (index < 2) {
                    p.classList.add('adjacent-to-floated-image');
                }
            });
        });
    }

    /**
     * Ajouter des interactions supplémentaires aux images
     */
    addImageInteractions() {
        const images = document.querySelectorAll('.rich-text-content img');

        images.forEach(img => {
            // Effet de chargement
            if (!img.complete) {
                img.style.opacity = '0';
                img.addEventListener('load', () => {
                    img.style.transition = 'opacity 0.3s ease';
                    img.style.opacity = '1';
                });
            }

            // Gestion des erreurs de chargement
            img.addEventListener('error', () => {
                img.style.display = 'none';

                const placeholder = document.createElement('div');
                placeholder.className = 'image-placeholder';
                placeholder.innerHTML = `
                    <div class="placeholder-content">
                        <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                        </svg>
                        <p>Image non disponible</p>
                    </div>
                `;
                img.parentNode.insertBefore(placeholder, img);
            });

            // Ajouter des attributs d'accessibilité si manquants
            if (!img.alt && img.title) {
                img.alt = img.title;
            }
        });
    }

    /**
     * Initialiser les galeries interactives
     */
    initInteractiveGalleries() {
        const galleries = document.querySelectorAll('.interactive-gallery');

        galleries.forEach(gallery => {
            const items = gallery.querySelectorAll('.gallery-item');

            items.forEach((item, index) => {
                item.addEventListener('click', () => {
                    // Créer une lightbox pour la galerie
                    this.showGalleryLightbox(items, index);
                });
            });
        });
    }

    /**
     * Afficher la lightbox de galerie
     */
    showGalleryLightbox(items, startIndex) {
            const lightbox = document.createElement('div');
            lightbox.className = 'gallery-lightbox';

            let currentIndex = startIndex;

            const updateLightbox = () => {
                    const currentItem = items[currentIndex];
                    const img = currentItem.querySelector('img');
                    const caption = currentItem.querySelector('.caption');

                    lightbox.innerHTML = `
                <div class="lightbox-content">
                    <img src="${img.src}" alt="${img.alt}" />
                    ${caption ? `<div class="lightbox-caption">${caption.innerHTML}</div>` : ''}
                    <button class="lightbox-prev">‹</button>
                    <button class="lightbox-next">›</button>
                    <button class="lightbox-close">×</button>
                    <div class="lightbox-counter">${currentIndex + 1} / ${items.length}</div>
                </div>
            `;
            
            // Event listeners pour la navigation
            lightbox.querySelector('.lightbox-prev').addEventListener('click', () => {
                currentIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
                updateLightbox();
            });
            
            lightbox.querySelector('.lightbox-next').addEventListener('click', () => {
                currentIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
                updateLightbox();
            });
            
            lightbox.querySelector('.lightbox-close').addEventListener('click', () => {
                document.body.removeChild(lightbox);
                document.body.style.overflow = '';
            });
        };
        
        updateLightbox();
        document.body.appendChild(lightbox);
        document.body.style.overflow = 'hidden';
        
        // Navigation au clavier
        const handleKeydown = (e) => {
            switch(e.key) {
                case 'ArrowLeft':
                    lightbox.querySelector('.lightbox-prev').click();
                    break;
                case 'ArrowRight':
                    lightbox.querySelector('.lightbox-next').click();
                    break;
                case 'Escape':
                    lightbox.querySelector('.lightbox-close').click();
                    document.removeEventListener('keydown', handleKeydown);
                    break;
            }
        };
        
        document.addEventListener('keydown', handleKeydown);
    }
}

// Styles CSS pour la lightbox de galerie
const lightboxStyles = `
<style>
.gallery-lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}

.lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
    text-align: center;
}

.lightbox-content img {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 8px;
}

.lightbox-caption {
    color: white;
    padding: 16px;
    font-size: 14px;
    line-height: 1.5;
}

.lightbox-prev,
.lightbox-next,
.lightbox-close {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: white;
    font-size: 24px;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.lightbox-prev:hover,
.lightbox-next:hover,
.lightbox-close:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

.lightbox-prev {
    top: 50%;
    left: -60px;
    transform: translateY(-50%);
}

.lightbox-next {
    top: 50%;
    right: -60px;
    transform: translateY(-50%);
}

.lightbox-close {
    top: -60px;
    right: 0;
}

.lightbox-counter {
    position: absolute;
    bottom: -50px;
    left: 50%;
    transform: translateX(-50%);
    color: white;
    font-size: 14px;
    background: rgba(0, 0, 0, 0.5);
    padding: 8px 16px;
    border-radius: 20px;
}

.image-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    padding: 32px;
    margin: 16px 0;
    color: #6b7280;
    text-align: center;
}

.placeholder-content svg {
    margin-bottom: 12px;
    opacity: 0.6;
}

.placeholder-content p {
    margin: 0;
    font-size: 14px;
}

@media (max-width: 768px) {
    .lightbox-prev,
    .lightbox-next {
        position: fixed;
        top: 50%;
        transform: translateY(-50%);
    }
    
    .lightbox-prev {
        left: 16px;
    }
    
    .lightbox-next {
        right: 16px;
    }
    
    .lightbox-close {
        position: fixed;
        top: 16px;
        right: 16px;
    }
    
    .lightbox-counter {
        position: fixed;
        bottom: 16px;
        left: 50%;
    }
}
</style>
`;

// Injecter les styles
document.head.insertAdjacentHTML('beforeend', lightboxStyles);

// Initialiser quand le DOM est prêt
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new ArticleImageEnhancer();
    });
} else {
    new ArticleImageEnhancer();
}