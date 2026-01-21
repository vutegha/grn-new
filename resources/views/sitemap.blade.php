@extends('layouts.iri')

@section('title', 'Plan du Site')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
    <div class="absolute inset-0 bg-black/10"></div>
    
    @section('breadcrumb')
    <x-breadcrumb-overlay :items="[
        ['title' => 'Plan du site', 'url' => null]
    ]" />
    @endsection
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="mb-6">
            <i class="fas fa-sitemap text-white text-6xl mb-4 drop-shadow-2xl"></i>
        </div>
        <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 drop-shadow-2xl">
            Plan du Site
        </h1>
        <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed drop-shadow-lg">
            Naviguer facilement à travers toutes les sections de notre site
        </p>
    </div>
</section>

<!-- Sitemap Content -->
<section class="py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Colonne 1 : Pages principales -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-iri-primary to-iri-secondary p-3 rounded-lg mr-4">
                        <i class="fas fa-home text-white text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Pages Principales</h2>
                </div>
                
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('site.home') }}" class="group flex items-center text-gray-700 hover:text-iri-primary transition-colors duration-200">
                            <i class="fas fa-angle-right mr-3 text-iri-primary group-hover:translate-x-1 transition-transform duration-200"></i>
                            <span class="font-medium">Accueil</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/') }}#aboutus" class="group flex items-center text-gray-700 hover:text-iri-primary transition-colors duration-200">
                            <i class="fas fa-angle-right mr-3 text-iri-primary group-hover:translate-x-1 transition-transform duration-200"></i>
                            <span class="font-medium">À propos de nous</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site.contact') }}" class="group flex items-center text-gray-700 hover:text-iri-primary transition-colors duration-200">
                            <i class="fas fa-angle-right mr-3 text-iri-primary group-hover:translate-x-1 transition-transform duration-200"></i>
                            <span class="font-medium">Contact</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site.galerie') }}" class="group flex items-center text-gray-700 hover:text-iri-primary transition-colors duration-200">
                            <i class="fas fa-angle-right mr-3 text-iri-primary group-hover:translate-x-1 transition-transform duration-200"></i>
                            <span class="font-medium">Galerie Photo</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Colonne 2 : Nos domaines & Activités -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-iri-accent to-iri-gold p-3 rounded-lg mr-4">
                        <i class="fas fa-cogs text-white text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Nos Activités</h2>
                </div>
                
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('site.services') }}" class="group flex items-center text-gray-700 hover:text-iri-primary transition-colors duration-200">
                            <i class="fas fa-angle-right mr-3 text-iri-accent group-hover:translate-x-1 transition-transform duration-200"></i>
                            <span class="font-medium">Nos Domaines d'intérêts</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site.projets') }}" class="group flex items-center text-gray-700 hover:text-iri-primary transition-colors duration-200">
                            <i class="fas fa-angle-right mr-3 text-iri-accent group-hover:translate-x-1 transition-transform duration-200"></i>
                            <span class="font-medium">Nos Projets</span>
                        </a>
                        <ul class="ml-8 mt-2 space-y-2">
                            <li>
                                <a href="{{ route('site.projets', ['etat' => 'en cours']) }}" class="text-sm text-gray-600 hover:text-iri-secondary flex items-center">
                                    <i class="fas fa-circle text-green-500 mr-2" style="font-size: 6px;"></i>
                                    Projets en cours
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('site.projets', ['etat' => 'termine']) }}" class="text-sm text-gray-600 hover:text-iri-secondary flex items-center">
                                    <i class="fas fa-circle text-blue-500 mr-2" style="font-size: 6px;"></i>
                                    Projets terminés
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('site.evenements') }}" class="group flex items-center text-gray-700 hover:text-iri-primary transition-colors duration-200">
                            <i class="fas fa-angle-right mr-3 text-iri-accent group-hover:translate-x-1 transition-transform duration-200"></i>
                            <span class="font-medium">Événements</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Colonne 3 : Ressources & Publications -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-iri-secondary to-iri-primary p-3 rounded-lg mr-4">
                        <i class="fas fa-book text-white text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Ressources</h2>
                </div>
                
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('site.publications') }}" class="group flex items-center text-gray-700 hover:text-iri-primary transition-colors duration-200">
                            <i class="fas fa-angle-right mr-3 text-iri-secondary group-hover:translate-x-1 transition-transform duration-200"></i>
                            <span class="font-medium">Publications</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site.actualites') }}" class="group flex items-center text-gray-700 hover:text-iri-primary transition-colors duration-200">
                            <i class="fas fa-angle-right mr-3 text-iri-secondary group-hover:translate-x-1 transition-transform duration-200"></i>
                            <span class="font-medium">Actualités</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site.rapports') }}" class="group flex items-center text-gray-700 hover:text-iri-primary transition-colors duration-200">
                            <i class="fas fa-angle-right mr-3 text-iri-secondary group-hover:translate-x-1 transition-transform duration-200"></i>
                            <span class="font-medium">Rapports</span>
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Ligne 2 : Carrières et Informations -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
            
            <!-- Carrières -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-3 rounded-lg mr-4">
                        <i class="fas fa-briefcase text-white text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Carrières & Collaboration</h2>
                </div>
                
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('site.work-with-us') }}" class="group flex items-center text-gray-700 hover:text-iri-primary transition-colors duration-200">
                            <i class="fas fa-angle-right mr-3 text-indigo-500 group-hover:translate-x-1 transition-transform duration-200"></i>
                            <span class="font-medium">Travaillez avec nous</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site.work-with-us') }}#emplois" class="group flex items-center text-gray-700 hover:text-iri-primary transition-colors duration-200">
                            <i class="fas fa-angle-right mr-3 text-indigo-500 group-hover:translate-x-1 transition-transform duration-200"></i>
                            <span class="font-medium">Offres d'emploi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site.work-with-us') }}#collaboration" class="group flex items-center text-gray-700 hover:text-iri-primary transition-colors duration-200">
                            <i class="fas fa-angle-right mr-3 text-indigo-500 group-hover:translate-x-1 transition-transform duration-200"></i>
                            <span class="font-medium">Opportunités de collaboration</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Informations -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-gray-600 to-gray-800 p-3 rounded-lg mr-4">
                        <i class="fas fa-info-circle text-white text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Informations</h2>
                </div>
                
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('site.sitemap') }}" class="group flex items-center text-iri-primary font-semibold transition-colors duration-200">
                            <i class="fas fa-angle-right mr-3 text-iri-primary group-hover:translate-x-1 transition-transform duration-200"></i>
                            <span>Plan du site</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site.contact') }}" class="group flex items-center text-gray-700 hover:text-iri-primary transition-colors duration-200">
                            <i class="fas fa-angle-right mr-3 text-gray-600 group-hover:translate-x-1 transition-transform duration-200"></i>
                            <span class="font-medium">Contactez-nous</span>
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Section statistiques -->
        <div class="mt-12 bg-gradient-to-r from-iri-primary via-iri-secondary to-iri-accent rounded-2xl shadow-xl p-8 text-white">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="text-4xl font-bold mb-2">{{ \App\Models\Service::count() }}</div>
                    <div class="text-white/90 text-sm">Domaines d'intérêts</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">{{ \App\Models\Projet::count() }}</div>
                    <div class="text-white/90 text-sm">Projets</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">{{ \App\Models\Publication::count() }}</div>
                    <div class="text-white/90 text-sm">Publications</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">{{ \App\Models\Actualite::count() }}</div>
                    <div class="text-white/90 text-sm">Actualités</div>
                </div>
            </div>
        </div>

        <!-- Section d'aide -->
        <div class="mt-12 bg-blue-50 rounded-2xl border border-blue-200 p-8">
            <div class="flex items-start">
                <div class="bg-blue-500 p-3 rounded-lg mr-4 flex-shrink-0">
                    <i class="fas fa-question-circle text-white text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Besoin d'aide pour naviguer ?</h3>
                    <p class="text-gray-600 mb-4">
                        Si vous ne trouvez pas ce que vous cherchez, n'hésitez pas à utiliser notre formulaire de contact 
                        ou à consulter notre FAQ.
                    </p>
                    <a href="{{ route('site.contact') }}" 
                       class="inline-flex items-center px-6 py-3 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors duration-200 font-medium">
                        <i class="fas fa-envelope mr-2"></i>
                        Contactez-nous
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
