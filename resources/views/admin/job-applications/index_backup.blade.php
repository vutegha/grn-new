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
                <span class="text-white font-medium text-sm">Candidatures</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', 'Gestion des Candidatures')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Candidatures</h1>
                <p class="text-gray-600 text-lg">Gérez et suivez toutes les candidatures reçues</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.job-applications.export', request()->query()) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-sm">
                    <i class="fas fa-download mr-2"></i>
                    <span class="font-medium">Exporter CSV</span>
                </a>
                <a href="{{ route('admin.job-applications.statistics') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                    <i class="fas fa-chart-line mr-2"></i>
                    <span class="font-medium">Statistiques</span>
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
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ En attente</option>
                            <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>👀 Révisées</option>
                            <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>⭐ Présélectionnées</option>
                            <option value="interviewed" {{ request('status') == 'interviewed' ? 'selected' : '' }}>💬 Entretien passé</option>
                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>✅ Acceptées</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>❌ Rejetées</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Offre d'emploi</label>
                        <select name="job_offer" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200 bg-white">
                            <option value="">Toutes les offres</option>
                            @foreach($jobOffers ?? [] as $offer)
                                @php
                                    $isSelected = false;
                                    if (isset($selectedJobOffer) && $selectedJobOffer) {
                                        $isSelected = $selectedJobOffer->id === $offer->id;
                                    }
                                @endphp
                                <option value="{{ $offer->slug }}" {{ $isSelected ? 'selected' : '' }}>
                                    {{ Str::limit($offer->title, 40) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2 lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Recherche</label>
                        <div class="relative">
                            <input type="text" name="search" 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors duration-200" 
                                   placeholder="Nom, prénom, email..." 
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
                    <a href="{{ route('admin.job-applications.index') }}" class="inline-flex items-center px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-undo mr-2"></i>
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Actions en lot -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden" id="bulk-actions" style="display: none;">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-tasks mr-2"></i>
                Actions en lot
            </h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.job-applications.bulk-review') }}" id="bulk-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Action à appliquer</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 bg-white" required>
                            <option value="">Choisir une action</option>
                            <option value="reviewed">👀 Marquer comme révisées</option>
                            <option value="shortlisted">⭐ Présélectionner</option>
                            <option value="interviewed">💬 Marquer entretien passé</option>
                            <option value="rejected">❌ Rejeter</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-purple-50 border border-purple-200 rounded-lg px-4 py-3">
                            <span class="text-purple-700 font-medium">
                                <span id="selected-count">0</span> candidature(s) sélectionnée(s)
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200 shadow-sm">
                            <i class="fas fa-check mr-2"></i>
                            Appliquer
                        </button>
                        <button type="button" onclick="clearSelection()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Annuler
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des candidatures -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-users mr-3"></i>
                    {{ $applications->total() }} Candidature(s)
                </h3>
                <div class="flex items-center">
                    <input type="checkbox" id="select-all" class="w-4 h-4 text-white bg-white/20 border-white/30 rounded focus:ring-white focus:ring-2 mr-2">
                    <label for="select-all" class="text-white/90 text-sm font-medium cursor-pointer">Tout sélectionner</label>
                </div>
            </div>
        </div>

        @if(optional($applications)->count() > 0)
            <!-- Desktop View -->
            <div class="hidden lg:block">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12"></th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Candidat</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Offre</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut & Score</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fichiers</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($applications as $application)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" name="application_ids[]" value="{{ $application->id }}" 
                                               class="w-4 h-4 text-iri-primary bg-gray-100 border-gray-300 rounded focus:ring-iri-primary focus:ring-2 application-checkbox">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <a href="{{ route('admin.job-applications.show', $application) }}" 
                                                   class="text-lg font-semibold text-gray-900 hover:text-iri-primary transition-colors duration-200 block">
                                                    {{ $application->first_name }} {{ $application->last_name }}
                                                </a>
                                                <div class="mt-1 space-y-1">
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <i class="fas fa-envelope mr-1"></i>
                                                        {{ $application->email }}
                                                    </div>
                                                    @if($application->phone)
                                                        <div class="flex items-center text-sm text-gray-500">
                                                            <i class="fas fa-phone mr-1"></i>
                                                            {{ $application->phone }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <a href="{{ route('admin.job-offers.show', $application->jobOffer->slug) }}" 
                                               class="text-sm font-medium text-gray-900 hover:text-iri-primary transition-colors duration-200">
                                                {{ Str::limit($application->jobOffer->title, 30) }}
                                            </a>
                                            @if($application->jobOffer->location)
                                                <div class="text-xs text-gray-500 mt-1 flex items-center">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    {{ $application->jobOffer->location }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            @php
                                                $statusConfig = [
                                                    'pending' => ['color' => 'yellow', 'icon' => 'fa-clock', 'text' => 'En attente'],
                                                    'reviewed' => ['color' => 'blue', 'icon' => 'fa-eye', 'text' => 'Révisée'],
                                                    'shortlisted' => ['color' => 'purple', 'icon' => 'fa-star', 'text' => 'Présélectionnée'],
                                                    'interviewed' => ['color' => 'indigo', 'icon' => 'fa-comments', 'text' => 'Entretien passé'],
                                                    'accepted' => ['color' => 'green', 'icon' => 'fa-check-circle', 'text' => 'Acceptée'],
                                                    'rejected' => ['color' => 'red', 'icon' => 'fa-times-circle', 'text' => 'Rejetée']
                                                ];
                                                $config = $statusConfig[$application->status] ?? ['color' => 'gray', 'icon' => 'fa-question', 'text' => $application->status];
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                                <i class="fas {{ $config['icon'] }} mr-1"></i>
                                                {{ $config['text'] }}
                                            </span>
                                            @if($application->score)
                                                <div>
                                                    @php
                                                        $scoreColor = $application->score >= 80 ? 'green' : ($application->score >= 60 ? 'yellow' : 'red');
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $scoreColor }}-100 text-{{ $scoreColor }}-800">
                                                        <i class="fas fa-star mr-1"></i>
                                                        {{ $application->score }}/100
                                                    </span>
                                                </div>
                                            @endif
                                            @if($application->reviewed_at)
                                                <div class="text-xs text-gray-500">
                                                    Révisée le {{ $application->reviewed_at->format('d/m/Y') }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900">
                                                {{ $application->created_at->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $application->created_at->format('H:i') }}
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1">
                                                {{ $application->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            @if($application->cv_path)
                                                <a href="{{ route('admin.job-applications.download-cv', $application) }}" 
                                                   class="inline-flex items-center p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200"
                                                   title="Télécharger CV">
                                                    <i class="fas fa-file-pdf text-sm"></i>
                                                </a>
                                            @endif
                                            @if($application->portfolio_path)
                                                <a href="{{ route('admin.job-applications.download-portfolio', $application) }}" 
                                                   class="inline-flex items-center p-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200"
                                                   title="Télécharger Portfolio">
                                                    <i class="fas fa-briefcase text-sm"></i>
                                                </a>
                                            @endif
                                            @if(!$application->cv_path && !$application->portfolio_path)
                                                <span class="text-gray-400 text-sm">Aucun fichier</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('admin.job-applications.show', $application) }}" 
                                               class="inline-flex items-center p-2 text-gray-400 hover:text-iri-primary transition-colors duration-200" 
                                               title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <div class="relative inline-block text-left" x-data="{ open: false }">
                                                <button @click="open = !open" 
                                                        class="inline-flex items-center p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200" 
                                                        title="Changer le statut">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <div x-show="open" 
                                                     @click.away="open = false"
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute right-0 z-10 mt-2 w-56 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                                                    <div class="py-1">
                                                        <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}" class="block">
                                                            @csrf
                                                            <input type="hidden" name="status" value="reviewed">
                                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200">
                                                                <i class="fas fa-eye mr-2 text-blue-500"></i>
                                                                Marquer révisée
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}" class="block">
                                                            @csrf
                                                            <input type="hidden" name="status" value="shortlisted">
                                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 transition-colors duration-200">
                                                                <i class="fas fa-star mr-2 text-purple-500"></i>
                                                                Présélectionner
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}" class="block">
                                                            @csrf
                                                            <input type="hidden" name="status" value="interviewed">
                                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 transition-colors duration-200">
                                                                <i class="fas fa-comments mr-2 text-indigo-500"></i>
                                                                Entretien passé
                                                            </button>
                                                        </form>
                                                        <hr class="my-1">
                                                        <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}" class="block">
                                                            @csrf
                                                            <input type="hidden" name="status" value="accepted">
                                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-green-50 transition-colors duration-200">
                                                                <i class="fas fa-check mr-2 text-green-600"></i>
                                                                Accepter
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}" class="block">
                                                            @csrf
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                                                                <i class="fas fa-times mr-2"></i>
                                                                Rejeter
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
                @foreach($applications as $application)
                    <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" name="application_ids[]" value="{{ $application->id }}" 
                                   class="w-4 h-4 text-iri-primary bg-gray-100 border-gray-300 rounded focus:ring-iri-primary focus:ring-2 application-checkbox mt-1">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user text-blue-600 text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('admin.job-applications.show', $application) }}" 
                                           class="text-lg font-semibold text-gray-900 hover:text-iri-primary transition-colors duration-200 block truncate">
                                            {{ $application->first_name }} {{ $application->last_name }}
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div class="space-y-2">
                                        @php
                                            $statusConfig = [
                                                'pending' => ['color' => 'yellow', 'icon' => 'fa-clock', 'text' => 'En attente'],
                                                'reviewed' => ['color' => 'blue', 'icon' => 'fa-eye', 'text' => 'Révisée'],
                                                'shortlisted' => ['color' => 'purple', 'icon' => 'fa-star', 'text' => 'Présélectionnée'],
                                                'interviewed' => ['color' => 'indigo', 'icon' => 'fa-comments', 'text' => 'Entretien passé'],
                                                'accepted' => ['color' => 'green', 'icon' => 'fa-check-circle', 'text' => 'Acceptée'],
                                                'rejected' => ['color' => 'red', 'icon' => 'fa-times-circle', 'text' => 'Rejetée']
                                            ];
                                            $config = $statusConfig[$application->status] ?? ['color' => 'gray', 'icon' => 'fa-question', 'text' => $application->status];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                            <i class="fas {{ $config['icon'] }} mr-1"></i>
                                            {{ $config['text'] }}
                                        </span>
                                        @if($application->score)
                                            @php
                                                $scoreColor = $application->score >= 80 ? 'green' : ($application->score >= 60 ? 'yellow' : 'red');
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $scoreColor }}-100 text-{{ $scoreColor }}-800">
                                                {{ $application->score }}/100
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-right space-y-1">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $application->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $application->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                    <div class="flex items-center space-x-4">
                                        <span class="flex items-center">
                                            <i class="fas fa-envelope mr-1"></i>
                                            {{ Str::limit($application->email, 20) }}
                                        </span>
                                        @if($application->phone)
                                            <span class="flex items-center">
                                                <i class="fas fa-phone mr-1"></i>
                                                {{ $application->phone }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="text-sm text-gray-600 mb-3">
                                    <a href="{{ route('admin.job-offers.show', $application->jobOffer->slug) }}" 
                                       class="hover:text-iri-primary transition-colors duration-200">
                                        {{ $application->jobOffer->title }}
                                    </a>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        @if($application->cv_path)
                                            <a href="{{ route('admin.job-applications.download-cv', $application) }}" 
                                               class="inline-flex items-center p-1 bg-red-100 text-red-700 rounded text-xs">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                        @if($application->portfolio_path)
                                            <a href="{{ route('admin.job-applications.download-portfolio', $application) }}" 
                                               class="inline-flex items-center p-1 bg-blue-100 text-blue-700 rounded text-xs">
                                                <i class="fas fa-briefcase"></i>
                                            </a>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.job-applications.show', $application) }}" 
                                           class="p-2 text-gray-400 hover:text-iri-primary transition-colors duration-200">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" 
                                                    class="p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <div x-show="open" 
                                                 @click.away="open = false"
                                                 class="absolute right-0 z-10 mt-2 w-48 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                                                <div class="py-1">
                                                    <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="reviewed">
                                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                                            <i class="fas fa-eye mr-2 text-blue-500"></i>Révisée
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="shortlisted">
                                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                                                            <i class="fas fa-star mr-2 text-purple-500"></i>Présélectionner
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="accepted">
                                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-green-50">
                                                            <i class="fas fa-check mr-2 text-green-600"></i>Accepter
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                            <i class="fas fa-times mr-2"></i>Rejeter
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
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
                        Affichage de {{ $applications->firstItem() ?? 0 }} à {{ $applications->lastItem() ?? 0 }} 
                        sur {{ $applications->total() }} résultats
                    </div>
                    <div class="pagination-wrapper">
                        {{ $applications->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune candidature trouvée</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Il n'y a actuellement aucune candidature correspondant à vos critères. 
                    Les candidatures apparaîtront ici une fois soumises.
                </p>
                <div class="space-y-3 sm:space-y-0 sm:space-x-3 sm:flex sm:justify-center">
                    <a href="{{ route('admin.job-offers.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-briefcase mr-2"></i>
                        <span class="font-semibold">Voir les offres</span>
                    </a>
                    <a href="{{ route('admin.job-applications.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-undo mr-2"></i>
                        <span class="font-medium">Réinitialiser les filtres</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

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

    /* Animations personnalisées */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const applicationCheckboxes = document.querySelectorAll('.application-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkForm = document.getElementById('bulk-form');

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
        const count = checkedBoxes.length;
        
        selectedCount.textContent = count;
        
        if (count > 0) {
            bulkActions.style.display = 'block';
            bulkActions.classList.add('animate-fade-in');
            
            // Ajouter les IDs au formulaire
            const existingInputs = bulkForm.querySelectorAll('input[name="application_ids[]"]');
            existingInputs.forEach(input => input.remove());
            
            checkedBoxes.forEach(checkbox => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'application_ids[]';
                hiddenInput.value = checkbox.value;
                bulkForm.appendChild(hiddenInput);
            });
        } else {
            bulkActions.style.display = 'none';
        }
    }

    selectAllCheckbox.addEventListener('change', function() {
        applicationCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    applicationCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(applicationCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(applicationCheckboxes).some(cb => cb.checked);
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
            
            updateBulkActions();
        });
    });

    window.clearSelection = function() {
        applicationCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
        updateBulkActions();
    };

    // Confirmation pour les actions de changement de statut
    const statusForms = document.querySelectorAll('form[action*="update-status"]');
    statusForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const status = this.querySelector('input[name="status"]').value;
            const candidateName = this.closest('tr, .p-6').querySelector('a[href*="show"]').textContent.trim();
            
            const messages = {
                'reviewed': 'marquer comme révisée',
                'shortlisted': 'présélectionner',
                'interviewed': 'marquer l\'entretien comme passé pour',
                'accepted': 'accepter la candidature de',
                'rejected': 'rejeter la candidature de'
            };
            
            if (!confirm(`Êtes-vous sûr de vouloir ${messages[status]} ${candidateName} ?`)) {
                e.preventDefault();
            }
        });
    });

    // Auto-refresh pour les nouvelles candidatures
    let lastRefresh = Date.now();
    setInterval(() => {
        if (document.hidden === false && Date.now() - lastRefresh > 300000) { // 5 minutes
            location.reload();
        }
    }, 60000); // Vérifier toutes les minutes

    // Marquer comme lu lors de la visualisation
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            lastRefresh = Date.now();
        }
    });
});
</script>
@endpush
                    <button type="submit" class="inline-flex items-center px-6 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors duration-200 shadow-sm">
                        <i class="fas fa-search mr-2"></i>
                        Appliquer les filtres
                    </button>
                    <a href="{{ route('admin.job-applications.index') }}" class="inline-flex items-center px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-undo mr-2"></i>
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>
                        <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>PrÃ©sÃ©lectionnÃ©es</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>AcceptÃ©es</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>RejetÃ©es</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Offre d'emploi</label>
                    <select name="job_offer_id" class="form-select">
                        <option value="">Toutes les offres</option>
                        @foreach($jobOffers as $id => $title)
                            <option value="{{ $id }}" {{ request('job_offer_id') == $id ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Recherche</label>
                    <input type="text" name="search" class="form-control" placeholder="Nom, prÃ©nom, email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary me-2">Filtrer</button>
                    <a href="{{ route('admin.job-applications.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Actions en lot -->
    <div class="card mb-4" id="bulk-actions" style="display: none;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.job-applications.bulk-review') }}" id="bulk-form">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Action en lot</label>
                        <select name="status" class="form-select" required>
                            <option value="">Choisir une action</option>
                            <option value="reviewed">Marquer comme rÃ©visÃ©es</option>
                            <option value="shortlisted">PrÃ©sÃ©lectionner</option>
                            <option value="rejected">Rejeter</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <span id="selected-count">0</span> candidature(s) sÃ©lectionnÃ©e(s)
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Appliquer</button>
                        <button type="button" class="btn btn-secondary" onclick="clearSelection()">Annuler</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des candidatures -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $applications->total() }} Candidature(s)</h5>
            <div>
                <input type="checkbox" id="select-all" class="form-check-input me-2">
                <label for="select-all" class="form-check-label">Tout sÃ©lectionner</label>
            </div>
        </div>
        <div class="card-body p-0">
            @if(optional($applications)->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="30"></th>
                                <th>Candidat</th>
                                <th>Offre d'emploi</th>
                                <th>Statut</th>
                                <th>Note</th>
                                <th>Date de candidature</th>
                                <th>Fichiers</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="application_ids[]" value="{{ $application->id }}" 
                                               class="form-check-input application-checkbox">
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('admin.job-applications.show', $application) }}" class="text-decoration-none">
                                                    {{ $application->first_name }} {{ $application->last_name }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">{{ $application->email }}</small>
                                            @if($application->phone)
                                                <br><small class="text-muted">{{ $application->phone }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.job-offers.show', $application->jobOffer) }}" class="text-decoration-none">
                                            {{ $application->jobOffer->title }}
                                        </a>
                                        <br><small class="text-muted">{{ $application->jobOffer->location }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'reviewed' => 'info',
                                                'shortlisted' => 'primary',
                                                'accepted' => 'success',
                                                'rejected' => 'danger'
                                            ];
                                            $statusLabels = [
                                                'pending' => 'En attente',
                                                'reviewed' => 'RÃ©visÃ©e',
                                                'shortlisted' => 'PrÃ©sÃ©lectionnÃ©e',
                                                'accepted' => 'AcceptÃ©e',
                                                'rejected' => 'RejetÃ©e'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$application->status] ?? 'secondary' }}">
                                            {{ $statusLabels[$application->status] ?? $application->status }}
                                        </span>
                                        @if($application->reviewed_at)
                                            <br><small class="text-muted">{{ $application->reviewed_at->format('d/m/Y') }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($application->score)
                                            <span class="badge bg-{{ $application->score >= 80 ? 'success' : ($application->score >= 60 ? 'warning' : 'danger') }}">
                                                {{ $application->score }}/100
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $application->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @if($application->cv_path)
                                                <a href="{{ route('admin.job-applications.download-cv', $application) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="TÃ©lÃ©charger CV">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            @endif
                                            @if($application->portfolio_path)
                                                <a href="{{ route('admin.job-applications.download-portfolio', $application) }}" 
                                                   class="btn btn-sm btn-outline-info" title="TÃ©lÃ©charger Portfolio">
                                                    <i class="fas fa-briefcase"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.job-applications.show', $application) }}" 
                                               class="btn btn-outline-primary" title="Voir dÃ©tails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-info dropdown-toggle" 
                                                        data-bs-toggle="dropdown" title="Changer statut">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="reviewed">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-eye text-info"></i> Marquer rÃ©visÃ©e
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="shortlisted">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-star text-primary"></i> PrÃ©sÃ©lectionner
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="accepted">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-check text-success"></i> Accepter
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-times text-danger"></i> Rejeter
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    {{ $applications->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune candidature trouvÃ©e</h5>
                    <p class="text-muted">Les candidatures apparaÃ®tront ici une fois soumises.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const applicationCheckboxes = document.querySelectorAll('.application-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkForm = document.getElementById('bulk-form');

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
        const count = checkedBoxes.length;
        
        selectedCount.textContent = count;
        
        if (count > 0) {
            bulkActions.style.display = 'block';
            // Ajouter les IDs au formulaire
            const existingInputs = bulkForm.querySelectorAll('input[name="application_ids[]"]');
            existingInputs.forEach(input => input.remove());
            
            checkedBoxes.forEach(checkbox => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'application_ids[]';
                hiddenInput.value = checkbox.value;
                bulkForm.appendChild(hiddenInput);
            });
        } else {
            bulkActions.style.display = 'none';
        }
    }

    selectAllCheckbox.addEventListener('change', function() {
        applicationCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    applicationCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(applicationCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(applicationCheckboxes).some(cb => cb.checked);
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
            
            updateBulkActions();
        });
    });

    window.clearSelection = function() {
        applicationCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
        updateBulkActions();
    };
});
</script>
@endpush

