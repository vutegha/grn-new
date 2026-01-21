@extends('layouts.admin')

@section('title', isset($actualite) ? 'Modifier l\'actualité' : 'Créer une actualité')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-6 py-4">
                    <h1 class="text-xl font-bold text-white">
                        {{ isset($actualite) ? 'Modifier l\'actualité' : 'Nouvelle actualité' }}
                    </h1>
                    <p class="text-blue-100 text-sm mt-1">
                        {{ isset($actualite) ? 'Modifiez les informations de cette actualité' : 'Créez une nouvelle actualité' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Messages d'erreur -->
        @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        {{ $errors->count() }} erreur(s) de validation
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Messages de succès -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Formulaire -->
        <form method="POST" 
              action="{{ isset($actualite) ? route('admin.actualite.update', $actualite) : route('admin.actualite.store') }}" 
              enctype="multipart/form-data" 
              class="space-y-6">
            
            @csrf
            @if(isset($actualite))
                @method('PUT')
            @endif

            <!-- Informations générales -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-info-circle mr-2 text-iri-primary"></i>
                        Informations générales
                    </h3>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Titre -->
                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                            Titre de l'actualité <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="titre" 
                               id="titre"
                               value="{{ old('titre', $actualite->titre ?? '') }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary/20 focus:border-iri-primary @error('titre') border-red-500 @enderror"
                               placeholder="Saisissez un titre accrocheur..."
                               required
                               maxlength="255">
                        @error('titre')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catégorie et Service -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Catégorie -->
                        <div>
                            <label for="categorie_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Catégorie <span class="text-red-500">*</span>
                            </label>
                            <select name="categorie_id" 
                                    id="categorie_id"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary/20 focus:border-iri-primary"
                                    required>
                                <option value="">Sélectionner une catégorie</option>
                                @if(isset($categories))
                                    @foreach($categories as $categorie)
                                        <option value="{{ $categorie->id }}" 
                                                {{ old('categorie_id', $actualite->categorie_id ?? '') == $categorie->id ? 'selected' : '' }}>
                                            {{ $categorie->nom }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Service -->
                        <div>
                            <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Service associé
                            </label>
                            <select name="service_id" 
                                    id="service_id"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary/20 focus:border-iri-primary">
                                <option value="">Aucun service</option>
                                @foreach(\App\Models\Service::all() as $service)
                                    <option value="{{ $service->id }}" 
                                            {{ old('service_id', $actualite->service_id ?? '') == $service->id ? 'selected' : '' }}>
                                        {{ $service->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Résumé -->
                    <div>
                        <label for="resume" class="block text-sm font-medium text-gray-700 mb-2">
                            Résumé de l'actualité
                        </label>
                        <textarea name="resume" 
                                  id="resume"
                                  rows="3"
                                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary/20 focus:border-iri-primary"
                                  placeholder="Décrivez brièvement cette actualité...">{{ old('resume', $actualite->resume ?? '') }}</textarea>
                    </div>

                    <!-- Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Image à la une
                        </label>
                        <input type="file" 
                               name="image" 
                               id="image"
                               accept="image/*"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary/20 focus:border-iri-primary">
                        @if(isset($actualite) && $actualite->image)
                            <p class="mt-2 text-sm text-gray-500">Image actuelle : {{ $actualite->image }}</p>
                        @endif
                    </div>

                    <!-- Options de publication -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="a_la_une" 
                                   name="a_la_une" 
                                   value="1"
                                   {{ old('a_la_une', $actualite->a_la_une ?? false) ? 'checked' : '' }}
                                   class="h-4 w-4 text-iri-primary focus:ring-iri-primary border-gray-300 rounded">
                            <label for="a_la_une" class="ml-2 block text-sm text-gray-900">
                                🌟 À la une
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="en_vedette" 
                                   name="en_vedette" 
                                   value="1"
                                   {{ old('en_vedette', $actualite->en_vedette ?? false) ? 'checked' : '' }}
                                   class="h-4 w-4 text-iri-primary focus:ring-iri-primary border-gray-300 rounded">
                            <label for="en_vedette" class="ml-2 block text-sm text-gray-900">
                                ⭐ En vedette
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_published" 
                                   name="is_published" 
                                   value="1"
                                   {{ old('is_published', $actualite->is_published ?? false) ? 'checked' : '' }}
                                   class="h-4 w-4 text-iri-primary focus:ring-iri-primary border-gray-300 rounded">
                            <label for="is_published" class="ml-2 block text-sm text-gray-900">
                                ✅ Publier
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu avec CKEditor -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-edit mr-2 text-iri-primary"></i>
                        Contenu de l'actualité
                    </h3>
                </div>
                
                <div class="p-6">
                    <label for="contenu" class="block text-sm font-medium text-gray-700 mb-2">
                        Contenu <span class="text-red-500">*</span>
                    </label>
                    
                    <textarea name="texte" 
                              id="contenu" 
                              rows="15"
                              required
                              class="w-full px-4 py-4 border-2 border-gray-300 rounded-lg">{{ old('texte', $actualite->texte ?? '') }}</textarea>
                    
                    @error('texte')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-between pt-6">
                <a href="{{ route('admin.actualite.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 bg-iri-primary hover:bg-iri-secondary text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    {{ isset($actualite) ? 'Mettre à jour' : 'Créer' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/translations/fr.js"></script>

<script>
// ===========================
// INITIALISATION CKEDITOR 5
// ===========================

console.log('🚀 Script chargé - Initialisation CKEditor...');

// Vérifier que CKEditor est disponible
if (typeof ClassicEditor === 'undefined') {
    console.error('❌ ClassicEditor n\'est pas défini ! Vérifiez le chargement du CDN.');
} else {
    console.log('✅ ClassicEditor est disponible');
}

// Initialiser CKEditor au chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 DOM chargé');
    
    const editorElement = document.querySelector('#contenu');
    
    if (!editorElement) {
        console.error('❌ Element #contenu introuvable dans le DOM');
        return;
    }
    
    console.log('✅ Element #contenu trouvé');
    console.log('🎨 Initialisation de CKEditor...');
    
    ClassicEditor
        .create(editorElement, {
            language: 'fr',
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', '|',
                    'link', 'bulletedList', 'numberedList', '|',
                    'indent', 'outdent', '|',
                    'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ],
                shouldNotGroupWhenFull: true
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraphe', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Titre 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Titre 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Titre 3', class: 'ck-heading_heading3' }
                ]
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            },
            placeholder: 'Saisissez le contenu de votre actualité ici...'
        })
        .then(editor => {
            console.log('✅ CKEditor initialisé avec succès !');
            console.log('📦 Nombre de plugins:', editor.plugins._plugins.size);
            window.actualiteEditor = editor;
            
            // Afficher les barres d'outils
            console.log('🎨 Interface CKEditor prête');
        })
        .catch(error => {
            console.error('❌ Erreur d\'initialisation de CKEditor:', error);
            console.error('Stack:', error.stack);
            
            // Afficher un message à l'utilisateur
            const errorDiv = document.createElement('div');
            errorDiv.className = 'mt-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700';
            errorDiv.innerHTML = `
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium">Erreur de chargement de l'éditeur</h3>
                        <div class="mt-2 text-sm">
                            <p>L'éditeur visuel n'a pas pu se charger. Vous pouvez toujours utiliser le champ texte.</p>
                            <p class="mt-1"><strong>Erreur:</strong> ${error.message}</p>
                        </div>
                    </div>
                </div>
            `;
            editorElement.parentNode.insertBefore(errorDiv, editorElement.nextSibling);
        });
});

// Nettoyage avant de quitter la page
window.addEventListener('beforeunload', function() {
    if (window.actualiteEditor) {
        console.log('🧹 Nettoyage de CKEditor');
        try {
            window.actualiteEditor.destroy();
        } catch (e) {
            console.warn('Erreur lors du nettoyage:', e);
        }
    }
});

console.log('✅ Script CKEditor configuré et prêt');
</script>
@endsection
