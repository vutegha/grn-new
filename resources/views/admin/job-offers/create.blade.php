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

@push('scripts')
<script src="{{ asset('js/admin-forms-optimized.js') }}"></script>
<script>
// Initialisation spécifique pour Offres emploi
document.addEventListener('DOMContentLoaded', function() {
    if (typeof AdminFormsOptimized !== 'undefined') {
        AdminFormsOptimized.init('offres-emploi');
    }
});
</script>
@endpush
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
  <option value="freelance" {{ old('type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
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

  <!-- Critères d'évaluation -->
  <div class="criteria-builder-section">
    <div class="criteria-section-header">
      <h3 class="criteria-section-title">
        Critères d'évaluation
      </h3>
      <div class="criteria-count" id="criteria-count">0</div>
    </div>
      
      <!-- Templates intelligents -->
      <div class="templates-section">
        <h4>Templates intelligents</h4>
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
          <button type="button" class="template-btn" data-template="clear">
            🗑️ Effacer tout
          </button>
        </div>
        <p class="text-sm text-gray-600 mt-3">
          <i class="fas fa-info-circle mr-2"></i>
          Les templates s'adaptent automatiquement selon le titre de votre offre. Les suggestions intelligentes analysent le titre pour proposer des critères pertinents.
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
      <input type="hidden" name="criteria" id="criteria-input" value="{{ old('criteria', json_encode([])) }}">
      
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
          <strong>Conseil :</strong> Commencez par un template correspondant à votre poste, puis personnalisez selon vos besoins.
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

@push('styles')
<link rel="stylesheet" href="{{ asset('css/job-criteria-builder.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/admin-forms-optimized.js') }}"></script>
<script src="{{ asset('js/job-criteria-builder.js') }}"></script>
<script>
// Initialisation spécifique pour Offres emploi
document.addEventListener('DOMContentLoaded', function() {
    if (typeof AdminFormsOptimized !== 'undefined') {
        AdminFormsOptimized.init('offres-emploi');
    }
});
</script>
@endpush
@endsection
