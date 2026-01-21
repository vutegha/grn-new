@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">newsletter</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Gestion Newsletter')
@section('subtitle', 'Gérez les abonnés et leurs préférences')

@section('content')
<div class="space-y-6">
    
    <!-- Header avec actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Abonnés Newsletter</h2>
            <p class="text-sm text-gray-600">Gérez les abonnés à votre newsletter et leurs préférences de contenu</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('newsletter.subscribe') }}" 
               target="_blank"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-coral transition-colors duration-200">
                <i class="bi bi-box-arrow-up-right mr-2"></i>
                Page d'inscription
            </a>
            <a href="{{ route('admin.newsletter.export', request()->query()) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-olive hover:bg-olive hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive transition-colors duration-200">
                <i class="bi bi-download mr-2"></i>
                Exporter CSV
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <i class="bi bi-people text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Abonnés</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <i class="bi bi-check-circle text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Actifs</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['actifs'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <i class="bi bi-x-circle text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Inactifs</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['inactifs'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <i class="bi bi-patch-check text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Confirmés</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['confirmes'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" action="{{ route('admin.newsletter.index') }}" class="space-y-4 sm:space-y-0 sm:flex sm:items-end sm:space-x-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    Rechercher
                </label>
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="Email ou nom..."
                           class="block w-full pr-10 border-gray-300 rounded-md focus:ring-coral focus:border-coral sm:text-sm">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="bi bi-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div class="sm:w-48">
                <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">
                    Statut
                </label>
                <select name="statut" id="statut" class="block w-full border-gray-300 rounded-md focus:ring-coral focus:border-coral sm:text-sm">
                    <option value="">Tous les statuts</option>
                    <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actifs</option>
                    <option value="inactif" {{ request('statut') === 'inactif' ? 'selected' : '' }}>Inactifs</option>
                </select>
            </div>

            <div class="flex space-x-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-coral hover:bg-coral-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-coral transition-colors duration-200">
                    <i class="bi bi-funnel mr-2"></i>
                    Filtrer
                </button>
                @if(request()->hasAny(['search', 'statut']))
                    <a href="{{ route('admin.newsletter.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-coral transition-colors duration-200">
                        <i class="bi bi-x mr-2"></i>
                        Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table des abonnés -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                Liste des Abonnés ({{ $newsletters->total() }})
            </h3>
        </div>

        @if($newsletters->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Abonné
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Préférences
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date d'inscription
                            </th>
                            <th class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($newsletters as $newsletter)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-coral-light flex items-center justify-center">
                                                <span class="text-xs font-medium text-coral-dark">
                                                    {{ strtoupper(substr($newsletter->email, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $newsletter->email }}
                                            </div>
                                            @if($newsletter->nom)
                                                <div class="text-sm text-gray-500">{{ $newsletter->nom }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @php
                                            $activePrefs = [];
                                            if($newsletter->preferences && is_array($newsletter->preferences)) {
                                                foreach($newsletter->preferences as $key => $value) {
                                                    if($value === true || $value === 1 || $value === '1') {
                                                        $activePrefs[] = $key;
                                                    }
                                                }
                                            }
                                        @endphp
                                        
                                        @if(count($activePrefs) > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($activePrefs as $pref)
                                                    @if(isset($preferenceTypes[$pref]))
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ $preferenceTypes[$pref] }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                            {{ ucfirst($pref) }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">Aucune préférence</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        <!-- Statut Actif/Inactif -->
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $newsletter->actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <i class="bi {{ $newsletter->actif ? 'bi-check-circle' : 'bi-x-circle' }} mr-1"></i>
                                            {{ $newsletter->actif ? 'Actif' : 'Inactif' }}
                                        </span>
                                        
                                        <!-- Statut Confirmation -->
                                        @if($newsletter->confirme_a)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="bi bi-patch-check mr-1"></i>
                                                Confirmé
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="bi bi-clock mr-1"></i>
                                                En attente
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $newsletter->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <!-- Visualiser -->
                                        <a href="{{ route('admin.newsletter.show', $newsletter) }}" 
                                           class="text-blue-400 hover:text-blue-600 transition-colors duration-200"
                                           title="Visualiser">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <!-- Toggle Actif/Inactif -->
                                        <form method="POST" action="{{ route('admin.newsletter.toggle', $newsletter) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                                    title="{{ $newsletter->actif ? 'Désactiver' : 'Activer' }}">
                                                <i class="bi {{ $newsletter->actif ? 'bi-toggle-on text-green-500' : 'bi-toggle-off' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <!-- Supprimer -->
                                        <form method="POST" action="{{ route('admin.newsletter.destroy', $newsletter) }}" 
                                              class="inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet abonné ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-400 hover:text-red-600 transition-colors duration-200"
                                                    title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $newsletters->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="bi bi-inbox text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun abonné trouvé</h3>
                <p class="text-gray-500 mb-6">
                    @if(request()->hasAny(['search', 'statut']))
                        Aucun abonné ne correspond aux critères de recherche.
                    @else
                        Aucun abonné n'est encore inscrit à la newsletter.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'statut']))
                    <a href="{{ route('admin.newsletter.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-coral hover:bg-coral-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-coral transition-colors duration-200">
                        <i class="bi bi-arrow-left mr-2"></i>
                        Voir tous les abonnés
                    </a>
                @else
                    <a href="{{ route('newsletter.subscribe') }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-coral hover:bg-coral-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-coral transition-colors duration-200">
                        <i class="bi bi-plus mr-2"></i>
                        Page d'inscription
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit du formulaire de recherche après 300ms d'inactivité
    let searchTimeout;
    const searchInput = document.getElementById('search');
    const statutSelect = document.getElementById('statut');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    }
    
    if (statutSelect) {
        statutSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
});
</script>
@endsection
