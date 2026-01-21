{{-- Formulaire réutilisable pour les catégories (version simplifiée avec contraste élevé) --}}
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg border-2 border-gray-200">
    <div class="px-8 py-6">
        {{-- En-tête du formulaire --}}
        <div class="mb-8 pb-4 border-b-2 border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-edit mr-3 text-blue-600"></i>
                {{ isset($categorie) && $categorie->exists ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}
            </h2>
            <p class="text-gray-600 mt-1">Remplissez les informations ci-dessous</p>
        </div>

        <form action="{{ $formAction }}" method="POST" id="categorie-form" novalidate>
            @csrf
            @if(isset($categorie) && $categorie->exists)
                @method('PUT')
            @endif
            
            <div class="space-y-8">
                {{-- Nom de la catégorie --}}
                <div class="bg-gray-50 rounded-xl p-6 border-2 border-gray-200">
                    <label for="nom" class="block text-lg font-semibold text-gray-900 mb-3">
                        <i class="fas fa-tag mr-2 text-blue-600"></i>
                        Nom de la catégorie <span class="text-red-600 text-xl">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               name="nom" 
                               id="nom"
                               value="{{ old('nom', $categorie->nom ?? '') }}"
                               maxlength="255"
                               required
                               autocomplete="off"
                               class="block w-full px-4 py-4 text-lg font-medium rounded-xl border-2 border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/25 transition-all duration-200 @error('nom') border-red-400 focus:border-red-500 focus:ring-red-500/25 @enderror"
                               placeholder="Ex: Actualités, Publications, Services..."
                               style="min-height: 56px;">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm font-medium bg-white px-2 py-1 rounded" id="nom-counter">0/255</span>
                        </div>
                    </div>
                    @error('nom')
                        <div class="mt-3 p-3 bg-red-50 border-2 border-red-200 rounded-lg">
                            <p class="text-red-700 font-medium flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                {{ $message }}
                            </p>
                        </div>
                    @enderror
                    <p class="mt-3 text-sm text-gray-600 bg-blue-50 p-3 rounded-lg border-l-4 border-blue-400">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        Le nom sera utilisé pour identifier cette catégorie dans l'administration
                    </p>
                </div>

                {{-- Description --}}
                <div class="bg-gray-50 rounded-xl p-6 border-2 border-gray-200">
                    <label for="description" class="block text-lg font-semibold text-gray-900 mb-3">
                        <i class="fas fa-align-left mr-2 text-green-600"></i>
                        Description
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="5"
                              maxlength="1000"
                              class="block w-full px-4 py-4 text-base rounded-xl border-2 border-gray-300 bg-white shadow-sm focus:border-green-500 focus:ring-4 focus:ring-green-500/25 transition-all duration-200 @error('description') border-red-400 focus:border-red-500 focus:ring-red-500/25 @enderror resize-none"
                              placeholder="Description optionnelle de la catégorie (maximum 1000 caractères)&#10;&#10;Décrivez le type de contenu que cette catégorie va contenir..."
                              style="min-height: 120px;">{{ old('description', $categorie->description ?? '') }}</textarea>
                    
                    <div class="flex justify-between items-center mt-3">
                        @error('description')
                            <div class="p-3 bg-red-50 border-2 border-red-200 rounded-lg flex-1 mr-3">
                                <p class="text-red-700 font-medium flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    {{ $message }}
                                </p>
                            </div>
                        @else
                            <p class="text-sm text-gray-600 bg-green-50 p-3 rounded-lg border-l-4 border-green-400 flex-1 mr-3">
                                <i class="fas fa-lightbulb mr-2 text-green-600"></i>
                                Utilisée pour décrire le contenu de cette catégorie
                            </p>
                        @enderror
                        <span class="text-sm font-medium text-gray-600 bg-white px-3 py-2 rounded-lg border-2 border-gray-200" id="description-counter">0/1000</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-10 pt-6 border-t-2 border-gray-100 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.categorie.index') }}" 
                   class="inline-flex items-center px-6 py-3 border-2 border-gray-400 rounded-xl shadow-sm bg-white text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-500 focus:outline-none focus:ring-4 focus:ring-gray-500/25 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 border-2 border-transparent rounded-xl shadow-lg text-white font-bold bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-500/50 transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>
                    {{ isset($categorie) && $categorie->exists ? 'Mettre à jour' : 'Créer la catégorie' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Compteur de caractères pour le nom
    const nomInput = document.getElementById('nom');
    const nomCounter = document.getElementById('nom-counter');
    
    if (nomInput && nomCounter) {
        function updateNomCounter() {
            const length = nomInput.value.length;
            nomCounter.textContent = `${length}/255`;
            
            // Changement de couleur selon la progression
            nomCounter.classList.remove('text-gray-500', 'text-orange-600', 'text-red-600');
            if (length > 240) {
                nomCounter.classList.add('text-red-600');
                nomCounter.style.backgroundColor = '#FEE2E2';
                nomCounter.style.borderColor = '#F87171';
            } else if (length > 200) {
                nomCounter.classList.add('text-orange-600');
                nomCounter.style.backgroundColor = '#FED7AA';
                nomCounter.style.borderColor = '#FB923C';
            } else {
                nomCounter.classList.add('text-gray-500');
                nomCounter.style.backgroundColor = 'white';
                nomCounter.style.borderColor = '#D1D5DB';
            }
        }
        
        updateNomCounter();
        nomInput.addEventListener('input', updateNomCounter);
        
        // Animation focus sur le champ nom
        nomInput.addEventListener('focus', function() {
            this.parentElement.parentElement.style.transform = 'scale(1.01)';
            this.parentElement.parentElement.style.boxShadow = '0 10px 25px -5px rgba(59, 130, 246, 0.3)';
        });
        
        nomInput.addEventListener('blur', function() {
            this.parentElement.parentElement.style.transform = 'scale(1)';
            this.parentElement.parentElement.style.boxShadow = '';
        });
    }
    
    // Compteur de caractères pour la description
    const descriptionInput = document.getElementById('description');
    const descriptionCounter = document.getElementById('description-counter');
    
    if (descriptionInput && descriptionCounter) {
        function updateDescriptionCounter() {
            const length = descriptionInput.value.length;
            descriptionCounter.textContent = `${length}/1000`;
            
            // Changement de couleur selon la progression
            descriptionCounter.classList.remove('text-gray-600', 'text-orange-600', 'text-red-600');
            if (length > 900) {
                descriptionCounter.classList.add('text-red-600');
                descriptionCounter.style.backgroundColor = '#FEE2E2';
                descriptionCounter.style.borderColor = '#F87171';
            } else if (length > 800) {
                descriptionCounter.classList.add('text-orange-600');
                descriptionCounter.style.backgroundColor = '#FED7AA';
                descriptionCounter.style.borderColor = '#FB923C';
            } else {
                descriptionCounter.classList.add('text-gray-600');
                descriptionCounter.style.backgroundColor = 'white';
                descriptionCounter.style.borderColor = '#D1D5DB';
            }
        }
        
        updateDescriptionCounter();
        descriptionInput.addEventListener('input', updateDescriptionCounter);
        
        // Animation focus sur le champ description
        descriptionInput.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.01)';
            this.parentElement.style.boxShadow = '0 10px 25px -5px rgba(34, 197, 94, 0.3)';
        });
        
        descriptionInput.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
            this.parentElement.style.boxShadow = '';
        });
    }
    
    // Animation sur le bouton de soumission
    const submitButton = document.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        submitButton.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    }
});
</script>

<style>
.color-preset {
    transition: all 0.2s ease-in-out;
}

.color-preset:hover {
    transform: scale(1.1);
}

/* Animation pour les champs en focus */
.form-input:focus {
    transform: translateY(-1px);
}

/* Animation pour les erreurs */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.border-red-300 {
    animation: shake 0.5s ease-in-out;
}
</style>
