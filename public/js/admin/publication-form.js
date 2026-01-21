/**
 * JavaScript pour le formulaire de publication - Admin GRN-UCBC
 * Restauré le 12/11/2025
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log(' Formulaire de publication initialisé');
    
    // ================================================================
    // GESTION DES CHECKBOXES PERSONNALISÉES
    // ================================================================
    
    window.toggleCheckbox = function(id) {
        const checkbox = document.getElementById(id);
        const visual = document.getElementById('visual_' + id);
        const icon = document.getElementById('icon_' + id);
        const label = document.getElementById('label_' + id);
        
        if (!checkbox || !visual || !icon) return;
        
        checkbox.checked = !checkbox.checked;
        
        if (checkbox.checked) {
            visual.classList.add('bg-blue-600', 'border-blue-600');
            visual.classList.remove('border-gray-300');
            icon.classList.remove('hidden');
            label?.classList.add('bg-blue-50');
        } else {
            visual.classList.remove('bg-blue-600', 'border-blue-600');
            visual.classList.add('border-gray-300');
            icon.classList.add('hidden');
            label?.classList.remove('bg-blue-50');
        }
    };
    
    // ================================================================
    // COMPTEUR DE CARACTÈRES
    // ================================================================
    
    const resumeTextarea = document.getElementById('resume');
    const resumeCounter = document.getElementById('resume-count');
    
    if (resumeTextarea && resumeCounter) {
        const updateCounter = () => {
            const count = resumeTextarea.value.length;
            resumeCounter.textContent = count;
            
            if (count < 50) {
                resumeCounter.className = 'text-red-400';
            } else if (count > 500) {
                resumeCounter.className = 'text-yellow-400';
            } else {
                resumeCounter.className = 'text-gray-400';
            }
        };
        
        resumeTextarea.addEventListener('input', updateCounter);
        updateCounter();
    }
    
    // ================================================================
    // GESTION DES MODALS
    // ================================================================
    
    // Modals
    const authorModal = document.getElementById('authorModal');
    const categoryModal = document.getElementById('categoryModal');
    
    // Boutons d'ouverture
    const createAuthorBtn = document.getElementById('createAuthorBtn');
    const searchAuthorBtn = document.getElementById('searchAuthorBtn');
    const createCategoryBtn = document.getElementById('createCategoryBtn');
    
    // Boutons de fermeture
    const closeAuthorModalBtn = document.getElementById('closeAuthorModal');
    const closeCategoryModalBtn = document.getElementById('closeCategoryModal');
    const cancelCreateAuthor = document.getElementById('cancelCreateAuthor');
    const cancelCreateCategory = document.getElementById('cancelCreateCategory');
    
    // Onglets du modal auteur
    const searchAuthorTab = document.getElementById('searchAuthorTab');
    const createAuthorTab = document.getElementById('createAuthorTab');
    const searchAuthorContent = document.getElementById('searchAuthorContent');
    const createAuthorContent = document.getElementById('createAuthorContent');
    
    // Fonctions d'ouverture/fermeture des modals
    window.openAuthorModal = function(tab = 'search') {
        if (authorModal) {
            authorModal.classList.remove('hidden');
            if (tab === 'create') {
                switchAuthorTab('create');
            } else {
                switchAuthorTab('search');
            }
        }
    };
    
    window.closeAuthorModal = function() {
        if (authorModal) {
            authorModal.classList.add('hidden');
            // Réinitialiser le formulaire
            const form = document.getElementById('createAuthorForm');
            if (form) form.reset();
        }
    };
    
    window.openCategoryModal = function() {
        if (categoryModal) {
            categoryModal.classList.remove('hidden');
        }
    };
    
    window.closeCategoryModal = function() {
        if (categoryModal) {
            categoryModal.classList.add('hidden');
            // Réinitialiser le formulaire
            const form = document.getElementById('createCategoryForm');
            if (form) form.reset();
        }
    };
    
    // Fonction pour changer d'onglet dans le modal auteur
    function switchAuthorTab(tab) {
        if (tab === 'search') {
            searchAuthorTab?.classList.add('border-iri-primary', 'text-iri-primary');
            searchAuthorTab?.classList.remove('border-transparent', 'text-gray-500');
            createAuthorTab?.classList.add('border-transparent', 'text-gray-500');
            createAuthorTab?.classList.remove('border-iri-primary', 'text-iri-primary');
            searchAuthorContent?.classList.remove('hidden');
            createAuthorContent?.classList.add('hidden');
        } else {
            createAuthorTab?.classList.add('border-iri-primary', 'text-iri-primary');
            createAuthorTab?.classList.remove('border-transparent', 'text-gray-500');
            searchAuthorTab?.classList.add('border-transparent', 'text-gray-500');
            searchAuthorTab?.classList.remove('border-iri-primary', 'text-iri-primary');
            createAuthorContent?.classList.remove('hidden');
            searchAuthorContent?.classList.add('hidden');
        }
    }
    
    // Event listeners pour les boutons d'ouverture
    createAuthorBtn?.addEventListener('click', function(e) {
        e.preventDefault();
        openAuthorModal('create');
    });
    
    searchAuthorBtn?.addEventListener('click', function(e) {
        e.preventDefault();
        openAuthorModal('search');
    });
    
    createCategoryBtn?.addEventListener('click', function(e) {
        e.preventDefault();
        openCategoryModal();
    });
    
    // Event listeners pour les boutons de fermeture
    closeAuthorModalBtn?.addEventListener('click', closeAuthorModal);
    closeCategoryModalBtn?.addEventListener('click', closeCategoryModal);
    cancelCreateAuthor?.addEventListener('click', closeAuthorModal);
    cancelCreateCategory?.addEventListener('click', closeCategoryModal);
    
    // Event listeners pour les onglets
    searchAuthorTab?.addEventListener('click', () => switchAuthorTab('search'));
    createAuthorTab?.addEventListener('click', () => switchAuthorTab('create'));
    
    // Fermer les modals en cliquant à l'extérieur
    authorModal?.addEventListener('click', function(e) {
        if (e.target === authorModal) {
            closeAuthorModal();
        }
    });
    
    categoryModal?.addEventListener('click', function(e) {
        if (e.target === categoryModal) {
            closeCategoryModal();
        }
    });
    
    // ================================================================
    // UPLOAD DE FICHIERS PDF
    // ================================================================
    
    const fileInput = document.getElementById('fichier_pdf');
    
    if (fileInput) {
        console.log(' Champ fichier PDF trouvé');
        
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                console.log(' Fichier sélectionné:', file.name);
                
                // Validation PDF
                if (file.type !== 'application/pdf') {
                    showNotification('Seuls les fichiers PDF sont autorisés', 'error');
                    this.value = '';
                    return;
                }
                
                // Validation taille
                if (file.size > 50 * 1024 * 1024) {
                    showNotification('Fichier trop volumineux (max 50MB)', 'error');
                    this.value = '';
                    return;
                }
                
                updateFileDisplay(file);
                showNotification('Fichier sélectionné avec succès', 'success');
            }
        });
        
        // Drag & Drop
        const dropZone = fileInput.parentElement;
        if (dropZone) {
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('border-blue-500', 'bg-blue-50');
            });
            
            dropZone.addEventListener('dragleave', function(e) {
                this.classList.remove('border-blue-500', 'bg-blue-50');
            });
            
            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('border-blue-500', 'bg-blue-50');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const file = files[0];
                    
                    if (file.type === 'application/pdf' && file.size <= 50 * 1024 * 1024) {
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        fileInput.files = dt.files;
                        updateFileDisplay(file);
                        showNotification('Fichier ajouté par glisser-déposer', 'success');
                    } else {
                        showNotification('Fichier invalide', 'error');
                    }
                }
            });
        }
    }
    
    // ================================================================
    // FONCTIONS UTILITAIRES
    // ================================================================
    
    function updateFileDisplay(file) {
        const dropZone = fileInput?.parentElement;
        const textContainer = dropZone?.querySelector('.text-center');
        
        if (textContainer) {
            dropZone.classList.add('border-green-300', 'bg-green-50');
            textContainer.innerHTML = `
                <div class="text-center">
                    <div class="text-green-500 mb-2"> Fichier sélectionné</div>
                    <div class="font-medium">${file.name}</div>
                    <div class="text-sm text-gray-500">${formatFileSize(file.size)}</div>
                </div>
            `;
        }
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300`;
        
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            info: 'bg-blue-500 text-white'
        };
        
        notification.classList.add(...colors[type].split(' '));
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => notification.remove(), 3000);
    }
    
    // Rendre les fonctions globales
    window.showNotification = showNotification;
    window.formatFileSize = formatFileSize;
    
    // ================================================================
    // GESTION RECHERCHE D'AUTEURS (AJAX)
    // ================================================================
    
    const authorSearch = document.getElementById('authorSearch');
    const authorSearchResults = document.getElementById('authorSearchResults');
    
    let searchTimeout;
    
    authorSearch?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (searchTerm.length < 2) {
            authorSearchResults.innerHTML = `
                <div class="p-4 text-center text-gray-500">
                    Tapez au moins 2 caractères pour rechercher...
                </div>
            `;
            return;
        }
        
        authorSearchResults.innerHTML = `
            <div class="p-4 text-center text-gray-500">
                <i class="fas fa-spinner fa-spin mr-2"></i> Recherche en cours...
            </div>
        `;
        
        searchTimeout = setTimeout(() => {
            fetch(`/admin/auteurs/search?q=${encodeURIComponent(searchTerm)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success || !data.data || data.data.length === 0) {
                    authorSearchResults.innerHTML = `
                        <div class="p-4 text-center text-gray-500">
                            Aucun auteur trouvé. <button type="button" onclick="document.getElementById('createAuthorTab').click()" class="text-blue-600 hover:text-blue-800 underline">Créer un nouveau</button>
                        </div>
                    `;
                    return;
                }
                
                let html = '<div class="divide-y divide-gray-200">';
                data.data.forEach(auteur => {
                    const fullName = auteur.nom_complet || auteur.display_name;
                    html += `
                        <div class="p-3 hover:bg-gray-50 cursor-pointer" onclick="selectAuthor(${auteur.id}, '${fullName.replace(/'/g, "\\'")}')">
                            <div class="font-medium text-gray-900">${fullName}</div>
                            ${auteur.institution ? `<div class="text-sm text-gray-500">${auteur.institution}</div>` : ''}
                            ${auteur.email ? `<div class="text-xs text-gray-400">${auteur.email}</div>` : ''}
                        </div>
                    `;
                });
                html += '</div>';
                
                authorSearchResults.innerHTML = html;
            })
            .catch(error => {
                console.error('Erreur de recherche:', error);
                authorSearchResults.innerHTML = `
                    <div class="p-4 text-center text-red-500">
                        Erreur lors de la recherche. Veuillez réessayer.
                    </div>
                `;
            });
        }, 300);
    });
    
    // Fonction pour sélectionner un auteur depuis la recherche
    window.selectAuthor = function(id, name) {
        const auteursSelect = document.getElementById('auteurs');
        if (auteursSelect) {
            // Sélectionner l'option correspondante
            const option = auteursSelect.querySelector(`option[value="${id}"]`);
            if (option) {
                option.selected = true;
                showNotification(`Auteur "${name}" ajouté`, 'success');
                closeAuthorModal();
            }
        }
    };
    
    // ================================================================
    // CRÉATION D'AUTEUR (AJAX)
    // ================================================================
    
    const createAuthorForm = document.getElementById('createAuthorForm');
    
    createAuthorForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Création...';
        
        fetch('/admin/auteurs', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Ajouter le nouvel auteur au select
                const auteursSelect = document.getElementById('auteurs');
                if (auteursSelect && data.data) {
                    const fullName = data.data.nom_complet || data.data.display_name;
                    const option = document.createElement('option');
                    option.value = data.data.id;
                    option.text = fullName;
                    option.selected = true;
                    auteursSelect.add(option);
                }
                
                showNotification(data.message || 'Auteur créé avec succès', 'success');
                createAuthorForm.reset();
                closeAuthorModal();
            } else {
                throw new Error(data.message || 'Erreur lors de la création');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification(error.message || 'Erreur lors de la création de l\'auteur', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
    
    // ================================================================
    // CRÉATION DE CATÉGORIE (AJAX)
    // ================================================================
    
    const createCategoryForm = document.getElementById('createCategoryForm');
    
    createCategoryForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Création...';
        
        fetch('/admin/categories', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Ajouter la nouvelle catégorie au select
                const categorieSelect = document.getElementById('categorie_id');
                if (categorieSelect && data.categorie) {
                    const option = document.createElement('option');
                    option.value = data.categorie.id;
                    option.text = data.categorie.nom;
                    option.selected = true;
                    categorieSelect.add(option);
                }
                
                showNotification(data.message || 'Catégorie créée avec succès', 'success');
                createCategoryForm.reset();
                closeCategoryModal();
            } else {
                throw new Error(data.message || 'Erreur lors de la création');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification(error.message || 'Erreur lors de la création de la catégorie', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
    
    // ================================================================
    // SÉLECTION MULTIPLE D'AUTEURS
    // ================================================================
    
    const selectAllAuthors = document.getElementById('selectAllAuthors');
    const clearAllAuthors = document.getElementById('clearAllAuthors');
    const auteursSelect = document.getElementById('auteurs');
    
    selectAllAuthors?.addEventListener('click', function() {
        if (auteursSelect) {
            for (let option of auteursSelect.options) {
                option.selected = true;
            }
            showNotification('Tous les auteurs sélectionnés', 'success');
        }
    });
    
    clearAllAuthors?.addEventListener('click', function() {
        if (auteursSelect) {
            for (let option of auteursSelect.options) {
                option.selected = false;
            }
            showNotification('Sélection effacée', 'info');
        }
    });
    
    console.log('✅ JavaScript entièrement restauré avec gestion AJAX');
});
