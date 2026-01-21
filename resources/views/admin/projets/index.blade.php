@extends('layouts.admin')

@section('title', 'Projets de Recherche')
@section('subtitle', 'Gestion complète des projets de recherche et d\'innovation')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- En-tête avec statistiques avancées -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Titre et actions principales -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
                <div class="mb-4 lg:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="h-8 w-8 text-iri-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Projets de Recherche
                    </h1>
                    <p class="text-gray-600 mt-2">Gestion complète des projets de recherche et d'innovation</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button id="toggle-view" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                        <svg id="grid-icon" class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <svg id="list-icon" class="h-4 w-4 mr-2 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        <span id="view-text">Vue grille</span>
                    </button>
                    @can('create_projets')
                        <a href="{{ route('admin.projets.create') }}" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nouveau Projet
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Statistiques en cartes -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4 mb-8">
                <!-- Total Projets -->
                <div class="bg-gradient-to-br from-iri-primary to-iri-secondary rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Total Projets</p>
                            <p class="text-2xl font-bold">{{ $projets->total() }}</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Budget Total -->
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Budget Total</p>
                            <p class="text-2xl font-bold">${{ number_format($budgetTotal, 0) }}</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Publiés -->
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Publiés</p>
                            <p class="text-2xl font-bold">{{ $projets->where('is_published', true)->count() }}</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- En Attente -->
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">En Attente</p>
                            <p class="text-2xl font-bold">{{ $projets->where('is_published', false)->count() }}</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- En Cours -->
                <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">En Cours</p>
                            <p class="text-2xl font-bold">{{ $projets->where('etat', 'en cours')->count() }}</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10V9a2 2 0 012-2h2a2 2 0 012 2v1M9 10v10a2 2 0 002 2h2a2 2 0 002-2V10M9 10H7a2 2 0 00-2 2v8a2 2 0 002 2h2M9 10h6"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Terminés -->
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Terminés</p>
                            <p class="text-2xl font-bold">{{ $projets->where('etat', 'terminé')->count() }}</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Suspendus -->
                <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80">Suspendus</p>
                            <p class="text-2xl font-bold">{{ $projets->where('etat', 'suspendu')->count() }}</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Zone de contenu principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Barre de recherche et filtres avancés -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('admin.projets.index') }}" id="filter-form">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
                    <!-- Recherche textuelle -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   id="search"
                                   value="{{ request('search') }}"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary"
                                   placeholder="Rechercher par nom, description...">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- État -->
                    <div>
                        <label for="etat" class="block text-sm font-medium text-gray-700 mb-2">État</label>
                        <select name="etat" id="etat" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary">
                            <option value="">Tous les états</option>
                            <option value="en cours" {{ request('etat') === 'en cours' ? 'selected' : '' }}>En cours</option>
                            <option value="terminé" {{ request('etat') === 'terminé' ? 'selected' : '' }}>Terminé</option>
                            <option value="suspendu" {{ request('etat') === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                        </select>
                    </div>

                    <!-- Publication -->
                    <div>
                        <label for="is_published" class="block text-sm font-medium text-gray-700 mb-2">Publication</label>
                        <select name="is_published" id="is_published" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary">
                            <option value="">Tous</option>
                            <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Publiés</option>
                            <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Non publiés</option>
                        </select>
                    </div>

                    <!-- Service -->
                    <div>
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">Service</label>
                        <select name="service_id" id="service_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary">
                            <option value="">Tous les services</option>
                            @foreach($services ?? [] as $service)
                                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filtrer
                        </button>
                        <a href="{{ route('admin.projets.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Réinitialiser
                        </a>
                    </div>
                    
                    <div class="text-sm text-gray-600">
                        {{ $projets->total() }} projet(s) trouvé(s)
                    </div>
                </div>
            </form>
        </div>

        <!-- Container pour les résultats AJAX -->
        <div id="projects-container">
            @include('admin.projets.partials.projects-list', ['projets' => $projets])
        </div>

        <!-- Indicateur de chargement -->
        <div id="loading-indicator" class="hidden">
            <div class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-iri-primary"></div>
                <span class="ml-3 text-gray-600">Recherche en cours...</span>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour le basculement de vue et recherche AJAX -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments de l'interface
    const toggleBtn = document.getElementById('toggle-view');
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const gridIcon = document.getElementById('grid-icon');
    const listIcon = document.getElementById('list-icon');
    const viewText = document.getElementById('view-text');
    const projectsContainer = document.getElementById('projects-container');
    const loadingIndicator = document.getElementById('loading-indicator');
    const filterForm = document.getElementById('filter-form');
    const searchInput = document.getElementById('search');
    
    let isGridView = true;
    let searchTimeout;
    let currentRequest = null;
    
    // Récupérer la préférence depuis localStorage
    const savedView = localStorage.getItem('projets-view');
    if (savedView === 'list') {
        isGridView = false;
        switchToListView();
    }
    
    // Basculement de vue
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            if (isGridView) {
                switchToListView();
                localStorage.setItem('projets-view', 'list');
            } else {
                switchToGridView();
                localStorage.setItem('projets-view', 'grid');
            }
            isGridView = !isGridView;
        });
    }
    
    function switchToGridView() {
        if (gridView && listView) {
            gridView.classList.remove('hidden');
            listView.classList.add('hidden');
            gridIcon.classList.remove('hidden');
            listIcon.classList.add('hidden');
            viewText.textContent = 'Vue grille';
        }
    }
    
    function switchToListView() {
        if (gridView && listView) {
            gridView.classList.add('hidden');
            listView.classList.remove('hidden');
            gridIcon.classList.add('hidden');
            listIcon.classList.remove('hidden');
            viewText.textContent = 'Vue liste';
        }
    }
    
    // Fonction de recherche AJAX
    function performSearch() {
        // Annuler la requête précédente si elle existe
        if (currentRequest) {
            currentRequest.abort();
        }
        
        // Afficher l'indicateur de chargement
        loadingIndicator.classList.remove('hidden');
        projectsContainer.style.opacity = '0.5';
        
        // Collecter tous les paramètres du formulaire
        const formData = new FormData(filterForm);
        
        // Créer la requête AJAX
        currentRequest = fetch('{{ route("admin.projets.search") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Mettre à jour le conteneur avec les nouveaux résultats
                projectsContainer.innerHTML = data.html;
                
                // Mettre à jour le compteur de résultats
                const resultCount = document.querySelector('.bg-white.rounded-xl .text-sm.text-gray-600');
                if (resultCount) {
                    resultCount.textContent = `${data.total} projet(s) trouvé(s)`;
                }
                
                // Réappliquer les vues en fonction de la préférence
                const newGridView = document.getElementById('grid-view');
                const newListView = document.getElementById('list-view');
                
                if (newGridView && newListView) {
                    if (isGridView) {
                        newGridView.classList.remove('hidden');
                        newListView.classList.add('hidden');
                    } else {
                        newGridView.classList.add('hidden');
                        newListView.classList.remove('hidden');
                    }
                }
                
                // Réattacher les événements de suppression si nécessaire
                attachDeleteEvents();
            } else {
                console.error('Erreur dans la réponse:', data);
            }
        })
        .catch(error => {
            if (error.name !== 'AbortError') {
                console.error('Erreur lors de la recherche:', error);
                // Afficher un message d'erreur à l'utilisateur
                projectsContainer.innerHTML = `
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <div class="text-red-500 mb-4">
                                <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Erreur de recherche</h3>
                            <p class="text-gray-500">Une erreur s'est produite lors de la recherche. Veuillez réessayer.</p>
                        </div>
                    </div>
                `;
            }
        })
        .finally(() => {
            // Masquer l'indicateur de chargement
            loadingIndicator.classList.add('hidden');
            projectsContainer.style.opacity = '1';
            currentRequest = null;
        });
    }
    
    // Fonction pour réattacher les événements de suppression
    function attachDeleteEvents() {
        const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
        deleteForms.forEach(form => {
            form.onsubmit = function() {
                return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');
            };
        });
    }
    
    // Recherche en temps réel
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                performSearch();
            }, 500); // Délai de 500ms pour éviter trop de requêtes
        });
    }
    
    // Auto-submit des filtres
    const filterSelects = document.querySelectorAll('#etat, #is_published, #service_id, #annee, #created_at_from, #created_at_to');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                performSearch();
            }, 200); // Délai plus court pour les filtres
        });
    });
    
    // Gestion de la pagination AJAX
    document.addEventListener('click', function(e) {
        if (e.target.matches('.pagination a')) {
            e.preventDefault();
            const url = e.target.getAttribute('href');
            
            if (url) {
                // Extraire les paramètres de pagination de l'URL
                const urlParams = new URLSearchParams(url.split('?')[1]);
                const page = urlParams.get('page');
                
                if (page) {
                    // Ajouter le paramètre de page au formulaire
                    let pageInput = filterForm.querySelector('input[name="page"]');
                    if (!pageInput) {
                        pageInput = document.createElement('input');
                        pageInput.type = 'hidden';
                        pageInput.name = 'page';
                        filterForm.appendChild(pageInput);
                    }
                    pageInput.value = page;
                    
                    // Effectuer la recherche
                    performSearch();
                }
            }
        }
    });
    
    // Attacher les événements de suppression au chargement initial
    attachDeleteEvents();
});
</script>

@endsection

