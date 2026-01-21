@extends('layouts.iri')

@section('content')
<!-- Breadcrumb -->
@include('partials.breadcrumb', [
    'breadcrumbs' => [
        ['title' => 'Actualités', 'url' => null]
    ]
])

<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 drop-shadow-2xl">
                Actualités
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed drop-shadow-lg">
                Suivez les dernières nouvelles et développements du GRN-UCBC
            </p>
        </div>
    </section>

    <!-- Articles Grid -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($actualites->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($actualites as $item)
                        <article class="group" x-data="{ expanded: false }">
                            <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                                <!-- Article Image -->
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('assets/img/iri.jpg') }}" 
                                         alt="{{ $item->titre }}" 
                                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                    
                                    <!-- Date Badge -->
                                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-gray-700 text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                        {{ $item->created_at->format('d M Y') }}
                                    </div>

                                    <!-- Category Badge -->
                                    <div class="absolute top-3 left-3 bg-iri-accent/90 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                        <i class="fas fa-newspaper mr-1"></i>
                                        Actualité
                                    </div>

                                    <!-- Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="absolute bottom-4 left-4 right-4">
                                            <a href="{{ route('site.actualite.show', ['slug' => $item->slug]) }}" 
                                               class="inline-flex items-center justify-center w-full bg-white/20 backdrop-blur-sm text-white font-medium py-2 px-4 rounded-lg border border-white/30 hover:bg-white/30 transition-all duration-200">
                                                <i class="fas fa-eye mr-2"></i>
                                                Lire l'article
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-iri-primary transition-colors duration-200">
                                        <a href="{{ route('site.actualite.show', ['slug' => $item->slug]) }}" class="hover:underline">
                                            {{ $item->titre }}
                                        </a>
                                    </h3>

                                    <!-- Summary -->
                                    @if($item->resume)
                                        <div class="mb-4">
                                            <p class="text-gray-600 text-sm" 
                                               :class="{ 'line-clamp-3': !expanded, 'line-clamp-none': expanded }">
                                                {{ $item->resume }}
                                            </p>
                                            @if(strlen($item->resume) > 120)
                                                <button @click="expanded = !expanded" 
                                                        class="mt-2 text-xs text-iri-primary hover:text-iri-secondary underline transition-colors duration-200">
                                                    <span x-text="expanded ? 'Voir moins' : 'Voir plus'"></span>
                                                </button>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Action Button -->
                                    <div class="flex items-center justify-between">
                                        <a href="{{ route('site.actualite.show', ['slug' => $item->slug]) }}" 
                                           class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-semibold text-sm transition-colors duration-200">
                                            Lire plus
                                            <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform duration-200"></i>
                                        </a>

                                        <!-- Share Button -->
                                        <button class="text-gray-400 hover:text-iri-accent transition-colors duration-200" 
                                                title="Partager">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($actualites->hasPages())
                    <div class="mt-12 flex justify-center">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            {{ $actualites->links('pagination::tailwind') }}
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="bg-gradient-to-br from-iri-primary to-iri-secondary w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-newspaper text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucune actualité disponible</h3>
                        <p class="text-gray-600 mb-6">
                            Les actualités seront publiées ici dès qu'elles seront disponibles.
                        </p>
                        <a href="{{ route('site.home') }}" 
                           class="inline-flex items-center bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-3 px-6 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-home mr-2"></i>
                            Retour à l'accueil
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>

<!-- Alpine.js Script for expand/collapse -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Styles -->
<style>
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
    .line-clamp-none {
        display: block;
        -webkit-line-clamp: none;
        -webkit-box-orient: initial;
        overflow: visible;
    }
</style>
@endsection
