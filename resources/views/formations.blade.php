@extends('layouts.iri')

@section('title', 'Formation et Renforcement de Capacités - GRN-UCBC')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        
        @section('breadcrumb')
            <x-breadcrumb-overlay :items="[
                ['title' => 'Formation et Capacités', 'url' => null]
            ]" />
        @endsection
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                Formation & <span class="text-iri-gold">Renforcement</span> de Capacités
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-4xl mx-auto leading-relaxed drop-shadow-lg">
                Développer les compétences locales pour une gouvernance foncière participative et durable
            </p>
        </div>
    </section>

    <!-- Programme de formation -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Faculté de Land Management -->
            <div class="mb-20">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        Faculté de <span class="text-iri-accent">Land Management</span> à l'UCBC
                    </h2>
                    <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                        Notre programme de formation académique pour former la prochaine génération d'experts en gouvernance foncière
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <div class="prose prose-lg text-gray-700 space-y-6">
                            <p>
                                La Faculté de Land Management de l'UCBC est une initiative unique en République Démocratique du Congo, dédiée à la formation d'experts en gouvernance foncière, cartographie participative et gestion des ressources naturelles.
                            </p>
                            <div class="bg-iri-primary/5 p-6 rounded-xl">
                                <h3 class="text-xl font-bold text-iri-primary mb-4">Programmes offerts :</h3>
                                <ul class="space-y-2">
                                    <li class="flex items-center">
                                        <i class="fas fa-graduation-cap text-iri-accent mr-3"></i>
                                        <span>Licence en Gestion Foncière et Aménagement du Territoire</span>
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-graduation-cap text-iri-accent mr-3"></i>
                                        <span>Master en Gouvernance des Ressources Naturelles</span>
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-graduation-cap text-iri-accent mr-3"></i>
                                        <span>Certificats professionnels en SIG et cartographie</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <img src="{{ asset('assets/img/faculty-land-management.jpg') }}" 
                             alt="Faculté Land Management" 
                             class="w-full h-auto rounded-2xl shadow-2xl">
                        <div class="absolute inset-0 bg-gradient-to-t from-iri-primary/20 to-transparent rounded-2xl"></div>
                    </div>
                </div>
            </div>

            <!-- Centres de formation continue -->
            <div class="mb-20">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-12 text-center">
                    Centres de Formation Continue
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-xl flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-map text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Outils Fonciers</h3>
                        <p class="text-gray-600 text-center mb-6">
                            Formation sur les instruments juridiques et techniques de la gouvernance foncière.
                        </p>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>• Loi foncière congolaise</li>
                            <li>• Procédures d'acquisition foncière</li>
                            <li>• Droits coutumiers et formels</li>
                            <li>• Gestion des conflits fonciers</li>
                        </ul>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-iri-accent to-iri-gold rounded-xl flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-handshake text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Médiation Foncière</h3>
                        <p class="text-gray-600 text-center mb-6">
                            Techniques de médiation et résolution pacifique des conflits fonciers.
                        </p>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>• Techniques de négociation</li>
                            <li>• Médiation communautaire</li>
                            <li>• Gestion des tensions</li>
                            <li>• Dialogue interculturel</li>
                        </ul>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-laptop text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">SIG et Technologie</h3>
                        <p class="text-gray-600 text-center mb-6">
                            Maîtrise des systèmes d'information géographique et outils technologiques.
                        </p>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>• Cartographie numérique</li>
                            <li>• Utilisation GPS</li>
                            <li>• Bases de données foncières</li>
                            <li>• Applications mobiles</li>
                        </ul>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Leadership Communautaire</h3>
                        <p class="text-gray-600 text-center mb-6">
                            Développement des compétences de leadership pour les acteurs communautaires.
                        </p>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>• Gouvernance participative</li>
                            <li>• Animation communautaire</li>
                            <li>• Plaidoyer et mobilisation</li>
                            <li>• Gestion de projets</li>
                        </ul>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-purple-600 rounded-xl flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-female text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Genre et Inclusion</h3>
                        <p class="text-gray-600 text-center mb-6">
                            Formation spécialisée sur l'approche genre dans la gouvernance foncière.
                        </p>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>• Droits fonciers des femmes</li>
                            <li>• Participation des jeunes</li>
                            <li>• Inclusion des marginalisés</li>
                            <li>• Autonomisation économique</li>
                        </ul>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mb-6 mx-auto">
                            <i class="fas fa-certificate text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Certification Professionnelle</h3>
                        <p class="text-gray-600 text-center mb-6">
                            Programmes de certification reconnus pour les professionnels du secteur.
                        </p>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>• Expert en gouvernance foncière</li>
                            <li>• Spécialiste SIG</li>
                            <li>• Médiateur foncier certifié</li>
                            <li>• Formateur STL</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Méthodes de formation -->
            <div class="mb-20">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-12 text-center">
                    Nos Méthodes de Formation
                </h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-chalkboard-teacher text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Formation Pratique</h3>
                                <p class="text-gray-600">Apprentissage par la pratique avec des cas réels et des exercices sur le terrain.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-iri-accent to-iri-gold rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Apprentissage Participatif</h3>
                                <p class="text-gray-600">Méthodes interactives favorisant l'échange d'expériences entre participants.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-laptop text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Formation Digitale</h3>
                                <p class="text-gray-600">Utilisation d'outils numériques et plateformes d'apprentissage en ligne.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-handshake text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Mentorat</h3>
                                <p class="text-gray-600">Accompagnement personnalisé par des experts expérimentés.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-iri-primary/5 to-iri-secondary/5 p-8 rounded-2xl">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Statistiques de Formation</h3>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-iri-primary mb-2">1,200+</div>
                                <div class="text-sm text-gray-600">Personnes formées</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-iri-accent mb-2">450+</div>
                                <div class="text-sm text-gray-600">Femmes capacitées</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600 mb-2">85%</div>
                                <div class="text-sm text-gray-600">Taux de réussite</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600 mb-2">25+</div>
                                <div class="text-sm text-gray-600">Communautés</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center">
                <div class="bg-gradient-to-r from-iri-primary via-iri-accent to-iri-gold p-8 rounded-2xl text-white">
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">
                        Rejoignez nos Programmes de Formation
                    </h2>
                    <p class="text-xl mb-8 opacity-90 max-w-3xl mx-auto">
                        Développez vos compétences en gouvernance foncière et contribuez à la transformation de nos communautés
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ url('/contact') }}" 
                           class="inline-flex items-center px-8 py-4 bg-white text-iri-primary font-bold rounded-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            S'inscrire aux formations
                        </a>
                        <a href="{{ url('/contact') }}" 
                           class="inline-flex items-center px-8 py-4 border-2 border-white text-white font-bold rounded-lg hover:bg-white hover:text-iri-primary transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-info-circle mr-2"></i>
                            Plus d'informations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
