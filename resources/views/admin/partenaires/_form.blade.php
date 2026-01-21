@props(['partenaire' => null, 'formAction'])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($partenaire) @method('PUT') @endif

        <!-- Header du formulaire -->
        <div class="border-b border-gray-200 pb-6">
            <h3 class="text-xl font-semibold text-gray-900">
                {{ $partenaire ? 'Modifier le partenaire' : 'Nouveau partenaire' }}
            </h3>
            <p class="mt-1 text-sm text-gray-600">
                {{ $partenaire ? 'Modifiez les informations de ce partenaire.' : 'Ajoutez un nouveau partenaire au réseau GRN-UCBC.' }}
            </p>
        </div>

        <!-- Section informations principales -->
        <div class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Nom du partenaire -->
                <div>
                    <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-building text-iri-primary mr-2"></i>Nom du partenaire *
                    </label>
                    <input type="text" 
                           id="nom"
                           name="nom" 
                           value="{{ old('nom', $partenaire->nom ?? '') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-all duration-200 @error('nom') border-red-500 @enderror"
                           placeholder="Ex: Université de Kinshasa"
                           required>
                    @error('nom')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Type de partenaire -->
                <div>
                    <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag text-iri-secondary mr-2"></i>Type de partenaire *
                    </label>
                    <select name="type" 
                            id="type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-secondary focus:border-transparent transition-all duration-200 @error('type') border-red-500 @enderror"
                            required>
                        <option value="">-- Sélectionner un type --</option>
                        <option value="universite" {{ old('type', $partenaire->type ?? '') == 'universite' ? 'selected' : '' }}>
                            Université
                        </option>
                        <option value="centre_recherche" {{ old('type', $partenaire->type ?? '') == 'centre_recherche' ? 'selected' : '' }}>
                            Centre de Recherche
                        </option>
                        <option value="organisation_internationale" {{ old('type', $partenaire->type ?? '') == 'organisation_internationale' ? 'selected' : '' }}>
                            Organisation Internationale
                        </option>
                        <option value="ong" {{ old('type', $partenaire->type ?? '') == 'ong' ? 'selected' : '' }}>
                            ONG
                        </option>
                        <option value="entreprise" {{ old('type', $partenaire->type ?? '') == 'entreprise' ? 'selected' : '' }}>
                            Entreprise
                        </option>
                        <option value="autre" {{ old('type', $partenaire->type ?? '') == 'autre' ? 'selected' : '' }}>
                            Autre
                        </option>
                    </select>
                    @error('type')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Logo -->
            <div>
                <label for="logo" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-image text-iri-gold mr-2"></i>Logo du partenaire
                </label>
                
                @if($partenaire && $partenaire->logo)
                    <div class="mb-4 flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <img src="{{ $partenaire->logo_url }}" 
                                 alt="Logo actuel" 
                                 class="w-20 h-20 object-contain rounded-lg border border-gray-200">
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Logo actuel</p>
                            <p class="text-xs text-gray-500 mt-1">Choisissez un nouveau fichier pour le remplacer</p>
                        </div>
                    </div>
                @endif
                
                <div class="relative">
                    <input type="file" 
                           id="logo"
                           name="logo" 
                           accept="image/*"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-gold focus:border-transparent transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-iri-gold file:text-white hover:file:bg-iri-primary @error('logo') border-red-500 @enderror">
                    <p class="mt-2 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Formats acceptés: JPG, PNG, SVG, WebP. Taille max: 5MB. Recommandé: 200x200px en format carré.
                    </p>
                </div>
                @error('logo')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-align-left text-iri-accent mr-2"></i>Description
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="4" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-accent focus:border-transparent transition-all duration-200 resize-none @error('description') border-red-500 @enderror"
                          placeholder="Description du partenaire, de ses activités et de sa mission...">{{ old('description', $partenaire->description ?? '') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Informations de contact -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-address-book text-iri-primary mr-2"></i>
                    Informations de contact
                </h4>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Site web -->
                    <div>
                        <label for="site_web" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-globe text-blue-500 mr-2"></i>Site web
                        </label>
                        <input type="url" 
                               id="site_web"
                               name="site_web" 
                               value="{{ old('site_web', $partenaire->site_web ?? '') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('site_web') border-red-500 @enderror"
                               placeholder="https://example.com">
                        @error('site_web')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email_contact" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope text-green-500 mr-2"></i>Email de contact
                        </label>
                        <input type="email" 
                               id="email_contact"
                               name="email_contact" 
                               value="{{ old('email_contact', $partenaire->email_contact ?? '') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('email_contact') border-red-500 @enderror"
                               placeholder="contact@example.com">
                        @error('email_contact')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label for="telephone" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-phone text-orange-500 mr-2"></i>Téléphone
                        </label>
                        <input type="text" 
                               id="telephone"
                               name="telephone" 
                               value="{{ old('telephone', $partenaire->telephone ?? '') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 @error('telephone') border-red-500 @enderror"
                               placeholder="+243 123 456 789">
                        @error('telephone')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Pays -->
                    <div>
                        <label for="pays" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-flag text-red-500 mr-2"></i>Pays
                        </label>
                        <input type="text" 
                               id="pays"
                               name="pays" 
                               value="{{ old('pays', $partenaire->pays ?? '') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('pays') border-red-500 @enderror"
                               placeholder="République Démocratique du Congo">
                        @error('pays')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Adresse -->
                <div class="mt-6">
                    <label for="adresse" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-purple-500 mr-2"></i>Adresse complète
                    </label>
                    <textarea name="adresse" 
                              id="adresse" 
                              rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 resize-none @error('adresse') border-red-500 @enderror"
                              placeholder="Adresse complète du partenaire...">{{ old('adresse', $partenaire->adresse ?? '') }}</textarea>
                    @error('adresse')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Statut -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-circle text-iri-accent mr-2"></i>
                    Statut du partenaire
                </h4>
                
                <div>
                    <label for="statut" class="block text-sm font-semibold text-gray-700 mb-2">
                        Statut *
                    </label>
                    <select name="statut" 
                            id="statut" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-accent focus:border-transparent transition-all duration-200 @error('statut') border-red-500 @enderror"
                            required>
                        <option value="">-- Sélectionner un statut --</option>
                        <option value="actif" {{ old('statut', $partenaire->statut ?? '') == 'actif' ? 'selected' : '' }}>
                            Actif
                        </option>
                        <option value="inactif" {{ old('statut', $partenaire->statut ?? '') == 'inactif' ? 'selected' : '' }}>
                            Inactif
                        </option>
                        <option value="en_negociation" {{ old('statut', $partenaire->statut ?? '') == 'en_negociation' ? 'selected' : '' }}>
                            En négociation
                        </option>
                    </select>
                    @error('statut')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
            <a href="{{ route('admin.partenaires.index') }}" 
               class="inline-flex items-center px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste
            </a>

            <div class="flex space-x-3">
                <button type="reset" 
                        class="inline-flex items-center px-6 py-3 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition-colors duration-200 font-medium">
                    <i class="fas fa-redo mr-2"></i>
                    Réinitialiser
                </button>

                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i>
                    {{ $partenaire ? 'Mettre à jour' : 'Créer le partenaire' }}
                </button>
            </div>
        </div>
    </form>
</div>
