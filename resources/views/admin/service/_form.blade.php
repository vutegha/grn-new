@props(['service' => null, 'formAction'])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
  <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="space-y-6">
  @csrf
  @if($service) @method('PUT') @endif

  <!-- Header du formulaire -->
  <div class="border-b border-gray-200 pb-6">
  <h3 class="text-xl font-semibold text-gray-900">
  {{ $service ? 'Modifier le service' : 'Nouveau service' }}
  </h3>
  <p class="mt-1 text-sm text-gray-600">
  {{ $service ? 'Modifiez les informations de ce service.' : 'Créez un nouveau service pour le GRN-UCBC.' }}
  </p>
  </div>

  <!-- Section informations principales -->
  <div class="space-y-6">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <!-- Nom du service -->
  <div>
  <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">
  <i class="fas fa-tag text-iri-primary mr-2"></i>Nom du service
  </label>
  <input type="text" 
  id="nom"
  name="nom" 
  value="{{ old('nom', $service->nom ?? '') }}" 
  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-all duration-200 @error('nom') border-red-500 @enderror"
  placeholder="Ex: Recherche et développement"
  required>
  @error('nom')
  <p class="mt-2 text-sm text-red-600 flex items-center">
  <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
  </p>
  @enderror
  </div>

  <!-- Nom pour le menu -->
  <div>
  <label for="nom_menu" class="block text-sm font-semibold text-gray-700 mb-2">
  <i class="fas fa-bars text-iri-secondary mr-2"></i>Nom pour le menu
  <span class="text-xs font-normal text-gray-500">(optionnel)</span>
  </label>
  <input type="text" 
  id="nom_menu"
  name="nom_menu" 
  value="{{ old('nom_menu', $service->nom_menu ?? '') }}" 
  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-secondary focus:border-transparent transition-all duration-200"
  placeholder="Nom affiché dans le menu">
  <p class="mt-2 text-xs text-gray-500 flex items-center">
  <i class="fas fa-info-circle mr-1"></i>
  Ce nom apparaîtra dans le menu déroulant "Programmes". Laissez vide pour utiliser le nom principal.
  </p>
  </div>
  </div>

  <!-- Résumé court -->
  <div>
  <label for="resume" class="block text-sm font-semibold text-gray-700 mb-2">
  <i class="fas fa-file-alt text-iri-gold mr-2"></i>Résumé court
  </label>
  <textarea name="resume" 
  id="resume" 
  rows="3" 
  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-gold focus:border-transparent transition-all duration-200 resize-none @error('resume') border-red-500 @enderror"
  placeholder="Un résumé concis de ce service...">{{ old('resume', $service->resume ?? '') }}</textarea>
  @error('resume')
  <p class="mt-2 text-sm text-red-600 flex items-center">
  <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
  </p>
  @enderror
  </div>

  <!-- Description complète -->
  <div>
  <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
  <i class="fas fa-align-left text-iri-accent mr-2"></i>Description complète
  </label>
  
  <textarea name="description" 
  id="description" 
  class="wysiwyg w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-accent focus:border-transparent transition-all duration-200 bg-white placeholder-gray-400 lg:min-h-[400px]"
  rows="12"
  placeholder="Décrivez en détail ce service, ses objectifs et ses activités...">{{ old('description', $service->description ?? '') }}</textarea>
  @error('description')
  <p class="mt-2 text-sm text-red-600 flex items-center">
  <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
  </p>
  @enderror
  <p class="mt-2 text-xs text-gray-500">
  <i class="fas fa-info-circle mr-1"></i>
  Utilisez l'éditeur de texte enrichi pour formatter votre contenu avec des titres, listes, liens, etc. Le bouton "Médiathèque" dans la barre d'outils permet d'insérer des images.
  </p>
  </div>

  <!-- Image -->
  <div>
  <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
  <i class="fas fa-image text-olive mr-2"></i>Image du service
  <span class="text-xs font-normal text-gray-500">(optionnelle)</span>
  </label>
  
  <div class="flex items-start space-x-6">
  <div class="flex-1">
  <div class="relative">
  <input type="file" 
  id="image"
  name="image" 
  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-olive focus:border-transparent transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-olive/10 file:text-olive hover:file:bg-olive/20"
  accept="image/*"
  onchange="previewImage(this)">
  </div>
  <p class="mt-2 text-xs text-gray-500">
  Formats acceptés: JPG, PNG, GIF (max 2MB)
  </p>
  </div>
  
  <!-- Aperçu de l'image -->
  <div class="flex-shrink-0">
  @if($service && $service->hasValidImage())
  <div class="relative group">
  <img id="image-preview" 
  src="{{ $service->image_url }}" 
  class="w-24 h-24 object-cover rounded-lg border-2 border-gray-200 shadow-sm" 
  alt="Aperçu"
  loading="lazy">
  <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
  <i class="fas fa-eye text-white text-lg"></i>
  </div>
  </div>
  @else
  <div id="image-placeholder" class="w-24 h-24 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
  <i class="fas fa-image text-gray-400 text-2xl"></i>
  </div>
  <img id="image-preview" 
  class="w-24 h-24 object-cover rounded-lg border-2 border-gray-200 shadow-sm hidden" 
  alt="Aperçu">
  @endif
  </div>
  </div>
  </div>
  </div>

  <!-- Actions -->
  <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-8 border-t border-gray-200">
  <a href="{{ route('admin.service.index') }}" 
  class="group inline-flex items-center justify-center px-6 py-3 border-2 border-gray-400 text-gray-700 bg-white rounded-xl hover:bg-gray-50 hover:border-gray-600 hover:text-gray-900 transition-all duration-300 font-medium shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
  <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform duration-300"></i>
  Retour aux services
  </a>
  <button type="submit" 
  class="group inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-xl hover:from-iri-secondary hover:to-iri-primary transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 border border-iri-accent/20">
  <i class="fas fa-save mr-3 group-hover:rotate-12 transition-transform duration-300"></i>
  <span class="relative">
  {{ $service ? 'Mettre à jour le service' : 'Enregistrer le service' }}
  <div class="absolute inset-0 bg-gradient-to-r from-iri-gold/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded"></div>
  </span>
  </button>
  </div>
  </form>
</div>

<!-- Modal de sélection de médias pour CKEditor -->
<style>
#mediaModal {
  background: rgba(0, 0, 0, 0.8);
  backdrop-filter: blur(5px);
  animation: fadeIn 0.3s ease-out;
}

#mediaModal.hidden {
  display: none !important;
}

#mediaModal .bg-white {
  animation: slideIn 0.3s ease-out;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

@keyframes fadeIn {
  from {
  opacity: 0;
  }
  to {
  opacity: 1;
  }
}

@keyframes slideIn {
  from {
  opacity: 0;
  transform: translateY(-20px);
  }
  to {
  opacity: 1;
  transform: translateY(0);
  }
}

.modal-open {
  position: fixed !important;
  width: 100% !important;
  overflow: hidden !important;
}
</style>

<!-- Modale Médiathèque -->
<div id="mediaModal" class="hidden">
  <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
  <div class="px-6 py-4 border-b flex items-center justify-between bg-gradient-to-r from-iri-primary to-iri-secondary">
  <h3 class="text-lg font-semibold text-white flex items-center">
  <i class="fas fa-photo-video mr-3"></i>
  Médiathèque
  </h3>
  <button type="button" onclick="closeMediaModal()" class="text-white hover:text-gray-200 transition-colors">
  <i class="fas fa-times text-xl"></i>
  </button>
  </div>
  <div class="p-6">
  <!-- Upload d'image -->
  <form id="mediaUploadForm" action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data" class="mb-6">
  @csrf
  <div class="flex items-center space-x-4">
  <input type="file" name="image" id="mediaUploadInput" accept="image/*" class="border rounded p-2 flex-1">
  <button type="submit" class="px-4 py-2 bg-iri-primary text-white rounded hover:bg-iri-secondary transition">Uploader</button>
  </div>
  <p class="text-xs text-gray-500 mt-2">
  <span class="text-green-600">✓ Compression automatique</span> - Les images sont optimisées automatiquement
  </p>
  </form>
  <!-- Liste des images existantes -->
  <div id="mediaList" class="grid grid-cols-2 md:grid-cols-4 gap-4">
  <div class="col-span-full text-center text-gray-500 py-8">
  <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
  <p>Chargement des médias...</p>
  </div>
  </div>
  </div>
  </div>
</div>

<!-- Script pour l'aperçu d'image et CKEditor -->
<script>/* Optimized - JS moved to external file */</script>
