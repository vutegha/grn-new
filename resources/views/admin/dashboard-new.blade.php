@extends('layouts.admin')

@section('title', 'Tableau de Bord')
@section('subtitle', 'Vue d\'ensemble de votre système de gestion')

@section('head')
<style>
    .dashboard-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .stat-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .stat-card.blue {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .stat-card.green {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    .stat-card.orange {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    .stat-card.purple {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    }
    .progress-ring {
        transform: rotate(-90deg);
    }
    .progress-ring-circle {
        stroke-dasharray: 251.2;
        stroke-dashoffset: 251.2;
        transition: stroke-dashoffset 0.35s;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
    .activity-item {
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }
    .activity-item:hover {
        border-left-color: #667eea;
        background-color: #f8fafc;
    }
    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: linear-gradient(45deg, #ff6b6b, #ee5a52);
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
</style>
@endsection

@section('content')

<!-- Header avec salutation personnalisée -->
<div class="dashboard-gradient rounded-xl p-6 mb-8 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 -mr-4 -mt-4 w-32 h-32 bg-white bg-opacity-10 rounded-full"></div>
    <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-24 h-24 bg-white bg-opacity-10 rounded-full"></div>
    <div class="relative z-10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">
                    Bonjour {{ auth()->user()->name }} ! 👋
                </h1>
                <p class="text-white text-opacity-90 text-lg">
                    Voici un aperçu de votre système aujourd'hui.
                </p>
                <p class="text-white text-opacity-75 text-sm mt-1">
                    {{ \Carbon\Carbon::now()->isoFormat('dddd D MMMM YYYY') }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-4xl font-bold">{{ \Carbon\Carbon::now()->format('H:i') }}</div>
                <div class="text-white text-opacity-75">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques principales avec cartes modernisées -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Publications -->
    @if(auth()->user()->can('viewAny', App\Models\Publication::class))
    <div class="stat-card rounded-xl p-6 text-white card-hover relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-16 h-16 bg-white bg-opacity-20 rounded-full"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold">{{ $stats['publications'] ?? 0 }}</div>
                    <div class="text-white text-opacity-80 text-sm">Publications</div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-white text-opacity-80 text-sm">Total créées</span>
                <a href="{{ route('admin.publication.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-1 rounded-full text-sm transition-all">
                    Gérer →
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Actualités -->
    @if(auth()->user()->can('viewAny', App\Models\Actualite::class))
    <div class="stat-card blue rounded-xl p-6 text-white card-hover relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-16 h-16 bg-white bg-opacity-20 rounded-full"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-newspaper text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold">{{ $stats['actualites'] ?? 0 }}</div>
                    <div class="text-white text-opacity-80 text-sm">Actualités</div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-white text-opacity-80 text-sm">Total publiées</span>
                <a href="{{ route('admin.actualite.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-1 rounded-full text-sm transition-all">
                    Gérer →
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Projets -->
    @if(auth()->user()->can('viewAny', App\Models\Projet::class))
    <div class="stat-card green rounded-xl p-6 text-white card-hover relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-16 h-16 bg-white bg-opacity-20 rounded-full"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-project-diagram text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold">{{ $stats['projets'] ?? 0 }}</div>
                    <div class="text-white text-opacity-80 text-sm">Projets</div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                @if(isset($statsProjects))
                <span class="text-white text-opacity-80 text-sm">{{ $statsProjects['projets_en_cours'] }} en cours</span>
                @else
                <span class="text-white text-opacity-80 text-sm">Actifs</span>
                @endif
                <a href="{{ route('admin.projets.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-1 rounded-full text-sm transition-all">
                    Gérer →
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Messages -->
    @if(auth()->user()->can('viewAny', App\Models\Contact::class))
    <div class="stat-card orange rounded-xl p-6 text-white card-hover relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-16 h-16 bg-white bg-opacity-20 rounded-full"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center relative">
                    <i class="fas fa-envelope text-2xl"></i>
                    @php $unreadCount = \App\Models\Contact::where('statut', 'nouveau')->count(); @endphp
                    @if($unreadCount > 0)
                        <div class="notification-badge">{{ $unreadCount }}</div>
                    @endif
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold">{{ $stats['messages'] ?? 0 }}</div>
                    <div class="text-white text-opacity-80 text-sm">Messages</div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-white text-opacity-80 text-sm">{{ $unreadCount }} non lus</span>
                <a href="{{ route('admin.contacts.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-1 rounded-full text-sm transition-all">
                    Lire →
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Actions rapides modernisées -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-zap text-yellow-500 mr-3"></i>
            Actions Rapides
        </h2>
        <div class="text-sm text-gray-500">
            Créez rapidement du nouveau contenu
        </div>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @can('create', App\Models\Publication::class)
        <a href="{{ route('admin.publication.create') }}" class="group flex flex-col items-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-xl transition-all card-hover border border-purple-200">
            <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <i class="fas fa-file-plus text-white"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 text-center">Publication</span>
        </a>
        @endcan

        @can('create', App\Models\Actualite::class)
        <a href="{{ route('admin.actualite.create') }}" class="group flex flex-col items-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl transition-all card-hover border border-blue-200">
            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <i class="fas fa-newspaper text-white"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 text-center">Actualité</span>
        </a>
        @endcan

        @can('create', App\Models\Projet::class)
        <a href="{{ route('admin.projets.create') }}" class="group flex flex-col items-center p-6 bg-gradient-to-br from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-xl transition-all card-hover border border-green-200">
            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <i class="fas fa-plus-circle text-white"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 text-center">Projet</span>
        </a>
        @endcan

        @can('create', App\Models\Service::class)
        <a href="{{ route('admin.service.create') }}" class="group flex flex-col items-center p-6 bg-gradient-to-br from-orange-50 to-orange-100 hover:from-orange-100 hover:to-orange-200 rounded-xl transition-all card-hover border border-orange-200">
            <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <i class="fas fa-cogs text-white"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 text-center">Service</span>
        </a>
        @endcan

        @can('manage_newsletter')
        <a href="{{ route('admin.newsletter.index') }}" class="group flex flex-col items-center p-6 bg-gradient-to-br from-pink-50 to-pink-100 hover:from-pink-100 hover:to-pink-200 rounded-xl transition-all card-hover border border-pink-200">
            <div class="w-12 h-12 bg-pink-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <i class="fas fa-mail-bulk text-white"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 text-center">Newsletter</span>
        </a>
        @endcan

        @can('viewAny', App\Models\User::class)
        <a href="{{ route('admin.users.index') }}" class="group flex flex-col items-center p-6 bg-gradient-to-br from-indigo-50 to-indigo-100 hover:from-indigo-100 hover:to-indigo-200 rounded-xl transition-all card-hover border border-indigo-200">
            <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <i class="fas fa-users text-white"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 text-center">Utilisateurs</span>
        </a>
        @endcan
    </div>
</div>

<!-- Contenu principal avec graphiques et activités récentes -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Colonne principale (2/3) -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Impact des projets (si autorisé) -->
        @if(auth()->user()->can('viewAny', App\Models\Projet::class) && isset($statsProjects))
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-users text-green-500 mr-3"></i>
                    Impact des Projets
                </h3>
                <div class="text-sm text-gray-500">Statistiques globales</div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div class="text-3xl font-bold text-blue-600 mb-2">{{ number_format($statsProjects['total_beneficiaires']) }}</div>
                    <div class="text-sm text-gray-600">Total Bénéficiaires</div>
                </div>
                
                <div class="text-center p-6 bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl">
                    <div class="w-16 h-16 bg-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-female text-white text-xl"></i>
                    </div>
                    <div class="text-3xl font-bold text-pink-600 mb-2">{{ number_format($statsProjects['beneficiaires_femmes']) }}</div>
                    <div class="text-sm text-gray-600">Femmes</div>
                </div>
                
                <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-male text-white text-xl"></i>
                    </div>
                    <div class="text-3xl font-bold text-green-600 mb-2">{{ number_format($statsProjects['beneficiaires_hommes']) }}</div>
                    <div class="text-sm text-gray-600">Hommes</div>
                </div>
            </div>
            
            @if($statsProjects['total_beneficiaires'] > 0)
            <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-medium text-gray-700">Répartition par genre</span>
                    <span class="text-sm text-gray-500">{{ round(($statsProjects['beneficiaires_femmes'] / $statsProjects['total_beneficiaires']) * 100) }}% Femmes</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-pink-400 to-pink-600 h-3 rounded-full transition-all duration-1000" 
                         style="width: {{ ($statsProjects['beneficiaires_femmes'] / $statsProjects['total_beneficiaires']) * 100 }}%"></div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Graphique d'évolution (si des données existent) -->
        @if(!empty($moisLabels))
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-chart-line text-purple-500 mr-3"></i>
                    Évolution des Publications
                </h3>
                <div class="text-sm text-gray-500">6 derniers mois</div>
            </div>
            
            <div class="chart-container">
                <canvas id="evolutionChart"></canvas>
            </div>
        </div>
        @endif

        <!-- Activités récentes -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-clock text-indigo-500 mr-3"></i>
                    Activités Récentes
                </h3>
                <div class="text-sm text-gray-500">Dernières actions</div>
            </div>
            
            <div class="space-y-4 max-h-80 overflow-y-auto">
                @if(auth()->user()->can('viewAny', App\Models\Publication::class) && $dernieresPublications->count() > 0)
                    @foreach($dernieresPublications as $publication)
                    <div class="activity-item p-4 rounded-lg border-l-4 border-purple-200 bg-purple-50 hover:bg-purple-100 transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-file-alt text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ Str::limit($publication->title, 50) }}</h4>
                                    <p class="text-sm text-gray-500">Publication créée</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">{{ $publication->created_at->diffForHumans() }}</div>
                                <div class="text-xs text-gray-400">{{ $publication->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif

                @if(auth()->user()->can('viewAny', App\Models\Actualite::class) && $dernieresActualites->count() > 0)
                    @foreach($dernieresActualites as $actualite)
                    <div class="activity-item p-4 rounded-lg border-l-4 border-blue-200 bg-blue-50 hover:bg-blue-100 transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-newspaper text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ Str::limit($actualite->title, 50) }}</h4>
                                    <p class="text-sm text-gray-500">Actualité publiée</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">{{ $actualite->created_at->diffForHumans() }}</div>
                                <div class="text-xs text-gray-400">{{ $actualite->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif

                @if(auth()->user()->can('viewAny', App\Models\Contact::class) && $derniersMessages->count() > 0)
                    @foreach($derniersMessages as $message)
                    <div class="activity-item p-4 rounded-lg border-l-4 border-orange-200 bg-orange-50 hover:bg-orange-100 transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-envelope text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ Str::limit($message->sujet, 50) }}</h4>
                                    <p class="text-sm text-gray-500">Message de {{ $message->nom }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">{{ $message->created_at->diffForHumans() }}</div>
                                @if($message->statut == 'nouveau')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Non lu</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif

                @if($dernieresPublications->count() == 0 && $dernieresActualites->count() == 0 && $derniersMessages->count() == 0)
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-500">Aucune activité récente</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Colonne secondaire (1/3) -->
    <div class="space-y-8">
        
        <!-- Notifications/Alertes -->
        @php $unreadMessages = \App\Models\Contact::where('statut', 'nouveau')->count(); @endphp
        @if($unreadMessages > 0)
        <div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-xl p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-red-900">Attention requise</h3>
                    <p class="text-red-700 text-sm">{{ $unreadMessages }} nouveau(x) message(s) non lu(s)</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.contacts.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-eye mr-2"></i>
                    Consulter maintenant
                </a>
            </div>
        </div>
        @endif

        <!-- Prochains événements (si autorisé) -->
        @if(auth()->user()->can('viewAny', App\Models\Evenement::class) && isset($prochainsEvenements) && $prochainsEvenements->count() > 0)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-calendar-alt text-green-500 mr-3"></i>
                    Prochains Événements
                </h3>
                <a href="{{ route('admin.evenements.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Voir tout →
                </a>
            </div>
            
            <div class="space-y-4">
                @foreach($prochainsEvenements as $evenement)
                <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-start space-x-3">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-xs text-green-600 font-semibold">
                                    {{ \Carbon\Carbon::parse($evenement->date_debut)->format('M') }}
                                </div>
                                <div class="text-sm font-bold text-green-700">
                                    {{ \Carbon\Carbon::parse($evenement->date_debut)->format('d') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 text-sm mb-1">{{ Str::limit($evenement->titre, 40) }}</h4>
                            <p class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($evenement->date_debut)->format('d/m/Y à H:i') }}
                            </p>
                            <p class="text-xs text-green-600 mt-1">
                                Dans {{ \Carbon\Carbon::parse($evenement->date_debut)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Outils d'administration -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-tools text-indigo-500 mr-3"></i>
                    Outils Admin
                </h3>
            </div>
            
            <div class="space-y-3">
                @can('viewAny', App\Models\User::class)
                <a href="{{ route('admin.email-test.index') }}" 
                   class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-envelope-open-text text-white"></i>
                    </div>
                    <div class="ml-3">
                        <div class="font-medium text-gray-900 text-sm">Test Email</div>
                        <div class="text-xs text-gray-500">Tester l'envoi d'emails</div>
                    </div>
                </a>
                
                <a href="{{ route('admin.email-settings.index') }}" 
                   class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-cog text-white"></i>
                    </div>
                    <div class="ml-3">
                        <div class="font-medium text-gray-900 text-sm">Config Emails</div>
                        <div class="text-xs text-gray-500">Paramètres d'envoi</div>
                    </div>
                </a>
                
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div class="ml-3">
                        <div class="font-medium text-gray-900 text-sm">Utilisateurs</div>
                        <div class="text-xs text-gray-500">Gérer les comptes</div>
                    </div>
                </a>
                @endcan
                
                <a href="{{ route('admin.contacts.index') }}" 
                   class="flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors group">
                    <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform relative">
                        <i class="fas fa-inbox text-white"></i>
                        @if($unreadMessages > 0)
                            <div class="notification-badge">{{ $unreadMessages }}</div>
                        @endif
                    </div>
                    <div class="ml-3">
                        <div class="font-medium text-gray-900 text-sm">Messages</div>
                        <div class="text-xs text-gray-500">{{ $unreadMessages }} non lus</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Raccourcis système -->
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-rocket text-yellow-500 mr-3"></i>
                    Système
                </h3>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                    <div class="text-sm font-medium text-gray-900">Utilisateurs</div>
                    <div class="text-lg font-bold text-blue-600">{{ \App\Models\User::count() }}</div>
                </div>
                
                <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                    <div class="text-sm font-medium text-gray-900">Rôles</div>
                    <div class="text-lg font-bold text-green-600">{{ \Spatie\Permission\Models\Role::count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@if(!empty($moisLabels))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique d'évolution
    const ctx = document.getElementById('evolutionChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($moisLabels) !!},
                datasets: [
                    @if(auth()->user()->can('viewAny', App\Models\Publication::class))
                    {
                        label: 'Publications',
                        data: {!! json_encode($publicationsData) !!},
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    @endif
                    @if(auth()->user()->can('viewAny', App\Models\Actualite::class))
                    {
                        label: 'Actualités',
                        data: {!! json_encode($actualitesData) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    @endif
                    @if(auth()->user()->can('viewAny', App\Models\Projet::class))
                    {
                        label: 'Projets',
                        data: {!! json_encode($projetsData) !!},
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                    @endif
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                },
                elements: {
                    point: {
                        radius: 6,
                        hoverRadius: 8
                    }
                }
            }
        });
    }
});
</script>
@endif
@endsection
