<!-- Formulaire moderne pour événements -->
<div class="space-y-8">
    <!-- Section principale -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations de base -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary border-b">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Informations générales
                    </h3>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Titre -->
                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading mr-2 text-iri-primary"></i>
                            Titre de l'événement <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="titre" 
                               id="titre" 
                               value="{{ old('titre', $evenement->titre ?? '') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary transition-colors @error('titre') border-red-500 @enderror"
                               placeholder="Ex: Conférence internationale sur le développement durable" 
                               required>
                        @error('titre')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Résumé -->
                    <div>
                        <label for="resume" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clipboard-list mr-2 text-iri-primary"></i>
                            Résumé court
                        </label>
                        <textarea name="resume" 
                                  id="resume" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary transition-colors @error('resume') border-red-500 @enderror"
                                  placeholder="Résumé accrocheur de l'événement (recommandé pour l'affichage public)">{{ old('resume', $evenement->resume ?? '') }}</textarea>
                        @error('resume')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Maximum 250 caractères recommandés</p>
                    </div>

                    <!-- Description complète -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-iri-primary"></i>
                            Description détaillée
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="8"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary transition-colors @error('description') border-red-500 @enderror"
                                  placeholder="Description complète de l'événement, objectifs, programme préliminaire, public cible...">{{ old('description', $evenement->description ?? '') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-iri-primary"></i>
                            Type d'événement
                        </label>
                        <select name="type" 
                                id="type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary transition-colors @error('type') border-red-500 @enderror">
                            <option value="conference" {{ old('type', $evenement->type ?? '') == 'conference' ? 'selected' : '' }}>Conférence</option>
                            <option value="seminaire" {{ old('type', $evenement->type ?? '') == 'seminaire' ? 'selected' : '' }}>Séminaire</option>
                            <option value="atelier" {{ old('type', $evenement->type ?? '') == 'atelier' ? 'selected' : '' }}>Atelier</option>
                            <option value="formation" {{ old('type', $evenement->type ?? '') == 'formation' ? 'selected' : '' }}>Formation</option>
                            <option value="table-ronde" {{ old('type', $evenement->type ?? '') == 'table-ronde' ? 'selected' : '' }}>Table ronde</option>
                            <option value="colloque" {{ old('type', $evenement->type ?? '') == 'colloque' ? 'selected' : '' }}>Colloque</option>
                            <option value="autre" {{ old('type', $evenement->type ?? '') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Dates et lieu -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold border-b">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        Dates et localisation
                    </h3>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Date et heure -->
                    <div>
                        <label for="date_evenement" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-iri-primary"></i>
                            Date et heure <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" 
                               name="date_evenement" 
                               id="date_evenement" 
                               value="{{ old('date_evenement', isset($evenement) && $evenement->date_evenement ? \Carbon\Carbon::parse($evenement->date_evenement)->format('Y-m-d\TH:i') : '') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary transition-colors @error('date_evenement') border-red-500 @enderror"
                               required>
                        @error('date_evenement')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Lieu -->
                    <div>
                        <label for="lieu" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-iri-primary"></i>
                            Lieu
                        </label>
                        <input type="text" 
                               name="lieu" 
                               id="lieu" 
                               value="{{ old('lieu', $evenement->lieu ?? '') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary transition-colors @error('lieu') border-red-500 @enderror"
                               placeholder="Ex: Salle de conférence IRI-UCBC">
                        @error('lieu')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne latérale (1/3) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Image et médias -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-indigo-600 border-b">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-image mr-3"></i>
                        Image de couverture
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="text-center">
                        <!-- Aperçu de l'image -->
                        <div id="image-preview" class="mb-4">
                            @if(isset($evenement) && $evenement->image)
                                <img src="{{ asset('storage/' . $evenement->image) }}" 
                                     alt="Image actuelle" 
                                     class="w-full h-48 object-cover rounded-lg border border-gray-200">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                                        <p class="text-gray-500 text-sm">Aucune image sélectionnée</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Upload -->
                        <input type="file" 
                               name="image" 
                               id="image" 
                               accept="image/*" 
                               class="hidden" 
                               onchange="previewImage(this)">
                        <label for="image" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 transition-all duration-200">
                            <i class="fas fa-upload mr-2"></i>
                            Choisir une image
                        </label>
                        
                        @if(isset($evenement) && $evenement->image)
                            <button type="button" 
                                    onclick="removeImage()" 
                                    class="ml-2 inline-flex items-center px-3 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="fas fa-trash mr-1"></i>
                                Supprimer
                            </button>
                        @endif
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-3 text-center">
                        Formats : JPG, PNG, GIF • Taille max : 5MB<br>
                        Résolution recommandée : 1200x600px
                    </p>
                    
                    @error('image')
                        <p class="mt-2 text-sm text-red-600 flex items-center justify-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Organisation -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 border-b">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-users mr-3"></i>
                        Organisation
                    </h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div>
                        <label for="organisateur" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-tie mr-2 text-iri-primary"></i>
                            Organisateur
                        </label>
                        <input type="text" 
                               name="organisateur" 
                               id="organisateur" 
                               value="{{ old('organisateur', $evenement->organisateur ?? 'IRI-UCBC') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary transition-colors @error('organisateur') border-red-500 @enderror"
                               placeholder="Nom de l'organisateur">
                        @error('organisateur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-iri-primary"></i>
                            Email de contact
                        </label>
                        <input type="email" 
                               name="contact_email" 
                               id="contact_email" 
                               value="{{ old('contact_email', $evenement->contact_email ?? '') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary transition-colors @error('contact_email') border-red-500 @enderror"
                               placeholder="contact@iri-ucbc.org">
                        @error('contact_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_telephone" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2 text-iri-primary"></i>
                            Téléphone de contact
                        </label>
                        <input type="tel" 
                               name="contact_telephone" 
                               id="contact_telephone" 
                               value="{{ old('contact_telephone', $evenement->contact_telephone ?? '') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary transition-colors @error('contact_telephone') border-red-500 @enderror"
                               placeholder="+243 XXX XXX XXX">
                        @error('contact_telephone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Paramètres avancés -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-orange-600 border-b">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-cogs mr-3"></i>
                        Paramètres avancés
                    </h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- En vedette -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" 
                                   name="en_vedette" 
                                   id="en_vedette" 
                                   value="1"
                                   {{ old('en_vedette', $evenement->en_vedette ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 text-iri-primary border-gray-300 rounded focus:ring-iri-primary">
                        </div>
                        <div class="ml-3">
                            <label for="en_vedette" class="text-sm font-medium text-gray-700">
                                <i class="fas fa-star mr-1 text-yellow-500"></i>
                                Mettre en vedette
                            </label>
                            <p class="text-xs text-gray-500">Afficher en priorité sur le site</p>
                        </div>
                    </div>

                    <!-- URL du rapport -->
                    <div>
                        <label for="rapport_url" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-link mr-2 text-iri-primary"></i>
                            Lien vers le rapport (après événement)
                        </label>
                        <input type="url" 
                               name="rapport_url" 
                               id="rapport_url" 
                               value="{{ old('rapport_url', $evenement->rapport_url ?? '') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-iri-primary focus:border-iri-primary transition-colors @error('rapport_url') border-red-500 @enderror"
                               placeholder="https://...">
                        @error('rapport_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Gestion de l'aperçu d'image
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" 
                     alt="Aperçu" 
                     class="w-full h-48 object-cover rounded-lg border border-gray-200">
            `;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Suppression d'image
function removeImage() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
        document.getElementById('image').value = '';
        document.getElementById('image-preview').innerHTML = `
            <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500 text-sm">Aucune image sélectionnée</p>
                </div>
            </div>
        `;
    }
}
</script>
@endpush
