@extends('layouts.admin')

@section('title', 'Partenaires')
@section('subtitle', 'Gestion des partenaires GRN-UCBC')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header avec bouton d'action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Gestion des Partenaires</h1>
            <p class="text-gray-600 flex items-center">
                <i class="fas fa-handshake text-blue-500 mr-2"></i>
                {{ $stats['total'] }} partenaire(s) enregistré(s)
            </p>
        </div>
        @can('create', App\Models\Partenaire::class)
        <a href="{{ route('admin.partenaires.create') }}" 
           class="group relative inline-flex items-center px-8 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-xl hover:from-iri-secondary hover:to-iri-primary transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 border border-iri-accent/20">
            <i class="fas fa-plus-circle mr-3 text-lg group-hover:rotate-90 transition-transform duration-300"></i>
            <span class="relative">
                Nouveau Partenaire
                <div class="absolute inset-0 bg-gradient-to-r from-iri-gold/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded"></div>
            </span>
        </a>
        @endcan
    </div>

    <!-- Cartes statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
        <!-- Total Partenaires -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 iri-shine">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-iri-primary/20 rounded-lg flex items-center justify-center border border-iri-primary/30">
                        <i class="fas fa-handshake text-iri-primary text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Total Partenaires</p>
                    <p class="text-2xl font-bold text-iri-primary">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <!-- Partenaires Actifs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 iri-shine">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-iri-accent/20 rounded-lg flex items-center justify-center border border-iri-accent/30">
                        <i class="fas fa-check-circle text-iri-accent text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Actifs</p>
                    <p class="text-2xl font-bold text-iri-accent">{{ $stats['actifs'] }}</p>
                </div>
            </div>
        </div>

        <!-- Partenaires Publiés -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 iri-shine">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-iri-gold/20 rounded-lg flex items-center justify-center border border-iri-gold/30">
                        <i class="fas fa-eye text-iri-gold text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Publiés</p>
                    <p class="text-2xl font-bold text-iri-gold">{{ $stats['publies'] }}</p>
                </div>
            </div>
        </div>

        <!-- Universités -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 iri-shine">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-iri-secondary/20 rounded-lg flex items-center justify-center border border-iri-secondary/30">
                        <i class="fas fa-graduation-cap text-iri-secondary text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Universités</p>
                    <p class="text-2xl font-bold text-iri-secondary">{{ $stats['universites'] }}</p>
                </div>
            </div>
        </div>

        <!-- Organisations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 iri-shine">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center border border-purple-500/30">
                        <i class="fas fa-globe text-purple-500 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Organisations</p>
                    <p class="text-2xl font-bold text-purple-500">{{ $stats['organisations'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center">
                <label class="text-sm font-semibold text-gray-700 mr-3">Filtrer par :</label>
            </div>
            
            <select id="filter-type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                <option value="">Tous les types</option>
                <option value="universite">Universités</option>
                <option value="centre_recherche">Centres de Recherche</option>
                <option value="organisation_internationale">Organisations Internationales</option>
                <option value="ong">ONG</option>
                <option value="entreprise">Entreprises</option>
                <option value="autre">Autre</option>
            </select>
            
            <select id="filter-statut" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                <option value="">Tous les statuts</option>
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
                <option value="en_negociation">En négociation</option>
            </select>
            
            <select id="filter-publication" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                <option value="">Toutes publications</option>
                <option value="1">Publiés</option>
                <option value="0">Non publiés</option>
            </select>
        </div>
    </div>

    <!-- Liste des partenaires -->
    @if($partenaires->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-list text-iri-primary mr-2"></i>
                    Liste des Partenaires
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full" id="partenaires-table">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Partenaire
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Type
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Publication
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Contact
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($partenaires as $partenaire)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 partenaire-row" 
                                data-type="{{ $partenaire->type }}" 
                                data-statut="{{ $partenaire->statut }}" 
                                data-publication="{{ $partenaire->published_at ? '1' : '0' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-12 h-12 mr-4">
                                            @if($partenaire->logo)
                                                <img src="{{ $partenaire->logo_url }}" 
                                                     alt="Logo {{ $partenaire->nom }}" 
                                                     class="w-12 h-12 object-contain rounded-lg border border-gray-200">
                                            @else
                                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-building text-gray-500"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $partenaire->nom }}
                                            </div>
                                            @if($partenaire->pays)
                                                <div class="text-xs text-gray-500 flex items-center mt-1">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    {{ $partenaire->pays }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                        @switch($partenaire->type)
                                            @case('universite') bg-blue-100 text-blue-800 @break
                                            @case('centre_recherche') bg-green-100 text-green-800 @break
                                            @case('organisation_internationale') bg-purple-100 text-purple-800 @break
                                            @case('ong') bg-orange-100 text-orange-800 @break
                                            @case('entreprise') bg-indigo-100 text-indigo-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        {{ $partenaire->type_libelle }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @switch($partenaire->statut)
                                            @case('actif') bg-green-100 text-green-800 @break
                                            @case('inactif') bg-red-100 text-red-800 @break
                                            @case('en_negociation') bg-yellow-100 text-yellow-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        <i class="fas fa-circle mr-1 text-xs
                                            @switch($partenaire->statut)
                                                @case('actif') text-green-500 @break
                                                @case('inactif') text-red-500 @break
                                                @case('en_negociation') text-yellow-500 @break
                                                @default text-gray-500
                                            @endswitch"></i>
                                        {{ $partenaire->statut_libelle }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($partenaire->published_at)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-eye mr-1"></i>
                                            Publié
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $partenaire->published_at->format('d/m/Y') }}
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-eye-slash mr-1"></i>
                                            Non publié
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        @if($partenaire->site_web)
                                            <div class="flex items-center text-iri-primary mb-1">
                                                <i class="fas fa-globe mr-2 text-xs"></i>
                                                <a href="{{ $partenaire->site_web }}" target="_blank" 
                                                   class="hover:underline">Site web</a>
                                            </div>
                                        @endif
                                        @if($partenaire->email_contact)
                                            <div class="flex items-center text-gray-600">
                                                <i class="fas fa-envelope mr-2 text-xs"></i>
                                                <a href="mailto:{{ $partenaire->email_contact }}" 
                                                   class="hover:underline">{{ $partenaire->email_contact }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        @can('view', $partenaire)
                                        <a href="{{ route('admin.partenaires.show', $partenaire) }}" 
                                           class="group relative p-2 text-iri-primary hover:text-iri-secondary transition-colors duration-200">
                                            <i class="fas fa-eye group-hover:scale-110 transition-transform duration-200"></i>
                                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                Voir
                                            </div>
                                        </a>
                                        @endcan
                                        
                                        @can('update', $partenaire)
                                        <a href="{{ route('admin.partenaires.edit', $partenaire) }}" 
                                           class="group relative p-2 text-iri-accent hover:text-iri-gold transition-colors duration-200">
                                            <i class="fas fa-edit group-hover:scale-110 transition-transform duration-200"></i>
                                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                Modifier
                                            </div>
                                        </a>
                                        @endcan
                                        
                                        @can('delete', $partenaire)
                                        <form action="{{ route('admin.partenaires.destroy', $partenaire) }}" 
                                              method="POST" 
                                              class="inline-block"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce partenaire ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="group relative p-2 text-red-600 hover:text-red-800 transition-colors duration-200">
                                                <i class="fas fa-trash group-hover:scale-110 transition-transform duration-200"></i>
                                                <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                    Supprimer
                                                </div>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                <i class="fas fa-handshake text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Aucun partenaire enregistré</h3>
            <p class="text-gray-600 mb-8">Commencez par ajouter votre premier partenaire pour enrichir votre réseau de collaboration.</p>
            @can('create', App\Models\Partenaire::class)
                <a href="{{ route('admin.partenaires.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors duration-200 font-medium">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Ajouter un partenaire
                </a>
            @endcan
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtrage des partenaires
    const filterType = document.getElementById('filter-type');
    const filterStatut = document.getElementById('filter-statut');
    const filterPublication = document.getElementById('filter-publication');
    const rows = document.querySelectorAll('.partenaire-row');

    function filterTable() {
        const typeValue = filterType.value;
        const statutValue = filterStatut.value;
        const publicationValue = filterPublication.value;

        rows.forEach(row => {
            const type = row.getAttribute('data-type');
            const statut = row.getAttribute('data-statut');
            const publication = row.getAttribute('data-publication');

            const typeMatch = !typeValue || type === typeValue;
            const statutMatch = !statutValue || statut === statutValue;
            const publicationMatch = !publicationValue || publication === publicationValue;

            if (typeMatch && statutMatch && publicationMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    filterType.addEventListener('change', filterTable);
    filterStatut.addEventListener('change', filterTable);
    filterPublication.addEventListener('change', filterTable);
});
</script>
@endpush
