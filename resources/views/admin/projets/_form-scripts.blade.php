<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<script>
let editorInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    
    // Elements
    const form = document.getElementById('projet-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitLoading = document.getElementById('submit-loading');
    
    // Form validation
    const validators = {
        nom: {
            element: document.getElementById('nom'),
            errorElement: document.getElementById('nom-error'),
            validate: function(value) {
                if (!value || value.trim().length < 3) {
                    return 'Le nom du projet doit contenir au moins 3 caractères';
                }
                if (value.length > 100) {
                    return 'Le nom du projet ne peut pas dépasser 100 caractères';
                }
                return null;
            }
        },
        description: {
            element: document.getElementById('description'),
            errorElement: document.getElementById('description-error'),
            validate: function(value) {
                if (!value || value.trim().length < 50) {
                    return 'La description doit contenir au moins 50 caractères';
                }
                if (value.length > 2000) {
                    return 'La description ne peut pas dépasser 2000 caractères';
                }
                return null;
            }
        },
        date_debut: {
            element: document.getElementById('date_debut'),
            errorElement: document.getElementById('date_debut-error'),
            validate: function(value) {
                if (value) {
                    const dateDebut = new Date(value);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    if (dateDebut < today) {
                        return 'La date de début ne peut pas être antérieure à aujourd\'hui';
                    }
                }
                return null;
            }
        },
        date_fin: {
            element: document.getElementById('date_fin'),
            errorElement: document.getElementById('date_fin-error'),
            validate: function(value) {
                const dateDebut = document.getElementById('date_debut').value;
                if (value && dateDebut) {
                    const debut = new Date(dateDebut);
                    const fin = new Date(value);
                    
                    if (fin <= debut) {
                        return 'La date de fin doit être postérieure à la date de début';
                    }
                }
                return null;
            }
        }
    };

    // Real-time validation
    Object.keys(validators).forEach(fieldName => {
        const validator = validators[fieldName];
        const element = validator.element;
        const errorElement = validator.errorElement;

        element.addEventListener('blur', function() {
            validateField(fieldName);
        });

        element.addEventListener('input', function() {
            // Clear error on input
            if (errorElement.textContent) {
                clearError(fieldName);
            }
        });
    });

    function validateField(fieldName) {
        const validator = validators[fieldName];
        const value = validator.element.value;
        const error = validator.validate(value);
        
        if (error) {
            showError(fieldName, error);
            return false;
        } else {
            clearError(fieldName);
            return true;
        }
    }

    function showError(fieldName, message) {
        const validator = validators[fieldName];
        validator.element.classList.add('border-red-500');
        validator.element.classList.remove('border-gray-300');
        validator.errorElement.textContent = message;
        validator.errorElement.classList.remove('hidden');
    }

    function clearError(fieldName) {
        const validator = validators[fieldName];
        validator.element.classList.remove('border-red-500');
        validator.element.classList.add('border-gray-300');
        validator.errorElement.textContent = '';
        validator.errorElement.classList.add('hidden');
    }

    // Validate all fields
    function validateAllFields() {
        let isValid = true;
        Object.keys(validators).forEach(fieldName => {
            if (!validateField(fieldName)) {
                isValid = false;
            }
        });
        return isValid;
    }

    // Resume character counter
    const resumeTextarea = document.getElementById('resume');
    const resumeCounter = document.getElementById('resume-count');
    
    if (resumeTextarea && resumeCounter) {
        function updateResumeCounter() {
            const count = resumeTextarea.value.length;
            resumeCounter.textContent = count;
            
            if (count > 255) {
                resumeCounter.classList.add('text-red-500');
                resumeCounter.classList.remove('text-gray-500');
            } else {
                resumeCounter.classList.remove('text-red-500');
                resumeCounter.classList.add('text-gray-500');
            }
        }
        
        resumeTextarea.addEventListener('input', updateResumeCounter);
        updateResumeCounter(); // Initial count
    }

    // Durée calculation avec animation
    const dateDebutInput = document.getElementById('date_debut');
    const dateFinInput = document.getElementById('date_fin');
    const dureeDisplay = document.getElementById('duree-display');
    const dureeMois = document.getElementById('duree-mois');

    function calculateDuree() {
        const dateDebut = dateDebutInput.value;
        const dateFin = dateFinInput.value;
        
        if (!dateDebut || !dateFin) {
            dureeDisplay.textContent = 'Non définie';
            dureeMois.textContent = '';
            dureeDisplay.className = 'text-lg font-semibold text-gray-400';
            return;
        }

        const debut = new Date(dateDebut);
        const fin = new Date(dateFin);
        
        if (fin <= debut) {
            dureeDisplay.textContent = 'Dates invalides';
            dureeMois.textContent = '';
            dureeDisplay.className = 'text-lg font-semibold text-red-500';
            return;
        }

        // Calcul de la différence en jours
        const diffTime = Math.abs(fin - debut);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        // Conversion en mois (utilisant 30.44 jours par mois en moyenne)
        const moisDecimal = Math.round((diffDays / 30.44) * 10) / 10;
        
        // Formatage du texte
        let texteFormate;
        if (moisDecimal < 1) {
            const jours = Math.round(moisDecimal * 30.44);
            texteFormate = jours + ' jour' + (jours > 1 ? 's' : '');
        } else if (moisDecimal === 1) {
            texteFormate = '1 mois';
        } else if (moisDecimal < 12) {
            texteFormate = moisDecimal + ' mois';
        } else {
            const annees = Math.floor(moisDecimal / 12);
            const moisRestants = Math.round((moisDecimal % 12) * 10) / 10;
            texteFormate = annees + ' an' + (annees > 1 ? 's' : '');
            if (moisRestants > 0) {
                texteFormate += ' et ' + moisRestants + ' mois';
            }
        }
        
        // Animation de mise à jour
        dureeDisplay.style.transition = 'all 0.3s ease';
        dureeDisplay.style.transform = 'scale(1.05)';
        dureeDisplay.className = 'text-lg font-semibold text-green-600';
        
        dureeDisplay.textContent = texteFormate;
        dureeMois.textContent = '(' + moisDecimal + ' mois)';
        
        // Retour à la normale
        setTimeout(() => {
            dureeDisplay.style.transform = 'scale(1)';
            dureeDisplay.className = 'text-lg font-semibold text-iri-primary';
        }, 300);
    }

    // Event listeners pour le calcul de durée - calcul immédiat
    if (dateDebutInput && dateFinInput) {
        // Calcul immédiat sur chaque saisie
        dateDebutInput.addEventListener('input', calculateDuree);
        dateFinInput.addEventListener('input', calculateDuree);
        dateDebutInput.addEventListener('change', calculateDuree);
        dateFinInput.addEventListener('change', calculateDuree);
        
        // Calcul initial si les dates sont déjà remplies
        calculateDuree();
    }

    // Calcul automatique des bénéficiaires totaux avec animation améliorée
    const beneficiairesHommesInput = document.getElementById('beneficiaires_hommes');
    const beneficiairesFemmesInput = document.getElementById('beneficiaires_femmes');
    const beneficiairesTotalInput = document.getElementById('beneficiaires_total');
    const beneficiairesTotalDisplay = document.getElementById('beneficiaires-total-display');

    function calculateBeneficiairesTotal() {
        const hommes = parseInt(beneficiairesHommesInput.value) || 0;
        const femmes = parseInt(beneficiairesFemmesInput.value) || 0;
        const total = hommes + femmes;
        
        beneficiairesTotalInput.value = total;
        
        // Animation de compteur
        const currentTotal = parseInt(beneficiairesTotalDisplay.textContent) || 0;
        const increment = total > currentTotal ? 1 : -1;
        const steps = Math.abs(total - currentTotal);
        
        if (steps > 0 && steps <= 20) {
            // Animation de compteur pour petites différences
            let current = currentTotal;
            const stepTime = 30;
            
            const counter = setInterval(() => {
                current += increment;
                beneficiairesTotalDisplay.textContent = current;
                
                if (current === total) {
                    clearInterval(counter);
                    // Animation de mise en évidence
                    beneficiairesTotalDisplay.style.transition = 'all 0.3s ease';
                    beneficiairesTotalDisplay.style.transform = 'scale(1.1)';
                    beneficiairesTotalDisplay.style.color = '#059669'; // vert
                    
                    setTimeout(() => {
                        beneficiairesTotalDisplay.style.transform = 'scale(1)';
                        beneficiairesTotalDisplay.style.color = '';
                    }, 300);
                }
            }, stepTime);
        } else {
            // Mise à jour directe pour grandes différences
            beneficiairesTotalDisplay.textContent = total;
            beneficiairesTotalDisplay.style.transition = 'all 0.3s ease';
            beneficiairesTotalDisplay.style.transform = 'scale(1.05)';
            beneficiairesTotalDisplay.style.color = '#059669';
            
            setTimeout(() => {
                beneficiairesTotalDisplay.style.transform = 'scale(1)';
                beneficiairesTotalDisplay.style.color = '';
            }, 300);
        }
    }

    // Event listeners pour le calcul des bénéficiaires - calcul immédiat
    if (beneficiairesHommesInput && beneficiairesFemmesInput) {
        // Calcul immédiat sur chaque saisie
        beneficiairesHommesInput.addEventListener('input', calculateBeneficiairesTotal);
        beneficiairesFemmesInput.addEventListener('input', calculateBeneficiairesTotal);
        beneficiairesHommesInput.addEventListener('change', calculateBeneficiairesTotal);
        beneficiairesFemmesInput.addEventListener('change', calculateBeneficiairesTotal);
        
        // Calcul initial
        calculateBeneficiairesTotal();
    }

    // Feedback visuel pour les champs modifiés
    function addFieldFeedback() {
        const inputs = document.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.style.transition = 'border-color 0.2s ease';
                this.style.borderColor = '#10b981'; // vert pour indiquer la modification
                
                setTimeout(() => {
                    this.style.borderColor = '';
                }, 1000);
            });
        });
    }

    // Initialiser le feedback visuel
    addFieldFeedback();

    // Auto-calculate total beneficiaries
    const beneficiairesHommes = document.getElementById('beneficiaires_hommes');
    const beneficiairesFemmes = document.getElementById('beneficiaires_femmes');
    const beneficiairesTotal = document.getElementById('beneficiaires_total');

    function updateTotalBeneficiaires() {
        const hommes = parseInt(beneficiairesHommes.value) || 0;
        const femmes = parseInt(beneficiairesFemmes.value) || 0;
        const currentTotal = parseInt(beneficiairesTotal.value) || 0;
        
        // Only auto-calculate if total is empty or equals sum
        if (!beneficiairesTotal.value || currentTotal === (hommes + femmes)) {
            beneficiairesTotal.value = hommes + femmes;
        }
    }

    if (beneficiairesHommes && beneficiairesFemmes && beneficiairesTotal) {
        beneficiairesHommes.addEventListener('input', updateTotalBeneficiaires);
        beneficiairesFemmes.addEventListener('input', updateTotalBeneficiaires);
    }

    // Image preview
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    if (imageInput && imagePreview && previewImg) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('La taille de l\'image ne peut pas dépasser 5MB');
                    imageInput.value = '';
                    imagePreview.classList.add('hidden');
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Seuls les fichiers JPG, PNG et WEBP sont acceptés');
                    imageInput.value = '';
                    imagePreview.classList.add('hidden');
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('hidden');
            }
        });
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate all fields
        if (!validateAllFields()) {
            // Scroll to first error
            const firstError = form.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                firstError.focus();
            }
            return false;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');
        
        // Submit form
        setTimeout(() => {
            form.submit();
        }, 300);
    });

    // Confirmation for form reset/cancel
    const cancelBtn = form.querySelector('a[href*="projets.index"]');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function(e) {
            const hasChanges = Array.from(form.elements).some(element => {
                if (element.type === 'submit' || element.type === 'button') return false;
                
                if (element.type === 'checkbox' || element.type === 'radio') {
                    return element.checked !== element.defaultChecked;
                }
                
                return element.value !== element.defaultValue;
            });
            
            if (hasChanges) {
                if (!confirm('Êtes-vous sûr de vouloir annuler ? Toutes les modifications seront perdues.')) {
                    e.preventDefault();
                }
            }
        });
    }

    // Auto-save draft (optional feature)
    let autoSaveTimeout;
    function autoSaveDraft() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            const formData = new FormData(form);
            const draftData = {};
            
            for (let [key, value] of formData.entries()) {
                if (key !== '_token' && key !== '_method' && key !== 'image') {
                    draftData[key] = value;
                }
            }
            
            localStorage.setItem('projet_draft', JSON.stringify(draftData));
        }, 2000);
    }

    // Load draft on page load
    function loadDraft() {
        const draft = localStorage.getItem('projet_draft');
        if (draft && !form.dataset.editing) { // Only for new projects
            try {
                const draftData = JSON.parse(draft);
                if (confirm('Un brouillon a été trouvé. Voulez-vous le restaurer ?')) {
                    Object.keys(draftData).forEach(key => {
                        const element = form.querySelector(`[name="${key}"]`);
                        if (element && !element.value) {
                            element.value = draftData[key];
                        }
                    });
                    updateResumeCounter();
                    updateTotalBeneficiaires();
                }
            } catch (e) {
                console.log('Erreur lors du chargement du brouillon');
            }
        }
    }

    // Add auto-save listeners
    const autosaveFields = ['nom', 'description', 'resume'];
    autosaveFields.forEach(fieldName => {
        const element = document.getElementById(fieldName);
        if (element) {
            element.addEventListener('input', autoSaveDraft);
        }
    });

    // Clear draft on successful submission
    form.addEventListener('submit', function() {
        localStorage.removeItem('projet_draft');
    });

    // Load draft when page loads
    loadDraft();

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
        
        // Escape to cancel
        if (e.key === 'Escape') {
            const cancelLink = form.querySelector('a[href*="projets.index"]');
            if (cancelLink) {
                cancelLink.click();
            }
        }
    });

    // Accessibility improvements
    // Add aria-describedby for error messages
    Object.keys(validators).forEach(fieldName => {
        const validator = validators[fieldName];
        validator.element.setAttribute('aria-describedby', `${fieldName}-error`);
    });

    // Focus management
    const firstInput = form.querySelector('input, textarea, select');
    if (firstInput) {
        firstInput.focus();
    }

    // Initialiser CKEditor pour le champ description
    initializeCKEditor();
});

// Fonctions CKEditor et Médiathèque
function initializeCKEditor() {
    const descriptionElement = document.getElementById('description');
    if (!descriptionElement) return;

    ClassicEditor
        .create(descriptionElement, {
            language: 'fr',
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', '|',
                'link', 'bulletedList', 'numberedList', '|',
                'blockQuote', 'insertTable', '|',
                'imageUpload', '|',
                'undo', 'redo'
            ],
            image: {
                toolbar: ['imageTextAlternative', 'imageStyle:full', 'imageStyle:side']
            }
        })
        .then(editor => {
            editorInstance = editor;
            
            // Ajouter le bouton Médiathèque manuellement
            addMediaLibraryButton(editor);
            
            // Synchronisation avec le textarea
            editor.model.document.on('change:data', () => {
                descriptionElement.value = editor.getData();
            });

            // Charger le contenu existant en mode édition
            const existingContent = descriptionElement.value;
            if (existingContent) {
                editor.setData(existingContent);
            }
        })
        .catch(error => {
            console.error('Erreur CKEditor:', error);
        });
}

function addMediaLibraryButton(editor) {
    // Ajouter un bouton personnalisé dans la toolbar
    const toolbar = editor.ui.view.toolbar;
    const mediaButton = document.createElement('button');
    mediaButton.type = 'button'; // Important: empêcher la soumission de formulaire
    mediaButton.className = 'ck ck-button ck-off';
    mediaButton.innerHTML = `
        <svg class="ck ck-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span class="ck ck-button__label">Médiathèque</span>
    `;
    mediaButton.title = 'Médiathèque';
    
    mediaButton.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopImmediatePropagation();
        
        openMediaModal(function(url) {
            try {
                editor.model.change(writer => {
                    const imageElement = writer.createElement('imageBlock', { src: url });
                    editor.model.insertContent(imageElement, editor.model.document.selection);
                });
            } catch (error) {
                console.error('Erreur lors de l\'insertion:', error);
            }
        });
        
        return false;
    });
    
    // Insérer le bouton dans la toolbar
    setTimeout(() => {
        const toolbarElement = toolbar.element.querySelector('.ck-toolbar__items');
        if (toolbarElement) {
            toolbarElement.appendChild(mediaButton);
        }
    }, 100);
}

// Fonction pour ouvrir le modal de médiathèque
function openMediaModal(callback) {
    // Créer le modal
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full m-4 max-h-[90vh] overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Médiathèque</h3>
                    <button type="button" class="close-modal text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6 overflow-y-auto max-h-[70vh]">
                <div id="media-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Chargement des médias...</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Fermer le modal
    modal.querySelector('.close-modal').addEventListener('click', () => {
        document.body.removeChild(modal);
    });
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            document.body.removeChild(modal);
        }
    });
    
    // Charger les médias
    fetch('/admin/media/list')
        .then(response => response.json())
        .then(data => {
            const grid = modal.querySelector('#media-grid');
            if (data.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Aucun média disponible</p>
                        <p class="text-xs text-gray-400">Ajoutez des images via le gestionnaire de médias</p>
                    </div>
                `;
                return;
            }
            
            grid.innerHTML = data.map(media => `
                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow cursor-pointer media-item" data-url="${media.url}">
                    <img src="${media.url}" alt="${media.name}" class="w-full h-32 object-cover">
                    <div class="p-3">
                        <p class="text-sm font-medium text-gray-900 truncate">${media.name}</p>
                        <p class="text-xs text-gray-500">${media.size || 'Taille inconnue'}</p>
                    </div>
                </div>
            `).join('');
            
            // Ajouter les événements de clic
            grid.querySelectorAll('.media-item').forEach(item => {
                item.addEventListener('click', () => {
                    const url = item.dataset.url;
                    callback(url);
                    document.body.removeChild(modal);
                });
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement des médias:', error);
            const grid = modal.querySelector('#media-grid');
            grid.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-red-600">Erreur lors du chargement</p>
                    <p class="text-xs text-gray-400">Vérifiez que la route /admin/media/list existe</p>
                </div>
            `;
        });
}
</script>

<style>
/* Custom animations and transitions */
.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Loading animation */
@keyframes pulse-subtle {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.animate-pulse-subtle {
    animation: pulse-subtle 2s infinite;
}

/* Focus styles for better accessibility */
input:focus, textarea:focus, select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* File input custom styling */
input[type="file"]:focus + label {
    border-color: #3B82F6;
    background-color: #F3F4F6;
}

/* Error state animations */
.border-red-500 {
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Success feedback */
.success-feedback {
    background: linear-gradient(90deg, #10B981, #059669);
    color: white;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Drag and drop styles */
.drag-over {
    border-color: #3B82F6 !important;
    background-color: #EFF6FF !important;
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .max-w-4xl {
        margin: 0.5rem;
    }
    
    .p-8 {
        padding: 1rem;
    }
    
    .px-8 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>
