<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <form action="{{ $socialLink ? route('admin.social-links.update', $socialLink) : route('admin.social-links.store') }}" 
          method="POST" 
          class="space-y-6">
        @csrf
        @if($socialLink)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Plateforme -->
            <div>
                <label for="platform" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-tag text-iri-primary mr-2"></i>Plateforme *
                </label>
                <select id="platform"
                        name="platform" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-all duration-200 @error('platform') border-red-500 @enderror"
                        required
                        onchange="updatePreview()">
                    <option value="">-- Choisir une plateforme --</option>
                    <option value="facebook" {{ old('platform', $socialLink->platform ?? '') == 'facebook' ? 'selected' : '' }}>
                        Facebook
                    </option>
                    <option value="twitter" {{ old('platform', $socialLink->platform ?? '') == 'twitter' ? 'selected' : '' }}>
                        Twitter
                    </option>
                    <option value="x" {{ old('platform', $socialLink->platform ?? '') == 'x' ? 'selected' : '' }}>
                        X (ex-Twitter)
                    </option>
                    <option value="instagram" {{ old('platform', $socialLink->platform ?? '') == 'instagram' ? 'selected' : '' }}>
                        Instagram
                    </option>
                    <option value="linkedin" {{ old('platform', $socialLink->platform ?? '') == 'linkedin' ? 'selected' : '' }}>
                        LinkedIn
                    </option>
                    <option value="youtube" {{ old('platform', $socialLink->platform ?? '') == 'youtube' ? 'selected' : '' }}>
                        YouTube
                    </option>
                    <option value="tiktok" {{ old('platform', $socialLink->platform ?? '') == 'tiktok' ? 'selected' : '' }}>
                        TikTok
                    </option>
                    <option value="whatsapp" {{ old('platform', $socialLink->platform ?? '') == 'whatsapp' ? 'selected' : '' }}>
                        WhatsApp
                    </option>
                    <option value="telegram" {{ old('platform', $socialLink->platform ?? '') == 'telegram' ? 'selected' : '' }}>
                        Telegram
                    </option>
                    <option value="snapchat" {{ old('platform', $socialLink->platform ?? '') == 'snapchat' ? 'selected' : '' }}>
                        Snapchat
                    </option>
                    <option value="pinterest" {{ old('platform', $socialLink->platform ?? '') == 'pinterest' ? 'selected' : '' }}>
                        Pinterest
                    </option>
                    <option value="reddit" {{ old('platform', $socialLink->platform ?? '') == 'reddit' ? 'selected' : '' }}>
                        Reddit
                    </option>
                    <option value="discord" {{ old('platform', $socialLink->platform ?? '') == 'discord' ? 'selected' : '' }}>
                        Discord
                    </option>
                    <option value="github" {{ old('platform', $socialLink->platform ?? '') == 'github' ? 'selected' : '' }}>
                        GitHub
                    </option>
                    <option value="email" {{ old('platform', $socialLink->platform ?? '') == 'email' ? 'selected' : '' }}>
                        Email
                    </option>
                    <option value="website" {{ old('platform', $socialLink->platform ?? '') == 'website' ? 'selected' : '' }}>
                        Site Web
                    </option>
                    <option value="blog" {{ old('platform', $socialLink->platform ?? '') == 'blog' ? 'selected' : '' }}>
                        Blog
                    </option>
                </select>
                @error('platform')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror

                <!-- Aperçu de l'icône -->
                <div id="icon-preview" class="mt-2 p-3 bg-gray-50 rounded-lg border hidden">
                    <div class="flex items-center">
                        <i id="preview-icon" class="text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Aperçu de l'icône</p>
                            <p id="preview-classes" class="text-xs text-gray-500"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nom d'affichage -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-signature text-iri-secondary mr-2"></i>Nom d'affichage *
                </label>
                <input type="text" 
                       id="name"
                       name="name" 
                       value="{{ old('name', $socialLink->name ?? '') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-secondary focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                       required
                       placeholder="Ex: Notre page Facebook, Suivez-nous sur Twitter...">
                @error('name')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <!-- URL -->
        <div>
            <label for="url" class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-link text-iri-accent mr-2"></i>URL complète *
            </label>
            <input type="url" 
                   id="url"
                   name="url" 
                   value="{{ old('url', $socialLink->url ?? '') }}" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-accent focus:border-transparent transition-all duration-200 @error('url') border-red-500 @enderror"
                   required
                   placeholder="https://...">
            @error('url')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- Ordre d'affichage -->
        <div>
            <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-sort-numeric-down text-purple-500 mr-2"></i>Ordre d'affichage
            </label>
            <input type="number" 
                   id="order"
                   name="order" 
                   value="{{ old('order', $socialLink->order ?? '') }}" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 @error('order') border-red-500 @enderror"
                   min="0"
                   placeholder="0">
            <p class="mt-1 text-xs text-gray-500">
                Laissez vide pour ajouter à la fin
            </p>
            @error('order')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </p>
            @enderror
        </div>

        <!-- État actif -->
        <div class="border-t border-gray-200 pt-6">
            <div class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" 
                       id="is_active" 
                       name="is_active" 
                       value="1"
                       class="w-5 h-5 text-iri-primary border-gray-300 rounded focus:ring-2 focus:ring-iri-primary"
                       {{ old('is_active', $socialLink->is_active ?? true) ? 'checked' : '' }}>
                <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">
                    Activer ce lien social
                </label>
            </div>
            <p class="mt-2 text-xs text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Les liens actifs sont affichés sur le site public.
            </p>
        </div>

        <!-- Boutons d'action -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
            <a href="{{ route('admin.social-links.index') }}" 
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
                    {{ $socialLink ? 'Mettre à jour' : 'Créer le lien' }}
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function updatePreview() {
    const platform = document.getElementById('platform').value;
    const preview = document.getElementById('icon-preview');
    const icon = document.getElementById('preview-icon');
    const classes = document.getElementById('preview-classes');
    
    const platformData = {
        'facebook': { icon: 'fab fa-facebook', color: 'text-blue-600' },
        'twitter': { icon: 'fab fa-twitter', color: 'text-blue-400' },
        'x': { icon: 'fab fa-x-twitter', color: 'text-black' },
        'instagram': { icon: 'fab fa-instagram', color: 'text-pink-500' },
        'linkedin': { icon: 'fab fa-linkedin', color: 'text-blue-700' },
        'youtube': { icon: 'fab fa-youtube', color: 'text-red-600' },
        'tiktok': { icon: 'fab fa-tiktok', color: 'text-black' },
        'whatsapp': { icon: 'fab fa-whatsapp', color: 'text-green-500' },
        'telegram': { icon: 'fab fa-telegram', color: 'text-blue-500' },
        'snapchat': { icon: 'fab fa-snapchat', color: 'text-yellow-400' },
        'pinterest': { icon: 'fab fa-pinterest', color: 'text-red-500' },
        'reddit': { icon: 'fab fa-reddit', color: 'text-orange-600' },
        'discord': { icon: 'fab fa-discord', color: 'text-indigo-600' },
        'github': { icon: 'fab fa-github', color: 'text-gray-800' },
        'email': { icon: 'fas fa-envelope', color: 'text-gray-600' },
        'website': { icon: 'fas fa-globe', color: 'text-iri-primary' },
        'blog': { icon: 'fas fa-blog', color: 'text-iri-secondary' },
    };
    
    if (platform && platformData[platform]) {
        const data = platformData[platform];
        icon.className = data.icon + ' ' + data.color + ' text-2xl mr-3';
        classes.textContent = data.icon + ' ' + data.color;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}

// Initialiser l'aperçu au chargement si une plateforme est déjà sélectionnée
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
});
</script>

        <!-- État actif -->
        <div class="border-t border-gray-200 pt-6">
            <div class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" 
                       id="is_active" 
                       name="is_active" 
                       value="1"
                       class="w-5 h-5 text-iri-primary border-gray-300 rounded focus:ring-2 focus:ring-iri-primary"
                       {{ old('is_active', $socialLink->is_active ?? true) ? 'checked' : '' }}>
                <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">
                    Activer ce lien social
                </label>
            </div>
            <p class="mt-2 text-xs text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Les liens actifs sont affichés sur le site public.
            </p>
        </div>

        <!-- Boutons d'action -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
            <a href="{{ route('admin.social-links.index') }}" 
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
                    {{ $socialLink ? 'Mettre à jour' : 'Créer le lien' }}
                </button>
            </div>
        </div>
    </form>
</div>
