@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.iri')

@section('title', 'Publication - ' . e($publication->titre))

@push('meta')
    <x-social-meta 
        :title="$publication->titre"
        :description="Str::limit(strip_tags($publication->resume ?? $publication->description ?? ''), 160)"
        :image="$publication->image ? asset('storage/' . $publication->image) : asset('assets/img/logos/iri-logo.png')"
        :url="route('publication.show', ['slug' => $publication->slug])"
        :imageAlt="$publication->titre"
        :publishedAt="$publication->created_at->toISOString()"
        :modifiedAt="$publication->updated_at && $publication->updated_at != $publication->created_at ? $publication->updated_at->toISOString() : null"
        :author="$publication->auteur?->nom"
    />
    <meta name="twitter:site" content="@GRNUCBC">
    <meta property="og:type" content="article">
    
    <!-- Description générale -->
    <meta name="description" content="{{ Str::limit(strip_tags($publication->resume ?? $publication->description ?? ''), 160) }}">
    <meta name="keywords" content="publication, rapport, GRN, UCBC, RDC, {{ e($publication->titre) }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ route('publication.show', ['slug' => $publication->slug]) }}">
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/publication-show.css') }}">
<!-- Feuille de style CKEditor - PRIORITAIRE pour le contenu des publications -->
<link rel="stylesheet" href="{{ asset('css/ckeditor-content.css') }}" data-priority="high">
@endpush

@section('content')
<!-- Main Content avec Alpine.js pour le téléchargement -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white" 
     x-data="{
         showModal: false,
         loading: false,
         showToast: false,
         toastMessage: '',
         toastNewSubscriber: false,
         
         selectedRapport: {
             id: null,
             title: ''
         },
         
         email: '',
         errors: {},
         attemptedSubmit: false,
         
         get isEmailValid() {
             const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
             return this.email.trim().length > 0 && emailRegex.test(this.email.trim());
         },
         
         openDownloadModal(rapportId, rapportTitle) {
             this.selectedRapport = {
                 id: rapportId,
                 title: rapportTitle
             };
             
             this.email = '';
             this.errors = {};
             this.attemptedSubmit = false;
             this.loading = false;
             this.showModal = true;
         },
         
         closeModal() {
             this.showModal = false;
             this.email = '';
             this.errors = {};
             this.attemptedSubmit = false;
             this.loading = false;
         },
         
         clearEmailError() {
             if (this.errors.email) {
                 this.errors = {};
                 this.attemptedSubmit = false;
             }
         },
         
         async submitDownload() {
             this.attemptedSubmit = true;
             
             if (!this.isEmailValid) {
                 this.errors = { email: ['Veuillez saisir une adresse email valide.'] };
                 return;
             }
             
             this.loading = true;
             this.errors = {};
             
             try {
                 const response = await fetch(`/rapport/${this.selectedRapport.id}/validate-email`, {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                         'Accept': 'application/json'
                     },
                     body: JSON.stringify({
                         email: this.email.trim(),
                         publication_id: {{ $publication->id ?? 'null' }}
                     })
                 });
                 
                 const data = await response.json();
                 
                 if (data.success) {
                     window.open(data.download_url, '_blank');
                     this.closeModal();
                     
                     this.toastNewSubscriber = data.newsletter_subscribed;
                     this.toastMessage = data.newsletter_subscribed 
                         ? 'Téléchargement démarré ! Vous êtes maintenant inscrit à notre newsletter.' 
                         : 'Téléchargement démarré !';
                     this.showToast = true;
                     
                     setTimeout(() => { this.showToast = false; }, 5000);
                 } else {
                     this.errors = { email: [data.message || 'Une erreur est survenue lors de la validation.'] };
                 }
             } catch (error) {
                 this.errors = { email: ['Une erreur est survenue. Veuillez réessayer.'] };
             } finally {
                 this.loading = false;
             }
         }
     }">
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
                        <i class="fas fa-tag mr-2" aria-hidden="true"></i>
                        {{ e($categoryName) }}
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                        {{ e($publication->titre) }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-6 text-white/90 mb-8">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2" aria-hidden="true"></i>
                            <time datetime="{{ $publication->created_at->format('Y-m-d') }}">
                                {{ $publication->created_at->format('d M Y') }}
                            </time>
                        </div>
                        @if($publication->auteur)
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2" aria-hidden="true"></i>
                                <a href="{{ route('site.auteur.show', $publication->auteur->id) }}" 
                                   class="hover:underline hover:text-iri-gold transition-colors">
                                    {{ e($publication->auteur->nom) }}
                                </a>
                            </div>
                        @endif
                        
                        <!-- Share Buttons -->
                        <x-social-share-buttons 
                            :url="route('publication.show', ['slug' => $publication->slug])"
                            :title="$publication->titre"
                            :description="Str::limit(strip_tags($publication->resume ?? $publication->description ?? ''), 150)"
                            :image="$publication->image ? asset('storage/' . $publication->image) : ''"
                            style="hero"
                            :platforms="['facebook', 'twitter', 'linkedin', 'whatsapp', 'email']"
                            class="ml-auto" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <button type="button" data-show-toast
                                class="bg-white/20 backdrop-blur-sm border border-white/30 text-white font-semibold py-3 px-6 rounded-lg hover:bg-white/30 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-book-open mr-2" aria-hidden="true"></i>
                            Lire le résumé
                        </button>
                        
                        @if($publication->fichier_pdf)
                            <button type="button"
                                    @click="openDownloadModal({{ $publication->id }}, '{{ addslashes($publication->titre) }}')"
                                    class="bg-iri-gold/80 backdrop-blur-sm border border-iri-gold/50 text-white font-semibold py-3 px-6 rounded-lg hover:bg-iri-gold transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-download mr-2" aria-hidden="true"></i>
                                Télécharger PDF
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Publication Preview -->
                <div class="lg:col-span-1">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 p-6 shadow-2xl">
                        @if($publication->fichier_pdf && Storage::disk('public')->exists($publication->fichier_pdf))
                            <canvas id="pdf-preview" 
                                    class="w-full rounded-lg shadow-lg" 
                                    data-pdf-url="{{ Storage::url($publication->fichier_pdf) }}"
                                    data-pdf-name="{{ e($publication->titre) }}">
                            </canvas>
                        @else
                            <div class="aspect-[3/4] bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-alt text-white/50 text-6xl" aria-hidden="true"></i>
                                <span class="sr-only">Aperçu non disponible</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Resume Toast/Modal -->
    <div id="resumeToast"
         class="fixed left-6 top-[20vh] bg-white rounded-2xl shadow-2xl border border-gray-200 max-w-md w-full overflow-hidden z-50 hidden modal-max-height"
         style="display: none;">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-iri-primary to-iri-secondary text-white p-4 flex justify-between items-center">
            <h4 class="text-lg font-bold flex items-center">
                <i class="fas fa-book-open mr-2" aria-hidden="true"></i>
                Résumé de la publication
            </h4>
            <button type="button" data-close-toast class="text-white/80 hover:text-white text-xl" aria-label="Fermer le résumé">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto modal-max-height">
            <p class="text-gray-700 leading-relaxed">
                {{ e($publication->resume ?? 'Aucun résumé disponible pour cette publication.') }}
            </p>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button type="button" data-show-toast
            class="fixed bottom-6 left-6 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-full shadow-lg w-14 h-14 flex items-center justify-center hover:shadow-xl transform hover:scale-110 transition-all duration-200 z-[60]"
            aria-label="Afficher le résumé">
        <i class="fas fa-book-open text-lg" aria-hidden="true"></i>
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
                                    <i class="fas fa-calendar-alt mr-3 text-iri-primary w-4" aria-hidden="true"></i>
                                    <time datetime="{{ $publication->created_at->format('Y-m-d') }}">
                                        {{ $publication->created_at->format('d M Y') }}
                                    </time>
                                </div>
                                @if($publication->auteur)
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-user mr-3 text-iri-primary w-4" aria-hidden="true"></i>
                                        <a href="{{ route('site.auteur.show', $publication->auteur->id) }}" 
                                           class="hover:text-iri-primary hover:underline transition-colors">
                                            {{ e($publication->auteur->nom) }}
                                        </a>
                                    </div>
                                @endif
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-tag mr-3 text-iri-primary w-4" aria-hidden="true"></i>
                                    <span>{{ e($publication->categorie->nom ?? 'Non catégorisé') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Citation -->
                        @if($publication->citation)
                            <div class="mb-6 p-4 bg-gray-50 rounded-lg border-l-4 border-iri-primary">
                                <h4 class="font-semibold text-gray-900 mb-2">Comment citer :</h4>
                                <p class="text-sm text-gray-700 italic">{{ e($publication->citation) }}</p>
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
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ e($publication->titre) }}</h2>
                            
                           
                        </div>

                        <!-- PDF Viewer Optimized -->
                        @if ($extension === 'pdf')
                            @php
                                $filePath = $publication->fichier_pdf ?? '';
                                $fileExists = $filePath && Storage::disk('public')->exists($filePath);
                                $file = $fileExists ? Storage::url($filePath) : '';
                            @endphp
                            
                            @if($fileExists)
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
                                            <button type="button"
                                                    @click="openDownloadModal({{ $publication->id }}, '{{ addslashes($publication->titre) }}')"
                                                    class="bg-iri-accent text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors duration-200 flex items-center">
                                                <i class="fas fa-download mr-2"></i>
                                                Télécharger
                                            </button>
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
                                <div id="pdfContainer" class="relative min-h-96" data-pdf-url="{{ $file }}">
                                    <!-- Loading State (Initial) -->
                                    <div id="pdfLoader" class="flex flex-col items-center justify-center p-12 bg-gray-50">
                                        <div class="bg-gradient-to-r from-iri-primary to-iri-secondary w-16 h-16 rounded-full flex items-center justify-center mb-4 animate-pulse">
                                            <i class="fas fa-file-pdf text-white text-2xl"></i>
                                        </div>
                                        <p class="text-gray-700 font-medium text-lg mb-4">Chargement du document PDF...</p>
                                        <div class="w-full max-w-md">
                                            <div id="pdfProgressContainer" class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                                <div id="pdfProgressBar" class="bg-gradient-to-r from-iri-primary to-iri-secondary h-3 rounded-full transition-all duration-300 pdf-progress-bar-initial"></div>
                                            </div>
                                            <div id="pdfProgressText" class="text-sm text-gray-600 text-center">Initialisation...</div>
                                        </div>
                                    </div>

                                    <!-- PDF Viewer Container -->
                                    <div id="pdfViewer" class="hidden p-6 space-y-6 bg-gray-50"></div>
                                </div>
                            </div>
                            @else
                            <!-- File Not Found Error -->
                            <div class="p-12 text-center">
                                <div class="bg-red-100 text-red-800 p-6 rounded-lg">
                                    <i class="fas fa-exclamation-triangle text-3xl mb-4" aria-hidden="true"></i>
                                    <p class="text-lg font-semibold mb-2">Fichier PDF introuvable</p>
                                    <p class="text-sm mb-4">Le fichier PDF associé à cette publication n'existe plus ou n'a pas été téléversé correctement.</p>
                                    @if($publication->resume)
                                        <button type="button" data-show-toast
                                                class="inline-flex items-center mt-2 bg-red-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-700 transition-colors duration-200">
                                            <i class="fas fa-book-open mr-2"></i>
                                            Lire le résumé
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @endif
                        @else
                            <div class="p-12 text-center">
                                <div class="bg-red-100 text-red-800 p-6 rounded-lg">
                                    <i class="fas fa-exclamation-triangle text-3xl mb-4"></i>
                                    <p class="text-lg">Ce type de fichier n'est pas pris en charge pour l'aperçu direct.</p>
                                    @if($publication->fichier_pdf)
                                        <button type="button"
                                                @click="openDownloadModal({{ $publication->id }}, '{{ addslashes($publication->titre) }}')"
                                                class="inline-flex items-center mt-4 bg-red-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-700 transition-colors duration-200">
                                            <i class="fas fa-download mr-2"></i>
                                            Télécharger le fichier
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal de Téléchargement --}}
    <div x-show="showModal" 
         x-cloak
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
         style="display: none;"
         @click.self="closeModal()">
        
        <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full z-10 transform transition-all"
             @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4">
                
                <div class="bg-gradient-to-r from-[#2C5F2D] to-[#97BC62] text-white rounded-t-3xl p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold">
                            <i class="fas fa-download mr-2"></i>
                            <span x-text="selectedRapport.title"></span>
                        </h3>
                        <button @click="closeModal()" 
                                type="button"
                                class="text-white hover:text-gray-200 transition-colors">
                            <i class="fas fa-times text-2xl"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">
                        Pour télécharger ce document, veuillez saisir votre adresse email.
                        Vous serez automatiquement inscrit à notre liste de diffusion pour recevoir nos actualités.
                    </p>
                    
                    <form @submit.prevent="submitDownload" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-[#2C5F2D]"></i>
                                Adresse email <span class="text-red-600">*</span>
                            </label>
                            
                            <input type="email"
                                   x-model="email"
                                   @input="clearEmailError()"
                                   placeholder="votre.email@exemple.com"
                                   class="w-full px-4 py-3 border-2 rounded-xl focus:ring-2 focus:ring-[#2C5F2D] focus:border-[#2C5F2D] transition-colors"
                                   :class="(attemptedSubmit && errors.email) ? 'border-red-500' : 'border-gray-300'"
                                   autocomplete="email"
                                   autofocus
                                   required>
                            
                            <template x-if="attemptedSubmit && errors.email">
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span x-text="errors.email[0]"></span>
                                </p>
                            </template>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            En téléchargeant, vous acceptez de recevoir la newsletter du GRN-UCBC.
                        </div>
                        
                        <!-- Boutons -->
                        <div class="flex gap-3 pt-4">
                            <button type="button"
                                    @click="closeModal()"
                                    :disabled="loading"
                                    class="flex-1 px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-times mr-2"></i>
                                Annuler
                            </button>
                            
                            <button type="submit"
                                    :disabled="loading"
                                    :class="isEmailValid ? 'opacity-100' : 'opacity-60'"
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-[#2C5F2D] to-[#97BC62] text-white rounded-xl hover:shadow-lg transition-all disabled:cursor-not-allowed">
                                <template x-if="!loading">
                                    <span class="flex items-center justify-center">
                                        <i class="fas fa-download mr-2"></i>
                                        Télécharger
                                    </span>
                                </template>
                                <template x-if="loading">
                                    <span class="flex items-center justify-center">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>
                                        Validation...
                                    </span>
                                </template>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast de Confirmation --}}
    <div x-show="showToast"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-8 right-8 z-50 max-w-md"
         style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl border-2 p-6"
             :class="toastNewSubscriber ? 'border-green-500' : 'border-blue-500'">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="text-3xl" 
                       :class="toastNewSubscriber ? 'fas fa-check-circle text-green-500' : 'fas fa-download text-blue-500'"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="text-lg font-semibold text-gray-900 mb-1">
                        <span x-show="toastNewSubscriber">Bienvenue !</span>
                        <span x-show="!toastNewSubscriber">Téléchargement</span>
                    </h4>
                    <p class="text-sm text-gray-600" x-text="toastMessage"></p>
                </div>
                <button @click="showToast = false"
                        class="flex-shrink-0 ml-4 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@push('head-scripts')
<!-- PDF.js 2.16.105 - SANS SRI pour test -->
<script>
    console.log('🔧 Début chargement PDF.js...');
    window.pdfJsLoadStart = Date.now();
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script>
    console.log('📦 Après balise script PDF.js - Type:', typeof pdfjsLib);
    console.log('⏱️ Temps écoulé:', Date.now() - window.pdfJsLoadStart, 'ms');
    
    // Attendre un micro-délai pour l'exécution du script
    setTimeout(() => {
        console.log('📦 Après timeout 0ms - Type:', typeof pdfjsLib);
        if (typeof pdfjsLib !== 'undefined') {
            console.log('✅ PDF.js détecté! Version:', pdfjsLib.version);
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
            window.pdfJsLoaded = true;
            console.log('✅ Worker configuré');
        } else {
            console.error('❌ pdfjsLib toujours undefined après timeout');
            
            // Dernier recours : créer un fallback manuel
            window.addEventListener('load', () => {
                setTimeout(() => {
                    console.log('📦 window.load + timeout - Type:', typeof pdfjsLib);
                }, 100);
            });
        }
    }, 0);
</script>
@endpush

@push('scripts')
<!-- Vérification Alpine.js et Modal de Téléchargement -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🔍 Vérification Alpine.js...');
        console.log('📦 Alpine disponible:', typeof Alpine !== 'undefined');
        
        // Attendre que Alpine soit initialisé
        document.addEventListener('alpine:init', () => {
            console.log('✅ Alpine.js initialisé');
        });
        
        // Vérifier après un délai
        setTimeout(() => {
            const mainDiv = document.querySelector('[x-data]');
            console.log('📦 Div avec x-data trouvée:', !!mainDiv);
            if (mainDiv) {
                console.log('✅ Alpine.js devrait être fonctionnel');
                
                // Test du modal
                window.testDownloadModal = function() {
                    console.log('🧪 Test ouverture modal de téléchargement');
                    // Déclencher manuellement
                    if (typeof Alpine !== 'undefined') {
                        Alpine.evaluate(mainDiv, 'openDownloadModal({{ $publication->id }}, "{{ addslashes($publication->titre) }}")');
                    }
                };
                console.log('✅ Fonction testDownloadModal() disponible');
            } else {
                console.error('❌ Aucun élément avec x-data trouvé!');
            }
        }, 1000);
    });
</script>

<!-- Vérification et logs PDF.js -->
<script>
    // Vérifier que PDF.js est bien chargé
    window.addEventListener('DOMContentLoaded', function() {
        console.log('🔍 Vérification PDF.js au DOMContentLoaded...');
        console.log('📦 pdfjsLib disponible:', typeof pdfjsLib !== 'undefined');
        if (typeof pdfjsLib !== 'undefined') {
            console.log('✅ PDF.js version:', pdfjsLib.version);
            console.log('✅ PDF.js.GlobalWorkerOptions:', typeof pdfjsLib.GlobalWorkerOptions);
        } else {
            console.error('❌ PDF.js non chargé! Vérifiez votre connexion ou bloqueur de publicités.');
            console.error('❌ window.pdfjsLib:', window.pdfjsLib);
            console.error('❌ Liste des scripts chargés:', Array.from(document.scripts).map(s => s.src));
        }
    });
</script>

<script src="{{ asset('js/publication-modal.js') }}" defer></script>
<script>
// Script de débogage pour le modal
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Page chargée, vérification du modal...');
    
    // Vérifier que les éléments existent
    const resumeToast = document.getElementById('resumeToast');
    const showButtons = document.querySelectorAll('[data-show-toast]');
    const closeButtons = document.querySelectorAll('[data-close-toast]');
    
    console.log('📝 Toast element:', resumeToast);
    console.log('📝 Show buttons:', showButtons.length);
    console.log('📝 Close buttons:', closeButtons.length);
    
    // Vérifier le script
    console.log('📝 PublicationModal:', typeof window.PublicationModal);
    console.log('📝 showToastAgain:', typeof window.showToastAgain);
    
    // Vérifier le statut localStorage
    const hasViewed = localStorage.getItem('publication-resume-viewed');
    console.log('📝 Statut "vu":', hasViewed);
    if (hasViewed === 'true') {
        console.log('ℹ️ Le modal ne s\'affichera pas automatiquement car déjà vu');
        console.log('ℹ️ Utilisez resetResumeViewed() pour réinitialiser');
    } else {
        console.log('⏰ Le modal s\'affichera automatiquement dans 3 secondes');
    }
    
    // Test manuel du modal
    window.testModal = function() {
        console.log('🧪 Test manuel du modal');
        if (window.showToastAgain) {
            window.showToastAgain();
        } else if (window.PublicationModal && window.PublicationModal.showResumeToast) {
            window.PublicationModal.showResumeToast();
        } else {
            console.error('❌ Aucune fonction de modal disponible');
        }
    };
    
    // Fonction pour réinitialiser le statut
    window.resetResumeViewed = function() {
        localStorage.removeItem('publication-resume-viewed');
        console.log('✅ Statut réinitialisé. Rechargez la page pour voir l\'affichage automatique.');
    };
    
    console.log('✅ Fonctions disponibles: testModal(), resetResumeViewed()');
});
</script>
<script src="{{ asset('js/publication-pdf-viewer.js') }}" defer></script>
@endpush
@endsection
