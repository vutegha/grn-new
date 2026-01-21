@extends('layouts.iri')

@section('title', 'Publication - ' . $publication->titre)

@section('content')
<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <!-- Breadcrumb Overlay -->
        @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
            <nav class="absolute top-4 left-0 right-0 z-10" aria-label="Breadcrumb">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li>
                            <a href="{{ url('/') }}" class="text-white/70 hover:text-white transition-colors duration-200">
                                <i class="fas fa-home mr-1"></i>
                                Accueil
                            </a>
                        </li>
                        
                        @foreach($breadcrumbs as $breadcrumb)
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-white/50 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                @if($loop->last)
                                    <span class="text-white font-medium">{{ $breadcrumb['title'] }}</span>
                                @else
                                    <a href="{{ $breadcrumb['url'] }}" class="text-white/70 hover:text-white transition-colors duration-200">
                                        {{ $breadcrumb['title'] }}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </div>
            </nav>
        @endif
        
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <!-- Content -->
                <div class="lg:col-span-2">
                    <!-- Category Badge -->
                    @php
                        $categoryName = $publication->categorie->nom ?? 'Non catégorisé';
                        $badgeClass = match ($categoryName) {
                            'Rapport' => 'bg-blue-500/20 text-blue-100 border-blue-300/30',
                            'Article' => 'bg-yellow-500/20 text-yellow-100 border-yellow-300/30',
                            'Document' => 'bg-purple-500/20 text-purple-100 border-purple-300/30',
                            'Publication scientifique' => 'bg-emerald-500/20 text-emerald-100 border-emerald-300/30',
                            'Actualité' => 'bg-red-500/20 text-red-100 border-red-300/30',
                            default => 'bg-white/20 text-white border-white/30',
                        };
                    @endphp
                    
                    <div class="inline-flex items-center {{ $badgeClass }} border backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-6">
                        <i class="fas fa-tag mr-2"></i>
                        {{ $categoryName }}
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                        {{ $publication->titre }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-6 text-white/90 mb-8">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>{{ $publication->created_at->format('d M Y') }}</span>
                        </div>
                        @if($publication->auteur)
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                <span>{{ $publication->auteur->nom }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <button onclick="showToastAgain()"
                                class="bg-white/20 backdrop-blur-sm border border-white/30 text-white font-semibold py-3 px-6 rounded-lg hover:bg-white/30 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-book-open mr-2"></i>
                            Lire le résumé
                        </button>
                        
                        @if($publication->fichier_pdf)
                            <a href="{{ asset('storage/'.$publication->fichier_pdf) }}" 
                               target="_blank"
                               class="bg-iri-gold/80 backdrop-blur-sm border border-iri-gold/50 text-white font-semibold py-3 px-6 rounded-lg hover:bg-iri-gold transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger PDF
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Publication Preview -->
                <div class="lg:col-span-1">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 p-6 shadow-2xl">
                        @if($publication->fichier_pdf)
                            <div class="aspect-[3/4] bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-lg">
                                <i class="fas fa-file-pdf text-white text-6xl"></i>
                            </div>
                        @else
                            <div class="aspect-[3/4] bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-alt text-white/50 text-6xl"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Resume Toast/Modal -->
    <div id="resumeToast"
         class="fixed left-6 top-[20vh] bg-white rounded-2xl shadow-2xl border border-gray-200 max-w-md w-full overflow-hidden z-50 hidden"
         style="max-height: 70vh;">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-iri-primary to-iri-secondary text-white p-4 flex justify-between items-center">
            <h4 class="text-lg font-bold flex items-center">
                <i class="fas fa-book-open mr-2"></i>
                Résumé de la publication
            </h4>
            <button onclick="closeToast()" class="text-white/80 hover:text-white text-xl">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto" style="max-height: calc(70vh - 80px);">
            <p class="text-gray-700 leading-relaxed">
                {{ $publication->resume ?? 'Aucun résumé disponible pour cette publication.' }}
            </p>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button onclick="showToastAgain()"
            class="fixed bottom-6 left-6 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-full shadow-lg w-14 h-14 flex items-center justify-center hover:shadow-xl transform hover:scale-110 transition-all duration-200 z-[60]">
        <i class="fas fa-book-open text-lg"></i>
    </button>

    <!-- Main Content Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-6">
                        <!-- Back Button -->
                        <div class="mb-6">
                            <a href="{{ route('site.publications') }}" 
                               class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-semibold transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Retour aux publications
                            </a>
                        </div>

                        <!-- Publication Info -->
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Informations</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-calendar-alt mr-3 text-iri-primary w-4"></i>
                                    <span>{{ $publication->created_at->format('d M Y') }}</span>
                                </div>
                                @if($publication->auteur)
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-user mr-3 text-iri-primary w-4"></i>
                                        <span>{{ $publication->auteur->nom }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-tag mr-3 text-iri-primary w-4"></i>
                                    <span>{{ $publication->categorie->nom ?? 'Non catégorisé' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Citation -->
                        @if($publication->citation)
                            <div class="mb-6 p-4 bg-gray-50 rounded-lg border-l-4 border-iri-primary">
                                <h4 class="font-semibold text-gray-900 mb-2">Comment citer :</h4>
                                <p class="text-sm text-gray-700 italic">{{ $publication->citation }}</p>
                            </div>
                        @endif

                        <!-- Related Publications -->
                        @if(optional($autresPublications)->count() > 0)
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 mb-4">Publications similaires</h4>
                                <div class="space-y-3">
                                    @foreach($autresPublications->take(3) as $otherPub)
                                        <a href="{{ route('publication.show', $otherPub->slug) }}" 
                                           class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                            <h5 class="font-medium text-gray-900 text-sm mb-1 line-clamp-2">
                                                {{ $otherPub->titre }}
                                            </h5>
                                            <p class="text-xs text-gray-500">
                                                {{ $otherPub->created_at->format('d M Y') }}
                                            </p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-gray-50 to-white p-6 border-b border-gray-200">
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $publication->titre }}</h2>
                            
                            @if($publication->description)
                                <div class="prose prose-gray max-w-none">
                                    {!! $publication->description !!}
                                </div>
                            @endif
                        </div>

                        <!-- PDF Viewer Simple -->
                        @if($publication->fichier_pdf)
                            @php
                                $fileUrl = asset('storage/' . $publication->fichier_pdf);
                                $extension = pathinfo($publication->fichier_pdf, PATHINFO_EXTENSION);
                            @endphp
                            
                            @if($extension === 'pdf')
                                <!-- Visualiseur PDF -->
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                    <!-- Header -->
                                    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-200">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                                <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                                Document PDF
                                            </h3>
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ $fileUrl }}" 
                                                   target="_blank" 
                                                   class="inline-flex items-center px-4 py-2 bg-iri-primary text-white text-sm font-medium rounded-lg hover:bg-iri-secondary transition-colors duration-200">
                                                    <i class="fas fa-external-link-alt mr-2"></i>Ouvrir
                                                </a>
                                                <a href="{{ $fileUrl }}" 
                                                   download 
                                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                                    <i class="fas fa-download mr-2"></i>Télécharger
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PDF Content -->
                                    <div class="p-6">
                                        <iframe src="{{ $fileUrl }}" 
                                                width="100%" 
                                                height="600" 
                                                class="border rounded-lg shadow-sm"
                                                title="Visualiseur PDF">
                                            <p class="text-center text-gray-600 p-6">
                                                Votre navigateur ne prend pas en charge l'affichage des PDF. 
                                                <a href="{{ $fileUrl }}" target="_blank" class="text-iri-primary hover:underline">
                                                    Cliquez ici pour ouvrir le PDF.
                                                </a>
                                            </p>
                                        </iframe>
                                    </div>
                                </div>
                            @else
                                <!-- Autres types de fichiers -->
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden p-8 text-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-file text-2xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Fichier {{ strtoupper($extension) }}</h3>
                                    <p class="text-gray-600 mb-4">Ce type de fichier ne peut pas être affiché dans le navigateur.</p>
                                    <a href="{{ $fileUrl }}" 
                                       download 
                                       class="inline-flex items-center px-6 py-3 bg-iri-primary text-white font-medium rounded-lg hover:bg-iri-secondary transition-colors duration-200">
                                        <i class="fas fa-download mr-2"></i>Télécharger le fichier
                                    </a>
                                </div>
                            @endif
                        @else
                            <!-- Aucun fichier -->
                            <div class="bg-amber-50 border border-amber-200 text-amber-800 p-8 rounded-xl text-center">
                                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-exclamation-triangle text-2xl text-amber-600"></i>
                                </div>
                                <h3 class="text-xl font-semibold mb-3">Aucun fichier disponible</h3>
                                <p class="text-lg">Cette publication n'a pas de fichier associé.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Scripts -->
<script>
    function closeToast() {
        const toast = document.getElementById('resumeToast');
        toast.classList.add('hidden');
    }

    function showToastAgain() {
        const toast = document.getElementById('resumeToast');
        toast.classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Auto-show resume toast after 3 seconds
        setTimeout(function () {
            showToastAgain();
        }, 3000);
    });
</script>

<!-- Styles -->
<style>
    iframe {
        background: #f8f9fa;
        transition: all 0.3s ease;
    }
    
    iframe:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    @media (max-width: 768px) {
        iframe {
            height: 400px !important;
        }
    }
    
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }
</style>
@endsection
