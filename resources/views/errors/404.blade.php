@extends('layouts.iri')

@section('title', 'Erreur 404 - Page introuvable | CI-UCBC')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-20">
    <div class="max-w-2xl w-full space-y-8">
        <!-- Carte d'erreur principale -->
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
            <!-- En-tête avec dégradé -->
            <div class="bg-gradient-to-r from-blue-900 to-blue-600 px-8 py-12 text-center">
                <div class="inline-block" style="animation: float 3s ease-in-out infinite;">
                    <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-full p-6 inline-block">
                        <i class="bi bi-compass text-6xl text-white"></i>
                    </div>
                </div>
                <h1 class="mt-6 text-5xl font-bold text-white">404</h1>
                <p class="mt-2 text-xl text-white text-opacity-90">Page introuvable</p>
            </div>

                <!-- Contenu principal -->
                <div class="px-8 py-10">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-3">
                            Cette page semble perdue
                        </h2>
                        <p class="text-gray-600 text-lg leading-relaxed">
                            La page que vous recherchez n'existe pas ou a été déplacée. 
                            Vérifiez l'URL ou explorez nos ressources ci-dessous.
                        </p>
                    </div>

                    <!-- Suggestions de navigation -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">
                            <i class="bi bi-signpost-2 mr-2"></i>
                            Pages populaires
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('site.home') }}" class="flex items-center p-3 bg-white rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="bi bi-house-door text-blue-600 text-xl mr-3"></i>
                                <span class="text-gray-700 font-medium">Accueil</span>
                            </a>
                            <a href="{{ route('site.actualites') }}" class="flex items-center p-3 bg-white rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="bi bi-newspaper text-blue-600 text-xl mr-3"></i>
                                <span class="text-gray-700 font-medium">Actualités</span>
                            </a>
                            <a href="{{ route('site.publications') }}" class="flex items-center p-3 bg-white rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="bi bi-book text-blue-600 text-xl mr-3"></i>
                                <span class="text-gray-700 font-medium">Publications</span>
                            </a>
                            <a href="{{ route('site.projets') }}" class="flex items-center p-3 bg-white rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="bi bi-briefcase text-blue-600 text-xl mr-3"></i>
                                <span class="text-gray-700 font-medium">Projets</span>
                            </a>
                            <a href="{{ route('site.services') }}" class="flex items-center p-3 bg-white rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="bi bi-gear text-blue-600 text-xl mr-3"></i>
                                <span class="text-gray-700 font-medium">Services</span>
                            </a>
                            <a href="{{ route('site.contact') }}" class="flex items-center p-3 bg-white rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="bi bi-envelope text-blue-600 text-xl mr-3"></i>
                                <span class="text-gray-700 font-medium">Contact</span>
                            </a>
                        </div>
                    </div>

                    <!-- Barre de recherche -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="bi bi-search text-blue-500 mr-2"></i>
                            Rechercher sur le site
                        </h3>
                        <form action="{{ route('site.search', ['query' => '']) }}" method="GET" class="flex gap-2">
                            <input type="text" 
                                   name="query" 
                                   placeholder="Que recherchez-vous ?" 
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <button type="submit" 
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ url('/') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg">
                            <i class="bi bi-house-door mr-2"></i>
                            Retour à l'accueil
                        </a>

                        <button onclick="history.back()" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg border-2 border-gray-300 hover:border-blue-500 hover:text-blue-600 transform hover:scale-105 transition-all duration-200">
                            <i class="bi bi-arrow-left mr-2"></i>
                            Page précédente
                        </button>
                    </div>
                </div>

                <!-- Pied de page de la carte -->
                <div class="bg-gray-100 px-8 py-6 border-t border-gray-200">
                    <div class="text-center">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Besoin d'aide ?</h4>
                        <div class="flex flex-wrap justify-center gap-6 text-sm text-gray-600">
                            <a href="mailto:iri@ucbc.org" class="flex items-center hover:text-blue-600 transition-colors">
                                <i class="bi bi-envelope mr-2"></i>
                                iri@ucbc.org
                            </a>
                            <a href="tel:+243992405948" class="flex items-center hover:text-blue-600 transition-colors">
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
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
</style>
@endpush

@push('scripts')
<script>
    // Log l'erreur 404 pour analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'exception', {
            'description': '404 Error - ' + window.location.pathname,
            'fatal': false
        });
    }
</script>
@endpush
@endsection
