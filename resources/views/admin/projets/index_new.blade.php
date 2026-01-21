@extends('layouts.admin')

@section('title', 'Gestion des Projets')
@section('subtitle', 'Suivi et modération des projets de recherche')

@section('content')

<x-admin-layout 
    title="Gestion des Projets"
    subtitle="Suivi et modération des projets de recherche"
    create-route="{{ route('admin.projets.create') }}"
    create-label="Nouveau Projet"
    :show-search="true"
    search-placeholder="Rechercher par titre, description..."
    :filters="[
        [
            'name' => 'etat',
            'label' => 'État',
            'type' => 'select',
            'options' => [
                '' => 'Tous les états',
                'en cours' => 'En cours',
                'terminé' => 'Terminé',
                'suspendu' => 'Suspendu'
            ]
        ],
        [
            'name' => 'is_published',
            'label' => 'Publication',
            'type' => 'select',
            'options' => [
                '' => 'Tous',
                '1' => 'Publiés',
                '0' => 'Non publiés'
            ]
        ],
        [
            'name' => 'annee',
            'label' => 'Année',
            'type' => 'select',
            'options' => array_merge(['' => 'Toutes'], array_combine($anneesDisponibles, $anneesDisponibles))
        ],
        [
            'name' => 'created_at_from',
            'label' => 'Créé depuis',
            'type' => 'date'
        ],
        [
            'name' => 'created_at_to',
            'label' => 'Créé jusqu\'au',
            'type' => 'date'
        ]
    ]"
    :stats="[
        [
            'label' => 'Total Projets',
            'value' => $projets->total(),
            'icon' => 'bi-folder',
            'bg_color' => 'bg-coral',
            'icon_color' => 'text-coral'
        ],
        [
            'label' => 'Publiés',
            'value' => $projets->where('is_published', true)->count(),
            'icon' => 'bi-check-circle',
            'bg_color' => 'bg-green-100',
            'icon_color' => 'text-green-600'
        ],
        [
            'label' => 'En Attente',
            'value' => $projets->where('is_published', false)->count(),
            'icon' => 'bi-clock',
            'bg_color' => 'bg-yellow-100',
            'icon_color' => 'text-yellow-600'
        ],
        [
            'label' => 'En Cours',
            'value' => $projets->where('etat', 'en cours')->count(),
            'icon' => 'bi-play-circle',
            'bg_color' => 'bg-blue-100',
            'icon_color' => 'text-blue-600'
        ],
        [
            'label' => 'Terminés',
            'value' => $projets->where('etat', 'terminé')->count(),
            'icon' => 'bi-check-circle-fill',
            'bg_color' => 'bg-emerald-100',
            'icon_color' => 'text-emerald-600'
        ],
        [
            'label' => 'Suspendus',
            'value' => $projets->where('etat', 'suspendu')->count(),
            'icon' => 'bi-pause-circle',
            'bg_color' => 'bg-red-100',
            'icon_color' => 'text-red-600'
        ]
    ]"
>

    <!-- Table des projets -->
    <div class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Informations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">État</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modération</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($projets as $projet)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-coral bg-opacity-10 rounded-full flex items-center justify-center">
                                        <i class="bi bi-folder text-coral"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($projet->titre, 40) }}</div>
                                        <div class="text-sm text-gray-500">
                                            @if($projet->user)
                                                par {{ $projet->user->prenom }} {{ $projet->user->nom }}
                                            @else
                                                Auteur non défini
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div class="font-medium">{{ $projet->date_debut ? $projet->date_debut->format('Y') : 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ Str::limit($projet->description, 50) }}</div>
                                </div>
                                @if($projet->partenaires)
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="bi bi-people text-gray-400"></i> {{ $projet->partenaires }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $etatConfig = [
                                        'en cours' => ['class' => 'bg-blue-100 text-blue-800', 'icon' => 'bi-play-circle'],
                                        'terminé' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'bi-check-circle'],
                                        'suspendu' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'bi-pause-circle'],
                                    ];
                                    $config = $etatConfig[$projet->etat] ?? ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'bi-question-circle'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['class'] }}">
                                    <i class="{{ $config['icon'] }} mr-1"></i>
                                    {{ ucfirst($projet->etat) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    @if($projet->is_published)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="bi bi-eye mr-1"></i>
                                            Publié
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="bi bi-clock mr-1"></i>
                                            En attente
                                        </span>
                                    @endif
                                    
                                    @if(auth()->check() && auth()->user()->canModerate())
                                        <div class="flex space-x-1">
                                            @if(!$projet->is_published)
                                                <form action="{{ route('admin.projets.approve', $projet) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-xs px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition-colors"
                                                            title="Approuver">
                                                        <i class="bi bi-check"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.projets.reject', $projet) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-xs px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors"
                                                            title="Dépublier">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.projets.show', $projet) }}" 
                                       class="text-coral hover:text-coral/80 p-1 rounded-md hover:bg-coral/5 transition-colors duration-200"
                                       title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.projets.edit', $projet) }}" 
                                       class="text-blue-600 hover:text-blue-800 p-1 rounded-md hover:bg-blue-50 transition-colors duration-200"
                                       title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if(auth()->check() && auth()->user()->canModerate())
                                        <form action="{{ route('admin.projets.destroy', $projet) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 p-1 rounded-md hover:bg-red-50 transition-colors duration-200"
                                                    title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-folder text-gray-300 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun projet</h3>
                                    <p class="text-gray-500">Commencez par créer votre premier projet.</p>
                                    <a href="{{ route('admin.projets.create') }}" 
                                       class="mt-4 btn-primary">
                                        <i class="bi bi-plus mr-2"></i>
                                        Créer un projet
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($projets->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $projets->links() }}
        </div>
    @endif

</x-admin-layout>

@endsection
