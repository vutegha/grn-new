<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Colonne principale -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Informations de base -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-dark border-b rounded-t-xl">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-info-circle mr-3"></i>
                    Informations de base
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="type" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('type') border-red-500 @enderror">
                        <option value="">Sélectionner un type</option>
                        <option value="bureau_principal" {{ old('type', $contactInfo->type ?? '') == 'bureau_principal' ? 'selected' : '' }}>Bureau Principal</option>
                        <option value="bureau_regional" {{ old('type', $contactInfo->type ?? '') == 'bureau_regional' ? 'selected' : '' }}>Bureau Régional / Bureau de Liaison</option>
                        <option value="autre" {{ old('type', $contactInfo->type ?? '') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Les points focaux doivent être ajoutés dans la section "Responsable / Point Focal" d'un bureau régional
                    </p>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nom" id="nom" required
                           value="{{ old('nom', $contactInfo->nom ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('nom') border-red-500 @enderror"
                           placeholder="Ex: Bureau IRI Nord-Kivu">
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Titre -->
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                        Titre
                    </label>
                    <input type="text" name="titre" id="titre"
                           value="{{ old('titre', $contactInfo->titre ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('titre') border-red-500 @enderror"
                           placeholder="Ex: Bureau Régional de Goma">
                    @error('titre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Description du bureau ou point focal">{{ old('description', $contactInfo->description ?? '') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Adresse -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 border-b rounded-t-xl">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-map-marker-alt mr-3"></i>
                    Adresse
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <!-- Adresse -->
                <div>
                    <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">
                        Adresse complète
                    </label>
                    <textarea name="adresse" id="adresse" rows="2"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('adresse') border-red-500 @enderror"
                              placeholder="Ex: Avenue de l'Université, Quartier Masiani">{{ old('adresse', $contactInfo->adresse ?? '') }}</textarea>
                    @error('adresse')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Ville -->
                    <div>
                        <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">
                            Ville
                        </label>
                        <input type="text" name="ville" id="ville"
                               value="{{ old('ville', $contactInfo->ville ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('ville') border-red-500 @enderror"
                               placeholder="Ex: Beni">
                        @error('ville')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Province -->
                    <div>
                        <label for="province" class="block text-sm font-medium text-gray-700 mb-2">
                            Province
                        </label>
                        <input type="text" name="province" id="province"
                               value="{{ old('province', $contactInfo->province ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('province') border-red-500 @enderror"
                               placeholder="Ex: Nord-Kivu">
                        @error('province')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pays -->
                <div>
                    <label for="pays" class="block text-sm font-medium text-gray-700 mb-2">
                        Pays <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pays" id="pays" required
                           value="{{ old('pays', $contactInfo->pays ?? 'RDC') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('pays') border-red-500 @enderror">
                    @error('pays')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Coordonnées GPS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Latitude
                        </label>
                        <input type="number" step="0.0000001" name="latitude" id="latitude"
                               value="{{ old('latitude', $contactInfo->latitude ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('latitude') border-red-500 @enderror"
                               placeholder="Ex: 0.5000000">
                        @error('latitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Longitude
                        </label>
                        <input type="number" step="0.0000001" name="longitude" id="longitude"
                               value="{{ old('longitude', $contactInfo->longitude ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('longitude') border-red-500 @enderror"
                               placeholder="Ex: 29.5000000">
                        @error('longitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 border-b rounded-t-xl">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-phone mr-3"></i>
                    Coordonnées de contact du bureau
                    <span class="ml-2 text-xs bg-white/20 px-2 py-1 rounded">(Usage interne uniquement)</span>
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded">
                    <p class="text-xs text-blue-800">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>Note :</strong> Ces coordonnées (email, téléphone, horaires) ne sont PAS affichées sur le site public. 
                        Seules les informations du responsable/point focal et l'adresse du bureau sont visibles.
                    </p>
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email', $contactInfo->email ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('email') border-red-500 @enderror"
                           placeholder="exemple@iri.ucbc.org">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Téléphone -->
                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone
                        </label>
                        <input type="text" name="telephone" id="telephone"
                               value="{{ old('telephone', $contactInfo->telephone ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('telephone') border-red-500 @enderror"
                               placeholder="+243 000 000 000">
                        @error('telephone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone secondaire -->
                    <div>
                        <label for="telephone_secondaire" class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone secondaire
                        </label>
                        <input type="text" name="telephone_secondaire" id="telephone_secondaire"
                               value="{{ old('telephone_secondaire', $contactInfo->telephone_secondaire ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('telephone_secondaire') border-red-500 @enderror"
                               placeholder="+243 000 000 000">
                        @error('telephone_secondaire')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Responsable / Point Focal -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 bg-gradient-to-r from-orange-600 to-orange-700 border-b rounded-t-xl">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-user-tie mr-3"></i>
                    Responsable / Point Focal
                    <span class="ml-2 text-xs bg-white/20 px-2 py-1 rounded">(Affiché sur le site)</span>
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-green-50 border-l-4 border-green-600 p-3 rounded">
                    <p class="text-xs text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>
                        <strong>Informations affichées publiquement :</strong> Les informations du responsable/point focal 
                        (nom, fonction, email, téléphone, photo) sont affichées en priorité sur le site public dans une zone verte mise en évidence.
                    </p>
                </div>
                
                <!-- Nom du responsable -->
                <div>
                    <label for="responsable_nom" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom complet
                    </label>
                    <input type="text" name="responsable_nom" id="responsable_nom"
                           value="{{ old('responsable_nom', $contactInfo->responsable_nom ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('responsable_nom') border-red-500 @enderror"
                           placeholder="Ex: Dr. Jean Dupont">
                    @error('responsable_nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fonction -->
                <div>
                    <label for="responsable_fonction" class="block text-sm font-medium text-gray-700 mb-2">
                        Fonction
                    </label>
                    <input type="text" name="responsable_fonction" id="responsable_fonction"
                           value="{{ old('responsable_fonction', $contactInfo->responsable_fonction ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('responsable_fonction') border-red-500 @enderror"
                           placeholder="Ex: Coordinateur Régional">
                    @error('responsable_fonction')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Email du responsable -->
                    <div>
                        <label for="responsable_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" name="responsable_email" id="responsable_email"
                               value="{{ old('responsable_email', $contactInfo->responsable_email ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('responsable_email') border-red-500 @enderror"
                               placeholder="exemple@iri.ucbc.org">
                        @error('responsable_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone du responsable -->
                    <div>
                        <label for="responsable_telephone" class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone
                        </label>
                        <input type="text" name="responsable_telephone" id="responsable_telephone"
                               value="{{ old('responsable_telephone', $contactInfo->responsable_telephone ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('responsable_telephone') border-red-500 @enderror"
                               placeholder="+243 000 000 000">
                        @error('responsable_telephone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Photo du responsable -->
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-image mr-2 text-orange-500"></i>
                        Photo du responsable
                    </label>
                    @if(isset($contactInfo) && $contactInfo->photo)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $contactInfo->photo) }}" alt="Photo" class="w-32 h-32 rounded-full object-cover border-4 border-orange-200">
                            <p class="text-sm text-gray-600 mt-2">Photo actuelle</p>
                        </div>
                    @endif
                    <input type="file" name="photo" id="photo" accept="image/*"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('photo') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Format recommandé : JPG, PNG (max 2MB) - Idéal pour les points focaux</p>
                    @error('photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Colonne latérale -->
    <div class="space-y-6">
        <!-- Paramètres -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-600 to-gray-700 border-b rounded-t-xl">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-cog mr-3"></i>
                    Paramètres
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <!-- Ordre d'affichage -->
                <div>
                    <label for="ordre" class="block text-sm font-medium text-gray-700 mb-2">
                        Ordre d'affichage
                    </label>
                    <input type="number" name="ordre" id="ordre" min="0"
                           value="{{ old('ordre', $contactInfo->ordre ?? 0) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('ordre') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Plus le nombre est petit, plus l'élément apparaît en premier</p>
                    @error('ordre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actif -->
                <div class="flex items-center">
                    <input type="hidden" name="actif" value="0">
                    <input type="checkbox" name="actif" id="actif" value="1"
                           {{ old('actif', $contactInfo->actif ?? true) ? 'checked' : '' }}
                           class="w-5 h-5 text-iri-primary border-gray-300 rounded focus:ring-iri-primary">
                    <label for="actif" class="ml-3 text-sm font-medium text-gray-700">
                        Activer cette information
                    </label>
                </div>
            </div>
        </div>

        <!-- Horaires -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-700 border-b rounded-t-xl">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-clock mr-3"></i>
                    Horaires
                </h3>
            </div>
            <div class="p-6">
                <textarea name="horaires" id="horaires" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent @error('horaires') border-red-500 @enderror"
                          placeholder="Ex: Lundi - Vendredi: 8h - 17h&#10;Samedi: 8h - 12h">{{ old('horaires', $contactInfo->horaires ?? '') }}</textarea>
                @error('horaires')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="space-y-3">
                <button type="submit" 
                        class="w-full bg-iri-primary hover:bg-iri-dark text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer
                </button>
                <a href="{{ route('admin.contact-info.index') }}" 
                   class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold transition-colors flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </a>
            </div>
        </div>
    </div>
</div>
