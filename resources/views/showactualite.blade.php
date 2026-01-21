@extends('layouts.iri')

@section('title', e($actualite->titre) . ' - Actualités')

@push('meta')
    <x-social-meta 
        :title="$actualite->titre"
        :description="$actualite->social_description"
        :image="$actualite->social_image_url"
        :url="$actualite->canonical_url"
        :imageAlt="$actualite->social_image_alt"
        :publishedAt="$actualite->published_at?->toISOString() ?? $actualite->created_at->toISOString()"
        :modifiedAt="$actualite->updated_at && $actualite->updated_at != $actualite->created_at ? $actualite->updated_at->toISOString() : null"
        :author="$actualite->auteur?->name"
        :section="$actualite->service?->nom"
    />
    <meta name="twitter:site" content="@GRNUCBC">
    
    <!-- LinkedIn -->
    <meta property="linkedin:owner" content="GRN-UCBC">
    
    <!-- WhatsApp / Telegram -->
    <meta property="og:image:alt" content="{{ e($actualite->titre) }}">
    
    <!-- Description générale -->
    <meta name="description" content="{{ e($actualite->resume ?? Str::limit(strip_tags($actualite->texte ?? ''), 160)) }}">
    <meta name="keywords" content="actualité, GRN, UCBC, RDC, {{ e($actualite->titre) }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ route('site.actualite.show', ['slug' => $actualite->slug]) }}">
@endpush

@section('breadcrumb')
    <x-breadcrumb-overlay :items="[
        ['title' => 'Actualités', 'url' => route('site.actualites')],
        ['title' => Str::limit(e($actualite->titre), 50), 'url' => null]
    ]" />
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/actualite-show.css') }}">
<link rel="stylesheet" href="{{ asset('css/article-display-enhanced.css') }}">
<link rel="stylesheet" href="{{ asset('css/related-articles.css') }}">
<!-- Feuille de style CKEditor - PRIORITAIRE - chargée en dernier -->
<link rel="stylesheet" href="{{ asset('css/ckeditor-content.css') }}" data-priority="high">
@endpush

@section('content')
<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <!-- Content -->
                <div class="lg:col-span-2">
                    <!-- Category Badge -->
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm border border-white/30 text-white px-4 py-2 rounded-full text-sm font-medium mb-6">
                        <i class="fas fa-newspaper mr-2" aria-hidden="true"></i>
                        Actualité
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                        {{ e($actualite->titre) }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-6 text-white/90 mb-8">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2" aria-hidden="true"></i>
                            <time datetime="{{ $actualite->created_at->format('Y-m-d') }}">
                                {{ $actualite->created_at->format('d M Y') }}
                            </time>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2" aria-hidden="true"></i>
                            <span>Lecture : {{ ceil(str_word_count(strip_tags($actualite->contenu ?? $actualite->texte ?? '')) / 200) }} min</span>
                        </div>
                        
                        <!-- Share Buttons -->
                        <x-social-share-buttons 
                            :url="route('site.actualite.show', ['slug' => $actualite->slug])"
                            :title="$actualite->titre"
                            :description="$actualite->resume ?? ($actualite->texte ? Str::limit(strip_tags($actualite->texte), 120) : '')"
                            :image="$actualite->hasImage() ? $actualite->image_url : ''"
                            style="hero"
                            class="ml-auto" />
                    </div>

                </div>

                <!-- Article Image -->
                @if($actualite->hasImage())
                    <div class="relative group overflow-hidden rounded-xl shadow-lg">
                        <img src="{{ $actualite->image_url }}"
                             alt="{{ $actualite->titre }}"
                             class="w-full h-auto object-contain transition-transform duration-300 group-hover:scale-105">
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Article Content -->
    <section class="py-16">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Main Content - Takes 8 columns -->
                <div class="lg:col-span-8">
                    <article class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <!-- Article Header -->
                        <div class="bg-gradient-to-r from-gray-50 to-white p-8 border-b border-gray-200">
                            @if($actualite->resume)
                                <div class="border-l-4 border-iri-primary bg-iri-primary/5 p-6 rounded-lg mb-6">
                                    <div class="text-lg text-gray-700 leading-relaxed font-medium">
                                        <x-wordpress-content 
                                            :content="$actualite->resume" 
                                            max-width="max-w-none"
                                        />
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Article Body -->
                        <div class="p-8 article-content-enhanced">
                            @if($actualite->texte || $actualite->contenu)
                                <div class="article-content prose max-w-none">
                                    <x-wordpress-content 
                                        :content="$actualite->texte ?? $actualite->contenu" 
                                        max-width="max-w-none"
                                    />
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500">Contenu de l'article en cours de rédaction...</p>
                                </div>
                            @endif
                        </div>

                        <!-- Article Footer -->
                        <div class="bg-gray-50 p-6 border-t border-gray-200">
                            <!-- Social Share Buttons -->
                            <div class="mb-6">
                                <x-social-share-buttons 
                                    :url="route('site.actualite.show', ['slug' => $actualite->slug])"
                                    :title="$actualite->titre"
                                    :description="$actualite->resume ?? ($actualite->texte ? Str::limit(strip_tags($actualite->texte), 120) : '')"
                                    :image="$actualite->hasImage() ? $actualite->image_url : ''"
                                    style="default" />
                            </div>

                            @if($actualite->hasRapports())
                                {{-- Section Rapports Liés - Liste Linéaire avec Modal Alpine.js --}}
                                <div class="mb-8 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" 
                                     x-data="{ 
                                         showModal: false, 
                                         selectedRapport: null,
                                         email: '',
                                         loading: false,
                                         errors: {},
                                         showToast: false,
                                         toastMessage: '',
                                         toastNewSubscriber: false,
                                         get isEmailValid() {
                                             const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                             return this.email.trim().length > 0 && emailRegex.test(this.email.trim());
                                         },
                                         openDownloadModal(rapportId, rapportTitle) {
                                             this.selectedRapport = { id: rapportId, title: rapportTitle };
                                             this.email = '';
                                             this.errors = {};
                                             this.showModal = true;
                                         },
                                         async submitDownload() {
                                             if (!this.isEmailValid) {
                                                 this.errors = { email: ['Veuillez saisir une adresse email valide.'] };
                                                 return;
                                             }
                                             
                                             this.loading = true;
                                             this.errors = {};
                                             
                                             try {
                                                 console.log('Envoi de la requête pour rapport:', this.selectedRapport.id);
                                                 console.log('Email:', this.email);
                                                 console.log('Actualité ID:', {{ $actualite->id }});
                                                 
                                                 const response = await fetch(`/rapport/${this.selectedRapport.id}/validate-email`, {
                                                     method: 'POST',
                                                     headers: {
                                                         'Content-Type': 'application/json',
                                                         'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                                         'Accept': 'application/json'
                                                     },
                                                     body: JSON.stringify({
                                                         email: this.email.trim(),
                                                         actualite_id: {{ $actualite->id }}
                                                     })
                                                 });
                                                 
                                                 console.log('Statut de la réponse:', response.status);
                                                 
                                                 const data = await response.json();
                                                 console.log('Données reçues:', data);
                                                 
                                                 if (data.success) {
                                                     // Télécharger le fichier
                                                     console.log('Téléchargement depuis:', data.download_url);
                                                     window.open(data.download_url, '_blank');
                                                     
                                                     // Fermer le modal
                                                     this.showModal = false;
                                                     this.email = '';
                                                     
                                                     // Afficher le toast
                                                     this.toastNewSubscriber = data.newsletter_subscribed;
                                                     this.toastMessage = data.newsletter_subscribed 
                                                         ? 'Téléchargement démarré ! Vous êtes maintenant inscrit à notre newsletter.' 
                                                         : 'Téléchargement démarré !';
                                                     this.showToast = true;
                                                     
                                                     // Cacher le toast après 5 secondes
                                                     setTimeout(() => { this.showToast = false; }, 5000);
                                                 } else {
                                                     console.error('Erreur retournée:', data);
                                                     this.errors = data.errors || { general: data.message || 'Une erreur est survenue.' };
                                                 }
                                             } catch (error) {
                                                 console.error('Erreur lors de la requête:', error);
                                                 this.errors = { general: 'Une erreur est survenue lors de la connexion. Veuillez vérifier votre connexion internet et réessayer.' };
                                             } finally {
                                                 this.loading = false;
                                             }
                                         }
                                     }">
                                    <!-- En-tête de section -->
                                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                            <i class="fas fa-file-pdf text-red-600 mr-3"></i>
                                            Rapports liés ({{ $actualite->publishedRapports->count() }})
                                        </h2>
                                    </div>

                                    <!-- Liste linéaire des rapports -->
                                    <div class="divide-y divide-gray-100">
                                        @foreach($actualite->publishedRapports as $rapport)
                                            <button type="button"
                                               @click="openDownloadModal({{ $rapport->id }}, '{{ addslashes($rapport->titre) }}')"
                                               class="w-full flex items-center px-6 py-4 hover:bg-gray-50 transition-colors duration-200 group text-left">
                                                <!-- Icône PDF -->
                                                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-red-200 transition-colors">
                                                    <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                                                </div>
                                                
                                                <!-- Titre du rapport -->
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900 group-hover:text-iri-primary transition-colors truncate">
                                                        {{ $rapport->titre }}
                                                    </p>
                                                    @if($rapport->date_publication)
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        <i class="far fa-calendar mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($rapport->date_publication)->format('d/m/Y') }}
                                                    </p>
                                                    @endif
                                                </div>
                                                
                                                <!-- Icône de téléchargement -->
                                                <div class="flex-shrink-0 ml-4">
                                                    <i class="fas fa-download text-gray-400 group-hover:text-iri-primary transition-colors"></i>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>

                                    {{-- Modal Alpine.js + TailwindCSS pour Email --}}
                                    <div x-show="showModal" 
                                         x-cloak
                                         @keydown.escape.window="showModal = false"
                                         class="fixed inset-0 z-50 overflow-y-auto"
                                         style="display: none;">
                                        
                                        <!-- Overlay avec fond sombre -->
                                        <div x-show="showModal"
                                             x-transition:enter="ease-out duration-300"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="ease-in duration-200"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             @click="showModal = false"
                                             class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

                                        <!-- Container centré -->
                                        <div class="fixed inset-0 z-10 overflow-y-auto">
                                            <div class="flex min-h-full items-center justify-center p-4 text-center">
                                                <!-- Contenu du modal -->
                                                <div x-show="showModal"
                                                     x-transition:enter="ease-out duration-300"
                                                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                     x-transition:leave="ease-in duration-200"
                                                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                                     x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                                                     @click.stop
                                                     class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-full max-w-lg">
                                                    
                                                    <!-- Contenu interne avec padding -->
                                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                                <div class="sm:flex sm:items-start">
                                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                        <i class="fas fa-file-pdf text-red-600 text-xl"></i>
                                                    </div>
                                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                            Télécharger le rapport
                                                        </h3>
                                                        <div class="mt-2">
                                                            <p class="text-sm text-gray-500" x-text="selectedRapport?.title"></p>
                                                        </div>
                                                    </div>
                                                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                                        <i class="fas fa-times text-xl"></i>
                                                    </button>
                                                </div>

                                                <!-- Formulaire -->
                                                <form @submit.prevent="submitDownload" class="mt-5">
                                                    <div class="mb-4">
                                                        <label for="download-email" class="block text-sm font-medium text-gray-700 mb-2">
                                                            <i class="fas fa-envelope mr-2 text-iri-primary"></i>
                                                            Adresse email <span class="text-red-500">*</span>
                                                        </label>
                                                        <input type="email" 
                                                               id="download-email"
                                                               x-model="email"
                                                               required
                                                               placeholder="votre@email.com"
                                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-all"
                                                               :class="{ 
                                                                   'border-red-500': errors.email,
                                                                   'border-green-500': email.length > 0 && isEmailValid,
                                                                   'border-red-300': email.length > 0 && !isEmailValid
                                                               }">
                                                        
                                                        <!-- Messages d'erreur -->
                                                        <p x-show="errors.email" x-text="errors.email?.[0]" class="mt-1 text-sm text-red-600"></p>
                                                        <p x-show="errors.general" x-text="errors.general" class="mt-1 text-sm text-red-600"></p>
                                                        
                                                        <!-- Indicateur de validation -->
                                                        <p x-show="email.length > 0 && !isEmailValid" class="mt-1 text-sm text-red-500">
                                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                                            Veuillez saisir une adresse email valide
                                                        </p>
                                                        <p x-show="email.length > 0 && isEmailValid" class="mt-1 text-sm text-green-600">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            Email valide
                                                        </p>
                                                        
                                                        <p class="mt-2 text-xs text-gray-500">
                                                            <i class="fas fa-info-circle mr-1"></i>
                                                            En téléchargeant ce rapport, vous acceptez de recevoir notre newsletter.
                                                        </p>
                                                    </div>

                                                    <!-- Boutons -->
                                                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                                        <button type="submit"
                                                                :disabled="loading || !isEmailValid"
                                                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-iri-primary text-base font-medium text-white hover:bg-iri-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary sm:col-start-2 sm:text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                                :class="{ 'opacity-50 cursor-not-allowed': !isEmailValid }">
                                                            <span x-show="!loading">
                                                                <i class="fas fa-download mr-2"></i>
                                                                Télécharger
                                                            </span>
                                                            <span x-show="loading">
                                                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                                                Traitement...
                                                            </span>
                                                        </button>
                                                        <button type="button"
                                                                @click="showModal = false"
                                                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary sm:mt-0 sm:col-start-1 sm:text-sm transition-colors">
                                                            Annuler
                                                        </button>
                                                    </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Toast de succès --}}
                                    <div x-show="showToast"
                                         x-cloak
                                         x-transition:enter="transform ease-out duration-300 transition"
                                         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                                         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="fixed bottom-0 right-0 mb-8 mr-8 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                                        <div class="p-4">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                                                </div>
                                                <div class="ml-3 w-0 flex-1 pt-0.5">
                                                    <p class="text-sm font-medium text-gray-900" x-text="toastMessage"></p>
                                                    <p x-show="toastNewSubscriber" class="mt-1 text-sm text-gray-500">
                                                        <i class="fas fa-envelope mr-1"></i>
                                                        Bienvenue dans notre communauté !
                                                    </p>
                                                </div>
                                                <div class="ml-4 flex-shrink-0 flex">
                                                    <button @click="showToast = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary">
                                                        <span class="sr-only">Fermer</span>
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <a href="{{ route('site.actualites') }}" 
                                   class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-semibold transition-colors duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Retour aux actualités
                                </a>

                                <!-- Tags -->
                                {{-- Catégorie supprimée car non disponible pour les actualités --}}
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Sidebar - Takes 4 columns on the right -->
                <div class="lg:col-span-4">
                    <div class="space-y-6 lg:sticky lg:top-6">
                        <!-- Recent Articles -->
                        @if($relatedActualites && optional($relatedActualites)->count() > 0)
                            @php
                                // Filtrer pour exclure les actualités de la même catégorie
                                $otherCategoryActualites = $relatedActualites->filter(function($item) use ($actualite) {
                                    return $item->categorie_id !== $actualite->categorie_id;
                                });
                            @endphp
                            
                            @if($otherCategoryActualites->count() > 0)
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                                    <i class="fas fa-newspaper text-iri-primary mr-2"></i>
                                    Autres actualités
                                </h3>
                                
                                <div class="space-y-3">
                                    @foreach($otherCategoryActualites->take(5) as $recente)
                                        @if($recente->slug && $recente->id !== $actualite->id)
                                            <a href="{{ route('site.actualite.show', ['slug' => $recente->slug]) }}" 
                                               class="flex gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 group">
                                                <!-- Miniature de l'image -->
                                                <div class="flex-shrink-0 w-16 h-16 rounded-md overflow-hidden bg-gray-200">
                                                    @if($recente->hasImage())
                                                        <img src="{{ $recente->image_url }}" 
                                                             alt="{{ $recente->titre }}"
                                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                                             loading="lazy">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-300 to-gray-400">
                                                            <i class="fas fa-newspaper text-white text-lg" aria-hidden="true"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Contenu -->
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="font-medium text-gray-900 text-sm mb-2 leading-snug group-hover:text-iri-primary transition-colors duration-200">
                                                        {{ e($recente->titre) }}
                                                    </h4>
                                                    <p class="text-xs text-gray-500 flex items-center">
                                                        <i class="fas fa-calendar-alt mr-1" aria-hidden="true"></i>
                                                        <time datetime="{{ $recente->created_at->format('Y-m-d') }}">
                                                            {{ $recente->created_at->format('d M Y') }}
                                                        </time>
                                                    </p>
                                                </div>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>

                                <div class="mt-6">
                                    <a href="{{ route('site.actualites') }}" 
                                       class="inline-flex items-center w-full justify-center bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-2 px-4 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                        <i class="fas fa-eye mr-2"></i>
                                        Voir toutes les actualités
                                    </a>
                                </div>
                            </div>
                            @endif
                        @endif

                        <!-- Newsletter Subscription -->
                        <div class="bg-gradient-to-br from-iri-primary to-iri-secondary rounded-2xl p-6 text-white">
                            <h3 class="text-lg font-bold mb-4">Restez informé</h3>
                            <p class="text-white/90 text-sm mb-4">
                                Recevez les dernières actualités du GRN-UCBC directement dans votre boîte mail.
                            </p>
                            @if(Route::has('newsletter.subscribe'))
                                <a href="{{ route('newsletter.subscribe') }}" 
                                   class="inline-flex items-center justify-center w-full bg-white/20 backdrop-blur-sm border border-white/30 text-white font-semibold py-2 px-4 rounded-lg hover:bg-white/30 transition-all duration-200">
                                    <i class="fas fa-envelope mr-2" aria-hidden="true"></i>
                                    S'abonner à la newsletter
                                </a>
                            @else
                                <div class="bg-white/20 backdrop-blur-sm border border-white/30 text-white/70 font-medium py-2 px-4 rounded-lg text-center text-sm">
                                    <i class="fas fa-envelope mr-2" aria-hidden="true"></i>
                                    Newsletter bientôt disponible
                                </div>
                            @endif
                        </div>

                        <!-- Contact for Information -->
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Plus d'informations</h3>
                            <p class="text-gray-600 text-sm mb-4">
                                Vous souhaitez en savoir plus sur cette actualité ?
                            </p>
                            <a href="{{ route('site.contact') }}" 
                               class="inline-flex items-center w-full justify-center bg-gradient-to-r from-iri-accent to-iri-gold text-white font-semibold py-2 px-4 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-envelope mr-2" aria-hidden="true"></i>
                                Nous contacter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin de la grille Tailwind principale -->

        </div>
    </section>
</div>

<!-- Actualités Liées - Section pleine largeur ISOLÉE -->
@if($relatedActualites && $relatedActualites->count() > 0)
    <section class="related-articles-section bg-gradient-to-b from-gray-50 to-white py-16">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="related-articles">
                    <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
                        <div class="section-header">
                            <h2 class="section-title">
                                <i class="fas fa-newspaper" aria-hidden="true"></i>
                                <span>Actualités connexes</span>
                            </h2>
                            <p class="section-description">
                                @if($actualite->categorie_id || $actualite->service_id)
                                    Explorez d'autres actualités 
                                    @if($actualite->categorie)
                                        de la catégorie <strong>"{{ $actualite->categorie->nom }}"</strong>
                                    @elseif($actualite->service)
                                        du service <strong>"{{ $actualite->service->nom }}"</strong>
                                    @endif
                                    qui pourraient vous intéresser
                                @else
                                    Restez informé avec nos dernières actualités et découvertes
                                @endif
                            </p>
                        </div>

                        <div class="p-8">
                            <div class="articles-grid articles-grid-isolated">
                                @foreach($relatedActualites->take(8) as $item)
                                    <article class="article-card group">
                                        <div class="article-image">
                                            @if($item->image && file_exists(public_path('storage/' . $item->image)))
                                                <img src="{{ asset('storage/' . $item->image) }}" 
                                                     alt="{{ $item->titre }}"
                                                     loading="lazy"
                                                     onerror="this.parentElement.innerHTML='<div class=\'article-image-placeholder\'><i class=\'fas fa-image\'></i><div class=\'placeholder-text\'>Image non disponible</div></div>'">
                                            @else
                                                <div class="article-image-placeholder">
                                                    <i class="fas fa-newspaper" aria-hidden="true"></i>
                                                    <div class="placeholder-text">
                                                        {{ $item->service ? $item->service->nom : 'Actualité GRN' }}
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <!-- Titre en overlay sur l'image -->
                                            <h3 class="article-title">
                                                <a href="{{ route('site.actualite.show', ['slug' => $item->slug]) }}"
                                                   title="{{ $item->titre }}">
                                                    {{ $item->titre }}
                                                </a>
                                            </h3>
                                        </div>

                                        <div class="article-content">
                                            <div class="flex-1">
                                                <!-- Titre déplacé en overlay sur l'image -->

                                                @if($item->resume)
                                                    <p class="article-excerpt">
                                                        {{ Str::limit($item->resume, 95) }}
                                                    </p>
                                                @elseif($item->texte)
                                                    <p class="article-excerpt">
                                                        {{ Str::limit(strip_tags($item->texte), 95) }}
                                                    </p>
                                                @else
                                                    <p class="article-excerpt text-gray-400 italic">
                                                        Découvrez le contenu de cette actualité du GRN-UCBC...
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="article-meta">
                                                <div class="article-date">
                                                    <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                                    <time datetime="{{ $item->created_at->format('Y-m-d') }}">
                                                        {{ $item->created_at->format('d M Y') }}
                                                    </time>
                                                </div>

                                                <a href="{{ route('site.actualite.show', ['slug' => $item->slug]) }}"
                                                   class="article-read-more"
                                                   aria-label="Lire l'article : {{ $item->titre }}">
                                                    <span>Découvrir</span>
                                                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

{{-- Modal pour téléchargement de rapport avec validation email --}}
<div class="modal fade" id="downloadRapportModal" tabindex="-1" aria-labelledby="downloadRapportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3xl border-0 shadow-2xl">
            <div class="modal-header border-0 bg-gradient-to-r from-[#2C5F2D] to-[#97BC62] text-white rounded-t-3xl p-6">
                <h5 class="modal-title font-bold text-xl" id="downloadRapportModalLabel">
                    <i class="fas fa-download mr-2"></i>
                    <span id="rapport-title-display"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body p-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">
                        Pour télécharger ce rapport, veuillez saisir votre adresse email. 
                        Vous serez automatiquement inscrit à notre liste de diffusion pour recevoir nos actualités.
                    </p>
                </div>

                <div id="download-error-message" class="alert alert-danger d-none mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span id="error-text"></span>
                </div>

                <div id="download-success-message" class="alert alert-success d-none mb-4" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span id="success-text"></span>
                </div>

                <form id="downloadRapportForm">
                    <input type="hidden" id="rapport-id" name="rapport_id">
                    <input type="hidden" id="actualite-id" name="actualite_id">
                    
                    <div class="mb-4">
                        <label for="email-input" class="form-label font-semibold text-gray-700">
                            <i class="fas fa-envelope mr-2 text-[#2C5F2D]"></i>
                            Adresse email <span class="text-danger">*</span>
                        </label>
                        <input type="email" 
                               class="form-control rounded-xl border-2 border-gray-300 focus:border-[#2C5F2D] focus:ring focus:ring-[#2C5F2D] focus:ring-opacity-50 px-4 py-3" 
                               id="email-input" 
                               name="email"
                               placeholder="votre.email@exemple.com"
                               required>
                        <div class="invalid-feedback" id="email-error"></div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="newsletter-consent" checked>
                        <label class="form-check-label text-sm text-gray-600" for="newsletter-consent">
                            J'accepte de recevoir la newsletter du GRN-UCBC
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 p-6 pt-0">
                <button type="button" class="btn btn-secondary rounded-xl px-6 py-3" data-bs-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </button>
                <button type="button" id="btn-validate-download" class="btn btn-primary rounded-xl px-6 py-3" style="background: linear-gradient(to right, #2C5F2D, #97BC62); border: none;">
                    <i class="fas fa-download mr-2"></i>
                    <span id="btn-download-text">Télécharger</span>
                    <span id="btn-download-spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/actualite-modal.js') }}" defer></script>
<script src="{{ asset('js/article-image-enhancer.js') }}" defer></script>
<script src="{{ asset('js/rapport-download-modal.js') }}" defer></script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/social-share.css') }}">
@endpush
@endsection
