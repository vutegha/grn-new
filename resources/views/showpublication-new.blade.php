@extends('layouts.iri')

@section('title', 'Ressource - ' . $publication->titre)

@section('head')
    <!-- PDF.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    
    <!-- PDF Optimized Styles -->
    <link rel="stylesheet" href="{{ asset('css/pdf-viewer-optimized.css') }}">
    
    <!-- PDF Lazy Loader Script -->
    <script src="{{ asset('js/pdf-lazy-loader.js') }}"></script>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Publication Header -->
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <!-- Breadcrumb -->
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('accueil') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-3a1 1 0 011-1h2a1 1 0 011 1v3a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                Accueil
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('publications.index') }}" class="ml-1 text-sm font-medium text-gray-500 hover:text-gray-700 md:ml-2">Publications</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 truncate">{{ Str::limit($publication->titre, 30) }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Publication Info -->
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl sm:truncate">
                            {{ $publication->titre }}
                        </h1>
                        
                        <div class="mt-4 flex flex-col sm:flex-row sm:flex-wrap sm:space-x-6">
                            @if($publication->auteur)
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $publication->auteur->nom }} {{ $publication->auteur->prenom }}
                            </div>
                            @endif
                            
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $publication->created_at->format('d/m/Y') }}
                            </div>
                            
                            @if($publication->categorie)
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $publication->categorie->nom }}
                            </div>
                            @endif
                        </div>
                        
                        @if($publication->description)
                        <div class="mt-4">
                            <p class="text-sm text-gray-700 leading-5">
                                {{ Str::limit($publication->description, 200) }}
                            </p>
                        </div>
                        @endif
                    </div>
                    
                    <div class="mt-5 flex lg:mt-0 lg:ml-4">
                        <span class="hidden sm:block">
                            <a href="{{ Storage::url($publication->fichier_pdf) }}" 
                               download="{{ Str::slug($publication->titre) }}.pdf"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-coral">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m5.016-1.906a8.963 8.963 0 01-2.874 6.578l.077.077a8.956 8.956 0 01-6.594 2.781H5.09c-2.4 0-3.806-1.336-3.806-3.202 0-1.21.736-2.27 1.906-2.874a8.963 8.963 0 016.578-2.874l-.077-.077A8.956 8.956 0 0115.275 5.09c2.4 0 3.806 1.336 3.806 3.202 0 1.21-.736 2.27-1.906 2.874z"></path>
                                </svg>
                                Télécharger PDF
                            </a>
                        </span>

                        <span class="ml-3">
                            <button onclick="window.history.back()" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-coral hover:bg-coral-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-coral">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Retour
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PDF Viewer Component -->
    @include('components.pdf-viewer', ['publication' => $publication])
</div>

<script>
// Configure PDF.js worker
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

// Analytics and Performance Monitoring
document.addEventListener('DOMContentLoaded', function() {
    // Track PDF view
    if (typeof gtag === 'function') {
        gtag('event', 'pdf_view', {
            'event_category': 'Publications',
            'event_label': '{{ $publication->titre }}',
            'value': 1
        });
    }
    
    // Performance monitoring
    const startTime = performance.now();
    
    // Monitor PDF loading performance
    window.addEventListener('load', function() {
        const loadTime = performance.now() - startTime;
        console.log(`📊 Page load time: ${loadTime.toFixed(2)}ms`);
        
        // Send performance data
        if (typeof gtag === 'function') {
            gtag('event', 'timing_complete', {
                'name': 'pdf_page_load',
                'value': Math.round(loadTime)
            });
        }
    });
});

// Error handling and reporting
window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
    
    // Report to analytics
    if (typeof gtag === 'function') {
        gtag('event', 'exception', {
            'description': e.error.message,
            'fatal': false
        });
    }
});
</script>
@endsection
