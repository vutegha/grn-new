@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <a href="{{ route('admin.publication.index') }}" 
           class="text-iri-gray hover:text-iri-primary transition-colors duration-200 font-medium">
            Publications
        </a>
    </li>
    <li aria-current="page">
        <div class="flex items-center">
            <i class="fas fa-chevron-right mx-2 text-iri-gray/50"></i>
            <span class="text-iri-primary font-medium">{{ Str::limit($publication->titre, 30) }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- En-tête de la publication -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h1 class="text-2xl font-bold text-white">{{ $publication->titre }}</h1>
                </div>
                <div class="p-6">
                    @if($publication->image)
                    <div class="mb-6">
                        <img src="{{ Storage::url($publication->image) }}" 
                             alt="{{ $publication->titre }}" 
                             class="w-full h-64 object-cover rounded-lg border border-gray-200">
                    </div>
                    @endif
                    
                    <div class="prose max-w-none">
                        <div class="space-y-4">
                            <div class="text-sm text-iri-gray flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                Publié le {{ $publication->created_at->format('d/m/Y à H:i') }}
                            </div>
                            
                            @if($publication->updated_at && $publication->updated_at != $publication->created_at)
                            <div class="text-sm text-iri-gray flex items-center">
                                <i class="fas fa-edit mr-2"></i>
                                Modifié le {{ $publication->updated_at->format('d/m/Y à H:i') }}
                            </div>
                            @endif
                        </div>
                        
                        <div class="mt-6 prose prose-lg max-w-none text-iri-dark leading-relaxed">
                            {!! nl2br(e($publication->contenu)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mots-clés -->
            @if($publication->mots_cles)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-tags mr-3"></i>
                        Mots-clés
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', $publication->mots_cles) as $motCle)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-iri-primary/10 text-iri-primary border border-iri-primary/20">
                                <i class="fas fa-tag mr-1 text-xs"></i>
                                {{ trim($motCle) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Auteurs -->
            @if($publication->auteurs && $publication->auteurs->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-users mr-3"></i>
                        Auteurs ({{ $publication->auteurs->count() }})
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($publication->auteurs as $auteur)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg border">
                                <div class="w-10 h-10 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-iri-dark">{{ $auteur->nom }}</p>
                                    @if($auteur->email)
                                        <p class="text-sm text-iri-gray">{{ $auteur->email }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Lecteur PDF simplifié -->
            @if($publication->fichier_pdf)
            @php
                $fileUrl = Storage::url($publication->fichier_pdf);
                $extension = pathinfo($publication->fichier_pdf, PATHINFO_EXTENSION);
            @endphp
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-file-pdf mr-3"></i>
                        Document PDF
                    </h2>
                </div>
                
                @if ($extension === 'pdf')
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <!-- PDF Header with Search -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 border-b">
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                            <div class="flex items-center gap-4">
                                <input type="text" id="searchText" 
                                       placeholder="Rechercher dans le document..." 
                                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                                <button id="searchBtn" 
                                        class="bg-iri-primary text-white px-4 py-2 rounded-lg hover:bg-iri-secondary transition-colors duration-200">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <button id="resetBtn" 
                                        class="hidden bg-gray-500 text-white px-3 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                                    <i class="fas fa-times mr-1"></i>Reset
                                </button>
                                <button id="prevMatch" 
                                        class="hidden bg-iri-accent text-white px-3 py-2 rounded-lg hover:bg-iri-gold transition-colors duration-200">
                                    ←
                                </button>
                                <button id="nextMatch" 
                                        class="hidden bg-iri-accent text-white px-3 py-2 rounded-lg hover:bg-iri-gold transition-colors duration-200">
                                    →
                                </button>
                                <span id="matchCount" class="hidden text-sm text-gray-600"></span>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div id="pdfControls" class="flex items-center space-x-2">
                                <button id="prevPage" 
                                        class="bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 transition-colors duration-200"
                                        title="Page précédente">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-600">Page</span>
                                    <input type="number" 
                                           id="pageInput" 
                                           min="1" 
                                           value="1"
                                           class="w-16 px-2 py-1 text-center border border-gray-300 rounded text-sm focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                                    <span class="text-sm text-gray-600">sur <span id="totalPages">-</span></span>
                                </div>
                                
                                <button id="nextPage" 
                                        class="bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 transition-colors duration-200"
                                        title="Page suivante">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <a href="{{ $fileUrl }}" 
                                   target="_blank"
                                   class="bg-iri-accent text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors duration-200 flex items-center">
                                    <i class="fas fa-download mr-2"></i>
                                    Télécharger
                                </a>
                                <button id="fullscreenBtn" 
                                        class="bg-iri-secondary text-white px-3 py-2 rounded-lg hover:bg-iri-primary transition-colors duration-200">
                                    <i class="fas fa-expand"></i>
                                </button>
                                <span id="pageCount" class="text-sm text-gray-600"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Navigation Bar -->
                    <div id="floatingNav" class="fixed top-0 left-0 right-0 bg-white shadow-lg border-b border-gray-200 z-50 hidden transform -translate-y-full transition-transform duration-300">
                        <div class="max-w-7xl mx-auto px-4 py-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center space-x-2">
                                        <button id="floatingPrevPage" 
                                                class="bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 transition-colors duration-200"
                                                title="Page précédente">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-600">Page</span>
                                            <input type="number" 
                                                   id="floatingPageInput" 
                                                   min="1" 
                                                   value="1"
                                                   class="w-16 px-2 py-1 text-center border border-gray-300 rounded text-sm focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                                            <span class="text-sm text-gray-600">sur <span id="floatingTotalPages">-</span></span>
                                        </div>
                                        
                                        <button id="floatingNextPage" 
                                                class="bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 transition-colors duration-200"
                                                title="Page suivante">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <!-- Search indicators for floating nav -->
                                    <div id="floatingSearchInfo" class="hidden flex items-center space-x-2">
                                        <button id="floatingPrevMatch" 
                                                class="bg-iri-accent text-white px-2 py-2 rounded hover:bg-iri-gold transition-colors duration-200">
                                            ←
                                        </button>
                                        <span id="floatingMatchCount" class="text-sm text-gray-600 px-2"></span>
                                        <button id="floatingNextMatch" 
                                                class="bg-iri-accent text-white px-2 py-2 rounded hover:bg-iri-gold transition-colors duration-200">
                                            →
                                        </button>
                                    </div>
                                    
                                    <button id="floatingFullscreen" 
                                            class="bg-iri-secondary text-white px-3 py-2 rounded-lg hover:bg-iri-primary transition-colors duration-200">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PDF Content Container -->
                    <div id="pdfContainer" class="relative min-h-96" data-pdf-url="{{ $fileUrl }}">
                        <!-- Loading State (Initial) -->
                        <div id="pdfLoader" class="flex flex-col items-center justify-center p-12 bg-gray-50">
                            <div class="bg-gradient-to-r from-iri-primary to-iri-secondary w-16 h-16 rounded-full flex items-center justify-center mb-4 animate-pulse">
                                <i class="fas fa-file-pdf text-white text-2xl"></i>
                            </div>
                            <p class="text-gray-700 font-medium text-lg mb-4">Chargement du document PDF...</p>
                            <div class="w-full max-w-md">
                                <div id="pdfProgressContainer" class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                    <div id="pdfProgressBar" class="bg-gradient-to-r from-iri-primary to-iri-secondary h-3 rounded-full transition-all duration-300" style="width: 0%;"></div>
                                </div>
                                <div id="pdfProgressText" class="text-sm text-gray-600 text-center">Initialisation...</div>
                            </div>
                        </div>

                        <!-- PDF Viewer Container -->
                        <div id="pdfViewer" class="hidden p-6 space-y-6 bg-gray-50"></div>
                    </div>
                </div>
                @else
                <div class="p-6">
                    <div class="text-center">
                        <i class="fas fa-file text-iri-gray text-4xl mb-4"></i>
                        <p class="text-iri-gray mb-4">Document non-PDF disponible</p>
                        <a href="{{ $fileUrl }}" target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200">
                            <i class="fas fa-download mr-2"></i>Télécharger le fichier
                        </a>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
            <!-- Statut et métadonnées -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-eye mr-3"></i>
                        Statut & Métadonnées
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Statut de publication -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Statut</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                              {{ $publication->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            <i class="fas {{ $publication->is_published ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                            {{ $publication->is_published ? 'Publié' : 'Brouillon' }}
                        </span>
                    </div>

                    <!-- Date de création -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Créé le</span>
                        <span class="text-sm text-gray-600">{{ $publication->created_at->format('d/m/Y') }}</span>
                    </div>

                    <!-- Dernière modification -->
                    @if($publication->updated_at && $publication->updated_at != $publication->created_at)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Modifié le</span>
                        <span class="text-sm text-gray-600">{{ $publication->updated_at->format('d/m/Y') }}</span>
                    </div>
                    @endif

                    <!-- Catégorie -->
                    @if($publication->categorie)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Catégorie</span>
                        <span class="text-sm text-gray-600">{{ $publication->categorie->nom ?? 'Non définie' }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions de modération -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-gavel mr-3"></i>
                        Actions
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Historique de modération -->
                    @if($publication->moderation_comment || $publication->published_at || $publication->published_by)
                    <div class="bg-blue-50 rounded-lg p-4 mb-4 border border-blue-200">
                        <h3 class="text-sm font-medium text-blue-800 mb-3 flex items-center">
                            <i class="fas fa-history mr-2"></i>Historique de modération
                        </h3>
                        <div class="space-y-2 text-sm">
                            <!-- Statut actuel -->
                            <div class="flex items-center">
                                <i class="fas {{ $publication->is_published ? 'fa-check-circle text-green-600' : 'fa-clock text-yellow-600' }} mr-2"></i>
                                <span class="font-medium {{ $publication->is_published ? 'text-green-800' : 'text-yellow-800' }}">
                                    {{ $publication->is_published ? 'Publié' : 'En attente de modération' }}
                                </span>
                            </div>

                            <!-- Date et modérateur -->
                            @if($publication->published_at)
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-calendar mr-2 text-gray-500"></i>
                                <span>{{ $publication->published_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            @endif

                            @if($publication->published_by)
                                @php
                                    $moderator = \App\Models\User::find($publication->published_by);
                                @endphp
                                @if($moderator)
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-user-shield mr-2 text-gray-500"></i>
                                    <span>par {{ $moderator->prenom }} {{ $moderator->nom }}</span>
                                </div>
                                @endif
                            @endif

                            <!-- Commentaire de modération -->
                            @if($publication->moderation_comment)
                            <div class="mt-3 p-3 bg-white rounded border-l-4 border-blue-400">
                                <div class="flex items-start">
                                    <i class="fas fa-comment-dots mr-2 text-blue-600 mt-0.5"></i>
                                    <div>
                                        <div class="text-xs text-gray-500 mb-1">Commentaire du modérateur :</div>
                                        <div class="text-gray-800 italic">
                                            "{{ $publication->moderation_comment }}"
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Actions de modération (nécessitent des permissions spéciales) -->
                    @can('moderate', $publication)
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-shield-alt mr-2"></i>Actions de modération
                        </h3>
                        <div class="space-y-2">
                            @if(!$publication->is_published)
                            <button onclick="moderatePublication('publish', {{ $publication->id }})" 
                                    class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-check mr-2"></i>Approuver et Publier
                            </button>
                            @else
                            <button onclick="moderatePublication('unpublish', {{ $publication->id }})" 
                                    class="w-full flex items-center justify-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                                <i class="fas fa-pause mr-2"></i>Dépublier
                            </button>
                            @endif
                        </div>
                    </div>
                    @endcan

                    <!-- Actions d'édition standard -->
                    @can('update', $publication)
                    <a href="{{ route('admin.publication.edit', $publication) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    @endcan

                    @can('delete', $publication)
                    <form action="{{ route('admin.publication.destroy', $publication) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de modération -->
<div id="moderationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modal-title" class="text-lg font-medium text-gray-900"></h3>
                <button onclick="closeModerationModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-2 px-2 py-3">
                <p id="modal-description" class="text-sm text-gray-500 mb-4"></p>
                
                <!-- Affichage du commentaire existant -->
                @if($publication->moderation_comment)
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="text-xs text-blue-600 font-medium mb-1">Commentaire actuel :</div>
                    <div class="text-sm text-blue-800 italic">"{{ $publication->moderation_comment }}"</div>
                </div>
                @endif
                
                <div class="mb-4">
                    <label for="moderation_comment" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $publication->moderation_comment ? 'Nouveau commentaire (optionnel)' : 'Commentaire (optionnel)' }}
                    </label>
                    <textarea id="moderation_comment" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary"
                              placeholder="{{ $publication->moderation_comment ? 'Ajoutez un nouveau commentaire ou laissez vide pour conserver l\'actuel...' : 'Ajoutez un commentaire sur cette action...' }}"></textarea>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ $publication->moderation_comment ? 'Laisser vide conservera le commentaire actuel' : 'Ce commentaire sera visible dans l\'historique de modération' }}
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-2">
                <button onclick="closeModerationModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    Annuler
                </button>
                <button onclick="confirmModerationAction()" 
                        class="px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors">
                    Confirmer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Styles pour le lecteur PDF avancé -->
<style>
    /* PDF Viewer Styles */
    #pdfViewer canvas {
        display: block;
        margin: 0 auto 2rem auto;
        width: 100% !important;
        height: auto !important;
        max-width: 100%;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    #pdfViewer canvas:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    /* Search highlight styles */
    .highlighted {
        background-color: rgba(255, 255, 0, 0.4) !important;
        color: inherit !important;
        padding: 1px 2px;
        border-radius: 2px;
        box-shadow: 0 0 3px rgba(255, 255, 0, 0.6);
    }
    
    .highlighted.current {
        background-color: rgba(255, 165, 0, 0.6) !important;
        box-shadow: 0 0 5px rgba(255, 165, 0, 0.8);
    }
    
    /* Loading animation */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .loading-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    /* Floating navigation styles */
    #floatingNav.show {
        transform: translateY(0);
    }
    
    /* Button animations */
    .control-button {
        transition: all 0.2s ease;
    }
    
    .control-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
    }
    
    /* Progress bar animation */
    #pdfProgressBar {
        transition: width 0.3s ease-in-out;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .pdf-controls {
            flex-direction: column;
            gap: 1rem;
        }
        
        .pdf-controls > div {
            width: 100%;
            justify-content: center;
        }
        
        #searchText {
            max-width: none;
            width: 100%;
        }
        
        #floatingNav .flex {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
    
    /* Page number input styling */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    input[type="number"] {
        -moz-appearance: textfield;
    }
    
    /* Fullscreen styles */
    .fullscreen-container {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 9999 !important;
        background: white !important;
        overflow: auto !important;
    }
    
    .fullscreen-container #pdfViewer {
        padding: 2rem;
        background: white;
    }
</style>

<!-- Scripts PDF.js avancés -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    // Configuration PDF.js
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    
    class AdvancedPDFViewer {
        constructor(container) {
            this.container = container;
            this.pdfDocument = null;
            this.currentPage = 1;
            this.totalPages = 0;
            this.scale = 1.2;
            this.searchMatches = [];
            this.currentMatchIndex = -1;
            this.isFullscreen = false;
            this.floatingNavVisible = false;
            
            this.initializeElements();
            this.bindEvents();
            this.loadPDF();
        }
        
        initializeElements() {
            this.elements = {
                loader: document.getElementById('pdfLoader'),
                viewer: document.getElementById('pdfViewer'),
                progressBar: document.getElementById('pdfProgressBar'),
                progressText: document.getElementById('pdfProgressText'),
                
                // Navigation
                prevPage: document.getElementById('prevPage'),
                nextPage: document.getElementById('nextPage'),
                pageInput: document.getElementById('pageInput'),
                totalPages: document.getElementById('totalPages'),
                pageCount: document.getElementById('pageCount'),
                
                // Search
                searchText: document.getElementById('searchText'),
                searchBtn: document.getElementById('searchBtn'),
                resetBtn: document.getElementById('resetBtn'),
                prevMatch: document.getElementById('prevMatch'),
                nextMatch: document.getElementById('nextMatch'),
                matchCount: document.getElementById('matchCount'),
                
                // Controls
                fullscreenBtn: document.getElementById('fullscreenBtn'),
                
                // Floating navigation
                floatingNav: document.getElementById('floatingNav'),
                floatingPrevPage: document.getElementById('floatingPrevPage'),
                floatingNextPage: document.getElementById('floatingNextPage'),
                floatingPageInput: document.getElementById('floatingPageInput'),
                floatingTotalPages: document.getElementById('floatingTotalPages'),
                floatingSearchInfo: document.getElementById('floatingSearchInfo'),
                floatingPrevMatch: document.getElementById('floatingPrevMatch'),
                floatingNextMatch: document.getElementById('floatingNextMatch'),
                floatingMatchCount: document.getElementById('floatingMatchCount'),
                floatingFullscreen: document.getElementById('floatingFullscreen')
            };
        }
        
        bindEvents() {
            // Navigation events
            this.elements.prevPage.addEventListener('click', () => this.goToPage(this.currentPage - 1));
            this.elements.nextPage.addEventListener('click', () => this.goToPage(this.currentPage + 1));
            this.elements.pageInput.addEventListener('change', (e) => this.goToPage(parseInt(e.target.value)));
            
            // Floating navigation events
            this.elements.floatingPrevPage.addEventListener('click', () => this.goToPage(this.currentPage - 1));
            this.elements.floatingNextPage.addEventListener('click', () => this.goToPage(this.currentPage + 1));
            this.elements.floatingPageInput.addEventListener('change', (e) => this.goToPage(parseInt(e.target.value)));
            
            // Search events
            this.elements.searchBtn.addEventListener('click', () => this.performSearch());
            this.elements.searchText.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') this.performSearch();
            });
            this.elements.resetBtn.addEventListener('click', () => this.resetSearch());
            this.elements.prevMatch.addEventListener('click', () => this.navigateMatch(-1));
            this.elements.nextMatch.addEventListener('click', () => this.navigateMatch(1));
            this.elements.floatingPrevMatch.addEventListener('click', () => this.navigateMatch(-1));
            this.elements.floatingNextMatch.addEventListener('click', () => this.navigateMatch(1));
            
            // Fullscreen events
            this.elements.fullscreenBtn.addEventListener('click', () => this.toggleFullscreen());
            this.elements.floatingFullscreen.addEventListener('click', () => this.toggleFullscreen());
            
            // Scroll events for floating navigation
            window.addEventListener('scroll', () => this.handleScroll());
            
            // Keyboard shortcuts
            document.addEventListener('keydown', (e) => this.handleKeyboard(e));
        }
        
        async loadPDF() {
            const url = this.container.getAttribute('data-pdf-url');
            if (!url) return;
            
            try {
                this.updateProgress(0, 'Initialisation...');
                
                const loadingTask = pdfjsLib.getDocument({
                    url: url,
                    disableAutoFetch: false,
                    disableStream: false
                });
                
                loadingTask.onProgress = (progress) => {
                    if (progress.loaded && progress.total) {
                        const percent = Math.round((progress.loaded / progress.total) * 100);
                        this.updateProgress(percent, `Chargement... ${percent}%`);
                    }
                };
                
                this.pdfDocument = await loadingTask.promise;
                this.totalPages = this.pdfDocument.numPages;
                
                this.updateProgress(100, 'Rendu en cours...');
                await this.renderPage(1);
                this.showViewer();
                this.updateNavigationInfo();
                
            } catch (error) {
                console.error('Erreur lors du chargement du PDF:', error);
                this.showError('Impossible de charger le document PDF.');
            }
        }
        
        async renderPage(pageNum) {
            if (!this.pdfDocument || pageNum < 1 || pageNum > this.totalPages) return;
            
            try {
                const page = await this.pdfDocument.getPage(pageNum);
                const viewport = page.getViewport({ scale: this.scale });
                
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                canvas.id = `page-${pageNum}`;
                canvas.className = 'page-canvas';
                
                // Clear viewer and add new page
                this.elements.viewer.innerHTML = '';
                
                // Page container
                const pageContainer = document.createElement('div');
                pageContainer.className = 'page-container relative mb-8';
                pageContainer.appendChild(canvas);
                
                // Page number indicator
                const pageIndicator = document.createElement('div');
                pageIndicator.className = 'text-center text-sm text-gray-600 mt-2 font-medium';
                pageIndicator.textContent = `Page ${pageNum} sur ${this.totalPages}`;
                pageContainer.appendChild(pageIndicator);
                
                this.elements.viewer.appendChild(pageContainer);
                
                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                
                await page.render(renderContext).promise;
                this.currentPage = pageNum;
                this.updateNavigationInfo();
                
            } catch (error) {
                console.error('Erreur lors du rendu de la page:', error);
            }
        }
        
        goToPage(pageNum) {
            if (pageNum >= 1 && pageNum <= this.totalPages && pageNum !== this.currentPage) {
                this.renderPage(pageNum);
            }
        }
        
        async performSearch() {
            const searchTerm = this.elements.searchText.value.trim();
            if (!searchTerm || !this.pdfDocument) return;
            
            this.resetSearch();
            this.updateProgress(0, 'Recherche en cours...');
            this.elements.loader.classList.remove('hidden');
            
            try {
                // Simulation de recherche (dans une vraie implémentation, 
                // il faudrait parcourir le contenu textuel du PDF)
                const mockMatches = Math.floor(Math.random() * 15) + 1;
                this.searchMatches = Array.from({ length: mockMatches }, (_, i) => ({
                    page: Math.floor(Math.random() * this.totalPages) + 1,
                    index: i
                }));
                
                if (this.searchMatches.length > 0) {
                    this.currentMatchIndex = 0;
                    this.showSearchResults();
                    await this.renderPage(this.searchMatches[0].page);
                } else {
                    this.showNotification('Aucun résultat trouvé', 'warning');
                }
                
            } catch (error) {
                console.error('Erreur lors de la recherche:', error);
            } finally {
                this.elements.loader.classList.add('hidden');
            }
        }
        
        showSearchResults() {
            this.elements.resetBtn.classList.remove('hidden');
            this.elements.prevMatch.classList.remove('hidden');
            this.elements.nextMatch.classList.remove('hidden');
            this.elements.matchCount.classList.remove('hidden');
            this.elements.floatingSearchInfo.classList.remove('hidden');
            
            this.updateMatchInfo();
        }
        
        updateMatchInfo() {
            const info = `${this.currentMatchIndex + 1} sur ${this.searchMatches.length}`;
            this.elements.matchCount.textContent = info;
            this.elements.floatingMatchCount.textContent = info;
        }
        
        navigateMatch(direction) {
            if (this.searchMatches.length === 0) return;
            
            this.currentMatchIndex += direction;
            if (this.currentMatchIndex < 0) this.currentMatchIndex = this.searchMatches.length - 1;
            if (this.currentMatchIndex >= this.searchMatches.length) this.currentMatchIndex = 0;
            
            this.updateMatchInfo();
            this.renderPage(this.searchMatches[this.currentMatchIndex].page);
        }
        
        resetSearch() {
            this.searchMatches = [];
            this.currentMatchIndex = -1;
            this.elements.searchText.value = '';
            this.elements.resetBtn.classList.add('hidden');
            this.elements.prevMatch.classList.add('hidden');
            this.elements.nextMatch.classList.add('hidden');
            this.elements.matchCount.classList.add('hidden');
            this.elements.floatingSearchInfo.classList.add('hidden');
        }
        
        toggleFullscreen() {
            if (!this.isFullscreen) {
                this.container.classList.add('fullscreen-container');
                this.elements.fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';
                this.elements.floatingFullscreen.innerHTML = '<i class="fas fa-compress"></i>';
                this.isFullscreen = true;
            } else {
                this.container.classList.remove('fullscreen-container');
                this.elements.fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i>';
                this.elements.floatingFullscreen.innerHTML = '<i class="fas fa-expand"></i>';
                this.isFullscreen = false;
            }
        }
        
        handleScroll() {
            const scrollY = window.scrollY;
            const shouldShow = scrollY > 300;
            
            if (shouldShow && !this.floatingNavVisible) {
                this.elements.floatingNav.classList.remove('hidden');
                this.elements.floatingNav.classList.add('show');
                this.floatingNavVisible = true;
            } else if (!shouldShow && this.floatingNavVisible) {
                this.elements.floatingNav.classList.remove('show');
                setTimeout(() => {
                    if (!this.floatingNavVisible) {
                        this.elements.floatingNav.classList.add('hidden');
                    }
                }, 300);
                this.floatingNavVisible = false;
            }
        }
        
        handleKeyboard(e) {
            if (e.ctrlKey || e.metaKey) {
                switch (e.key) {
                    case 'f':
                        e.preventDefault();
                        this.elements.searchText.focus();
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        this.goToPage(this.currentPage - 1);
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        this.goToPage(this.currentPage + 1);
                        break;
                }
            } else if (e.key === 'Escape') {
                if (this.isFullscreen) {
                    this.toggleFullscreen();
                } else if (this.searchMatches.length > 0) {
                    this.resetSearch();
                }
            }
        }
        
        updateNavigationInfo() {
            this.elements.pageInput.value = this.currentPage;
            this.elements.floatingPageInput.value = this.currentPage;
            this.elements.totalPages.textContent = this.totalPages;
            this.elements.floatingTotalPages.textContent = this.totalPages;
            this.elements.pageCount.textContent = `${this.totalPages} pages`;
            
            // Update button states
            this.elements.prevPage.disabled = this.currentPage <= 1;
            this.elements.nextPage.disabled = this.currentPage >= this.totalPages;
            this.elements.floatingPrevPage.disabled = this.currentPage <= 1;
            this.elements.floatingNextPage.disabled = this.currentPage >= this.totalPages;
        }
        
        updateProgress(percent, text) {
            this.elements.progressBar.style.width = percent + '%';
            this.elements.progressText.textContent = text;
        }
        
        showViewer() {
            this.elements.loader.classList.add('hidden');
            this.elements.viewer.classList.remove('hidden');
        }
        
        showError(message) {
            this.elements.loader.innerHTML = `
                <div class="text-center p-8">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Erreur de chargement</h3>
                    <p class="text-gray-600 mb-4">${message}</p>
                    <a href="${this.container.getAttribute('data-pdf-url')}" target="_blank" 
                       class="inline-flex items-center px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors">
                        <i class="fas fa-download mr-2"></i>Télécharger le fichier
                    </a>
                </div>
            `;
        }
        
        showNotification(message, type = 'info') {
            // Réutilise la fonction existante
            if (typeof showNotification === 'function') {
                showNotification(message, type);
            }
        }
    }
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        const pdfContainer = document.getElementById('pdfContainer');
        if (pdfContainer && pdfContainer.getAttribute('data-pdf-url')) {
            new AdvancedPDFViewer(pdfContainer);
        }
    });
</script>

<!-- Script pour les actions de modération -->
<script>
    let currentModerationAction = null;
    let currentPublicationId = null;

    function moderatePublication(action, publicationId) {
        currentModerationAction = action;
        currentPublicationId = publicationId;
        
        const modal = document.getElementById('moderationModal');
        const title = document.getElementById('modal-title');
        const description = document.getElementById('modal-description');
        const commentField = document.getElementById('moderation_comment');
        
        // Configurer le modal selon l'action
        if (action === 'publish') {
            title.textContent = 'Approuver la Publication';
            description.textContent = 'Êtes-vous sûr de vouloir approuver et publier cette publication ?';
        } else if (action === 'unpublish') {
            title.textContent = 'Dépublier la Publication';
            description.textContent = 'Êtes-vous sûr de vouloir dépublier cette publication ?';
        }
        
        // Réinitialiser le commentaire
        commentField.value = '';
        
        // Afficher le modal
        modal.classList.remove('hidden');
    }

    function closeModerationModal() {
        const modal = document.getElementById('moderationModal');
        modal.classList.add('hidden');
        currentModerationAction = null;
        currentPublicationId = null;
    }

    function confirmModerationAction() {
        if (!currentModerationAction || !currentPublicationId) {
            return;
        }

        const comment = document.getElementById('moderation_comment').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Afficher un indicateur de chargement
        const confirmButton = event.target;
        const originalText = confirmButton.innerHTML;
        confirmButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Traitement...';
        confirmButton.disabled = true;

        // Déterminer l'URL selon l'action
        let url = '';
        let method = 'POST';
        
        if (currentModerationAction === 'publish') {
            url = `{{ route('admin.publication.publish', $publication) }}`;
        } else if (currentModerationAction === 'unpublish') {
            url = `{{ route('admin.publication.unpublish', $publication) }}`;
        }

        // Effectuer la requête AJAX
        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                comment: comment
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Action de modération effectuée avec succès', 'success');
                closeModerationModal();
                // Recharger la page pour refléter les changements
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'Erreur lors de l\'action de modération', 'error');
                // Restaurer le bouton
                confirmButton.innerHTML = originalText;
                confirmButton.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur lors de l\'action de modération', 'error');
            // Restaurer le bouton
            confirmButton.innerHTML = originalText;
            confirmButton.disabled = false;
        });
    }

    function showNotification(message, type = 'info') {
        // Créer la notification
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
        
        if (type === 'success') {
            notification.classList.add('bg-green-500', 'text-white');
            notification.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${message}`;
        } else if (type === 'error') {
            notification.classList.add('bg-red-500', 'text-white');
            notification.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i>${message}`;
        } else {
            notification.classList.add('bg-blue-500', 'text-white');
            notification.innerHTML = `<i class="fas fa-info-circle mr-2"></i>${message}`;
        }
        
        document.body.appendChild(notification);
        
        // Animer l'entrée
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Supprimer après 5 secondes
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }

    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('moderationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModerationModal();
        }
    });

    // Fermer le modal avec la touche Échap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModerationModal();
        }
    });
</script>

@endsection
