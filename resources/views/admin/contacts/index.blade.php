@extends('layouts.admin')

@section('title', 'Messages de Contact')
@section('subtitle', 'Gestion des messages reçus via le formulaire de contact')

@section('content')

<x-admin-layout 
    title="Messages de Contact"
    subtitle="Gestion des messages reçus via le formulaire de contact"
    :show-search="true"
    search-placeholder="Rechercher par nom, email ou sujet..."
    :filters="[
        [
            'name' => 'statut',
            'label' => 'Statut',
            'type' => 'select',
            'options' => [
                '' => 'Tous les statuts',
                'nouveau' => 'Nouveaux',
                'lu' => 'Lus',
                'traite' => 'Traités',
                'archive' => 'Archivés'
            ]
        ],
        [
            'name' => 'date_debut',
            'label' => 'Date début',
            'type' => 'date'
        ],
        [
            'name' => 'date_fin',
            'label' => 'Date fin',
            'type' => 'date'
        ]
    ]"
    :stats="[
        [
            'label' => 'Total Messages',
            'value' => $contacts->total(),
            'icon' => 'bi-envelope',
            'bg_color' => 'bg-coral',
            'icon_color' => 'text-coral'
        ],
        [
            'label' => 'Nouveaux',
            'value' => \App\Models\Contact::where('statut', 'nouveau')->count(),
            'icon' => 'bi-exclamation-circle',
            'bg_color' => 'bg-red-100',
            'icon_color' => 'text-red-600'
        ],
        [
            'label' => 'En cours',
            'value' => \App\Models\Contact::where('statut', 'lu')->count(),
            'icon' => 'bi-clock',
            'bg_color' => 'bg-orange-100',
            'icon_color' => 'text-orange-600'
        ],
        [
            'label' => 'Traités',
            'value' => \App\Models\Contact::where('statut', 'traite')->count(),
            'icon' => 'bi-check-circle',
            'bg_color' => 'bg-green-100',
            'icon_color' => 'text-green-600'
        ]
    ]"
>

    <!-- Table des contacts -->
    <div class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sujet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($contacts as $contact)
                        <tr class="hover:bg-gray-50 {{ $contact->statut === 'nouveau' ? 'bg-blue-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-coral bg-opacity-10 rounded-full flex items-center justify-center">
                                        <i class="bi bi-person text-coral"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $contact->nom }}</div>
                                        <div class="text-sm text-gray-500">{{ $contact->email }}</div>
                                        @if($contact->telephone)
                                            <div class="text-xs text-gray-400">{{ $contact->telephone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($contact->sujet, 40) }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($contact->message, 60) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusConfig = [
                                        'nouveau' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Nouveau'],
                                        'lu' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'Lu'],
                                        'traite' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Traité'],
                                        'archive' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Archivé']
                                    ];
                                    $status = $statusConfig[$contact->statut] ?? $statusConfig['nouveau'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $status['class'] }}">
                                    {{ $status['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $contact->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $contact->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.contacts.show', $contact) }}" 
                                       class="text-coral hover:text-coral/80 p-1 rounded-md hover:bg-coral/5 transition-colors duration-200"
                                       title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($contact->statut !== 'traite')
                                        <form action="{{ route('admin.contacts.update', $contact) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="statut" value="traite">
                                            <button type="submit" 
                                                    class="text-green-600 hover:text-green-800 p-1 rounded-md hover:bg-green-50 transition-colors duration-200"
                                                    title="Marquer comme traité">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 p-1 rounded-md hover:bg-red-50 transition-colors duration-200"
                                                title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-envelope text-gray-300 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun message</h3>
                                    <p class="text-gray-500">Aucun message de contact n'a été reçu pour le moment.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($contacts->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $contacts->links() }}
        </div>
    @endif

</x-admin-layout>

@endsection
