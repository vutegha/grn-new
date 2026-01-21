@extends('layouts.admin')

@section('title', 'Détails Partenaire')
@section('subtitle', $partenaire->nom)

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <a href="{{ route('admin.partenaires.index') }}" 
           class="group inline-flex items-center text-iri-primary hover:text-iri-secondary transition-colors duration-200 font-medium">
            <i class="fas fa-handshake mr-2 group-hover:rotate-12 transition-transform duration-200"></i>
            Partenaires
        </a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <span class="text-iri-gray font-medium flex items-center">
            <i class="fas fa-eye mr-2 text-iri-accent"></i>
            {{ Str::limit($partenaire->nom, 30) }}
        </span>
    </li>
@endsection

@section('content')
<div class="container-fluid px-4 py-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header avec actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl font-bold text-gray-900 mb-2 flex items-center">
                    @if($partenaire->logo)
                        <img src="{{ $partenaire->logo_url }}" 
                             alt="Logo {{ $partenaire->nom }}" 
                             class="w-12 h-12 object-contain rounded-lg border border-gray-200 mr-4">
                    @endif
                    {{ $partenaire->nom }}
                </h1>
                <p class="text-gray-600 flex items-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mr-3
                        @switch($partenaire->type)
                            @case('universite') bg-blue-100 text-blue-800 @break
                            @case('centre_recherche') bg-green-100 text-green-800 @break
                            @case('organisation_internationale') bg-purple-100 text-purple-800 @break
                            @case('ong') bg-orange-100 text-orange-800 @break
                            @case('entreprise') bg-indigo-100 text-indigo-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch">
                        {{ $partenaire->type_libelle }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        @switch($partenaire->statut)
                            @case('actif') bg-green-100 text-green-800 @break
                            @case('inactif') bg-red-100 text-red-800 @break
                            @case('en_negociation') bg-yellow-100 text-yellow-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch">
                        <i class="fas fa-circle mr-1 text-xs
                            @switch($partenaire->statut)
                                @case('actif') text-green-500 @break
                                @case('inactif') text-red-500 @break
                                @case('en_negociation') text-yellow-500 @break
                                @default text-gray-500
                            @endswitch"></i>
                        {{ $partenaire->statut_libelle }}
                    </span>
                </p>
            </div>
            
            <div class="flex space-x-3">
                @can('update', $partenaire)
                <a href="{{ route('admin.partenaires.edit', $partenaire) }}" 
                   class="inline-flex items-center px-6 py-3 bg-iri-accent text-white rounded-lg hover:bg-iri-gold transition-colors duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                @endcan
                
                @can('delete', $partenaire)
                <form action="{{ route('admin.partenaires.destroy', $partenaire) }}" 
                      method="POST" 
                      class="inline-block"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce partenaire ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-trash mr-2"></i>
                        Supprimer
                    </button>
                </form>
                @endcan
                
                <a href="{{ route('admin.partenaires.index') }}" 
                   class="inline-flex items-center px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>

        <!-- Section Modération -->
        @can('moderate', $partenaire)
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-shield-alt text-blue-600 mr-2"></i>
                Modération du partenaire
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Publication/Dépublication -->
                <div class="bg-white rounded-lg p-4 border border-blue-200">
                    <h4 class="font-medium text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-{{ $partenaire->published_at ? 'eye' : 'eye-slash' }} text-blue-600 mr-2"></i>
                        État de publication
                    </h4>
                    <p class="text-sm text-gray-600 mb-3">
                        @if($partenaire->published_at)
                            <span class="text-green-600 font-medium">Publié</span> le {{ $partenaire->published_at->format('d/m/Y à H:i') }}
                        @else
                            <span class="text-red-600 font-medium">Non publié</span>
                        @endif
                    </p>
                    
                    <form action="{{ route('admin.partenaires.toggle-publication', $partenaire) }}" 
                          method="POST" 
                          class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200
                                    @if($partenaire->published_at)
                                        bg-red-100 text-red-700 hover:bg-red-200
                                    @else
                                        bg-green-100 text-green-700 hover:bg-green-200
                                    @endif">
                            <i class="fas fa-{{ $partenaire->published_at ? 'eye-slash' : 'eye' }} mr-2"></i>
                            {{ $partenaire->published_at ? 'Dépublier' : 'Publier' }}
                        </button>
                    </form>
                </div>

                <!-- Changement de statut -->
                <div class="bg-white rounded-lg p-4 border border-blue-200">
                    <h4 class="font-medium text-gray-900 mb-2 flex items-center">
                        <i class="fas fa-edit text-blue-600 mr-2"></i>
                        Changer le statut
                    </h4>
                    <p class="text-sm text-gray-600 mb-3">
                        Statut actuel : <span class="font-medium text-{{ $partenaire->statut == 'actif' ? 'green' : ($partenaire->statut == 'inactif' ? 'red' : 'yellow') }}-600">{{ $partenaire->statut_libelle }}</span>
                    </p>
                    
                    <form action="{{ route('admin.partenaires.update', $partenaire) }}" 
                          method="POST" 
                          class="flex space-x-2">
                        @csrf
                        @method('PATCH')
                        <select name="statut" 
                                class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="actif" {{ $partenaire->statut == 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="inactif" {{ $partenaire->statut == 'inactif' ? 'selected' : '' }}>Inactif</option>
                            <option value="en_negociation" {{ $partenaire->statut == 'en_negociation' ? 'selected' : '' }}>En négociation</option>
                        </select>
                        <button type="submit" 
                                class="inline-flex items-center px-3 py-1 text-sm bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-md transition-colors duration-200">
                            <i class="fas fa-check mr-1"></i>
                            Modifier
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endcan

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                @if($partenaire->description)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-align-left text-iri-primary mr-2"></i>
                        Description
                    </h3>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($partenaire->description)) !!}
                    </div>
                </div>
                @endif

                <!-- Domaines de collaboration -->
                @if($partenaire->domaines_collaboration && count($partenaire->domaines_collaboration) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-project-diagram text-iri-secondary mr-2"></i>
                        Domaines de collaboration
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($partenaire->domaines_collaboration as $domaine)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-primary/10 text-iri-primary border border-iri-primary/20">
                                <i class="fas fa-tag mr-2 text-xs"></i>
                                {{ $domaine }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Message spécifique -->
                @if($partenaire->message_specifique)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-quote-left text-iri-gold mr-2"></i>
                        Message spécifique
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-iri-gold">
                        <blockquote class="text-gray-700 italic">
                            "{{ $partenaire->message_specifique }}"
                        </blockquote>
                    </div>
                </div>
                @endif

                <!-- Informations de contact -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-address-book text-iri-accent mr-2"></i>
                        Informations de contact
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($partenaire->site_web)
                        <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-globe text-blue-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-blue-600 font-medium">Site web</p>
                                <a href="{{ $partenaire->site_web }}" 
                                   target="_blank" 
                                   class="text-sm text-blue-800 hover:underline">
                                    {{ Str::limit($partenaire->site_web, 30) }}
                                    <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($partenaire->email_contact)
                        <div class="flex items-center p-3 bg-green-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-envelope text-green-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-green-600 font-medium">Email</p>
                                <a href="mailto:{{ $partenaire->email_contact }}" 
                                   class="text-sm text-green-800 hover:underline">
                                    {{ $partenaire->email_contact }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($partenaire->telephone)
                        <div class="flex items-center p-3 bg-orange-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-phone text-orange-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-orange-600 font-medium">Téléphone</p>
                                <a href="tel:{{ $partenaire->telephone }}" 
                                   class="text-sm text-orange-800 hover:underline">
                                    {{ $partenaire->telephone }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($partenaire->pays)
                        <div class="flex items-center p-3 bg-red-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-flag text-red-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-red-600 font-medium">Pays</p>
                                <p class="text-sm text-red-800">{{ $partenaire->pays }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if($partenaire->adresse)
                    <div class="mt-4 p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-purple-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-purple-600 font-medium mb-1">Adresse</p>
                                <p class="text-sm text-purple-800">{{ $partenaire->adresse }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Logo -->
                @if($partenaire->logo)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-image text-iri-gold mr-2"></i>
                        Logo
                    </h3>
                    <div class="text-center">
                        <img src="{{ $partenaire->logo_url }}" 
                             alt="Logo {{ $partenaire->nom }}" 
                             class="max-w-full h-32 object-contain mx-auto rounded-lg border border-gray-200">
                    </div>
                </div>
                @endif

                <!-- Informations du partenariat -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-handshake text-iri-secondary mr-2"></i>
                        Partenariat
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Statut</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @switch($partenaire->statut)
                                    @case('actif') bg-green-100 text-green-800 @break
                                    @case('inactif') bg-red-100 text-red-800 @break
                                    @case('en_negociation') bg-yellow-100 text-yellow-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                <i class="fas fa-circle mr-1 text-xs
                                    @switch($partenaire->statut)
                                        @case('actif') text-green-500 @break
                                        @case('inactif') text-red-500 @break
                                        @case('en_negociation') text-yellow-500 @break
                                        @default text-gray-500
                                    @endswitch"></i>
                                {{ $partenaire->statut_libelle }}
                            </span>
                        </div>

                        @if($partenaire->date_debut_partenariat)
                        <div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Date de début</p>
                            <p class="text-sm text-gray-900 flex items-center">
                                <i class="fas fa-calendar-alt text-green-500 mr-2"></i>
                                {{ $partenaire->date_debut_partenariat->format('d/m/Y') }}
                            </p>
                        </div>
                        @endif

                        @if($partenaire->date_fin_partenariat)
                        <div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Date de fin</p>
                            <p class="text-sm text-gray-900 flex items-center">
                                <i class="fas fa-calendar-times text-red-500 mr-2"></i>
                                {{ $partenaire->date_fin_partenariat->format('d/m/Y') }}
                            </p>
                        </div>
                        @endif

                        <div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Visibilité</p>
                            @if($partenaire->afficher_publiquement)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-eye mr-1"></i>
                                    Public
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-eye-slash mr-1"></i>
                                    Privé
                                </span>
                            @endif
                        </div>

                        @if($partenaire->ordre_affichage)
                        <div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Ordre d'affichage</p>
                            <p class="text-sm text-gray-900 flex items-center">
                                <i class="fas fa-sort text-iri-gold mr-2"></i>
                                {{ $partenaire->ordre_affichage }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Métadonnées -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-gray-500 mr-2"></i>
                        Métadonnées
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Créé le :</span>
                            <span class="text-gray-900">{{ $partenaire->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Modifié le :</span>
                            <span class="text-gray-900">{{ $partenaire->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">ID :</span>
                            <span class="text-gray-900 font-mono">#{{ $partenaire->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
