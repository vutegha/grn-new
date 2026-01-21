@extends('layouts.iri')

@section('title', 'Erreur 503 - Maintenance en cours | CI-UCBC')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-20">
    <div class="max-w-2xl w-full space-y-8">
        <!-- Carte d'erreur principale -->
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
            <!-- En-tête avec dégradé -->
            <div class="bg-gradient-to-r from-orange-600 to-orange-500 px-8 py-12 text-center">
                <div class="inline-block">
                    <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-full p-6 inline-block">
                        <i class="bi bi-tools text-6xl text-white" style="animation: rotate 4s linear infinite;"></i>
                    </div>
                </div>
                <h1 class="mt-6 text-5xl font-bold text-white">503</h1>
                <p class="mt-2 text-xl text-white text-opacity-90">Service temporairement indisponible</p>
            </div>

                <!-- Contenu principal -->
                <div class="px-8 py-10">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-3">
                            Maintenance en cours
                        </h2>
                        <p class="text-gray-600 text-lg leading-relaxed">
                            Nous effectuons actuellement des opérations de maintenance pour améliorer nos services. 
                            Le site sera de nouveau disponible très bientôt.
                        </p>
                    </div>

                    <!-- Informations de maintenance -->
                    <div class="bg-orange-50 border-l-4 border-orange-500 rounded-lg p-6 mb-6">
                        <div class="flex items-start">
                            <i class="bi bi-clock-history text-orange-500 text-2xl mr-4 mt-1"></i>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-orange-900 mb-2">Temps d'arrêt prévu</h3>
                                <p class="text-orange-800 text-sm leading-relaxed mb-3">
                                    La maintenance est planifiée et devrait être terminée rapidement. 
                                    Nous mettons tout en œuvre pour minimiser la gêne occasionnée.
                                </p>
                                @if(isset($retryAfter))
                                    <p class="text-orange-900 font-semibold">
                                        <i class="bi bi-arrow-clockwise mr-2"></i>
                                        Réessayez dans environ {{ $retryAfter }} secondes
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Ce qui se passe -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="bi bi-info-circle text-blue-500 mr-2"></i>
                            Que se passe-t-il ?
                        </h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <i class="bi bi-check-circle text-green-500 mr-3 mt-1"></i>
                                <span>Mise à jour de la plateforme pour de nouvelles fonctionnalités</span>
                            </li>
                            <li class="flex items-start">
                                <i class="bi bi-check-circle text-green-500 mr-3 mt-1"></i>
                                <span>Amélioration des performances et de la sécurité</span>
                            </li>
                            <li class="flex items-start">
                                <i class="bi bi-check-circle text-green-500 mr-3 mt-1"></i>
                                <span>Optimisation de l'expérience utilisateur</span>
                            </li>
                            <li class="flex items-start">
                                <i class="bi bi-check-circle text-green-500 mr-3 mt-1"></i>
                                <span>Corrections et améliorations diverses</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Bouton d'action -->
                    <div class="text-center">
                        <button onclick="location.reload()" 
                                class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-lg hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                            <i class="bi bi-arrow-clockwise mr-2"></i>
                            Rafraîchir la page
                        </button>
                    </div>
                </div>

                <!-- Pied de page de la carte -->
                <div class="bg-gray-100 px-8 py-6 border-t border-gray-200">
                    <div class="text-center">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Questions urgentes ?</h4>
                        <div class="flex flex-wrap justify-center gap-6 text-sm text-gray-600">
                            <a href="mailto:iri@ucbc.org" class="flex items-center hover:text-orange-600 transition-colors">
                                <i class="bi bi-envelope mr-2"></i>
                                iri@ucbc.org
                            </a>
                            <a href="tel:+243992405948" class="flex items-center hover:text-orange-600 transition-colors">
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
                <p class="mt-4 text-xs">Merci pour votre patience et votre compréhension</p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-refresh toutes les 30 secondes
    setTimeout(() => {
        location.reload();
    }, 30000);
</script>
@endpush
@endsection
