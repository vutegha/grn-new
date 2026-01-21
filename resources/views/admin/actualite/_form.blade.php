<!-- Container principal avec design moderne inspiré du formulaire projets -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
  
  <!-- En-tête moderne -->
  <div class="mb-8">
  <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-white/20">
  <div class="bg-gradient-to-r from-iri-primary via-blue-600 to-iri-secondary px-8 py-6">
  <div class="flex items-center justify-between">
  <div class="flex items-center space-x-4">
  <div class="flex-shrink-0">
  <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
  <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
  </svg>
  </div>
  </div>
  <div>
  <h1 class="text-2xl font-bold text-white">
  {{ isset($actualite) ? 'Modifier l\'actualité' : 'Nouvelle actualité' }}
  </h1>
  <p class="text-blue-100 text-sm mt-1">
  {{ isset($actualite) ? 'Modifiez les informations de cette actualité' : 'Créez une nouvelle actualité pour votre organisation' }}
  </p>
  </div>
  </div>
  <div class="hidden md:block">
  <div class="text-white/80 text-sm text-right">
  <div>{{ date('d/m/Y') }}</div>
  <div class="text-xs">{{ now()->format('H:i') }}</div>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>

  <!-- Messages de succès/erreur avec design moderne -->
  @if ($errors->any())
  <div class="mb-6">
  <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-2xl p-6 shadow-lg">
  <div class="flex items-start">
  <div class="flex-shrink-0">
  <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
  <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
  </svg>
  </div>
  </div>
  <div class="ml-4 flex-1">
  <h3 class="text-red-800 font-semibold text-lg">{{ $errors->count() }} erreur(s) détectée(s)</h3>
  <p class="text-red-700 text-sm mt-1 mb-3">Veuillez corriger les champs suivants :</p>
  <div class="space-y-2 bg-white/60 rounded-lg p-3">
  @foreach ($errors->all() as $error)
  <div class="flex items-start p-2 bg-white/80 rounded-md border-l-4 border-red-400">
  <svg class="h-4 w-4 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 8 8">
  <circle cx="4" cy="4" r="3"/>
  </svg>
  <span class="text-red-700 text-sm">{{ $error }}</span>
  </div>
  @endforeach
  </div>
  </div>
  </div>
  </div>
  </div>
  @endif

  @if(session('success'))
  <div class="mb-6">
  <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6 shadow-lg">
  <div class="flex items-center">
  <div class="flex-shrink-0">
  <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
  <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
  </svg>
  </div>
  </div>
  <div class="ml-4">
  <p class="text-green-800 font-medium">{{ session('success') }}</p>
  </div>
  </div>
  </div>
  </div>
  @endif

  <!-- Formulaire principal -->
  <div class="w-full">
  <form id="actualite-form" 
        action="{{ isset($actualite) ? route('admin.actualite.update', $actualite) : route('admin.actualite.store') }}" 
        method="POST" 
        enctype="multipart/form-data" 
        class="space-y-8">
  @csrf
  @if(isset($actualite))
  @method('PUT')
  @endif

  <!-- Section 1: Informations générales -->
  <div class="bg-white rounded-2xl shadow-xl border border-white/20 overflow-hidden">
  <div class="bg-gradient-to-r from-slate-50 to-blue-50 px-6 py-4 border-b border-gray-100">
  <div class="flex items-center space-x-3">
  <div class="w-8 h-8 bg-iri-primary rounded-lg flex items-center justify-center">
  <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>
  </div>
  <div>
  <h3 class="text-lg font-bold text-gray-900">Informations générales</h3>
  <p class="text-sm text-gray-600">Renseignez les informations de base de l'actualité</p>
  </div>
  </div>
  </div>
  
  <div class="p-6 space-y-6">
  <!-- Titre -->
  <div>
  <label for="titre" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
  <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-1.414.586H7a4 4 0 01-4-4V7a4 4 0 014-4z"></path>
  </svg>
  Titre de l'actualité
  <span class="text-red-500 ml-1">*</span>
  </label>
  <input type="text" 
  name="titre" 
  id="titre"
  value="{{ old('titre', $actualite->titre ?? '') }}"
  class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white placeholder-gray-400 @error('titre') border-red-500 bg-red-50 @enderror"
  placeholder="Saisissez un titre accrocheur et informatif..."
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
  <label for="categorie_id" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
  <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v9a2 2 0 01-2 2h-3l-4 4z"></path>
  </svg>
  Catégorie
  <span class="text-red-500 ml-1">*</span>
  </label>
  <select name="categorie_id" 
  id="categorie_id"
  class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('categorie_id') border-red-500 bg-red-50 @enderror"
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
  @error('categorie_id')
  <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
  @enderror
  </div>

  <!-- Service -->
  <div>
  <label for="service_id" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
  <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
  </svg>
  Service associé
  </label>
  <select name="service_id" 
  id="service_id"
  class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white">
  <option value="">Aucun service spécifique</option>
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
  <label for="resume" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
  <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
  </svg>
  Résumé
  </label>
  <textarea name="resume" 
  id="resume"
  rows="3"
  class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white placeholder-gray-400 @error('resume') border-red-500 bg-red-50 @enderror"
  placeholder="Un bref résumé de l'actualité (sera affiché dans les listes et aperçus)...">{{ old('resume', $actualite->resume ?? '') }}</textarea>
  @error('resume')
  <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
  @enderror
  </div>

  <!-- Image -->
  <div>
  <label for="image" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
  <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
  </svg>
  Image à la une
  </label>
  <input type="file" 
  name="image" 
  id="image"
  accept="image/jpeg,image/png,image/jpg,image/webp"
  class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('image') border-red-500 bg-red-50 @enderror">
  
  <p class="mt-2 text-xs text-gray-500">
  <svg class="h-3 w-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>
  Formats acceptés : JPG, JPEG, PNG, WebP • Taille maximale : 5 MB
  </p>
  
  @if(isset($actualite) && $actualite->image)
  <div class="mt-3 inline-flex items-center px-3 py-1 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
  <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
  <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
  </svg>
  Image actuelle : {{ basename($actualite->image) }}
  </div>
  @endif
  
  @error('image')
  <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
  @enderror
  </div>

  <!-- Options de publication -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
  <div class="flex items-center p-3 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-xl hover:shadow-md transition-shadow">
  <input type="checkbox" 
  id="a_la_une" 
  name="a_la_une" 
  value="1"
  {{ old('a_la_une', $actualite->a_la_une ?? false) ? 'checked' : '' }}
  class="h-5 w-5 text-iri-primary focus:ring-iri-primary border-gray-300 rounded">
  <label for="a_la_une" class="ml-3 text-sm font-medium text-gray-900 cursor-pointer">
  🌟 À la une
  </label>
  </div>

  <div class="flex items-center p-3 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-xl hover:shadow-md transition-shadow">
  <input type="checkbox" 
  id="en_vedette" 
  name="en_vedette" 
  value="1"
  {{ old('en_vedette', $actualite->en_vedette ?? false) ? 'checked' : '' }}
  class="h-5 w-5 text-iri-primary focus:ring-iri-primary border-gray-300 rounded">
  <label for="en_vedette" class="ml-3 text-sm font-medium text-gray-900 cursor-pointer">
  ⭐ En vedette
  </label>
  </div>

  <div class="flex items-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl hover:shadow-md transition-shadow">
  <input type="checkbox" 
  id="is_published" 
  name="is_published" 
  value="1"
  {{ old('is_published', $actualite->is_published ?? false) ? 'checked' : '' }}
  class="h-5 w-5 text-iri-primary focus:ring-iri-primary border-gray-300 rounded">
  <label for="is_published" class="ml-3 text-sm font-medium text-gray-900 cursor-pointer">
  ✅ Publier
  </label>
  </div>
  </div>
  </div>
  </div>

  <!-- Section 2: Contenu de l'actualité -->
  <div class="bg-white rounded-2xl shadow-xl border border-white/20 overflow-hidden">
  <div class="bg-gradient-to-r from-slate-50 to-blue-50 px-6 py-4 border-b border-gray-100">
  <div class="flex items-center justify-between">
  <div class="flex items-center space-x-3">
  <div class="w-8 h-8 bg-iri-primary rounded-lg flex items-center justify-center">
  <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
  </svg>
  </div>
  <div>
  <h3 class="text-lg font-bold text-gray-900">Contenu de l'actualité</h3>
  <p class="text-sm text-gray-600">Rédigez le contenu détaillé de votre actualité</p>
  </div>
  </div>
  </div>
  </div>
  
  <div class="p-6">
  <div class="space-y-2">
  <label for="texte" class="flex items-center text-sm font-semibold text-gray-700">
  <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
  </svg>
  Contenu
  <span class="text-red-500 ml-1">*</span>
  </label>
  
  <textarea name="texte" 
  id="texte" 
  class="wysiwyg w-full px-4 py-4 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white placeholder-gray-400 min-h-[400px] @error('texte') border-red-500 bg-red-50 @enderror"
  style="min-height: 400px;"
  rows="16"
  required>{{ old('texte', $actualite->texte ?? '') }}</textarea>
  
  @error('texte')
  <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
  @enderror

  <!-- Guide de rédaction -->
  <div class="mt-3 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl">
  <div class="flex items-start space-x-3">
  <div class="flex-shrink-0 mt-1">
  <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>
  </div>
  <div class="flex-1">
  <h4 class="text-sm font-semibold text-blue-800 mb-2">💡 Guide de rédaction</h4>
  <div class="text-sm text-blue-700 space-y-2">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
  <div>
  <div class="font-medium mb-1">📋 Structure recommandée :</div>
  <ul class="text-xs space-y-1 ml-2">
  <li>• <strong>Introduction</strong> claire et accrocheuse</li>
  <li>• <strong>Corps</strong> du texte bien structuré</li>
  <li>• <strong>Détails</strong> et informations clés</li>
  <li>• <strong>Conclusion</strong> ou appel à l'action</li>
  </ul>
  </div>
  <div>
  <div class="font-medium mb-1">🎯 Conseils pratiques :</div>
  <ul class="text-xs space-y-1 ml-2">
  <li>• Utiliser des <strong>paragraphes courts</strong></li>
  <li>• Ajouter des <em>listes à puces</em></li>
  <li>• Mettre en <u>valeur</u> les mots clés</li>
  <li>• Insérer des <strong>images</strong> pertinentes</li>
  </ul>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>

  <!-- Boutons d'action -->
  <div class="flex items-center justify-between pt-6">
  <a href="{{ route('admin.actualite.index') }}" 
  class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
  <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
  </svg>
  Retour à la liste
  </a>
  
  <button type="submit" 
  class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-primary text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
  <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
  </svg>
  {{ isset($actualite) ? 'Mettre à jour l\'actualité' : 'Créer l\'actualité' }}
  </button>
  </div>

  </form>
  </div>
  </div>
</div>

<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/translations/fr.js"></script>

<script>
// Configuration et initialisation de CKEditor
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initialisation du formulaire d\'actualités');
    
    // Configuration CKEditor
    const editorConfig = {
        language: 'fr',
        toolbar: {
            items: [
                'undo', 'redo',
                '|', 'heading',
                '|', 'bold', 'italic', 'underline',
                '|', 'link', 'bulletedList', 'numberedList',
                '|', 'indent', 'outdent',
                '|', 'blockQuote', 'insertTable',
                '|', 'removeFormat'
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
        link: {
            decorators: {
                addTargetToExternalLinks: true,
                defaultProtocol: 'https://'
            }
        },
        table: {
            contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells'
            ]
        },
        placeholder: 'Rédigez ici le contenu de votre actualité...'
    };

    // Initialiser CKEditor sur le textarea avec l'ID 'texte'
    const editorElement = document.querySelector('#texte');
    
    if (editorElement) {
        console.log('📝 Initialisation de CKEditor...');
        
        ClassicEditor
            .create(editorElement, editorConfig)
            .then(editor => {
                console.log('✅ CKEditor initialisé avec succès');
                
                // Stocker l'éditeur globalement
                window.globalEditor = editor;
                
                // Définir la hauteur minimale
                const editableElement = editor.ui.view.editable.element;
                editableElement.style.minHeight = '400px';
                
                // Gestion de la soumission du formulaire
                const form = document.querySelector('#actualite-form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const data = editor.getData();
                        editorElement.value = data;
                        console.log('📄 Contenu CKEditor synchronisé');
                    });
                }
                
                // Sauvegarde automatique
                editor.model.document.on('change:data', () => {
                    const data = editor.getData();
                    editorElement.value = data;
                });
                
            })
            .catch(error => {
                console.error('❌ Erreur lors de l\'initialisation de CKEditor:', error);
                
                // Fallback
                const container = editorElement.parentNode;
                const fallbackMessage = document.createElement('div');
                fallbackMessage.className = 'bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3';
                fallbackMessage.innerHTML = `
                    <div class="flex items-center text-sm text-yellow-800">
                        <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span><strong>Mode texte simple :</strong> L'éditeur visuel n'a pas pu se charger. Utilisez le champ texte ci-dessous.</span>
                    </div>
                `;
                container.insertBefore(fallbackMessage, editorElement);
                editorElement.style.display = 'block';
                editorElement.style.minHeight = '400px';
            });
    } else {
        console.error('❌ Element #texte non trouvé');
    }
});
</script>
