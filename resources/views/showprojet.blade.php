@extends('layouts.iri')

@section('title', $projet->exists ? 'Projet - ' . $projet->nom : 'Projet introuvable')

@push('styles')
<!-- Feuille de style CKEditor - PRIORITAIRE pour le contenu des projets -->
<link rel="stylesheet" href="{{ asset('css/ckeditor-content.css') }}" data-priority="high">
@endpush

@section('breadcrumb')
@if($projet->exists)
    @php
        $breadcrumbItems = [
            ['title' => 'Services', 'url' => route('site.services')]
        ];
        
        if($projet->service) {
            $breadcrumbItems[] = ['title' => $projet->service->nom, 'url' => route('site.service.show', $projet->service->slug)];
        }
        
        $breadcrumbItems[] = ['title' => Str::limit($projet->nom, 50), 'url' => null];
    @endphp
    
    <x-breadcrumb-overlay :items="$breadcrumbItems" />
@endif
@endsection

@section('content')
@if($projet->exists)
        <!-- Hero Section -->
        <section class="relative">
            <div class="relative h-96 bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent overflow-hidden">
                @if($projet->image)
                    <img src="{{ asset('storage/'.$projet->image) }}" 
                         alt="{{ $projet->nom }}" 
                         class="absolute inset-0 w-full h-full object-cover mix-blend-overlay">
                @endif
                <div class="absolute inset-0 bg-black/20"></div>
                
                <div class="relative z-10 flex items-center justify-center h-full">
                    <div class="text-center text-white px-6 max-w-4xl mx-auto">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 drop-shadow-2xl">
                            {{ $projet->nom }}
                        </h1>
                        @if($projet->service)
                            <p class="text-xl md:text-2xl text-white/90 leading-relaxed drop-shadow-lg">
                                Programme : {{ $projet->service->nom }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </section>

    <!-- Main Content -->
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        <!-- Project Details -->
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Main Content -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">À propos de ce projet</h2>
                            
                            <!-- Résumé du projet si disponible -->
                            @if($projet->resume)
                                <blockquote class="border-l-4 border-iri-primary bg-iri-primary/5 p-6 rounded-lg mb-6">
                                    <p class="text-lg text-gray-700 italic leading-relaxed font-medium">
                                        "{{ $projet->resume }}"
                                    </p>
                                </blockquote>
                            @endif
                            
                            @if($projet->description)
                                <div class="article-content">
                                    <x-rich-text-display :content="$projet->description" 
                                        class="prose-headings:text-iri-primary prose-links:text-iri-secondary prose-strong:text-iri-dark" />
                                </div>
                            @else
                                <p class="text-gray-500 italic">Aucune description disponible pour ce projet.</p>
                            @endif
                        </div>

                        <!-- Rapports et Publications associés -->
                        @if($projet->hasRapports())
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mt-8" 
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
                                             const response = await fetch(`/rapport/${this.selectedRapport.id}/validate-email`, {
                                                 method: 'POST',
                                                 headers: {
                                                     'Content-Type': 'application/json',
                                                     'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                                     'Accept': 'application/json'
                                                 },
                                                 body: JSON.stringify({
                                                     email: this.email.trim(),
                                                     projet_id: {{ $projet->id }}
                                                 })
                                             });
                                             
                                             const data = await response.json();
                                             
                                             if (data.success) {
                                                 window.open(data.download_url, '_blank');
                                                 this.showModal = false;
                                                 this.email = '';
                                                 this.toastNewSubscriber = data.newsletter_subscribed;
                                                 this.toastMessage = data.newsletter_subscribed 
                                                     ? 'Téléchargement démarré ! Vous êtes maintenant inscrit à notre newsletter.' 
                                                     : 'Téléchargement démarré !';
                                                 this.showToast = true;
                                                 setTimeout(() => { this.showToast = false; }, 5000);
                                             } else {
                                                 this.errors = data.errors || { general: data.message || 'Une erreur est survenue.' };
                                             }
                                         } catch (error) {
                                             this.errors = { general: 'Une erreur est survenue lors de la connexion.' };
                                         } finally {
                                             this.loading = false;
                                         }
                                     }
                                 }">
                                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                    <i class="fas fa-file-pdf text-red-600 mr-3"></i>
                                    Documentation associée ({{ $projet->publishedRapports->count() }})
                                </h3>
                                
                                <div class="divide-y divide-gray-100">
                                    @foreach($projet->publishedRapports as $rapport)
                                        <button type="button"
                                           @click="openDownloadModal({{ $rapport->id }}, '{{ addslashes($rapport->titre) }}')"
                                           class="w-full flex items-center px-6 py-4 hover:bg-gray-50 transition-colors duration-200 group text-left">
                                            <!-- Icône PDF -->
                                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-red-200 transition-colors">
                                                <i class="fas fa-file-pdf text-red-600 text-xl"></i>
                                            </div>
                                            
                                            <!-- Informations du rapport -->
                                            <div class="flex-1 min-w-0">
                                                <p class="text-base font-semibold text-gray-900 group-hover:text-iri-primary transition-colors">
                                                    {{ $rapport->titre }}
                                                </p>
                                                @if($rapport->description)
                                                    <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                                                        {{ Str::limit(strip_tags($rapport->description), 120) }}
                                                    </p>
                                                @endif
                                                @if($rapport->date_publication)
                                                    <p class="text-xs text-gray-500 mt-2">
                                                        <i class="far fa-calendar mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($rapport->date_publication)->format('d/m/Y') }}
                                                    </p>
                                                @endif
                                            </div>
                                            
                                            <!-- Icône de téléchargement -->
                                            <div class="flex-shrink-0 ml-4">
                                                <i class="fas fa-download text-gray-400 group-hover:text-iri-primary transition-colors text-lg"></i>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>

                                {{-- Modal Alpine.js pour Email --}}
                                <div x-show="showModal" 
                                     x-cloak
                                     @keydown.escape.window="showModal = false"
                                     class="fixed inset-0 z-50 overflow-y-auto"
                                     style="display: none;">
                                    
                                    <div x-show="showModal"
                                         x-transition:enter="ease-out duration-300"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="ease-in duration-200"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         @click="showModal = false"
                                         class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

                                    <div class="fixed inset-0 z-10 overflow-y-auto">
                                        <div class="flex min-h-full items-center justify-center p-4 text-center">
                                            <div x-show="showModal"
                                                 x-transition:enter="ease-out duration-300"
                                                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                 x-transition:leave="ease-in duration-200"
                                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                                                 @click.stop
                                                 class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-full max-w-lg">
                                                
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

                                                    <form @submit.prevent="submitDownload" class="mt-5">
                                                        <div class="mb-4">
                                                            <label for="download-email-projet" class="block text-sm font-medium text-gray-700 mb-2">
                                                                <i class="fas fa-envelope mr-2 text-iri-primary"></i>
                                                                Adresse email <span class="text-red-500">*</span>
                                                            </label>
                                                            <input type="email" 
                                                                   id="download-email-projet"
                                                                   x-model="email"
                                                                   required
                                                                   placeholder="votre@email.com"
                                                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-all"
                                                                   :class="{ 
                                                                       'border-red-500': errors.email,
                                                                       'border-green-500': email.length > 0 && isEmailValid
                                                                   }">
                                                            
                                                            <p x-show="errors.email" x-text="errors.email?.[0]" class="mt-1 text-sm text-red-600"></p>
                                                            <p x-show="errors.general" x-text="errors.general" class="mt-1 text-sm text-red-600"></p>
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

                                                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                                            <button type="submit"
                                                                    :disabled="loading || !isEmailValid"
                                                                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-iri-primary text-base font-medium text-white hover:bg-iri-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary sm:col-start-2 sm:text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
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
                                                <button @click="showToast = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Project Gallery -->
                        @if($projet->medias && optional($projet->medias)->count() ?? 0 > 0)
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mt-8">
                                <h3 class="text-2xl font-bold text-gray-900 mb-6">Galerie du projet</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($projet->medias as $media)
                                        <div class="relative group overflow-hidden rounded-xl bg-gray-100 aspect-video">
                                            @if($media->type === 'image')
                                                <img src="{{ asset('storage/'.$media->chemin) }}" 
                                                     alt="{{ $media->nom }}" 
                                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300"></div>
                                            @elseif($media->type === 'video')
                                                <video class="w-full h-full object-cover" controls>
                                                    <source src="{{ asset('storage/'.$media->chemin) }}" type="video/mp4">
                                                    Votre navigateur ne supporte pas les vidéos.
                                                </video>
                                            @endif
                                            
                                            @if($media->nom)
                                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                                                    <p class="text-white text-sm font-medium">{{ $media->nom }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <!-- Project Info -->
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Informations du projet</h3>
                            
                            <div class="space-y-4">
                                <!-- Status -->
                                @if($projet->etat)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">État :</span>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            @if($projet->etat === 'en cours') bg-green-100 text-green-800
                                            @elseif($projet->etat === 'terminé') bg-blue-100 text-blue-800
                                            @elseif($projet->etat === 'suspendu') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($projet->etat) }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Budget -->
                                @if($projet->budget)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Budget :</span>
                                        <span class="font-bold text-iri-gold text-lg">{{ number_format($projet->budget, 0, ',', ' ') }} $</span>
                                    </div>
                                @endif

                                <!-- Dates -->
                                @if($projet->date_debut)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Début :</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($projet->date_debut)->format('d/m/Y') }}</span>
                                    </div>
                                @endif

                                @if($projet->date_fin)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Fin :</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($projet->date_fin)->format('d/m/Y') }}</span>
                                    </div>
                                @endif

                                <!-- Beneficiaries Section -->
                                @php
                                    $beneficiaires_attendus = ($projet->beneficiaires_hommes ?? 0) + ($projet->beneficiaires_femmes ?? 0);
                                @endphp
                                @if($projet->beneficiaires_total > 0 || $beneficiaires_attendus > 0)
                                    <div class="border-t pt-4 mt-4">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Bénéficiaires</h4>
                                        
                                        <div class="space-y-2">
                                            @if($beneficiaires_attendus > 0)
                                                <div class="flex items-center justify-between">
                                                    <span class="text-gray-600">Attendus :</span>
                                                    <span class="font-bold text-iri-accent">{{ number_format($beneficiaires_attendus) }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($projet->beneficiaires_total > 0)
                                                <div class="flex items-center justify-between">
                                                    <span class="text-gray-600">Atteints :</span>
                                                    <span class="font-bold text-iri-primary">{{ number_format($projet->beneficiaires_total) }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($projet->beneficiaires_hommes > 0 || $projet->beneficiaires_femmes > 0)
                                                <div class="mt-2 pt-2 border-t border-gray-100">
                                                    @if($projet->beneficiaires_hommes > 0)
                                                        <div class="flex items-center justify-between text-sm">
                                                            <span class="text-gray-500">• Hommes :</span>
                                                            <span class="font-medium">{{ number_format($projet->beneficiaires_hommes) }}</span>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($projet->beneficiaires_femmes > 0)
                                                        <div class="flex items-center justify-between text-sm">
                                                            <span class="text-gray-500">• Femmes :</span>
                                                            <span class="font-medium">{{ number_format($projet->beneficiaires_femmes) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Service Link -->
                                @if($projet->service)
                                    <div class="border-t pt-4 mt-4">
                                        <a href="{{ route('site.service.show', $projet->service->slug) }}" 
                                           class="inline-flex items-center gap-2 text-iri-primary hover:text-iri-secondary transition-colors duration-200">
                                            <i class="fas fa-arrow-left"></i>
                                            <span>Retour au programme</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Projets exécutés -->
                        @if($projet->service && optional($projet->service->projets)->count() > 1)
                            @php
                                $autresProjets = $projet->service->projets->where('id', '!=', $projet->id)->take(5);
                            @endphp
                            @if($autresProjets->count() > 0)
                                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mt-8">
                                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                                        <i class="fas fa-project-diagram text-iri-primary mr-2"></i>
                                        Autres projets du programme
                                    </h3>
                                    
                                    <div class="space-y-4">
                                        @foreach($autresProjets as $autreProjet)
                                            <a href="{{ route('site.projet.show', ['slug' => $autreProjet->slug]) }}" 
                                               class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 group">
                                                <div class="flex items-start gap-3">
                                                    @if($autreProjet->image)
                                                        <img src="{{ asset('storage/'.$autreProjet->image) }}" 
                                                             alt="{{ $autreProjet->nom }}" 
                                                             class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                                                    @else
                                                        <div class="w-12 h-12 bg-iri-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                                            <i class="fas fa-project-diagram text-iri-primary"></i>
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="font-medium text-gray-900 text-sm mb-1 line-clamp-2 group-hover:text-iri-primary transition-colors duration-200">
                                                            {{ $autreProjet->nom }}
                                                        </h4>
                                                        @if($autreProjet->etat)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                                @if($autreProjet->etat === 'en cours') bg-green-100 text-green-800
                                                                @elseif($autreProjet->etat === 'terminé') bg-blue-100 text-blue-800
                                                                @elseif($autreProjet->etat === 'suspendu') bg-red-100 text-red-800
                                                                @else bg-gray-100 text-gray-800
                                                                @endif">
                                                                {{ ucfirst($autreProjet->etat) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>

                                    <div class="mt-6">
                                        <a href="{{ route('site.projets') }}" 
                                           class="inline-flex items-center w-full justify-center bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-2 px-4 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                            <i class="fas fa-eye mr-2"></i>
                                            Voir tous les projets du programme
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

@else
    <!-- Project Not Found -->
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white flex items-center justify-center">
        <div class="text-center">
            <div class="text-6xl text-gray-300 mb-8">
                <i class="fas fa-project-diagram"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Projet introuvable</h1>
            <p class="text-xl text-gray-600 mb-8">Le projet que vous recherchez n'existe pas ou a été supprimé.</p>
            <a href="{{ route('site.services') }}" 
               class="btn-iri-primary">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour aux services
            </a>
        </div>
    </div>
@endif

<!-- Styles -->
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .prose {
        color: #374151;
        max-width: none;
    }
    .prose p {
        margin-bottom: 1.5rem;
        line-height: 1.8;
    }
    .prose h1, .prose h2, .prose h3, .prose h4 {
        color: #111827;
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .prose h1 {
        font-size: 2rem;
    }
    .prose h2 {
        font-size: 1.5rem;
    }
    .prose h3 {
        font-size: 1.25rem;
    }
    .prose ul, .prose ol {
        margin: 1.5rem 0;
        padding-left: 1.5rem;
    }
    .prose li {
        margin-bottom: 0.5rem;
    }
    .prose blockquote {
        border-left: 4px solid #3B82F6;
        padding-left: 1rem;
        margin: 1.5rem 0;
        font-style: italic;
        background: #F8FAFC;
        padding: 1rem;
        border-radius: 0.5rem;
    }
</style>

{{-- Boutons de partage social pour les projets --}}
@if($projet->exists)
<x-global-social-share 
    position="floating"
    style="hero"
    size="large"
    :showLabels="false"
    platforms="facebook,twitter,linkedin,whatsapp,telegram,email"
    customTitle="{{ $projet->nom }} - GRN-UCBC"
    customDescription="{{ Str::limit(strip_tags($projet->resume ?? $projet->description ?? ''), 150) }}"
    customImage="{{ $projet->image ? asset('storage/' . $projet->image) : asset('assets/img/logos/iri-logo.png') }}"
    :analytics="true" />
@endif
@endsection
