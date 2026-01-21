@extends('layouts.iri')

@section('title', 'Newsletter GRN-UCBC')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-md mx-auto px-4">
        
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-coral to-olive rounded-full mx-auto mb-6 flex items-center justify-center">
                <i class="fas fa-envelope text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-3">Newsletter GRN-UCBC</h1>
            <p class="text-gray-600 leading-relaxed">
                Restez informé de nos dernières actualités, publications et découvertes scientifiques
            </p>
        </div>

        @if(session('success'))
            <!-- Message de succès -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-8 mb-8 text-center">
                <div class="w-16 h-16 bg-green-500 rounded-full mx-auto mb-6 flex items-center justify-center">
                    <i class="fas fa-check text-white text-xl"></i>
                </div>
                <h2 class="text-2xl font-semibold text-green-800 mb-4">Inscription réussie !</h2>
                <p class="text-green-700 mb-6 leading-relaxed">{{ session('success') }}</p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ session('redirect_url', url('/')) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-coral text-white font-medium rounded-lg hover:bg-coral-dark transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour
                    </a>
                    <a href="{{ url('/') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>
                        Accueil
                    </a>
                </div>
            </div>
        @else
            <!-- Formulaire d'inscription -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">S'abonner à notre newsletter</h2>
                
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <ul class="list-disc list-inside text-sm space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('newsletter.subscribe') }}">
                    @csrf
                    
                    <!-- URL de redirection -->
                    <input type="hidden" name="redirect_url" value="{{ url()->previous() }}">
                    
                    <div class="space-y-6">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Adresse email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-md focus:ring-2 focus:ring-coral focus:border-coral transition-colors duration-200"
                                   placeholder="votre.email@exemple.com"
                                   aria-label="Votre adresse email"
                                   aria-describedby="email-help">
                            <p id="email-help" class="text-xs text-gray-500 mt-1">
                                Nous ne partagerons jamais votre email avec des tiers.
                            </p>
                        </div>
                        
                        <!-- Nom -->
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom (optionnel)
                            </label>
                            <input type="text" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom') }}"
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-md focus:ring-2 focus:ring-coral focus:border-coral transition-colors duration-200"
                                   placeholder="Votre nom complet"
                                   aria-label="Votre nom complet">
                        </div>

                        <!-- Préférences -->
                        <div>
                            <fieldset>
                                <legend class="text-sm font-medium text-gray-700 mb-4">
                                    Types de notifications souhaitées :
                                </legend>
                                <div class="space-y-3">
                                    @foreach(App\Models\NewsletterPreference::TYPES as $type => $label)
                                        <label class="flex items-start p-3 border-2 border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200">
                                            <input type="checkbox" 
                                                   name="preferences[]" 
                                                   value="{{ $type }}"
                                                   {{ in_array($type, old('preferences', [])) ? 'checked' : '' }}
                                                   class="w-5 h-5 text-coral bg-gray-100 border-gray-300 rounded focus:ring-coral focus:ring-2 mt-0.5">
                                            <div class="ml-3 flex-1">
                                                <div class="text-sm font-medium text-gray-800">{{ $label }}</div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    @switch($type)
                                                        @case('publications')
                                                            Publications académiques et articles de recherche
                                                            @break
                                                        @case('actualites')
                                                            Actualités de l'institut et événements
                                                            @break
                                                        @case('newsletters')
                                                            Bulletins d'information périodiques
                                                            @break
                                                        @case('projets')
                                                            Nouveaux projets et collaborations
                                                            @break
                                                        @default
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
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="flex items-start cursor-pointer">
                                <input type="checkbox" 
                                       name="consent" 
                                       required
                                       class="w-5 h-5 text-coral bg-gray-100 border-gray-300 rounded focus:ring-coral focus:ring-2 mt-0.5">
                                <div class="ml-3">
                                    <div class="text-sm text-gray-700">
                                        J'accepte de recevoir des emails du Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo et je comprends que je peux me désabonner à tout moment.
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Boutons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <button type="submit" 
                                    class="flex-1 bg-coral text-white px-6 py-3 rounded-lg font-medium hover:bg-coral-dark focus:outline-none focus:ring-2 focus:ring-coral focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-envelope mr-2"></i>
                                S'abonner à la newsletter
                            </button>
                            <a href="{{ url()->previous() }}" 
                               class="flex-1 text-center border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-colors duration-200">
                                Annuler
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Informations supplémentaires -->
            <div class="mt-8 text-center">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pourquoi s'abonner ?</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <div>Recevez les dernières publications scientifiques</div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <div>Soyez informé des événements académiques</div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <div>Découvrez nos collaborations</div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <div>Pas de spam, désabonnement facile</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
