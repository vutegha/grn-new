@extends('layouts.iri')

@section('title', 'À propos - Programme Gouvernance des Ressources Naturelles')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        
        @section('breadcrumb')
            <x-breadcrumb-overlay :items="[
                ['title' => 'À propos du programme', 'url' => null]
            ]" />
        @endsection
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                Programme Gouvernance des <span class="text-iri-gold">Ressources Naturelles</span>
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-4xl mx-auto leading-relaxed drop-shadow-lg">
                Une initiative de l'Université Chrétienne Bilingue du Congo pour transformer la gouvernance foncière, forestière et minière
            </p>
        </div>
    </section>

    <!-- À propos Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        À propos de nous
                    </h2>
                    <div class="prose prose-lg text-gray-700 space-y-6">
                        <p>
                            Le programme « Gouvernance des Ressources Naturelles » (GRN) est une initiative de l'Université Chrétienne Bilingue du Congo (UCBC).
                        </p>
                        <p>
                            Depuis 2015, à travers le projet phare « Sharing The Land », nous combinons recherche scientifique, engagement communautaire, technologies innovantes et plaidoyer politique pour transformer la gouvernance foncière, forestière et minière dans une approche participative.
                        </p>
                        <p>
                            Notre objectif est de garantir aux communautés, en particulier aux groupes marginalisés (femmes, jeunes, peuples autochtones), un accès sécurisé, équitable et durable aux ressources naturelles.
                        </p>
                    </div>
                </div>
                <div class="relative">
                    <img src="{{ asset('assets/img/stl/stl-2.png') }}" 
                         alt="Sharing The Land" 
                         class="w-full h-auto rounded-2xl shadow-2xl">
                    <div class="absolute inset-0 bg-gradient-to-t from-iri-primary/20 to-transparent rounded-2xl"></div>
                </div>
            </div>

            <!-- Vision & Mission Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-20">
                <!-- Vision UCBC -->
                <div class="bg-gradient-to-r from-iri-accent/5 to-iri-gold/5 rounded-3xl p-8 md:p-12">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                            Vision UCBC
                        </h2>
                        <p class="text-xl text-gray-700 max-w-4xl mx-auto leading-relaxed italic mb-6">
                            Former une nouvelle génération de leaders chrétiens intègres, bilingues et compétents, engagés pour la transformation de la RDC.
                        </p>
                        
                        <div class="text-left">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            </h3>
                            <ul class="space-y-2 text-gray-700">
                                <li class="flex items-start">
                                    <span class="text-iri-accent mr-2">•</span>
                                    <span><strong>Transformation holistique :</strong> spirituelle, intellectuelle, sociale et communautaire</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-iri-accent mr-2">•</span>
                                    <span><strong>Service communautaire :</strong> l'apprentissage est lié aux réalités et besoins de la communauté</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-iri-accent mr-2">•</span>
                                    <span><strong>Leadership par le service :</strong> les diplômés sont formés pour être des serviteurs-leaders</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-iri-accent mr-2">•</span>
                                    <span><strong>Excellence académique :</strong> avec une approche pratique et locale</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-iri-accent mr-2">•</span>
                                    <span><strong>Bilinguisme (français-anglais) :</strong> pour favoriser la collaboration internationale</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-iri-accent mr-2">•</span>
                                    <span><strong>Intégrité et foi chrétienne :</strong> au cœur du curriculum et de la vie universitaire</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Mission du Programme GRN -->
                <div class="bg-gradient-to-r from-iri-primary/5 to-iri-secondary/5 rounded-3xl p-8 md:p-12">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                            Notre Mission
                        </h2>
                        <p class="text-xl text-gray-700 max-w-4xl mx-auto leading-relaxed italic">
                            Accompagner les communautés de la RDC vers une gouvernance foncière, forestière et minière transparente, équitable et participative, garantissant aux générations présentes et futures un accès sécurisé et durable aux ressources naturelles.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Valeurs Section -->
            <div class="mb-20">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-12 text-center">
                    Nos Valeurs
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 bg-gradient-to-br from-iri-accent to-iri-gold rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-balance-scale text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Justice sociale et équité</h3>
                        <p class="text-gray-600">Garantir un accès juste et équitable aux ressources naturelles pour tous.</p>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Inclusion et participation</h3>
                        <p class="text-gray-600">Impliquer toutes les parties prenantes dans les processus de décision.</p>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-eye text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Transparence et responsabilité</h3>
                        <p class="text-gray-600">Promouvoir la transparence dans la gestion des ressources naturelles.</p>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-dove text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Paix et cohésion communautaire</h3>
                        <p class="text-gray-600">Favoriser la paix et l'harmonie entre les communautés.</p>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-lightbulb text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Innovation locale et durable</h3>
                        <p class="text-gray-600">Développer des solutions innovantes adaptées au contexte local.</p>
                    </div>
                </div>
            </div>

            <!-- Secteurs d'intervention -->
            <div class="mb-20">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 text-center">
                    Nos Secteurs d'Intervention
                </h2>
                <p class="text-xl text-gray-600 text-center max-w-4xl mx-auto mb-12">
                    Notre programme s'articule autour de quatre axes majeurs d'intervention pour une transformation systémique
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Politique publique -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-gavel text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">1. Politique publique</h3>
                        </div>
                    </div>

                    <!-- Gouvernance institutionnelle -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-iri-accent to-iri-gold rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-building text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">2. Gouvernance et appui institutionnel</h3>
                        </div>
                    </div>

                    <!-- Forêts, mines et économie -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-tree text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">3. Forêts, mines et économie locale</h3>
                        </div>
                    </div>

                    <!-- Cohésion sociale -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-handshake text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">4. Cohésion sociale et transformation des conflits</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nos initiatives -->
            

            <!-- Approche Sharing The Land -->
            <div class="bg-gradient-to-r from-iri-primary via-iri-accent to-iri-gold p-8 rounded-2xl text-white">
                <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center">
                    Notre approche : Sharing The Land
                </h2>
                <div class="max-w-4xl mx-auto text-center mb-8">
                    <p class="text-xl leading-relaxed opacity-90">
                        Sharing The Land (STL) est l'approche méthodologique centrale du programme GRN. Elle vise à renforcer la gouvernance foncière en RDC par la participation communautaire, l'inclusion, la transparence et l'usage des technologies.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                        <h4 class="text-lg font-bold mb-3">🤝 Participation communautaire</h4>
                        <p class="text-white/90 text-sm">Implication directe des communautés dans les processus de gouvernance foncière</p>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                        <h4 class="text-lg font-bold mb-3">⚖️ Complémentarité droit coutumier / droit formel</h4>
                        <p class="text-white/90 text-sm">Harmonisation des systèmes juridiques traditionnels et modernes</p>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                        <h4 class="text-lg font-bold mb-3">💻 Technologie locale et accessible</h4>
                        <p class="text-white/90 text-sm">Utilisation d'outils technologiques adaptés au contexte local</p>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                        <h4 class="text-lg font-bold mb-3">👥 Approche genre et inclusion</h4>
                        <p class="text-white/90 text-sm">Attention particulière aux femmes, jeunes et groupes marginalisés</p>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                        <h4 class="text-lg font-bold mb-3">🏛️ Dialogue et co-construction</h4>
                        <p class="text-white/90 text-sm">Collaboration étroite avec les autorités et institutions locales</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
