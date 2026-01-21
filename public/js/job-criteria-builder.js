/**
 * Job Criteria Builder - Interface dynamique pour la gestion des critères d'évaluation
 * Version: 1.0.0
 */

class JobCriteriaBuilder {
    constructor() {
        this.criteria = [];
        this.nextId = 1;
        this.isInitialized = false;

        // Templates prédéfinis
        this.templates = {
            developer: [{
                    type: 'select',
                    question: 'Niveau d\'expérience en développement',
                    options: ['Junior (0-2 ans)', 'Confirmé (3-5 ans)', 'Senior (5+ ans)', 'Lead/Architect (7+ ans)'],
                    required: true,
                    description: 'Sélectionnez votre niveau d\'expérience principal'
                },
                {
                    type: 'select',
                    question: 'Framework principal maîtrisé',
                    options: ['Laravel', 'Symfony', 'Vue.js', 'React', 'Angular', 'Node.js'],
                    required: false,
                    description: 'Quel framework maîtrisez-vous le mieux ?'
                },
                {
                    type: 'radio',
                    question: 'Préférence de travail',
                    options: ['100% présentiel', 'Hybride (2-3j télétravail/semaine)', '100% télétravail'],
                    required: true
                }
            ],
            researcher: [{
                    type: 'textarea',
                    question: 'Décrivez votre expérience de terrain',
                    required: true,
                    description: 'Détaillez vos missions de terrain et collecte de données'
                },
                {
                    type: 'select',
                    question: 'Logiciels statistiques maîtrisés',
                    options: ['SPSS', 'R', 'Python/Pandas', 'SAS', 'Stata', 'Excel avancé'],
                    required: true
                },
                {
                    type: 'radio',
                    question: 'Expérience avec les communautés rurales',
                    options: ['Très expérimenté (5+ missions)', 'Expérimenté (2-4 missions)', 'Quelque expérience (1 mission)', 'Débutant'],
                    required: true
                }
            ],
            manager: [{
                    type: 'select',
                    question: 'Taille d\'équipe managée',
                    options: ['1-3 personnes', '4-10 personnes', '11-20 personnes', '20+ personnes', 'Pas d\'expérience management'],
                    required: true
                },
                {
                    type: 'textarea',
                    question: 'Décrivez votre approche de management',
                    required: false,
                    description: 'Quel est votre style de leadership et de gestion d\'équipe ?'
                }
            ],
            communication: [{
                    type: 'select',
                    question: 'Outils de création maîtrisés',
                    options: ['Suite Adobe (Photoshop, Illustrator)', 'Canva/Figma', 'Outils vidéo (Premiere, After Effects)', 'WordPress/CMS'],
                    required: true
                },
                {
                    type: 'radio',
                    question: 'Spécialité privilégiée',
                    options: ['Réseaux sociaux', 'Création graphique', 'Rédaction/Contenu', 'Stratégie digitale'],
                    required: true
                }
            ]
        };

        this.init();
    }

    /**
     * Initialisation du builder
     */
    init() {
        if (this.isInitialized) return;

        this.loadExistingCriteria();
        this.bindEvents();
        this.isInitialized = true;

        console.log('Job Criteria Builder initialized');
    }

    /**
     * Charger les critères existants (pour l'édition)
     */
    loadExistingCriteria() {
        const hiddenInput = document.getElementById('criteria-input');
        if (hiddenInput && hiddenInput.value) {
            try {
                const existingCriteria = JSON.parse(hiddenInput.value);
                if (Array.isArray(existingCriteria) && existingCriteria.length > 0) {
                    this.criteria = existingCriteria.map((criterion, index) => ({
                        ...criterion,
                        id: ++this.nextId
                    }));
                    this.render();
                    this.updatePreview();
                }
            } catch (error) {
                console.warn('Erreur lors du chargement des critères existants:', error);
            }
        }
    }

    /**
     * Lier les événements
     */
    bindEvents() {
        // Templates rapides
        document.querySelectorAll('.template-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const template = e.target.dataset.template;
                this.handleTemplateClick(template, btn);
            });
        });

        // Ajouter critère personnalisé
        const addBtn = document.getElementById('add-criteria-btn');
        if (addBtn) {
            addBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.addCustomCriterion();
            });
        }

        // Auto-suggestions basées sur le titre
        const titleInput = document.getElementById('title');
        if (titleInput) {
            let titleTimeout;
            titleInput.addEventListener('input', () => {
                clearTimeout(titleTimeout);
                titleTimeout = setTimeout(() => {
                    this.suggestFromTitle();
                }, 1000);
            });
        }

        // Gérer la soumission du formulaire
        const form = document.getElementById('job-offer-form');
        if (form) {
            form.addEventListener('submit', () => {
                this.updateHiddenInput();
            });
        }
    }

    /**
     * Gérer le clic sur un template
     */
    handleTemplateClick(templateName, btnElement) {
        // Réinitialiser l'état actif des boutons
        document.querySelectorAll('.template-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        if (templateName === 'smart') {
            this.loadSmartSuggestions();
        } else if (templateName === 'clear') {
            this.clearAllCriteria();
        } else {
            btnElement.classList.add('active');
            this.loadTemplate(templateName);
        }
    }

    /**
     * Charger un template prédéfini
     */
    loadTemplate(templateName) {
        if (this.templates[templateName]) {
            // Confirmer si des critères existent déjà
            if (this.criteria.length > 0) {
                if (!confirm('Remplacer les critères existants par le template ?')) {
                    return;
                }
            }

            this.criteria = this.templates[templateName].map(criterion => ({
                ...criterion,
                id: ++this.nextId
            }));

            this.render();
            this.updatePreview();
            this.showSuccessMessage(`Template "${templateName}" chargé avec succès`);
        }
    }

    /**
     * Charger les suggestions intelligentes
     */
    async loadSmartSuggestions() {
        const titleInput = document.getElementById('title');
        const title = titleInput ? titleInput.value.trim() : '';

        if (!title) {
            alert('Veuillez d\'abord saisir un titre pour obtenir des suggestions intelligentes');
            return;
        }

        this.showLoadingMessage('Génération de suggestions intelligentes...');

        try {
            const response = await fetch('/admin/job-offers/suggest-criteria', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ title: title })
            });

            if (!response.ok) {
                throw new Error('Erreur réseau');
            }

            const suggestions = await response.json();

            if (suggestions && suggestions.length > 0) {
                // Confirmer si des critères existent déjà
                if (this.criteria.length > 0) {
                    if (!confirm('Remplacer les critères existants par les suggestions ?')) {
                        this.hideLoadingMessage();
                        return;
                    }
                }

                this.criteria = suggestions.map(criterion => ({
                    ...criterion,
                    id: ++this.nextId
                }));

                this.render();
                this.updatePreview();
                this.showSuccessMessage('Suggestions intelligentes chargées');
            } else {
                this.showErrorMessage('Aucune suggestion trouvée pour ce titre');
            }
        } catch (error) {
            console.error('Erreur lors du chargement des suggestions:', error);
            this.showErrorMessage('Erreur lors du chargement des suggestions');
        } finally {
            this.hideLoadingMessage();
        }
    }

    /**
     * Ajouter un critère personnalisé
     */
    addCustomCriterion() {
        const newCriterion = {
            id: ++this.nextId,
            type: 'text',
            question: '',
            required: false,
            options: [],
            description: ''
        };

        this.criteria.push(newCriterion);
        this.render();
        this.updatePreview();

        // Focus sur le champ question du nouveau critère
        setTimeout(() => {
            const newCriterionElement = document.querySelector(`[data-criterion-id="${newCriterion.id}"] .criterion-question`);
            if (newCriterionElement) {
                newCriterionElement.focus();
            }
        }, 100);
    }

    /**
     * Supprimer tous les critères
     */
    clearAllCriteria() {
        if (this.criteria.length === 0) return;

        if (confirm('Supprimer tous les critères ?')) {
            this.criteria = [];
            this.render();
            this.updatePreview();
            this.showSuccessMessage('Tous les critères ont été supprimés');
        }
    }

    /**
     * Rendre l'interface
     */
    render() {
        const container = document.getElementById('criteria-container');
        if (!container) return;

        container.innerHTML = '';

        this.criteria.forEach((criterion, index) => {
            const criterionHtml = this.buildCriterionHtml(criterion, index);
            container.insertAdjacentHTML('beforeend', criterionHtml);
        });

        this.bindCriterionEvents();
        this.updateHiddenInput();
    }

    /**
     * Construire le HTML d'un critère
     */
    buildCriterionHtml(criterion, index) {
        const optionsDisplay = (criterion.type === 'select' || criterion.type === 'radio') ? 'block' : 'none';
        const optionsValue = (criterion.options || []).join('\n');

        return `
        <div class="criterion-item p-4 border border-gray-200 rounded-lg relative" data-criterion-id="${criterion.id}">
            <div class="drag-handle" title="Faire glisser pour réorganiser">
                <i class="fas fa-grip-vertical"></i>
            </div>
            
            <div class="flex items-start justify-between mb-3">
                <h5 class="font-medium text-gray-900 flex items-center">
                    <i class="fas fa-clipboard-list mr-2 text-purple-500"></i>
                    Critère ${index + 1}
                </h5>
                <button type="button" class="remove-criterion text-red-500 hover:text-red-700 transition-colors" title="Supprimer ce critère">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Question <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           class="criterion-question w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors" 
                           value="${this.escapeHtml(criterion.question || '')}" 
                           placeholder="Ex: Quelle est votre expérience avec...">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de réponse</label>
                    <select class="criterion-type w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                        <option value="text" ${criterion.type === 'text' ? 'selected' : ''}>📝 Texte court</option>
                        <option value="textarea" ${criterion.type === 'textarea' ? 'selected' : ''}>📄 Texte long</option>
                        <option value="select" ${criterion.type === 'select' ? 'selected' : ''}>📋 Liste déroulante</option>
                        <option value="radio" ${criterion.type === 'radio' ? 'selected' : ''}>⚪ Choix multiple</option>
                    </select>
                </div>
            </div>
            
            <div class="options-container mb-4" style="display: ${optionsDisplay}">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Options de réponse <span class="text-red-500">*</span>
                </label>
                <textarea class="criterion-options w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors" 
                          rows="3"
                          placeholder="Option 1&#10;Option 2&#10;Option 3">${this.escapeHtml(optionsValue)}</textarea>
                <p class="text-xs text-gray-500 mt-1">Une option par ligne</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center space-x-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="criterion-required mr-2 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" ${criterion.required ? 'checked' : ''}>
                        <span class="text-sm text-gray-700 font-medium">Question obligatoire</span>
                    </label>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (optionnelle)</label>
                    <input type="text" 
                           class="criterion-description w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors" 
                           value="${this.escapeHtml(criterion.description || '')}" 
                           placeholder="Aide ou précision pour le candidat">
                </div>
            </div>
        </div>
        `;
    }

    /**
     * Lier les événements des critères
     */
    bindCriterionEvents() {
        // Supprimer un critère
        document.querySelectorAll('.remove-criterion').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const criterionElement = e.target.closest('.criterion-item');
                const criterionId = parseInt(criterionElement.dataset.criterionId);
                this.removeCriterion(criterionId);
            });
        });

        // Changer le type de critère
        document.querySelectorAll('.criterion-type').forEach(select => {
            select.addEventListener('change', (e) => {
                const criterionElement = e.target.closest('.criterion-item');
                const optionsContainer = criterionElement.querySelector('.options-container');
                const newType = e.target.value;

                if (newType === 'select' || newType === 'radio') {
                    optionsContainer.style.display = 'block';
                } else {
                    optionsContainer.style.display = 'none';
                }

                this.updateCriterionFromDOM();
                this.updatePreview();
            });
        });

        // Mettre à jour les critères lors des modifications
        const inputs = [
            '.criterion-question',
            '.criterion-options',
            '.criterion-description',
            '.criterion-required'
        ];

        inputs.forEach(selector => {
            document.querySelectorAll(selector).forEach(input => {
                const eventType = input.type === 'checkbox' ? 'change' : 'input';
                input.addEventListener(eventType, () => {
                    this.updateCriterionFromDOM();
                    this.updatePreview();
                });
            });
        });
    }

    /**
     * Supprimer un critère
     */
    removeCriterion(criterionId) {
        const index = this.criteria.findIndex(c => c.id === criterionId);
        if (index !== -1) {
            this.criteria.splice(index, 1);
            this.render();
            this.updatePreview();
        }
    }

    /**
     * Mettre à jour les critères depuis le DOM
     */
    updateCriterionFromDOM() {
        document.querySelectorAll('.criterion-item').forEach(element => {
            const criterionId = parseInt(element.dataset.criterionId);
            const criterion = this.criteria.find(c => c.id === criterionId);

            if (criterion) {
                criterion.question = element.querySelector('.criterion-question').value;
                criterion.type = element.querySelector('.criterion-type').value;
                criterion.required = element.querySelector('.criterion-required').checked;
                criterion.description = element.querySelector('.criterion-description').value;

                const optionsTextarea = element.querySelector('.criterion-options');
                if (optionsTextarea) {
                    criterion.options = optionsTextarea.value
                        .split('\n')
                        .map(opt => opt.trim())
                        .filter(opt => opt.length > 0);
                }
            }
        });
    }

    /**
     * Mettre à jour le preview
     */
    updatePreview() {
        const preview = document.getElementById('criteria-preview');
        const content = document.getElementById('preview-content');

        if (!preview || !content) return;

        if (this.criteria.length === 0) {
            preview.classList.add('hidden');
            return;
        }

        preview.classList.remove('hidden');
        content.innerHTML = '';

        this.criteria.forEach((criterion, index) => {
            if (criterion.question && criterion.question.trim()) {
                const previewHtml = this.buildPreviewHtml(criterion, index);
                content.insertAdjacentHTML('beforeend', previewHtml);
            }
        });
    }

    /**
     * Construire le HTML du preview
     */
    buildPreviewHtml(criterion, index) {
            let inputHtml = '';

            switch (criterion.type) {
                case 'text':
                    inputHtml = `<input type="text" class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-50" placeholder="Réponse..." disabled>`;
                    break;

                case 'textarea':
                    inputHtml = `<textarea class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-50" rows="3" placeholder="Réponse détaillée..." disabled></textarea>`;
                    break;

                case 'select':
                    const selectOptions = (criterion.options || [])
                        .map(opt => `<option>${this.escapeHtml(opt)}</option>`)
                        .join('');
                    inputHtml = `
                    <select class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-50" disabled>
                        <option>Choisir une option</option>
                        ${selectOptions}
                    </select>`;
                    break;

                case 'radio':
                    inputHtml = (criterion.options || [])
                        .map(opt => `
                        <label class="flex items-center space-x-2 mb-2 cursor-pointer">
                            <input type="radio" name="preview_${index}" class="text-purple-600" disabled>
                            <span class="text-gray-700">${this.escapeHtml(opt)}</span>
                        </label>
                    `).join('');
                    break;
            }

            return `
        <div class="preview-field">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                ${this.escapeHtml(criterion.question)}
                ${criterion.required ? '<span class="text-red-500">*</span>' : ''}
            </label>
            ${inputHtml}
            ${criterion.description ? `<p class="text-xs text-gray-500 mt-1">${this.escapeHtml(criterion.description)}</p>` : ''}
        </div>
        `;
    }

    /**
     * Suggestions basées sur le titre
     */
    suggestFromTitle() {
        const titleInput = document.getElementById('title');
        if (!titleInput) return;

        const title = titleInput.value.toLowerCase();
        let suggestedTemplate = null;

        if (title.includes('développeur') || title.includes('developer') || title.includes('programmeur')) {
            suggestedTemplate = 'developer';
        } else if (title.includes('chercheur') || title.includes('recherche')) {
            suggestedTemplate = 'researcher';
        } else if (title.includes('manager') || title.includes('chef') || title.includes('responsable')) {
            suggestedTemplate = 'manager';
        } else if (title.includes('communication') || title.includes('marketing')) {
            suggestedTemplate = 'communication';
        }

        if (suggestedTemplate && this.criteria.length === 0) {
            this.showSuggestionNotification(`Template "${suggestedTemplate}" suggéré pour ce type de poste`, suggestedTemplate);
        }
    }

    /**
     * Mettre à jour l'input caché
     */
    updateHiddenInput() {
        this.updateCriterionFromDOM();
        
        const hiddenInput = document.getElementById('criteria-input');
        if (hiddenInput) {
            // Nettoyer les critères avant sauvegarde
            const cleanCriteria = this.criteria
                .filter(criterion => criterion.question && criterion.question.trim())
                .map(criterion => {
                    const clean = {
                        type: criterion.type,
                        question: criterion.question.trim(),
                        required: Boolean(criterion.required)
                    };
                    
                    if (criterion.description && criterion.description.trim()) {
                        clean.description = criterion.description.trim();
                    }
                    
                    if (criterion.type === 'select' || criterion.type === 'radio') {
                        clean.options = (criterion.options || [])
                            .filter(opt => opt && opt.trim())
                            .map(opt => opt.trim());
                    }
                    
                    return clean;
                });
            
            hiddenInput.value = JSON.stringify(cleanCriteria);
        }
    }

    /**
     * Utilitaires d'interface
     */
    showSuccessMessage(message) {
        this.showMessage(message, 'success');
    }

    showErrorMessage(message) {
        this.showMessage(message, 'error');
    }

    showLoadingMessage(message) {
        this.showMessage(`<i class="loading-spinner mr-2"></i>${message}`, 'info');
    }

    hideLoadingMessage() {
        const existing = document.querySelector('.criteria-message');
        if (existing) {
            existing.remove();
        }
    }

    showMessage(message, type = 'info') {
        // Supprimer le message existant
        const existing = document.querySelector('.criteria-message');
        if (existing) {
            existing.remove();
        }

        const colors = {
            success: 'bg-green-50 text-green-800 border-green-200',
            error: 'bg-red-50 text-red-800 border-red-200',
            info: 'bg-blue-50 text-blue-800 border-blue-200'
        };

        const messageDiv = document.createElement('div');
        messageDiv.className = `criteria-message p-3 rounded-lg border mb-4 ${colors[type]}`;
        messageDiv.innerHTML = message;

        const container = document.getElementById('criteria-container');
        if (container && container.parentNode) {
            container.parentNode.insertBefore(messageDiv, container);
        }

        // Auto-supprimer après 5 secondes (sauf pour loading)
        if (type !== 'info') {
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.remove();
                }
            }, 5000);
        }
    }

    showSuggestionNotification(message, templateName) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-purple-100 border border-purple-200 text-purple-800 px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm';
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-lightbulb mr-2"></i>
                    <span class="text-sm">${message}</span>
                </div>
                <div class="ml-3 flex space-x-2">
                    <button class="apply-suggestion text-purple-600 hover:text-purple-800 text-sm font-medium" 
                            data-template="${templateName}">Appliquer</button>
                    <button class="dismiss-suggestion text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(notification);

        // Événements
        notification.querySelector('.apply-suggestion').addEventListener('click', () => {
            this.loadTemplate(templateName);
            notification.remove();
        });

        notification.querySelector('.dismiss-suggestion').addEventListener('click', () => {
            notification.remove();
        });

        // Auto-supprimer après 10 secondes
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 10000);
    }

    /**
     * Échapper le HTML
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialisation automatique
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('criteria-container')) {
        window.jobCriteriaBuilder = new JobCriteriaBuilder();
    }
});

// Export pour usage externe
if (typeof module !== 'undefined' && module.exports) {
    module.exports = JobCriteriaBuilder;
}