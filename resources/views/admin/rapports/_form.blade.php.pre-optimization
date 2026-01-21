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

<!-- JavaScript moderne pour le formulaire -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du formulaire
    const form = document.getElementById('rapport-form').closest('form');
    const titreInput = document.getElementById('titre');
    const titreError = document.getElementById('titre-error');
    const fichierInput = document.getElementById('fichier');
    const descriptionTextarea = document.getElementById('description');
    const descriptionCount = document.getElementById('description-count');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitSpinner = document.getElementById('submit-spinner');
    
    // Zone de drop pour les fichiers
    const fileDropZone = document.getElementById('file-drop-zone');
    const fileDropContent = document.getElementById('file-drop-content');
    const fileSelected = document.getElementById('file-selected');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const fileIcon = document.getElementById('file-icon');
    const fileRemove = document.getElementById('file-remove');

    // Icônes pour les types de fichiers
    const fileIcons = {
        pdf: { icon: 'fas fa-file-pdf', color: 'text-red-500' },
        doc: { icon: 'fas fa-file-word', color: 'text-blue-600' },
        docx: { icon: 'fas fa-file-word', color: 'text-blue-600' },
        xls: { icon: 'fas fa-file-excel', color: 'text-green-600' },
        xlsx: { icon: 'fas fa-file-excel', color: 'text-green-600' },
        ppt: { icon: 'fas fa-file-powerpoint', color: 'text-orange-500' },
        pptx: { icon: 'fas fa-file-powerpoint', color: 'text-orange-500' },
        txt: { icon: 'fas fa-file-alt', color: 'text-gray-600' },
        rtf: { icon: 'fas fa-file-alt', color: 'text-gray-600' },
        default: { icon: 'fas fa-file', color: 'text-gray-500' }
    };

    // Fonction pour formater la taille du fichier
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Fonction pour obtenir l'extension du fichier
    function getFileExtension(filename) {
        return filename.split('.').pop().toLowerCase();
    }

    // Fonction pour afficher le fichier sélectionné
    function displaySelectedFile(file) {
        const extension = getFileExtension(file.name);
        const iconData = fileIcons[extension] || fileIcons.default;
        
        // Masquer le contenu de drop et afficher le fichier sélectionné
        fileDropContent.classList.add('hidden');
        fileSelected.classList.remove('hidden');
        
        // Mettre à jour les informations du fichier
        fileIcon.className = iconData.icon + ' ' + iconData.color;
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        
        // Changer le style de la zone de drop
        fileDropZone.classList.remove('border-gray-300');
        fileDropZone.classList.add('border-green-400', 'bg-green-50');
    }

    // Fonction pour réinitialiser l'affichage du fichier
    function resetFileDisplay() {
        fileDropContent.classList.remove('hidden');
        fileSelected.classList.add('hidden');
        fileDropZone.classList.remove('border-green-400', 'bg-green-50', 'border-red-400', 'bg-red-50');
        fileDropZone.classList.add('border-gray-300');
        fichierInput.value = '';
    }

    // Gestion du compteur de caractères pour la description
    if (descriptionTextarea && descriptionCount) {
        function updateCharCount() {
            const count = descriptionTextarea.value.length;
            descriptionCount.textContent = count + ' caractères';
            
            if (count > 500) {
                descriptionCount.classList.add('text-orange-500');
                descriptionCount.classList.remove('text-gray-400');
            } else {
                descriptionCount.classList.add('text-gray-400');
                descriptionCount.classList.remove('text-orange-500');
            }
        }
        
        updateCharCount();
        descriptionTextarea.addEventListener('input', updateCharCount);
    }

    // Gestion du drag & drop
    if (fileDropZone && fichierInput) {
        // Prévenir le comportement par défaut
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileDropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Effet visuel lors du survol
        ['dragenter', 'dragover'].forEach(eventName => {
            fileDropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileDropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            fileDropZone.classList.add('border-blue-400', 'bg-blue-50');
        }

        function unhighlight(e) {
            fileDropZone.classList.remove('border-blue-400', 'bg-blue-50');
        }

        // Gestion du drop
        fileDropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                const file = files[0];
                
                // Vérifier le type de fichier
                const allowedTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf'];
                const extension = getFileExtension(file.name);
                
                if (!allowedTypes.includes(extension)) {
                    alert('Type de fichier non supporté. Veuillez sélectionner un fichier PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT ou RTF.');
                    return;
                }
                
                // Vérifier la taille (10MB max)
                if (file.size > 10 * 1024 * 1024) {
                    alert('Le fichier est trop volumineux. Taille maximale autorisée: 10MB.');
                    return;
                }
                
                // Assigner le fichier à l'input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fichierInput.files = dataTransfer.files;
                
                displaySelectedFile(file);
            }
        }

        // Gestion du clic sur la zone de drop
        fileDropZone.addEventListener('click', () => {
            fichierInput.click();
        });

        // Gestion du changement de fichier via l'input
        fichierInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                displaySelectedFile(file);
            }
        });

        // Bouton pour supprimer le fichier
        if (fileRemove) {
            fileRemove.addEventListener('click', function(e) {
                e.stopPropagation();
                resetFileDisplay();
            });
        }
    }

    // Validation en temps réel du titre
    if (titreInput && titreError) {
        titreInput.addEventListener('input', function() {
            const value = this.value.trim();
            
            if (value.length === 0) {
                titreError.textContent = '';
                titreInput.classList.remove('border-red-500', 'border-green-500');
                titreInput.classList.add('border-gray-300');
            } else if (value.length < 3) {
                titreError.textContent = 'Le titre doit contenir au moins 3 caractères.';
                titreInput.classList.add('border-red-500');
                titreInput.classList.remove('border-green-500', 'border-gray-300');
            } else {
                titreError.textContent = '';
                titreInput.classList.add('border-green-500');
                titreInput.classList.remove('border-red-500', 'border-gray-300');
            }
        });
    }

    // Validation du formulaire
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Réinitialiser les erreurs
            titreError.textContent = '';
            titreInput.classList.remove('border-red-500');
            
            // Validation du titre
            const titreValue = titreInput.value.trim();
            if (!titreValue) {
                e.preventDefault();
                titreError.textContent = 'Le titre est requis.';
                titreInput.classList.add('border-red-500');
                titreInput.focus();
                isValid = false;
            } else if (titreValue.length < 3) {
                e.preventDefault();
                titreError.textContent = 'Le titre doit contenir au moins 3 caractères.';
                titreInput.classList.add('border-red-500');
                titreInput.focus();
                isValid = false;
            }
            
            // Afficher le spinner si le formulaire est valide
            if (isValid) {
                submitBtn.disabled = true;
                submitText.textContent = 'Enregistrement...';
                submitSpinner.classList.remove('hidden');
                
                // Réactiver le bouton après 10 secondes (sécurité)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitText.textContent = '{{ isset($rapport) ? "Mettre à jour le rapport" : "Créer le rapport" }}';
                    submitSpinner.classList.add('hidden');
                }, 10000);
            }
            
            return isValid;
        });
    }

    // Animation d'entrée
    setTimeout(() => {
        const formElement = document.getElementById('rapport-form');
        if (formElement) {
            formElement.style.opacity = '0';
            formElement.style.transform = 'translateY(20px)';
            formElement.style.transition = 'all 0.5s ease';
            
            requestAnimationFrame(() => {
                formElement.style.opacity = '1';
                formElement.style.transform = 'translateY(0)';
            });
        }
    }, 100);
});
</script>

<!-- Styles CSS pour les animations -->
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

/* Responsive improvements */
@media (max-width: 640px) {
    #rapport-form {
        margin: 0 -1rem;
    }
    
    .lg\\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
}
</style>
