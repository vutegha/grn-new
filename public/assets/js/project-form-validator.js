/**
 * Validation avancée du formulaire de projet
 * Sécurité et expérience utilisateur améliorées
 */
class ProjectFormValidator {
    constructor(form) {
        this.form = form;
        this.errors = new Map();
        this.initEventListeners();
        this.setupFileValidation();
    }

    initEventListeners() {
        // Validation en temps réel (avec délai pour éviter trop d'appels)
        this.form.querySelectorAll('input, textarea, select').forEach(field => {
            let hasUserInteracted = false;
            
            // Marquer l'interaction utilisateur lors de la saisie
            field.addEventListener('input', () => {
                hasUserInteracted = true;
                this.clearFieldError(field);
            });
            
            // Marquer l'interaction utilisateur lors du changement (pour les select)
            field.addEventListener('change', () => {
                hasUserInteracted = true;
                this.clearFieldError(field);
            });
            
            // Validation seulement quand l'utilisateur quitte le champ ET a interagi avec
            field.addEventListener('blur', () => {
                if (hasUserInteracted && field.value.trim() !== '') {
                    this.validateField(field);
                }
            });
        });

        // Validation avant soumission
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));

        // Validation des dates en temps réel (seulement si les deux champs sont remplis)
        const dateDebut = this.form.querySelector('[name="date_debut"]');
        const dateFin = this.form.querySelector('[name="date_fin"]');
        
        if (dateDebut) {
            dateDebut.addEventListener('change', () => {
                if (dateDebut.value && dateFin && dateFin.value) {
                    this.validateDates();
                }
            });
        }
        if (dateFin) {
            dateFin.addEventListener('change', () => {
                if (dateDebut && dateDebut.value && dateFin.value) {
                    this.validateDates();
                }
            });
        }

        // Validation du budget en temps réel
        const budgetField = this.form.querySelector('[name="budget"]');
        if (budgetField) {
            budgetField.addEventListener('input', () => this.formatBudget(budgetField));
        }

        // Calcul automatique du total des bénéficiaires
        ['beneficiaires_hommes', 'beneficiaires_femmes', 'beneficiaires_enfants'].forEach(fieldName => {
            const field = this.form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.addEventListener('input', () => this.calculateTotalBeneficiaires());
            }
        });
    }

    setupFileValidation() {
        const fileInput = this.form.querySelector('[name="image"]');
        if (!fileInput) return;

        fileInput.addEventListener('change', (e) => {
            this.validateFileUpload(e.target);
        });

        // Drag & Drop
        const dropZone = fileInput.closest('.file-upload-zone');
        if (dropZone) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, this.preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.add('drag-over'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.remove('drag-over'), false);
            });

            dropZone.addEventListener('drop', (e) => this.handleDrop(e, fileInput));
        }
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    handleDrop(e, fileInput) {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            this.validateFileUpload(fileInput);
        }
    }

    validateField(field) {
        const fieldName = field.name;
        const value = field.value.trim();

        // Nettoyer les erreurs précédentes
        this.clearFieldError(field);

        switch (fieldName) {
            case 'nom':
                this.validateNom(field, value);
                break;
            case 'description':
                this.validateDescription(field, value);
                break;
            case 'date_debut':
                this.validateDateDebut(field, value);
                break;
            case 'service_id':
                this.validateService(field, value);
                break;
            case 'etat':
                this.validateEtat(field, value);
                break;
            case 'budget':
                this.validateBudget(field, value);
                break;
            case 'beneficiaires_hommes':
            case 'beneficiaires_femmes':
            case 'beneficiaires_enfants':
            case 'beneficiaires_total':
                this.validateBeneficiaires(field, value);
                break;
        }
    }

    validateNom(field, value) {
        if (!value) {
            this.addError(field, 'Le nom du projet est obligatoire.');
            return false;
        }
        if (value.length > 255) {
            this.addError(field, 'Le nom du projet ne peut pas dépasser 255 caractères.');
            return false;
        }
        // Vérifier les caractères dangereux
        if (/<script|javascript:|on\w+=/i.test(value)) {
            this.addError(field, 'Le nom contient des caractères non autorisés.');
            return false;
        }
        return true;
    }

    validateDescription(field, value) {
        // Pour CKEditor, synchroniser d'abord le contenu
        if (window.descriptionEditor && typeof window.descriptionEditor.getData === 'function') {
            const editorData = window.descriptionEditor.getData();
            field.value = editorData;
            value = editorData;
        }
        
        if (!value) {
            this.addError(field, 'La description du projet est obligatoire.');
            return false;
        }
        
        // Nettoyer le HTML pour valider seulement le texte
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = value;
        const textContent = tempDiv.textContent || tempDiv.innerText || '';
        const cleanText = textContent.trim();
        
        if (!cleanText) {
            this.addError(field, 'La description du projet est obligatoire.');
            return false;
        }
        
        if (cleanText.length < 50) {
            this.addError(field, `La description doit contenir au moins 50 caractères. (${cleanText.length}/50)`);
            return false;
        }
        
        // Debug pour identifier les problèmes de validation
        console.log('[DEBUG] CKEditor Validation:', {
            rawValue: value.substring(0, 100) + '...',
            textContent: cleanText.substring(0, 100) + '...',
            textLength: cleanText.length,
            isValid: cleanText.length >= 50
        });
        
        return true;
    }

    validateDateDebut(field, value) {
        if (!value) {
            this.addError(field, 'La date de début est obligatoire.');
            return false;
        }
        
        // Validation basique : vérifier que c'est une date valide
        const dateDebut = new Date(value);
        if (isNaN(dateDebut.getTime())) {
            this.addError(field, 'La date de début doit être une date valide.');
            return false;
        }
        
        return true;
    }

    validateDates() {
        const dateDebutField = this.form.querySelector('[name="date_debut"]');
        const dateFinField = this.form.querySelector('[name="date_fin"]');
        
        // Validation seulement si les deux dates sont présentes
        if (!dateDebutField || !dateFinField || !dateDebutField.value || !dateFinField.value) {
            return true;
        }
        
        const dateDebut = new Date(dateDebutField.value);
        const dateFin = new Date(dateFinField.value);
        
        // Vérifier que les dates sont valides
        if (isNaN(dateDebut.getTime()) || isNaN(dateFin.getTime())) {
            this.addError(dateDebutField, 'Veuillez saisir des dates valides.');
            return false;
        }
        
        // Vérifier que la date de fin est après la date de début
        if (dateFin <= dateDebut) {
            this.addError(dateFinField, 'La date de fin doit être postérieure à la date de début.');
            return false;
        }
        
        // Dates valides, effacer les erreurs
        this.clearFieldError(dateDebutField);
        this.clearFieldError(dateFinField);
        return true;
    }

    validateService(field, value) {
        if (!value) {
            this.addError(field, 'Veuillez sélectionner un service responsable.');
            return false;
        }
        return true;
    }

    validateEtat(field, value) {
        const validStates = ['en cours', 'terminé', 'suspendu'];
        if (!validStates.includes(value)) {
            this.addError(field, 'L\'état du projet doit être : en cours, terminé ou suspendu.');
            return false;
        }
        return true;
    }

    validateBudget(field, value) {
        // Permettre les valeurs vides (nullable)
        if (!value || value.trim() === '') {
            return true;
        }
        
        // Vérifier que c'est un nombre
        const numValue = parseFloat(value);
        if (isNaN(numValue)) {
            this.addError(field, 'Le budget doit être un nombre.');
            return false;
        }
        
        // Vérifier les limites
        if (numValue < 0) {
            this.addError(field, 'Le budget ne peut pas être négatif.');
            return false;
        }
        
        if (numValue > 999999999.99) {
            this.addError(field, 'Le budget ne peut pas dépasser 999 999 999,99 USD.');
            return false;
        }
        
        return true;
    }

    validateBeneficiaires(field, value) {
        if (value && (!Number.isInteger(parseFloat(value)) || parseFloat(value) < 0)) {
            this.addError(field, 'Le nombre de bénéficiaires doit être un nombre entier positif.');
            return false;
        }
        if (value && parseFloat(value) > 1000000) {
            this.addError(field, 'Le nombre de bénéficiaires ne peut pas dépasser 1 000 000.');
            return false;
        }
        return true;
    }

    validateFileUpload(fileInput) {
        const file = fileInput.files[0];
        if (!file) return true;

        // Types de fichiers autorisés
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        
        // Vérifier le type MIME
        if (!allowedTypes.includes(file.type)) {
            this.addError(fileInput, 'L\'image doit être au format : JPG, JPEG, PNG, GIF, WebP ou SVG.');
            return false;
        }

        // Vérifier l'extension
        const extension = file.name.split('.').pop().toLowerCase();
        if (!allowedExtensions.includes(extension)) {
            this.addError(fileInput, 'L\'extension du fichier doit être : jpg, jpeg, png, gif, webp ou svg.');
            return false;
        }

        // Vérifier la taille (10MB max)
        const maxSize = 10 * 1024 * 1024; // 10MB
        if (file.size > maxSize) {
            this.addError(fileInput, 'La taille de l\'image ne peut pas dépasser 10 MB.');
            return false;
        }

        // Vérifier les dimensions
        this.validateImageDimensions(file, fileInput);
        return true;
    }

    validateImageDimensions(file, fileInput) {
        const img = new Image();
        img.onload = () => {
            if (img.width < 400 || img.height < 300) {
                this.addError(fileInput, 'L\'image doit avoir au minimum 400x300 pixels.');
            } else {
                this.clearFieldError(fileInput);
                this.updateImagePreview(file);
            }
        };
        img.src = URL.createObjectURL(file);
    }

    updateImagePreview(file) {
        const preview = document.getElementById('image-preview');
        if (preview) {
            const img = preview.querySelector('img') || document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'w-full h-32 object-cover rounded';
            img.alt = 'Aperçu de l\'image';
            
            if (!preview.querySelector('img')) {
                preview.appendChild(img);
            }
            preview.classList.remove('hidden');
        }
    }

    formatBudget(field) {
        // Nettoyer la valeur pour ne garder que les chiffres et point/virgule
        let value = field.value.replace(/[^\d.,]/g, '');
        value = value.replace(',', '.');
        
        // Si la valeur est valide, ne pas reformater pendant la saisie
        // Le formatage final se fera à l'affichage
        if (value && !isNaN(value)) {
            const number = parseFloat(value);
            // Ne pas dépasser la limite maximum
            if (number > 999999999.99) {
                field.value = '999999999.99';
            }
        }
    }

    calculateTotalBeneficiaires() {
        const hommes = parseInt(this.form.querySelector('[name="beneficiaires_hommes"]')?.value) || 0;
        const femmes = parseInt(this.form.querySelector('[name="beneficiaires_femmes"]')?.value) || 0;
        const enfants = parseInt(this.form.querySelector('[name="beneficiaires_enfants"]')?.value) || 0;
        
        const totalField = this.form.querySelector('[name="beneficiaires_total"]');
        if (totalField) {
            const total = hommes + femmes + enfants;
            totalField.value = total > 0 ? total : '';
        }
    }

    addError(field, message) {
        this.errors.set(field.name, message);
        
        // Ajouter la classe d'erreur
        field.classList.add('border-red-500', 'focus:border-red-500');
        field.classList.remove('border-gray-300', 'focus:border-blue-500');
        
        // Afficher le message d'erreur
        let errorElement = field.parentNode.querySelector('.error-message');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'error-message text-red-500 text-sm mt-1';
            field.parentNode.appendChild(errorElement);
        }
        errorElement.textContent = message;
        
        // Animation
        errorElement.style.opacity = '0';
        errorElement.style.transform = 'translateY(-5px)';
        setTimeout(() => {
            errorElement.style.transition = 'all 0.3s ease';
            errorElement.style.opacity = '1';
            errorElement.style.transform = 'translateY(0)';
        }, 10);
    }

    clearFieldError(field) {
        this.errors.delete(field.name);
        
        // Enlever les classes d'erreur
        field.classList.remove('border-red-500', 'focus:border-red-500');
        field.classList.add('border-gray-300', 'focus:border-blue-500');
        
        // Supprimer le message d'erreur
        const errorElement = field.parentNode.querySelector('.error-message');
        if (errorElement) {
            errorElement.style.transition = 'all 0.3s ease';
            errorElement.style.opacity = '0';
            errorElement.style.transform = 'translateY(-5px)';
            setTimeout(() => errorElement.remove(), 300);
        }
    }

    handleSubmit(e) {
        // Valider tous les champs
        const fields = this.form.querySelectorAll('input, textarea, select');
        fields.forEach(field => this.validateField(field));

        // Validation spéciale pour les dates
        this.validateDates();

        // Si il y a des erreurs, empêcher la soumission
        if (this.errors.size > 0) {
            e.preventDefault();
            
            // Focuser sur le premier champ avec erreur
            const firstErrorField = this.form.querySelector('.border-red-500');
            if (firstErrorField) {
                firstErrorField.focus();
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            // Afficher un message global
            this.showGlobalError(`Veuillez corriger ${this.errors.size} erreur(s) avant de continuer.`);
            return false;
        }

        // Désactiver le bouton de soumission pour éviter les doubles soumissions
        const submitButton = this.form.querySelector('[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enregistrement...';
        }

        return true;
    }

    showGlobalError(message) {
        // Supprimer l'ancien message s'il existe
        const oldAlert = document.querySelector('.global-form-error');
        if (oldAlert) oldAlert.remove();

        // Créer le nouveau message
        const alert = document.createElement('div');
        alert.className = 'global-form-error bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
        alert.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>${message}</span>
            </div>
        `;

        // Insérer le message au début du formulaire
        this.form.insertBefore(alert, this.form.firstChild);
        
        // Faire défiler vers le message
        alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Supprimer automatiquement après 5 secondes
        setTimeout(() => alert.remove(), 5000);
    }
}

// Initialisation automatique quand le DOM est prêt
document.addEventListener('DOMContentLoaded', function() {
    const projectForm = document.querySelector('form[data-form="project"]');
    if (projectForm) {
        new ProjectFormValidator(projectForm);
        console.log('✅ Validation du formulaire de projet initialisée');
    }
});

// CSS pour les animations et le drag & drop
const styleSheet = document.createElement('style');
styleSheet.textContent = `
    .file-upload-zone {
        transition: all 0.3s ease;
    }
    
    .file-upload-zone.drag-over {
        border-color: #3b82f6;
        background-color: #eff6ff;
        transform: scale(1.02);
    }
    
    .error-message {
        animation: shake 0.5s ease-in-out;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .global-form-error {
        animation: slideDown 0.3s ease-out;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(styleSheet);
