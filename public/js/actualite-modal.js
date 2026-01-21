/**
 * Actualité Modal Management
 * Gestion sécurisée des modales d'image
 */

(function() {
    'use strict';
    
    // Configuration
    const MODAL_ID = 'imageModal';
    const MODAL_IMAGE_ID = 'modalImage';
    const MODAL_TRIGGER_ATTR = 'data-modal-trigger';
    
    // Références DOM
    let modal = null;
    let modalImage = null;
    
    // État
    let isInitialized = false;
    
    /**
     * Initialise le système de modal
     */
    function initModal() {
        if (isInitialized) return;
        
        // Créer la modal si elle n'existe pas
        createModalIfNeeded();
        
        // Récupérer les références
        modal = document.getElementById(MODAL_ID);
        modalImage = document.getElementById(MODAL_IMAGE_ID);
        
        if (!modal || !modalImage) {
            console.warn('Modal elements not found');
            return;
        }
        
        // Attacher les événements
        attachEventListeners();
        
        isInitialized = true;
    }
    
    /**
     * Crée la structure HTML de la modal si nécessaire
     */
    function createModalIfNeeded() {
        if (document.getElementById(MODAL_ID)) return;
        
        const modalHTML = `
            <div id="${MODAL_ID}" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
                <div class="relative max-w-4xl max-h-full p-4">
                    <button type="button" class="close-button" aria-label="Fermer la modal">
                        <i class="fas fa-times text-xl" aria-hidden="true"></i>
                    </button>
                    <img id="${MODAL_IMAGE_ID}" src="" alt="" class="max-w-full max-h-full rounded-lg shadow-2xl">
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }
    
    /**
     * Attache tous les event listeners
     */
    function attachEventListeners() {
        // Event listener pour les triggers d'images
        document.addEventListener('click', handleImageClick);
        
        // Event listener pour fermer la modal
        const closeButton = modal.querySelector('.close-button');
        if (closeButton) {
            closeButton.addEventListener('click', closeModal);
        }
        
        // Fermer avec Escape
        document.addEventListener('keydown', handleKeyDown);
        
        // Fermer en cliquant sur le fond
        modal.addEventListener('click', handleBackgroundClick);
        
        // Gestion du chargement des images
        document.addEventListener('load', handleImageLoad, true);
    }
    
    /**
     * Gère le clic sur les images avec trigger
     */
    function handleImageClick(event) {
        const target = event.target;
        
        if (!target.matches(`[${MODAL_TRIGGER_ATTR}]`)) return;
        
        event.preventDefault();
        
        const src = target.getAttribute('data-modal-src');
        const alt = target.getAttribute('data-modal-alt');
        
        if (src && isValidImageUrl(src)) {
            openModal(src, alt || '');
        }
    }
    
    /**
     * Valide si l'URL de l'image est sécurisée
     */
    function isValidImageUrl(url) {
        try {
            const urlObj = new URL(url, window.location.origin);
            return urlObj.origin === window.location.origin && 
                   /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(urlObj.pathname);
        } catch {
            return false;
        }
    }
    
    /**
     * Ouvre la modal avec l'image spécifiée
     */
    function openModal(src, alt) {
        if (!modal || !modalImage) return;
        
        modalImage.src = src;
        modalImage.alt = sanitizeAlt(alt);
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Focus sur la modal pour l'accessibilité
        modal.focus();
    }
    
    /**
     * Ferme la modal
     */
    function closeModal() {
        if (!modal) return;
        
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Nettoyer l'image
        if (modalImage) {
            modalImage.src = '';
            modalImage.alt = '';
        }
    }
    
    /**
     * Gère les touches du clavier
     */
    function handleKeyDown(event) {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    }
    
    /**
     * Gère le clic sur le fond de la modal
     */
    function handleBackgroundClick(event) {
        if (event.target === modal) {
            closeModal();
        }
    }
    
    /**
     * Gère le chargement des images lazy
     */
    function handleImageLoad(event) {
        if (event.target.tagName === 'IMG' && event.target.loading === 'lazy') {
            event.target.classList.add('loaded');
        }
    }
    
    /**
     * Nettoie le texte alt pour éviter les injections
     */
    function sanitizeAlt(alt) {
        const div = document.createElement('div');
        div.textContent = alt;
        return div.innerHTML;
    }
    
    // Initialisation automatique
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initModal);
    } else {
        initModal();
    }
    
    // Export pour usage externe si nécessaire
    window.ActualiteModal = {
        open: openModal,
        close: closeModal,
        init: initModal
    };
    
})();
