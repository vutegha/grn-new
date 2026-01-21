// Composant pour la gestion dynamique des listes d'exigences
class DynamicRequirements {
    constructor(containerId, options = {}) {
        this.container = document.getElementById(containerId);
        this.requirements = [];
        this.options = {
            inputPlaceholder: options.inputPlaceholder || 'Saisissez une exigence...',
            addButtonText: options.addButtonText || 'Ajouter',
            hiddenInputName: options.hiddenInputName || 'requirements',
            maxItems: options.maxItems || 20,
            ...options
        };
        
        this.init();
    }
    
    init() {
        this.createHTML();
        this.bindEvents();
        this.loadExistingData();
    }
    
    createHTML() {
        this.container.innerHTML = `
            <div class="dynamic-requirements">
                <div class="flex gap-2 mb-4">
                    <input type="text" 
                           id="requirement-input" 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200" 
                           placeholder="${this.options.inputPlaceholder}"
                           maxlength="500">
                    <button type="button" 
                            id="add-requirement-btn" 
                            class="bg-iri-primary hover:bg-iri-secondary text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        ${this.options.addButtonText}
                    </button>
                </div>
                
                <div id="requirements-list" class="space-y-2 mb-4">
                    <!-- Les exigences apparaîtront ici -->
                </div>
                
                <input type="hidden" name="${this.options.hiddenInputName}" id="requirements-json" value="[]">
                
                <div class="text-sm text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    <span id="requirements-count">0</span> exigence(s) ajoutée(s) (maximum ${this.options.maxItems})
                </div>
            </div>
        `;
    }
    
    bindEvents() {
        const input = document.getElementById('requirement-input');
        const addBtn = document.getElementById('add-requirement-btn');
        
        // Ajouter par clic sur le bouton
        addBtn.addEventListener('click', () => this.addRequirement());
        
        // Ajouter par Entrée
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.addRequirement();
            }
        });
    }
    
    addRequirement() {
        const input = document.getElementById('requirement-input');
        const text = input.value.trim();
        
        if (!text) {
            this.showMessage('Veuillez saisir une exigence.', 'error');
            return;
        }
        
        if (this.requirements.length >= this.options.maxItems) {
            this.showMessage(`Maximum ${this.options.maxItems} exigences autorisées.`, 'error');
            return;
        }
        
        if (this.requirements.includes(text)) {
            this.showMessage('Cette exigence existe déjà.', 'error');
            return;
        }
        
        this.requirements.push(text);
        input.value = '';
        this.updateDisplay();
        this.updateHiddenInput();
        this.showMessage('Exigence ajoutée avec succès.', 'success');
    }
    
    removeRequirement(index) {
        this.requirements.splice(index, 1);
        this.updateDisplay();
        this.updateHiddenInput();
        this.showMessage('Exigence supprimée.', 'success');
    }
    
    updateDisplay() {
        const list = document.getElementById('requirements-list');
        const count = document.getElementById('requirements-count');
        
        if (this.requirements.length === 0) {
            list.innerHTML = `
                <div class="text-gray-500 text-center py-4 border-2 border-dashed border-gray-200 rounded-lg">
                    <i class="fas fa-list-ul text-2xl mb-2"></i>
                    <p>Aucune exigence ajoutée pour le moment</p>
                </div>
            `;
        } else {
            list.innerHTML = this.requirements.map((req, index) => `
                <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg p-3 group hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex items-start space-x-3 flex-1">
                        <i class="fas fa-check-circle text-iri-primary mt-0.5"></i>
                        <span class="text-gray-800 flex-1">${this.escapeHtml(req)}</span>
                    </div>
                    <button type="button" 
                            onclick="dynamicRequirements.removeRequirement(${index})"
                            class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1 rounded transition-colors duration-200 opacity-0 group-hover:opacity-100"
                            title="Supprimer cette exigence">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            `).join('');
        }
        
        count.textContent = this.requirements.length;
    }
    
    updateHiddenInput() {
        const hiddenInput = document.getElementById('requirements-json');
        hiddenInput.value = JSON.stringify(this.requirements);
    }
    
    loadExistingData() {
        // Charger les données existantes si elles existent (pour l'édition)
        const hiddenInput = document.getElementById('requirements-json');
        if (hiddenInput.value && hiddenInput.value !== '[]') {
            try {
                this.requirements = JSON.parse(hiddenInput.value) || [];
                this.updateDisplay();
            } catch (e) {
                console.error('Erreur lors du chargement des données existantes:', e);
            }
        } else {
            this.updateDisplay();
        }
    }
    
    setRequirements(requirements) {
        this.requirements = Array.isArray(requirements) ? requirements : [];
        this.updateDisplay();
        this.updateHiddenInput();
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    showMessage(message, type = 'info') {
        // Supprimer les anciens messages
        const existingMessage = this.container.querySelector('.dynamic-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `dynamic-message p-2 rounded mb-2 ${
            type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' : 
            type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
            'bg-blue-100 text-blue-800 border border-blue-200'
        }`;
        messageDiv.innerHTML = `<i class="fas fa-${type === 'error' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'} mr-1"></i>${message}`;
        
        this.container.insertBefore(messageDiv, this.container.firstChild);
        
        // Supprimer automatiquement après 3 secondes
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 3000);
    }
}

// Variable globale pour l'instance
let dynamicRequirements;
