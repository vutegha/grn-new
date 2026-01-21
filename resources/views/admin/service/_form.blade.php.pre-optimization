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
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<!-- Script de compression d'images -->
<script src="{{ asset('assets/js/image-compressor.js') }}"></script>
<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('image-placeholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Initialiser CKEditor pour description
document.addEventListener('DOMContentLoaded', function() {
    ClassicEditor
        .create(document.querySelector('#description'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', '|',
                'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'undo', 'redo', '|',
                'link', 'insertTable', '|',
                'alignment', '|',
                'fontColor', 'fontBackgroundColor', '|',
                'fontSize', '|',
                'specialCharacters'
            ],
            language: 'fr',
            table: {
                contentToolbar: [
                    'tableColumn', 'tableRow', 'mergeTableCells'
                ]
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            },
            image: {
                toolbar: [
                    'imageTextAlternative',
                    'imageStyle:inline',
                    'imageStyle:block',
                    'imageStyle:side'
                ]
            }
        })
        .then(editor => {
            console.log('CKEditor initialisé avec succès pour Service');
            
            // Associer l'éditeur à l'élément
            document.querySelector('#description').ckeditorInstance = editor;
            window.descriptionEditor = editor;
            
            // Style personnalisé pour CKEditor
            editor.editing.view.change(writer => {
                writer.setStyle('min-height', '400px', editor.editing.view.document.getRoot());
            });

            // Ajouter le bouton de médiathèque à la toolbar
            addMediaLibraryButton(editor);

            // Configurer l'adaptateur d'upload personnalisé
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MediaUploadAdapter(loader);
            };
        })
        .catch(error => {
            console.error('Erreur CKEditor:', error);
        });
});

// Adaptateur d'upload personnalisé pour CKEditor
class MediaUploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file
            .then(file => new Promise((resolve, reject) => {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                fetch('{{ route("admin.media.upload") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        resolve({
                            default: result.url
                        });
                    } else {
                        reject(result.message || 'Erreur lors de l\'upload');
                    }
                })
                .catch(error => {
                    reject('Erreur réseau lors de l\'upload');
                });
            }));
    }

    abort() {
        // Gérer l'annulation de l'upload si nécessaire
    }
}

// Gestion de l'upload dans la modal
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('mediaUploadForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const fileInput = this.querySelector('input[name="image"]');
            const originalFile = fileInput.files[0];
            
            if (!originalFile) {
                alert('Veuillez sélectionner un fichier');
                return;
            }
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            try {
                // Désactiver le bouton et afficher l'état de compression
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Compression...';
                
                // Compresser l'image si c'est une image
                let fileToUpload = originalFile;
                if (originalFile.type.startsWith('image/')) {
                    fileToUpload = await defaultCompressor.compressImage(originalFile);
                    console.log('Compression terminée:', {
                        originalSize: formatFileSize(originalFile.size),
                        compressedSize: formatFileSize(fileToUpload.size)
                    });
                }
                
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Upload...';
                
                const formData = new FormData();
                formData.append('image', fileToUpload);
                formData.append('_token', '{{ csrf_token() }}');
                
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Recharger la liste des médias
                    loadMediaList(window.currentMediaCallback);
                    // Réinitialiser le formulaire
                    this.reset();
                    
                    // Message de succès avec info de compression
                    let message = 'Image uploadée avec succès !';
                    if (originalFile.type.startsWith('image/') && fileToUpload !== originalFile) {
                        const info = defaultCompressor.getCompressionInfo(originalFile, fileToUpload);
                        message += ` (Compressée: ${info.compressionRatio.toFixed(1)}% d'économie)`;
                    }
                    alert(message);
                } else {
                    alert('Erreur lors de l\'upload: ' + result.message);
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'upload');
            } finally {
                // Réactiver le bouton
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        });
    }

    // Fermer le modal en cliquant à l'extérieur
    const modal = document.getElementById('mediaModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeMediaModal();
            }
        });
    }

    // Fermer le modal avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('mediaModal').classList.contains('hidden')) {
            closeMediaModal();
        }
    });
});

// Ajouter un bouton personnalisé pour la médiathèque dans CKEditor
function addMediaLibraryButton(editor) {
    const toolbar = editor.ui.view.toolbar;
    const mediaButton = document.createElement('button');
    mediaButton.type = 'button';
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
            // Ajouter un séparateur
            const separator = document.createElement('span');
            separator.className = 'ck ck-toolbar__separator';
            toolbarElement.appendChild(separator);
            
            toolbarElement.appendChild(mediaButton);
        }
    }, 100);
}

// Ouvrir la modal de médiathèque
function openMediaModal(callback) {
    console.log('=== DÉBUT openMediaModal ===');
    
    // Sauvegarder le callback globalement pour l'upload
    window.currentMediaCallback = callback;
    
    const modal = document.getElementById('mediaModal');
    if (!modal) {
        console.error('Modal mediaModal non trouvé !');
        return;
    }
    
    console.log('Modal trouvé, ouverture...');
    
    // Sauvegarder la position de scroll actuelle
    const scrollY = window.scrollY;
    
    // S'assurer que le modal est centré par rapport au viewport
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100vw';
    modal.style.height = '100vh';
    modal.style.display = 'flex';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    modal.style.zIndex = '9999';
    modal.style.margin = '0';
    modal.style.padding = '1rem';
    
    // Empêcher le scroll du body
    document.body.classList.add('modal-open');
    document.body.style.top = `-${scrollY}px`;
    
    modal.classList.remove('hidden');
    window._mediaInsertCallback = callback;
    
    // Empêcher la fermeture automatique du modal
    if (window.event) {
        window.event.stopPropagation();
        window.event.preventDefault();
    }
    
    console.log('Chargement de la liste des médias...');
    loadMediaList(callback);
    console.log('=== FIN openMediaModal ===');
}

function closeMediaModal() {
    console.log('=== FERMETURE du modal ===');
    const modal = document.getElementById('mediaModal');
    if (modal) {
        // Récupérer la position de scroll sauvegardée
        const scrollY = document.body.style.top;
        
        modal.classList.add('hidden');
        modal.style.display = 'none';
        
        // Restaurer le scroll du body
        document.body.classList.remove('modal-open');
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        document.body.style.overflow = '';
        
        // Restaurer la position de scroll
        if (scrollY) {
            window.scrollTo(0, parseInt(scrollY || '0') * -1);
        }
        
        window._mediaInsertCallback = null;
    }
}

// Charger la liste des images depuis le backend
function loadMediaList(callback) {
    console.log('Chargement de la liste des médias...');
    fetch("{{ route('admin.media.list') }}")
        .then(res => {
            console.log('Réponse reçue:', res.status);
            if (!res.ok) {
                throw new Error('Erreur réseau: ' + res.status);
            }
            return res.json();
        })
        .then(data => {
            console.log('Données reçues:', data);
            const mediaList = document.getElementById('mediaList');
            mediaList.innerHTML = '';
            
            if (data.length === 0) {
                mediaList.innerHTML = '<p class="text-gray-500 text-center col-span-full">Aucune image disponible</p>';
                return;
            }
            
            data.forEach((media, index) => {
                console.log('Traitement du média:', media);
                const div = document.createElement('div');
                div.className = 'border rounded-lg overflow-hidden cursor-pointer hover:shadow-lg transition duration-200';
                div.innerHTML = `
                    <img src="${media.url}" 
                         alt="${media.name}" 
                         class="w-full h-24 object-cover"
                         onerror="console.error('Erreur de chargement image:', this.src); this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDJMMTMuMDkgOC4yNkwyMCA5TDEzLjA5IDE1Ljc0TDEyIDIyTDEwLjkxIDE1Ljc0TDQgOUwxMC45MSA4LjI2TDEyIDJaIiBzdHJva2U9IiNjY2MiIGZpbGw9IiNmZmYiLz4KPHN2Zz4K';">
                    <div class="p-2">
                        <p class="text-xs text-gray-600 truncate">${media.name}</p>
                    </div>
                `;
                div.onclick = function(e) {
                    e.stopPropagation();
                    console.log('Image sélectionnée:', media.url);
                    if(callback) {
                        callback(media.url);
                        closeMediaModal();
                    }
                };
                mediaList.appendChild(div);
            });
        })
        .catch(error => {
            console.error('Erreur:', error);
            const mediaList = document.getElementById('mediaList');
            mediaList.innerHTML = `<div class="col-span-full text-center py-8 text-red-500">
                <i class="fas fa-exclamation-triangle mb-2"></i><br>
                Impossible de charger la médiathèque.<br>
                <small class="text-gray-500">Erreur: ${error.message}</small><br>
                <button onclick="loadMediaList(window.currentMediaCallback)" class="mt-2 px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600">
                    Réessayer
                </button>
            </div>`;
        });
}

// Variables globales pour la modal de médias  
let selectedMedia = null;
</script>
