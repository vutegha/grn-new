@if($errors->any())
    <div class="mx-6 mt-6">
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Erreurs de validation</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="space-y-8 p-6">
    @csrf
    @if(isset($method) && $method === 'PUT')
        @method('PUT')
    @endif

    {{-- Zone de téléchargement par glisser-déposer --}}
    <div class="space-y-4">
        <label class="block text-sm font-semibold text-iri-primary">
            <i class="fas fa-upload mr-2"></i>Fichier média
        </label>
        <div id="dropzone" class="relative border-2 border-dashed border-iri-light hover:border-iri-primary transition-colors duration-300 rounded-xl p-8 text-center bg-gradient-to-br from-iri-light/20 to-white cursor-pointer group">
            <div id="drop-content" class="space-y-4">
                <div class="mx-auto w-16 h-16 bg-gradient-to-r from-iri-primary to-iri-accent rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-cloud-upload-alt text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-iri-primary font-semibold">
                        {{ isset($media) && $media ? 'Remplacer le fichier existant' : 'Glissez-déposez votre fichier ici' }}
                    </p>
                    <p class="text-iri-gray text-sm">ou cliquez pour parcourir</p>
                    @if(isset($media) && $media)
                        <p class="text-xs text-gray-500 mt-1">Laissez vide pour conserver le fichier actuel</p>
                    @endif
                </div>
                <div class="text-xs text-iri-gray">
                    <p>Formats supportés : JPG, PNG, GIF, MP4, AVI, MOV</p>
                    <p>Taille maximale : 50MB</p>
                    <p class="text-green-600 font-medium">✓ Compression automatique des images</p>
                </div>
            </div>
            <div id="file-preview" class="hidden space-y-4">
                <div id="preview-container" class="mx-auto w-32 h-32 rounded-lg overflow-hidden border-2 border-iri-light"></div>
                <div id="file-info" class="text-sm text-iri-gray"></div>
                <button type="button" id="remove-file" class="text-red-500 hover:text-red-700 text-sm font-medium">
                    <i class="fas fa-times mr-1"></i>Supprimer
                </button>
            </div>
            <input type="file" name="medias" id="file-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*,video/*" {{ isset($media) && $media ? '' : 'required' }}>
        </div>
        @error('medias')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Informations du média --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-2">
            <label for="titre" class="block text-sm font-semibold text-iri-primary">
                <i class="fas fa-heading mr-2"></i>Titre *
            </label>
            <input type="text" name="titre" id="titre" 
                   value="{{ old('titre', $media->titre ?? '') }}" 
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 bg-white"
                   placeholder="Titre du média"
                   required>
            @error('titre')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Type --}}
        <div class="space-y-2">
            <label for="type" class="block text-sm font-semibold text-iri-primary">
                <i class="fas fa-tag mr-2"></i>Type *
            </label>
            <select name="type" id="type" 
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 bg-white"
                    required>
                <option value="">Sélectionnez un type</option>
                <option value="image" {{ old('type', $media->type ?? '') === 'image' ? 'selected' : '' }}>Image</option>
                <option value="video" {{ old('type', $media->type ?? '') === 'video' ? 'selected' : '' }}>Vidéo</option>
            </select>
            @error('type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Description --}}
    <div class="space-y-2">
        <label for="description" class="block text-sm font-semibold text-iri-primary">
            <i class="fas fa-align-left mr-2"></i>Description
        </label>
        <textarea name="description" id="description" rows="4" 
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 resize-none bg-white"
                  placeholder="Description du média...">{{ old('description', $media->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Projet associé --}}
    <div class="space-y-2">
        <label for="projet_id" class="block text-sm font-semibold text-iri-primary">
            <i class="fas fa-project-diagram mr-2"></i>Projet associé
        </label>
        <select name="projet_id" id="projet_id" 
                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 bg-white">
            <option value="">Aucun projet spécifique</option>
            @foreach($projets ?? [] as $projet)
                <option value="{{ $projet->id }}" {{ old('projet_id', $media->projet_id ?? '') == $projet->id ? 'selected' : '' }}>
                    {{ $projet->nom }}
                </option>
            @endforeach
        </select>
        @error('projet_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
        </label>
        <textarea name="description" id="description" rows="4"
                  class="w-full px-4 py-3 border border-iri-light rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm resize-none"
                  placeholder="Description du média...">{{ old('description', $media->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Projet associé --}}
    <div class="space-y-2">
        <label for="projet_id" class="block text-sm font-semibold text-iri-primary">
            <i class="fas fa-project-diagram mr-2"></i>Projet associé
        </label>
        <select name="projet_id" id="projet_id" 
                class="w-full px-4 py-3 border border-iri-light rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm">
            <option value="">Aucun projet</option>
            @if(isset($projets))
                @foreach($projets as $projet)
                    <option value="{{ $projet->id }}" {{ old('projet_id', $media->projet_id ?? '') == $projet->id ? 'selected' : '' }}>
                        {{ $projet->nom }}
                    </option>
                @endforeach
            @endif
        </select>
        @error('projet_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Aperçu du média existant --}}
    @if(isset($media) && $media && $media->medias)
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-iri-primary flex items-center">
                <i class="fas fa-image mr-2"></i>Média actuel
            </h3>
            <div class="bg-gray-50 rounded-xl p-6 border-2 border-gray-200">
                @if($media->type === 'image')
                    <img src="{{ asset('storage/' . $media->medias) }}" alt="Image" class="max-h-64 mx-auto rounded-lg shadow-lg">
                @elseif($media->type === 'video')
                    <video controls class="max-h-64 mx-auto rounded-lg shadow-lg">
                        <source src="{{ asset('storage/' . $media->medias) }}" type="video/mp4">
                        Votre navigateur ne prend pas en charge la lecture vidéo.
                    </video>
                @endif
                <p class="text-center mt-3 text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    {{ isset($media) && $media ? 'Laissez vide pour conserver le fichier actuel' : 'Sélectionnez un nouveau fichier' }}
                </p>
            </div>
        </div>
    @endif

    {{-- Boutons d'action --}}
    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-iri-light">
        <button type="submit" class="flex-1 bg-gradient-to-r from-iri-primary to-iri-accent text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg hover:scale-105 transition-all duration-200 flex items-center justify-center">
            <i class="fas fa-save mr-2"></i>
            {{ isset($media) && $media ? 'Mettre à jour' : 'Enregistrer' }}
        </button>
        <a href="{{ route('admin.media.index') }}" class="flex-1 bg-white border-2 border-iri-light text-iri-primary px-6 py-3 rounded-lg font-semibold hover:bg-iri-light/20 transition-all duration-200 flex items-center justify-center">
            <i class="fas fa-times mr-2"></i>
            Annuler
        </a>
    </div>
</form>

<style>
/* Toggle Switch Styles */
input:checked ~ .toggle-bg {
    @apply bg-gradient-to-r from-iri-primary to-iri-accent;
}
input:checked ~ .toggle-bg .dot {
    @apply translate-x-6;
}

/* Dropzone Styles */
#dropzone.dragover {
    @apply border-iri-primary bg-iri-primary/10;
}

/* File Preview Styles */
.file-preview-image {
    @apply w-full h-full object-cover rounded-lg;
}

.file-preview-video {
    @apply w-full h-full object-cover rounded-lg bg-iri-primary/20 flex items-center justify-center;
}
</style>

<!-- Script de compression d'images -->
<script src="{{ asset('assets/js/image-compressor.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('file-input');
    const dropContent = document.getElementById('drop-content');
    const filePreview = document.getElementById('file-preview');
    const previewContainer = document.getElementById('preview-container');
    const fileInfo = document.getElementById('file-info');
    const removeFileBtn = document.getElementById('remove-file');
    const typeSelect = document.getElementById('type');

    // Gestion du glisser-déposer
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, unhighlight, false);
    });

    dropzone.addEventListener('drop', handleDrop, false);
    fileInput.addEventListener('change', handleFileSelect, false);
    removeFileBtn.addEventListener('click', removeFile, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        dropzone.classList.add('dragover');
    }

    function unhighlight(e) {
        dropzone.classList.remove('dragover');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    function handleFileSelect(e) {
        const files = e.target.files;
        handleFiles(files);
    }

    function handleFiles(files) {
        if (files.length > 0) {
            const file = files[0];
            if (validateFile(file)) {
                compressAndShowPreview(file);
                autoDetectType(file);
            }
        }
    }

    async function compressAndShowPreview(file) {
        try {
            // Afficher un indicateur de compression pour les images
            if (file.type.startsWith('image/') && file.type !== 'image/svg+xml') {
                showCompressionIndicator();
                
                console.log('Compression de l\'image en cours...');
                const compressedFile = await defaultCompressor.compressImage(file);
                
                // Remplacer le fichier dans l'input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(compressedFile);
                fileInput.files = dataTransfer.files;
                
                // Afficher l'aperçu du fichier compressé
                showPreview(compressedFile, file);
                
                console.log('Compression terminée:', {
                    originalSize: formatFileSize(file.size),
                    compressedSize: formatFileSize(compressedFile.size),
                    compressionRatio: defaultCompressor.getCompressionInfo(file, compressedFile).compressionRatio.toFixed(1) + '%'
                });
            } else {
                // Pour les vidéos et SVG, afficher directement
                showPreview(file);
            }
        } catch (error) {
            console.error('Erreur lors de la compression:', error);
            // En cas d'erreur, afficher le fichier original
            showPreview(file);
        }
    }

    function showCompressionIndicator() {
        dropContent.classList.add('hidden');
        filePreview.classList.remove('hidden');
        
        previewContainer.innerHTML = `
            <div class="w-full h-full flex items-center justify-center bg-blue-50 rounded-lg">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
                    <p class="text-sm text-blue-600 font-medium">Compression en cours...</p>
                </div>
            </div>
        `;
        
        fileInfo.innerHTML = '<p class="text-sm text-blue-600">Optimisation de l\'image...</p>';
    }

    function validateFile(file) {
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'video/mp4', 'video/avi', 'video/mov', 'video/quicktime'];
        const maxSize = 50 * 1024 * 1024; // 50MB

        if (!validTypes.includes(file.type)) {
            alert('Format de fichier non supporté. Utilisez : JPG, PNG, GIF, MP4, AVI, MOV');
            return false;
        }

        if (file.size > maxSize) {
            alert('Fichier trop volumineux. Taille maximale : 50MB');
            return false;
        }

        return true;
    }

    function showPreview(file, originalFile = null) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            dropContent.classList.add('hidden');
            filePreview.classList.remove('hidden');
            
            if (file.type.startsWith('image/')) {
                previewContainer.innerHTML = `<img src="${e.target.result}" class="file-preview-image" alt="Aperçu">`;
            } else if (file.type.startsWith('video/')) {
                previewContainer.innerHTML = `
                    <div class="file-preview-video">
                        <i class="fas fa-video text-4xl text-iri-primary"></i>
                    </div>
                `;
            }
            
            // Configuration des informations du fichier
            let fileInfoHtml = `
                <p class="font-medium">${file.name}</p>
                <p class="text-xs">${formatFileSize(file.size)} • ${file.type}</p>
            `;
            
            // Si c'est un fichier compressé, afficher les informations de compression
            if (originalFile && originalFile !== file) {
                const info = defaultCompressor.getCompressionInfo(originalFile, file);
                fileInfoHtml += `
                    <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded text-xs">
                        <div class="flex items-center text-green-800 mb-1">
                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">Compressée</span>
                        </div>
                        <div class="text-green-700">
                            <div>Original: ${formatFileSize(originalFile.size)}</div>
                            <div>Économie: ${info.compressionRatio.toFixed(1)}%</div>
                        </div>
                    </div>
                `;
            }
            
            fileInfo.innerHTML = fileInfoHtml;
        };
        
        reader.readAsDataURL(file);
    }

    function autoDetectType(file) {
        if (file.type.startsWith('image/')) {
            typeSelect.value = 'image';
        } else if (file.type.startsWith('video/')) {
            typeSelect.value = 'video';
        }
    }

    function removeFile() {
        fileInput.value = '';
        dropContent.classList.remove('hidden');
        filePreview.classList.add('hidden');
        previewContainer.innerHTML = '';
        fileInfo.innerHTML = '';
        typeSelect.value = '';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>
