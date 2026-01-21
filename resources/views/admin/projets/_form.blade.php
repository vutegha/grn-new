<!-- Container principal avec design moderne -->
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
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
  </svg>
  </div>
  </div>
  <div>
  <h1 class="text-2xl font-bold text-white">
  {{ isset($projet) ? 'Modifier le projet' : 'Nouveau projet' }}
  </h1>
  <p class="text-blue-100 text-sm mt-1">
  {{ isset($projet) ? 'Modifiez les informations de ce projet' : 'Créez un nouveau projet pour votre organisation' }}
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
  @php
  $fieldLabels = [
  'nom' => 'Nom du projet',
  'description' => 'Description',
  'resume' => 'Résumé',
  'date_debut' => 'Date de début',
  'date_fin' => 'Date de fin',
  'service_id' => 'Service responsable',
  'etat' => 'État du projet',
  'budget' => 'Budget',
  'image' => 'Image',
  'beneficiaires_hommes' => 'Bénéficiaires hommes',
  'beneficiaires_femmes' => 'Bénéficiaires femmes',
  'beneficiaires_enfants' => 'Bénéficiaires enfants',
  'beneficiaires_total' => 'Total bénéficiaires'
  ];
  @endphp
  @foreach ($errors->keys() as $field)
  @if(isset($fieldLabels[$field]))
  <div class="flex items-start p-2 bg-white/80 rounded-md border-l-4 border-red-400">
  <svg class="h-4 w-4 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 8 8">
  <circle cx="4" cy="4" r="3"/>
  </svg>
  <div>
  <span class="font-medium text-red-800">{{ $fieldLabels[$field] }} :</span>
  <span class="text-red-700 text-sm">{{ $errors->first($field) }}</span>
  </div>
  </div>
  @else
  <div class="flex items-start p-2 bg-white/80 rounded-md border-l-4 border-red-400">
  <svg class="h-4 w-4 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 8 8">
  <circle cx="4" cy="4" r="3"/>
  </svg>
  <span class="text-red-700 text-sm">{{ $errors->first($field) }}</span>
  </div>
  @endif
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

  @if(session('error'))
  <div class="mb-6">
  @php
  $errorMessage = session('error');
  $errorType = 'system'; // Par défaut
  $errorTitle = 'Erreur système';
  $iconColor = 'text-orange-600';
  $bgColor = 'bg-orange-100';
  
  // Analyser le type d'erreur selon le contenu du message
  if (Str::contains($errorMessage, ['validation', 'champ', 'saisie', 'obligatoire', 'format'])) {
  $errorType = 'validation';
  $errorTitle = 'Erreur de saisie';
  $iconColor = 'text-red-600';
  $bgColor = 'bg-red-100';
  } elseif (Str::contains($errorMessage, ['permission', 'accès', 'autorisé', 'unauthorized'])) {
  $errorType = 'permission';
  $errorTitle = 'Accès refusé';
  $iconColor = 'text-yellow-600';
  $bgColor = 'bg-yellow-100';
  } elseif (Str::contains($errorMessage, ['stockage', 'espace', 'disk', 'storage', 'fichier'])) {
  $errorType = 'storage';
  $errorTitle = 'Problème de stockage';
  $iconColor = 'text-purple-600';
  $bgColor = 'bg-purple-100';
  } elseif (Str::contains($errorMessage, ['serveur', 'timeout', 'memory', 'server', 'difficultés'])) {
  $errorType = 'server';
  $errorTitle = 'Erreur serveur';
  $iconColor = 'text-red-600';
  $bgColor = 'bg-red-100';
  } elseif (Str::contains($errorMessage, ['connexion', 'réseau', 'network', 'connection'])) {
  $errorType = 'network';
  $errorTitle = 'Problème de connexion';
  $iconColor = 'text-blue-600';
  $bgColor = 'bg-blue-100';
  } elseif (Str::contains($errorMessage, ['session', 'token', 'csrf', 'expiré'])) {
  $errorType = 'session';
  $errorTitle = 'Session expirée';
  $iconColor = 'text-indigo-600';
  $bgColor = 'bg-indigo-100';
  } elseif (Str::contains($errorMessage, ['base de données', 'database', 'db', 'sql'])) {
  $errorType = 'database';
  $errorTitle = 'Erreur de base de données';
  $iconColor = 'text-red-600';
  $bgColor = 'bg-red-100';
  }
  @endphp
  
  <div class="bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-2xl p-6 shadow-lg">
  <div class="flex items-center">
  <div class="flex-shrink-0">
  <div class="w-10 h-10 {{ $bgColor }} rounded-xl flex items-center justify-center">
  @if($errorType === 'validation')
  <!-- Icône pour erreur de validation -->
  <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
  </svg>
  @elseif($errorType === 'permission')
  <!-- Icône pour erreur de permission -->
  <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
  </svg>
  @elseif($errorType === 'storage')
  <!-- Icône pour erreur de stockage -->
  <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
  </svg>
  @elseif($errorType === 'server')
  <!-- Icône pour erreur serveur -->
  <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
  </svg>
  @elseif($errorType === 'network')
  <!-- Icône pour erreur réseau -->
  <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
  </svg>
  @elseif($errorType === 'session')
  <!-- Icône pour session expirée -->
  <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>
  @elseif($errorType === 'database')
  <!-- Icône pour erreur de base de données -->
  <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
  </svg>
  @else
  <!-- Icône par défaut pour erreur système -->
  <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>
  @endif
  </div>
  </div>
  <div class="ml-4">
  <h3 class="text-orange-800 font-semibold">{{ $errorTitle }}</h3>
  <p class="text-orange-700 text-sm mt-1">{{ $errorMessage }}</p>
  
  @if($errorType === 'session')
  <p class="text-orange-600 text-xs mt-2">
  💡 <strong>Conseil :</strong> Rechargez la page et réessayez.
  </p>
  @elseif($errorType === 'validation')
  <p class="text-red-600 text-xs mt-2">
  💡 <strong>Conseil :</strong> Vérifiez les champs marqués en rouge ci-dessous.
  </p>
  @elseif($errorType === 'server')
  <p class="text-red-600 text-xs mt-2">
  💡 <strong>Conseil :</strong> Attendez quelques minutes et réessayez.
  </p>
  @elseif($errorType === 'storage')
  <p class="text-purple-600 text-xs mt-2">
  💡 <strong>Conseil :</strong> Contactez l'administrateur système.
  </p>
  @elseif($errorType === 'permission')
  <p class="text-yellow-600 text-xs mt-2">
  💡 <strong>Conseil :</strong> Contactez un administrateur pour obtenir les permissions.
  </p>
  @endif
  </div>
  </div>
  </div>
  </div>
  @endif

  <!-- Contenu principal -->
  <div class="w-full">
  
  <!-- Formulaire principal -->
  <div class="w-full">
  <form id="projet-form" action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="space-y-8" data-form="project">
  @csrf
  @if(isset($projet))
  @method('PUT')
  @endif

  <!-- Honeypot pour détecter les bots (invisible et avec attributs renforcés) -->
  <input type="text" 
  name="website" 
  style="display:none !important; position:absolute !important; left:-9999px !important; visibility:hidden !important;" 
  autocomplete="off" 
  tabindex="-1" 
  readonly
  aria-hidden="true"
  data-lpignore="true"
  data-form-type="other">

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
  <p class="text-sm text-gray-600">Renseignez les informations de base du projet</p>
  </div>
  </div>
  </div>
  
  <div class="p-6 space-y-6">
  <!-- Nom du projet -->
  <div>
  <label for="nom" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
  <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-1.414.586H7a4 4 0 01-4-4V7a4 4 0 014-4z"></path>
  </svg>
  Nom du projet
  <span class="text-red-500 ml-1">*</span>
  </label>
  <input type="text" 
  name="nom" 
  id="nom"
  value="{{ old('nom', $projet->nom ?? '') }}"
  class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white placeholder-gray-400 @error('nom') border-red-500 bg-red-50 @enderror"
  placeholder="Saisissez un nom descriptif pour votre projet (tous caractères autorisés)"
  required
  maxlength="255">
  
  <!-- Aperçu du slug -->
  <div class="mt-2 text-xs text-gray-500 bg-gray-50 rounded-lg p-2 border border-gray-200">
  <span class="font-medium">URL générée :</span> 
  <span id="slug-preview" class="font-mono text-blue-600">
  {{ !empty($projet->slug ?? '') ? $projet->slug : 'sera-generee-automatiquement' }}
  </span>
  <small class="block mt-1 text-gray-400">
  ℹ️ L'URL est générée automatiquement à partir du nom. Caractères spéciaux convertis automatiquement.
  </small>
  </div>

  @error('nom')
  <div class="mt-2 flex items-center text-sm text-red-600">
  <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
  </svg>
  {{ $message }}
  </div>
  @enderror
  </div>

  <!-- Résumé du projet -->
  <div>
  <label for="resume" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
  <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
  </svg>
  Résumé du projet
  </label>
  <textarea name="resume" 
  id="resume"
  rows="4"
  value="{{ old('resume', $projet->resume ?? '') }}"
  class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white placeholder-gray-400 @error('resume') border-red-500 bg-red-50 @enderror"
  placeholder="Résumé du projet - Décrivez en quelques phrases les objectifs principaux et l'impact attendu de votre projet...">{{ old('resume', $projet->resume ?? '') }}</textarea>
  <p class="text-xs text-gray-500 mt-1">
  <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>
  Ce résumé sera affiché dans les listes de projets et les aperçus. Pas de limite de caractères.
  </p>
  @error('resume')
  <div class="mt-2 flex items-center text-sm text-red-600">
  <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
  </svg>
  {{ $message }}
  </div>
  @enderror
  </div>

  <!-- Ligne avec deux colonnes -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <!-- Date début -->
  <div>
  <label for="date_debut" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
  <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
  </svg>
  Date de début
  <span class="text-red-500 ml-1">*</span>
  </label>
  <input type="date" 
  name="date_debut" 
  id="date_debut"
  value="{{ old('date_debut', isset($projet) ? $projet->date_debut?->format('Y-m-d') : '') }}"
  class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('date_debut') border-red-500 bg-red-50 @enderror"
  required>
  @error('date_debut')
  <div class="mt-2 flex items-center text-sm text-red-600">
  <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
  </svg>
  {{ $message }}
  </div>
  @enderror
  </div>

  <!-- Date fin -->
  <div>
  <label for="date_fin" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
  <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
  </svg>
  Date de fin estimée
  </label>
  <input type="date" 
  name="date_fin" 
  id="date_fin"
  value="{{ old('date_fin', isset($projet) ? $projet->date_fin?->format('Y-m-d') : '') }}"
  class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('date_fin') border-red-500 bg-red-50 @enderror">
  @error('date_fin')
  <div class="mt-2 flex items-center text-sm text-red-600">
  <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
  </svg>
  {{ $message }}
  </div>
  @enderror
  </div>
  </div>

  <!-- Ligne avec service et état -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <!-- Service -->
  <div>
  <label for="service_id" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
  <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
  </svg>
  Service
  <span class="text-red-500 ml-1">*</span>
  </label>
  <select name="service_id" 
  id="service_id"
  class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('service_id') border-red-500 bg-red-50 @enderror"
  required>
  <option value="">Sélectionner un service</option>
  @if(isset($services))
  @foreach($services as $service)
  <option value="{{ $service->id }}" 
  {{ old('service_id', $projet->service_id ?? '') == $service->id ? 'selected' : '' }}>
  {{ $service->nom }}
  </option>
  @endforeach
  @endif
  </select>
  @error('service_id')
  <div class="mt-2 flex items-center text-sm text-red-600">
  <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
  </svg>
  {{ $message }}
  </div>
  @enderror
  </div>

  <!-- État -->
  <div>
  <label for="etat" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
  <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>
  État du projet
  <span class="text-red-500 ml-1">*</span>
  </label>
  <select name="etat" 
  id="etat"
  class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('etat') border-red-500 bg-red-50 @enderror"
  required>
  <option value="">Sélectionner un état</option>
  <option value="en cours" {{ old('etat', $projet->etat ?? '') == 'en cours' ? 'selected' : '' }}>En cours</option>
  <option value="terminé" {{ old('etat', $projet->etat ?? '') == 'terminé' ? 'selected' : '' }}>Terminé</option>
  <option value="suspendu" {{ old('etat', $projet->etat ?? '') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
  </select>
  @error('etat')
  <div class="mt-2 flex items-center text-sm text-red-600">
  <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
  </svg>
  {{ $message }}
  </div>
  @enderror
  </div>
  </div>

  <!-- Ligne avec budget et bénéficiaires -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <!-- Budget -->
  <div>
  <label for="budget" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
  <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
  </svg>
  Budget (USD)
  </label>
  <div class="relative">
  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
  <span class="text-gray-500 sm:text-sm font-medium">$</span>
  </div>
  <input type="number" 
  name="budget" 
  id="budget"
  value="{{ old('budget', $projet->budget ?? '') }}"
  class="w-full pl-8 pr-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('budget') border-red-500 bg-red-50 @enderror"
  placeholder="0">
  </div>
  @error('budget')
  <div class="mt-2 flex items-center text-sm text-red-600">
  <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
  </svg>
  {{ $message }}
  </div>
  @enderror
  </div>

  <!-- Bénéficiaires totaux -->
  <div>
  <label for="beneficiaires_total" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
  <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
  </svg>
  Bénéficiaires totaux
  </label>
  <input type="number" 
  name="beneficiaires_total" 
  id="beneficiaires_total"
  value="{{ old('beneficiaires_total', $projet->beneficiaires_total ?? 0) }}"
  class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-gray-50 @error('beneficiaires_total') border-red-500 bg-red-50 @enderror"
  placeholder="0"
  min="0"
  readonly>
  <p class="text-xs text-gray-500 mt-1">Calculé automatiquement : Hommes + Femmes + Enfants</p>
  @error('beneficiaires_total')
  <div class="mt-2 flex items-center text-sm text-red-600">
  <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
  </svg>
  {{ $message }}
  </div>
  @enderror
  </div>
  </div>

  <!-- Bénéficiaires détaillés -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50 rounded-xl p-4">
  <!-- Hommes -->
  <div>
  <label for="beneficiaires_hommes" class="block text-sm font-medium text-gray-700 mb-2">Hommes</label>
  <input type="number" 
  name="beneficiaires_hommes" 
  id="beneficiaires_hommes"
  value="{{ old('beneficiaires_hommes', $projet->beneficiaires_hommes ?? 0) }}"
  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary/20 focus:border-iri-primary transition-all bg-white"
  placeholder="0"
  min="0">
  </div>

  <!-- Femmes -->
  <div>
  <label for="beneficiaires_femmes" class="block text-sm font-medium text-gray-700 mb-2">Femmes</label>
  <input type="number" 
  name="beneficiaires_femmes" 
  id="beneficiaires_femmes"
  value="{{ old('beneficiaires_femmes', $projet->beneficiaires_femmes ?? 0) }}"
  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary/20 focus:border-iri-primary transition-all bg-white"
  placeholder="0"
  min="0">
  </div>

  <!-- Enfants -->
  <div>
  <label for="beneficiaires_enfants" class="block text-sm font-medium text-gray-700 mb-2">Enfants</label>
  <input type="number" 
  name="beneficiaires_enfants" 
  id="beneficiaires_enfants"
  value="{{ old('beneficiaires_enfants', $projet->beneficiaires_enfants ?? 0) }}"
  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary/20 focus:border-iri-primary transition-all bg-white"
  placeholder="0"
  min="0">
  </div>
  </div>
  </div>
  </div>

  <!-- Section 2: Description -->
  <div class="bg-white rounded-2xl shadow-xl border border-white/20 overflow-hidden">
  <div class="bg-gradient-to-r from-slate-50 to-blue-50 px-6 py-4 border-b border-gray-100">
  <div class="flex items-center justify-between">
  <div class="flex items-center space-x-3">
  <div class="w-8 h-8 bg-iri-primary rounded-lg flex items-center justify-center">
  <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
  </svg>
  </div>
  <div>
  <h3 class="text-lg font-bold text-gray-900">Description détaillée</h3>
  <p class="text-sm text-gray-600">Présentez tous les aspects de votre projet</p>
  </div>
  </div>
  
  <button type="button" 
  id="testMediaButton" 
  class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/20 transition-all duration-200 shadow-md hover:shadow-lg">
  <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
  </svg>
  Médiathèque
  </button>
  </div>
  </div>
  
  <div class="p-6">
  <div class="space-y-2">
  <label for="description" class="flex items-center text-sm font-semibold text-gray-700">
  <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
  </svg>
  Description du projet
  <span class="text-red-500 ml-1">*</span>
  </label>
  
  <textarea name="description" 
  id="description" 
  class="wysiwyg w-full px-4 py-4 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white placeholder-gray-400 min-h-[400px] @error('description') border-red-500 bg-red-50 @enderror"
  style="min-height: 400px;"
  rows="16"
  required>{{ old('description', $projet->description ?? '') }}</textarea>
  
  <!-- Texte d'orientation formaté -->
  <div class="mt-3 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl guide-redaction">
  <div class="flex items-start space-x-3">
  <div class="flex-shrink-0 mt-1">
  <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>
  </div>
  <div class="flex-1">
  <h4 class="text-sm font-semibold text-blue-800 mb-2">💡 Guide de rédaction de la description</h4>
  <div class="text-sm text-blue-700 space-y-2">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
  <div>
  <div class="font-medium mb-1">📋 Structure recommandée :</div>
  <ul class="text-xs space-y-1 ml-2">
  <li>• <strong>Contexte</strong> et justification</li>
  <li>• <strong>Objectifs</strong> principaux et secondaires</li>
  <li>• <strong>Public cible</strong> et bénéficiaires</li>
  <li>• <strong>Méthodologie</strong> et activités</li>
  </ul>
  </div>
  <div>
  <div class="font-medium mb-1">🎯 Éléments clés :</div>
  <ul class="text-xs space-y-1 ml-2">
  <li>• <strong>Résultats attendus</strong> et indicateurs</li>
  <li>• <strong>Partenariats</strong> et collaborations</li>
  <li>• <strong>Calendrier</strong> et étapes</li>
  <li>• <strong>Impact</strong> et durabilité</li>
  </ul>
  </div>
  </div>
  <div class="mt-3 p-2 bg-white/60 rounded-lg">
  <div class="text-xs text-blue-600">
  <strong>✨ Conseils :</strong> Utilisez des <em>listes à puces</em>, des <strong>mots clés en gras</strong>, et structurez avec des <u>sous-titres</u> pour une lecture optimale.
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  
  @error('description')
  <div class="flex items-center text-sm text-red-600 bg-red-50 p-3 rounded-lg mt-2">
  <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
  </svg>
  {{ $message }}
  </div>
  @enderror
  </div>

  <!-- Image du projet -->
  <div>
  <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
  <i class="fas fa-image text-olive mr-2"></i>Image du projet
  <span class="text-xs font-normal text-gray-500">(optionnelle)</span>
  </label>
  
  <div class="flex items-start space-x-6">
  <div class="flex-1">
  <div class="relative">
  <input type="file" 
  id="image"
  name="image" 
  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-iri-primary/10 file:text-iri-primary hover:file:bg-iri-primary/20 @error('image') border-red-500 bg-red-50 @enderror"
  accept=".jpg,.jpeg,.png,.gif,.webp,.svg"
  onchange="previewImageProjet(this)"
  data-max-size="10485760">
  <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
  </svg>
  </div>
  </div>
  <div class="mt-2 space-y-1">
  <p class="text-xs text-gray-500">
  <strong>Formats acceptés :</strong> JPEG, JPG, PNG, GIF, WebP, SVG
  </p>
  <p class="text-xs text-gray-500">
  <strong>Taille maximale :</strong> 10 MB
  </p>
  <p class="text-xs text-gray-500">
  <strong>Recommandations :</strong> Images haute résolution (min. 800x600px) pour une meilleure qualité d'affichage
  </p>
  </div>
  <div id="file-error-message" class="hidden mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
  <div class="flex items-center text-sm text-red-700">
  <svg class="h-4 w-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
  </svg>
  <span id="file-error-text">Erreur de fichier</span>
  </div>
  </div>
  </div>
  
  <!-- Aperçu de l'image -->
  <div class="flex-shrink-0">
  @if(isset($projet) && $projet->image && Storage::disk('public')->exists($projet->image))
  <div class="space-y-2">
  <div class="relative group">
  <img id="image-preview-projet" 
  src="{{ asset('storage/' . $projet->image) }}" 
  class="w-24 h-24 object-cover rounded-lg border-2 border-gray-200 shadow-sm" 
  alt="Aperçu"
  loading="lazy">
  <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
  <i class="fas fa-eye text-white text-lg"></i>
  </div>
  </div>
  <div class="flex items-center">
  <input type="checkbox" 
  id="remove_image" 
  name="remove_image" 
  value="1"
  class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
  <label for="remove_image" class="ml-2 text-xs text-red-600 font-medium">
  Supprimer l'image
  </label>
  </div>
  </div>
  @else
  <div id="image-placeholder-projet" class="w-24 h-24 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
  <i class="fas fa-image text-gray-400 text-2xl"></i>
  </div>
  <img id="image-preview-projet" 
  class="w-24 h-24 object-cover rounded-lg border-2 border-gray-200 shadow-sm hidden" 
  alt="Aperçu">
  @endif
  </div>
  </div>
  @error('image')
  <div class="mt-2 flex items-center text-sm text-red-600">
  <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
  </svg>
  {{ $message }}
  </div>
  @enderror
  </div>
  </div>
  </div>

  <!-- Section 3: Actions -->
  <div class="bg-white rounded-2xl shadow-xl border border-white/20 overflow-hidden">
  <div class="p-6">
  <div class="flex flex-col sm:flex-row gap-4 justify-end">
  <a href="{{ route('admin.projets.index') }}" 
  class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 focus:ring-4 focus:ring-gray-500/20 transition-all duration-200">
  <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
  </svg>
  Annuler
  </a>
  
  <button type="submit" 
  class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold rounded-xl hover:shadow-lg focus:ring-4 focus:ring-iri-primary/20 transition-all duration-200 transform hover:scale-105">
  <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
  </svg>
  {{ isset($projet) ? 'Mettre à jour' : 'Créer le projet' }}
  </button>
  </div>
  </div>
  </div>

  </form>
  </div>

  </div>
  </div>
</div>

<style>
/* Styles pour les modales médiathèque */
#mediaModal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.75);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  margin: 0;
  padding: 1rem;
  transition: all 0.3s ease;
}

#mediaModal.hidden {
  display: none !important;
  opacity: 0;
  visibility: hidden;
}

#mediaModal .bg-white {
  border-radius: 1rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
  transform: translateY(0);
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* Empêcher le scroll du body quand le modal est ouvert */
body.modal-open {
  overflow: hidden !important;
  position: fixed !important;
  width: 100% !important;
}

/* Animations pour les cartes d'images */
.media-card {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  transform-origin: center;
}

.media-card:hover {
  transform: translateY(-4px) scale(1.02);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Animations pour les boutons */
.btn-animate {
  transition: all 0.2s ease;
  transform: translateY(0);
}

.btn-animate:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

/* Spinner de chargement */
@keyframes bounce {
  0%, 80%, 100% { transform: scale(0); }
  40% { transform: scale(1); }
}

.loading-dot {
  animation: bounce 1.4s infinite ease-in-out both;
}

.loading-dot:nth-child(1) { animation-delay: -0.32s; }
.loading-dot:nth-child(2) { animation-delay: -0.16s; }

/* Barre de progression */
.progress-bar {
  transition: width 0.3s ease;
  background: linear-gradient(90deg, #1e472f, #2d5a3f);
}

/* Zone de drop */
.drop-zone {
  transition: all 0.3s ease;
  border-width: 2px;
  border-style: dashed;
}

.drop-zone.drag-over {
  border-color: #1e472f;
  background-color: rgba(30, 71, 47, 0.05);
  transform: scale(1.02);
}

/* Styles pour CKEditor - Hauteur minimale 400px */
.ck-editor {
  min-height: 450px !important; /* 400px pour le contenu + 50px pour la toolbar */
}

.ck-editor__editable {
  min-height: 400px !important;
  max-height: none !important;
  height: auto !important;
}

.ck.ck-editor__main > .ck-editor__editable {
  min-height: 400px !important;
  padding: 1rem !important;
  font-size: 14px !important;
  line-height: 1.6 !important;
}

/* Style pour le contenu d'aide dans CKEditor */
.ck-content h3 {
  color: #1e40af;
  border-bottom: 2px solid #3b82f6;
  padding-bottom: 0.5rem;
  margin-bottom: 1rem;
}

.ck-content h4 {
  color: #1f2937;
  margin-top: 1.5rem;
  margin-bottom: 0.5rem;
  font-weight: 600;
}

.ck-content em {
  color: #6b7280;
  font-style: italic;
}

.ck-content blockquote {
  background: #eff6ff;
  border-left: 4px solid #3b82f6;
  padding: 1rem;
  margin: 1rem 0;
  border-radius: 0.5rem;
}

.ck-content blockquote p {
  margin: 0;
  color: #1e40af;
}

/* Styles pour les boutons personnalisés CKEditor */
.ck-dropdown__panel {
  position: absolute;
  top: 100%;
  left: 0;
  background: white;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  z-index: 1000;
  padding: 8px;
  min-width: 160px;
}

.color-palette {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 4px;
}

.color-btn {
  width: 24px;
  height: 24px;
  border: 1px solid #ccc;
  border-radius: 2px;
  cursor: pointer;
  transition: transform 0.1s;
}

.color-btn:hover {
  transform: scale(1.1);
  border-color: #000;
}

.ck-dropdown {
  position: relative;
  display: inline-block;
}

/* Styles pour les textes colorés et surlignés */
.ck-content span[style*="color"] {
  /* Préserver les couleurs dans l'éditeur */
}

.ck-content span[style*="background-color"] {
  /* Préserver les surlignages dans l'éditeur */
  padding: 2px 4px;
  border-radius: 2px;
}

/* Animation pour le guide de rédaction */
.guide-redaction {
  animation: slideInFromBottom 0.5s ease-out;
}

@keyframes slideInFromBottom {
  from {
  opacity: 0;
  transform: translateY(20px);
  }
  to {
  opacity: 1;
  transform: translateY(0);
  }
}
</style>

<!-- Modale Médiathèque Améliorée -->
<div id="mediaModal" class="hidden">
  <div class="bg-white rounded-2xl shadow-2xl max-w-6xl w-full max-h-[95vh] overflow-hidden">
    <!-- En-tête -->
    <div class="px-6 py-4 border-b flex items-center justify-between bg-gradient-to-r from-iri-primary to-iri-secondary">
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
          <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
        </div>
        <div>
          <h3 class="text-xl font-bold text-white">Médiathèque</h3>
          <p class="text-blue-100 text-sm">Sélectionnez ou uploadez vos images</p>
        </div>
      </div>
      
      <!-- Compteur et contrôles -->
      <div class="flex items-center space-x-4">
        <div class="text-white/80 text-sm">
          <span id="mediaCount" class="font-semibold">0</span> image(s)
        </div>
        <button type="button" onclick="closeMediaModal()" class="text-white hover:text-gray-200 transition-colors p-2 rounded-lg hover:bg-white/10">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>

    <!-- Corps du modal -->
    <div class="flex flex-col lg:flex-row h-[calc(95vh-80px)]">
      
      <!-- Section Upload -->
      <div class="w-full lg:w-1/3 p-6 border-r border-gray-200 bg-gray-50">
        <div class="space-y-6">
          
          <!-- Zone de drop & upload -->
          <div class="bg-white rounded-xl border-2 border-dashed border-gray-300 p-6 text-center hover:border-iri-primary transition-colors" id="dropZone">
            <div class="space-y-4">
              <div class="w-16 h-16 bg-iri-primary/10 rounded-full flex items-center justify-center mx-auto">
                <svg class="h-8 w-8 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
              </div>
              <div>
                <p class="text-lg font-semibold text-gray-700">Glissez vos images ici</p>
                <p class="text-sm text-gray-500">ou cliquez pour sélectionner</p>
              </div>
              
              <form id="mediaUploadForm" action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="image" id="mediaUploadInput" multiple accept="image/*" class="hidden">
                <button type="button" onclick="document.getElementById('mediaUploadInput').click()" class="inline-flex items-center px-4 py-2 bg-iri-primary text-white text-sm font-medium rounded-lg hover:bg-iri-secondary focus:ring-4 focus:ring-iri-primary/20 transition-all duration-200">
                  <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                  </svg>
                  Choisir des fichiers
                </button>
              </form>
            </div>
          </div>
          
          <!-- Informations upload -->
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start space-x-3">
              <svg class="h-5 w-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <div class="text-sm text-blue-700">
                <p class="font-medium mb-1">Formats supportés :</p>
                <p class="text-xs">JPEG, PNG, GIF, WebP, SVG</p>
                <p class="text-xs mt-2"><strong>Taille max :</strong> 10 MB par fichier</p>
                <p class="text-xs"><strong>Upload multiple :</strong> Sélectionnez plusieurs images</p>
              </div>
            </div>
          </div>
          
          <!-- Barre de progression -->
          <div id="uploadProgress" class="hidden">
            <div class="bg-gray-200 rounded-full h-2">
              <div id="uploadProgressBar" class="bg-iri-primary h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <p id="uploadProgressText" class="text-sm text-gray-600 mt-2">Upload en cours...</p>
          </div>
          
        </div>
      </div>

      <!-- Section Galerie -->
      <div class="flex-1 p-6 overflow-y-auto">
        
        <!-- Barre de recherche et filtres -->
        <div class="mb-6 space-y-4">
          <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </div>
                <input type="text" id="mediaSearch" placeholder="Rechercher par nom..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary/20 focus:border-iri-primary text-sm">
              </div>
            </div>
            <div class="flex space-x-2">
              <select id="mediaSort" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-iri-primary/20 focus:border-iri-primary">
                <option value="newest">Plus récent</option>
                <option value="oldest">Plus ancien</option>
                <option value="name">Nom A-Z</option>
                <option value="size">Taille</option>
              </select>
              <button id="toggleView" type="button" class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 focus:ring-2 focus:ring-iri-primary/20">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Grille des images -->
        <div id="mediaList" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
          <div class="col-span-full text-center text-gray-500 py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            <div id="loadingSpinner" class="flex items-center justify-center space-x-2">
              <div class="w-2 h-2 bg-iri-primary rounded-full animate-bounce"></div>
              <div class="w-2 h-2 bg-iri-primary rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
              <div class="w-2 h-2 bg-iri-primary rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            </div>
            <p class="text-lg font-medium mt-4">Chargement des médias...</p>
            <p class="text-sm">Veuillez patienter</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/translations/fr.js"></script>

<script>
// Configuration et initialisation de CKEditor
document.addEventListener('DOMContentLoaded', function() {
    // Configuration CKEditor
    const editorConfig = {
        language: 'fr',
        toolbar: {
            items: [
                'undo', 'redo',
                '|', 'heading',
                '|', 'bold', 'italic', 'underline', 'strikethrough',
                '|', 'fontSize', 'fontColor', 'fontBackgroundColor',
                '|', 'link', 'insertImage', 'insertTable', 'mediaEmbed',
                '|', 'alignment',
                '|', 'bulletedList', 'numberedList', 'outdent', 'indent',
                '|', 'blockQuote', 'insertTable',
                '|', 'removeFormat',
                '|', 'sourceEditing'
            ],
            shouldNotGroupWhenFull: true
        },
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraphe', class: 'ck-heading_paragraph' },
                { model: 'heading1', view: 'h1', title: 'Titre 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: 'Titre 2', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Titre 3', class: 'ck-heading_heading3' },
                { model: 'heading4', view: 'h4', title: 'Titre 4', class: 'ck-heading_heading4' }
            ]
        },
        fontSize: {
            options: [
                'tiny', 'small', 'default', 'big', 'huge'
            ]
        },
        fontColor: {
            colors: [
                {
                    color: 'hsl(0, 0%, 0%)',
                    label: 'Noir'
                },
                {
                    color: 'hsl(0, 0%, 30%)',
                    label: 'Gris foncé'
                },
                {
                    color: 'hsl(0, 0%, 60%)',
                    label: 'Gris'
                },
                {
                    color: 'hsl(0, 0%, 90%)',
                    label: 'Gris clair'
                },
                {
                    color: 'hsl(0, 0%, 100%)',
                    label: 'Blanc',
                    hasBorder: true
                },
                {
                    color: 'hsl(0, 75%, 60%)',
                    label: 'Rouge'
                },
                {
                    color: 'hsl(30, 75%, 60%)',
                    label: 'Orange'
                },
                {
                    color: 'hsl(60, 75%, 60%)',
                    label: 'Jaune'
                },
                {
                    color: 'hsl(90, 75%, 60%)',
                    label: 'Vert clair'
                },
                {
                    color: 'hsl(120, 75%, 60%)',
                    label: 'Vert'
                },
                {
                    color: 'hsl(150, 75%, 60%)',
                    label: 'Bleu-vert'
                },
                {
                    color: 'hsl(180, 75%, 60%)',
                    label: 'Turquoise'
                },
                {
                    color: 'hsl(210, 75%, 60%)',
                    label: 'Bleu clair'
                },
                {
                    color: 'hsl(240, 75%, 60%)',
                    label: 'Bleu'
                },
                {
                    color: 'hsl(270, 75%, 60%)',
                    label: 'Violet'
                }
            ]
        },
        fontBackgroundColor: {
            colors: [
                {
                    color: 'hsl(0, 0%, 100%)',
                    label: 'Blanc',
                    hasBorder: true
                },
                {
                    color: 'hsl(0, 0%, 90%)',
                    label: 'Gris clair'
                },
                {
                    color: 'hsl(0, 0%, 60%)',
                    label: 'Gris'
                },
                {
                    color: 'hsl(0, 0%, 30%)',
                    label: 'Gris foncé'
                },
                {
                    color: 'hsl(0, 0%, 0%)',
                    label: 'Noir'
                },
                {
                    color: 'hsl(0, 75%, 90%)',
                    label: 'Rouge clair'
                },
                {
                    color: 'hsl(30, 75%, 90%)',
                    label: 'Orange clair'
                },
                {
                    color: 'hsl(60, 75%, 90%)',
                    label: 'Jaune clair'
                },
                {
                    color: 'hsl(90, 75%, 90%)',
                    label: 'Vert très clair'
                },
                {
                    color: 'hsl(120, 75%, 90%)',
                    label: 'Vert clair'
                },
                {
                    color: 'hsl(150, 75%, 90%)',
                    label: 'Bleu-vert clair'
                },
                {
                    color: 'hsl(180, 75%, 90%)',
                    label: 'Turquoise clair'
                },
                {
                    color: 'hsl(210, 75%, 90%)',
                    label: 'Bleu très clair'
                },
                {
                    color: 'hsl(240, 75%, 90%)',
                    label: 'Bleu clair'
                },
                {
                    color: 'hsl(270, 75%, 90%)',
                    label: 'Violet clair'
                }
            ]
        },
        link: {
            decorators: {
                addTargetToExternalLinks: true,
                defaultProtocol: 'https://',
                toggleDownloadable: {
                    mode: 'manual',
                    label: 'Téléchargeable',
                    attributes: {
                        download: 'file'
                    }
                }
            }
        },
        image: {
            toolbar: [
                'imageTextAlternative',
                'toggleImageCaption',
                'imageStyle:inline',
                'imageStyle:block',
                'imageStyle:side'
            ]
        },
        table: {
            contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells',
                'tableCellProperties',
                'tableProperties'
            ]
        }
    };

    // Initialiser CKEditor sur le textarea avec l'ID 'description'
    const editorElement = document.querySelector('#description');
    
    if (editorElement) {
        ClassicEditor
            .create(editorElement, editorConfig)
            .then(editor => {
                console.log('✅ CKEditor initialisé avec succès');
                
                // Stocker l'éditeur globalement pour la médiathèque
                window.globalEditor = editor;
                
                // Définir la hauteur minimale
                const editableElement = editor.ui.view.editable.element;
                editableElement.style.minHeight = '400px';
                
                /* ===================================================================
                 * GUIDE DE RÉDACTION DÉSACTIVÉ
                 * Pour réactiver le guide automatique, décommentez le bloc ci-dessous
                 * =================================================================== */
                
                /*
                // Ajout du contenu d'aide initial si le textarea est vide
                if (!editor.getData().trim()) {
                    const helpContent = `
<h3>🎯 Guide de rédaction de votre projet</h3>

<h4>📋 Structure recommandée :</h4>
<p><strong>1. Contexte et justification</strong><br>
<em>Expliquez pourquoi ce projet est nécessaire, quel problème il résout.</em></p>

<p><strong>2. Objectifs principaux et secondaires</strong><br>
<em>Définissez clairement ce que vous voulez accomplir.</em></p>

<p><strong>3. Public cible et bénéficiaires</strong><br>
<em>Qui va bénéficier de ce projet ? Combien de personnes ?</em></p>

<p><strong>4. Méthodologie et activités</strong><br>
<em>Comment allez-vous procéder ? Quelles sont les étapes clés ?</em></p>

<h4>🎯 Éléments clés à inclure :</h4>
<ul>
    <li><strong>Résultats attendus</strong> et indicateurs de succès</li>
    <li><strong>Partenariats</strong> et collaborations prévues</li>
    <li><strong>Calendrier</strong> et principales étapes</li>
    <li><strong>Impact</strong> à long terme et durabilité</li>
</ul>

<blockquote>
<p><strong>💡 Conseil :</strong> Utilisez des listes à puces, mettez en <strong>gras</strong> les mots clés importants, et structurez avec des sous-titres pour une lecture optimale. N'hésitez pas à supprimer ce guide et à commencer votre rédaction !</p>
</blockquote>
                    `;
                    editor.setData(helpContent);
                }
                */
                
                // Gestion de la soumission du formulaire
                const form = document.querySelector('#projet-form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const data = editor.getData();
                        editorElement.value = data;
                        console.log('📄 Contenu CKEditor synchronisé avec le textarea');
                    });
                }
                
                // Gestion automatique de sauvegarde (optionnel)
                editor.model.document.on('change:data', () => {
                    const data = editor.getData();
                    editorElement.value = data;
                });
                
                // Ajout du bouton médiathèque personnalisé
                addMediaLibraryButton(editor);
                
            })
            .catch(error => {
                console.error('❌ Erreur lors de l\'initialisation de CKEditor:', error);
                
                // Nettoyer la référence globale en cas d'erreur
                window.globalEditor = null;
                
                // Fallback : afficher le textarea normal avec un message d'information
                const container = editorElement.parentNode;
                const fallbackMessage = document.createElement('div');
                fallbackMessage.className = 'bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3';
                fallbackMessage.innerHTML = `
                    <div class="flex items-center text-sm text-yellow-800">
                        <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span><strong>Mode de compatibilité :</strong> L'éditeur avancé n'a pas pu se charger. Utilisez le champ texte standard ci-dessous.</span>
                    </div>
                `;
                container.insertBefore(fallbackMessage, editorElement);
                editorElement.style.display = 'block';
                editorElement.style.minHeight = '400px';
            });
    }
});

// Fonction pour ajouter le bouton médiathèque
function addMediaLibraryButton(editor) {
    editor.ui.componentFactory.add('mediaLibrary', locale => {
        const view = new editor.ui.view.ButtonView(locale);
        
        view.set({
            label: 'Médiathèque',
            icon: '<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6 3v11c0 1.1.89 2 2 2h8c1.1 0 2-.9 2-2V3H6zM4 3c0-1.1.89-2 2-2h8c1.1 0 2 .9 2 2v1H4V3z"/><path d="M4 6h12v2H4V6zM4 10h12v2H4v-2z"/></svg>',
            tooltip: 'Ouvrir la médiathèque'
        });
        
        view.on('execute', () => {
            openMediaModal(editor);
        });
        
        return view;
    });
}

// Fonctions pour la médiathèque (reprises du code existant)
function openMediaModal(editor = null) {
    // Définir l'éditeur courant ou utiliser l'éditeur global
    window.currentEditor = editor || window.globalEditor;
    
    // Vérifier qu'au moins un éditeur est disponible
    if (!window.currentEditor && !window.globalEditor) {
        console.warn('⚠️ Aucun éditeur CKEditor disponible');
        showNotification('⚠️ Veuillez attendre que l\'éditeur soit complètement chargé avant d\'ouvrir la médiathèque.', 'warning');
        return;
    }
    
    const modal = document.getElementById('mediaModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('modal-open');
        loadMediaList();
        console.log('📂 Médiathèque ouverte, éditeur disponible:', window.currentEditor ? 'Oui' : 'Non');
    }
}

function closeMediaModal() {
    window.currentEditor = null;
    const modal = document.getElementById('mediaModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('modal-open');
    }
}

function loadMediaList() {
    const mediaList = document.getElementById('mediaList');
    if (!mediaList) return;
    
    // Afficher l'état de chargement
    mediaList.innerHTML = `
        <div class="col-span-full text-center text-gray-500 py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="flex items-center justify-center space-x-2">
                <div class="w-2 h-2 bg-iri-primary rounded-full animate-bounce"></div>
                <div class="w-2 h-2 bg-iri-primary rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                <div class="w-2 h-2 bg-iri-primary rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            </div>
            <p class="text-lg font-medium mt-4">Chargement des médias...</p>
        </div>
    `;
    
    fetch('{{ route("admin.media.list") }}')
        .then(response => {
            console.log('Status response:', response.status);
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Données reçues:', data);
            
            if (data.success && data.images) {
                displayMediaList(data.images);
                console.log(`✅ ${data.images.length} média(s) chargé(s)`);
            } else {
                console.warn('Aucune image trouvée ou erreur:', data);
                mediaList.innerHTML = `
                    <div class="col-span-full text-center text-gray-500 py-12">
                        <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="space-y-2">
                            <p class="text-lg font-semibold text-gray-700">Aucune image disponible</p>
                            <p class="text-sm text-gray-500">Commencez par uploader vos premières images</p>
                            <button type="button" onclick="document.getElementById('mediaUploadInput').click()" class="inline-flex items-center px-4 py-2 bg-iri-primary text-white text-sm font-medium rounded-lg hover:bg-iri-secondary transition-colors mt-4">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Ajouter des images
                            </button>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des médias:', error);
            mediaList.innerHTML = `
                <div class="col-span-full text-center text-red-500 py-12">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <p class="text-lg font-semibold text-red-700">Erreur de chargement</p>
                        <p class="text-sm text-red-600">${error.message}</p>
                        <button type="button" onclick="loadMediaList()" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors mt-4">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Réessayer
                        </button>
                    </div>
                </div>
            `;
        });
}

function displayMediaList(images) {
    const mediaList = document.getElementById('mediaList');
    const mediaCount = document.getElementById('mediaCount');
    
    if (!mediaList) return;
    
    // Mettre à jour le compteur
    if (mediaCount) {
        mediaCount.textContent = images ? images.length : 0;
    }
    
    if (!images || images.length === 0) {
        mediaList.innerHTML = `
            <div class="col-span-full text-center text-gray-500 py-12">
                <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="space-y-2">
                    <p class="text-lg font-semibold text-gray-700">Aucune image disponible</p>
                    <p class="text-sm text-gray-500">Commencez par uploader vos premières images</p>
                    <button type="button" onclick="document.getElementById('mediaUploadInput').click()" class="inline-flex items-center px-4 py-2 bg-iri-primary text-white text-sm font-medium rounded-lg hover:bg-iri-secondary transition-colors mt-4">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter des images
                    </button>
                </div>
            </div>
        `;
        return;
    }
    
    // Fonction pour formater la taille des fichiers
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }
    
    // Fonction pour formater la date
    function formatDate(dateString) {
        if (!dateString) return 'Date inconnue';
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', { 
            day: '2-digit', 
            month: '2-digit', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    const mediaHTML = images.map((image, index) => `
        <div class="relative group cursor-pointer bg-white rounded-xl border-2 border-gray-200 hover:border-iri-primary overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-iri-primary/10 transform hover:-translate-y-1" 
             onclick="selectMedia('${image.url}', '${image.name}', ${index})"
             data-name="${image.name.toLowerCase()}"
             data-size="${image.size || 0}"
             data-date="${image.created_at || ''}">
            
            <!-- Image -->
            <div class="aspect-square overflow-hidden bg-gray-50 relative">
                <img src="${image.url}" 
                     alt="${image.name}" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                     loading="lazy"
                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDJMMTMuMDkgOC4yNkwyMCA5TDEzLjA5IDE1Ljc0TDEyIDIyTDEwLjkxIDE1Ljc0TDQgOUwxMC45MSA4LjI2TDEyIDJaIiBmaWxsPSIjOTMzNkY2Ii8+Cjwvc3ZnPgo=';">
                
                <!-- Overlay de sélection -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                    <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                        <div class="w-12 h-12 bg-white/90 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="h-6 w-6 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <p class="text-white text-xs font-medium text-center mt-2">Sélectionner</p>
                    </div>
                </div>
                
                <!-- Badge de type -->
                <div class="absolute top-2 right-2 px-2 py-1 bg-black/60 text-white text-xs rounded-md font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    ${image.name.split('.').pop().toUpperCase()}
                </div>
            </div>
            
            <!-- Informations -->
            <div class="p-3 space-y-2">
                <div class="space-y-1">
                    <h4 class="text-sm font-semibold text-gray-800 truncate" title="${image.name}">
                        ${image.name.length > 15 ? image.name.substring(0, 15) + '...' : image.name}
                    </h4>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span class="truncate">${formatFileSize(image.size || 0)}</span>
                        ${image.dimensions ? `<span class="whitespace-nowrap">${image.dimensions}</span>` : ''}
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    <span class="text-xs text-gray-400 truncate">${formatDate(image.created_at)}</span>
                    <div class="flex space-x-1">
                        <button type="button" 
                                onclick="event.stopPropagation(); previewMedia('${image.url}', '${image.name}')" 
                                class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-iri-primary transition-colors rounded">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                        <button type="button" 
                                onclick="event.stopPropagation(); copyMediaUrl('${image.url}')" 
                                class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-blue-500 transition-colors rounded">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    
    mediaList.innerHTML = mediaHTML;
    
    // Appliquer les filtres actuels
    applyMediaFilters();
}

function selectMedia(url, name, index = null) {
    // Animation de sélection
    const selectedElement = document.querySelector(`[onclick*="selectMedia('${url}', '${name}', ${index})"]`);
    if (selectedElement) {
        selectedElement.classList.add('ring-4', 'ring-iri-primary', 'ring-opacity-50');
        selectedElement.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
            selectedElement.style.transform = 'scale(1)';
        }, 150);
    }
    
    // Utiliser l'éditeur courant ou l'éditeur global
    const editor = window.currentEditor || window.globalEditor;
    
    if (editor) {
        try {
            // Insérer l'image dans CKEditor avec une meilleure structure
            editor.model.change(writer => {
                const imageElement = writer.createElement('imageBlock', {
                    src: url,
                    alt: name,
                    title: name
                });
                editor.model.insertContent(imageElement);
            });
            
            // Notification de succès
            showNotification(`✅ Image "${name}" ajoutée avec succès`, 'success');
            console.log(`✅ Image "${name}" insérée dans CKEditor`);
        } catch (error) {
            console.error('❌ Erreur lors de l\'insertion de l\'image:', error);
            showNotification(`❌ Erreur lors de l'insertion de l'image: ${error.message}`, 'error');
        }
    } else {
        console.warn('⚠️ Aucun éditeur disponible (currentEditor et globalEditor sont null)');
        showNotification(`⚠️ Éditeur non disponible. Veuillez attendre que l'éditeur soit complètement chargé.`, 'warning');
    }
    
    // Fermer le modal avec un délai pour l'animation
    setTimeout(() => {
        closeMediaModal();
    }, 300);
}

// Fonction pour prévisualiser une image
function previewMedia(url, name) {
    const previewModal = document.createElement('div');
    previewModal.className = 'fixed inset-0 z-[10000] flex items-center justify-center bg-black bg-opacity-75 backdrop-blur-sm';
    previewModal.innerHTML = `
        <div class="relative max-w-4xl max-h-[90vh] bg-white rounded-2xl overflow-hidden shadow-2xl">
            <div class="absolute top-4 right-4 z-10">
                <button onclick="this.closest('.fixed').remove()" class="w-10 h-10 bg-white/90 hover:bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-gray-800 transition-colors shadow-lg">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <img src="${url}" alt="${name}" class="w-full h-auto max-h-[80vh] object-contain rounded-lg">
                <div class="mt-4 text-center">
                    <h3 class="text-lg font-semibold text-gray-800">${name}</h3>
                    <div class="flex justify-center space-x-4 mt-3">
                        <button onclick="selectMedia('${url}', '${name}'); this.closest('.fixed').remove();" class="px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors">
                            Sélectionner cette image
                        </button>
                        <button onclick="copyMediaUrl('${url}')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Copier l'URL
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(previewModal);
    
    // Fermer avec Escape
    const handleEscape = (e) => {
        if (e.key === 'Escape') {
            previewModal.remove();
            document.removeEventListener('keydown', handleEscape);
        }
    };
    document.addEventListener('keydown', handleEscape);
    
    // Fermer en cliquant à l'extérieur
    previewModal.addEventListener('click', (e) => {
        if (e.target === previewModal) {
            previewModal.remove();
            document.removeEventListener('keydown', handleEscape);
        }
    });
}

// Fonction pour copier l'URL d'une image
function copyMediaUrl(url) {
    navigator.clipboard.writeText(url).then(() => {
        showNotification('📋 URL copiée dans le presse-papier', 'success');
    }).catch(() => {
        // Fallback pour les navigateurs plus anciens
        const textarea = document.createElement('textarea');
        textarea.value = url;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showNotification('📋 URL copiée dans le presse-papier', 'success');
    });
}

// Fonction de notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'warning' ? 'bg-yellow-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    
    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-4 py-3 rounded-lg shadow-lg z-[10001] transform translate-x-full opacity-0 transition-all duration-300`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animation d'entrée
    setTimeout(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
    }, 100);
    
    // Suppression automatique
    setTimeout(() => {
        notification.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 3000);
}

// Fonction pour filtrer et trier les médias
function applyMediaFilters() {
    const searchTerm = document.getElementById('mediaSearch')?.value.toLowerCase() || '';
    const sortBy = document.getElementById('mediaSort')?.value || 'newest';
    const mediaItems = document.querySelectorAll('#mediaList > div[data-name]');
    
    // Filtrage par recherche
    mediaItems.forEach(item => {
        const name = item.getAttribute('data-name') || '';
        const matchesSearch = name.includes(searchTerm);
        item.style.display = matchesSearch ? 'block' : 'none';
    });
    
    // Tri
    const visibleItems = Array.from(mediaItems).filter(item => item.style.display !== 'none');
    const mediaList = document.getElementById('mediaList');
    
    visibleItems.sort((a, b) => {
        switch (sortBy) {
            case 'name':
                return (a.getAttribute('data-name') || '').localeCompare(b.getAttribute('data-name') || '');
            case 'size':
                return parseInt(b.getAttribute('data-size') || '0') - parseInt(a.getAttribute('data-size') || '0');
            case 'oldest':
                return new Date(a.getAttribute('data-date') || 0) - new Date(b.getAttribute('data-date') || 0);
            case 'newest':
            default:
                return new Date(b.getAttribute('data-date') || 0) - new Date(a.getAttribute('data-date') || 0);
        }
    });
    
    // Réorganiser les éléments
    visibleItems.forEach(item => {
        mediaList.appendChild(item);
    });
}

// Gestion avancée de l'upload de médias
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('mediaUploadForm');
    const uploadInput = document.getElementById('mediaUploadInput');
    const dropZone = document.getElementById('dropZone');
    const uploadProgress = document.getElementById('uploadProgress');
    const uploadProgressBar = document.getElementById('uploadProgressBar');
    const uploadProgressText = document.getElementById('uploadProgressText');
    
    // Gestion du drag & drop
    if (dropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('border-iri-primary', 'bg-iri-primary/5');
            });
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('border-iri-primary', 'bg-iri-primary/5');
            });
        });
        
        dropZone.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                uploadInput.files = files;
                handleFileUpload(files);
            }
        });
    }
    
    // Gestion de la sélection de fichiers
    if (uploadInput) {
        uploadInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                handleFileUpload(e.target.files);
            }
        });
    }
    
    // Fonction de gestion de l'upload
    function handleFileUpload(files) {
        const formData = new FormData();
        const maxFileSize = 10 * 1024 * 1024; // 10 MB
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        
        // Validation des fichiers
        let validFiles = [];
        let errors = [];
        
        Array.from(files).forEach((file, index) => {
            if (file.size > maxFileSize) {
                errors.push(`${file.name}: Fichier trop volumineux (max 10MB)`);
            } else if (!allowedTypes.includes(file.type)) {
                errors.push(`${file.name}: Format non supporté`);
            } else {
                validFiles.push(file);
            }
        });
        
        if (errors.length > 0) {
            showNotification(`❌ Erreurs détectées: ${errors.join(', ')}`, 'error');
            return;
        }
        
        if (validFiles.length === 0) {
            showNotification('⚠️ Aucun fichier valide sélectionné', 'warning');
            return;
        }
        
        // Ajouter les fichiers au FormData
        validFiles.forEach(file => {
            formData.append('images[]', file);
        });
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
        
        // Afficher la barre de progression
        if (uploadProgress) {
            uploadProgress.classList.remove('hidden');
            uploadProgressBar.style.width = '0%';
            uploadProgressText.textContent = `Upload de ${validFiles.length} fichier(s) en cours...`;
        }
        
        // XMLHttpRequest pour gérer la progression
        const xhr = new XMLHttpRequest();
        
        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                if (uploadProgressBar) {
                    uploadProgressBar.style.width = percentComplete + '%';
                }
                if (uploadProgressText) {
                    uploadProgressText.textContent = `Upload: ${Math.round(percentComplete)}%`;
                }
            }
        });
        
        xhr.addEventListener('load', () => {
            if (uploadProgress) {
                uploadProgress.classList.add('hidden');
            }
            
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    console.log('Réponse serveur:', response);
                    
                    if (response.success) {
                        const uploadedCount = response.uploaded || response.files?.length || 1;
                        showNotification(`✅ ${uploadedCount} image(s) uploadée(s) avec succès!`, 'success');
                        
                        // Recharger la liste des médias
                        setTimeout(() => {
                            loadMediaList();
                        }, 500);
                        
                        uploadInput.value = ''; // Reset input
                    } else {
                        const errorMsg = response.message || 'Upload échoué';
                        showNotification(`❌ ${errorMsg}`, 'error');
                    }
                    
                    // Afficher les erreurs partielles si présentes
                    if (response.errors && response.errors.length > 0) {
                        console.warn('Erreurs upload:', response.errors);
                        response.errors.forEach(error => {
                            showNotification(`⚠️ ${error}`, 'warning');
                        });
                    }
                } catch (e) {
                    console.error('Erreur parsing JSON:', e);
                    console.error('Réponse brute:', xhr.responseText);
                    showNotification('❌ Erreur lors du traitement de la réponse serveur', 'error');
                }
            } else {
                console.error('Erreur HTTP:', xhr.status, xhr.statusText);
                showNotification(`❌ Erreur serveur (${xhr.status}): ${xhr.statusText}`, 'error');
            }
        });
        
        xhr.addEventListener('error', () => {
            if (uploadProgress) {
                uploadProgress.classList.add('hidden');
            }
            showNotification('❌ Erreur réseau lors de l\'upload', 'error');
        });
        
        xhr.open('POST', '{{ route("admin.media.upload") }}');
        xhr.send(formData);
    }
    
    // Gestionnaires pour la recherche et le tri
    const mediaSearch = document.getElementById('mediaSearch');
    const mediaSort = document.getElementById('mediaSort');
    
    if (mediaSearch) {
        mediaSearch.addEventListener('input', applyMediaFilters);
    }
    
    if (mediaSort) {
        mediaSort.addEventListener('change', applyMediaFilters);
    }
    
    // Gestionnaire pour le bouton de vue
    const toggleView = document.getElementById('toggleView');
    if (toggleView) {
        let isGridView = true;
        toggleView.addEventListener('click', () => {
            const mediaList = document.getElementById('mediaList');
            if (isGridView) {
                mediaList.className = 'space-y-2';
                toggleView.innerHTML = `
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                `;
                isGridView = false;
            } else {
                mediaList.className = 'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4';
                toggleView.innerHTML = `
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                `;
                isGridView = true;
            }
        });
    }
});
</script>

<!-- Scripts de validation avancée et utilitaires -->
<script>
// Validation du formulaire projet
document.addEventListener('DOMContentLoaded', function() {
    // Génération automatique du slug
    const nomInput = document.getElementById('nom');
    const slugPreview = document.getElementById('slug-preview');
    
    if (nomInput && slugPreview) {
        nomInput.addEventListener('input', function() {
            const slug = generateSlug(this.value);
            slugPreview.textContent = slug || 'sera-generee-automatiquement';
        });
    }
    
    // Calcul automatique des bénéficiaires totaux
    const beneficiairesInputs = ['beneficiaires_hommes', 'beneficiaires_femmes', 'beneficiaires_enfants'];
    const totalInput = document.getElementById('beneficiaires_total');
    
    if (totalInput) {
        beneficiairesInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('input', calculateTotalBeneficiaires);
            }
        });
        
        // Calcul initial
        calculateTotalBeneficiaires();
    }
    
    // Validation des dates
    const dateDebutInput = document.getElementById('date_debut');
    const dateFinInput = document.getElementById('date_fin');
    
    if (dateDebutInput && dateFinInput) {
        dateDebutInput.addEventListener('change', validateDates);
        dateFinInput.addEventListener('change', validateDates);
    }
    
    // Validation du fichier image
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            validateImageFile(this);
        });
    }
    
    // Validation finale avant soumission
    const form = document.getElementById('projet-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }
        });
    }
});

// Fonctions utilitaires
function generateSlug(text) {
    return text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}

function calculateTotalBeneficiaires() {
    const hommes = parseInt(document.getElementById('beneficiaires_hommes')?.value) || 0;
    const femmes = parseInt(document.getElementById('beneficiaires_femmes')?.value) || 0;
    const enfants = parseInt(document.getElementById('beneficiaires_enfants')?.value) || 0;
    
    const total = hommes + femmes + enfants;
    const totalInput = document.getElementById('beneficiaires_total');
    
    if (totalInput) {
        totalInput.value = total;
    }
}

function validateDates() {
    const dateDebut = document.getElementById('date_debut')?.value;
    const dateFin = document.getElementById('date_fin')?.value;
    
    if (dateDebut && dateFin) {
        const debut = new Date(dateDebut);
        const fin = new Date(dateFin);
        
        if (fin <= debut) {
            const dateFinInput = document.getElementById('date_fin');
            dateFinInput.setCustomValidity('La date de fin doit être postérieure à la date de début');
            dateFinInput.reportValidity();
            return false;
        } else {
            document.getElementById('date_fin').setCustomValidity('');
        }
    }
    
    return true;
}

function validateImageFile(input) {
    const file = input.files[0];
    const errorMessage = document.getElementById('file-error-message');
    const errorText = document.getElementById('file-error-text');
    
    if (file) {
        const maxSize = 10 * 1024 * 1024; // 10 MB
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        
        if (file.size > maxSize) {
            errorText.textContent = `Le fichier est trop volumineux (${(file.size / 1024 / 1024).toFixed(1)} MB). Taille maximale : 10 MB.`;
            errorMessage.classList.remove('hidden');
            input.value = '';
            return false;
        }
        
        if (!allowedTypes.includes(file.type)) {
            errorText.textContent = 'Format de fichier non autorisé. Utilisez : JPEG, PNG, GIF, WebP ou SVG.';
            errorMessage.classList.remove('hidden');
            input.value = '';
            return false;
        }
        
        errorMessage.classList.add('hidden');
        return true;
    }
    
    return true;
}

function validateForm() {
    let isValid = true;
    
    // Vérifier les champs requis
    const requiredFields = ['nom', 'date_debut', 'service_id', 'etat', 'description'];
    
    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field && !field.value.trim()) {
            field.focus();
            field.scrollIntoView({ behavior: 'smooth', block: 'center' });
            isValid = false;
            return false;
        }
    });
    
    // Validation des dates
    if (!validateDates()) {
        isValid = false;
    }
    
    // Validation de l'image si présente
    const imageInput = document.getElementById('image');
    if (imageInput && imageInput.files.length > 0) {
        if (!validateImageFile(imageInput)) {
            isValid = false;
        }
    }
    
    return isValid;
}

// Fonction de prévisualisation d'image
function previewImageProjet(input) {
    const file = input.files[0];
    const preview = document.getElementById('image-preview-projet');
    const placeholder = document.getElementById('image-placeholder-projet');
    
    if (file && validateImageFile(input)) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        
        reader.readAsDataURL(file);
    } else {
        if (preview) {
            preview.classList.add('hidden');
        }
        if (placeholder) {
            placeholder.classList.remove('hidden');
        }
    }
}

// Gestion du bouton médiathèque externe
document.addEventListener('DOMContentLoaded', function() {
    const testMediaButton = document.getElementById('testMediaButton');
    if (testMediaButton) {
        testMediaButton.addEventListener('click', function() {
            // Utiliser l'éditeur global stocké lors de l'initialisation
            if (window.globalEditor) {
                openMediaModal(window.globalEditor);
            } else {
                openMediaModal();
                showNotification('⚠️ Éditeur non disponible. Veuillez attendre l\'initialisation complète.', 'warning');
            }
        });
    }
});

// Fonction pour fermer le modal avec la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMediaModal();
    }
});

// Fonction de débogage pour vérifier l'état des éditeurs
function debugEditorState() {
    console.log('🔍 État des éditeurs:');
    console.log('- window.globalEditor:', window.globalEditor ? 'Disponible' : 'Non disponible');
    console.log('- window.currentEditor:', window.currentEditor ? 'Disponible' : 'Non disponible');
    if (window.globalEditor) {
        console.log('- CKEditor état:', window.globalEditor.isReadOnly ? 'Lecture seule' : 'Éditable');
    }
}

// Fermer le modal en cliquant à l'extérieur
document.addEventListener('click', function(e) {
    const modal = document.getElementById('mediaModal');
    if (modal && e.target === modal) {
        closeMediaModal();
    }
});
</script>
