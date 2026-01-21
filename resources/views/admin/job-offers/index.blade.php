@extends('layouts.admin')

@section('breadcrumbs')
<nav class="flex mb-8" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/80 hover:text-white transition-colors duration-200 text-sm font-medium">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/40 text-xs"></i>
                <span class="text-white font-medium text-sm">Offres d'Emploi</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Gestion des Offres d\'Emploi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Offres d'Emploi</h1>
                <p class="text-gray-600 text-lg">Gérez et suivez toutes vos offres d'emploi en un seul endroit</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.job-offers.statistics') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                    <i class="fas fa-chart-line mr-2"></i>
                    <span class="font-medium">Statistiques</span>
                </a>
                <a href="{{ route('admin.job-offers.create') }}" 
                   class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="font-semibold">Nouvelle Offre</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-filter text-iri-primary mr-2"></i>
                Filtres de recherche
            </h3>
        </div>
        <div class="p-6">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200 bg-white">
                            <option value="">Tous les statuts</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✅ Actives</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>📝 Brouillons</option>
                            <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>⏸️ En pause</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>🔒 Fermées</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Source</label>
                        <select name="source" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200 bg-white">
                            <option value="">Toutes les sources</option>
                            <option value="internal" {{ request('source') == 'internal' ? 'selected' : '' }}>🏢 Internes</option>
                            <option value="partner" {{ request('source') == 'partner' ? 'selected' : '' }}>🤝 Partenaires</option>
                        </select>
                    </div>
                    <div class="space-y-2 lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Recherche</label>
                        <div class="relative">
                            <input type="text" name="search" 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200" 
                                   placeholder="Rechercher par titre, description, localisation..." 
                                   value="{{ request('search') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <button type="submit" class="inline-flex items-center px-6 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors duration-200 shadow-sm">
                        <i class="fas fa-search mr-2"></i>
                        Appliquer les filtres
                    </button>
                    <a href="{{ route('admin.job-offers.index') }}" class="inline-flex items-center px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-undo mr-2"></i>
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Job Offers List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-briefcase mr-3"></i>
                    {{ $jobOffers->total() }} Offre(s) d'Emploi
                </h3>
                <div class="text-white/80 text-sm">
                    <i class="fas fa-clock mr-1"></i>
                    Mis à jour le {{ now()->format('d/m/Y à H:i') }}
                </div>
            </div>
        </div>

        @if(optional($jobOffers)->count() > 0)
            <!-- Desktop View -->
            <div class="hidden lg:block">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Offre</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type & Source</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Performance</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Échéances</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($jobOffers as $offer)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-briefcase text-white text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('admin.job-offers.show', $offer->slug) }}" 
                                                       class="text-lg font-semibold text-gray-900 hover:text-iri-primary transition-colors duration-200 truncate">
                                                        {{ $offer->title }}
                                                    </a>
                                                    @if($offer->is_featured)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-star mr-1"></i>Vedette
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="mt-1 flex items-center text-sm text-gray-500 space-x-3">
                                                    @if($offer->location)
                                                        <span class="flex items-center">
                                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                                            {{ $offer->location }}
                                                        </span>
                                                    @endif
                                                    @if($offer->partner_name)
                                                        <span class="flex items-center text-iri-accent font-medium">
                                                            <i class="fas fa-handshake mr-1"></i>
                                                            {{ $offer->partner_name }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst(str_replace('-', ' ', $offer->type)) }}
                                            </span>
                                            <br>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $offer->source == 'internal' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                <i class="fas {{ $offer->source == 'internal' ? 'fa-building' : 'fa-handshake' }} mr-1"></i>
                                                {{ $offer->source == 'internal' ? 'Interne' : 'Partenaire' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusConfig = [
                                                'active' => ['color' => 'green', 'icon' => 'fa-check-circle', 'text' => 'Active'],
                                                'draft' => ['color' => 'gray', 'icon' => 'fa-edit', 'text' => 'Brouillon'],
                                                'paused' => ['color' => 'yellow', 'icon' => 'fa-pause-circle', 'text' => 'En pause'],
                                                'closed' => ['color' => 'red', 'icon' => 'fa-times-circle', 'text' => 'Fermée']
                                            ];
                                            $config = $statusConfig[$offer->status] ?? ['color' => 'gray', 'icon' => 'fa-question', 'text' => $offer->status];
                                        @endphp
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                                <i class="fas {{ $config['icon'] }} mr-1"></i>
                                                {{ $offer->status_label ?? $offer->status }}
                                            </span>
                                            @if($offer->is_expired)
                                                <br>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>Expirée
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            <div class="flex items-center text-sm">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    <i class="fas fa-users mr-1"></i>
                                                    {{ $offer->applications_count }} candidatures
                                                </span>
                                            </div>
                                            @if($offer->views_count > 0)
                                                <div class="text-xs text-gray-500 flex items-center">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    {{ $offer->views_count }} vues
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900">
                                                @if($offer->application_deadline)
                                                    <span class="{{ $offer->is_expired ? 'text-red-600' : 'text-gray-600' }}">
                                                        <i class="fas fa-calendar-alt mr-1"></i>
                                                        {{ $offer->application_deadline->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">Non définie</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                Créée le {{ $offer->created_at ? $offer->created_at->format('d/m/Y') : 'N/A' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('admin.job-offers.show', $offer->slug) }}" 
                                               class="inline-flex items-center p-2 text-gray-400 hover:text-iri-primary transition-colors duration-200" 
                                               title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.job-offers.edit', $offer->slug) }}" 
                                               class="inline-flex items-center p-2 text-gray-400 hover:text-iri-accent transition-colors duration-200" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <div class="relative inline-block text-left" x-data="{ open: false }">
                                                <button @click="open = !open" 
                                                        class="inline-flex items-center p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200" 
                                                        title="Plus d'actions">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div x-show="open" 
                                                     @click.away="open = false"
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute right-0 z-10 mt-2 w-48 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                                                    <div class="py-1">
                                                        <form method="POST" action="{{ route('admin.job-offers.duplicate', $offer->slug) }}" class="block">
                                                            @csrf
                                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                                                <i class="fas fa-copy mr-2 text-gray-400"></i>
                                                                Dupliquer
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.job-offers.toggle-featured', $offer->slug) }}" class="block">
                                                            @csrf
                                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                                                <i class="fas fa-star mr-2 text-gray-400"></i>
                                                                {{ $offer->is_featured ? 'Retirer vedette' : 'Marquer vedette' }}
                                                            </button>
                                                        </form>
                                                        <hr class="my-1">
                                                        <form method="POST" action="{{ route('admin.job-offers.destroy', $offer->slug) }}" 
                                                              class="block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                                                                <i class="fas fa-trash mr-2"></i>
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile View -->
            <div class="lg:hidden divide-y divide-gray-200">
                @foreach($jobOffers as $offer)
                    <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-briefcase text-white text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('admin.job-offers.show', $offer->slug) }}" 
                                           class="text-lg font-semibold text-gray-900 hover:text-iri-primary transition-colors duration-200 block truncate">
                                            {{ $offer->title }}
                                        </a>
                                    </div>
                                    @if($offer->is_featured)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 flex-shrink-0">
                                            <i class="fas fa-star mr-1"></i>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div class="space-y-2">
                                        @php
                                            $statusConfig = [
                                                'active' => ['color' => 'green', 'icon' => 'fa-check-circle', 'text' => 'Active'],
                                                'draft' => ['color' => 'gray', 'icon' => 'fa-edit', 'text' => 'Brouillon'],
                                                'paused' => ['color' => 'yellow', 'icon' => 'fa-pause-circle', 'text' => 'En pause'],
                                                'closed' => ['color' => 'red', 'icon' => 'fa-times-circle', 'text' => 'Fermée']
                                            ];
                                            $config = $statusConfig[$offer->status] ?? ['color' => 'gray', 'icon' => 'fa-question', 'text' => $offer->status];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                            <i class="fas {{ $config['icon'] }} mr-1"></i>
                                            {{ $config['text'] }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $offer->source == 'internal' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            <i class="fas {{ $offer->source == 'internal' ? 'fa-building' : 'fa-handshake' }} mr-1"></i>
                                            {{ $offer->source == 'internal' ? 'Interne' : 'Partenaire' }}
                                        </span>
                                    </div>
                                    <div class="text-right space-y-1">
                                        <div class="text-sm font-medium text-gray-900">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ $offer->applications_count }} candidatures
                                            </span>
                                        </div>
                                        @if($offer->views_count > 0)
                                            <div class="text-xs text-gray-500">{{ $offer->views_count }} vues</div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center space-x-4">
                                        @if($offer->location)
                                            <span class="flex items-center">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $offer->location }}
                                            </span>
                                        @endif
                                        @if($offer->application_deadline)
                                            <span class="flex items-center {{ $offer->is_expired ? 'text-red-600' : '' }}">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                {{ $offer->application_deadline->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($offer->partner_name)
                                    <div class="mt-2 text-sm text-iri-accent font-medium">
                                        <i class="fas fa-handshake mr-1"></i>
                                        {{ $offer->partner_name }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="ml-4 flex items-start space-x-2">
                                <a href="{{ route('admin.job-offers.show', $offer->slug) }}" 
                                   class="p-2 text-gray-400 hover:text-iri-primary transition-colors duration-200">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.job-offers.edit', $offer->slug) }}" 
                                   class="p-2 text-gray-400 hover:text-iri-accent transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div x-show="open" 
                                         @click.away="open = false"
                                         class="absolute right-0 z-10 mt-2 w-48 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                                        <div class="py-1">
                                            <form method="POST" action="{{ route('admin.job-offers.duplicate', $offer->slug) }}">
                                                @csrf
                                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                    <i class="fas fa-copy mr-2"></i>Dupliquer
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.job-offers.toggle-featured', $offer->slug) }}">
                                                @csrf
                                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                    <i class="fas fa-star mr-2"></i>
                                                    {{ $offer->is_featured ? 'Retirer vedette' : 'Marquer vedette' }}
                                                </button>
                                            </form>
                                            <hr class="my-1">
                                            <form method="POST" action="{{ route('admin.job-offers.destroy', $offer->slug) }}" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                    <i class="fas fa-trash mr-2"></i>Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Affichage de {{ $jobOffers->firstItem() ?? 0 }} à {{ $jobOffers->lastItem() ?? 0 }} 
                        sur {{ $jobOffers->total() }} résultats
                    </div>
                    <div class="pagination-wrapper">
                        {{ $jobOffers->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                    <i class="fas fa-briefcase text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune offre d'emploi trouvée</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Il n'y a actuellement aucune offre d'emploi correspondant à vos critères. 
                    Commencez par créer votre première offre.
                </p>
                <div class="space-y-3 sm:space-y-0 sm:space-x-3 sm:flex sm:justify-center">
                    <a href="{{ route('admin.job-offers.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-plus mr-2"></i>
                        <span class="font-semibold">Créer une offre</span>
                    </a>
                    <a href="{{ route('admin.job-offers.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-undo mr-2"></i>
                        <span class="font-medium">Réinitialiser les filtres</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom pagination styles */
    .pagination-wrapper .pagination {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 0.25rem;
    }
    
    .pagination-wrapper .page-item {
        display: flex;
    }
    
    .pagination-wrapper .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.75rem;
        text-decoration: none;
        color: #374151;
        background-color: white;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
        min-width: 2.5rem;
    }
    
    .pagination-wrapper .page-link:hover {
        background-color: #f9fafb;
        border-color: #1e472f;
        color: #1e472f;
    }
    
    .pagination-wrapper .page-item.active .page-link {
        background-color: #1e472f;
        border-color: #1e472f;
        color: white;
    }
    
    .pagination-wrapper .page-item.disabled .page-link {
        color: #9ca3af;
        background-color: #f9fafb;
        border-color: #e5e7eb;
        cursor: not-allowed;
    }
    
    /* Smooth hover animations */
    .hover-lift {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Status badges pulse animation */
    @keyframes pulse-green {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    
    .status-active {
        animation: pulse-green 2s infinite;
    }
    
    /* Responsive table scroll */
    @media (max-width: 1023px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced delete confirmation
    const deleteButtons = document.querySelectorAll('form[onsubmit*="confirm"]');
    deleteButtons.forEach(form => {
        form.addEventListener('submit', function(e) {
            const offerTitle = this.closest('tr, .p-6').querySelector('a[href*="show"]').textContent.trim();
            if (!confirm(`Êtes-vous sûr de vouloir supprimer l'offre "${offerTitle}" ?\n\nCette action est irréversible et supprimera également toutes les candidatures associées.`)) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-refresh page every 5 minutes to keep data fresh
    setTimeout(function() {
        if (document.hidden === false) {
            location.reload();
        }
    }, 300000);
    
    // Add loading states to buttons
    const actionButtons = document.querySelectorAll('form button[type="submit"]');
    actionButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.type === 'submit' && !this.closest('form').onsubmit) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Traitement...';
                this.disabled = true;
            }
        });
    });
    
    // Smooth scroll to top when pagination changes
    const paginationLinks = document.querySelectorAll('.pagination-wrapper a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function() {
            setTimeout(() => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }, 100);
        });
    });
    
    // Enhanced search with debouncing
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const form = this.form;
            searchTimeout = setTimeout(() => {
                if (this.value.length > 2 || this.value.length === 0) {
                    form.submit();
                }
            }, 1000);
        });
    }
});
</script>
@endpush

