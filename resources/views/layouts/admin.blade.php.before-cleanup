<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin GRN-UCBC - @yield('title', 'Dashboard')</title>
    
    <!-- Favicon -->
    @include('partials.favicon')
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- IRI Custom Colors -->
    <link rel="stylesheet" href="{{ asset('css/iri-colors.css') }}">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- TailwindCSS Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        coral: '#ee6751',
                        olive: '#505c10',
                        'light-green': '#dde3da',
                        beige: '#f5f1eb',
                        grayish: '#e8e8e8',
                        // Couleurs IRI Charte Graphique
                        'iri-primary': '#1e472f',
                        'iri-secondary': '#2d5a3f',
                        'iri-accent': '#d2691e',
                        'iri-light': '#f0f9f4',
                        'iri-gold': '#b8860b',
                        'iri-gray': '#64748b',
                        'iri-dark': '#1a1a1a',
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                        'poppins': ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Custom scrollbar */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #374151;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #6b7280;
            border-radius: 3px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
        
        /* Custom scrollbar for dark sidebar */
        .scrollbar-track-gray-800::-webkit-scrollbar-track {
            background: #1f2937;
        }
        
        .scrollbar-thumb-gray-600::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 3px;
        }
        
        .scrollbar-thumb-gray-600:hover::-webkit-scrollbar-thumb,
        .hover\:scrollbar-thumb-gray-500:hover::-webkit-scrollbar-thumb {
            background: #6b7280;
        }
        
        /* Styles pour les miniatures PDF haute résolution */
        .pdf-thumbnail-container {
            position: relative;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .pdf-thumbnail-loading {
            opacity: 0.9;
        }
        
        .pdf-thumbnail-loaded {
            opacity: 1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .pdf-thumbnail-error {
            opacity: 0.7;
        }
        
        .pdf-thumbnail {
            transition: transform 0.2s ease, filter 0.2s ease;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            image-rendering: pixelated;
        }
        
        .pdf-thumbnail-container:hover .pdf-thumbnail {
            transform: scale(1.02);
            filter: brightness(1.05) contrast(1.05);
        }
        
        /* Optimisations pour miniatures haute résolution */
        .pdf-thumbnail-container img {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: -moz-crisp-edges;
            image-rendering: crisp-edges;
            image-rendering: pixelated;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            transform: translateZ(0);
            -webkit-transform: translateZ(0);
        }
    </style>
    
    <!-- PDF.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    
    <!-- PDF Thumbnail Generator HD -->
    <script src="{{ asset('js/pdf-thumbnail-hd.js') }}"></script>
</head>
<body class="bg-gray-100 font-inter">
    
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col"
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-4 bg-gray-800 border-b border-gray-700 flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-coral to-olive rounded-lg flex items-center justify-center">
                        <i class="bi bi-building text-white text-sm"></i>
                    </div>
                    <div>
                        <h1 class="text-white font-semibold text-lg">GRN-UCBC</h1>
                        <p class="text-gray-400 text-xs">Administration</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- Profile Section -->
            <div class="px-4 py-4 bg-gradient-to-r from-coral to-olive border-b border-gray-700 flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-medium truncate">{{ auth()->user()->name ?? 'Administrateur' }}</p>
                        <p class="text-white text-opacity-80 text-sm truncate">{{ auth()->user()->email ?? 'iri@ucbc.org' }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu with improved scrolling -->
            <div class="flex-1 relative overflow-hidden">
                <!-- Gradient fade at top -->
                <div class="absolute top-0 left-0 right-0 h-4 bg-gradient-to-b from-gray-900 to-transparent z-10 pointer-events-none"></div>
                
                <!-- Scrollable Navigation -->
                <nav class="h-full px-2 py-4 space-y-1 overflow-y-auto scrollbar-thin scrollbar-track-gray-800 scrollbar-thumb-gray-600 hover:scrollbar-thumb-gray-500" style="scroll-behavior: smooth;">
                    
                    <!-- Menu Principal -->
                    <div class="px-3 py-2">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Principal</p>
                    </div>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-speedometer2 mr-3 text-lg"></i>
                        Dashboard
                    </a>
                    
                    @can('viewAny', App\Models\Service::class)
                    <a href="{{ route('admin.service.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.service.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-gear mr-3 text-lg"></i>
                        Services
                    </a>
                    @endcan
                    
                    @can('viewAny', App\Models\Actualite::class)
                    <a href="{{ route('admin.actualite.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.actualite.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-newspaper mr-3 text-lg"></i>
                        Actualités
                    </a>
                    @endcan
                    
                    @can('viewAny', App\Models\Publication::class)
                    <a href="{{ route('admin.publication.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.publication.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-file-earmark-text mr-3 text-lg"></i>
                        Publications
                    </a>
                    @endcan
                    
                    @can('viewAny', App\Models\Projet::class)
                    <a href="{{ route('admin.projets.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.projets.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-diagram-3 mr-3 text-lg"></i>
                        Projets
                    </a>
                    @endcan
                    
                    @can('viewAny', App\Models\Evenement::class)
                    <a href="{{ route('admin.evenements.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.evenements.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-calendar-event mr-3 text-lg"></i>
                        Événements
                    </a>
                    @endcan

                    <!-- Gestion -->
                    <div class="px-3 py-2 mt-6">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Gestion</p>
                    </div>
                    
                    @can('viewAny', App\Models\Auteur::class)
                    <a href="{{ route('admin.auteur.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.auteur.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-people mr-3 text-lg"></i>
                        Auteurs
                    </a>
                    @endcan
                    
                    @can('viewAny', App\Models\Categorie::class)
                    <a href="{{ route('admin.categorie.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.categorie.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-tags mr-3 text-lg"></i>
                        Catégories
                    </a>
                    @endcan
                    
                    @can('viewAny', App\Models\Media::class)
                    <a href="{{ route('admin.media.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.media.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-images mr-3 text-lg"></i>
                        Médias
                    </a>
                    @endcan
                    
                    @can('viewAny', App\Models\Partenaire::class)
                    <a href="{{ route('admin.partenaires.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.partenaires.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-handshake mr-3 text-lg"></i>
                        Partenaires
                    </a>
                    @endcan

                    @can('viewAny', App\Models\SocialLink::class)
                    <a href="{{ route('admin.social-links.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.social-links.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-share mr-3 text-lg"></i>
                        Liens sociaux
                    </a>
                    @endcan
                    
                    @can('viewAny', App\Models\Rapport::class)
                    <a href="{{ route('admin.rapports.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.rapports.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-bar-chart mr-3 text-lg"></i>
                        Rapports
                    </a>
                    @endcan

                    <!-- Administration -->
                    <div class="px-3 py-2 mt-6">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Administration</p>
                    </div>
                    
                    @can('manage_users')
                    <a href="{{ route('admin.users.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-people-fill mr-3 text-lg"></i>
                        Utilisateurs & Permissions
                    </a>
                    @endcan

                    <!-- Communication -->
                    <div class="px-3 py-2 mt-6">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Communication</p>
                    </div>
                    
                    @can('viewAny', App\Models\Contact::class)
                    <a href="{{ route('admin.contacts.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.contacts.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-envelope mr-3 text-lg"></i>
                        Messages
                    </a>
                    @endcan
                    
                    @can('manage_newsletter')
                    <a href="{{ route('admin.newsletter.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.newsletter.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-mailbox mr-3 text-lg"></i>
                        Newsletter
                    </a>
                    @endcan
                    
                    @can('manage_email_settings')
                    <a href="{{ route('admin.email-settings.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.email-settings.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-gear mr-3 text-lg"></i>
                        Configuration emails
                    </a>
                    @endcan

                    <!-- Ressources Humaines -->
                    <div class="px-3 py-2 mt-6">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ressources Humaines</p>
                    </div>
                    
                    @can('viewAny', App\Models\JobOffer::class)
                    <a href="{{ route('admin.job-offers.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.job-offers.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-briefcase mr-3 text-lg"></i>
                        Offres d'emploi
                    </a>
                    @endcan
                    
                    @can('viewAny', App\Models\JobApplication::class)
                    <a href="{{ route('admin.job-applications.index') }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.job-applications.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <i class="bi bi-person-check mr-3 text-lg"></i>
                        Candidatures
                    </a>
                    @endcan

                    <!-- Déconnexion -->
                    <div class="px-3 py-2 mt-6">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Compte</p>
                    </div>
                    
                    <form method="POST" action="{{ route('admin.logout') }}" class="m-0">
                        @csrf
                        <button type="submit" 
                                class="group flex items-center w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-800 hover:text-white transition-colors duration-200">
                            <i class="bi bi-box-arrow-right mr-3 text-lg"></i>
                            Se déconnecter
                        </button>
                    </form>

                    <!-- Padding bottom pour assurer que le dernier élément soit visible -->
                    <div class="h-8"></div>

                </nav>
                
                <!-- Gradient fade at bottom -->
                <div class="absolute bottom-0 left-0 right-0 h-4 bg-gradient-to-t from-gray-900 to-transparent z-10 pointer-events-none"></div>
                
                <!-- Scroll indicator (visible when content overflows) -->
                <div class="absolute right-2 top-1/2 transform -translate-y-1/2 opacity-40 hover:opacity-80 transition-opacity duration-200 group">
                    <div class="w-1 h-12 bg-gradient-to-b from-transparent via-coral to-transparent rounded-full group-hover:via-orange-400"></div>
                    <div class="absolute -right-1 top-0 w-3 h-3 bg-coral bg-opacity-60 rounded-full animate-pulse"></div>
                    <div class="absolute -right-1 bottom-0 w-3 h-3 bg-coral bg-opacity-60 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
             style="display: none;">
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-hidden">
            
            <!-- Top Navbar Horizontal -->
            <header class="bg-white shadow-sm border-b border-gray-200" x-data="{ profileOpen: false }">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center space-x-4">
                        <button @click="sidebarOpen = true" 
                                class="text-gray-500 hover:text-gray-600 lg:hidden">
                            <i class="bi bi-list text-xl"></i>
                        </button>
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                            <p class="text-sm text-gray-500">@yield('subtitle', 'Panneau d\'administration GRN-UCBC')</p>
                        </div>
                    </div>

                    <!-- Navbar Links (Desktop) -->
                    <div class="hidden md:flex items-center space-x-4">
                        
                        <!-- Links rapides -->
                        <a href="{{ route('admin.actualite.index') }}" 
                           class="text-gray-600 hover:text-coral px-3 py-2 text-sm font-medium transition-colors duration-200">
                            <i class="bi bi-newspaper mr-1"></i>
                            Actualités
                        </a>
                        <a href="{{ route('admin.publication.index') }}" 
                           class="text-gray-600 hover:text-coral px-3 py-2 text-sm font-medium transition-colors duration-200">
                            <i class="bi bi-file-earmark-text mr-1"></i>
                            Publications
                        </a>
                        <a href="{{ route('admin.newsletter.index') }}" 
                           class="text-gray-600 hover:text-coral px-3 py-2 text-sm font-medium transition-colors duration-200">
                            <i class="bi bi-mailbox mr-1"></i>
                            Newsletter
                        </a>
                        <a href="{{ route('admin.contacts.index') }}" 
                           class="text-gray-600 hover:text-coral px-3 py-2 text-sm font-medium transition-colors duration-200">
                            <i class="bi bi-envelope mr-1"></i>
                            Messages
                        </a>

                        <!-- Voir le site -->
                        <a href="{{ url('/') }}" 
                           target="_blank" 
                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            <i class="bi bi-box-arrow-up-right mr-1"></i>
                            Site
                        </a>

                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button @click="profileOpen = !profileOpen" 
                                    class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                <div class="w-8 h-8 bg-gradient-to-br from-coral to-olive rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                                    </span>
                                </div>
                                <span class="hidden lg:block text-sm font-medium">{{ auth()->user()->name ?? 'Admin' }}</span>
                                <i class="bi bi-chevron-down text-xs" :class="profileOpen ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Profile Dropdown Menu -->
                            <div x-show="profileOpen" 
                                 @click.away="profileOpen = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
                                 style="display: none;">
                                <div class="p-4 border-b border-gray-200">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Administrateur' }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'iri@ucbc.org' }}</p>
                                </div>
                                <div class="py-1">
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="bi bi-person mr-2"></i>
                                        Mon Profil
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="bi bi-gear mr-2"></i>
                                        Paramètres
                                    </a>
                                    <div class="border-t border-gray-200 my-1"></div>
                                    <form method="POST" action="{{ route('admin.logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <i class="bi bi-box-arrow-right mr-2"></i>
                                            Se déconnecter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button @click="profileOpen = !profileOpen" 
                                class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 p-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-coral to-olive rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-semibold">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                                </span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Mobile navbar menu -->
                <div x-show="profileOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="-translate-y-1 opacity-0"
                     x-transition:enter-end="translate-y-0 opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="translate-y-0 opacity-100"
                     x-transition:leave-end="-translate-y-1 opacity-0"
                     class="md:hidden bg-white border-t border-gray-200"
                     style="display: none;">
                    <div class="px-4 py-2 space-y-1">
                        <a href="{{ route('admin.actualite.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="bi bi-newspaper mr-2"></i>Actualités
                        </a>
                        <a href="{{ route('admin.publication.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="bi bi-file-earmark-text mr-2"></i>Publications
                        </a>
                        <a href="{{ route('admin.newsletter.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="bi bi-mailbox mr-2"></i>Newsletter
                        </a>
                        <a href="{{ route('admin.contacts.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="bi bi-envelope mr-2"></i>Messages
                        </a>
                        <div class="border-t border-gray-200 my-2"></div>
                        <a href="{{ url('/') }}" target="_blank" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                            <i class="bi bi-box-arrow-up-right mr-2"></i>Voir le site
                        </a>
                        <form method="POST" action="{{ route('admin.logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md">
                                <i class="bi bi-box-arrow-right mr-2"></i>Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto scrollbar-thin h-[calc(100vh-4rem)]">
                <div class="p-4 sm:p-6 lg:p-8 min-h-full">
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div x-data="{ show: true }" 
                             x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-90"
                             class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-check-circle-fill text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">
                                        {{ session('success') }}
                                    </p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button @click="show = false" 
                                            class="inline-flex text-green-400 hover:text-green-600">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div x-data="{ show: true }" 
                             x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-90"
                             class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-exclamation-circle-fill text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">
                                        {{ session('error') }}
                                    </p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button @click="show = false" 
                                            class="inline-flex text-red-400 hover:text-red-600">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div x-data="{ show: true }" 
                             x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-90"
                             class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-exclamation-triangle-fill text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Erreurs de validation :
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button @click="show = false" 
                                            class="inline-flex text-red-400 hover:text-red-600">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Breadcrumbs -->
                    @hasSection('breadcrumbs')
                        <nav class="flex mb-6" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                <li class="inline-flex items-center">
                                    <a href="{{ route('admin.dashboard') }}" 
                                       class="inline-flex items-center text-iri-gray hover:text-iri-primary transition-colors duration-200">
                                        <i class="fas fa-home mr-2"></i>
                                        Accueil
                                    </a>
                                </li>
                                @yield('breadcrumbs')
                            </ol>
                        </nav>
                    @endif

                    <!-- Main Content Area -->
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>