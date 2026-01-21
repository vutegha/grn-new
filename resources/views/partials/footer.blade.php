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
              <i class="fas fa-flask mr-1"></i> {{ \App\Models\Projet::getTotalProjets() }}+ Projets
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 py-2 rounded-full text-white text-xs font-bold">
              <i class="fas fa-users mr-1"></i> {{ \App\Models\Projet::getTotalBeneficiaires() }}+ Bénéficiaires
            </div>
          </div>
        </div>

        <!-- Bloc 2 : Navigation -->
        <div class="space-y-6">
          <div class="flex items-center space-x-2 mb-4">
            <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center">
              <i class="fas fa-compass text-white text-sm"></i>
            </div>
            <h4 class="text-white font-bold text-lg">Navigation</h4>
          </div>
          
          <nav class="space-y-3">
            <!-- Liens principaux -->
            <a href="{{ route('site.services') }}" class="group flex items-center space-x-3 text-gray-300 hover:text-white transition-all duration-300">
              <div class="w-6 h-6 bg-orange-500/20 rounded-md flex items-center justify-center group-hover:bg-orange-500/40 transition-colors duration-300">
                <i class="fas fa-cogs text-orange-400 text-xs"></i>
              </div>
              <span class="group-hover:translate-x-1 transition-transform duration-300">Nos Domaines d'intérêts</span>
            </a>
            
            <a href="{{ route('site.projets', ['etat' => 'en cours']) }}" class="group flex items-center space-x-3 text-gray-300 hover:text-white transition-all duration-300">
              <div class="w-6 h-6 bg-green-500/20 rounded-md flex items-center justify-center group-hover:bg-green-500/40 transition-colors duration-300">
                <i class="fas fa-project-diagram text-green-400 text-xs"></i>
              </div>
              <span class="group-hover:translate-x-1 transition-transform duration-300">Nos projets en cours</span>
            </a>
            
            <a href="{{ route('site.publications') }}" class="group flex items-center space-x-3 text-gray-300 hover:text-white transition-all duration-300">
              <div class="w-6 h-6 bg-blue-500/20 rounded-md flex items-center justify-center group-hover:bg-blue-500/40 transition-colors duration-300">
                <i class="fas fa-file-alt text-blue-400 text-xs"></i>
              </div>
              <span class="group-hover:translate-x-1 transition-transform duration-300">Accéder à nos ressources</span>
            </a>
            
            <!-- Liens secondaires -->
            <a href="{{ route('site.actualites') }}" class="group flex items-center space-x-3 text-gray-300 hover:text-white transition-all duration-300">
              <div class="w-6 h-6 bg-purple-500/20 rounded-md flex items-center justify-center group-hover:bg-purple-500/40 transition-colors duration-300">
                <i class="fas fa-newspaper text-purple-400 text-xs"></i>
              </div>
              <span class="group-hover:translate-x-1 transition-transform duration-300">Actualités & News</span>
            </a>
          </nav>
          
          <!-- Séparateur -->
          <div class="border-t border-gray-700 my-4"></div>
          
          <!-- Liens rapides -->
          <div class="space-y-2">
            <h5 class="text-gray-400 font-semibold text-sm uppercase tracking-wider mb-3">Liens rapides</h5>
            
            <a href="{{ route('site.work-with-us') }}#emplois" class="group flex items-center space-x-3 text-gray-400 hover:text-white transition-all duration-300">
              <div class="w-5 h-5 bg-yellow-500/20 rounded flex items-center justify-center group-hover:bg-yellow-500/40 transition-colors duration-300">
                <i class="fas fa-briefcase text-yellow-400 text-xs"></i>
              </div>
              <span class="text-sm group-hover:translate-x-1 transition-transform duration-300">Offres d'emploi</span>
            </a>
            
            <a href="{{ route('site.work-with-us') }}" class="group flex items-center space-x-3 text-gray-400 hover:text-white transition-all duration-300">
              <div class="w-5 h-5 bg-indigo-500/20 rounded flex items-center justify-center group-hover:bg-indigo-500/40 transition-colors duration-300">
                <i class="fas fa-handshake text-indigo-400 text-xs"></i>
              </div>
              <span class="text-sm group-hover:translate-x-1 transition-transform duration-300">Collaborer avec nous</span>
            </a>
            
            <a href="{{ route('site.galerie') }}" class="group flex items-center space-x-3 text-gray-400 hover:text-white transition-all duration-300">
              <div class="w-5 h-5 bg-pink-500/20 rounded flex items-center justify-center group-hover:bg-pink-500/40 transition-colors duration-300">
                <i class="fas fa-images text-pink-400 text-xs"></i>
              </div>
              <span class="text-sm group-hover:translate-x-1 transition-transform duration-300">Galerie photo</span>
            </a>
            
            <a href="{{ route('site.contact') }}" class="group flex items-center space-x-3 text-gray-400 hover:text-white transition-all duration-300">
              <div class="w-5 h-5 bg-red-500/20 rounded flex items-center justify-center group-hover:bg-red-500/40 transition-colors duration-300">
                <i class="fas fa-envelope text-red-400 text-xs"></i>
              </div>
              <span class="text-sm group-hover:translate-x-1 transition-transform duration-300">Nous contacter</span>
            </a>
            
            <a href="{{ url('/') }}#aboutus" class="group flex items-center space-x-3 text-gray-400 hover:text-white transition-all duration-300">
              <div class="w-5 h-5 bg-teal-500/20 rounded flex items-center justify-center group-hover:bg-teal-500/40 transition-colors duration-300">
                <i class="fas fa-info-circle text-teal-400 text-xs"></i>
              </div>
              <span class="text-sm group-hover:translate-x-1 transition-transform duration-300">À propos de nous</span>
            </a>
          </div>
        </div>

        <!-- Bloc 3 : Contact & Newsletter -->
        <div class="space-y-6">
          <div class="flex items-center space-x-2 mb-4">
            
            <h4 class="text-white font-bold text-lg">Contact</h4>
          </div>
          
          <!-- Informations de contact -->
          <div class="space-y-4">
            @php
              $bureauPrincipal = \App\Models\ContactInfo::where('type', 'bureau_principal')->actif()->ordered()->first();
            @endphp
            
            @if($bureauPrincipal)
              @if($bureauPrincipal->adresse)
              <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                  <i class="fas fa-map-marker-alt text-white text-xs"></i>
                </div>
                <div>
                  <p class="text-white font-medium">Adresse</p>
                  <p class="text-gray-300 text-sm">{{ $bureauPrincipal->ville }}, {{ $bureauPrincipal->province }}, {{ $bureauPrincipal->pays }}</p>
                </div>
              </div>
              @endif
              
              @if($bureauPrincipal->email)
              <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                  <i class="fas fa-envelope text-white text-xs"></i>
                </div>
                <div>
                  <p class="text-white font-medium">Email</p>
                  <a href="mailto:{{ $bureauPrincipal->email }}" class="text-blue-400 hover:text-blue-300 text-sm transition-colors duration-200">{{ $bureauPrincipal->email }}</a>
                </div>
              </div>
              @endif
              
              @if($bureauPrincipal->telephone)
              <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-teal-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                  <i class="fas fa-phone text-white text-xs"></i>
                </div>
                <div>
                  <p class="text-white font-medium">Téléphone</p>
                  <p class="text-gray-300 text-sm">{{ $bureauPrincipal->telephone }}</p>
                </div>
              </div>
              @endif
            @else
              <!-- Fallback si aucun bureau principal n'est configuré -->
              <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                  <i class="fas fa-map-marker-alt text-white text-xs"></i>
                </div>
                <div>
                  <p class="text-white font-medium">Adresse</p>
                  <p class="text-gray-300 text-sm">Beni, Nord-Kivu, RDC</p>
                </div>
              </div>
              
              <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                  <i class="fas fa-envelope text-white text-xs"></i>
                </div>
                <div>
                  <p class="text-white font-medium">Email</p>
                  <a href="mailto:iri@ucbc.org" class="text-blue-400 hover:text-blue-300 text-sm transition-colors duration-200">iri@ucbc.org</a>
                </div>
              </div>
              
              <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-teal-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                  <i class="fas fa-phone text-white text-xs"></i>
                </div>
                <div>
                  <p class="text-white font-medium">Téléphone</p>
                  <p class="text-gray-300 text-sm">+243 000 000 000</p>
                </div>
              </div>
            @endif
          </div>

          <!-- Newsletter sécurisée avec protection anti-bot -->
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
              
              <!-- Champs anti-bot cachés -->
              <input type="text" name="phone" style="display: none;" tabindex="-1" autocomplete="off">
              <input type="text" name="website" style="display: none;" tabindex="-1" autocomplete="off">
              <input type="hidden" name="start_time" value="{{ time() }}">
              
              <div class="flex">
                <input 
                  type="email" 
                  name="email"
                  placeholder="Votre adresse email" 
                  class="flex-grow px-4 py-3 bg-gray-900/70 border border-gray-600 rounded-l-lg text-white placeholder-gray-400 focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-400/20 transition-all duration-200" 
                  required
                  maxlength="255"
                >
                <button 
                  type="submit" 
                  class="px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold rounded-r-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-400/50 shadow-lg hover:shadow-orange-500/25"
                >
                  <i class="fas fa-paper-plane"></i>
                </button>
              </div>
              
              <!-- Messages de feedback -->
              @if(session('success'))
              <div class="bg-green-500/20 border border-green-500/50 rounded-lg p-3 mt-3">
                <div class="flex items-center space-x-2">
                  <i class="fas fa-check-circle text-green-400 text-sm"></i>
                  <span class="text-green-300 text-xs">{{ session('success') }}</span>
                </div>
              </div>
              @endif
              
              @if(session('info'))
              <div class="bg-blue-500/20 border border-blue-500/50 rounded-lg p-3 mt-3">
                <div class="flex items-center space-x-2">
                  <i class="fas fa-info-circle text-blue-400 text-sm"></i>
                  <span class="text-blue-300 text-xs">{{ session('info') }}</span>
                </div>
              </div>
              @endif
              
              @if(session('error'))
              <div class="bg-red-500/20 border border-red-500/50 rounded-lg p-3 mt-3">
                <div class="flex items-center space-x-2">
                  <i class="fas fa-exclamation-circle text-red-400 text-sm"></i>
                  <span class="text-red-300 text-xs">{{ session('error') }}</span>
                </div>
              </div>
              @endif
              
              @error('email')
              <div class="bg-red-500/20 border border-red-500/50 rounded-lg p-3 mt-3">
                <div class="flex items-center space-x-2">
                  <i class="fas fa-exclamation-triangle text-red-400 text-sm"></i>
                  <span class="text-red-300 text-xs">{{ $message }}</span>
                </div>
              </div>
              @enderror
            </form>
          </div>
        </div>

        <!-- Script pour gérer les messages de feedback -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-masquer les messages de succès après 5 secondes
            const successMessages = document.querySelectorAll('.bg-green-500\\/20');
            successMessages.forEach(function(message) {
                setTimeout(function() {
                    message.style.transition = 'opacity 0.5s ease-out';
                    message.style.opacity = '0';
                    setTimeout(function() {
                        message.remove();
                    }, 500);
                }, 5000);
            });
            
            // Auto-masquer les messages d'info après 6 secondes
            const infoMessages = document.querySelectorAll('.bg-blue-500\\/20');
            infoMessages.forEach(function(message) {
                setTimeout(function() {
                    message.style.transition = 'opacity 0.5s ease-out';
                    message.style.opacity = '0';
                    setTimeout(function() {
                        message.remove();
                    }, 500);
                }, 6000);
            });
        });
        </script>

        <!-- Bloc 4 : Réseaux sociaux -->
        <div class="space-y-6">
          <div class="flex items-center space-x-2 mb-4">
            
            <h4 class="text-white font-bold text-lg">Suivez-nous</h4>
          </div>
          
          <!-- Réseaux sociaux depuis la base de données - Icônes seules -->
          @if(isset($socialLinks) && $socialLinks->count() > 0)
          <div class="flex justify-center space-x-4">
            @foreach($socialLinks as $socialLink)
            <a href="{{ $socialLink->url }}" 
               target="_blank" 
               rel="noopener noreferrer"
               title="{{ $socialLink->name }}"
               class="group w-10 h-10 {{ $socialLink->color }} rounded-full flex items-center justify-center hover:scale-110 hover:shadow-lg transition-all duration-300">
              <i class="{{ $socialLink->icon }} text-white text-lg group-hover:text-xl transition-all duration-300"></i>
            </a>
            @endforeach
          </div>
          @else
          <!-- Liens par défaut si aucun lien social n'est configuré - Icônes seules -->
          <div class="flex justify-center space-x-4">
            <a href="#" 
               title="Facebook"
               class="group w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:scale-110 hover:shadow-lg transition-all duration-300">
              <i class="fab fa-facebook-f text-white text-lg group-hover:text-xl transition-all duration-300"></i>
            </a>
            
            <a href="#" 
               title="Twitter"
               class="group w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center hover:scale-110 hover:shadow-lg transition-all duration-300">
              <i class="fab fa-twitter text-white text-lg group-hover:text-xl transition-all duration-300"></i>
            </a>
            
            <a href="#" 
               title="LinkedIn"
               class="group w-10 h-10 bg-blue-700 rounded-full flex items-center justify-center hover:scale-110 hover:shadow-lg transition-all duration-300">
              <i class="fab fa-linkedin-in text-white text-lg group-hover:text-xl transition-all duration-300"></i>
            </a>
            
            <a href="#" 
               title="YouTube"
               class="group w-10 h-10 bg-red-600 rounded-full flex items-center justify-center hover:scale-110 hover:shadow-lg transition-all duration-300">
              <i class="fab fa-youtube text-white text-lg group-hover:text-xl transition-all duration-300"></i>
            </a>
          </div>
          @endif
          
          <!-- Informations supplémentaires -->
          
        </div>
      </div>
    </div>
  </div>

  <!-- Ligne de séparation et copyright -->
  <div class="w-full border-t border-gray-700/50 mt-16 pt-8">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      <div class="flex flex-col md:flex-row justify-between items-center text-sm">
        <div class="mb-4 md:mb-0">
          <p class="text-gray-400">© {{ date('Y') }} Programme Gouvernance des Ressources Naturelles - GRN. Tous droits réservés.</p>
        </div>
        <div class="flex flex-wrap gap-6 justify-center md:justify-end">
          <a href="{{ route('site.sitemap') }}" 
             class="text-gray-400 hover:text-white transition-colors duration-200 flex items-center relative z-10 cursor-pointer hover:underline"
             role="link"
             aria-label="Accéder au plan du site">
            <i class="fas fa-sitemap mr-1 text-xs"></i>
            Plan du site
          </a>
        </div>
      </div>
    </div>
  </div>
</footer>
