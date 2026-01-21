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
                <a href="{{ route('admin.job-offers.show', $jobOffer) }}" class="text-white/80 hover:text-white transition-colors duration-200 text-sm font-medium">
                    {{ Str::limit($jobOffer->title, 20) }}
                </a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/40 text-xs"></i>
                <span class="text-white font-medium text-sm">Modifier</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Modifier l\'Offre d\'Emploi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Modifier l'Offre d'Emploi</h1>
                <p class="text-gray-600">{{ $jobOffer->title }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.job-offers.show', $jobOffer) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <i class="fas fa-eye mr-2"></i>
                    Voir l'offre
                </a>
                <a href="{{ route('admin.job-offers.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.job-offers.update', $jobOffer->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

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
                               value="{{ old('title', $jobOffer->title) }}"
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
                            <option value="full-time" {{ old('type', $jobOffer->type) == 'full-time' ? 'selected' : '' }}>Temps plein</option>
                            <option value="part-time" {{ old('type', $jobOffer->type) == 'part-time' ? 'selected' : '' }}>Temps partiel</option>
                            <option value="contract" {{ old('type', $jobOffer->type) == 'contract' ? 'selected' : '' }}>Contrat</option>
                            <option value="internship" {{ old('type', $jobOffer->type) == 'internship' ? 'selected' : '' }}>Stage</option>
                            <option value="freelance" {{ old('type', $jobOffer->type) == 'freelance' ? 'selected' : '' }}>Freelance</option>
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
                            <option value="internal" {{ old('source', $jobOffer->source) == 'internal' ? 'selected' : '' }}>🏢 Interne</option>
                            <option value="partner" {{ old('source', $jobOffer->source) == 'partner' ? 'selected' : '' }}>🤝 Partenaire</option>
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
                               value="{{ old('location', $jobOffer->location) }}"
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
                               value="{{ old('positions_available', $jobOffer->positions_available) }}"
                               min="1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200 @error('positions_available') border-red-300 @enderror"
                               required>
                        @error('positions_available')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div id="partner-fields" class="space-y-4" style="display: {{ old('source', $jobOffer->source) == 'partner' ? 'block' : 'none' }};">
                    <div>
                        <label for="partner_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom du partenaire
                        </label>
                        <input type="text" 
                               id="partner_name" 
                               name="partner_name" 
                               value="{{ old('partner_name', $jobOffer->partner_name) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200"
                               placeholder="Nom de l'entreprise partenaire">
                    </div>
                    <div>
                        <label for="partner_logo" class="block text-sm font-medium text-gray-700 mb-2">
                            Logo du partenaire
                        </label>
                        @if($jobOffer->partner_logo)
                            <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <i class="fas fa-image text-green-600 mr-2"></i>
                                        <span class="text-sm font-medium text-green-800">
                                            Logo actuel : {{ basename($jobOffer->partner_logo) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded">
                                            {{ $jobOffer->getPartnerLogoSizeFormatted() }}
                                        </span>
                                        <a href="{{ Storage::url($jobOffer->partner_logo) }}" 
                                           target="_blank"
                                           class="text-green-600 hover:text-green-800 transition-colors text-sm">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                                <img src="{{ Storage::url($jobOffer->partner_logo) }}" 
                                     alt="{{ $jobOffer->partner_name }}"
                                     class="w-20 h-20 object-contain bg-white rounded-lg p-2 border">
                            </div>
                        @endif
                        <input type="file" 
                               id="partner_logo" 
                               name="partner_logo" 
                               accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200">
                        <p class="mt-1 text-sm text-gray-500">
                            Formats acceptés: JPG, PNG, SVG (max 2MB). 
                            @if($jobOffer->partner_logo)
                                <span class="text-orange-600 font-medium">Sélectionner un nouveau fichier remplacera le logo actuel.</span>
                            @else
                                Aucun logo actuellement défini.
                            @endif
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
                              required>{{ old('description', $jobOffer->description) }}</textarea>
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
                              placeholder="Décrivez les avantages du poste (télétravail, formation, évolution...)">{{ old('benefits', $jobOffer->benefits) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Conditions et informations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-iri-accent to-iri-gold px-6 py-4">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Conditions et informations
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="application_deadline" class="block text-sm font-medium text-gray-700 mb-2">
                            Date limite de candidature <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="application_deadline" 
                               name="application_deadline" 
                               value="{{ old('application_deadline', $jobOffer->application_deadline ? $jobOffer->application_deadline->format('Y-m-d') : '') }}"
                               min="{{ date('Y-m-d') }}"
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
                               value="{{ old('contact_email', $jobOffer->contact_email) }}"
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
                            @if($jobOffer->document_appel_offre)
                                <div class="mb-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                @php
                                                    $extension = $jobOffer->getDocumentAppelOffreExtension();
                                                    $iconClass = match(strtolower($extension ?? '')) {
                                                        'pdf' => 'fas fa-file-pdf text-red-600',
                                                        'doc', 'docx' => 'fas fa-file-word text-blue-600',
                                                        'odt' => 'fas fa-file-alt text-orange-600',
                                                        default => 'fas fa-file text-gray-600'
                                                    };
                                                @endphp
                                                <i class="{{ $iconClass }}"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-blue-900">
                                                    {{ $jobOffer->document_appel_offre_nom ?? basename($jobOffer->document_appel_offre) }}
                                                </h4>
                                                <div class="flex items-center space-x-3 mt-1">
                                                    <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded uppercase">
                                                        {{ $jobOffer->getDocumentAppelOffreExtension() ?? 'FICHIER' }}
                                                    </span>
                                                    <span class="text-xs text-blue-600">
                                                        {{ $jobOffer->getDocumentAppelOffreSizeFormatted() }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        Ajouté le {{ $jobOffer->created_at->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ Storage::url($jobOffer->document_appel_offre) }}" 
                                               target="_blank"
                                               class="inline-flex items-center px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors">
                                                <i class="fas fa-eye mr-1"></i>
                                                Voir
                                            </a>
                                            <a href="{{ Storage::url($jobOffer->document_appel_offre) }}" 
                                               download="{{ $jobOffer->document_appel_offre_nom ?? basename($jobOffer->document_appel_offre) }}"
                                               class="inline-flex items-center px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors">
                                                <i class="fas fa-download mr-1"></i>
                                                Télécharger
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
                                    @if($jobOffer->document_appel_offre)
                                        <span class="text-orange-600 font-medium">⚠️ Sélectionner un nouveau fichier remplacera définitivement le document actuel</span>
                                    @else
                                        <span class="text-gray-400">Aucun document actuellement attaché à cette offre</span>
                                    @endif
                                </span>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <div id="document-preview" class="w-16 h-16 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center {{ $jobOffer->document_appel_offre ? '' : 'hidden' }}">
                                <i class="fas fa-file-alt text-gray-400 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Critères d'évaluation -->
        <div class="criteria-builder-section">
            <div class="criteria-section-header">
                <h3 class="criteria-section-title">
                    Critères d'évaluation
                </h3>
                <div class="criteria-count" id="criteria-count">{{ count($jobOffer->criteria ?? []) }}</div>
            </div>
                
                <!-- Templates intelligents -->
                <div class="templates-section">
                    <h4>Templates intelligents & Analyse des candidatures</h4>
                    <div class="flex flex-wrap gap-3 mb-4">
                        <button type="button" class="template-btn" data-template="developer">
                            💻 Développeur
                        </button>
                        <button type="button" class="template-btn" data-template="researcher">
                            🔬 Chercheur
                        </button>
                        <button type="button" class="template-btn" data-template="manager">
                            👥 Manager
                        </button>
                        <button type="button" class="template-btn" data-template="communication">
                            📢 Communication
                        </button>
                        <button type="button" class="template-btn" data-template="smart">
                            🧠 Suggestions intelligentes
                        </button>
                        @if($jobOffer->applications->count() >= 3)
                        <button type="button" class="template-btn px-3 py-2 bg-teal-100 text-teal-700 rounded-lg hover:bg-teal-200 transition-colors text-sm" data-template="analyze">
                            📊 Analyser les candidatures ({{ $jobOffer->applications->count() }})
                        </button>
                        @endif
                        <button type="button" class="template-btn px-2 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm" data-template="clear">
                            🗑️ Effacer tout
                        </button>
                    </div>
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Les templates s'adaptent automatiquement selon le titre de votre offre. 
                        @if($jobOffer->applications->count() >= 3)
                        L'analyse des candidatures utilise les {{ $jobOffer->applications->count() }} candidatures existantes pour suggérer des critères pertinents.
                        @endif
                    </p>
                </div>

                <!-- Container des critères -->
                <div id="criteria-container" class="space-y-4 min-h-[60px]">
                    <!-- Les critères seront ajoutés ici dynamiquement -->
                </div>
                
                <!-- Bouton ajouter critère -->
                <div class="flex items-center justify-center mt-6">
                    <button type="button" id="add-criteria-btn" 
                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-200 hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter un critère personnalisé
                    </button>
                </div>
                
                <!-- Preview -->
                <div id="criteria-preview" class="mt-8 p-4 bg-gray-50 rounded-lg border-l-4 border-purple-500 hidden">
                    <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-eye mr-2 text-purple-500"></i>
                        Aperçu candidat - Comment les candidats verront ces questions
                    </h4>
                    <div id="preview-content" class="space-y-3">
                        <!-- Preview généré ici -->
                    </div>
                    <p class="text-xs text-gray-500 mt-3 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>
                        Cet aperçu montre comment les questions apparaîtront dans le formulaire de candidature
                    </p>
                </div>
                
                <!-- Input caché pour stocker les critères -->
                <input type="hidden" name="criteria" id="criteria-input" value="{{ old('criteria', json_encode($jobOffer->criteria ?? [])) }}">
                
                <!-- Statistiques des critères actuels -->
                @if($jobOffer->criteria && count($jobOffer->criteria) > 0 && $jobOffer->applications->count() > 0)
                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <h5 class="font-medium text-green-900 mb-2 flex items-center">
                        <i class="fas fa-chart-line mr-2"></i>
                        Impact des critères actuels
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ count($jobOffer->criteria) }}</div>
                            <div class="text-green-700">Critères actifs</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $jobOffer->applications->count() }}</div>
                            <div class="text-green-700">Candidatures reçues</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ number_format(($jobOffer->applications->where('status', '!=', 'pending')->count() / max(1, $jobOffer->applications->count())) * 100, 1) }}%</div>
                            <div class="text-green-700">Candidatures traitées</div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Aide contextuelle -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h5 class="font-medium text-blue-900 mb-2 flex items-center">
                        <i class="fas fa-question-circle mr-2"></i>
                        Comment utiliser les critères d'évaluation ?
                    </h5>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li><strong>• Texte court :</strong> Pour des réponses courtes (nom, ville, nombre d'années...)</li>
                        <li><strong>• Texte long :</strong> Pour des réponses détaillées (motivation, expérience...)</li>
                        <li><strong>• Liste déroulante :</strong> Pour choisir parmi plusieurs options prédéfinies</li>
                        <li><strong>• Choix multiple :</strong> Pour des réponses avec des options visibles (Oui/Non, niveaux...)</li>
                    </ul>
                    <p class="text-xs text-blue-600 mt-2">
                        <i class="fas fa-lightbulb mr-1"></i>
                        <strong>Conseil :</strong> Modifiez les critères avec précaution si vous avez déjà des candidatures - cela peut affecter la cohérence de l'évaluation.
                    </p>
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
                            <option value="draft" {{ old('status', $jobOffer->status) == 'draft' ? 'selected' : '' }}>📝 Brouillon</option>
                            <option value="active" {{ old('status', $jobOffer->status) == 'active' ? 'selected' : '' }}>✅ Active</option>
                            <option value="paused" {{ old('status', $jobOffer->status) == 'paused' ? 'selected' : '' }}>⏸️ En pause</option>
                            <option value="closed" {{ old('status', $jobOffer->status) == 'closed' ? 'selected' : '' }}>🔒 Fermée</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input id="is_featured" 
                                   name="is_featured" 
                                   type="checkbox" 
                                   value="1"
                                   {{ old('is_featured', $jobOffer->is_featured) ? 'checked' : '' }}
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

                <!-- Historique de modification -->
                <div class="border-t pt-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Historique</h4>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><strong>Créée le :</strong> {{ $jobOffer->created_at->format('d/m/Y à H:i') }}</p>
                        @if($jobOffer->updated_at != $jobOffer->created_at)
                            <p><strong>Dernière modification :</strong> {{ $jobOffer->updated_at->format('d/m/Y à H:i') }}</p>
                        @endif
                        <p><strong>Candidatures reçues :</strong> {{ $jobOffer->applications_count ?? 0 }}</p>
                        <p><strong>Vues :</strong> {{ $jobOffer->views_count ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
            <div class="flex space-x-3">
                <a href="{{ route('admin.job-offers.show', $jobOffer) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </a>
            </div>
            
            <div class="flex space-x-3">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer les modifications
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



    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        function resizeTextarea() {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        }
        
        textarea.addEventListener('input', resizeTextarea);
        resizeTextarea(); // Initial resize
    });

    // Form submission handling
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');
    let isSubmitting = false;
    
    form.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }
        
        isSubmitting = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enregistrement...';
        submitButton.disabled = true;
    });

    // Unsaved changes warning
    let hasUnsavedChanges = false;
    const formInputs = form.querySelectorAll('input, textarea, select');
    
    formInputs.forEach(input => {
        input.addEventListener('change', function() {
            hasUnsavedChanges = true;
        });
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (hasUnsavedChanges && !isSubmitting) {
            e.preventDefault();
            e.returnValue = '';
            return 'Vous avez des modifications non sauvegardées. Êtes-vous sûr de vouloir quitter ?';
        }
    });
    
    // Remove warning when form is submitted
    form.addEventListener('submit', function(e) {
        hasUnsavedChanges = false;
        
        // Valider les exigences
        if (dynamicRequirements && dynamicRequirements.requirements.length === 0) {
            e.preventDefault();
            alert('Veuillez ajouter au moins une exigence pour le poste.');
            return false;
        }
    });
    
    // Initialiser le composant de gestion des exigences
    dynamicRequirements = new DynamicRequirements('requirements-container', {
        inputPlaceholder: 'Saisissez une exigence (ex: Minimum 3 ans d\'expérience)',
        addButtonText: 'Ajouter',
        hiddenInputName: 'requirements',
        maxItems: 15
    });
    
    // Charger les exigences existantes
    @if($jobOffer->requirements)
        dynamicRequirements.setRequirements({!! json_encode($jobOffer->requirements) !!});
    @endif
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
    preview.className = 'file-preview mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg';
    
    const size = formatFileSize(file.size);
    const name = file.name;
    
    if (type === 'image') {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="flex items-center space-x-3">
                    <img src="${e.target.result}" alt="Aperçu" class="w-12 h-12 object-cover rounded">
                    <div>
                        <p class="text-sm font-medium text-yellow-800">📄 Nouveau fichier sélectionné</p>
                        <p class="text-xs text-yellow-600">${name} (${size})</p>
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
                    <p class="text-sm font-medium text-yellow-800">📄 Nouveau document sélectionné</p>
                    <p class="text-xs text-yellow-600">${name} (${size})</p>
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

<!-- CSS et JS pour le builder de critères -->
<link rel="stylesheet" href="{{ asset('css/job-criteria-builder.css') }}">
<script src="{{ asset('js/job-criteria-builder.js') }}"></script>

<script>
// Extension du builder pour la vue edit avec analyse des candidatures
document.addEventListener('DOMContentLoaded', function() {
    // Attendre que le builder soit initialisé
    setTimeout(() => {
        if (window.jobCriteriaBuilder) {
            // Ajouter la gestion du bouton d'analyse
            const analyzeBtn = document.querySelector('[data-template="analyze"]');
            if (analyzeBtn) {
                analyzeBtn.addEventListener('click', async function() {
                    analyzeBtn.disabled = true;
                    analyzeBtn.innerHTML = '<i class="loading-spinner mr-2"></i>Analyse en cours...';
                    
                    try {
                        const response = await fetch('{{ route("admin.job-offers.analyze", $jobOffer) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            if (data.suggestions && data.suggestions.length > 0) {
                                // Confirmer avant de remplacer
                                if (window.jobCriteriaBuilder.criteria.length > 0) {
                                    if (!confirm('Remplacer les critères existants par l\'analyse des candidatures ?')) {
                                        return;
                                    }
                                }
                                
                                // Appliquer les suggestions
                                window.jobCriteriaBuilder.criteria = data.suggestions.map((criterion, index) => ({
                                    ...criterion,
                                    id: Date.now() + index
                                }));
                                
                                window.jobCriteriaBuilder.render();
                                window.jobCriteriaBuilder.updatePreview();
                                window.jobCriteriaBuilder.showSuccessMessage(`${data.suggestions.length} critères suggérés basés sur l'analyse de ${data.analyzed_count} candidatures`);
                            } else {
                                window.jobCriteriaBuilder.showErrorMessage(data.message || 'Aucune suggestion trouvée');
                            }
                        } else {
                            throw new Error('Erreur réseau');
                        }
                    } catch (error) {
                        console.error('Erreur lors de l\'analyse:', error);
                        window.jobCriteriaBuilder.showErrorMessage('Erreur lors de l\'analyse des candidatures');
                    } finally {
                        analyzeBtn.disabled = false;
                        analyzeBtn.innerHTML = '📊 Analyser les candidatures ({{ $jobOffer->applications->count() }})';
                    }
                });
            }
        }
    }, 500);
});
</script>
@endsection
