@extends('layouts.iri')

@section('title', 'Travailler avec nous - Programme Gouvernance des Ressources Naturelles')

@section('content')
<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        
@section('breadcrumb')
    <x-breadcrumb-overlay :items="[
        ['title' => 'Travailler avec nous', 'url' => null]
    ]" />
@endsection
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                Rejoignez Notre Équipe
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed drop-shadow-lg">
                Participez à notre mission de transformation de la gouvernance foncière pour un impact positif sur nos communautés
            </p>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-iri-primary/5 to-iri-secondary/5 rounded-3xl p-8 md:p-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                            Pourquoi travailler avec le programme GRN-UCBC ?
                        </h2>
                        <div class="space-y-4 text-gray-700">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-iri-primary text-xl mt-1"></i>
                                <p class="text-lg">
                                    <strong>Impact significatif :</strong> Contribuez directement au développement des communautés locales
                                </p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-iri-primary text-xl mt-1"></i>
                                <p class="text-lg">
                                    <strong>Environnement collaboratif :</strong> Travaillez avec des experts passionnés et engagés
                                </p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-iri-primary text-xl mt-1"></i>
                                <p class="text-lg">
                                    <strong>Développement professionnel :</strong> Opportunités de formation et d'évolution continue
                                </p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-iri-primary text-xl mt-1"></i>
                                <p class="text-lg">
                                    <strong>Innovation :</strong> Participez à des projets de recherche de pointe
                                </p>
                            </div>
                        </div>
                        <div class="mt-8 space-y-4">
                            <a href="mailto:iri@ucbc.org" 
                               class="inline-flex items-center bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-accent text-white font-bold py-3 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <i class="fas fa-envelope mr-2"></i>
                                Candidature Spontanée
                            </a>
                            
                            <!-- Action pour devenir chercheur affilié ou partenaire -->
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-3">
                                    <i class="fas fa-users text-iri-primary mr-2"></i>
                                    Vous êtes chercheur ou organisation ?
                                </p>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="{{ route('site.partenariats') }}" 
                                       class="inline-flex items-center justify-center bg-white border-2 border-iri-primary text-iri-primary hover:bg-iri-primary hover:text-white font-semibold py-2 px-6 rounded-lg transition-all duration-300 text-sm">
                                        <i class="fas fa-user-graduate mr-2"></i>
                                        Devenir Chercheur Affilié
                                    </a>
                                    <a href="{{ route('site.partenariats') }}" 
                                       class="inline-flex items-center justify-center bg-white border-2 border-iri-secondary text-iri-secondary hover:bg-iri-secondary hover:text-white font-semibold py-2 px-6 rounded-lg transition-all duration-300 text-sm">
                                        <i class="fas fa-handshake mr-2"></i>
                                        Partenaire Technique
                                    </a>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ route('site.partenariats') }}" class="text-xs text-gray-500 hover:text-iri-primary transition-colors">
                                        En savoir plus sur nos types de collaborations →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <img src="{{ asset('assets/img/team-collaboration.jpg') }}" 
                             alt="Équipe IRI-UCBC" 
                             class="rounded-2xl shadow-2xl object-cover w-full h-80"
                             onerror="this.src='{{ asset('assets/img/iri.jpg') }}'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-2xl"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Dashboard -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Notre Impact en Chiffres
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Découvrez l'ampleur de notre action et l'impact de notre travail sur le terrain
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Projets Actifs -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-iri-primary/10 p-3 rounded-xl">
                            <i class="fas fa-rocket text-iri-primary text-2xl"></i>
                        </div>
                        <span class="text-sm font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full">Actifs</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($impactStats['projets_actifs']) }}</h3>
                    <p class="text-gray-600 font-medium">Projets en Cours</p>
                </div>

                <!-- Projets Terminés -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-iri-secondary/10 p-3 rounded-xl">
                            <i class="fas fa-check-circle text-iri-secondary text-2xl"></i>
                        </div>
                        <span class="text-sm font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full">Terminés</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($impactStats['projets_termines']) }}</h3>
                    <p class="text-gray-600 font-medium">Projets Réalisés</p>
                </div>

                <!-- Publications -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-indigo-100 p-3 rounded-xl">
                            <i class="fas fa-book text-indigo-600 text-2xl"></i>
                        </div>
                        <span class="text-sm font-medium text-indigo-600 bg-indigo-100 px-2 py-1 rounded-full">Recherche</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($impactStats['publications']) }}</h3>
                    <p class="text-gray-600 font-medium">Publications Scientifiques</p>
                </div>

                <!-- Rapports -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-orange-100 p-3 rounded-xl">
                            <i class="fas fa-file-alt text-orange-600 text-2xl"></i>
                        </div>
                        <span class="text-sm font-medium text-orange-600 bg-orange-100 px-2 py-1 rounded-full">Études</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($impactStats['rapports']) }}</h3>
                    <p class="text-gray-600 font-medium">Rapports d'Étude</p>
                </div>

                <!-- Bénéficiaires -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-iri-accent/10 p-3 rounded-xl">
                            <i class="fas fa-users text-iri-accent text-2xl"></i>
                        </div>
                        <span class="text-sm font-medium text-purple-600 bg-purple-100 px-2 py-1 rounded-full">Impact</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($impactStats['beneficiaires']) }}+</h3>
                    <p class="text-gray-600 font-medium">Bénéficiaires Touchés</p>
                </div>

                <!-- Domaines d'intervention -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-teal-100 p-3 rounded-xl">
                            <i class="fas fa-lightbulb text-teal-600 text-2xl"></i>
                        </div>
                        <span class="text-sm font-medium text-teal-600 bg-teal-100 px-2 py-1 rounded-full">Expertise</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($impactStats['services']) }}</h3>
                    <p class="text-gray-600 font-medium">Domaines d'Intervention</p>
                </div>

                <!-- Actualités -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-rose-100 p-3 rounded-xl">
                            <i class="fas fa-newspaper text-rose-600 text-2xl"></i>
                        </div>
                        <span class="text-sm font-medium text-rose-600 bg-rose-100 px-2 py-1 rounded-full">News</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($impactStats['actualites']) }}</h3>
                    <p class="text-gray-600 font-medium">Actualités Publiées</p>
                </div>

                <!-- Partenaires -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-emerald-100 p-3 rounded-xl">
                            <i class="fas fa-handshake text-emerald-600 text-2xl"></i>
                        </div>
                        <span class="text-sm font-medium text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full">Réseau</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($impactStats['partenaires']) }}+</h3>
                    <p class="text-gray-600 font-medium">Partenaires Actifs</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Job Offers Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Offres d'Emploi Actuelles
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-6">
                    Découvrez les opportunités de carrière disponibles au sein de notre équipe et chez nos partenaires
                </p>
                
                <!-- Statistiques des offres -->
                @if(isset($jobStats))
                <div class="flex flex-wrap justify-center gap-6 mb-8">
                    <div class="bg-gradient-to-r from-iri-primary/10 to-iri-secondary/10 rounded-lg px-4 py-2">
                        <span class="text-sm text-gray-600">Offres actives: </span>
                        <span class="font-bold text-iri-primary">{{ $jobStats['active_offers'] }}</span>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg px-4 py-2">
                        <span class="text-sm text-gray-600">Offres internes: </span>
                        <span class="font-bold text-green-600">{{ $jobStats['internal_offers'] }}</span>
                    </div>
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg px-4 py-2">
                        <span class="text-sm text-gray-600">Offres partenaires: </span>
                        <span class="font-bold text-blue-600">{{ $jobStats['partner_offers'] }}</span>
                    </div>
                </div>
                @endif
            </div>

            <div class="space-y-8">
                @forelse($jobOffers as $job)
                    <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-all duration-300 
                        {{ $job->is_featured ? 'ring-2 ring-iri-primary/20 bg-gradient-to-r from-iri-primary/5 to-transparent' : '' }}" 
                         x-data="{ expanded: false }">
                        
                        <!-- En-tête de l'offre -->
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4 mb-3">
                                    <h3 class="text-2xl font-bold text-gray-900">{{ $job->title }}</h3>
                                    
                                    <!-- Badge type de contrat -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        {{ $job->type === 'temps_plein' ? 'bg-green-100 text-green-800' : 
                                           ($job->type === 'temps_partiel' ? 'bg-blue-100 text-blue-800' : 
                                           ($job->type === 'stage' ? 'bg-orange-100 text-orange-800' : 'bg-purple-100 text-purple-800')) }}">
                                        {{ $job->type === 'temps_plein' ? 'Temps plein' : 
                                           ($job->type === 'temps_partiel' ? 'Temps partiel' : 
                                           ($job->type === 'stage' ? 'Stage' : ucfirst($job->type))) }}
                                    </span>

                                    <!-- Badge source -->
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $job->source === 'internal' ? 'bg-iri-primary/10 text-iri-primary' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $job->source === 'internal' ? 'GRN-UCBC' : ($job->partner_name ?: 'Partenaire') }}
                                    </span>

                                    <!-- Badge featured -->
                                    @if($job->is_featured)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-star mr-1"></i> Mise en avant
                                        </span>
                                    @endif

                                    <!-- Badge statut -->
                                    @if($job->is_expired)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-clock mr-1"></i> Expiré
                                        </span>
                                    @elseif($job->application_deadline && $job->days_until_deadline <= 7)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> {{ $job->days_until_deadline }} jour(s) restant(s)
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="flex flex-wrap items-center space-x-6 text-gray-600 mb-2">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-building text-iri-primary"></i>
                                        <span>{{ $job->department }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-map-marker-alt text-iri-primary"></i>
                                        <span>{{ $job->location }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-calendar text-iri-primary"></i>
                                        <span>Publié {{ $job->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($job->application_deadline)
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-hourglass-end text-iri-primary"></i>
                                            <span>Candidature avant le {{ $job->application_deadline->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Informations sur le salaire -->
                                @if($job->formatted_salary !== 'Non spécifié')
                                    <div class="flex items-center space-x-2 text-gray-600 mb-2">
                                        <i class="fas fa-dollar-sign text-iri-primary"></i>
                                        <span>{{ $job->formatted_salary }}</span>
                                    </div>
                                @endif

                                <!-- Nombre de postes -->
                                @if($job->positions_available > 1)
                                    <div class="flex items-center space-x-2 text-gray-600">
                                        <i class="fas fa-users text-iri-primary"></i>
                                        <span>{{ $job->positions_available }} postes disponibles</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex items-center space-x-4 mt-4 lg:mt-0">
                                <button @click="expanded = !expanded" 
                                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                                    <span x-text="expanded ? 'Moins de détails' : 'Plus de détails'"></span>
                                    <i class="fas fa-chevron-down ml-2 transition-transform" 
                                       :class="{ 'rotate-180': expanded }"></i>
                                </button>
                                
                                @if(!$job->is_expired)
                                    <a href="{{ route('site.job.apply', $job->slug) }}" 
                                       class="bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-accent text-white font-medium py-2 px-6 rounded-lg transition-all duration-300 transform hover:scale-105">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Postuler en ligne
                                    </a>
                                @else
                                    <span class="bg-gray-300 text-gray-600 font-medium py-2 px-6 rounded-lg cursor-not-allowed">
                                        <i class="fas fa-times mr-2"></i>
                                        Offre expirée
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Détails de l'offre (expandable) -->
                        <div x-show="expanded" x-transition class="border-t border-gray-200 pt-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Description du poste</h4>
                                    <div class="text-gray-700 leading-relaxed prose prose-sm max-w-none">
                                        {!! nl2br(e($job->description)) !!}
                                    </div>

                                    @if($job->benefits)
                                        <h4 class="text-lg font-semibold text-gray-900 mb-3 mt-6">Avantages</h4>
                                        <div class="text-gray-700 leading-relaxed">
                                            {!! nl2br(e($job->benefits)) !!}
                                        </div>
                                    @endif
                                </div>
                                
                                <div>
                                    @if($job->requirements)
                                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Exigences</h4>
                                        <ul class="space-y-2 mb-6">
                                            @foreach($job->requirements as $requirement)
                                                <li class="flex items-start space-x-2">
                                                    <i class="fas fa-check text-iri-primary mt-1.5 text-sm"></i>
                                                    <span class="text-gray-700">{{ $requirement }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    <!-- Contact -->
                                    @if($job->contact_email || $job->contact_phone)
                                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Contact</h4>
                                        <div class="space-y-2">
                                            @if($job->contact_email)
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-envelope text-iri-primary"></i>
                                                    <a href="mailto:{{ $job->contact_email }}" class="text-iri-primary hover:underline">
                                                        {{ $job->contact_email }}
                                                    </a>
                                                </div>
                                            @endif
                                            @if($job->contact_phone)
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-phone text-iri-primary"></i>
                                                    <a href="tel:{{ $job->contact_phone }}" class="text-iri-primary hover:underline">
                                                        {{ $job->contact_phone }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Document d'appel d'offre -->
                                    @if($job->hasDocumentAppelOffre())
                                        <h4 class="text-lg font-semibold text-gray-900 mb-3 mt-6">Documents à télécharger</h4>
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <div class="bg-iri-primary/10 rounded-full p-2">
                                                        <i class="fas fa-file-pdf text-iri-primary text-lg"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="font-medium text-gray-900">
                                                            {{ $job->document_appel_offre_nom ?: 'Document d\'appel d\'offre' }}
                                                        </h5>
                                                        <p class="text-sm text-gray-500">
                                                            Cliquez pour télécharger le document détaillé de cette offre
                                                        </p>
                                                    </div>
                                                </div>
                                                <a href="{{ route('site.job.download', $job->slug) }}" 
                                                   class="bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-accent text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center space-x-2">
                                                    <i class="fas fa-download"></i>
                                                    <span>Télécharger</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Statistiques -->
                                    <div class="mt-6 pt-4 border-t border-gray-100">
                                        <div class="flex items-center justify-between text-sm text-gray-500">
                                            <span>{{ $job->views_count }} vue(s)</span>
                                            <span>{{ $job->applications_count }} candidature(s)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="bg-gray-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-briefcase text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune offre disponible pour le moment</h3>
                        <p class="text-gray-600 mb-6">
                            Nous n'avons actuellement aucune offre d'emploi ouverte, mais nous sommes toujours à la recherche de talents exceptionnels.
                        </p>
                        <a href="mailto:iri@ucbc.org" 
                           class="inline-flex items-center bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-accent text-white font-medium py-2 px-6 rounded-lg transition-all duration-300">
                            <i class="fas fa-envelope mr-2"></i>
                            Candidature Spontanée
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Contact CTA Section -->
    <section class="py-16 bg-gradient-to-r from-iri-primary via-iri-secondary to-iri-accent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Vous ne trouvez pas ce que vous cherchez ?
            </h2>
            <p class="text-xl text-white/90 mb-8 max-w-3xl mx-auto">
                N'hésitez pas à nous envoyer votre candidature spontanée. Nous sommes toujours intéressés par des profils talentueux et motivés.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                <a href="mailto:iri@ucbc.org" 
                   class="inline-flex items-center bg-white text-iri-primary hover:bg-gray-100 font-bold py-3 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-envelope mr-2"></i>
                    Envoyer une Candidature
                </a>
                <a href="{{ route('site.contact') }}" 
                   class="inline-flex items-center border-2 border-white text-white hover:bg-white hover:text-iri-primary font-bold py-3 px-8 rounded-xl transition-all duration-300">
                    <i class="fas fa-phone mr-2"></i>
                    Nous Contacter
                </a>
            </div>
        </div>
    </section>
</div>

<!-- Alpine.js for interactive components -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
