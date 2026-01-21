@extends('layouts.iri')

@section('title', 'Sharing The Land - Notre Approche')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-accent via-iri-gold to-iri-primary py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        
        @section('breadcrumb')
            <x-breadcrumb-overlay :items="[
                ['title' => 'Sharing The Land', 'url' => null]
            ]" />
        @endsection
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                <span class="text-iri-gold">Sharing</span> The <span class="text-iri-gold">Land</span>
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-4xl mx-auto leading-relaxed drop-shadow-lg">
                Notre approche méthodologique pour transformer la gouvernance foncière en République Démocratique du Congo
            </p>
        </div>
    </section>

    <!-- Présentation de l'approche -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        Qu'est-ce que <span class="text-iri-accent">Sharing The Land</span> ?
                    </h2>
                    <div class="prose prose-lg text-gray-700 space-y-6">
                        <p>
                            <strong>Sharing The Land (STL)</strong> est l'approche méthodologique centrale du programme GRN. Elle vise à renforcer la gouvernance foncière en RDC par la participation communautaire, l'inclusion, la transparence et l'usage des technologies.
                        </p>
                        <p>
                            Cette approche innovante combine recherche scientifique, engagement communautaire, technologies SIG et plaidoyer politique pour garantir aux communautés un accès sécurisé, équitable et durable aux ressources naturelles.
                        </p>
                        <p>
                            Depuis 2015, STL a transformé la façon dont nous abordons les questions foncières, en plaçant les communautés au centre des processus de décision.
                        </p>
                    </div>
                </div>
                <div class="relative">
                    <img src="{{ asset('assets/img/stl-approach.jpg') }}" 
                         alt="Approche Sharing The Land" 
                         class="w-full h-auto rounded-2xl shadow-2xl">
                    <div class="absolute inset-0 bg-gradient-to-t from-iri-primary/20 to-transparent rounded-2xl"></div>
                </div>
            </div>

            <!-- Principes fondamentaux -->
            <div class="mb-20">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-12 text-center">
                    Principes Fondamentaux
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-xl flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Participation communautaire</h3>
                        <p class="text-gray-600 text-center">
                            Les communautés sont au cœur de tous les processus de décision concernant leurs terres et ressources.
                        </p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-iri-accent to-iri-gold rounded-xl flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-balance-scale text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Complémentarité droit coutumier / droit formel</h3>
                        <p class="text-gray-600 text-center">
                            Harmonisation des systèmes juridiques traditionnels avec la législation moderne.
                        </p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-laptop text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Technologie locale et accessible</h3>
                        <p class="text-gray-600 text-center">
                            Utilisation d'outils technologiques adaptés et accessibles aux communautés locales.
                        </p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-purple-600 rounded-xl flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-female text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Approche genre et inclusion</h3>
                        <p class="text-gray-600 text-center">
                            Attention particulière aux femmes, jeunes et groupes marginalisés dans l'accès aux ressources.
                        </p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-handshake text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Dialogue et co-construction</h3>
                        <p class="text-gray-600 text-center">
                            Collaboration étroite avec les autorités et institutions locales.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Méthodologie STL -->
            <div class="mb-20">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-12 text-center">
                    Méthodologie STL
                </h2>
                
                <div class="space-y-12">
                    <!-- Étape 1 -->
                    <div class="flex flex-col lg:flex-row items-center gap-12">
                        <div class="lg:w-1/2">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-full flex items-center justify-center mr-4">
                                    <span class="text-white font-bold text-xl">1</span>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Cartographie participative</h3>
                            </div>
                            <p class="text-gray-700 text-lg">
                                Les communautés participent activement à la cartographie de leurs terres en utilisant des technologies SIG accessibles. Cette étape permet de documenter les droits fonciers existants et les limites territoriales.
                            </p>
                        </div>
                        <div class="lg:w-1/2">
                            <div class="bg-gradient-to-br from-iri-primary/10 to-iri-secondary/10 p-8 rounded-2xl">
                                <i class="fas fa-map-marked-alt text-6xl text-iri-primary mb-4"></i>
                                <ul class="space-y-2 text-gray-700">
                                    <li>✓ Formation des communautés aux outils SIG</li>
                                    <li>✓ Collecte de données géospatiales</li>
                                    <li>✓ Validation communautaire des cartes</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Étape 2 -->
                    <div class="flex flex-col lg:flex-row-reverse items-center gap-12">
                        <div class="lg:w-1/2">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-iri-accent to-iri-gold rounded-full flex items-center justify-center mr-4">
                                    <span class="text-white font-bold text-xl">2</span>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Documentation des droits</h3>
                            </div>
                            <p class="text-gray-700 text-lg">
                                Numérisation et formalisation des droits fonciers coutumiers et formels. Cette phase inclut la création de registres fonciers coutumiers reconnus officiellement.
                            </p>
                        </div>
                        <div class="lg:w-1/2">
                            <div class="bg-gradient-to-br from-iri-accent/10 to-iri-gold/10 p-8 rounded-2xl">
                                <i class="fas fa-scroll text-6xl text-iri-accent mb-4"></i>
                                <ul class="space-y-2 text-gray-700">
                                    <li>✓ Registres fonciers coutumiers</li>
                                    <li>✓ Certificats de droits d'usage</li>
                                    <li>✓ Base de données numériques</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Étape 3 -->
                    <div class="flex flex-col lg:flex-row items-center gap-12">
                        <div class="lg:w-1/2">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-white font-bold text-xl">3</span>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Renforcement des capacités</h3>
                            </div>
                            <p class="text-gray-700 text-lg">
                                Formation des acteurs locaux, des autorités traditionnelles et des institutions sur la gouvernance foncière participative et l'utilisation des outils technologiques.
                            </p>
                        </div>
                        <div class="lg:w-1/2">
                            <div class="bg-gradient-to-br from-green-50 to-teal-50 p-8 rounded-2xl">
                                <i class="fas fa-graduation-cap text-6xl text-green-600 mb-4"></i>
                                <ul class="space-y-2 text-gray-700">
                                    <li>✓ Formation aux technologies SIG</li>
                                    <li>✓ Capacitation en médiation foncière</li>
                                    <li>✓ Leadership communautaire</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Étape 4 -->
                    <div class="flex flex-col lg:flex-row-reverse items-center gap-12">
                        <div class="lg:w-1/2">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-white font-bold text-xl">4</span>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900">Dialogue multi-acteurs</h3>
                            </div>
                            <p class="text-gray-700 text-lg">
                                Facilitation du dialogue entre les différents acteurs : communautés, autorités traditionnelles, administration foncière, société civile et secteur privé.
                            </p>
                        </div>
                        <div class="lg:w-1/2">
                            <div class="bg-gradient-to-br from-blue-50 to-purple-50 p-8 rounded-2xl">
                                <i class="fas fa-comments text-6xl text-blue-600 mb-4"></i>
                                <ul class="space-y-2 text-gray-700">
                                    <li>✓ Tables rondes multi-acteurs</li>
                                    <li>✓ Mécanismes de médiation</li>
                                    <li>✓ Accords de collaboration</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résultats et impact -->
            <div class="bg-gradient-to-r from-iri-primary via-iri-accent to-iri-gold p-8 rounded-2xl text-white mb-20">
                <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center">
                    Impact de l'approche STL
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center">
                        <div class="text-4xl font-bold mb-2">50+</div>
                        <div class="text-white/90">Communautés accompagnées</div>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center">
                        <div class="text-4xl font-bold mb-2">15,000+</div>
                        <div class="text-white/90">Hectares cartographiés</div>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center">
                        <div class="text-4xl font-bold mb-2">500+</div>
                        <div class="text-white/90">Femmes formées</div>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center">
                        <div class="text-4xl font-bold mb-2">25+</div>
                        <div class="text-white/90">Conflits fonciers résolus</div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                    Rejoignez l'approche STL
                </h2>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Participez à la transformation de la gouvernance foncière en République Démocratique du Congo
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ url('/contact') }}" 
                       class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-accent text-white font-bold rounded-lg transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-envelope mr-2"></i>
                        Nous Contacter
                    </a>
                    <a href="{{ url('/projets') }}" 
                       class="inline-flex items-center px-8 py-4 border-2 border-iri-primary text-iri-primary hover:bg-iri-primary hover:text-white font-bold rounded-lg transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-eye mr-2"></i>
                        Voir nos projets
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
