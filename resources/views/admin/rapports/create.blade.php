@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white transition-colors duration-200">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <a href="{{ route('admin.rapports.index') }}" class="text-white/70 hover:text-white transition-colors duration-200">Rapports</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white font-medium">Nouveau Rapport</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Créer un Rapport')
@section('subtitle', 'Ajouter un nouveau rapport institutionnel à la bibliothèque GRN-UCBC')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête avec design IRI -->
        <div class="bg-white rounded-lg shadow-sm border-l-4 border-iri-primary mb-8">
            <div class="px-6 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-iri-primary rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-plus text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-2xl font-poppins font-bold text-iri-primary">Créer un Rapport</h1>
                        <p class="text-iri-gray">Ajouter un nouveau rapport institutionnel à la bibliothèque GRN-UCBC</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages d'erreur -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-red-800 font-medium">Erreurs détectées</h3>
                        <div class="mt-2 text-red-700">
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

        <!-- Formulaire -->
        <form action="{{ route('admin.rapports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: Informations générales -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-6 py-4 rounded-t-lg">
                    <h2 class="text-lg font-poppins font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informations Générales
                    </h2>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- Titre -->
                    <div>
                        <label for="titre" class="block text-sm font-medium text-iri-primary mb-2">
                            <i class="fas fa-heading text-iri-accent mr-1"></i>
                            Titre du rapport <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="titre" 
                               id="titre" 
                               value="{{ old('titre') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors @error('titre') border-red-500 @enderror"
                               placeholder="Entrez le titre du rapport" 
                               required>
                        @error('titre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-iri-primary mb-2">
                            <i class="fas fa-align-left text-iri-accent mr-1"></i>
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors @error('description') border-red-500 @enderror"
                                  placeholder="Décrivez le contenu du rapport">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Catégorie -->
                        <div>
                            <label for="categorie_id" class="block text-sm font-medium text-iri-primary mb-2">
                                <i class="fas fa-folder text-iri-accent mr-1"></i>
                                Catégorie
                            </label>
                            <select name="categorie_id" 
                                    id="categorie_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors @error('categorie_id') border-red-500 @enderror">
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                        {{ $categorie->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categorie_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date de publication -->
                        <div>
                            <label for="date_publication" class="block text-sm font-medium text-iri-primary mb-2">
                                <i class="fas fa-calendar text-iri-accent mr-1"></i>
                                Date de publication <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="date_publication" 
                                   id="date_publication" 
                                   value="{{ old('date_publication', now()->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors @error('date_publication') border-red-500 @enderror"
                                   required>
                            @error('date_publication')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Document -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="bg-gradient-to-r from-iri-accent to-iri-gold px-6 py-4 rounded-t-lg">
                    <h2 class="text-lg font-poppins font-semibold text-white flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Document PDF
                    </h2>
                </div>
                
                <div class="p-6">
                    <div>
                        <label for="fichier" class="block text-sm font-medium text-iri-primary mb-2">
                            <i class="fas fa-upload text-iri-accent mr-1"></i>
                            Fichier PDF
                        </label>
                        <input type="file" 
                               name="fichier" 
                               id="fichier" 
                               accept=".pdf"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-iri-primary file:text-white hover:file:bg-iri-secondary @error('fichier') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-iri-gray">Sélectionnez le fichier PDF du rapport (max 50MB)</p>
                        @error('fichier')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Options de publication -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 rounded-t-lg">
                    <h2 class="text-lg font-poppins font-semibold text-white flex items-center">
                        <i class="fas fa-eye mr-2"></i>
                        Options de Publication
                    </h2>
                </div>
                
                <div class="p-6 space-y-4">
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        <span class="font-medium">Information :</span>
                                        Le rapport sera enregistré et pourra être publié ultérieurement depuis la liste des rapports.
                                    </p>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3 justify-end pt-6">
                <a href="{{ route('admin.rapports.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold rounded-lg hover:from-iri-secondary hover:to-iri-primary focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transition-all duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Créer le rapport
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<!-- CKEditor 5 v34.2.0 - Version Open Source pour rapports -->
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initialisation CKEditor pour création de rapport');
    
    // Vérifier si CKEditor est disponible
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
                    placeholder: 'Décrivez le contenu du rapport...'
                })
                .then(editor => {
                    console.log('✅ CKEditor initialisé avec succès');
                    
                    // Style personnalisé pour l'éditeur
                    const editorElement = editor.ui.element;
                    if (editorElement) {
                        editorElement.style.borderColor = '#d1d5db';
                        editorElement.style.borderRadius = '0.5rem';
                        editorElement.style.overflow = 'hidden';
                    }
                })
                .catch(error => {
                    console.error('❌ Erreur initialisation CKEditor:', error);
                    
                    // Fallback textarea amélioré
                    descriptionElement.style.minHeight = '150px';
                    descriptionElement.style.resize = 'vertical';
                    console.log('📝 Mode textarea activé en fallback');
                });
        } else {
            console.warn('⚠️ Élément #description non trouvé');
        }
    } else {
        console.error('❌ ClassicEditor non disponible');
    }
});
</script>

<style>
/* Styles CKEditor pour page de création */
.ck-editor {
    border: 1px solid #d1d5db !important;
    border-radius: 0.5rem !important;
    overflow: hidden;
}

.ck-editor__editable {
    min-height: 150px !important;
    padding: 1rem !important;
    font-size: 14px !important;
    line-height: 1.6 !important;
}

.ck-toolbar {
    border-bottom: 1px solid #e5e7eb !important;
    background: #f9fafb !important;
}

.ck-button:hover {
    background: var(--iri-primary) !important;
    color: white !important;
}

.ck-button.ck-on {
    background: var(--iri-primary) !important;
    color: white !important;
}

.ck-editor.ck-focused {
    border-color: var(--iri-primary) !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
}
</style>
@endpush