@extends('layouts.admin')

@section('breadcrumbs')
<nav class="flex mb-8" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/80 hover:text-white transition-colors duration-200 text-sm font-medium">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/40 text-xs"></i>
                <a href="{{ route('admin.job-offers.index') }}" class="text-white/80 hover:text-white transition-colors duration-200 text-sm font-medium">
                    Offres d'Emploi
                </a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/40 text-xs"></i>
                <span class="text-white font-medium text-sm">Nouvelle Offre</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Créer une Offre d\'Emploi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Créer une Offre d'Emploi</h1>
                <p class="text-gray-600">Remplissez les informations pour créer une nouvelle offre</p>
            </div>
            <a href="{{ route('admin.job-offers.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.job-offers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="job-offer-form">
        @csrf

        <!-- Informations générales -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-6 py-4">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-info-circle mr-3"></i>
                    Informations générales
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="lg:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Titre de l'offre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200 @error('title') border-red-300 @enderror"
                               placeholder="Ex: Développeur Full Stack Senior"
                               required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Type de contrat <span class="text-red-500">*</span>
                        </label>
                        <select id="type" 
                                name="type" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200 @error('type') border-red-300 @enderror"
                                required>
                            <option value="">Sélectionner un type</option>
                            <option value="full-time" {{ old('type') == 'full-time' ? 'selected' : '' }}>Temps plein</option>
                            <option value="part-time" {{ old('type') == 'part-time' ? 'selected' : '' }}>Temps partiel</option>
                            <option value="contract" {{ old('type') == 'contract' ? 'selected' : '' }}>Contrat</option>
                            <option value="internship" {{ old('type') == 'internship' ? 'selected' : '' }}>Stage</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="source" class="block text-sm font-medium text-gray-700 mb-2">
                            Source <span class="text-red-500">*</span>
                        </label>
                        <select id="source" 
                                name="source" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200 @error('source') border-red-300 @enderror"
                                required>
                            <option value="">Sélectionner une source</option>
                            <option value="internal" {{ old('source') == 'internal' ? 'selected' : '' }}>🏢 Interne</option>
                            <option value="partner" {{ old('source') == 'partner' ? 'selected' : '' }}>🤝 Partenaire</option>
                        </select>
                        @error('source')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                            Localisation <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="location" 
                               name="location" 
                               value="{{ old('location') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200 @error('location') border-red-300 @enderror"
                               placeholder="Ex: Paris, France ou Télétravail"
                               required>
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="positions_available" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de postes <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="positions_available" 
                               name="positions_available" 
                               value="{{ old('positions_available', 1) }}"
                               min="1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200 @error('positions_available') border-red-300 @enderror"
                               required>
                        @error('positions_available')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div id="partner-fields" class="space-y-4" style="display: none;">
                    <div>
                        <label for="partner_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom du partenaire
                        </label>
                        <input type="text" 
                               id="partner_name" 
                               name="partner_name" 
                               value="{{ old('partner_name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200"
                               placeholder="Nom de l'entreprise partenaire">
                    </div>
                    <div>
                        <label for="partner_logo" class="block text-sm font-medium text-gray-700 mb-2">
                            Logo du partenaire
                        </label>
                        <input type="file" 
                               id="partner_logo" 
                               name="partner_logo" 
                               accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200">
                        <p class="mt-1 text-sm text-gray-500 flex items-start">
                            <i class="fas fa-info-circle mr-1 mt-0.5 text-blue-500"></i>
                            <span>
                                <strong>Formats acceptés:</strong> JPG, PNG, SVG (max 2MB)<br>
                                <span class="text-gray-400">Ajoutez le logo de l'entreprise partenaire pour personnaliser l'offre</span>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description et exigences -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-iri-secondary to-iri-primary px-6 py-4">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-file-alt mr-3"></i>
                    Description du poste
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description détaillée <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="6"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200 @error('description') border-red-300 @enderror"
                              placeholder="Décrivez le poste, les missions, l'environnement de travail..."
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="requirements-container" class="block text-sm font-medium text-gray-700 mb-2">
                        Exigences et qualifications <span class="text-red-500">*</span>
                    </label>
                    <div id="requirements-container"></div>
                    @error('requirements')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        <i class="fas fa-lightbulb mr-1"></i>
                        Ajoutez chaque exigence une par une. Exemple : "Minimum 3 ans d'expérience", "Maîtrise de Laravel", etc.
                    </p>
                    <p class="mt-1 text-xs text-gray-400">
                        <i class="fas fa-magic mr-1"></i>
                        <strong>Astuce :</strong> Vous pouvez coller une liste à puces (-, *, •) ou plusieurs lignes et elles seront automatiquement séparées !
                    </p>
                </div>

                <div>
                    <label for="benefits" class="block text-sm font-medium text-gray-700 mb-2">
                        Avantages et bénéfices
                    </label>
                    <textarea id="benefits" 
                              name="benefits" 
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200"
                              placeholder="Décrivez les avantages du poste (télétravail, formation, évolution...)">{{ old('benefits') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Rémunération et conditions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-iri-accent to-iri-gold px-6 py-4">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-euro-sign mr-3"></i>
                    Rémunération et conditions
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="salary_min" class="block text-sm font-medium text-gray-700 mb-2">
                            Salaire minimum (€/an)
                        </label>
                        <input type="number" 
                               id="salary_min" 
                               name="salary_min" 
                               value="{{ old('salary_min') }}"
                               min="0"
                               step="1000"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200"
                               placeholder="Ex: 35000">
                    </div>

                    <div>
                        <label for="salary_max" class="block text-sm font-medium text-gray-700 mb-2">
                            Salaire maximum (€/an)
                        </label>
                        <input type="number" 
                               id="salary_max" 
                               name="salary_max" 
                               value="{{ old('salary_max') }}"
                               min="0"
                               step="1000"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200"
                               placeholder="Ex: 50000">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="application_deadline" class="block text-sm font-medium text-gray-700 mb-2">
                            Date limite de candidature <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="application_deadline" 
                               name="application_deadline" 
                               value="{{ old('application_deadline') }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200 @error('application_deadline') border-red-300 @enderror"
                               required>
                        @error('application_deadline')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email de contact <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="contact_email" 
                               name="contact_email" 
                               value="{{ old('contact_email') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200 @error('contact_email') border-red-300 @enderror"
                               placeholder="Ex: recrutement@entreprise.com"
                               required>
                        @error('contact_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Email pour recevoir les candidatures</p>
                    </div>
                </div>

                <!-- Document d'appel d'offre -->
                <div>
                    <label for="document_appel_offre" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                        Document d'appel d'offre
                        <span class="text-xs font-normal text-gray-500">(optionnel)</span>
                    </label>
                    <div class="flex items-start space-x-4">
                        <div class="flex-1">
                            <input type="file" 
                                   id="document_appel_offre" 
                                   name="document_appel_offre" 
                                   accept=".pdf,.doc,.docx,.odt"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-iri-primary/10 file:text-iri-primary hover:file:bg-iri-primary/20 @error('document_appel_offre') border-red-300 @enderror">
                            @error('document_appel_offre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500 flex items-start">
                                <i class="fas fa-info-circle mr-1 mt-0.5 text-blue-500"></i>
                                <span>
                                    <strong>Formats acceptés:</strong> PDF, DOC, DOCX, ODT (max 10MB)<br>
                                    <span class="text-gray-400">Document détaillé de l'offre que les candidats pourront télécharger</span>
                                </span>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <div id="document-preview" class="w-16 h-16 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center hidden">
                                <i class="fas fa-file-alt text-gray-400 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Options -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-cog mr-3"></i>
                    Options et publication
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Statut de publication
                        </label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200">
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>📝 Brouillon</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>✅ Publier immédiatement</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input id="is_featured" 
                                   name="is_featured" 
                                   type="checkbox" 
                                   value="1"
                                   {{ old('is_featured') ? 'checked' : '' }}
                                   class="h-4 w-4 text-iri-primary focus:ring-iri-primary border-gray-300 rounded">
                        </div>
                        <div class="ml-3">
                            <label for="is_featured" class="text-sm font-medium text-gray-700">
                                <i class="fas fa-star text-yellow-500 mr-1"></i>
                                Marquer comme offre vedette
                            </label>
                            <p class="text-xs text-gray-500">Les offres vedettes apparaissent en premier</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
            <a href="{{ route('admin.job-offers.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                <i class="fas fa-times mr-2"></i>
                Annuler
            </a>
            
            <div class="flex space-x-3">
                <button type="submit" 
                        name="action" 
                        value="draft"
                        class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Sauvegarder en brouillon
                </button>
                <button type="submit" 
                        name="action" 
                        value="publish"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Publier l'offre
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sourceSelect = document.getElementById('source');
    const partnerFields = document.getElementById('partner-fields');
    
    // Show/hide partner fields based on source selection
    function togglePartnerFields() {
        if (sourceSelect.value === 'partner') {
            partnerFields.style.display = 'block';
        } else {
            partnerFields.style.display = 'none';
        }
    }
    
    sourceSelect.addEventListener('change', togglePartnerFields);
    togglePartnerFields(); // Initial check

    // Salary validation
    const salaryMin = document.getElementById('salary_min');
    const salaryMax = document.getElementById('salary_max');
    
    function validateSalary() {
        const min = parseFloat(salaryMin.value);
        const max = parseFloat(salaryMax.value);
        
        if (min && max && min > max) {
            salaryMax.setCustomValidity('Le salaire maximum doit être supérieur au minimum');
        } else {
            salaryMax.setCustomValidity('');
        }
    }
    
    salaryMin.addEventListener('input', validateSalary);
    salaryMax.addEventListener('input', validateSalary);

    // Rich text editor for textareas (simple implementation)
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });

    // Form submission handling avec validation améliorée
    const form = document.getElementById('job-offer-form');
    const submitButtons = form.querySelectorAll('button[type="submit"]');
    
    form.addEventListener('submit', function(e) {
        // Validation additionnelle avant soumission
        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        const requirements = document.getElementById('requirements').value.trim();
        const type = document.getElementById('type').value;
        const location = document.getElementById('location').value.trim();
        const deadline = document.getElementById('application_deadline').value;
        const contactEmail = document.getElementById('contact_email').value.trim();
        const source = document.getElementById('source').value;
        
        // Vérifications de base
        if (!title || !description || !requirements || !type || !location || !deadline || !contactEmail || !source) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires.');
            return false;
        }
        
        // Vérification email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(contactEmail)) {
            e.preventDefault();
            alert('Veuillez saisir une adresse email valide.');
            return false;
        }
        
        // Vérification source/partenaire
        if (source === 'partner') {
            const partnerName = document.getElementById('partner_name').value.trim();
            if (!partnerName) {
                e.preventDefault();
                alert('Le nom du partenaire est requis quand la source est "Partenaire".');
                return false;
            }
        }
        
        // Valider les exigences
        if (dynamicRequirements && dynamicRequirements.requirements.length === 0) {
            e.preventDefault();
            alert('Veuillez ajouter au moins une exigence pour le poste.');
            return false;
        }
        
        const clickedButton = e.submitter;
        if (clickedButton) {
            clickedButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Traitement...';
            clickedButton.disabled = true;
            
            submitButtons.forEach(btn => {
                if (btn !== clickedButton) {
                    btn.disabled = true;
                }
            });
        }
    });
    
    // Initialiser le composant de gestion des exigences
    dynamicRequirements = new DynamicRequirements('requirements-container', {
        inputPlaceholder: 'Saisissez une exigence (ex: Minimum 3 ans d\'expérience)',
        addButtonText: 'Ajouter',
        hiddenInputName: 'requirements',
        maxItems: 15
    });
});

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
        addBtn.addEventListener('click', () => this.processInput());
        
        // Ajouter par Entrée
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.processInput();
            }
        });
        
        // Traitement automatique lors de la perte de focus
        input.addEventListener('blur', () => {
            if (input.value.trim()) {
                this.processInput();
            }
        });
    }
    
    // Nouvelle méthode pour traiter l'input (simple ou multiple)
    processInput() {
        const input = document.getElementById('requirement-input');
        const text = input.value.trim();
        
        if (!text) {
            this.showMessage('Veuillez saisir une exigence.', 'error');
            return;
        }
        
        // Détecter si c'est une liste à puces ou multi-lignes
        const requirements = this.parseMultipleRequirements(text);
        
        if (requirements.length > 1) {
            this.addMultipleRequirements(requirements);
        } else if (requirements.length === 1) {
            this.addSingleRequirement(requirements[0]);
        }
        
        input.value = '';
    }
    
    // Méthode pour parser les exigences multiples
    parseMultipleRequirements(text) {
        // Séparer par retours à la ligne
        let lines = text.split(/\r?\n/).map(line => line.trim()).filter(line => line);
        
        // Si une seule ligne, vérifier s'il y a des séparateurs
        if (lines.length === 1) {
            const singleLine = lines[0];
            
            // Chercher des patterns de listes à puces
            const bulletPatterns = [
                /(?:^|\s)[-•*]\s*(.+?)(?=\s[-•*]|$)/g,  // - • * en début ou après espace
                /(?:^|\s)\d+\.\s*(.+?)(?=\s\d+\.|$)/g,  // 1. 2. 3. etc.
                /(?:^|\s)[a-z]\)\s*(.+?)(?=\s[a-z]\)|$)/gi // a) b) c) etc.
            ];
            
            for (const pattern of bulletPatterns) {
                const matches = [...singleLine.matchAll(pattern)];
                if (matches.length > 1) {
                    return matches.map(match => match[1].trim()).filter(item => item);
                }
            }
            
            // Vérifier s'il y a des virgules ou points-virgules comme séparateurs
            if (singleLine.includes(',') || singleLine.includes(';')) {
                const separator = singleLine.includes(';') ? ';' : ',';
                const parts = singleLine.split(separator).map(part => part.trim()).filter(part => part);
                if (parts.length > 1) {
                    return parts;
                }
            }
        }
        
        // Traiter chaque ligne séparément et nettoyer les puces
        const cleanedLines = lines.map(line => {
            // Supprimer les puces au début de chaque ligne
            return line.replace(/^[-•*]\s*/, '')           // - • * 
                      .replace(/^\d+\.\s*/, '')            // 1. 2. 3.
                      .replace(/^[a-z]\)\s*/i, '')         // a) b) c)
                      .trim();
        }).filter(line => line);
        
        return cleanedLines.length > 0 ? cleanedLines : [text];
    }
    
    // Ajouter plusieurs exigences avec effet visuel
    addMultipleRequirements(requirements) {
        let addedCount = 0;
        let skippedCount = 0;
        let maxReachedCount = 0;
        
        for (const req of requirements) {
            if (this.requirements.length >= this.options.maxItems) {
                maxReachedCount++;
                continue;
            }
            
            if (this.requirements.includes(req)) {
                skippedCount++;
                continue;
            }
            
            this.requirements.push(req);
            addedCount++;
        }
        
        this.updateDisplay();
        this.updateHiddenInput();
        
        // Message de confirmation avec détails
        let message = '';
        if (addedCount > 0) {
            message += `✨ ${addedCount} exigence${addedCount > 1 ? 's' : ''} ajoutée${addedCount > 1 ? 's' : ''} automatiquement !`;
        }
        if (skippedCount > 0) {
            message += ` (${skippedCount} déjà existante${skippedCount > 1 ? 's' : ''})`;
        }
        if (maxReachedCount > 0) {
            message += ` (${maxReachedCount} ignorée${maxReachedCount > 1 ? 's' : ''} - limite atteinte)`;
        }
        
        if (addedCount > 0) {
            this.showMessage(message, 'success', 5000); // Message plus long pour les multiples
            this.highlightNewItems(addedCount);
        } else if (skippedCount > 0) {
            this.showMessage('Toutes les exigences existent déjà.', 'warning');
        } else if (maxReachedCount > 0) {
            this.showMessage(`Maximum ${this.options.maxItems} exigences autorisées.`, 'error');
        }
    }
    
    // Ajouter une seule exigence (méthode renommée)
    addSingleRequirement(text) {
        if (this.requirements.length >= this.options.maxItems) {
            this.showMessage(`Maximum ${this.options.maxItems} exigences autorisées.`, 'error');
            return;
        }
        
        if (this.requirements.includes(text)) {
            this.showMessage('Cette exigence existe déjà.', 'error');
            return;
        }
        
        this.requirements.push(text);
        this.updateDisplay();
        this.updateHiddenInput();
        this.showMessage('Exigence ajoutée avec succès.', 'success');
    }
    
    // Effet visuel pour mettre en surbrillance les nouveaux éléments
    highlightNewItems(count) {
        setTimeout(() => {
            const list = document.getElementById('requirements-list');
            const items = list.querySelectorAll('.requirement-item');
            
            // Mettre en surbrillance les derniers éléments ajoutés
            for (let i = Math.max(0, items.length - count); i < items.length; i++) {
                const item = items[i];
                item.classList.add('animate-pulse', 'bg-green-100', 'border-green-300');
                
                setTimeout(() => {
                    item.classList.remove('animate-pulse', 'bg-green-100', 'border-green-300');
                }, 2000);
            }
        }, 100);
    }

    addRequirement() {
        // Méthode conservée pour compatibilité, redirige vers processInput
        this.processInput();
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
                <div class="requirement-item flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg p-3 group hover:bg-gray-100 transition-colors duration-200">
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
    
    showMessage(message, type = 'info', duration = 3000) {
        // Supprimer les anciens messages
        const existingMessage = this.container.querySelector('.dynamic-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `dynamic-message p-3 rounded-lg mb-3 ${
            type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' : 
            type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
            type === 'warning' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' :
            'bg-blue-100 text-blue-800 border border-blue-200'
        }`;
        
        let icon = 'info-circle';
        if (type === 'error') icon = 'exclamation-triangle';
        else if (type === 'success') icon = 'check-circle';
        else if (type === 'warning') icon = 'exclamation-triangle';
        
        messageDiv.innerHTML = `<i class="fas fa-${icon} mr-2"></i>${message}`;
        
        this.container.insertBefore(messageDiv, this.container.firstChild);
        
        // Supprimer automatiquement après la durée spécifiée
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, duration);
    }
}

// Variable globale pour l'instance
let dynamicRequirements;

// Gestionnaire de prévisualisation de fichiers
document.addEventListener('DOMContentLoaded', function() {
    // Gestionnaire pour le logo partenaire
    const partnerLogoInput = document.getElementById('partner_logo');
    if (partnerLogoInput) {
        partnerLogoInput.addEventListener('change', function(e) {
            handleFilePreview(e.target, 'image');
        });
    }

    // Gestionnaire pour le document d'appel d'offre
    const documentInput = document.getElementById('document_appel_offre');
    if (documentInput) {
        documentInput.addEventListener('change', function(e) {
            handleFilePreview(e.target, 'document');
        });
    }
});

function handleFilePreview(input, type) {
    const file = input.files[0];
    if (!file) return;

    // Créer un élément de prévisualisation
    const preview = createFilePreview(file, type);
    
    // Insérer après l'input
    const existingPreview = input.parentNode.querySelector('.file-preview');
    if (existingPreview) {
        existingPreview.remove();
    }
    
    input.parentNode.insertBefore(preview, input.nextSibling);
}

function createFilePreview(file, type) {
    const preview = document.createElement('div');
    preview.className = 'file-preview mt-3 p-3 bg-green-50 border border-green-200 rounded-lg';
    
    const size = formatFileSize(file.size);
    const name = file.name;
    
    if (type === 'image') {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="flex items-center space-x-3">
                    <img src="${e.target.result}" alt="Aperçu" class="w-12 h-12 object-cover rounded">
                    <div>
                        <p class="text-sm font-medium text-green-800">✅ Nouveau fichier sélectionné</p>
                        <p class="text-xs text-green-600">${name} (${size})</p>
                    </div>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    } else {
        const extension = name.split('.').pop().toUpperCase();
        let icon = 'fas fa-file';
        
        switch(extension) {
            case 'PDF': icon = 'fas fa-file-pdf text-red-600'; break;
            case 'DOC':
            case 'DOCX': icon = 'fas fa-file-word text-blue-600'; break;
            case 'ODT': icon = 'fas fa-file-alt text-orange-600'; break;
        }
        
        preview.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-white rounded flex items-center justify-center">
                    <i class="${icon} text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-green-800">✅ Nouveau document sélectionné</p>
                    <p class="text-xs text-green-600">${name} (${size})</p>
                </div>
            </div>
        `;
    }
    
    return preview;
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}
</script>
@endsection
