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
           class="group relative inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
            <i class="fas fa-plus-circle mr-3 text-lg group-hover:rotate-90 transition-transform duration-300"></i>
            <span class="relative">Nouveau Partenaire</span>
        </a>
        @endcan
    </div>

    <!-- Cartes statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Partenaires -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center border border-blue-200">
                        <i class="fas fa-handshake text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Total Partenaires</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <!-- Partenaires Actifs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center border border-green-200">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Actifs</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['actifs'] }}</p>
                </div>
            </div>
        </div>

        <!-- Partenaires Publiés -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center border border-yellow-200">
                        <i class="fas fa-eye text-yellow-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Publiés</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['publies'] }}</p>
                </div>
            </div>
        </div>

        <!-- Universités -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center border border-purple-200">
                        <i class="fas fa-graduation-cap text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Universités</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['universites'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des partenaires -->
    @if($partenaires->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-list text-blue-600 mr-2"></i>
                    Liste des Partenaires
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
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
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($partenaires as $partenaire)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10 mr-4">
                                            @if($partenaire->logo)
                                                <img src="{{ $partenaire->logo_url }}" 
                                                     alt="Logo {{ $partenaire->nom }}" 
                                                     class="w-10 h-10 object-contain rounded-lg border border-gray-200">
                                            @else
                                                <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-building text-gray-500 text-sm"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $partenaire->nom }}</div>
                                            @if($partenaire->pays)
                                                <div class="text-xs text-gray-500">{{ $partenaire->pays }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-eye mr-1"></i>
                                            Publié
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $partenaire->published_at->format('d/m/Y') }}
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-eye-slash mr-1"></i>
                                            Non publié
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-3">
                                        @can('view', $partenaire)
                                        <a href="{{ route('admin.partenaires.show', $partenaire) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition-colors duration-200"
                                           title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @endcan
                                        
                                        @can('update', $partenaire)
                                        <a href="{{ route('admin.partenaires.edit', $partenaire) }}" 
                                           class="text-green-600 hover:text-green-800 transition-colors duration-200"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
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
                                                    class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
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
        <!-- État vide -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                <i class="fas fa-handshake text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Aucun partenaire enregistré</h3>
            <p class="text-gray-600 mb-8">Commencez par ajouter votre premier partenaire pour enrichir votre réseau de collaboration.</p>
            @can('create', App\Models\Partenaire::class)
                <a href="{{ route('admin.partenaires.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Ajouter un partenaire
                </a>
            @endcan
        </div>
    @endif
</div>
@endsection
