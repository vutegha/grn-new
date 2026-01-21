@extends('layouts.admin')

@section('title', 'Créer un Auteur')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="list-none p-0 inline-flex">
        <li class="flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-iri-primary hover:text-iri-secondary">Dashboard</a>
            <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.476 239.03c9.373 9.372 9.373 24.568 0 33.941z"/>
            </svg>
        </li>
        <li class="flex items-center">
            <a href="{{ route('admin.auteur.index') }}" class="text-iri-primary hover:text-iri-secondary">Auteurs</a>
            <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.476 239.03c9.373 9.372 9.373 24.568 0 33.941z"/>
            </svg>
        </li>
        <li class="text-gray-500">Nouveau</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-user-plus mr-3 text-iri-primary"></i>
                    Créer un Auteur
                </h1>
                <p class="text-gray-600 mt-1">Ajoutez un nouveau auteur à votre base de données</p>
            </div>
            
            <a href="{{ route('admin.auteur.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-iri-primary to-iri-secondary">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Informations de l'auteur
            </h3>
        </div>

        <form action="{{ route('admin.auteur.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Photo de profil -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-camera mr-2"></i>Photo de profil
                    </label>
                    
                    <div class="flex items-start space-x-6">
                        <!-- Aperçu -->
                        <div class="flex-shrink-0">
                            <div id="photo-preview" class="h-24 w-24 rounded-full bg-gradient-to-r from-iri-primary to-iri-secondary flex items-center justify-center overflow-hidden">
                                <i class="fas fa-user text-white text-2xl"></i>
                            </div>
                        </div>
                        
                        <!-- Upload -->
                        <div class="flex-1">
                            <input type="file" name="photo" id="photo" accept="image/*" 
                                   class="hidden" onchange="previewPhoto(this)">
                            <label for="photo" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <i class="fas fa-upload mr-2"></i>
                                Choisir une photo
                            </label>
                            <p class="text-sm text-gray-500 mt-2">
                                Formats acceptés : JPG, PNG, GIF. Taille max : 2MB
                            </p>
                            @error('photo')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-1"></i>Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nom" id="nom" required
                           value="{{ old('nom') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('nom') border-red-500 @enderror"
                           placeholder="Nom de famille">
                    @error('nom')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prénom -->
                <div>
                    <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-1"></i>Prénom
                    </label>
                    <input type="text" name="prenom" id="prenom"
                           value="{{ old('prenom') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('prenom') border-red-500 @enderror"
                           placeholder="Prénom">
                    @error('prenom')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-1"></i>Email
                    </label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('email') border-red-500 @enderror"
                           placeholder="email@example.com">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Téléphone -->
                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-1"></i>Téléphone
                    </label>
                    <input type="tel" name="telephone" id="telephone"
                           value="{{ old('telephone') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('telephone') border-red-500 @enderror"
                           placeholder="+243 XXX XXX XXX">
                    @error('telephone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Titre/Fonction -->
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-id-badge mr-1"></i>Titre/Fonction
                    </label>
                    <input type="text" name="titre" id="titre"
                           value="{{ old('titre') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('titre') border-red-500 @enderror"
                           placeholder="Ex: Directeur de recherche, Professeur...">
                    @error('titre')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Institution -->
                <div>
                    <label for="institution" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-building mr-1"></i>Institution
                    </label>
                    <input type="text" name="institution" id="institution"
                           value="{{ old('institution') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('institution') border-red-500 @enderror"
                           placeholder="Nom de l'institution">
                    @error('institution')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Site web -->
                <div class="md:col-span-2">
                    <label for="site_web" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-globe mr-1"></i>Site web
                    </label>
                    <input type="url" name="site_web" id="site_web"
                           value="{{ old('site_web') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('site_web') border-red-500 @enderror"
                           placeholder="https://example.com">
                    @error('site_web')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Biographie -->
                <div class="md:col-span-2">
                    <label for="biographie" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-1"></i>Biographie
                    </label>
                    <textarea name="biographie" id="biographie" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('biographie') border-red-500 @enderror"
                              placeholder="Décrivez le parcours et les expertises de l'auteur...">{{ old('biographie') }}</textarea>
                    @error('biographie')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Domaines d'expertise -->
                <div class="md:col-span-2">
                    <label for="domaines_expertise" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tags mr-1"></i>Domaines d'expertise
                    </label>
                    <input type="text" name="domaines_expertise" id="domaines_expertise"
                           value="{{ old('domaines_expertise') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary @error('domaines_expertise') border-red-500 @enderror"
                           placeholder="Ex: Recherche sociale, Économie, Environnement... (séparés par des virgules)">
                    @error('domaines_expertise')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">Séparez les domaines par des virgules</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.auteur.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </a>
                
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    Créer l'auteur
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewPhoto(input) {
    const preview = document.getElementById('photo-preview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="h-24 w-24 rounded-full object-cover">`;
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.innerHTML = '<i class="fas fa-user text-white text-2xl"></i>';
    }
}

// Auto-génération du slug à partir du nom et prénom
document.getElementById('nom').addEventListener('input', updatePreview);
document.getElementById('prenom').addEventListener('input', updatePreview);

function updatePreview() {
    const nom = document.getElementById('nom').value;
    const prenom = document.getElementById('prenom').value;
    const preview = document.getElementById('photo-preview');
    
    if (!preview.querySelector('img')) {
        const initiales = (nom.charAt(0) + (prenom.charAt(0) || '')).toUpperCase();
        preview.innerHTML = `<span class="text-white font-semibold text-xl">${initiales}</span>`;
    }
}
</script>
@endpush
@endsection
