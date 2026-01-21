@extends('layouts.admin')

@push('styles')
<style>
    /* Styles pour le contenu riche - Version améliorée avec support WordPress complet */
    .prose {
        color: inherit;
        font-size: 16px;
        line-height: 1.75;
    }
    
    /* Classes WordPress pour images - Support complet */
    .prose img.alignleft,
    .prose .alignleft img {
        float: left;
        margin: 0.5em 1.5em 1em 0;
        max-width: 50%;
    }
    .prose img.alignright,
    .prose .alignright img {
        float: right;
        margin: 0.5em 0 1em 1.5em;
        max-width: 50%;
    }
    .prose img.aligncenter,
    .prose .aligncenter {
        display: block;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }
    
    /* Blocs WordPress Gutenberg */
    .prose .wp-block-image {
        margin: 1.5em 0;
    }
    .prose .wp-block-image.alignleft {
        float: left;
        margin: 0.5em 1.5em 1em 0;
        max-width: 50%;
    }
    .prose .wp-block-image.alignright {
        float: right;
        margin: 0.5em 0 1em 1.5em;
        max-width: 50%;
    }
    .prose .wp-block-image.aligncenter {
        text-align: center;
        margin: 1.5em auto;
    }
    .prose .wp-block-image.size-large {
        max-width: 800px;
    }
    .prose .wp-block-image.size-medium {
        max-width: 600px;
    }
    .prose .wp-block-image.size-thumbnail {
        max-width: 300px;
    }
    
    /* Galeries WordPress */
    .prose .wp-block-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5em;
        margin: 2em 0;
    }
    .prose .wp-block-gallery img {
        width: 100%;
        height: auto;
        object-fit: cover;
        border-radius: 0.5rem;
    }
    
    /* Légendes WordPress */
    .prose .wp-caption {
        max-width: 100%;
        margin: 1.5em 0;
    }
    .prose .wp-caption-text,
    .prose figcaption {
        font-size: 0.875em;
        color: #6b7280;
        font-style: italic;
        margin-top: 0.75em;
        text-align: center;
        padding: 0.5em;
        background-color: #f9fafb;
        border-left: 3px solid #0891b2;
        border-radius: 0.25rem;
    }
    
    /* Galeries classiques WordPress */
    .prose .gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1em;
        margin: 1.5em 0;
    }
    .prose .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 0.5rem;
    }
    .prose .gallery-item img {
        width: 100%;
        height: auto;
        transition: transform 0.3s ease;
    }
    .prose .gallery-item:hover img {
        transform: scale(1.05);
    }
    
    /* Tailles d'images WordPress */
    .prose .size-full, .prose .size-large, .prose .size-medium, .prose .size-thumbnail {
        max-width: 100%;
        height: auto;
    }
    
    /* Paragraphes */
    .prose p {
        margin: 1em 0;
        line-height: 1.75;
        color: #374151;
    }
    
    /* Titres */
    .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
        margin-top: 2em;
        margin-bottom: 1em;
        font-weight: 600;
        line-height: 1.25;
    }
    .prose h1 { font-size: 2em; color: #0891b2; }
    .prose h2 { font-size: 1.5em; color: #0891b2; }
    .prose h3 { font-size: 1.25em; color: #0891b2; }
    .prose h4 { font-size: 1.125em; color: #1f2937; }
    .prose h5, .prose h6 { font-size: 1em; color: #1f2937; }
    
    /* Texte formaté */
    .prose strong, .prose b {
        font-weight: 600;
        color: #1f2937;
    }
    .prose em, .prose i {
        font-style: italic;
        color: #4b5563;
    }
    .prose u {
        text-decoration: underline;
    }
    .prose s, .prose del {
        text-decoration: line-through;
    }
    
    /* Liens */
    .prose a {
        color: #0891b2;
        text-decoration: underline;
        transition: color 0.2s;
    }
    .prose a:hover {
        color: #0e7490;
    }
    
    /* Listes */
    .prose ul, .prose ol {
        margin: 1em 0;
        padding-left: 2em;
    }
    .prose ul {
        list-style-type: disc;
    }
    .prose ol {
        list-style-type: decimal;
    }
    .prose ul li, .prose ol li {
        margin: 0.5em 0;
        padding-left: 0.5em;
    }
    .prose ul ul, .prose ol ul {
        list-style-type: circle;
    }
    .prose ul ul ul, .prose ol ul ul {
        list-style-type: square;
    }
    
    /* Citations */
    .prose blockquote {
        border-left: 4px solid #0891b2;
        padding-left: 1.5em;
        margin: 1.5em 0;
        font-style: italic;
        color: #4b5563;
        background-color: #f9fafb;
        padding: 1em 1.5em;
        border-radius: 0.375rem;
    }
    
    /* Code */
    .prose code {
        background-color: #f3f4f6;
        padding: 0.2em 0.4em;
        border-radius: 0.25rem;
        font-size: 0.875em;
        font-family: 'Courier New', monospace;
        color: #1f2937;
    }
    .prose pre {
        background-color: #1f2937;
        color: #f9fafb;
        padding: 1em;
        border-radius: 0.5rem;
        overflow-x: auto;
        margin: 1.5em 0;
    }
    .prose pre code {
        background-color: transparent;
        padding: 0;
        color: inherit;
    }
    
    /* Tableaux */
    .prose table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5em 0;
        font-size: 0.875em;
    }
    .prose th {
        background-color: rgba(8, 145, 178, 0.1);
        padding: 0.75em 1em;
        text-align: left;
        font-weight: 600;
        border: 1px solid #d1d5db;
        color: #1f2937;
    }
    .prose td {
        padding: 0.75em 1em;
        border: 1px solid #d1d5db;
    }
    .prose tr:nth-child(even) {
        background-color: #f9fafb;
    }
    
    /* Images */
    .prose img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin: 1.5em 0;
    }
    
    /* Séparateur horizontal */
    .prose hr {
        border: none;
        border-top: 2px solid #e5e7eb;
        margin: 2em 0;
    }
    
    /* Clearfix pour les images flottantes WordPress */
    .prose::after {
        content: "";
        display: table;
        clear: both;
    }
    .prose p::after {
        content: "";
        display: table;
        clear: both;
    }
    
    /* Paragraphes avec images flottantes WordPress */
    .prose p img.alignleft,
    .prose p img.alignright {
        margin-top: 0.25em;
    }
    
    /* Support des shortcodes WordPress nettoyés */
    .prose .wp-block-quote {
        border-left: 4px solid #0891b2;
        padding-left: 1.5em;
        margin: 1.5em 0;
        font-style: italic;
        color: #4b5563;
        background-color: #f9fafb;
        padding: 1em 1.5em;
        border-radius: 0.375rem;
    }
    
    /* Listes WordPress imbriquées */
    .prose ul ul,
    .prose ol ul,
    .prose ul ol,
    .prose ol ol {
        margin-top: 0.5em;
        margin-bottom: 0.5em;
    }
    
    /* Titres WordPress */
    .prose .wp-block-heading {
        margin-top: 2em;
        margin-bottom: 1em;
    }
    
    /* Boutons WordPress */
    .prose .wp-block-button {
        margin: 1em 0;
    }
    .prose .wp-block-button__link {
        display: inline-block;
        padding: 0.75em 1.5em;
        background-color: #0891b2;
        color: white;
        text-decoration: none;
        border-radius: 0.375rem;
        transition: background-color 0.2s;
    }
    .prose .wp-block-button__link:hover {
        background-color: #0e7490;
    }
    
    /* Séparateurs WordPress */
    .prose .wp-block-separator {
        border: none;
        border-top: 2px solid #e5e7eb;
        margin: 2em 0;
    }
    
    /* Espaces WordPress */
    .prose .wp-block-spacer {
        margin: 1em 0;
    }
    
    /* Limitation du nombre de lignes */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('breadcrumbs')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right mx-2 text-iri-gray/50"></i>
            <a href="{{ route('admin.actualite.index') }}" class="text-iri-gray hover:text-iri-primary transition-colors duration-200">Actualités</a>
        </div>
    </li>
    <li aria-current="page">
        <div class="flex items-center">
            <i class="fas fa-chevron-right mx-2 text-iri-gray/50"></i>
            <span class="text-iri-primary font-medium">{{ Str::limit($actualite->titre, 30) }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Messages Flash -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle h-5 w-5 text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button type="button" onclick="this.parentElement.parentElement.parentElement.parentElement.style.display='none'" 
                                class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100">
                            <i class="fas fa-times h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle h-5 w-5 text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button type="button" onclick="this.parentElement.parentElement.parentElement.parentElement.style.display='none'" 
                                class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100">
                            <i class="fas fa-times h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Header avec actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-iri-dark flex items-center">
                <i class="fas fa-newspaper text-iri-primary mr-3"></i>
                {{ $actualite->titre }}
            </h1>
            <p class="text-iri-gray mt-2">
                <i class="fas fa-info-circle mr-2"></i>
                Détails complets de l'actualité
            </p>
        </div>
        
        <!-- Actions -->
        <div class="flex space-x-3">
            @can('update_actualites')
            <a href="{{ route('admin.actualite.edit', $actualite) }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            @endcan
            <a href="{{ route('admin.actualite.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-iri-gray/10 text-iri-gray rounded-lg hover:bg-iri-gray/20 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Contenu de l'actualité -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-file-alt mr-3"></i>
                        Contenu de l'actualité
                    </h2>
                </div>
                <div class="p-6">
                    @if($actualite->hasImage())
                    <div class="mb-6">
                        <img src="{{ $actualite->image_url }}" 
                             alt="{{ $actualite->titre }}" 
                             class="w-full h-64 object-cover rounded-lg border border-gray-200">
                    </div>
                    @endif
                    
                    <div class="prose max-w-none">
                        <div>
                            <div class="text-sm text-iri-gray flex items-center mb-4">
                                <i class="fas fa-calendar mr-2"></i>
                                Publié le {{ $actualite->created_at ? $actualite->created_at->format('d/m/Y à H:i') : 'Date non disponible' }}
                            </div>
                            
                            @if($actualite->resume)
                            <div class="p-4 bg-gradient-to-r from-iri-accent/10 to-iri-gold/10 rounded-lg mb-6">
                                <h3 class="text-sm font-semibold text-iri-gray mb-3 flex items-center">
                                    <i class="fas fa-clipboard-list mr-2"></i>Résumé
                                </h3>
                                <x-wordpress-content 
                                    :content="$actualite->resume"
                                    max-width="max-w-none"
                                />
                            </div>
                            @endif
                            
                            @if($actualite->texte)
                            <div class="mt-6">
                                <h3 class="text-sm font-semibold text-iri-gray mb-4 flex items-center border-b border-gray-200 pb-2">
                                    <i class="fas fa-align-left mr-2"></i>Contenu
                                </h3>
                                <x-wordpress-content 
                                    :content="$actualite->texte"
                                    max-width="max-w-none"
                                />
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails de l'actualité -->
            @if($actualite->auteur)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Informations supplémentaires
                    </h2>
                </div>
                <div class="p-6">
                    <div>
                        <label class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-user-edit mr-2"></i>Auteur
                        </label>
                        <div class="p-3 bg-gray-50 rounded-lg border">
                            {{ $actualite->auteur }}
                        </div>
                    </div>
                </div>
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
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-globe mr-3 text-iri-gray"></i>
                            <span class="font-medium">Statut de publication</span>
                        </div>
                        @if($actualite->is_published)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                <i class="fas fa-check-circle mr-1"></i>Publiée
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800 border border-orange-200">
                                <i class="fas fa-clock mr-1"></i>En attente
                            </span>
                        @endif
                    </div>

                    <!-- À la une -->
                    @if(isset($actualite->a_la_une))
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-star mr-3 text-iri-gray"></i>
                            <span class="font-medium">À la une</span>
                        </div>
                        @if($actualite->a_la_une)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-gold/20 text-iri-gold border border-iri-gold/30">
                                <i class="fas fa-check mr-1"></i>Oui
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                <i class="fas fa-times mr-1"></i>Non
                            </span>
                        @endif
                    </div>
                    @endif

                    <!-- Date de création -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-plus mr-3 text-iri-gray"></i>
                            <span class="font-medium">Créé le</span>
                        </div>
                        <span class="text-sm text-iri-gray">{{ $actualite->created_at ? $actualite->created_at->format('d/m/Y à H:i') : 'Date non disponible' }}</span>
                    </div>

                    <!-- Dernière modification -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-edit mr-3 text-iri-gray"></i>
                            <span class="font-medium">Modifié le</span>
                        </div>
                        <span class="text-sm text-iri-gray">{{ $actualite->updated_at ? $actualite->updated_at->format('d/m/Y à H:i') : 'Date non disponible' }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions de modération -->
            @can('moderate_actualites')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-shield-alt mr-3"></i>
                        Actions de Modération
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    @if($actualite->statut !== 'publie')
                        @can('publish_actualites')
                        <form action="{{ route('admin.actualite.publish', $actualite) }}" method="POST" class="w-full">
                            @csrf
                            @method('POST')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-check-circle mr-2"></i>
                                Publier l'actualité
                            </button>
                        </form>
                        @endcan
                    @else
                        @can('unpublish', $actualite)
                        <form action="{{ route('admin.actualite.unpublish', $actualite) }}" method="POST" class="w-full">
                            @csrf
                            @method('POST')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-eye-slash mr-2"></i>
                                Dépublier l'actualité
                            </button>
                        </form>
                        @endcan
                    @endif

                    @can('moderate_actualites')
                    @if(isset($actualite->a_la_une) && !$actualite->a_la_une)
                        <form action="{{ route('admin.actualite.toggle-une', $actualite) }}" method="POST" class="w-full">
                            @csrf
                            @method('POST')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-star mr-2"></i>
                                Mettre à la une
                            </button>
                        </form>
                    @elseif(isset($actualite->a_la_une) && $actualite->a_la_une)
                        <form action="{{ route('admin.actualite.toggle-une', $actualite) }}" method="POST" class="w-full">
                            @csrf
                            @method('POST')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-star-half-alt mr-2"></i>
                                Retirer de la une
                            </button>
                        </form>
                    @endif
                    @endcan
                </div>
            </div>
            @endcan

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-cogs mr-3"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    @can('view_actualites')
                    <a href="{{ route('site.actualite.show', $actualite->slug) }}" target="_blank" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Voir sur le site
                    </a>
                    @endcan
                    
                    @can('update_actualites')
                    <a href="{{ route('admin.actualite.edit', $actualite) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier cette actualité
                    </a>
                    @endcan
                    
                    @can('delete_actualites')
                    <form action="{{ route('admin.actualite.destroy', $actualite) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')" 
                          class="w-full">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer cette actualité
                        </button>
                    </form>
                    @endcan
                </div>
            </div>

            <!-- Rapports liés à cette actualité -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-6">
                <div class="px-6 py-4 bg-gradient-to-r from-red-500 to-red-600">
                    <h2 class="text-lg font-semibold text-white flex items-center justify-between">
                        <span class="flex items-center">
                            <i class="fas fa-file-pdf mr-3"></i>
                            Rapports liés à cette actualité
                        </span>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-bold">
                            {{ $actualite->rapports->count() }}
                        </span>
                    </h2>
                </div>
                <div class="p-6">
                    @if($actualite->rapports && $actualite->rapports->count() > 0)
                    <!-- Liste des rapports liés -->
                    <div class="space-y-3 mb-4">
                        @foreach($actualite->rapports as $rapport)
                        <div class="p-4 bg-gradient-to-r from-red-50 to-orange-50 rounded-lg border-2 border-red-100 hover:border-red-300 transition-all duration-200 hover:shadow-md">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-md">
                                        <i class="fas fa-file-pdf text-white text-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-bold text-iri-dark mb-2 line-clamp-2">
                                        {{ $rapport->titre }}
                                    </h3>
                                    <div class="flex items-center gap-3 text-xs text-iri-gray mb-3">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1.5"></i>
                                            {{ $rapport->created_at->format('d/m/Y') }}
                                        </span>
                                        @if($rapport->date_publication)
                                        <span class="flex items-center">
                                            <i class="fas fa-clock mr-1.5"></i>
                                            Publié le {{ \Carbon\Carbon::parse($rapport->date_publication)->format('d/m/Y') }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="flex gap-2">
                                        @if($rapport->fichier)
                                        <a href="{{ asset('storage/' . $rapport->fichier) }}" 
                                           target="_blank"
                                           class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white text-xs font-semibold rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="fas fa-download mr-2"></i>
                                            Télécharger le PDF
                                        </a>
                                        @endif
                                        @can('update', $actualite)
                                        <form action="{{ route('admin.actualite.detach-rapport', [$actualite, $rapport->id]) }}" 
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir détacher ce rapport de cette actualité ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 text-xs font-semibold rounded-lg hover:bg-red-200 transition-all duration-200">
                                                <i class="fas fa-unlink mr-2"></i>
                                                Détacher
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <!-- Aucun rapport lié -->
                    <div class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-4 border-2 border-dashed border-gray-300">
                            <i class="fas fa-file-pdf text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-iri-gray mb-1">Aucun rapport lié à cette actualité</p>
                        <p class="text-xs text-gray-500">Utilisez le bouton ci-dessous pour ajouter des rapports</p>
                    </div>
                    @endif

                    <!-- Bouton pour ajouter des rapports via le modal -->
                    @can('create_rapports')
                    <div class="pt-4 border-t-2 border-gray-200">
                        <button type="button" 
                                @click="$dispatch('open-modal-rapports')"
                                class="w-full inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-bold rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-plus-circle mr-2 text-lg"></i>
                            Ajouter des rapports PDF
                        </button>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal : Ajout rapide de rapports PDF -->
<div x-data="{ open: false }" 
     @open-modal-rapports.window="open = true"
     @close-modal-rapports.window="open = false"
     @keydown.escape.window="open = false"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" 
         @click="open = false"
         x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"></div>
    
    <!-- Modal Dialog -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full mx-auto overflow-hidden"
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             @click.stop>
            
            <!-- En-tête du modal -->
            <div class="px-6 py-5 text-white" style="background: linear-gradient(to right, #22c55e, #10b981);">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-pdf text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-1">Ajouter des rapports PDF</h3>
                            <p class="text-sm opacity-90">Uploadez un ou plusieurs fichiers PDF</p>
                        </div>
                    </div>
                    <button type="button" 
                            @click="open = false"
                            class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>

            <!-- Corps du modal -->
            <div class="p-8">
                <form id="formAjoutRapportsRapide" action="{{ route('admin.rapports.store-multiple') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Zone d'information -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-5 mb-6">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-blue-600 text-2xl mt-1"></i>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-blue-900 mb-3">Comment ça marche ?</h4>
                                <ul class="text-sm text-blue-800 space-y-2 list-disc pl-5">
                                    <li>Sélectionnez un ou plusieurs fichiers <strong>PDF</strong></li>
                                    <li>Le <strong>nom du fichier</strong> sera utilisé comme titre du rapport</li>
                                    <li>Les rapports seront automatiquement liés à cette actualité</li>
                                    <li>Taille maximale : <strong>10 Mo</strong> par fichier</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Champ de sélection de fichiers -->
                    <div class="mb-6">
                        <label for="rapports_pdf" class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-cloud-upload-alt text-green-600 mr-2"></i>
                            Sélectionnez vos fichiers PDF
                        </label>
                        
                        <input type="file" 
                               class="block w-full text-sm text-gray-700 file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer border-2 border-dashed border-gray-300 rounded-xl p-4 bg-gray-50" 
                               id="rapports_pdf" 
                               name="rapports_pdf[]" 
                               accept=".pdf,application/pdf"
                               multiple
                               required>
                        
                        <div class="mt-3 flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-600"></i>
                            <span>Formats acceptés : <strong>PDF uniquement</strong></span>
                        </div>
                    </div>

                    <!-- Liste des fichiers sélectionnés -->
                    <div id="listefichiersSelectiones" class="mb-6 hidden">
                        <h4 class="text-sm font-semibold text-gray-800 mb-3">
                            <i class="fas fa-list-ul text-green-600 mr-2"></i>
                            Fichiers sélectionnés (<span id="compteurFichiers">0</span>)
                        </h4>
                        <div id="conteneurFichiers" class="space-y-2 max-h-64 overflow-y-auto border-2 border-gray-200 rounded-xl p-4 bg-gray-50"></div>
                    </div>

                    <!-- Champ caché pour l'ID de l'actualité -->
                    <input type="hidden" name="actualite_id" value="{{ $actualite->id }}">

                    <!-- Boutons d'action -->
                    <div class="flex items-center gap-3 justify-end pt-6 border-t-2">
                        <button type="button" 
                                @click="open = false"
                                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-8 py-3 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-105" 
                                id="btnSoumettre" 
                                disabled
                                style="background: linear-gradient(to right, #22c55e, #10b981);">
                            <i class="fas fa-upload mr-2"></i>
                            <span id="texteBouton">Ajouter les rapports</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputFichier = document.getElementById('rapports_pdf');
    const listeFichiers = document.getElementById('listefichiersSelectiones');
    const conteneurFichiers = document.getElementById('conteneurFichiers');
    const compteurFichiers = document.getElementById('compteurFichiers');
    const btnSoumettre = document.getElementById('btnSoumettre');
    const texteBouton = document.getElementById('texteBouton');
    
    if (inputFichier) {
        inputFichier.addEventListener('change', function(e) {
            const fichiers = Array.from(e.target.files);
            
            if (fichiers.length > 0) {
                // Activer le bouton soumettre
                btnSoumettre.disabled = false;
                
                // Afficher la liste
                listeFichiers.classList.remove('hidden');
                compteurFichiers.textContent = fichiers.length;
                texteBouton.textContent = fichiers.length > 1 ? `Ajouter ${fichiers.length} rapports` : 'Ajouter 1 rapport';
                
                // Générer la liste des fichiers
                conteneurFichiers.innerHTML = '';
                fichiers.forEach((fichier, index) => {
                    // Extraire le nom sans extension
                    const nomSansExtension = fichier.name.replace(/\.pdf$/i, '');
                    const tailleMo = (fichier.size / (1024 * 1024)).toFixed(2);
                    
                    // Vérifier si c'est un PDF
                    const estPdf = fichier.type === 'application/pdf' || fichier.name.toLowerCase().endsWith('.pdf');
                    const tailleOk = fichier.size <= 10 * 1024 * 1024; // 10 Mo
                    
                    const itemHtml = `
                        <div class="flex items-start gap-3 p-4 bg-white rounded-lg border-2 ${!estPdf || !tailleOk ? 'border-red-500' : 'border-gray-200'}">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: ${estPdf && tailleOk ? 'linear-gradient(to bottom right, #ef4444, #dc2626)' : 'linear-gradient(to bottom right, #9ca3af, #6b7280)'};">
                                    <i class="fas ${estPdf ? 'fa-file-pdf' : 'fa-exclamation-triangle'} text-white"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm text-gray-900 mb-1">
                                    ${nomSansExtension}
                                </p>
                                <p class="text-xs text-gray-600 mb-0">
                                    <i class="fas fa-file mr-1"></i>
                                    ${fichier.name} • ${tailleMo} Mo
                                </p>
                                ${!estPdf ? '<p class="text-xs text-red-600 mb-0 mt-1"><i class="fas fa-times-circle mr-1"></i>Format invalide (PDF requis)</p>' : ''}
                                ${!tailleOk ? '<p class="text-xs text-red-600 mb-0 mt-1"><i class="fas fa-times-circle mr-1"></i>Fichier trop volumineux (max 10 Mo)</p>' : ''}
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${estPdf && tailleOk ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${estPdf && tailleOk ? '✓ Valide' : '✗ Invalide'}
                                </span>
                            </div>
                        </div>
                    `;
                    conteneurFichiers.innerHTML += itemHtml;
                });
                
                // Vérifier si tous les fichiers sont valides
                const tousValides = fichiers.every(f => {
                    const estPdf = f.type === 'application/pdf' || f.name.toLowerCase().endsWith('.pdf');
                    const tailleOk = f.size <= 10 * 1024 * 1024;
                    return estPdf && tailleOk;
                });
                
                btnSoumettre.disabled = !tousValides;
            } else {
                listeFichiers.classList.add('hidden');
                btnSoumettre.disabled = true;
                texteBouton.textContent = 'Ajouter les rapports';
            }
        });
    }
});
</script>

@endsection
