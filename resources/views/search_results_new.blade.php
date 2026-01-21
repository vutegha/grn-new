@extends('layouts.iri')

@section('title', 'Résultats de recherche')

@section('content')
<!-- Breadcrumb -->
@include('partials.breadcrumb', [
    'breadcrumbs' => [
        ['title' => 'Recherche', 'url' => null]
    ]
])

<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                Résultats de recherche
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed drop-shadow-lg">
                Recherche pour : <span class="font-bold">"{{ $query }}"</span>
            </p>
            
            @if($totalResults > 0)
                <div class="mt-6 inline-flex items-center bg-white/20 backdrop-blur-sm border border-white/30 text-white px-4 py-2 rounded-full text-sm font-medium">
                    <i class="fas fa-search mr-2"></i>
                    {{ $totalResults }} résultat{{ $totalResults > 1 ? 's' : '' }} trouvé{{ $totalResults > 1 ? 's' : '' }}
                    @if(isset($elapsed))
                        en {{ $elapsed }} seconde{{ $elapsed > 1 ? 's' : '' }}
                    @endif
                </div>
            @endif
        </div>
    </section>

    <!-- Search Results -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Search Again -->
            <div class="mb-12">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Affiner votre recherche</h2>
                    <form action="{{ route('site.search') }}" method="GET" class="flex items-center gap-4" onsubmit="return validateSearch(this)">
                        <div class="flex-1">
                            <input type="text" 
                                   name="q" 
                                   value="{{ $query }}"
                                   placeholder="Rechercher dans les publications, actualités, rapports..."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-all duration-200">
                        </div>
                        <button type="submit" 
                                class="bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-3 px-6 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-search mr-2"></i>
                            Rechercher
                        </button>
                    </form>
                </div>
            </div>

            @php
            function extractAndHighlight($text, $keyword, $length = 150) {
                $keyword = trim($keyword);
                if (!$keyword) return Str::limit($text, $length);

                $pos = stripos($text, $keyword);
                if ($pos === false) return Str::limit($text, $length);

                $start = max($pos - intval($length / 2), 0);
                $snippet = substr($text, $start, $length);
                if ($start > 0) $snippet = '...' . $snippet;
                if ($start + $length < strlen($text)) $snippet .= '...';
                
                return preg_replace("/(" . preg_quote($keyword, '/') . ")/i", '<mark class="bg-yellow-200 px-1 rounded">$1</mark>', $snippet);
            }
            @endphp

            @if($results && $results->count() > 0)
                <!-- Results Grid -->
                <div class="space-y-6">
                    @foreach($results as $item)
                        <article class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100 group">
                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 p-6">
                                <!-- Thumbnail -->
                                <div class="lg:col-span-1">
                                    <div class="aspect-square lg:aspect-[4/3] rounded-lg overflow-hidden bg-gray-100 relative">
                                        @if($item->type_global === 'Actualité' && $item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" 
                                                 alt="{{ $item->titre ?? 'Actualité' }}" 
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                        @elseif(($item->type_global === 'Publication' && $item->fichier_pdf) || ($item->type_global === 'Rapport' && $item->fichier))
                                            <div class="w-full h-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                                                <i class="fas fa-file-pdf text-white text-4xl"></i>
                                            </div>
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-iri-primary to-iri-secondary flex items-center justify-center">
                                                <i class="fas fa-file-alt text-white text-4xl"></i>
                                            </div>
                                        @endif

                                        <!-- Type Badge -->
                                        @php
                                            $badgeClass = match($item->type_global) {
                                                'Publication' => 'bg-blue-500',
                                                'Actualité' => 'bg-green-500',
                                                'Rapport' => 'bg-red-500',
                                                default => 'bg-gray-500'
                                            };
                                        @endphp
                                        <div class="absolute top-2 left-2 {{ $badgeClass }} text-white text-xs font-bold px-2 py-1 rounded-full">
                                            {{ $item->type_global }}
                                        </div>

                                        <!-- File Extension Badge -->
                                        @if(($item->type_global === 'Publication' && $item->fichier_pdf) || ($item->type_global === 'Rapport' && $item->fichier))
                                            @php
                                                $fileUrl = $item->type_global === 'Publication' 
                                                    ? Storage::url($item->fichier_pdf)
                                                    : Storage::url($item->fichier);
                                                $ext = strtoupper(pathinfo($fileUrl, PATHINFO_EXTENSION));
                                            @endphp
                                            <div class="absolute top-2 right-2 bg-black/70 text-white text-xs font-bold px-2 py-1 rounded-full">
                                                {{ $ext }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="lg:col-span-3">
                                    <div class="h-full flex flex-col">
                                        <!-- Header -->
                                        <div class="mb-3">
                                            <h2 class="text-xl lg:text-2xl font-bold text-gray-900 mb-2 group-hover:text-iri-primary transition-colors duration-200">
                                                {{ $item->titre }}
                                            </h2>
                                            
                                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                                <span class="flex items-center">
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    {{ $item->date_global->format('d M Y') }}
                                                </span>
                                                
                                                @if(property_exists($item, 'categorie') && $item->categorie)
                                                    <span class="flex items-center">
                                                        <i class="fas fa-tag mr-1"></i>
                                                        {{ $item->categorie->nom }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="flex-1 mb-4">
                                            <p class="text-gray-700 leading-relaxed">
                                                @if($item->type_global === 'Publication' && $item->resume)
                                                    {!! extractAndHighlight($item->resume, $query) !!}
                                                @elseif($item->type_global === 'Rapport' && $item->resume)
                                                    {!! extractAndHighlight($item->resume, $query) !!}
                                                @elseif($item->type_global === 'Actualité' && $item->contenu)
                                                    {!! extractAndHighlight($item->contenu, $query) !!}
                                                @else
                                                    Aucune description disponible.
                                                @endif
                                            </p>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex items-center justify-between">
                                            @if($item->type_global === 'Publication')
                                                <a href="{{ route('publication.show', ['slug' => $item->slug]) }}" 
                                                   class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-semibold transition-colors duration-200">
                                                    <i class="fas fa-eye mr-2"></i>
                                                    Voir la publication
                                                </a>
                                            @elseif($item->type_global === 'Actualité')
                                                <a href="{{ route('site.actualite.show', ['slug' => $item->slug]) }}" 
                                                   class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-semibold transition-colors duration-200">
                                                    <i class="fas fa-newspaper mr-2"></i>
                                                    Lire l'actualité
                                                </a>
                                            @elseif($item->type_global === 'Rapport' && $item->fichier)
                                                <a href="{{ asset('storage/'.$item->fichier) }}" 
                                                   target="_blank"
                                                   class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-semibold transition-colors duration-200">
                                                    <i class="fas fa-download mr-2"></i>
                                                    Télécharger le rapport
                                                </a>
                                            @endif

                                            <!-- Share Button -->
                                            <button class="text-gray-400 hover:text-iri-accent transition-colors duration-200" 
                                                    title="Partager">
                                                <i class="fas fa-share-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($results->hasPages())
                    <div class="mt-12 flex justify-center">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            {{ $results->appends(['q' => $query])->links('pagination::tailwind') }}
                        </div>
                    </div>
                @endif
            @else
                <!-- No Results -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="bg-gradient-to-br from-gray-400 to-gray-500 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-search text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucun résultat trouvé</h3>
                        <p class="text-gray-600 mb-6">
                            Nous n'avons trouvé aucun résultat pour <strong>"{{ $query }}"</strong>.<br>
                            Essayez avec d'autres mots-clés ou vérifiez l'orthographe.
                        </p>
                        
                        <!-- Suggestions -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h4 class="font-bold text-gray-900 mb-3">Suggestions :</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Utilisez des mots-clés plus généraux</li>
                                <li>• Vérifiez l'orthographe de vos termes</li>
                                <li>• Essayez des synonymes</li>
                                <li>• Utilisez moins de mots</li>
                            </ul>
                        </div>

                        <div class="space-y-4">
                            <a href="{{ route('site.publications') }}" 
                               class="inline-flex items-center bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-3 px-6 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-file-alt mr-2"></i>
                                Voir toutes les publications
                            </a>
                            <div>
                                <a href="{{ route('site.home') }}" 
                                   class="text-iri-primary hover:text-iri-secondary font-semibold transition-colors duration-200">
                                    <i class="fas fa-home mr-1"></i>
                                    Retour à l'accueil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>

<!-- Styles -->
<style>
    mark {
        background-color: #fef08a !important;
        padding: 2px 4px;
        border-radius: 4px;
        font-weight: bold;
    }
    
    .aspect-square {
        aspect-ratio: 1 / 1;
    }
</style>
@endsection
