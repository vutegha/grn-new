<footer class="w-full bg-gradient-to-br from-gray-900 via-gray-800 to-black text-gray-100 py-16 mt-20 relative overflow-hidden">
  <!-- Effet de particules en arrière-plan -->
  <div class="absolute inset-0 opacity-10">
    <div class="absolute top-10 left-10 w-32 h-32 bg-orange-500 rounded-full blur-3xl"></div>
    <div class="absolute top-40 right-20 w-24 h-24 bg-green-600 rounded-full blur-2xl"></div>
    <div class="absolute bottom-20 left-1/3 w-40 h-40 bg-blue-600 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 right-10 w-28 h-28 bg-purple-600 rounded-full blur-2xl"></div>
  </div>
  
  <div class="relative z-10 w-full px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <!-- Section principale du footer -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

        <!-- Bloc 1 : Logo + Mission -->
        <div class="lg:col-span-1 space-y-6">
          <div class="flex items-center space-x-4 mb-6">
            <div class="relative">
              <img src="{{ asset('assets/img/logos/ucbc-2.png') }}" 
                   alt="Logo UCBC" 
                   class="h-16 w-16 rounded-full border-4 border-orange-400 shadow-xl">
              <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-2 border-gray-900"></div>
            </div>
            <div>
              <h3 class="text-xl font-bold text-white leading-tight">Programme Gouvernance</h3>
              <p class="text-orange-400 font-semibold">des Ressources Naturelles</p>
            </div>
          </div>
          
          <p class="text-gray-300 leading-relaxed text-sm">
            Œuvrant pour la transformation intégrale en RDC à travers la 
            <span class="text-orange-400 font-semibold">Recherche-à-Action</span>, 
            l'innovation et l'engagement communautaire durable.
          </p>
          
          <!-- Stats/Badges -->
          <div class="flex space-x-4 mt-6">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-4 py-2 rounded-full text-white text-xs font-bold">
              <i class="fas fa-flask mr-1"></i> 50+ Projets
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 py-2 rounded-full text-white text-xs font-bold">
              <i class="fas fa-users mr-1"></i> 1000+ Bénéficiaires
            </div>
          </div>
        </div>

        <!-- Bloc 2 : Navigation -->
        <div class="space-y-6">
          <div class="flex items-center space-x-2 mb-4">
            <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
              <i class="fas fa-compass text-white text-sm"></i>
            </div>
            <h4 class="text-white font-bold text-lg">Navigation</h4>
          </div>
          
          <nav class="space-y-3">
            <a href="{{ url('/') }}" class="group flex items-center space-x-3 text-gray-300 hover:text-white transition-all duration-300">
              <i class="fas fa-home w-4 text-orange-400 group-hover:text-orange-300"></i>
              <span class="group-hover:translate-x-1 transition-transform duration-300">Accueil</span>
            </a>
            <a href="{{ url('/about') }}" class="group flex items-center space-x-3 text-gray-300 hover:text-white transition-all duration-300">
              <i class="fas fa-info-circle w-4 text-orange-400 group-hover:text-orange-300"></i>
              <span class="group-hover:translate-x-1 transition-transform duration-300">À propos</span>
            </a>
            <a href="{{ url('/initiatives/programme-1') }}" class="group flex items-center space-x-3 text-gray-300 hover:text-white transition-all duration-300">
              <i class="fas fa-leaf w-4 text-green-400 group-hover:text-green-300"></i>
              <span class="group-hover:translate-x-1 transition-transform duration-300">Gouvernance Ressources Naturelles</span>
            </a>
            <a href="{{ url('/initiatives/programme-1') }}" class="group flex items-center space-x-3 text-gray-300 hover:text-white transition-all duration-300">
              <i class="fas fa-coffee w-4 text-orange-400 group-hover:text-orange-300"></i>
              <span class="group-hover:translate-x-1 transition-transform duration-300">Agribusiness & Coffee/Cocoa Lab</span>
            </a>
            <a href="{{ url('/initiatives/programme-1') }}" class="group flex items-center space-x-3 text-gray-300 hover:text-white transition-all duration-300">
              <i class="fas fa-globe w-4 text-blue-400 group-hover:text-blue-300"></i>
              <span class="group-hover:translate-x-1 transition-transform duration-300">Geolab</span>
            </a>
            <a href="{{ url('/work-with-us') }}" class="group flex items-center space-x-3 text-gray-300 hover:text-white transition-all duration-300">
              <i class="fas fa-briefcase w-4 text-purple-400 group-hover:text-purple-300"></i>
              <span class="group-hover:translate-x-1 transition-transform duration-300">Travailler avec nous</span>
            </a>
            <a href="{{ route('site.galerie') }}" class="group flex items-center space-x-3 text-gray-300 hover:text-white transition-all duration-300">
              <i class="fas fa-images w-4 text-pink-400 group-hover:text-pink-300"></i>
              <span class="group-hover:translate-x-1 transition-transform duration-300">Galerie Photo</span>
            </a>
            <a href="{{ route('site.publications') }}" class="group flex items-center space-x-3 text-gray-300 hover:text-white transition-all duration-300">
              <i class="fas fa-book w-4 text-indigo-400 group-hover:text-indigo-300"></i>
              <span class="group-hover:translate-x-1 transition-transform duration-300">Ressources</span>
            </a>
          </nav>
        </div>

        <!-- Bloc 3 : Contact & Newsletter -->
        <div class="space-y-6">
          <div class="flex items-center space-x-2 mb-4">
            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
              <i class="fas fa-envelope text-white text-sm"></i>
            </div>
            <h4 class="text-white font-bold text-lg">Contact</h4>
          </div>
          
          <!-- Informations de contact -->
          <div class="space-y-4">
            <div class="flex items-start space-x-3 p-3 bg-gray-800/50 rounded-lg border border-gray-700/50 backdrop-blur-sm">
              <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-map-marker-alt text-white text-xs"></i>
              </div>
              <div>
                <p class="text-white font-medium">Adresse</p>
                <p class="text-gray-300 text-sm">Beni, Nord-Kivu, RDC</p>
              </div>
            </div>
            
            <div class="flex items-start space-x-3 p-3 bg-gray-800/50 rounded-lg border border-gray-700/50 backdrop-blur-sm">
              <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-envelope text-white text-xs"></i>
              </div>
              <div>
                <p class="text-white font-medium">Email</p>
                <a href="mailto:iri@ucbc.org" class="text-blue-400 hover:text-blue-300 text-sm transition-colors duration-200">iri@ucbc.org</a>
              </div>
            </div>
            
            <div class="flex items-start space-x-3 p-3 bg-gray-800/50 rounded-lg border border-gray-700/50 backdrop-blur-sm">
              <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-teal-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-phone text-white text-xs"></i>
              </div>
              <div>
                <p class="text-white font-medium">Téléphone</p>
                <p class="text-gray-300 text-sm">+243 000 000 000</p>
              </div>
            </div>
          </div>

          <!-- Newsletter -->
          <div class="bg-gradient-to-r from-gray-800/60 to-gray-700/60 p-4 rounded-xl border border-gray-600/50 backdrop-blur-sm">
            <h5 class="text-white font-semibold mb-3 flex items-center">
              <i class="fas fa-newspaper mr-2 text-orange-400"></i>
              Newsletter
            </h5>
            <p class="text-gray-300 text-sm mb-4">Restez informé de nos dernières actualités et publications</p>
            
            <form action="{{ route('site.newsletter.subscribe') }}" method="POST" class="space-y-3">
              @csrf
              <input type="hidden" name="redirect_url" value="{{ url()->current() }}">
              <input type="hidden" name="preferences[]" value="actualites">
              <input type="hidden" name="preferences[]" value="publications">
              
              <div class="flex">
                <input 
                  type="email" 
                  name="email"
                  placeholder="Votre adresse email" 
                  class="flex-grow px-4 py-3 bg-gray-900/70 border border-gray-600 rounded-l-lg text-white placeholder-gray-400 focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-400/20 transition-all duration-200" 
                  required
                >
                <button 
                  type="submit" 
                  class="px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold rounded-r-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-400/50 shadow-lg hover:shadow-orange-500/25"
                >
                  <i class="fas fa-paper-plane"></i>
                </button>
              </div>
              @error('email')
              <span class="text-red-400 text-xs block">{{ $message }}</span>
              @enderror
            </form>
          </div>
        </div>

        <!-- Bloc 4 : Réseaux sociaux -->
        <div class="space-y-6">
          <div class="flex items-center space-x-2 mb-4">
            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
              <i class="fas fa-share-alt text-white text-sm"></i>
            </div>
            <h4 class="text-white font-bold text-lg">Suivez-nous</h4>
          </div>
          
          <!-- Réseaux sociaux -->
          <div class="grid grid-cols-2 gap-3">
            <a href="#" class="group flex items-center space-x-3 p-3 bg-gray-800/50 rounded-lg border border-gray-700/50 backdrop-blur-sm hover:bg-blue-600/20 hover:border-blue-500/50 transition-all duration-300">
              <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i class="fab fa-facebook-f text-white text-sm"></i>
              </div>
              <span class="text-sm text-gray-300 group-hover:text-white">Facebook</span>
            </a>
            
            <a href="#" class="group flex items-center space-x-3 p-3 bg-gray-800/50 rounded-lg border border-gray-700/50 backdrop-blur-sm hover:bg-blue-400/20 hover:border-blue-400/50 transition-all duration-300">
              <div class="w-8 h-8 bg-blue-400 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i class="fab fa-twitter text-white text-sm"></i>
              </div>
              <span class="text-sm text-gray-300 group-hover:text-white">Twitter</span>
            </a>
            
            <a href="#" class="group flex items-center space-x-3 p-3 bg-gray-800/50 rounded-lg border border-gray-700/50 backdrop-blur-sm hover:bg-blue-700/20 hover:border-blue-700/50 transition-all duration-300">
              <div class="w-8 h-8 bg-blue-700 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i class="fab fa-linkedin-in text-white text-sm"></i>
              </div>
              <span class="text-sm text-gray-300 group-hover:text-white">LinkedIn</span>
            </a>
            
            <a href="#" class="group flex items-center space-x-3 p-3 bg-gray-800/50 rounded-lg border border-gray-700/50 backdrop-blur-sm hover:bg-red-600/20 hover:border-red-600/50 transition-all duration-300">
              <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i class="fab fa-youtube text-white text-sm"></i>
              </div>
              <span class="text-sm text-gray-300 group-hover:text-white">YouTube</span>
            </a>
          </div>
          
          <!-- Informations supplémentaires -->
          <div class="bg-gradient-to-r from-gray-800/60 to-gray-700/60 p-4 rounded-xl border border-gray-600/50 backdrop-blur-sm">
            <h5 class="text-white font-semibold mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-400"></i>
              Informations
            </h5>
            <div class="space-y-2 text-sm">
              <p class="text-gray-300 flex items-center">
                <i class="fas fa-clock mr-2 text-orange-400 w-4"></i>
                Lun - Ven: 8h00 - 17h00
              </p>
              <p class="text-gray-300 flex items-center">
                <i class="fas fa-university mr-2 text-green-400 w-4"></i>
                Université Chrétienne Bilingue du Congo
              </p>
              <p class="text-gray-300 flex items-center">
                <i class="fas fa-certificate mr-2 text-purple-400 w-4"></i>
                Recherche & Innovation
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Ligne de séparation et copyright -->
  <div class="w-full border-t border-gray-700/50 mt-16 pt-8">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      <div class="flex flex-col md:flex-row justify-between items-center text-sm">
        <div class="mb-4 md:mb-0">
          <p class="text-gray-400">© {{ date('Y') }} Centre de Gouvernance des Ressources Naturelles - UCBC. Tous droits réservés.</p>
        </div>
        <div class="flex flex-wrap gap-6 justify-center md:justify-end">
          <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 flex items-center">
            <i class="fas fa-shield-alt mr-1 text-xs"></i>
            Politique de confidentialité
          </a>
          <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 flex items-center">
            <i class="fas fa-gavel mr-1 text-xs"></i>
            Conditions d'utilisation
          </a>
          <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200 flex items-center">
            <i class="fas fa-sitemap mr-1 text-xs"></i>
            Plan du site
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Modals de Newsletter -->
  @if(session('success'))
  <div id="newsletter-success-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 opacity-0 transition-opacity duration-300 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl p-8 mx-4 max-w-md w-full transform scale-95 transition-transform duration-300 border border-gray-200">
      <div class="flex items-center justify-center mb-6">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
          <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
      </div>
      <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Inscription réussie !</h3>
      <p class="text-gray-600 text-center mb-8 leading-relaxed">{{ session('success') }}</p>
      <div class="flex justify-center">
        <button onclick="closeModal()" class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-green-200">
          Parfait !
        </button>
      </div>
      <div class="mt-6">
        <div class="w-full bg-gray-200 rounded-full h-2">
          <div id="progress-bar" class="bg-green-600 h-2 rounded-full transition-all duration-100 ease-linear" style="width: 0%"></div>
        </div>
      </div>
    </div>
  </div>
  @endif
  
  @if(session('error'))
  <div id="newsletter-error-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 opacity-0 transition-opacity duration-300 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl p-8 mx-4 max-w-md w-full transform scale-95 transition-transform duration-300 border border-gray-200">
      <div class="flex items-center justify-center mb-6">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center">
          <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
        </div>
      </div>
      <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Erreur</h3>
      <p class="text-gray-600 text-center mb-8 leading-relaxed">{{ session('error') }}</p>
      <div class="flex justify-center">
        <button onclick="closeErrorModal()" class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-red-200">
          Fermer
        </button>
      </div>
    </div>
  </div>
  @endif
</footer>

<script>
// Script pour gérer les modals de newsletter
document.addEventListener('DOMContentLoaded', function() {
    const successModal = document.getElementById('newsletter-success-modal');
    if (successModal) {
        setTimeout(() => {
            successModal.classList.remove('opacity-0');
            successModal.classList.add('opacity-100');
            successModal.querySelector('div').classList.remove('scale-95');
            successModal.querySelector('div').classList.add('scale-100');
        }, 100);
        
        const progressBar = document.getElementById('progress-bar');
        if (progressBar) {
            let width = 0;
            const interval = setInterval(() => {
                width += 100 / 30;
                progressBar.style.width = width + '%';
                if (width >= 100) {
                    clearInterval(interval);
                }
            }, 100);
        }
        
        setTimeout(() => {
            closeModal();
        }, 3000);
    }
    
    const errorModal = document.getElementById('newsletter-error-modal');
    if (errorModal) {
        setTimeout(() => {
            errorModal.classList.remove('opacity-0');
            errorModal.classList.add('opacity-100');
            errorModal.querySelector('div').classList.remove('scale-95');
            errorModal.querySelector('div').classList.add('scale-100');
        }, 100);
    }
});

function closeModal() {
    const modal = document.getElementById('newsletter-success-modal');
    if (modal) {
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.remove('scale-100');
        modal.querySelector('div').classList.add('scale-95');
        
        setTimeout(() => {
            modal.remove();
            const url = new URL(window.location);
            url.searchParams.delete('success');
            window.history.replaceState({}, document.title, url.toString());
        }, 300);
    }
}

function closeErrorModal() {
    const modal = document.getElementById('newsletter-error-modal');
    if (modal) {
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.remove('scale-100');
        modal.querySelector('div').classList.add('scale-95');
        
        setTimeout(() => {
            modal.remove();
        }, 300);
    }
}

document.addEventListener('click', function(e) {
    if (e.target.id === 'newsletter-success-modal') {
        closeModal();
    }
    if (e.target.id === 'newsletter-error-modal') {
        closeErrorModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
        closeErrorModal();
    }
});
</script>
