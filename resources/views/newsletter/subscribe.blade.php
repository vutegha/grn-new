@extends('layouts.iri')

@section('title', 'Newsletter GRN-UCBC')

@section('breadcrumb')
    <x-breadcrumb-overlay :items="[
        ['title' => 'Newsletter', 'url' => null]
    ]" />
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/newsletter.css') }}">
@endpush

@php
    // Ensure variables are properly initialized with enhanced stats
    $stats = $stats ?? [
        'subscribers' => 0, 
        'publications' => 0, 
        'open_rate' => 0,
        'recent_subscribers' => 0,
        'total_content' => 0,
        'average_monthly_content' => 0
    ];
@endphp

@section('content')
<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-24 h-24 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full mx-auto mb-6 flex items-center justify-center">
                <i class="fas fa-envelope text-white text-3xl" aria-hidden="true"></i>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                Newsletter GRN-UCBC
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed drop-shadow-lg">
                Restez informé de nos dernières actualités, publications et découvertes scientifiques
            </p>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <!-- Message de succès -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-8 text-center">
                        <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full mx-auto mb-6 flex items-center justify-center">
                            <i class="fas fa-check text-white text-2xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-green-800 mb-4">Inscription réussie !</h2>
                        <p class="text-green-700 text-lg mb-8 leading-relaxed">{{ session('success') }}</p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-md mx-auto">
                            <a href="{{ session('redirect_url', url('/')) }}" 
                               class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i>
                                Retour
                            </a>
                            <a href="{{ url('/') }}" 
                               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all duration-200">
                                <i class="fas fa-home mr-2" aria-hidden="true"></i>
                                Accueil
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Formulaire d'inscription -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                            <!-- Header du formulaire -->
                            <div class="bg-gradient-to-r from-gray-50 to-white p-8 border-b border-gray-200">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">S'abonner à notre newsletter</h2>
                                <p class="text-gray-600">Rejoignez notre communauté et ne manquez aucune actualité importante</p>
                            </div>

                            <!-- Corps du formulaire -->
                            <div class="p-8">
                                @if($errors->any())
                                    <div class="bg-red-50 border-l-4 border-red-500 rounded-r-lg p-4 mb-6">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-exclamation-triangle text-red-400 text-lg" aria-hidden="true"></i>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-red-800 font-medium mb-2">Erreurs de validation</h3>
                                                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                                    @foreach($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <form method="POST" action="{{ route('newsletter.subscribe') }}" class="space-y-6">
                                    @csrf
                                    
                                    <!-- URL de redirection -->
                                    <input type="hidden" name="redirect_url" value="{{ url()->previous() }}">
                                    
                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Adresse email <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-envelope text-gray-400" aria-hidden="true"></i>
                                            </div>
                                            <input type="email" 
                                                   id="email" 
                                                   name="email" 
                                                   value="{{ old('email') }}"
                                                   required
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200 @error('email') border-red-500 @enderror"
                                                   placeholder="votre.email@exemple.com"
                                                   aria-label="Votre adresse email"
                                                   aria-describedby="email-help">
                                        </div>
                                        <p id="email-help" class="text-xs text-gray-500 mt-2 flex items-center">
                                            <i class="fas fa-shield-alt mr-1 text-iri-primary" aria-hidden="true"></i>
                                            Nous ne partagerons jamais votre email avec des tiers.
                                        </p>
                                    </div>
                                    
                                    <!-- Nom -->
                                    <div>
                                        <label for="nom" class="block text-sm font-semibold text-gray-900 mb-2">
                                            Nom complet (optionnel)
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user text-gray-400" aria-hidden="true"></i>
                                            </div>
                                            <input type="text" 
                                                   id="nom" 
                                                   name="nom" 
                                                   value="{{ old('nom') }}"
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200"
                                                   placeholder="Votre nom complet"
                                                   aria-label="Votre nom complet">
                                        </div>
                                    </div>

                                    <!-- Préférences -->
                                    <div>
                                        <fieldset class="space-y-4">
                                            <legend class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                                <i class="fas fa-cog mr-2 text-iri-primary" aria-hidden="true"></i>
                                                Types de notifications souhaitées
                                            </legend>
                                            <div class="grid grid-cols-1 gap-3">
                                                @foreach(App\Models\NewsletterPreference::TYPES as $type => $label)
                                                    <label class="relative flex items-start p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-iri-primary/50 cursor-pointer transition-all duration-200 group">
                                                        <input type="checkbox" 
                                                               name="preferences[]" 
                                                               value="{{ $type }}"
                                                               {{ in_array($type, old('preferences', [])) ? 'checked' : '' }}
                                                               class="w-5 h-5 text-iri-primary bg-gray-100 border-gray-300 rounded focus:ring-iri-primary focus:ring-2 mt-0.5">
                                                        <div class="ml-4 flex-1">
                                                            <div class="text-sm font-medium text-gray-900 group-hover:text-iri-primary transition-colors duration-200">
                                                                {{ $label }}
                                                            </div>
                                                            <div class="text-xs text-gray-500 mt-1">
                                                                @switch($type)
                                                                    @case('actualites')
                                                                        <i class="fas fa-newspaper mr-1" aria-hidden="true"></i>
                                                                        Actualités de l'institut et informations importantes
                                                                        @break
                                                                    @case('publications')
                                                                        <i class="fas fa-file-alt mr-1" aria-hidden="true"></i>
                                                                        Publications académiques et articles de recherche
                                                                        @break
                                                                    @case('rapports')
                                                                        <i class="fas fa-chart-line mr-1" aria-hidden="true"></i>
                                                                        Rapports d'activité et analyses approfondies
                                                                        @break
                                                                    @case('projets')
                                                                        <i class="fas fa-project-diagram mr-1" aria-hidden="true"></i>
                                                                        Nouveaux projets et collaborations
                                                                        @break
                                                                    @case('evenements')
                                                                        <i class="fas fa-calendar-alt mr-1" aria-hidden="true"></i>
                                                                        Événements, conférences et formations
                                                                        @break
                                                                    @default
                                                                        <i class="fas fa-bell mr-1" aria-hidden="true"></i>
                                                                        Notifications générales
                                                                @endswitch
                                                            </div>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </fieldset>
                                    </div>

                                    <!-- Consentement -->
                                    <div class="bg-gradient-to-r from-iri-primary/5 to-iri-secondary/5 p-6 rounded-lg border border-iri-primary/20">
                                        <label class="flex items-start cursor-pointer">
                                            <input type="checkbox" 
                                                   name="consent" 
                                                   required
                                                   class="w-5 h-5 text-iri-primary bg-gray-100 border-gray-300 rounded focus:ring-iri-primary focus:ring-2 mt-0.5">
                                            <div class="ml-4">
                                                <div class="text-sm text-gray-700 leading-relaxed">
                                                    <i class="fas fa-check-circle text-iri-primary mr-1" aria-hidden="true"></i>
                                                    J'accepte de recevoir des emails du <strong>Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo</strong> et je comprends que je peux me désabonner à tout moment.
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Boutons -->
                                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                                        <button type="submit" 
                                                class="flex-1 bg-gradient-to-r from-iri-primary to-iri-secondary text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-iri-primary focus:ring-offset-2">
                                            <i class="fas fa-envelope mr-2" aria-hidden="true"></i>
                                            S'abonner à la newsletter
                                        </button>
                                        <a href="{{ url()->previous() }}" 
                                           class="flex-1 text-center border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                                            Annuler
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar informatif -->
                    <div class="lg:col-span-1">
                        <div class="space-y-6">
                            <!-- Avantages -->
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                                    <i class="fas fa-star text-iri-accent mr-2" aria-hidden="true"></i>
                                    Pourquoi s'abonner ?
                                </h3>
                                
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                            <i class="fas fa-check text-white text-xs" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 text-sm">Publications scientifiques</div>
                                            <div class="text-xs text-gray-500 mt-1">Recevez les dernières recherches</div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                            <i class="fas fa-calendar text-white text-xs" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 text-sm">Événements académiques</div>
                                            <div class="text-xs text-gray-500 mt-1">Conférences et formations</div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                            <i class="fas fa-handshake text-white text-xs" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 text-sm">Collaborations</div>
                                            <div class="text-xs text-gray-500 mt-1">Opportunités de partenariat</div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 bg-gradient-to-r from-iri-primary to-iri-secondary rounded-full flex items-center justify-center mr-3 mt-0.5">
                                            <i class="fas fa-shield-alt text-white text-xs" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 text-sm">Pas de spam</div>
                                            <div class="text-xs text-gray-500 mt-1">Désabonnement facile</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistiques -->
                            <div class="bg-gradient-to-br from-iri-primary to-iri-secondary rounded-2xl p-6 text-white">
                                <h3 class="text-lg font-bold mb-4 flex items-center">
                                    <i class="fas fa-chart-bar mr-2" aria-hidden="true"></i>
                                    Notre communauté
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-white/90 text-sm">Abonnés actifs</span>
                                        <span class="font-bold text-lg stats-number">{{ number_format($stats['subscribers'] ?? 0) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-white/90 text-sm">Publications totales</span>
                                        <span class="font-bold text-lg stats-number">{{ number_format($stats['total_content'] ?? 0) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-white/90 text-sm">Taux d'ouverture</span>
                                        <span class="font-bold text-lg stats-number">{{ number_format($stats['open_rate'] ?? 0) }}%</span>
                                    </div>
                                    @if(($stats['recent_subscribers'] ?? 0) > 0)
                                    <div class="flex justify-between items-center pt-2 border-t border-white/20">
                                        <span class="text-white/80 text-xs">Nouveaux ce mois</span>
                                        <span class="font-medium text-sm text-iri-gold">+{{ number_format($stats['recent_subscribers']) }}</span>
                                    </div>
                                    @endif
                                    @if(($stats['average_monthly_content'] ?? 0) > 0)
                                    <div class="flex justify-between items-center">
                                        <span class="text-white/80 text-xs">Contenu/mois</span>
                                        <span class="font-medium text-sm">{{ $stats['average_monthly_content'] }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Contact -->
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Besoin d'aide ?</h3>
                                <p class="text-gray-600 text-sm mb-4">
                                    Une question sur notre newsletter ?
                                </p>
                                <a href="{{ route('site.contact') }}" 
                                   class="inline-flex items-center w-full justify-center bg-gradient-to-r from-iri-accent to-iri-gold text-white font-semibold py-2 px-4 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                    <i class="fas fa-envelope mr-2" aria-hidden="true"></i>
                                    Nous contacter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>

@push('scripts')
<script src="{{ asset('js/newsletter.js') }}" defer></script>
@endpush
@endsection
