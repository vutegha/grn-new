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
                <a href="{{ route('admin.job-offers.index') }}" class="text-white/80 hover:text-white transition-colors duration-200 text-sm font-medium">
                    Offres d'Emploi
                </a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/40 text-xs"></i>
                <span class="text-white font-medium text-sm">{{ Str::limit($jobOffer->title, 30) }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('title', $jobOffer->title)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header avec actions -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-4 lg:mb-0">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-12 h-12 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-lg flex items-center justify-center">
                        <i class="fas fa-briefcase text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $jobOffer->title }}</h1>
                        <div class="flex items-center space-x-4 text-gray-600">
                            <span class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $jobOffer->location }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Publié le {{ $jobOffer->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.job-offers.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
                <a href="{{ route('admin.job-offers.edit', $jobOffer->slug) }}" 
                   class="inline-flex items-center px-4 py-2 bg-iri-accent text-white rounded-lg hover:bg-iri-gold transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <a href="{{ route('admin.job-applications.index', ['job_offer' => $jobOffer->slug]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200">
                    <i class="fas fa-users mr-2"></i>
                    Candidatures ({{ $jobOffer->applications_count ?? 0 }})
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Statuts et badges -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-wrap items-center gap-4">
                    @php
                        $statusConfig = [
                            'active' => ['color' => 'green', 'icon' => 'fa-check-circle', 'text' => 'Active'],
                            'draft' => ['color' => 'gray', 'icon' => 'fa-edit', 'text' => 'Brouillon'],
                            'paused' => ['color' => 'yellow', 'icon' => 'fa-pause-circle', 'text' => 'En pause'],
                            'closed' => ['color' => 'red', 'icon' => 'fa-times-circle', 'text' => 'Fermée']
                        ];
                        $config = $statusConfig[$jobOffer->status] ?? ['color' => 'gray', 'icon' => 'fa-question', 'text' => $jobOffer->status];
                    @endphp
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                        <i class="fas {{ $config['icon'] }} mr-2"></i>
                        {{ $config['text'] }}
                    </span>

                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                        {{ ucfirst(str_replace('-', ' ', $jobOffer->type)) }}
                    </span>

                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $jobOffer->source == 'internal' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                        <i class="fas {{ $jobOffer->source == 'internal' ? 'fa-building' : 'fa-handshake' }} mr-2"></i>
                        {{ $jobOffer->source == 'internal' ? 'Interne' : 'Partenaire' }}
                    </span>

                    @if($jobOffer->is_featured)
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-star mr-2"></i>
                            Vedette
                        </span>
                    @endif

                    @if($jobOffer->is_expired)
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Expirée
                        </span>
                    @endif
                </div>
            </div>

            <!-- Description du poste -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-6 py-4">
                    <h3 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-file-alt mr-3"></i>
                        Description du poste
                    </h3>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none">
                        {!! nl2br(e($jobOffer->description)) !!}
                    </div>
                </div>
            </div>

            <!-- Exigences -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-iri-secondary to-iri-primary px-6 py-4">
                    <h3 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-list-check mr-3"></i>
                        Exigences et qualifications
                    </h3>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none">
                        @if(is_array($jobOffer->requirements))
                            <ul class="list-disc list-inside space-y-2">
                                @foreach($jobOffer->requirements as $requirement)
                                    <li class="text-gray-700">{{ $requirement }}</li>
                                @endforeach
                            </ul>
                        @else
                            {!! nl2br(e($jobOffer->requirements)) !!}
                        @endif
                    </div>
                </div>
            </div>

            <!-- Avantages -->
            @if($jobOffer->benefits)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-iri-accent to-iri-gold px-6 py-4">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-gift mr-3"></i>
                            Avantages et bénéfices
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none">
                            {!! nl2br(e($jobOffer->benefits)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Partenaire -->
            @if($jobOffer->source == 'partner' && $jobOffer->partner_name)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-handshake mr-3"></i>
                            Partenaire
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            @if($jobOffer->partner_logo)
                                <img src="{{ Storage::url($jobOffer->partner_logo) }}" 
                                     alt="{{ $jobOffer->partner_name }}"
                                     class="w-16 h-16 object-contain bg-gray-50 rounded-lg p-2">
                            @else
                                <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-building text-green-600 text-xl"></i>
                                </div>
                            @endif
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ $jobOffer->partner_name }}</h4>
                                <p class="text-gray-600">Entreprise partenaire</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistiques -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Statistiques
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            Candidatures
                        </span>
                        <span class="text-2xl font-bold text-indigo-600">{{ $jobOffer->applications_count ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 flex items-center">
                            <i class="fas fa-eye mr-2"></i>
                            Vues
                        </span>
                        <span class="text-2xl font-bold text-indigo-600">{{ $jobOffer->views_count ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 flex items-center">
                            <i class="fas fa-briefcase mr-2"></i>
                            Postes
                        </span>
                        <span class="text-2xl font-bold text-indigo-600">{{ $jobOffer->positions_available }}</span>
                    </div>
                </div>
            </div>

            <!-- Informations générales -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informations
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Type de contrat</label>
                        <p class="text-gray-900 font-medium">{{ ucfirst(str_replace('-', ' ', $jobOffer->type)) }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Localisation</label>
                        <p class="text-gray-900 font-medium">{{ $jobOffer->location }}</p>
                    </div>

                    @if($jobOffer->salary_min || $jobOffer->salary_max)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Rémunération (€/an)</label>
                            <p class="text-gray-900 font-medium">
                                @if($jobOffer->salary_min && $jobOffer->salary_max)
                                    {{ number_format($jobOffer->salary_min, 0, ',', ' ') }} - {{ number_format($jobOffer->salary_max, 0, ',', ' ') }}
                                @elseif($jobOffer->salary_min)
                                    À partir de {{ number_format($jobOffer->salary_min, 0, ',', ' ') }}
                                @else
                                    Jusqu'à {{ number_format($jobOffer->salary_max, 0, ',', ' ') }}
                                @endif
                            </p>
                        </div>
                    @endif

                    <div>
                        <label class="text-sm font-medium text-gray-500">Date limite</label>
                        <p class="text-gray-900 font-medium {{ $jobOffer->is_expired ? 'text-red-600' : '' }}">
                            {{ $jobOffer->application_deadline ? $jobOffer->application_deadline->format('d/m/Y') : 'Non définie' }}
                            @if($jobOffer->is_expired)
                                <span class="text-red-600 text-sm">(Expirée)</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Créée le</label>
                        <p class="text-gray-900">{{ $jobOffer->created_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    @if($jobOffer->updated_at != $jobOffer->created_at)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Modifiée le</label>
                            <p class="text-gray-900">{{ $jobOffer->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-tools mr-2"></i>
                        Actions rapides
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <form method="POST" action="{{ route('admin.job-offers.toggle-featured', $jobOffer->slug) }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition-colors duration-200">
                            <i class="fas fa-star mr-2"></i>
                            {{ $jobOffer->is_featured ? 'Retirer vedette' : 'Marquer vedette' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.job-offers.duplicate', $jobOffer->slug) }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                            <i class="fas fa-copy mr-2"></i>
                            Dupliquer l'offre
                        </button>
                    </form>

                    <div class="border-t pt-3">
                        <form method="POST" action="{{ route('admin.job-offers.destroy', $jobOffer->slug) }}" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ? Cette action supprimera également toutes les candidatures associées.')"
                              class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition-colors duration-200">
                                <i class="fas fa-trash mr-2"></i>
                                Supprimer l'offre
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Options de modération -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Modération
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <!-- Changement de statut -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2 block">Changer le statut</label>
                        <form method="POST" action="{{ route('admin.job-offers.change-status', $jobOffer->slug) }}" class="space-y-2">
                            @csrf
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm" onchange="this.form.submit()">
                                <option value="draft" {{ $jobOffer->status == 'draft' ? 'selected' : '' }}>📝 Brouillon</option>
                                <option value="active" {{ $jobOffer->status == 'active' ? 'selected' : '' }}>✅ Active</option>
                                <option value="paused" {{ $jobOffer->status == 'paused' ? 'selected' : '' }}>⏸️ En pause</option>
                                <option value="closed" {{ $jobOffer->status == 'closed' ? 'selected' : '' }}>🔒 Fermée</option>
                            </select>
                        </form>
                    </div>

                    <!-- Actions de modération -->
                    <div class="border-t pt-3 space-y-2">
                        @if($jobOffer->status == 'draft')
                            <form method="POST" action="{{ route('admin.job-offers.change-status', $jobOffer->slug) }}" class="w-full">
                                @csrf
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition-colors duration-200">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Publier l'offre
                                </button>
                            </form>
                        @endif

                        @if($jobOffer->status == 'active')
                            <form method="POST" action="{{ route('admin.job-offers.change-status', $jobOffer->slug) }}" class="w-full">
                                @csrf
                                <input type="hidden" name="status" value="paused">
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition-colors duration-200">
                                    <i class="fas fa-pause-circle mr-2"></i>
                                    Suspendre l'offre
                                </button>
                            </form>
                        @endif

                        @if($jobOffer->status == 'paused')
                            <form method="POST" action="{{ route('admin.job-offers.change-status', $jobOffer->slug) }}" class="w-full">
                                @csrf
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition-colors duration-200">
                                    <i class="fas fa-play-circle mr-2"></i>
                                    Réactiver l'offre
                                </button>
                            </form>
                        @endif

                        @if(in_array($jobOffer->status, ['active', 'paused']))
                            <form method="POST" action="{{ route('admin.job-offers.change-status', $jobOffer->slug) }}" class="w-full">
                                @csrf
                                <input type="hidden" name="status" value="closed">
                                <button type="submit" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir fermer cette offre ? Les candidatures ne seront plus acceptées.')"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                    <i class="fas fa-times-circle mr-2"></i>
                                    Fermer l'offre
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Informations de modération -->
                    <div class="border-t pt-3">
                        <div class="text-xs text-gray-500 space-y-1">
                            <div class="flex items-center justify-between">
                                <span>Statut actuel:</span>
                                <span class="font-medium">{{ ucfirst($jobOffer->status) }}</span>
                            </div>
                            @if($jobOffer->is_expired)
                                <div class="flex items-center justify-between text-red-600">
                                    <span>État:</span>
                                    <span class="font-medium">Expirée</span>
                                </div>
                            @endif
                            <div class="flex items-center justify-between">
                                <span>Candidatures actives:</span>
                                <span class="font-medium">{{ $jobOffer->applications_count ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Candidatures récentes -->
            @if($jobOffer->applications && $jobOffer->applications->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-user-friends mr-2"></i>
                            Candidatures récentes
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($jobOffer->applications->take(5) as $application)
                            <div class="p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-purple-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-purple-600 text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $application->first_name }} {{ $application->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $application->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <a href="{{ route('admin.job-applications.show', $application) }}" 
                                       class="text-purple-600 hover:text-purple-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        @if($jobOffer->applications->count() > 5)
                            <div class="p-4 bg-gray-50">
                                <a href="{{ route('admin.job-applications.index', ['job_offer' => $jobOffer->slug]) }}" 
                                   class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                                    Voir toutes les candidatures ({{ $jobOffer->applications->count() }})
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
