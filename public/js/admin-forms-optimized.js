/**
 * SCRIPT GLOBAL OPTIMISÉ POUR TOUS LES FORMULAIRES ADMIN
 * Remplace tous les scripts inline volumineux
 * Prévient les freezes et optimise les performances
 */

window.AdminFormsOptimized = (function() {
    "use strict";
    
    // Configuration globale
    const config = {
        debug: window.location.hostname === "127.0.0.1" || window.location.hostname === "localhost",
        maxEventListeners: 10,
        cleanupTimeout: 5000
    };
    
    // Variables globales
    let editors = {};
    let activeModals = {};
    let eventListeners = [];
    let timeouts = [];
    let intervals = [];
    
    // Fonctions utilitaires
    function log(message, type = "info") {
        if (config.debug) {
            console[type]("[AdminForms]", message);
        }
    }
    
    function addEventListenerSafe(element, event, handler, options = {}) {
        if (eventListeners.length >= config.maxEventListeners) {
            log("Trop d event listeners, nettoyage automatique", "warn");
            cleanup();
        }
        
        element.addEventListener(event, handler, options);
        eventListeners.push({ element, event, handler, options });
    }
    
    function addTimeoutSafe(callback, delay) {
        const id = setTimeout(() => {
            callback();
            timeouts = timeouts.filter(tid => tid !== id);
        }, delay);
        timeouts.push(id);
        return id;
    }
    
    function cleanup() {
        // Nettoyer les event listeners
        eventListeners.forEach(({ element, event, handler, options }) => {
            try {
                element.removeEventListener(event, handler, options);
            } catch (e) {
                log("Erreur nettoyage listener: " + e.message, "warn");
            }
        });
        eventListeners = [];
        
        // Nettoyer les timeouts
        timeouts.forEach(id => clearTimeout(id));
        timeouts = [];
        
        // Nettoyer les intervals
        intervals.forEach(id => clearInterval(id));
        intervals = [];
        
        // Nettoyer les éditeurs
        Object.values(editors).forEach(editor => {
            if (editor && typeof editor.destroy === "function") {
                editor.destroy().catch(() => {});
            }
        });
        editors = {};
        
        log("Nettoyage global effectué");
    }
    
    // Initialisation CKEditor optimisée
    function initCKEditor(selector, formType) {
        const element = document.querySelector(selector);
        if (!element || typeof ClassicEditor === "undefined") {
            return null;
        }
        
        const editorConfig = {
            toolbar: {
                items: [
                    "heading", "|", "bold", "italic", "link", "bulletedList", "numberedList", "|",
                    "outdent", "indent", "|", "imageUpload", "blockQuote", "insertTable", "mediaEmbed",
                    "undo", "redo", "fontFamily", "fontSize", "fontColor", "fontBackgroundColor", "alignment"
                ]
            },
            language: "fr",
            image: {
                toolbar: ["imageTextAlternative", "imageStyle:inline", "imageStyle:block", "imageStyle:side", "linkImage"]
            },
            table: {
                contentToolbar: ["tableColumn", "tableRow", "mergeTableCells"]
            },
            simpleUpload: {
                uploadUrl: "/admin/media/upload-from-ckeditor",
                withCredentials: true,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").getAttribute("content")
                }
            }
        };
        
        return ClassicEditor.create(element, editorConfig)
            .then(editor => {
                editors[formType] = editor;
                log("CKEditor initialisé pour " + formType);
                return editor;
            })
            .catch(error => {
                log("Erreur CKEditor: " + error.message, "error");
                return null;
            });
    }
    
    // Initialisation bibliothèque média optimisée
    function initMediaLibrary(formType) {
        const openBtn = document.getElementById("open-media-library");
        const modal = document.getElementById("media-library-modal");
        const closeBtn = document.getElementById("close-media-library");
        const mediaGrid = document.getElementById("media-grid");
        
        if (!openBtn || !modal) return;
        
        addEventListenerSafe(openBtn, "click", function(e) {
            e.preventDefault();
            loadMediaItems();
            modal.classList.remove("hidden");
            activeModals[formType] = modal;
        });
        
        if (closeBtn) {
            addEventListenerSafe(closeBtn, "click", function() {
                modal.classList.add("hidden");
                delete activeModals[formType];
            });
        }
        
        addEventListenerSafe(modal, "click", function(e) {
            if (e.target === modal) {
                modal.classList.add("hidden");
                delete activeModals[formType];
            }
        });
        
        log("Bibliothèque média initialisée pour " + formType);
    }
    
    // Chargement optimisé des médias
    function loadMediaItems() {
        const mediaGrid = document.getElementById("media-grid");
        if (!mediaGrid) return;
        
        mediaGrid.innerHTML = '<div class="flex justify-center items-center h-64"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div></div>';
        
        fetch("/admin/media/list", {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").getAttribute("content")
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.media) {
                displayMediaItems(data.media);
            } else {
                mediaGrid.innerHTML = '<div class="text-center text-gray-500">Aucun média disponible</div>';
            }
        })
        .catch(error => {
            log("Erreur chargement médias: " + error.message, "error");
            mediaGrid.innerHTML = '<div class="text-center text-red-600">Erreur de chargement</div>';
        });
    }
    
    // Affichage des médias
    function displayMediaItems(mediaItems) {
        const mediaGrid = document.getElementById("media-grid");
        if (!mediaGrid) return;
        
        if (mediaItems.length === 0) {
            mediaGrid.innerHTML = '<div class="text-center text-gray-500">Aucun média disponible</div>';
            return;
        }
        
        const html = mediaItems.map(media => `
            <div class="media-item relative group cursor-pointer border-2 border-transparent hover:border-blue-500 rounded-lg overflow-hidden" 
                 data-media-id="${media.id}" 
                 data-media-url="${media.url}"
                 data-media-name="${media.nom}">
                <img src="${media.url}" alt="${media.nom}" class="w-full h-32 object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center">
                    <button class="select-media-btn opacity-0 group-hover:opacity-100 bg-blue-600 text-white px-3 py-1 rounded text-sm transition-opacity duration-200">
                        Sélectionner
                    </button>
                </div>
                <div class="p-2 bg-white">
                    <p class="text-xs text-gray-600 truncate">${media.nom}</p>
                </div>
            </div>
        `).join("");
        
        mediaGrid.innerHTML = html;
        
        // Ajouter les listeners de sélection
        mediaGrid.querySelectorAll(".media-item").forEach(item => {
            addEventListenerSafe(item, "click", function() {
                selectMediaItem(this);
            });
        });
    }
    
    // Sélection de média
    function selectMediaItem(element) {
        const mediaUrl = element.dataset.mediaUrl;
        const mediaName = element.dataset.mediaName;
        const currentEditor = Object.values(editors)[0]; // Premier éditeur actif
        
        if (currentEditor && mediaUrl) {
            currentEditor.model.change(writer => {
                const imageElement = writer.createElement("imageBlock", {
                    src: mediaUrl,
                    alt: mediaName
                });
                currentEditor.model.insertContent(imageElement);
            });
        }
        
        // Fermer toutes les modales actives
        Object.values(activeModals).forEach(modal => {
            modal.classList.add("hidden");
        });
        activeModals = {};
    }
    
    // Initialisation upload optimisée
    function initUpload(formType) {
        const uploadBtn = document.getElementById("upload-media-btn");
        const fileInput = document.getElementById("media-file-input");
        
        if (!uploadBtn || !fileInput) return;
        
        addEventListenerSafe(uploadBtn, "click", function() {
            fileInput.click();
        });
        
        addEventListenerSafe(fileInput, "change", function(e) {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                uploadFiles(files);
            }
        });
        
        log("Upload initialisé pour " + formType);
    }
    
    // Upload de fichiers
    function uploadFiles(files) {
        const progressContainer = document.getElementById("upload-progress-container");
        const progressBar = document.getElementById("upload-progress-bar");
        
        if (progressContainer) {
            progressContainer.classList.remove("hidden");
        }
        
        let completed = 0;
        const total = files.length;
        
        files.forEach(file => {
            uploadSingleFile(file, () => {
                completed++;
                const progress = (completed / total) * 100;
                
                if (progressBar) {
                    progressBar.style.width = progress + "%";
                }
                
                if (completed === total) {
                    addTimeoutSafe(() => {
                        if (progressContainer) {
                            progressContainer.classList.add("hidden");
                        }
                        loadMediaItems();
                    }, 1000);
                }
            });
        });
    }
    
    // Upload d un seul fichier
    function uploadSingleFile(file, onComplete) {
        const formData = new FormData();
        formData.append("file", file);
        formData.append("_token", document.querySelector("meta[name=csrf-token]").getAttribute("content"));
        
        fetch("/admin/media/upload", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                log("Fichier uploadé: " + file.name);
            }
        })
        .catch(error => {
            log("Erreur upload: " + error.message, "error");
        })
        .finally(() => {
            onComplete();
        });
    }
    
    // Initialisation principale
    function init(formType) {
        log("Initialisation formulaire: " + formType);
        
        // Nettoyer avant initialisation
        cleanup();
        
        // Initialiser les composants
        addTimeoutSafe(() => {
            initCKEditor("#content", formType);
            initMediaLibrary(formType);
            initUpload(formType);
            
            // Initialiser la validation de formulaire
            const form = document.querySelector("form");
            if (form) {
                addEventListenerSafe(form, "submit", function(e) {
                    const requiredFields = form.querySelectorAll("[required]");
                    let isValid = true;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add("border-red-500");
                        } else {
                            field.classList.remove("border-red-500");
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        log("Validation formulaire échouée", "warn");
                    }
                });
            }
        }, 100);
        
        log("Formulaire " + formType + " initialisé avec succès");
    }
    
    // Nettoyage automatique au départ de page
    window.addEventListener("beforeunload", cleanup);
    
    // Nettoyage automatique périodique
    const cleanupInterval = setInterval(() => {
        if (eventListeners.length > config.maxEventListeners) {
            log("Nettoyage automatique périodique");
            cleanup();
        }
    }, config.cleanupTimeout);
    intervals.push(cleanupInterval);
    
    // API publique
    return {
        init: init,
        cleanup: cleanup,
        log: log
    };
})();