@use('Illuminate\Support\Facades\Storage')
@extends('layouts.iri')

@section('content')
<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        
@section('breadcrumb')
    <x-breadcrumb-overlay :items="[
        ['title' => 'Publications', 'url' => null]
    ]" />
@endsection
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 drop-shadow-2xl">
                Publications & Rapports
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed drop-shadow-lg">
                Découvrez nos publications scientifiques et nos rapports de recherche : articles, études et documents issus de nos travaux de recherche et d'intervention
            </p>
        </div>
    </section>

    <!-- Filters and Search Section -->
    <section class="py-12 bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                <!-- Category Filter -->
                <div class="w-full lg:w-auto">
                    <form action="{{ route('site.publications') }}" method="GET" class="flex items-center gap-3">
                        <label class="text-gray-700 font-medium">Filtrer par :</label>
                        <select name="categorie" class="bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-700 focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-all duration-200">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}" 
                                    {{ request('categorie') == $categorie->id ? 'selected' : '' }}>
                                    {{ $categorie->nom }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-gradient-to-r from-iri-primary to-iri-secondary text-white px-6 py-2 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-filter mr-2"></i>Filtrer
                        </button>
                    </form>
                </div>

                <!-- Search Bar -->
                <div class="w-full lg:w-auto" x-data="{ searchQuery: '{{ request('q') }}' }">
                    <form action="{{ route('site.search') }}" method="GET" class="flex items-center gap-3">
                        <div class="relative">
                            <input type="text" name="q" x-model="searchQuery"
                                placeholder="Rechercher une publication..."
                                class="bg-white border border-gray-300 rounded-lg pl-10 pr-4 py-2 w-80 text-gray-700 focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-all duration-200">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <button type="submit" 
                            :disabled="!searchQuery || searchQuery.trim() === ''"
                            :class="searchQuery && searchQuery.trim() !== '' ? 'bg-gradient-to-r from-iri-accent to-iri-gold text-white hover:shadow-lg transform hover:scale-105' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                            class="px-6 py-2 rounded-lg transition-all duration-200">
                            Rechercher
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Publications Grid -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(optional($publications)->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($publications as $item)
                        @php
                            // Déterminer le type d'élément (Publication ou Rapport)
                            $isRapport = $item instanceof \App\Models\Rapport;
                            $fileField = $isRapport ? $item->fichier : $item->fichier_pdf;
                            $itemType = $isRapport ? 'Rapport' : 'Publication';
                            $description = $isRapport ? $item->description : $item->resume;
                        @endphp
                        <div class="group">
                            <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                                <!-- PDF Preview -->
                                <div class="relative h-64 overflow-hidden">
                                    <!-- Category Badge -->
                                    @php
                                        $categoryName = $item->categorie->nom ?? 'Non catégorisé';
                                        $badgeClass = match ($categoryName) {
                                            'Rapport' => 'bg-gradient-to-r from-blue-500 to-blue-600',
                                            'Article' => 'bg-gradient-to-r from-yellow-500 to-orange-500',
                                            'Document' => 'bg-gradient-to-r from-purple-500 to-pink-500',
                                            'Publication scientifique' => 'bg-gradient-to-r from-emerald-500 to-teal-500',
                                            'Actualité' => 'bg-gradient-to-r from-red-500 to-pink-500',
                                            default => 'bg-gradient-to-r from-iri-primary to-iri-secondary',
                                        };
                                    @endphp

                                    <!-- Type Badge (Rapport/Publication) -->
                                    <div class="absolute top-3 right-3 z-20 bg-white/90 text-gray-700 text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                        {{ $itemType }}
                                    </div>

                                    <!-- Category Badge -->
                                    <div class="absolute top-3 left-3 z-20 {{ $badgeClass }} text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                        {{ $categoryName }}
                                    </div>

                                    @if($fileField)
                                        <canvas id="pdf-canvas-{{$item->id}}-{{ $isRapport ? 'rapport' : 'publication' }}" 
                                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" 
                                                data-pdf-url="{{ Storage::url($fileField) }}">
                                        </canvas>
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            <i class="fas fa-file-alt text-gray-400 text-4xl"></i>
                                        </div>
                                    @endif

                                    <!-- Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="absolute bottom-4 left-4 right-4">
                                            <a href="{{ route('publication.show', ['slug' => $item->slug]) }}" 
                                               class="inline-flex items-center justify-center w-full bg-white/20 backdrop-blur-sm text-white font-medium py-2 px-4 rounded-lg border border-white/30 hover:bg-white/30 transition-all duration-200">
                                                <i class="fas fa-eye mr-2"></i>
                                                {{ $isRapport ? 'Voir le rapport' : 'Voir la publication' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $item->created_at->format('d M Y') }}
                                        </span>
                                    </div>
                                    
                                    <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-iri-primary transition-colors duration-200">
                                        <a href="{{ route('publication.show', ['slug' => $item->slug]) }}" class="hover:underline">
                                            {{ $item->titre }}
                                        </a>
                                    </h3>

                                    @if($description)
                                        <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                            {{ Str::limit(strip_tags($description), 120) }}
                                        </p>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        <a href="{{ route('publication.show', ['slug' => $item->slug]) }}" 
                                           class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-semibold text-sm transition-colors duration-200">
                                            Lire plus
                                            <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform duration-200"></i>
                                        </a>
                                        
                                        @if($fileField)
                                            <a href="{{ Storage::url($fileField) }}" 
                                               target="_blank"
                                               class="inline-flex items-center text-gray-500 hover:text-iri-accent text-sm transition-colors duration-200">
                                                <i class="fas fa-download mr-1"></i>
                                                {{ $isRapport ? $item->getFileType() : 'PDF' }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="bg-gradient-to-br from-iri-primary to-iri-secondary w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-file-alt text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucun document trouvé</h3>
                        <p class="text-gray-600 mb-6">
                            Aucune publication ou rapport ne correspond à vos critères de recherche.
                        </p>
                        <a href="{{ route('site.publications') }}" 
                           class="inline-flex items-center bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-3 px-6 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-refresh mr-2"></i>
                            Voir tous les documents
                        </a>
                    </div>
                </div>
            @endif

            <!-- Pagination -->
            @if($publications->hasPages())
                <div class="mt-12 flex justify-center">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        {{ $publications->links('pagination::tailwind') }}
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    document.querySelectorAll('canvas[data-pdf-url]').forEach(canvas => {
        const url = canvas.getAttribute('data-pdf-url');
        const ctx = canvas.getContext('2d');
        const containerWidth = canvas.parentElement.offsetWidth;

        pdfjsLib.getDocument(url).promise.then(pdf => {
            return pdf.getPage(1);
        }).then(page => {
            const viewport = page.getViewport({ scale: 1 });
            const scale = containerWidth / viewport.width;
            const scaledViewport = page.getViewport({ scale });

            canvas.width = scaledViewport.width;
            canvas.height = scaledViewport.height;

            return page.render({ canvasContext: ctx, viewport: scaledViewport }).promise;
        }).catch(err => {
            console.error("Erreur lors du rendu du PDF :", err);
        });
    });
});
</script>

{{-- Boutons de partage social pour la page des publications --}}
<x-global-social-share 
    position="fixed-right"
    style="default"
    size="medium"
    :showLabels="false"
    platforms="facebook,twitter,linkedin,email"
    customTitle="Publications & Rapports - GRN-UCBC"
    customDescription="Découvrez nos publications scientifiques et rapports de recherche en gouvernance foncière et développement durable"
    :analytics="true" />
@endsection
