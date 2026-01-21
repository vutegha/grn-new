@extends('layouts.iri')

@section('title', 'Erreur 500 - Erreur serveur | CI-UCBC')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-20">
    <div class="max-w-2xl w-full space-y-8">
        <!-- Carte d'erreur principale -->
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
            <!-- En-tête avec dégradé -->
            <div class="bg-gradient-to-r from-purple-900 to-purple-600 px-8 py-12 text-center">
                <div class="inline-block" style="animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;">
                    <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-full p-6 inline-block">
                        <i class="bi bi-exclamation-triangle-fill text-6xl text-white"></i>
                    </div>
                </div>
                <h1 class="mt-6 text-5xl font-bold text-white">500</h1>
                <p class="mt-2 text-xl text-white text-opacity-90">Erreur interne du serveur</p>
            </div>

                <!-- Contenu principal -->
                <div class="px-8 py-10">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-3">
                            Oups ! Une erreur s'est produite
                        </h2>
                        <p class="text-gray-600 text-lg leading-relaxed">
                            Nous sommes désolés, mais quelque chose s'est mal passé de notre côté. 
                            Notre équipe technique a été automatiquement notifiée et travaille à résoudre le problème.
                        </p>
                    </div>

                    <!-- Informations pour l'utilisateur -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6 mb-6">
                        <div class="flex items-start">
                            <i class="bi bi-info-circle-fill text-blue-500 text-2xl mr-4 mt-1"></i>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-blue-900 mb-2">Que s'est-il passé ?</h3>
                                <p class="text-blue-800 text-sm leading-relaxed">
                                    Le serveur a rencontré une condition inattendue qui l'a empêché de traiter votre demande. 
                                    Cette erreur a été enregistrée et notre équipe va l'examiner rapidement.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Suggestions d'actions -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="bi bi-lightbulb text-yellow-500 mr-2"></i>
                            Que pouvez-vous faire ?
                        </h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <i class="bi bi-arrow-clockwise text-purple-500 mr-3 mt-1"></i>
                                <span>Rafraîchir la page et réessayer</span>
                            </li>
                            <li class="flex items-start">
                                <i class="bi bi-clock-history text-purple-500 mr-3 mt-1"></i>
                                <span>Attendre quelques minutes avant de réessayer</span>
                            </li>
                            <li class="flex items-start">
                                <i class="bi bi-house text-purple-500 mr-3 mt-1"></i>
                                <span>Retourner à la page d'accueil</span>
                            </li>
                            <li class="flex items-start">
                                <i class="bi bi-envelope text-purple-500 mr-3 mt-1"></i>
                                <span>Si le problème persiste, contactez notre support</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button onclick="location.reload()" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                            <i class="bi bi-arrow-clockwise mr-2"></i>
                            Rafraîchir la page
                        </button>

                        <a href="{{ url('/') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg border-2 border-gray-300 hover:border-purple-500 hover:text-purple-600 transform hover:scale-105 transition-all duration-200">
                            <i class="bi bi-house-door mr-2"></i>
                            Retour à l'accueil
                        </a>

                        <button onclick="history.back()" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg border-2 border-gray-300 hover:border-purple-500 hover:text-purple-600 transform hover:scale-105 transition-all duration-200">
                            <i class="bi bi-arrow-left mr-2"></i>
                            Page précédente
                        </button>
                    </div>
                </div>

                <!-- Pied de page de la carte -->
                <div class="bg-gray-100 px-8 py-6 border-t border-gray-200">
                    <div class="text-center">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Besoin d'aide immédiate ?</h4>
                        <div class="flex flex-wrap justify-center gap-6 text-sm text-gray-600">
                            <a href="mailto:iri@ucbc.org" class="flex items-center hover:text-purple-600 transition-colors">
                                <i class="bi bi-envelope mr-2"></i>
                                iri@ucbc.org
                            </a>
                            <a href="tel:+243992405948" class="flex items-center hover:text-purple-600 transition-colors">
                                <i class="bi bi-telephone mr-2"></i>
                                +243 992 405 948
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations supplémentaires -->
            <div class="text-center text-sm text-gray-500">
                <p class="mb-2">
                    <strong>Centre de Gouvernance des Ressources Naturelles</strong>
                </p>
                <p>Programme Gouvernance des Ressources Naturelles - UCBC (CI-UCBC)</p>
                @if(config('app.debug') && isset($exception))
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg text-left">
                        <p class="text-red-800 font-mono text-xs break-all">
                            <strong>Debug Info:</strong> {{ $exception->getMessage() }}
                        </p>
                        <p class="text-red-700 font-mono text-xs mt-2">
                            <strong>File:</strong> {{ $exception->getFile() }}:{{ $exception->getLine() }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .5; }
    }
</style>
@endpush

@push('scripts')
<script>
    // Log l'erreur côté client pour analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'exception', {
            'description': '500 Error',
            'fatal': false
        });
    }
</script>
@endpush
@endsection
