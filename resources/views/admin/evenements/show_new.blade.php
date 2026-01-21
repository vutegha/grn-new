@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <a href="{{ route('admin.evenements.index') }}" class="text-white/70 hover:text-white">Événements</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">{{ Str::limit($evenement->titre, 30) }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- En-tête de l'événement -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-white flex items-center">
                                <i class="fas fa-calendar-alt mr-3"></i>
                                {{ $evenement->titre }}
                            </h1>
                            <div class="flex items-center mt-2 space-x-4">
                                @if($evenement->type)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white/20 text-white">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ ucfirst($evenement->type) }}
                                    </span>
                                @endif
                                
                                @if($evenement->statut === 'publie')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-500 text-white">
                                        <i class="fas fa-check mr-1"></i>Publié
                                    </span>
                                @elseif($evenement->statut === 'brouillon')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-500 text-white">
                                        <i class="fas fa-edit mr-1"></i>Brouillon
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-500 text-white">
                                        <i class="fas fa-archive mr-1"></i>Archivé
                                    </span>
                                @endif

                                @if($evenement->en_vedette)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-400 text-gray-900">
                                        <i class="fas fa-star mr-1"></i>En vedette
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.evenements.edit', $evenement) }}" 
                               class="inline-flex items-center px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all duration-200">
                                <i class="fas fa-edit mr-2"></i>
                                Modifier
                            </a>
                            <a href="{{ route('admin.evenements.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all duration-200">
                                <i class="fas fa-list mr-2"></i>
                                Liste
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    @if($evenement->image)
                    <div class="mb-6">
                        <img src="{{ asset('storage/' . $evenement->image) }}" 
                             alt="{{ $evenement->titre }}" 
                             class="w-full h-64 object-cover rounded-lg border border-gray-200 shadow-sm">
                    </div>
                    @endif
                    
                    <div class="space-y-6">
                        @if($evenement->resume)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-clipboard-list mr-2 text-iri-primary"></i>
                                Résumé
                            </h3>
                            <div class="p-4 bg-gradient-to-r from-iri-accent/10 to-iri-gold/10 rounded-lg border border-iri-accent/20">
                                <p class="text-gray-700 italic">{{ $evenement->resume }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($evenement->description)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-align-left mr-2 text-iri-primary"></i>
                                Description détaillée
                            </h3>
                            <div class="prose max-w-none">
                                <div class="text-gray-700 leading-relaxed p-4 bg-gray-50 rounded-lg border">
                                    {!! nl2br(e($evenement->description)) !!}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations détaillées -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Informations détaillées
                    </h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date et heure -->
                        @if($evenement->date_evenement)
                        <div class="flex items-start p-4 rounded-lg border border-blue-200 bg-blue-50">
                            <i class="fas fa-calendar-alt mr-3 text-blue-600 mt-1"></i>
                            <div>
                                <span class="font-medium text-blue-800">Date et heure :</span>
                                <p class="text-blue-700 mt-1">
                                    {{ \Carbon\Carbon::parse($evenement->date_evenement)->format('d/m/Y à H:i') }}
                                </p>
                            </div>
                        </div>
                        @endif

                        <!-- Date limite d'inscription -->
                        @if($evenement->date_limite_inscription)
                        <div class="flex items-start p-4 rounded-lg border border-orange-200 bg-orange-50">
                            <i class="fas fa-clock mr-3 text-orange-600 mt-1"></i>
                            <div>
                                <span class="font-medium text-orange-800">Date limite d'inscription :</span>
                                <p class="text-orange-700 mt-1">
                                    {{ \Carbon\Carbon::parse($evenement->date_limite_inscription)->format('d/m/Y à H:i') }}
                                </p>
                            </div>
                        </div>
                        @endif

                        <!-- Lieu -->
                        @if($evenement->lieu)
                        <div class="flex items-start p-4 rounded-lg border border-green-200 bg-green-50">
                            <i class="fas fa-map-marker-alt mr-3 text-green-600 mt-1"></i>
                            <div>
                                <span class="font-medium text-green-800">Lieu :</span>
                                <p class="text-green-700 mt-1">{{ $evenement->lieu }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Adresse -->
                        @if($evenement->adresse)
                        <div class="flex items-start p-4 rounded-lg border border-purple-200 bg-purple-50">
                            <i class="fas fa-location-arrow mr-3 text-purple-600 mt-1"></i>
                            <div>
                                <span class="font-medium text-purple-800">Adresse :</span>
                                <p class="text-purple-700 mt-1">{{ $evenement->adresse }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Organisateur -->
                        @if($evenement->organisateur)
                        <div class="flex items-start p-4 rounded-lg border border-indigo-200 bg-indigo-50">
                            <i class="fas fa-user-tie mr-3 text-indigo-600 mt-1"></i>
                            <div>
                                <span class="font-medium text-indigo-800">Organisateur :</span>
                                <p class="text-indigo-700 mt-1">{{ $evenement->organisateur }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Places disponibles -->
                        @if($evenement->inscription_requise && $evenement->places_disponibles)
                        <div class="flex items-start p-4 rounded-lg border border-teal-200 bg-teal-50">
                            <i class="fas fa-users mr-3 text-teal-600 mt-1"></i>
                            <div>
                                <span class="font-medium text-teal-800">Places disponibles :</span>
                                <p class="text-teal-700 mt-1">{{ $evenement->places_disponibles }} places</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Contact -->
                    @if($evenement->contact_email || $evenement->contact_telephone)
                    <div class="mt-6 p-4 rounded-lg border border-gray-200 bg-gray-50">
                        <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-address-book mr-2 text-gray-600"></i>
                            Informations de contact
                        </h4>
                        <div class="space-y-2">
                            @if($evenement->contact_email)
                            <div class="flex items-center">
                                <i class="fas fa-envelope mr-3 text-gray-500"></i>
                                <a href="mailto:{{ $evenement->contact_email }}" 
                                   class="text-iri-primary hover:text-iri-secondary">
                                    {{ $evenement->contact_email }}
                                </a>
                            </div>
                            @endif
                            
                            @if($evenement->contact_telephone)
                            <div class="flex items-center">
                                <i class="fas fa-phone mr-3 text-gray-500"></i>
                                <a href="tel:{{ $evenement->contact_telephone }}" 
                                   class="text-iri-primary hover:text-iri-secondary">
                                    {{ $evenement->contact_telephone }}
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- URL du rapport -->
                    @if($evenement->rapport_url)
                    <div class="mt-6 p-4 rounded-lg border border-red-200 bg-red-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file-pdf mr-3 text-red-600"></i>
                                <span class="font-medium text-red-800">Rapport de l'événement</span>
                            </div>
                            <a href="{{ $evenement->rapport_url }}" target="_blank" 
                               class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Consulter
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-indigo-600">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-bolt mr-3"></i>
                        Actions rapides
                    </h3>
                </div>
                
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.evenements.edit', $evenement) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier l'événement
                    </a>
                    
                    <a href="{{ route('admin.evenements.create') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Nouvel événement
                    </a>
                    
                    <a href="{{ route('admin.evenements.index') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                        <i class="fas fa-list mr-2"></i>
                        Liste des événements
                    </a>
                </div>
            </div>

            <!-- Informations système -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-500 to-gray-600">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-info mr-3"></i>
                        Informations système
                    </h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between py-2 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-600">ID :</span>
                        <span class="text-sm text-gray-800">{{ $evenement->id }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-600">Créé le :</span>
                        <span class="text-sm text-gray-800">{{ $evenement->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-600">Modifié le :</span>
                        <span class="text-sm text-gray-800">{{ $evenement->updated_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm font-medium text-gray-600">Statut :</span>
                        <span class="text-sm">
                            @if($evenement->statut === 'publie')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Publié
                                </span>
                            @elseif($evenement->statut === 'brouillon')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-edit mr-1"></i>Brouillon
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-archive mr-1"></i>Archivé
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Paramètres -->
            @if($evenement->inscription_requise || $evenement->en_vedette)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-orange-600">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-cogs mr-3"></i>
                        Paramètres spéciaux
                    </h3>
                </div>
                
                <div class="p-6 space-y-3">
                    @if($evenement->inscription_requise)
                    <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <i class="fas fa-user-check text-blue-600 mr-3"></i>
                        <span class="text-sm font-medium text-blue-800">Inscription requise</span>
                    </div>
                    @endif
                    
                    @if($evenement->en_vedette)
                    <div class="flex items-center p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <i class="fas fa-star text-yellow-600 mr-3"></i>
                        <span class="text-sm font-medium text-yellow-800">Événement en vedette</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- SEO -->
            @if($evenement->meta_title || $evenement->meta_description)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-search mr-3"></i>
                        Référencement (SEO)
                    </h3>
                </div>
                
                <div class="p-6 space-y-4">
                    @if($evenement->meta_title)
                    <div>
                        <span class="text-sm font-medium text-gray-600">Titre SEO :</span>
                        <p class="text-sm text-gray-800 mt-1">{{ $evenement->meta_title }}</p>
                    </div>
                    @endif
                    
                    @if($evenement->meta_description)
                    <div>
                        <span class="text-sm font-medium text-gray-600">Description SEO :</span>
                        <p class="text-sm text-gray-800 mt-1">{{ $evenement->meta_description }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
