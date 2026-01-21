@extends('layouts.iri')

@section('title', 'Partenariats & Collaborations - Programme Gouvernance des Ressources Naturelles')

@section('content')
<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        
@section('breadcrumb')
    <x-breadcrumb-overlay :items="[
        ['title' => 'Partenariats', 'url' => null]
    ]" />
@endsection
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                Partenariats & Collaborations
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed drop-shadow-lg">
                Rejoignez notre réseau de chercheurs, institutions et organisations partenaires pour amplifier notre impact
            </p>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Notre Réseau de Partenaires
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Un écosystème collaboratif qui favorise l'innovation et l'impact
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                <!-- Universités Partenaires -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 text-center">
                    <div class="bg-blue-500 p-4 rounded-xl inline-block mb-4">
                        <i class="fas fa-university text-white text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-blue-900 mb-2">{{ $partnershipStats['universites_partenaires'] }}</h3>
                    <p class="text-blue-700 font-medium">Universités Partenaires</p>
                </div>

                <!-- Organisations Collaboratrices -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 text-center">
                    <div class="bg-green-500 p-4 rounded-xl inline-block mb-4">
                        <i class="fas fa-building text-white text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-green-900 mb-2">{{ $partnershipStats['organisations_collaboratrices'] }}</h3>
                    <p class="text-green-700 font-medium">Organisations Collaboratrices</p>
                </div>

                <!-- Chercheurs Affiliés -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-8 text-center">
                    <div class="bg-purple-500 p-4 rounded-xl inline-block mb-4">
                        <i class="fas fa-user-graduate text-white text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-purple-900 mb-2">{{ $partnershipStats['chercheurs_affilies'] }}</h3>
                    <p class="text-purple-700 font-medium">Chercheurs Affiliés</p>
                </div>

                <!-- Projets Collaboratifs -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-8 text-center">
                    <div class="bg-orange-500 p-4 rounded-xl inline-block mb-4">
                        <i class="fas fa-project-diagram text-white text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-orange-900 mb-2">{{ $partnershipStats['projets_collaboratifs'] }}</h3>
                    <p class="text-orange-700 font-medium">Projets Collaboratifs</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Types de Partenariats -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Types de Collaborations
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Découvrez les différentes façons de collaborer avec le programme GRN-UCBC
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Chercheur Affilié -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div class="bg-iri-primary/10 p-3 rounded-xl">
                            <i class="fas fa-user-graduate text-iri-primary text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 ml-4">Chercheur Affilié</h3>
                    </div>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-500 text-lg mt-1"></i>
                            <p class="text-gray-700">Accès aux ressources de recherche du programme GRN-UCBC</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-500 text-lg mt-1"></i>
                            <p class="text-gray-700">Collaboration sur des projets de recherche</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-500 text-lg mt-1"></i>
                            <p class="text-gray-700">Co-publication d'articles scientifiques</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-500 text-lg mt-1"></i>
                            <p class="text-gray-700">Participation aux séminaires et formations</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-500 text-lg mt-1"></i>
                            <p class="text-gray-700">Utilisation du titre d'affiliation institutionnelle</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h4 class="font-semibold text-gray-900 mb-2">Critères d'éligibilité :</h4>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>• Doctorat (PhD) ou Maitrise (Msc, Ma...) dans un domaine pertinent</li>
                            <li>• Expérience de recherche démontrée</li>
                            <li>• Alignement avec les valeurs de <a href="https://congoinitiative.org/" class="text-light-green bold" target="blank">CI-UCBC</a></li>
                            <li>• Engagement à contribuer activement</li>
                        </ul>
                    </div>

                    <a href="mailto:iri@ucbc.org?subject=Demande d'affiliation - Chercheur&body=Bonjour,%0D%0A%0D%0AJe souhaite soumettre ma candidature pour devenir chercheur affilié à l'IRI-UCBC.%0D%0A%0D%0AMes coordonnées :%0D%0ANom : %0D%0AInstitution actuelle : %0D%0ADomaine de recherche : %0D%0ATéléphone : %0D%0A%0D%0ACordialement," 
                       class="inline-flex items-center bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-accent text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-envelope mr-2"></i>
                        Devenir Chercheur Affilié
                    </a>
                </div>

                <!-- Partenaire Technique -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div class="bg-iri-secondary/10 p-3 rounded-xl">
                            <i class="fas fa-handshake text-iri-secondary text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 ml-4">Partenaire Technique</h3>
                    </div>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-500 text-lg mt-1"></i>
                            <p class="text-gray-700">Collaboration sur des projets spécialisés</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-500 text-lg mt-1"></i>
                            <p class="text-gray-700">Partage d'expertise technique</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-500 text-lg mt-1"></i>
                            <p class="text-gray-700">Accès à des opportunités de financement</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-500 text-lg mt-1"></i>
                            <p class="text-gray-700">Formations croisées et échange de personnel</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-500 text-lg mt-1"></i>
                            <p class="text-gray-700">Visibilité conjointe dans les publications</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h4 class="font-semibold text-gray-900 mb-2">Types d'organisations :</h4>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>• ONG et organisations de développement</li>
                            <li>• Institutions gouvernementales</li>
                            <li>• Entreprises sociales et startups</li>
                            <li>• Organisations internationales</li>
                        </ul>
                    </div>

                    <a href="mailto:iri@ucbc.org?subject=Demande de partenariat technique&body=Bonjour,%0D%0A%0D%0ANous souhaitons explorer un partenariat technique avec l'IRI-UCBC.%0D%0A%0D%0AInformations sur notre organisation :%0D%0ANom de l'organisation : %0D%0ASecteur d'activité : %0D%0APersonne de contact : %0D%0AEmail : %0D%0ATéléphone : %0D%0A%0D%0AType de collaboration envisagée : %0D%0A%0D%0ACordialement," 
                       class="inline-flex items-center bg-gradient-to-r from-iri-secondary to-iri-accent hover:from-iri-accent hover:to-iri-primary text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-envelope mr-2"></i>
                        Proposer un Partenariat
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Partenariats Institutionnels -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Partenariats Institutionnels
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Nous développons également des partenariats stratégiques avec des institutions académiques et de recherche
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Universités -->
                <div class="text-center">
                    <div class="bg-blue-100 p-6 rounded-full inline-block mb-4">
                        <i class="fas fa-university text-blue-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Universités</h3>
                    <p class="text-gray-700 mb-4">
                        Accords de coopération académique, échanges d'étudiants et programmes de recherche conjoints
                    </p>
                    <a href="mailto:iri@ucbc.org?subject=Partenariat Universitaire" 
                       class="text-blue-600 hover:text-blue-800 font-medium">
                        En savoir plus →
                    </a>
                </div>

                <!-- Centres de Recherche -->
                <div class="text-center">
                    <div class="bg-green-100 p-6 rounded-full inline-block mb-4">
                        <i class="fas fa-microscope text-green-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Centres de Recherche</h3>
                    <p class="text-gray-700 mb-4">
                        Collaboration sur des projets de recherche, partage d'infrastructures et publications conjointes
                    </p>
                    <a href="mailto:iri@ucbc.org?subject=Partenariat Centre de Recherche" 
                       class="text-green-600 hover:text-green-800 font-medium">
                        En savoir plus →
                    </a>
                </div>

                <!-- Organisations Internationales -->
                <div class="text-center">
                    <div class="bg-purple-100 p-6 rounded-full inline-block mb-4">
                        <i class="fas fa-globe text-purple-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Organisations Internationales</h3>
                    <p class="text-gray-700 mb-4">
                        Programmes de développement, initiatives régionales et projets à impact continental
                    </p>
                    <a href="mailto:iri@ucbc.org?subject=Partenariat International" 
                       class="text-purple-600 hover:text-purple-800 font-medium">
                        En savoir plus →
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-gradient-to-r from-iri-primary to-iri-secondary">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Prêt à Collaborer avec Nous ?
            </h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                Contactez notre équipe des partenariats pour discuter des opportunités de collaboration qui correspondent à vos objectifs
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="mailto:iri@ucbc.org" 
                   class="inline-flex items-center bg-white text-iri-primary font-bold py-3 px-8 rounded-xl hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-envelope mr-2"></i>
                    Contactez-nous
                </a>
                <a href="{{ route('site.work-with-us') }}" 
                   class="inline-flex items-center border-2 border-white text-white font-bold py-3 px-8 rounded-xl hover:bg-white hover:text-iri-primary transition-all duration-300">
                    <i class="fas fa-briefcase mr-2"></i>
                    Voir les Offres d'Emploi
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
