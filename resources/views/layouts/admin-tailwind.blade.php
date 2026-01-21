<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin IRI-UCBC - @yield('title', 'Dashboard')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}">
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
                        grayish: '#e8e8e8'
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif']
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
            background: #f1f5f9;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-gray-100 font-inter">
    
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-4 bg-gray-800 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-coral to-olive rounded-lg flex items-center justify-center">
                        <i class="bi bi-building text-white text-sm"></i>
                    </div>
                    <div>
                        <h1 class="text-white font-semibold text-lg">IRI-UCBC</h1>
                        <p class="text-gray-400 text-xs">Administration</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- Profile Section -->
            <div class="px-4 py-4 bg-gradient-to-r from-coral to-olive">
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

            <!-- Navigation Menu -->
            <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto scrollbar-thin">
                
                <!-- Menu Principal -->
                <div class="px-3 py-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Principal</p>
                </div>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="bi bi-speedometer2 mr-3 text-lg"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.service.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.service.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="bi bi-gear mr-3 text-lg"></i>
                    Services
                </a>
                
                <a href="{{ route('admin.actualite.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.actualite.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="bi bi-newspaper mr-3 text-lg"></i>
                    Actualités
                </a>
                
                <a href="{{ route('admin.publication.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.publication.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="bi bi-file-earmark-text mr-3 text-lg"></i>
                    Publications
                </a>
                
                <a href="{{ route('admin.projets.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.projets.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="bi bi-diagram-3 mr-3 text-lg"></i>
                    Projets
                </a>

                <!-- Gestion -->
                <div class="px-3 py-2 mt-6">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Gestion</p>
                </div>
                
                <a href="{{ route('admin.auteur.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.auteur.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="bi bi-people mr-3 text-lg"></i>
                    Auteurs
                </a>
                
                <a href="{{ route('admin.categorie.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.categorie.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="bi bi-tags mr-3 text-lg"></i>
                    Catégories
                </a>
                
                <a href="{{ route('admin.media.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.media.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="bi bi-images mr-3 text-lg"></i>
                    Médias
                </a>
                
                <a href="{{ route('admin.rapports.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.rapports.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="bi bi-bar-chart mr-3 text-lg"></i>
                    Rapports
                </a>

                <!-- Communication -->
                <div class="px-3 py-2 mt-6">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Communication</p>
                </div>
                
                <a href="{{ route('admin.contacts.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.contacts.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="bi bi-envelope mr-3 text-lg"></i>
                    Messages
                </a>
                
                <a href="{{ route('admin.newsletter.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ request()->routeIs('admin.newsletter.*') ? 'bg-coral text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="bi bi-mailbox mr-3 text-lg"></i>
                    Newsletter
                </a>

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

            </nav>
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
            
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" 
                                class="text-gray-500 hover:text-gray-600 lg:hidden mr-4">
                            <i class="bi bi-list text-xl"></i>
                        </button>
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                            <p class="text-sm text-gray-500">@yield('subtitle', 'Panneau d\'administration IRI-UCBC')</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ url('/') }}" 
                           target="_blank" 
                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-coral transition-colors duration-200">
                            <i class="bi bi-box-arrow-up-right mr-2"></i>
                            Voir le site
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto scrollbar-thin">
                <div class="p-4 sm:p-6 lg:p-8">
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

                    <!-- Main Content Area -->
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

    <!-- CKEditor Configuration -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('TailwindCSS admin layout chargé, initialisation CKEditor...');
        
        // Configuration CKEditor en français
        const editorConfig = {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', '|',
                'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'blockQuote', 'insertTable', '|',
                'link', '|',
                'undo', 'redo'
            ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraphe', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Titre 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Titre 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Titre 3', class: 'ck-heading_heading3' }
                ]
            ],
            language: 'fr'
        };
        
        // Initialiser CKEditor pour tous les textareas avec la classe 'wysiwyg'
        const textareas = document.querySelectorAll('textarea.wysiwyg');
        console.log(`Trouvé ${textareas.length} textareas WYSIWYG`);
        
        textareas.forEach((textarea, index) => {
            console.log(`Initialisation de CKEditor ${index + 1}...`);
            
            ClassicEditor
                .create(textarea, editorConfig)
                .then(editor => {
                    console.log(`CKEditor ${index + 1} initialisé avec succès`);
                    
                    // Style personnalisé pour l'éditeur
                    editor.editing.view.change(writer => {
                        writer.setStyle('min-height', '150px', editor.editing.view.document.getRoot());
                    });
                    
                    // Synchroniser le contenu avec le textarea
                    editor.model.document.on('change:data', () => {
                        textarea.value = editor.getData();
                    });
                })
                .catch(error => {
                    console.error(`Erreur lors de l'initialisation de CKEditor ${index + 1}:`, error);
                    // En cas d'erreur, on garde le textarea normal
                    textarea.style.display = 'block';
                });
        });
        
        console.log('Configuration CKEditor terminée');
    });
    </script>

    @yield('scripts')
</body>
</html>
