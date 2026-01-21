<!-- Modern Author Form with Protection -->
<form id="auteurForm" action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="space-y-6">
  @csrf
  @if(isset($auteur))
  @method('PUT')
  @endif
  
  <!-- Progress Indicator -->
  <div class="mb-8">
  <div class="flex items-center justify-between mb-4">
  <h2 class="text-xl font-semibold text-gray-900">{{ isset($auteur) ? 'Modifier l\'auteur' : 'Nouvel auteur' }}</h2>
  <div class="text-sm text-gray-500">
  Étape 1 sur 1 - Informations personnelles
  </div>
  </div>
  <div class="w-full bg-gray-200 rounded-full h-2">
  <div class="bg-gradient-to-r from-iri-primary to-iri-secondary h-2 rounded-full" style="width: 100%"></div>
  </div>
  </div>

  <!-- Basic Information Section -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
  <div class="flex items-center mb-6">
  <div class="w-8 h-8 bg-iri-primary/10 rounded-lg flex items-center justify-center mr-3">
  <svg class="w-5 h-5 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
  </svg>
  </div>
  <h3 class="text-lg font-semibold text-gray-900">Informations personnelles</h3>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <!-- Nom (Required) -->
  <div>
  <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
  Nom <span class="text-red-500">*</span>
  </label>
  <input type="text" 
  id="nom" 
  name="nom"
  value="{{ old('nom', $auteur->nom ?? '') }}" 
  required
  maxlength="100"
  class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 @error('nom') border-red-500 ring-red-500 @enderror"
  placeholder="Entrez le nom de famille">
  @error('nom')
  <p class="mt-2 text-sm text-red-600 flex items-center">
  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
  </svg>
  {{ $message }}
  </p>
  @enderror
  </div>

  <!-- Prénom -->
  <div>
  <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
  <input type="text" 
  id="prenom" 
  name="prenom"
  value="{{ old('prenom', $auteur->prenom ?? '') }}" 
  maxlength="100"
  class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 @error('prenom') border-red-500 ring-red-500 @enderror"
  placeholder="Entrez le prénom">
  @error('prenom')
  <p class="mt-2 text-sm text-red-600 flex items-center">
  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
  </svg>
  {{ $message }}
  </p>
  @enderror
  </div>
  
  <!-- Email -->
  <div>
  <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email</label>
  <input type="email" 
  id="email" 
  name="email"
  value="{{ old('email', $auteur->email ?? '') }}" 
  maxlength="255"
  class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 @error('email') border-red-500 ring-red-500 @enderror"
  placeholder="exemple@email.com">
  @error('email')
  <p class="mt-2 text-sm text-red-600 flex items-center">
  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
  </svg>
  {{ $message }}
  </p>
  @enderror
  </div>

  <!-- Institution -->
  <div>
  <label for="institution" class="block text-sm font-medium text-gray-700 mb-2">Institution</label>
  <input type="text" 
  id="institution" 
  name="institution"
  value="{{ old('institution', $auteur->institution ?? '') }}" 
  maxlength="255"
  class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 @error('institution') border-red-500 ring-red-500 @enderror"
  placeholder="Nom de l'institution ou organisation">
  @error('institution')
  <p class="mt-2 text-sm text-red-600 flex items-center">
  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
  </svg>
  {{ $message }}
  </p>
  @enderror
  </div>
  </div>
  </div>

  <!-- Biography Section -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
  <div class="flex items-center mb-6">
  <div class="w-8 h-8 bg-iri-primary/10 rounded-lg flex items-center justify-center mr-3">
  <svg class="w-5 h-5 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
  </svg>
  </div>
  <h3 class="text-lg font-semibold text-gray-900">Biographie</h3>
  <span class="ml-2 text-sm text-gray-500">(Optionnel)</span>
  </div>

  <div>
  <label for="biographie" class="block text-sm font-medium text-gray-700 mb-2">
  Biographie de l'auteur
  </label>
  <textarea id="biographie" 
  name="biographie" 
  rows="6"
  maxlength="2000"
  class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 @error('biographie') border-red-500 ring-red-500 @enderror"
  placeholder="Décrivez brièvement l'auteur, son parcours, ses domaines d'expertise...">{{ old('biographie', $auteur->biographie ?? '') }}</textarea>
  <div class="mt-2 flex justify-between items-center">
  <div class="text-sm text-gray-500">
  Partagez les informations importantes sur cet auteur
  </div>
  <div class="text-sm text-gray-400">
  <span id="biographieCount">0</span>/2000 caractères
  </div>
  </div>
  @error('biographie')
  <p class="mt-2 text-sm text-red-600 flex items-center">
  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
  </svg>
  {{ $message }}
  </p>
  @enderror
  </div>
  </div>

  <!-- Professional Information Section -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
  <div class="flex items-center mb-6">
  <div class="w-8 h-8 bg-iri-primary/10 rounded-lg flex items-center justify-center mr-3">
  <svg class="w-5 h-5 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
  </svg>
  </div>
  <h3 class="text-lg font-semibold text-gray-900">Informations professionnelles</h3>
  <span class="ml-2 text-sm text-gray-500">(Optionnel)</span>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <!-- Titre professionnel -->
  <div>
  <label for="titre_professionnel" class="block text-sm font-medium text-gray-700 mb-2">Titre professionnel</label>
  <input type="text" 
  id="titre_professionnel" 
  name="titre_professionnel"
  value="{{ old('titre_professionnel', $auteur->titre_professionnel ?? '') }}" 
  maxlength="255"
  class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 @error('titre_professionnel') border-red-500 ring-red-500 @enderror"
  placeholder="Ex: Chercheur principal, Professeur...">
  @error('titre_professionnel')
  <p class="mt-2 text-sm text-red-600 flex items-center">
  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
  </svg>
  {{ $message }}
  </p>
  @enderror
  </div>

  <!-- Téléphone -->
  <div>
  <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
  <input type="tel" 
  id="telephone" 
  name="telephone"
  value="{{ old('telephone', $auteur->telephone ?? '') }}" 
  maxlength="50"
  class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 @error('telephone') border-red-500 ring-red-500 @enderror"
  placeholder="+243 XXX XXX XXX">
  @error('telephone')
  <p class="mt-2 text-sm text-red-600 flex items-center">
  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
  </svg>
  {{ $message }}
  </p>
  @enderror
  </div>
  
  <!-- ORCID -->
  <div class="md:col-span-2">
  <label for="orcid" class="block text-sm font-medium text-gray-700 mb-2">
  <svg class="inline-block w-4 h-4 mr-1" viewBox="0 0 256 256" fill="#A6CE39">
  <path d="M256,128c0,70.7-57.3,128-128,128C57.3,256,0,198.7,0,128C0,57.3,57.3,0,128,0C198.7,0,256,57.3,256,128z"/>
  <path fill="#fff" d="M86.3,186.2H70.9V79.1h15.4v48.4V186.2z M108.9,79.1h41.6c39.6,0,57,28.3,57,53.6c0,27.5-21.5,53.6-56.8,53.6h-41.8V79.1z M124.3,172.4h24.5c34.9,0,42.9-26.5,42.9-39.7c0-21.5-13.7-39.7-43.7-39.7h-23.7V172.4z M88.7,56.8c0,5.5-4.5,10.1-10.1,10.1c-5.6,0-10.1-4.6-10.1-10.1c0-5.6,4.5-10.1,10.1-10.1C84.2,46.7,88.7,51.3,88.7,56.8z"/>
  </svg>
  ORCID iD
  </label>
  <div class="flex items-center">
  <span class="text-sm text-gray-500 mr-2">https://orcid.org/</span>
  <input type="text" 
  id="orcid" 
  name="orcid"
  value="{{ old('orcid', $auteur->orcid ?? '') }}" 
  pattern="\d{4}-\d{4}-\d{4}-\d{3}[0-9X]"
  maxlength="19"
  class="flex-1 px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 @error('orcid') border-red-500 ring-red-500 @enderror"
  placeholder="0000-0002-1825-0097">
  </div>
  <p class="mt-1 text-xs text-gray-500">Format: 0000-0002-1825-0097. <a href="https://orcid.org/register" target="_blank" class="text-iri-primary hover:underline">Créer un ORCID iD</a></p>
  @error('orcid')
  <p class="mt-2 text-sm text-red-600 flex items-center">
  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
  </svg>
  {{ $message }}
  </p>
  @enderror
  </div>
  </div>
  </div>

  <!-- Social Links Section with Dynamic Addition -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6" x-data="socialLinksManager()">
  <div class="flex items-center mb-6">
  <div class="w-8 h-8 bg-iri-primary/10 rounded-lg flex items-center justify-center mr-3">
  <svg class="w-5 h-5 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
  </svg>
  </div>
  <h3 class="text-lg font-semibold text-gray-900">Liens sociaux et professionnels</h3>
  <span class="ml-2 text-sm text-gray-500">(Optionnel)</span>
  </div>

  <div class="grid grid-cols-1 gap-6">
  <!-- LinkedIn (toujours visible) -->
  <div>
  <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-2">
  <i class="fab fa-linkedin text-blue-600 mr-1"></i> LinkedIn
  </label>
  <input type="url" 
  id="linkedin" 
  name="linkedin"
  value="{{ old('linkedin', $auteur->linkedin ?? '') }}" 
  maxlength="500"
  class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 @error('linkedin') border-red-500 ring-red-500 @enderror"
  placeholder="https://linkedin.com/in/votre-profil">
  @error('linkedin')
  <p class="mt-2 text-sm text-red-600 flex items-center">
  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
  </svg>
  {{ $message }}
  </p>
  @enderror
  </div>

  <!-- Champs sociaux dynamiques -->
  <div id="additionalSocialLinks">
  <!-- Twitter/X -->
  <div x-show="visibleFields.twitter" x-transition class="mb-6" style="display: {{ old('twitter', $auteur->twitter ?? '') ? 'block' : 'none' }}">
  <div class="flex items-center justify-between mb-2">
  <label for="twitter" class="block text-sm font-medium text-gray-700">
  <i class="fab fa-twitter text-sky-500 mr-1"></i> Twitter / X
  </label>
  <button type="button" @click="hideField('twitter')" class="text-gray-400 hover:text-red-500 transition-colors">
  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
  </svg>
  </button>
  </div>
  <input type="url" 
  id="twitter" 
  name="twitter"
  value="{{ old('twitter', $auteur->twitter ?? '') }}" 
  maxlength="500"
  class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200"
  placeholder="https://twitter.com/votre-compte">
  </div>

  <!-- Facebook -->
  <div x-show="visibleFields.facebook" x-transition class="mb-6" style="display: {{ old('facebook', $auteur->facebook ?? '') ? 'block' : 'none' }}">
  <div class="flex items-center justify-between mb-2">
  <label for="facebook" class="block text-sm font-medium text-gray-700">
  <i class="fab fa-facebook text-blue-600 mr-1"></i> Facebook
  </label>
  <button type="button" @click="hideField('facebook')" class="text-gray-400 hover:text-red-500 transition-colors">
  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
  </svg>
  </button>
  </div>
  <input type="url" 
  id="facebook" 
  name="facebook"
  value="{{ old('facebook', $auteur->facebook ?? '') }}" 
  maxlength="500"
  class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200"
  placeholder="https://facebook.com/votre-page">
  </div>

  <!-- Instagram -->
  <div x-show="visibleFields.instagram" x-transition class="mb-6" style="display: {{ old('instagram', $auteur->instagram ?? '') ? 'block' : 'none' }}">
  <div class="flex items-center justify-between mb-2">
  <label for="instagram" class="block text-sm font-medium text-gray-700">
  <i class="fab fa-instagram text-pink-500 mr-1"></i> Instagram
  </label>
  <button type="button" @click="hideField('instagram')" class="text-gray-400 hover:text-red-500 transition-colors">
  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
  </svg>
  </button>
  </div>
  <input type="url" 
  id="instagram" 
  name="instagram"
  value="{{ old('instagram', $auteur->instagram ?? '') }}" 
  maxlength="500"
  class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200"
  placeholder="https://instagram.com/votre-compte">
  </div>

  <!-- GitHub -->
  <div x-show="visibleFields.github" x-transition class="mb-6" style="display: {{ old('github', $auteur->github ?? '') ? 'block' : 'none' }}">
  <div class="flex items-center justify-between mb-2">
  <label for="github" class="block text-sm font-medium text-gray-700">
  <i class="fab fa-github text-gray-800 mr-1"></i> GitHub
  </label>
  <button type="button" @click="hideField('github')" class="text-gray-400 hover:text-red-500 transition-colors">
  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
  </svg>
  </button>
  </div>
  <input type="url" 
  id="github" 
  name="github"
  value="{{ old('github', $auteur->github ?? '') }}" 
  maxlength="500"
  class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200"
  placeholder="https://github.com/votre-compte">
  </div>

  <!-- ResearchGate -->
  <div x-show="visibleFields.researchgate" x-transition class="mb-6" style="display: {{ old('researchgate', $auteur->researchgate ?? '') ? 'block' : 'none' }}">
  <div class="flex items-center justify-between mb-2">
  <label for="researchgate" class="block text-sm font-medium text-gray-700">
  <i class="fas fa-flask text-cyan-600 mr-1"></i> ResearchGate
  </label>
  <button type="button" @click="hideField('researchgate')" class="text-gray-400 hover:text-red-500 transition-colors">
  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
  </svg>
  </button>
  </div>
  <input type="url" 
  id="researchgate" 
  name="researchgate"
  value="{{ old('researchgate', $auteur->researchgate ?? '') }}" 
  maxlength="500"
  class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200"
  placeholder="https://researchgate.net/profile/votre-profil">
  </div>

  <!-- Website -->
  <div x-show="visibleFields.website" x-transition class="mb-6" style="display: {{ old('website', $auteur->website ?? '') ? 'block' : 'none' }}">
  <div class="flex items-center justify-between mb-2">
  <label for="website" class="block text-sm font-medium text-gray-700">
  <i class="fas fa-globe text-green-600 mr-1"></i> Site web personnel
  </label>
  <button type="button" @click="hideField('website')" class="text-gray-400 hover:text-red-500 transition-colors">
  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
  </svg>
  </button>
  </div>
  <input type="url" 
  id="website" 
  name="website"
  value="{{ old('website', $auteur->website ?? '') }}" 
  maxlength="500"
  class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200"
  placeholder="https://votre-site-web.com">
  </div>
  </div>

  <!-- Bouton d'ajout de lien social -->
  <div x-show="availableFields.length > 0" class="mt-4">
  <div class="relative" x-data="{ open: false }">
  <button type="button" 
  @click="open = !open"
  class="inline-flex items-center px-4 py-2 border border-dashed border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transition-all duration-200">
  <svg class="w-5 h-5 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
  </svg>
  Ajouter un lien social
  </button>

  <!-- Dropdown Menu -->
  <div x-show="open" 
  @click.away="open = false"
  x-transition:enter="transition ease-out duration-100"
  x-transition:enter-start="transform opacity-0 scale-95"
  x-transition:enter-end="transform opacity-100 scale-100"
  x-transition:leave="transition ease-in duration-75"
  x-transition:leave-start="transform opacity-100 scale-100"
  x-transition:leave-end="transform opacity-0 scale-95"
  class="absolute z-10 mt-2 w-64 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5"
  style="display: none;">
  <div class="py-1">
  <template x-for="field in availableFields" :key="field.name">
  <button type="button"
  @click="showField(field.name); open = false"
  class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
  x-html="field.icon + ' ' + field.label">
  </button>
  </template>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>

  <!-- Photo Section -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6" id="photo">
  <div class="flex items-center mb-6">
  <div class="w-8 h-8 bg-iri-primary/10 rounded-lg flex items-center justify-center mr-3">
  <svg class="w-5 h-5 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
  </svg>
  </div>
  <h3 class="text-lg font-semibold text-gray-900">Photo de profil</h3>
  <span class="ml-2 text-sm text-gray-500">(Optionnel)</span>
  </div>

  <div class="flex flex-col md:flex-row md:items-start md:space-x-6">
  <!-- Current Photo Preview -->
  <div class="flex-shrink-0 mb-4 md:mb-0">
  <div class="w-32 h-32 bg-gray-100 rounded-xl overflow-hidden border-2 border-dashed border-gray-300" id="photoPreview">
  @if(isset($auteur) && $auteur->photo)
  <img src="{{ asset('storage/' . $auteur->photo) }}" 
  alt="Photo actuelle" 
  class="w-full h-full object-cover">
  @else
  <div class="w-full h-full flex items-center justify-center text-gray-400">
  <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2-2H5a2 2 0 01-2-2V9z"/>
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
  </svg>
  </div>
  @endif
  </div>
  </div>

  <!-- Photo Upload -->
  <div class="flex-1">
  <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
  Choisir une nouvelle photo
  </label>
  <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-iri-primary transition-colors duration-200" id="dropZone">
  <div class="space-y-1 text-center">
  <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
  <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
  <div class="flex text-sm text-gray-600">
  <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-iri-primary hover:text-iri-secondary">
  <span>Télécharger un fichier</span>
  <input id="photo" name="photo" type="file" accept="image/*" class="sr-only" onchange="previewPhoto(this)">
  </label>
  <p class="pl-1">ou glisser-déposer</p>
  </div>
  <p class="text-xs text-gray-500">PNG, JPG, JPEG jusqu'à 2MB</p>
  </div>
  </div>
  @error('photo')
  <p class="mt-2 text-sm text-red-600 flex items-center">
  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
  </svg>
  {{ $message }}
  </p>
  @enderror
  </div>
  </div>
  </div>

  <!-- Action Buttons -->
  <div class="flex flex-col sm:flex-row gap-4 pt-6">
  <button type="submit" 
  id="submitBtn"
  class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transition-all duration-200 transform hover:scale-105">
  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
  </svg>
  <span id="submitText">{{ isset($auteur) ? 'Modifier l\'auteur' : 'Créer l\'auteur' }}</span>
  </button>
  
  <a href="{{ route('admin.auteur.index') }}" 
  class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transition-all duration-200">
  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
  </svg>
  Annuler
  </a>
  </div>
</form>

<script>
// Alpine.js component pour gérer l'affichage dynamique des liens sociaux
// Doit être défini globalement avant Alpine.js
window.socialLinksManager = function() {
    return {
        visibleFields: {
            twitter: {{ old('twitter', $auteur->twitter ?? '') ? 'true' : 'false' }},
            facebook: {{ old('facebook', $auteur->facebook ?? '') ? 'true' : 'false' }},
            instagram: {{ old('instagram', $auteur->instagram ?? '') ? 'true' : 'false' }},
            github: {{ old('github', $auteur->github ?? '') ? 'true' : 'false' }},
            researchgate: {{ old('researchgate', $auteur->researchgate ?? '') ? 'true' : 'false' }},
            website: {{ old('website', $auteur->website ?? '') ? 'true' : 'false' }}
        },
        
        allFields: [
            { 
                name: 'twitter', 
                label: 'Twitter / X',
                icon: '<i class="fab fa-twitter text-sky-500 mr-2"></i>'
            },
            { 
                name: 'facebook', 
                label: 'Facebook',
                icon: '<i class="fab fa-facebook text-blue-600 mr-2"></i>'
            },
            { 
                name: 'instagram', 
                label: 'Instagram',
                icon: '<i class="fab fa-instagram text-pink-500 mr-2"></i>'
            },
            { 
                name: 'github', 
                label: 'GitHub',
                icon: '<i class="fab fa-github text-gray-800 mr-2"></i>'
            },
            { 
                name: 'researchgate', 
                label: 'ResearchGate',
                icon: '<i class="fas fa-flask text-cyan-600 mr-2"></i>'
            },
            { 
                name: 'website', 
                label: 'Site web personnel',
                icon: '<i class="fas fa-globe text-green-600 mr-2"></i>'
            }
        ],
        
        get availableFields() {
            return this.allFields.filter(field => !this.visibleFields[field.name]);
        },
        
        showField(fieldName) {
            this.visibleFields[fieldName] = true;
        },
        
        hideField(fieldName) {
            this.visibleFields[fieldName] = false;
            // Vider le champ caché
            const input = document.getElementById(fieldName);
            if (input) {
                input.value = '';
            }
        }
    }
}
</script>

@push('scripts')
<script>
// Compteur de caractères pour la biographie
document.addEventListener('DOMContentLoaded', function() {
    const biographieTextarea = document.getElementById('biographie');
    const biographieCount = document.getElementById('biographieCount');
    
    if (biographieTextarea && biographieCount) {
        function updateCount() {
            biographieCount.textContent = biographieTextarea.value.length;
        }
        
        biographieTextarea.addEventListener('input', updateCount);
        updateCount();
    }
});

// Prévisualisation de la photo
function previewPhoto(input) {
    const preview = document.getElementById('photoPreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Prévisualisation" class="w-full h-full object-cover">';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Drag and drop pour la photo
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const photoInput = document.getElementById('photo');
    
    if (dropZone && photoInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight(e) {
            dropZone.classList.add('border-iri-primary', 'bg-blue-50');
        }
        
        function unhighlight(e) {
            dropZone.classList.remove('border-iri-primary', 'bg-blue-50');
        }
        
        dropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                photoInput.files = files;
                previewPhoto(photoInput);
            }
        }
    }
});
</script>
@endpush

