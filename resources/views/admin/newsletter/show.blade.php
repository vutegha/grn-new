@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <a href="{{ route('admin.newsletter.index') }}" class="text-white/70 hover:text-white">Newsletter</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">{{ Str::limit($newsletter->email, 30) }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations de l'abonné -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h1 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-user mr-3"></i>
                        Informations de l'Abonné
                    </h1>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="h-16 w-16 rounded-full bg-gradient-to-br from-iri-primary to-iri-secondary flex items-center justify-center">
                            <span class="text-white font-semibold text-xl">
                                {{ strtoupper(substr($newsletter->email, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold text-iri-dark">
                                {{ $newsletter->nom ?: 'Nom non fourni' }}
                            </h4>
                            <p class="text-iri-gray">{{ $newsletter->email }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div class="flex items-center justify-between p-3 rounded-lg border">
                            <div class="flex items-center">
                                <i class="fas fa-envelope mr-3 text-iri-gray"></i>
                                <span class="font-medium">Email</span>
                            </div>
                            <span class="text-sm text-iri-gray break-all">{{ $newsletter->email }}</span>
                        </div>

                        <!-- Nom -->
                        <div class="flex items-center justify-between p-3 rounded-lg border">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-3 text-iri-gray"></i>
                                <span class="font-medium">Nom</span>
                            </div>
                            <span class="text-sm text-iri-gray">{{ $newsletter->nom ?: 'Non fourni' }}</span>
                        </div>

                        <!-- Statut -->
                        <div class="flex items-center justify-between p-3 rounded-lg border">
                            <div class="flex items-center">
                                <i class="fas fa-circle mr-3 text-iri-gray"></i>
                                <span class="font-medium">Statut</span>
                            </div>
                            @if($newsletter->actif)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                    <i class="fas fa-check-circle mr-1"></i>Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                    <i class="fas fa-times-circle mr-1"></i>Inactif
                                </span>
                            @endif
                        </div>

                        <!-- Confirmé -->
                        <div class="flex items-center justify-between p-3 rounded-lg border">
                            <div class="flex items-center">
                                <i class="fas fa-envelope-open mr-3 text-iri-gray"></i>
                                <span class="font-medium">Confirmé</span>
                            </div>
                            @if($newsletter->confirme_a)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                    <i class="fas fa-check mr-1"></i>{{ $newsletter->confirme_a->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-sm text-iri-gray">Non confirmé</span>
                            @endif
                        </div>

                        <!-- Date d'inscription -->
                        <div class="flex items-center justify-between p-3 rounded-lg border">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-plus mr-3 text-iri-gray"></i>
                                <span class="font-medium">Inscrit le</span>
                            </div>
                            <span class="text-sm text-iri-gray">{{ $newsletter->created_at->format('d/m/Y à H:i') }}</span>
                        </div>

                        <!-- Token -->
                        <div class="flex items-center justify-between p-3 rounded-lg border">
                            <div class="flex items-center">
                                <i class="fas fa-key mr-3 text-iri-gray"></i>
                                <span class="font-medium">Token</span>
                            </div>
                            <span class="text-xs font-mono text-iri-gray break-all max-w-32">{{ Str::limit($newsletter->token, 20) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Préférences -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-cog mr-3"></i>
                        Préférences de Contenu
                    </h2>
                </div>
                <div class="p-6">
                    @if(is_array($newsletter->preferences) && count($newsletter->preferences) > 0)
                        <div class="space-y-4">
                            @foreach($preferenceTypes as $type => $label)
                                @php
                                    $isActive = isset($newsletter->preferences[$type]) && $newsletter->preferences[$type] === true;
                                @endphp
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-full flex items-center justify-center">
                                            @if($type === 'publications')
                                                <i class="fas fa-book text-white text-sm"></i>
                                            @elseif($type === 'actualites')
                                                <i class="fas fa-newspaper text-white text-sm"></i>
                                            @elseif($type === 'projets')
                                                <i class="fas fa-rocket text-white text-sm"></i>
                                            @elseif($type === 'evenements')
                                                <i class="fas fa-calendar text-white text-sm"></i>
                                            @elseif($type === 'rapports')
                                                <i class="fas fa-file-alt text-white text-sm"></i>
                                            @else
                                                <i class="fas fa-envelope text-white text-sm"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-iri-dark">{{ $label }}</h4>
                                            <p class="text-xs text-iri-gray">
                                                @if($type === 'publications')
                                                    Notifications pour les nouvelles publications académiques
                                                @elseif($type === 'actualites')
                                                    Notifications pour les actualités importantes
                                                @elseif($type === 'projets')
                                                    Notifications pour les projets et initiatives
                                                @elseif($type === 'evenements')
                                                    Notifications pour les événements et formations
                                                @elseif($type === 'rapports')
                                                    Notifications pour les rapports de recherche
                                                @else
                                                    Notifications diverses
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        @if($isActive)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                                <i class="fas fa-check mr-1"></i>Activé
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                <i class="fas fa-times mr-1"></i>Désactivé
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 mx-auto bg-gradient-to-br from-iri-primary/20 to-iri-secondary/20 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-cog text-iri-gray text-xl"></i>
                            </div>
                            <h4 class="text-lg font-medium text-iri-dark mb-2">Aucune préférence configurée</h4>
                            <p class="text-iri-gray">Cet abonné n'a pas encore configuré ses préférences de contenu.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-bolt mr-3"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <!-- Toggle statut -->
                    <form method="POST" action="{{ route('admin.newsletter.toggle', $newsletter) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 {{ $newsletter->actif ? 'bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700' : 'bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700' }} text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-{{ $newsletter->actif ? 'pause' : 'play' }} mr-2"></i>
                            {{ $newsletter->actif ? 'Désactiver' : 'Activer' }} l'abonné
                        </button>
                    </form>

                    <!-- Modifier préférences -->
                    <a href="{{ route('newsletter.preferences', $newsletter->token) }}" target="_blank" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-cog mr-2"></i>
                        Modifier les préférences
                    </a>

                    <!-- Supprimer -->
                    <form method="POST" action="{{ route('admin.newsletter.destroy', $newsletter) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet abonné ? Cette action est irréversible.')"
                          class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer l'abonné
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-chart-bar mr-3"></i>
                        Informations
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-check-double mr-3 text-iri-gray"></i>
                            <span class="font-medium">Préférences actives</span>
                        </div>
                        <span class="text-sm text-iri-gray">
                            {{ count(array_filter($newsletter->preferences ?? [])) }} / {{ count(\App\Models\NewsletterPreference::TYPES) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-edit mr-3 text-iri-gray"></i>
                            <span class="font-medium">Dernière modification</span>
                        </div>
                        <span class="text-sm text-iri-gray">{{ $newsletter->updated_at->format('d/m/Y à H:i') }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-3 text-iri-gray"></i>
                            <span class="font-medium">Ancienneté</span>
                        </div>
                        <span class="text-sm text-iri-gray">{{ $newsletter->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Liens utiles -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-link mr-3"></i>
                        Liens Utiles
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('newsletter.preferences', $newsletter->token) }}" target="_blank"
                       class="flex items-center p-3 rounded-lg border hover:bg-iri-primary/5 transition-colors duration-200">
                        <i class="fas fa-cog mr-3 text-iri-primary"></i>
                        <span class="text-iri-dark hover:text-iri-primary">Page de préférences</span>
                    </a>
                    
                    <a href="{{ route('newsletter.unsubscribe', $newsletter->token) }}" target="_blank"
                       class="flex items-center p-3 rounded-lg border hover:bg-red-50 transition-colors duration-200">
                        <i class="fas fa-sign-out-alt mr-3 text-red-500"></i>
                        <span class="text-iri-dark hover:text-red-600">Page de désabonnement</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
