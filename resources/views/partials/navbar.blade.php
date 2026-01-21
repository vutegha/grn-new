<!-- Top Contact Bar -->
<!-- 3 colonnes alignées horizontalement - visible seulement sur grands écrans -->
 <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="hidden lg:flex items-center justify-between rounded-xl shadow-md">
  <!-- Logo IRI -->
  <div>
    <div class="flex items-center justify-start pl-10">
      <img src="{{ asset('assets/img/logos/iri.png') }}"
           alt="Logo IRI"
           class="h-20 w-auto object-contain" />
    </div>
  </div>
  <!-- Contact Info -->
  <div>
    <div class="flex items-center justify-center text-sm text-slate-700">
      <div class="flex gap-6">
        <div class="flex items-center">
          <i class="fa fa-phone mr-2 opacity-60"></i>
          Tél: +243 (0) 99 240 5948
        </div>
        <div class="flex items-center">
          <a href="mailto:iri@ucbc.org" class="hover:underline flex items-center">
            <i class="fa fa-envelope mr-2 opacity-60"></i>
            Email: iri@ucbc.org
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Logo CI -->
  <div>
    <div class="flex items-center justify-end pr-10">
      <img src="{{ asset('assets/img/logos/cropped-ci-logo.webp') }}"
           alt="Logo CI"
           class="h-10 w-auto object-contain" />
    </div>
  </div>
</div>












<!-- AlpineJS -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- NAVBAR COMPLETE -->
<div class="sticky top-0 z-50 bg-white/80 backdrop-blur-2xl shadow-md" x-data="{ mobileOpen: false, subOpen: false }">
  <nav class="absolute top-0 left-0 right-0 z-30 bg-white/80 backdrop-blur-2xl rounded-blur mx-6 my-4 lg:flex-nowrap lg:justify-start backdrop-saturate-200 shadow-md">
    <!-- Ligne principale -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between">
      <!-- Logo + Titre -->
      <div class="flex items-center space-x-3 py-4 px-4 lg:px-0 lg:py-0">
        <img src="{{ asset('assets/img/logos/ucbc-2.png') }}" alt="Logo UCBC" class="h-12 w-12 object-contain rounded-full">
        <span class="text-sm font-bold text-slate-700 whitespace-nowrap">Centre de Gouvernance des Ressources Naturelles</span>
      </div>

      <!-- Bouton Burger (Mobile Only) -->
      <div class="flex justify-end lg:hidden px-4">
        <button @click="mobileOpen = !mobileOpen" class="text-gray-700 p-3 hover:bg-gray-100 rounded-lg transition">
          <i :class="mobileOpen ? 'fas fa-times' : 'fas fa-bars'" class="text-2xl transition-transform duration-300"></i>
        </button>
      </div>
    </div>

    <!-- Contact Info Mobile (visible uniquement sur petits écrans) -->
    <div class="lg:hidden flex justify-center gap-4 text-xs text-slate-600 py-2 border-t border-gray-100">
      <a href="tel:+243992405948" class="flex items-center hover:text-primary transition">
        <i class="fa fa-phone mr-1"></i> +243 99 240 5948
      </a>
      <a href="mailto:iri@ucbc.org" class="flex items-center hover:text-primary transition">
        <i class="fa fa-envelope mr-1"></i> iri@ucbc.org
      </a>
    </div>

    <div class="flex flex-col lg:flex-row lg:items-center justify-between">
      <!-- Menu principal + CTA (Visible Desktop & Mobile avec x-show) -->
      <div :class="{'block': mobileOpen, 'hidden': !mobileOpen}" class="w-full lg:flex lg:items-center lg:justify-between lg:space-x-4 mt-4 lg:mt-0 p-2 lg:p-0 z-40 lg:z-auto shadow lg:shadow-none rounded lg:rounded-none">
        <!-- Menu principal -->
        <ul class="flex flex-col lg:flex-row items-start lg:items-center justify-center space-y-2 lg:space-y-0 lg:space-x-4 text-sm text-slate-700 font-normal w-full lg:w-auto">
          <!-- Accueil -->
          <li class="px-4">
            <a href="{{ url('/') }}" 
               @click="mobileOpen = false"
               class="flex items-center btn-ci transition px-4 py-2 rounded-md w-full lg:w-auto">
              <i class="fa fa-home mr-2"></i> Accueil
            </a>
          </li>

          <!-- À propos -->
          <li class="px-4">
            <a href="{{ url('/about') }}" 
               @click="mobileOpen = false"
               class="flex items-center px-4 py-2 rounded-md btn-ci transition w-full lg:w-auto">
              <i class="fa fa-info-circle mr-2"></i> À propos
            </a>
          </li>

          <!-- Dropdown Programmes -->
          <li class="relative group lg:hover:bg-transparent" x-data="{ dropdownWidth: null }" @mouseenter="if (window.innerWidth >= 1024) { subOpen = true; dropdownWidth = $refs.trigger.offsetWidth * 2; }" @mouseleave="if (window.innerWidth >= 1024) subOpen = false">
            <!-- Bouton déclencheur -->
            <button @click.prevent="subOpen = !subOpen" x-ref="trigger" class="flex items-center justify-between px-4 py-2 rounded-md btn-ci transition w-full lg:w-auto">
              <i class="fas fa-lightbulb mr-2"></i> Programmes
              <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- Menu déroulant (mobile et desktop) -->
            <div x-show="subOpen" x-transition x-cloak
                 :class="window.innerWidth >= 1024 ? 'absolute mt-2' : 'mt-2'"
                 :style="window.innerWidth >= 1024 ? `width: ${dropdownWidth}px` : ''"
                 class="bg-white border border-gray-200 rounded-xl shadow-xl z-50 space-y-2">
              @if(isset($menuServices) && optional($menuServices)->count() > 0)
                @foreach($menuServices as $service)
                  <a href="{{ route('site.service.show', $service->slug) }}" 
                     @click="subOpen = false; mobileOpen = false"
                     class="flex items-center px-4 py-2 rounded-lg text-sm font-medium btn-ci transition">
                    <i class="fas fa-lightbulb mr-2"></i> {{ $service->nom_menu ?? $service->nom }}
                  </a>
                @endforeach
              @else
                <!-- Fallback si aucun service -->
                <div class="px-4 py-2 text-sm text-gray-500">
                  Aucun programme disponible
                </div>
              @endif
            </div>
          </li>

          <!-- Travailler avec nous -->
          <li class="px-4">
            <a href="{{ url('/work-with-us') }}" 
               @click="mobileOpen = false"
               class="flex items-center px-4 py-2 rounded-md btn-ci transition w-full lg:w-auto">
              <i class="fas fa-briefcase mr-2"></i> Travailler avec nous
            </a>
          </li>
        </ul>

        <!-- CTA "Nos Publications" -->
        <div class="mt-4 lg:mt-0 px-4 lg:px-0">
          <a href="{{route('site.publications')}}" 
             @click="mobileOpen = false"
             class="block text-center text-xs font-bold uppercase text-white bg-gradient-to-tl from-gray-900 to-slate-800 px-6 py-3 rounded-3xl hover:opacity-90 transition w-full lg:w-auto">
             Nos Publications
          </a>
        </div>
        
      </div>
    </div>
  </nav>
</div>









