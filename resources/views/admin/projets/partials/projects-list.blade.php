<!-- Vue Grille -->
<div id="grid-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($projets as $projet)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
            <!-- Image du projet -->
            <div class="relative h-48 bg-gradient-to-br from-iri-primary to-iri-secondary overflow-hidden">
                @if($projet->image && Storage::disk('public')->exists($projet->image))
                    <img src="{{ Storage::url($projet->image) }}" 
                         alt="{{ $projet->nom }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-white">
                        <svg class="h-16 w-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                @endif
                
                <!-- Badge d'état -->
                <div class="absolute top-3 left-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($projet->etat === 'en cours') bg-green-100 text-green-800
                        @elseif($projet->etat === 'terminé') bg-blue-100 text-blue-800
                        @else bg-yellow-100 text-yellow-800
                        @endif">
                        {{ ucfirst($projet->etat) }}
                    </span>
                </div>
                
                <!-- Badge de publication -->
                @if($projet->is_published)
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            Publié
                        </span>
                    </div>
                @endif
            </div>

            <!-- Contenu de la carte -->
            <div class="p-6">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-semibold text-gray-900 leading-tight line-clamp-2">
                        {{ $projet->nom }}
                    </h3>
                </div>

                <!-- Service -->
                @if($projet->service)
                    <div class="flex items-center text-sm text-gray-600 mb-3">
                        <svg class="h-4 w-4 mr-2 text-iri-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-2m-2 0H7m14 0V7a2 2 0 00-2-2H7a2 2 0 00-2 2v14"></path>
                        </svg>
                        {{ $projet->service->nom }}
                    </div>
                @endif

                <!-- Description -->
                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                    {{ $projet->resume ?: Str::limit(strip_tags($projet->description), 120) }}
                </p>

                <!-- Informations supplémentaires -->
                <div class="space-y-2 mb-4">
                    @if($projet->date_debut)
                        <div class="flex items-center text-xs text-gray-500">
                            <svg class="h-3 w-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Début : {{ $projet->date_debut->format('d/m/Y') }}
                        </div>
                    @endif
                    
                    @if($projet->budget)
                        <div class="flex items-center text-xs text-gray-500">
                            <svg class="h-3 w-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            Budget : ${{ number_format($projet->budget, 0, ',', ' ') }}
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex space-x-2">
                        @can('view', $projet)
                            <a href="{{ route('admin.projets.show', $projet) }}" 
                               class="inline-flex items-center text-xs font-medium text-iri-primary hover:text-iri-secondary transition-colors">
                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Voir
                            </a>
                        @endcan

                        @can('update', $projet)
                            <a href="{{ route('admin.projets.edit', $projet) }}" 
                               class="inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Modifier
                            </a>
                        @endcan
                    </div>

                    @can('delete', $projet)
                        <form action="{{ route('admin.projets.destroy', $projet) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center text-xs font-medium text-red-600 hover:text-red-800 transition-colors">
                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Supprimer
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun projet trouvé</h3>
                <p class="mt-1 text-sm text-gray-500">Aucun projet ne correspond à vos critères de recherche.</p>
                @can('create', App\Models\Projet::class)
                    <div class="mt-6">
                        <a href="{{ route('admin.projets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-iri-primary hover:bg-iri-secondary">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nouveau projet
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    @endforelse
</div>

<!-- Vue Liste -->
<div id="list-view" class="space-y-4 hidden">
    @forelse($projets as $projet)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start space-x-6">
                    <!-- Image miniature -->
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 rounded-lg overflow-hidden bg-gradient-to-br from-iri-primary to-iri-secondary">
                            @if($projet->image && Storage::disk('public')->exists($projet->image))
                                <img src="{{ Storage::url($projet->image) }}" 
                                     alt="{{ $projet->nom }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white">
                                    <svg class="h-8 w-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contenu principal -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $projet->nom }}</h3>
                                @if($projet->service)
                                    <p class="text-sm text-gray-600 mb-2">{{ $projet->service->nom }}</p>
                                @endif
                            </div>
                            
                            <div class="flex items-center space-x-2 ml-4">
                                <!-- Badge d'état -->
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($projet->etat === 'en cours') bg-green-100 text-green-800
                                    @elseif($projet->etat === 'terminé') bg-blue-100 text-blue-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($projet->etat) }}
                                </span>
                                
                                @if($projet->is_published)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Publié
                                    </span>
                                @endif
                            </div>
                        </div>

                        <p class="text-gray-600 text-sm mb-3">
                            {{ $projet->resume ?: Str::limit(strip_tags($projet->description), 200) }}
                        </p>

                        <!-- Informations supplémentaires -->
                        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 mb-4">
                            @if($projet->date_debut)
                                <div class="flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Début : {{ $projet->date_debut->format('d/m/Y') }}
                                </div>
                            @endif
                            
                            @if($projet->budget)
                                <div class="flex items-center">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    Budget : ${{ number_format($projet->budget, 0, ',', ' ') }}
                                </div>
                            @endif
                            
                            <div class="flex items-center">
                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Créé le {{ $projet->created_at->format('d/m/Y') }}
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-4">
                            @can('view', $projet)
                                <a href="{{ route('admin.projets.show', $projet) }}" 
                                   class="inline-flex items-center text-sm font-medium text-iri-primary hover:text-iri-secondary transition-colors">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Voir détails
                                </a>
                            @endcan

                            @can('update', $projet)
                                <a href="{{ route('admin.projets.edit', $projet) }}" 
                                   class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Modifier
                                </a>
                            @endcan

                            @can('delete', $projet)
                                <form action="{{ route('admin.projets.destroy', $projet) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Supprimer
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun projet trouvé</h3>
            <p class="mt-1 text-sm text-gray-500">Aucun projet ne correspond à vos critères de recherche.</p>
            @can('create', App\Models\Projet::class)
                <div class="mt-6">
                    <a href="{{ route('admin.projets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-iri-primary hover:bg-iri-secondary">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nouveau projet
                    </a>
                </div>
            @endcan
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($projets->hasPages())
    <div class="mt-8 flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Affichage de {{ $projets->firstItem() ?? 0 }} à {{ $projets->lastItem() ?? 0 }} sur {{ $projets->total() }} résultats
        </div>
        <div>
            {{ $projets->appends(request()->query())->links() }}
        </div>
    </div>
@endif
