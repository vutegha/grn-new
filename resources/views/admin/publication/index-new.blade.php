@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('title', 'Publications')
@section('subtitle', 'Gestion des publications GRN-UCBC')

@section('content')

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Publications -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-coral bg-opacity-10 rounded-lg flex items-center justify-center">
                    <i class="bi bi-file-earmark-text text-coral text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $publications->total() }}</p>
            </div>
        </div>
    </div>

    <!-- À la Une -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-star text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">À la Une</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $publications->where('a_la_une', true)->count() }}</p>
            </div>
        </div>
    </div>

    <!-- En Vedette -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-bookmark text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">En Vedette</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $publications->where('en_vedette', true)->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Récentes -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-clock text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Cette semaine</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $publications->where('created_at', '>=', now()->subWeek())->count() }}</p>
            </div>
        </div>
    </div>

</div>

<!-- Composant Table Admin -->
<x-admin-table 
    :title="'Publications'"
    :subtitle="'Gestion des publications GRN-UCBC'"
    :items="$publications"
    :create-route="route('admin.publication.create')"
    :create-label="'Nouvelle Publication'"
    :search-placeholder="'Rechercher par titre...'"
    :filters="[
        [
            'name' => 'auteur',
            'label' => 'Auteur',
            'type' => 'select',
            'options' => $auteurs->pluck('nom', 'id')->toArray(),
            'selected' => request('auteur')
        ],
        [
            'name' => 'categorie',
            'label' => 'Catégorie',
            'type' => 'select',
            'options' => $categories->pluck('nom', 'id')->toArray(),
            'selected' => request('categorie')
        ]
    ]"
    :headers="['Titre', 'Auteur', 'Catégorie', 'Statut', 'Date', 'Actions']"
    :empty-message="'Aucune publication trouvée'"
    :empty-description="'Commencez par créer votre première publication'"
>
    @foreach($publications as $publication)
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                        <i class="bi bi-file-earmark-pdf text-red-600"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($publication->titre, 40) }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($publication->resume, 60) }}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                @if($publication->auteurs && $publication->auteurs->count() > 0)
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8 bg-olive bg-opacity-10 rounded-full flex items-center justify-center">
                            <i class="bi bi-person text-olive text-sm"></i>
                        </div>
                        <div class="ml-2">
                            <div class="text-sm font-medium text-gray-900">{{ $publication->auteurs->first()->nom }}</div>
                            <div class="text-xs text-gray-500">{{ $publication->auteurs->first()->institution ?? 'Institution non renseignée' }}</div>
                        </div>
                    </div>
                @else
                    <span class="text-gray-500">Aucun</span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                @if($publication->categorie)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $publication->categorie->nom }}
                    </span>
                @else
                    <span class="text-gray-500">Aucune</span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex flex-col space-y-1">
                    @if($publication->a_la_une)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="bi bi-star-fill mr-1"></i>
                            À la Une
                        </span>
                    @endif
                    @if($publication->en_vedette)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="bi bi-bookmark-fill mr-1"></i>
                            En Vedette
                        </span>
                    @endif
                    @if(!$publication->a_la_une && !$publication->en_vedette)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Normal
                        </span>
                    @endif
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <div class="flex flex-col">
                    <span class="font-medium">{{ $publication->created_at->format('d/m/Y') }}</span>
                    <span class="text-xs text-gray-400">{{ $publication->created_at->format('H:i') }}</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end space-x-2">
                    <!-- Télécharger PDF -->
                    @if($publication->fichier_pdf)
                        <a href="{{ Storage::url($publication->fichier_pdf) }}" 
                           target="_blank"
                           class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition-colors duration-200"
                           title="Télécharger PDF">
                            <i class="bi bi-download"></i>
                        </a>
                    @endif
                    
                    <!-- Consulter -->
                    <a href="{{ route('admin.publication.show', $publication->id) }}" 
                       class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-50 transition-colors duration-200"
                       title="Consulter">
                        <i class="bi bi-eye"></i>
                    </a>
                    
                    <!-- Modifier -->
                    <a href="{{ route('admin.publication.edit', $publication) }}" 
                       class="text-olive hover:text-olive-dark p-1 rounded-md hover:bg-olive-50 transition-colors duration-200"
                       title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </a>
                    
                    <!-- Supprimer -->
                    <form action="{{ route('admin.publication.destroy', $publication) }}" 
                          method="POST" 
                          class="inline-block"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition-colors duration-200"
                                title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
</x-admin-table>

@endsection

@push('scripts')
<script>
    // Script pour les filtres en temps réel
    document.addEventListener('DOMContentLoaded', function() {
        // Soumission automatique du formulaire de filtre
        const filterForm = document.querySelector('#filter-form');
        if (filterForm) {
            const filterInputs = filterForm.querySelectorAll('select, input[type="search"]');
            filterInputs.forEach(input => {
                input.addEventListener('change', function() {
                    filterForm.submit();
                });
            });
        }
    });
</script>
@endpush
