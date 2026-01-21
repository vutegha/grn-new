/**
 * Publication Modal System
 * Système simple pour afficher le résumé dans un toast
 */

(function() {
    'use strict';
    
    let currentToast = null;
    
    /**
     * Initialise le système de modal
     */
    function initModalSystem() {
        // Éviter la double initialisation
        if (window.PublicationModal && window.PublicationModal._initialized) {
            console.log('Modal déjà initialisé, ignoré');
            return;
        }
        
        console.log('Initialisation du système de modal...');
        
        // Gestionnaires d'événements basés sur les attributs data
        setupDataAttributeHandlers();
        
        // Gestion de l'échappement
        setupKeyboardHandlers();
        
        // Programmer l'affichage automatique
        scheduleAutoShow();
        
        // Marquer comme initialisé
        if (window.PublicationModal) {
            window.PublicationModal._initialized = true;
        }
        
        console.log('Système de modal initialisé avec succès');
    }
    
    /**
     * Configure les gestionnaires d'événements basés sur les attributs data
     */
    function setupDataAttributeHandlers() {
        document.addEventListener('click', function(event) {
            const target = event.target;
            
            // Bouton pour montrer le toast "Lire le résumé"
            if (target.hasAttribute('data-show-toast') || target.closest('[data-show-toast]')) {
                console.log('Bouton "Lire le résumé" cliqué');
                event.preventDefault();
                showResumeToast();
                return;
            }
            
            // Bouton pour fermer le toast
            if (target.hasAttribute('data-close-toast') || target.closest('[data-close-toast]')) {
                console.log('Bouton fermer toast cliqué');
                event.preventDefault();
                closeToast();
                return;
            }
            
            // Lien de téléchargement du PDF
            if (target.hasAttribute('data-download-pdf') || target.closest('[data-download-pdf]')) {
                const link = target.hasAttribute('data-download-pdf') ? target : target.closest('[data-download-pdf]');
                const pdfUrl = link.getAttribute('data-download-pdf') || link.getAttribute('href');
                if (pdfUrl) {
                    console.log('Téléchargement PDF:', pdfUrl);
                    downloadPdf(pdfUrl, link);
                }
                return;
            }
        });
        
        console.log('Gestionnaires d\'événements configurés');
    }
    
    /**
     * Affiche le toast avec le résumé
     */
    function showResumeToast() {
        console.log('showResumeToast() appelée');
        
        const toast = document.getElementById('resumeToast');
        if (!toast) {
            console.error('Toast element with ID "resumeToast" not found');
            return;
        }
        
        console.log('Toast trouvé, affichage...');
        
        // Afficher le toast
        toast.classList.remove('hidden');
        toast.style.display = 'block';
        currentToast = toast;
        
        // Focus pour l'accessibilité
        const closeButton = toast.querySelector('[data-close-toast]');
        if (closeButton) {
            closeButton.focus();
        }
        
        // Marquer comme vu dans le localStorage
        localStorage.setItem('publication-resume-viewed', 'true');
        
        console.log('Toast affiché avec succès');
    }
    
    /**
     * Ferme le toast
     */
    function closeToast() {
        console.log('closeToast() appelée');
        
        const toast = document.getElementById('resumeToast');
        if (toast) {
            toast.classList.add('hidden');
            toast.style.display = 'none';
            console.log('Toast fermé');
        }
        currentToast = null;
    }
    
    /**
     * Configure les gestionnaires de clavier
     */
    function setupKeyboardHandlers() {
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && currentToast) {
                console.log('Touche Escape pressée, fermeture du toast');
                closeToast();
            }
        });
    }
    
    /**
     * Gère le téléchargement des PDF
     */
    function downloadPdf(url, linkElement) {
        if (!url) return;
        
        // Validation de l'URL
        if (!isValidPdfUrl(url)) {
            console.error('URL PDF invalide:', url);
            return;
        }
        
        // Indication visuelle du téléchargement
        const originalHtml = linkElement.innerHTML;
        linkElement.innerHTML = '<i class="fas fa-spinner fa-spin me-1" aria-hidden="true"></i> Téléchargement...';
        linkElement.classList.add('disabled');
        
        // Créer un lien temporaire pour le téléchargement
        const tempLink = document.createElement('a');
        tempLink.href = url;
        tempLink.download = ''; // Le navigateur utilisera le nom du fichier
        tempLink.target = '_blank';
        tempLink.rel = 'noopener noreferrer';
        
        // Déclencher le téléchargement
        document.body.appendChild(tempLink);
        tempLink.click();
        document.body.removeChild(tempLink);
        
        // Restaurer le bouton après un délai
        setTimeout(() => {
            linkElement.innerHTML = originalHtml;
            linkElement.classList.remove('disabled');
        }, 2000);
    }
    
    /**
     * Valide une URL PDF
     */
    function isValidPdfUrl(url) {
        try {
            const urlObj = new URL(url, window.location.origin);
            return urlObj.origin === window.location.origin && 
                   urlObj.pathname.toLowerCase().endsWith('.pdf');
        } catch {
            return false;
        }
    }
    
    /**
     * Programme l'affichage automatique du modal
     */
    function scheduleAutoShow() {
        // Configuration de l'affichage automatique
        const AUTO_SHOW_DELAY = 3000; // 3 secondes
        const STORAGE_KEY = 'publication-resume-viewed';
        
        // Vérifier si l'utilisateur a déjà vu le résumé
        const hasViewed = localStorage.getItem(STORAGE_KEY);
        if (hasViewed === 'true') {
            console.log('Modal déjà vu par l\'utilisateur, pas d\'affichage automatique');
            return;
        }
        
        console.log(`Modal programmé pour s'afficher automatiquement dans ${AUTO_SHOW_DELAY}ms`);
        
        // Programmer l'affichage automatique
        setTimeout(() => {
            // Vérifier à nouveau au cas où l'utilisateur aurait cliqué entre temps
            const hasViewedNow = localStorage.getItem(STORAGE_KEY);
            if (hasViewedNow !== 'true') {
                console.log('Affichage automatique du modal résumé');
                showResumeToast();
            } else {
                console.log('Modal déjà affiché manuellement, annulation de l\'affichage automatique');
            }
        }, AUTO_SHOW_DELAY);
    }
    
    /**
     * Réinitialise le statut "vu" pour permettre l'affichage automatique à nouveau
     */
    function resetViewedStatus() {
        localStorage.removeItem('publication-resume-viewed');
        console.log('Statut "vu" réinitialisé - le modal s\'affichera automatiquement au prochain chargement');
    }
    
    // Initialisation automatique avec multiples méthodes
    function ensureInitialization() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initModalSystem);
        } else {
            // DOM déjà chargé
            if (document.readyState === 'interactive' || document.readyState === 'complete') {
                setTimeout(initModalSystem, 0);
            }
        }
        
        // Backup au cas où
        window.addEventListener('load', function() {
            if (!window.PublicationModal._initialized) {
                initModalSystem();
            }
        });
    }
    
    ensureInitialization();
    
    // Export pour usage externe
    window.PublicationModal = {
        init: initModalSystem,
        showResumeToast: showResumeToast,
        closeToast: closeToast,
        scheduleAutoShow: scheduleAutoShow,
        resetViewedStatus: resetViewedStatus,
        _initialized: false
    };
    
    // Compatibilité avec l'ancien code
    window.showToastAgain = showResumeToast;
    window.closeToast = closeToast;
    window.resetResumeViewed = resetViewedStatus;
    
    console.log('Script publication-modal.js chargé');
    
})();
