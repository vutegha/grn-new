<!-- Formulaire moderne pour les rapports -->
<div id="rapport-form" class="max-w-4xl mx-auto">
  <!-- En-tête du formulaire -->
  <div class="bg-white rounded-t-xl border-b border-gray-200 px-8 py-6">
  <div class="flex items-center space-x-3">
  <div class="flex-shrink-0">
  <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
  <i class="fas fa-file-alt text-blue-600 text-lg"></i>
  </div>
  </div>
  <div>
  <h2 class="text-xl font-semibold text-gray-900">
  {{ isset($rapport) ? 'Modifier le rapport' : 'Nouveau rapport' }}
  </h2>
  <p class="text-sm text-gray-600 mt-1">
  {{ isset($rapport) ? 'Modifiez les informations du rapport ci-dessous' : 'Remplissez les informations pour créer un nouveau rapport' }}
  </p>
  </div>
  </div>
  </div>

  <!-- Corps du formulaire -->
  <div class="bg-white rounded-b-xl shadow-sm border border-gray-200 p-8">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
  
  <!-- Colonne gauche - Informations principales -->
  <div class="space-y-6">
  <div class="bg-gray-50 rounded-lg p-6">
  <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
  <i class="fas fa-info-circle text-blue-500 mr-2"></i>
  Informations principales
  </h3>
  
  <!-- Titre -->
  <div class="mb-6">
  <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
  <i class="fas fa-heading text-gray-400 mr-1"></i>
  Titre du rapport *
  </label>
  <div class="relative">
  <input type="text" 
  name="titre" 
  id="titre"
  value="{{ old('titre', $rapport->titre ?? '') }}"
  placeholder="Entrez le titre du rapport..."
  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 pl-10">
  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
  <i class="fas fa-text-width text-gray-400"></i>
  </div>
  </div>
  <p id="titre-error" class="text-sm text-red-500 mt-1"></p>
  @error('titre')
  <p class="text-sm text-red-500 mt-1 flex items-center">
  <i class="fas fa-exclamation-circle mr-1"></i>
  {{ $message }}
  </p>
  @enderror
  </div>

  <!-- Catégorie -->
  <div class="mb-6">
  <label for="categorie_id" class="block text-sm font-medium text-gray-700 mb-2">
  <i class="fas fa-folder-open text-gray-400 mr-1"></i>
  Catégorie *
  </label>
  <div class="relative">
  <select name="categorie_id" 
  id="categorie_id"
  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 pl-10 appearance-none bg-white">
  <option value="">-- Sélectionner une catégorie --</option>
  @foreach($categories as $categorie)
  <option value="{{ $categorie->id }}" 
  {{ old('categorie_id', $rapport->categorie_id ?? '') == $categorie->id ? 'selected' : '' }}>
  {{ $categorie->nom }}
  </option>
  @endforeach
  </select>
  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
  <i class="fas fa-folder text-gray-400"></i>
  </div>
  <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
  <i class="fas fa-chevron-down text-gray-400"></i>
  </div>
  </div>
  @error('categorie_id')
  <p class="text-sm text-red-500 mt-1 flex items-center">
  <i class="fas fa-exclamation-circle mr-1"></i>
  {{ $message }}
  </p>
  @enderror
  </div>

  <!-- Date de publication -->
  <div>
  <label for="date_publication" class="block text-sm font-medium text-gray-700 mb-2">
  <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
  Date de publication
  </label>
  <div class="relative">
  <input type="date" 
  name="date_publication" 
  id="date_publication"
  value="{{ old('date_publication', $rapport->date_publication ? $rapport->date_publication->format('Y-m-d') : '') }}"
  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 pl-10">
  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
  <i class="fas fa-calendar text-gray-400"></i>
  </div>
  </div>
  @error('date_publication')
  <p class="text-sm text-red-500 mt-1 flex items-center">
  <i class="fas fa-exclamation-circle mr-1"></i>
  {{ $message }}
  </p>
  @enderror
  </div>

  <!-- Statut de publication -->
  <div>
  <label class="flex items-center space-x-3 cursor-pointer group">
  <div class="relative">
  <input type="checkbox" 
  name="is_published" 
  id="is_published"
  value="1"
  {{ old('is_published', $rapport->is_published ?? false) ? 'checked' : '' }}
  class="sr-only peer">
  <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-green-600 peer-focus:ring-2 peer-focus:ring-green-500 peer-focus:ring-offset-2 transition-all duration-200"></div>
  <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-all duration-200 peer-checked:translate-x-5"></div>
  </div>
  <div class="flex items-center space-x-2">
  <i class="fas fa-eye text-gray-400 group-hover:text-green-600 transition-colors"></i>
  <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
  Publier immédiatement
  </span>
  </div>
  </label>
  <p class="text-xs text-gray-500 mt-2 ml-14">
  <i class="fas fa-info-circle mr-1"></i>
  Si décoché, le rapport sera créé en brouillon
  </p>
  </div>
  </div>
  </div>

  <!-- Colonne droite - Fichier et description -->
  <div class="space-y-6">
  <!-- Upload de fichier -->
  <div class="bg-gray-50 rounded-lg p-6">
  <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
  <i class="fas fa-cloud-upload-alt text-green-500 mr-2"></i>
  Document
  </h3>
  
  <div class="mb-6">
  <label for="fichier" class="block text-sm font-medium text-gray-700 mb-2">
  <i class="fas fa-file-upload text-gray-400 mr-1"></i>
  Fichier du rapport
  </label>
  
  <!-- Zone de drop -->
  <div class="relative">
  <div id="file-drop-zone" 
  class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition duration-200 cursor-pointer">
  <div id="file-drop-content">
  <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
  <p class="text-gray-600 mb-2">
  <span class="font-medium text-blue-600">Cliquez pour sélectionner</span> ou glissez-déposez votre fichier
  </p>
  <p class="text-sm text-gray-500">
  Formats supportés: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX (Max: 10MB)
  </p>
  </div>
  
  <!-- Fichier sélectionné -->
  <div id="file-selected" class="hidden">
  <div class="flex items-center justify-center space-x-3">
  <div id="file-icon" class="text-3xl"></div>
  <div class="text-left">
  <p id="file-name" class="font-medium text-gray-900"></p>
  <p id="file-size" class="text-sm text-gray-500"></p>
  </div>
  <button type="button" id="file-remove" 
  class="text-red-500 hover:text-red-700 transition duration-200">
  <i class="fas fa-times-circle text-xl"></i>
  </button>
  </div>
  </div>
  </div>
  
  <input type="file" 
  name="fichier" 
  id="fichier" 
  accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.rtf"
  class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
  </div>
  
  @if(isset($rapport) && $rapport->fichier)
  <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
  <div class="flex items-center justify-between">
  <div class="flex items-center space-x-2">
  <i class="fas fa-file-alt text-blue-600"></i>
  <span class="text-sm text-blue-800">Fichier actuel: {{ basename($rapport->fichier) }}</span>
  </div>
  <a href="{{ $rapport->getDownloadUrl() }}" 
  target="_blank"
  class="text-blue-600 hover:text-blue-800 text-sm">
  <i class="fas fa-eye mr-1"></i>Aperçu
  </a>
  </div>
  </div>
  @endif
  
  @error('fichier')
  <p class="text-sm text-red-500 mt-2 flex items-center">
  <i class="fas fa-exclamation-circle mr-1"></i>
  {{ $message }}
  </p>
  @enderror
  </div>
  </div>

  <!-- Description -->
  <div class="bg-gray-50 rounded-lg p-6">
  <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
  <i class="fas fa-align-left text-purple-500 mr-2"></i>
  Description
  </h3>
  
  <div>
  <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
  <i class="fas fa-edit text-gray-400 mr-1"></i>
  Résumé du rapport
  </label>
  <textarea name="description" 
  id="description" 
  rows="6"
  placeholder="Décrivez brièvement le contenu et les objectifs de ce rapport..."
  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 resize-none">{{ old('description', $rapport->description ?? '') }}</textarea>
  @error('description')
  <p class="text-sm text-red-500 mt-1 flex items-center">
  <i class="fas fa-exclamation-circle mr-1"></i>
  {{ $message }}
  </p>
  @enderror
  <div class="flex justify-between items-center mt-2">
  <p class="text-xs text-gray-500">
  <i class="fas fa-info-circle mr-1"></i>
  Optionnel mais recommandé pour une meilleure visibilité
  </p>
  <span id="description-count" class="text-xs text-gray-400">0 caractères</span>
  </div>
  </div>
  </div>
  </div>
  </div>

  <!-- Actions du formulaire -->
  <div class="border-t border-gray-200 pt-8 mt-8">
  <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
  <div class="flex items-center space-x-2 text-sm text-gray-600">
  <i class="fas fa-info-circle text-blue-500"></i>
  <span>Les champs marqués d'un * sont obligatoires</span>
  </div>
  
  <div class="flex space-x-3">
  <a href="{{ route('admin.rapports.index') }}" 
  class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 flex items-center">
  <i class="fas fa-times mr-2"></i>
  Annuler
  </a>
  
  <button type="submit" 
  id="submit-btn"
  class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 flex items-center font-medium">
  <i class="fas fa-save mr-2"></i>
  <span id="submit-text">{{ isset($rapport) ? 'Mettre à jour le rapport' : 'Créer le rapport' }}</span>
  <div id="submit-spinner" class="hidden ml-2">
  <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
  </div>
  </button>
  </div>
  </div>
  </div>
  </div>
</div>

<!-- CKEditor 5 v34.2.0 - Version Open Source stable -->
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>

<!-- JavaScript moderne pour le formulaire avec CKEditor -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Configuration CKEditor pour le champ description
    if (typeof ClassicEditor !== 'undefined') {
        const descriptionElement = document.querySelector('#description');
        
        if (descriptionElement) {
            ClassicEditor
                .create(descriptionElement, {
                    language: 'fr',
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'underline', '|',
                        'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'link', 'blockQuote', '|',
                        'undo', 'redo'
                    ],
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraphe', class: 'ck-heading_paragraph' },
                            { model: 'heading2', view: 'h2', title: 'Titre 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Titre 3', class: 'ck-heading_heading3' }
                        ]
                    },
                    placeholder: 'Décrivez brièvement le contenu et les objectifs de ce rapport...'
                })
                .then(editor => {
                    
                    // Compteur de caractères en temps réel
                    const countElement = document.getElementById('description-count');
                    if (countElement) {
                        editor.model.document.on('change:data', () => {
                            const data = editor.getData();
                            const textLength = data.replace(/<[^>]*>/g, '').length;
                            countElement.textContent = `${textLength} caractères`;
                        });
                        
                        // Initialiser le compteur
                        const initialData = editor.getData();
                        const initialLength = initialData.replace(/<[^>]*>/g, '').length;
                        countElement.textContent = `${initialLength} caractères`;
                    }
                    
                    // Validation visuelle
                    editor.model.document.on('change:data', () => {
                        const data = editor.getData();
                        const editorElement = editor.ui.element;
                        
                        if (data.trim().length > 0) {
                            editorElement.style.borderColor = '#10b981';
                        } else {
                            editorElement.style.borderColor = '#d1d5db';
                        }
                    });
                })
                .catch(error => {
                    
                    // Fallback : améliorer le textarea simple
                    descriptionElement.style.minHeight = '150px';
                    descriptionElement.placeholder = 'Décrivez brièvement le contenu et les objectifs de ce rapport...';
                    
                    // Compteur de caractères pour textarea
                    const countElement = document.getElementById('description-count');
                    if (countElement) {
                        descriptionElement.addEventListener('input', function() {
                            countElement.textContent = `${this.value.length} caractères`;
                        });
                        countElement.textContent = `${descriptionElement.value.length} caractères`;
                    }
                });
        }
    
    // Autres fonctionnalités du formulaire
    initializeFormFeatures();
});

function initializeFormFeatures() {
    // Gestion du drag & drop pour fichiers
    const fileDropZone = document.getElementById('file-drop-zone');
    const fileInput = document.getElementById('fichier');
    const fileRemoveBtn = document.getElementById('file-remove');
    
    if (fileDropZone && fileInput) {
        // Drag & drop events
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileDropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            fileDropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            fileDropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            fileDropZone.classList.add('border-blue-400', 'bg-blue-50');
        }
        
        function unhighlight() {
            fileDropZone.classList.remove('border-blue-400', 'bg-blue-50');
        }
        
        // Drop handler
        fileDropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                displaySelectedFile(files[0]);
            }
        }
        
        // Click to select
        fileDropZone.addEventListener('click', () => fileInput.click());
        
        // File input change
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                displaySelectedFile(this.files[0]);
            }
        });
        
        // Remove file
        if (fileRemoveBtn) {
            fileRemoveBtn.addEventListener('click', function() {
                fileInput.value = '';
                document.getElementById('file-drop-content').classList.remove('hidden');
                document.getElementById('file-selected').classList.add('hidden');
            });
        }
    }
    
    function displaySelectedFile(file) {
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const fileIcon = document.getElementById('file-icon');
        
        if (fileName && fileSize && fileIcon) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            
            // Icône selon le type de fichier
            const extension = file.name.split('.').pop().toLowerCase();
            const iconMap = {
                'pdf': 'fas fa-file-pdf text-red-500',
                'doc': 'fas fa-file-word text-blue-500',
                'docx': 'fas fa-file-word text-blue-500',
                'xls': 'fas fa-file-excel text-green-500',
                'xlsx': 'fas fa-file-excel text-green-500',
                'ppt': 'fas fa-file-powerpoint text-orange-500',
                'pptx': 'fas fa-file-powerpoint text-orange-500'
            };
            
            fileIcon.className = iconMap[extension] || 'fas fa-file text-gray-500';
            
            document.getElementById('file-drop-content').classList.add('hidden');
            document.getElementById('file-selected').classList.remove('hidden');
        }
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Animation du bouton submit
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitSpinner = document.getElementById('submit-spinner');
    
    if (submitBtn && submitText && submitSpinner) {
        submitBtn.addEventListener('click', function() {
            if (submitBtn.type === 'submit') {
                submitText.textContent = 'Traitement en cours...';
                submitSpinner.classList.remove('hidden');
                submitBtn.disabled = true;
            }
        });
    }
    

}
</script>

<!-- Styles CSS pour les animations et CKEditor -->
<style>
/* Transitions fluides pour tous les éléments interactifs */
input, select, textarea, button {
  transition: all 0.2s ease;
}

/* Effet de focus amélioré */
input:focus, select:focus, textarea:focus {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

/* Animation pour la zone de drop */
#file-drop-zone {
  transition: all 0.3s ease;
}

#file-drop-zone:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Animation pour les boutons */
button {
  transform: translateY(0);
}

button:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

button:active {
  transform: translateY(0);
}

/* Style pour les champs valides */
.border-green-500 {
  border-color: #10b981 !important;
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

/* Style pour les champs invalides */
.border-red-500 {
  border-color: #ef4444 !important;
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

/* Animation du spinner */
@keyframes spin {
  from {
  transform: rotate(0deg);
  }
  to {
  transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

/* Styles CKEditor pour formulaire rapports */
.ck-editor {
  border: 1px solid #d1d5db !important;
  border-radius: 0.5rem !important;
  overflow: hidden;
}

.ck-editor__editable {
  min-height: 200px !important;
  padding: 1rem !important;
  font-size: 14px !important;
  line-height: 1.6 !important;
}

.ck-toolbar {
  border-bottom: 1px solid #e5e7eb !important;
  background: #f9fafb !important;
  padding: 0.75rem !important;
}

.ck-button:hover {
  background: #2563eb !important;
  color: white !important;
}

.ck-button.ck-on {
  background: #2563eb !important;
  color: white !important;
}

.ck-editor.ck-focused {
  border-color: #2563eb !important;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
}

/* Animation pour le drag & drop */
.border-blue-400 {
  border-color: #60a5fa !important;
}

.bg-blue-50 {
  background-color: #eff6ff !important;
}

/* Responsive improvements */
@media (max-width: 640px) {
  #rapport-form {
  margin: 0 -1rem;
  }
  
  .lg\\:grid-cols-2 {
  grid-template-columns: 1fr;
  }
  
  .ck-toolbar {
  padding: 0.5rem !important;
  }
  
  .ck-editor__editable {
  min-height: 150px !important;
  padding: 0.75rem !important;
  }
}
</style>
