@extends('layouts.iri')

@section('title', $evenement->titre . ' - GRN-UCBC')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('site.home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-iri-accent">
                        <i class="fas fa-home mr-2"></i>
                        Accueil
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">Événements</span>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-iri-accent">{{ Str::limit($evenement->titre, 50) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Contenu principal -->
            <div class="lg:col-span-2">
                <article class="bg-white rounded-xl shadow-lg overflow-hidden">
                    
                    <!-- En-tête de l'événement -->
                    <div class="bg-gradient-to-r from-iri-primary to-iri-secondary text-white p-8">
                        <!-- Badge de statut -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                @if($evenement->est_passe)
                                    <span class="bg-gray-500/20 backdrop-blur-sm text-white text-sm font-bold px-4 py-2 rounded-full border border-white/30">
                                        <i class="fas fa-history mr-2"></i>
                                        Événement passé
                                    </span>
                                @elseif($evenement->est_a_venir)
                                    <span class="bg-green-500/20 backdrop-blur-sm text-white text-sm font-bold px-4 py-2 rounded-full border border-white/30">
                                        <i class="fas fa-calendar-plus mr-2"></i>
                                        À venir
                                    </span>
                                @else
                                    <span class="bg-orange-500/20 backdrop-blur-sm text-white text-sm font-bold px-4 py-2 rounded-full border border-white/30">
                                        <i class="fas fa-clock mr-2"></i>
                                        En cours
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Boutons d'action -->
                            <div class="flex items-center space-x-2">
                                @if($evenement->rapport_url)
                                    <a href="{{ $evenement->rapport_url }}" target="_blank"
                                       class="bg-iri-gold hover:bg-iri-accent text-white px-4 py-2 rounded-lg font-semibold text-sm transition-all duration-300 flex items-center">
                                        <i class="fas fa-file-pdf mr-2"></i>
                                        Rapport
                                    </a>
                                @endif
                                
                                <!-- Boutons de partage sur les réseaux sociaux -->
                                <div class="flex items-center space-x-2">
                                    <!-- Partage Facebook -->
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                                       target="_blank"
                                       class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-all duration-300 flex items-center justify-center"
                                       title="Partager sur Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    
                                    <!-- Partage Twitter -->
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($evenement->titre) }}" 
                                       target="_blank"
                                       class="bg-sky-500 hover:bg-sky-600 text-white p-2 rounded-lg transition-all duration-300 flex items-center justify-center"
                                       title="Partager sur Twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    
                                    <!-- Partage LinkedIn -->
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                                       target="_blank"
                                       class="bg-blue-700 hover:bg-blue-800 text-white p-2 rounded-lg transition-all duration-300 flex items-center justify-center"
                                       title="Partager sur LinkedIn">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                    
                                    <!-- Partage WhatsApp -->
                                    <a href="https://wa.me/?text={{ urlencode($evenement->titre . ' - ' . request()->url()) }}" 
                                       target="_blank"
                                       class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-lg transition-all duration-300 flex items-center justify-center"
                                       title="Partager sur WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    
                                    <!-- Copier le lien -->
                                    <button onclick="copyToClipboard('{{ request()->url() }}')" 
                                            class="bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-lg transition-all duration-300 flex items-center justify-center"
                                            title="Copier le lien">
                                        <i class="fas fa-link"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Titre -->
                        <h1 class="text-3xl md:text-4xl font-bold mb-4 leading-tight">
                            {{ $evenement->titre }}
                        </h1>
                        
                        <!-- Résumé -->
                        @if($evenement->resume)
                            <p class="text-lg text-gray-100 leading-relaxed">
                                {{ $evenement->resume }}
                            </p>
                        @endif
                    </div>
                    
                    <!-- Informations pratiques -->
                    <div class="bg-gray-50 p-6 border-b">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Date de début -->
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-iri-accent rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-day text-white text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Date de début</p>
                                    <p class="text-sm font-bold text-gray-900">
                                        {{ \Carbon\Carbon::parse($evenement->date_debut)->format('d M Y à H:i') }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Date de fin -->
                            @if($evenement->date_fin)
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-iri-secondary rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-check text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Date de fin</p>
                                        <p class="text-sm font-bold text-gray-900">
                                            {{ \Carbon\Carbon::parse($evenement->date_fin)->format('d M Y à H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Lieu -->
                            @if($evenement->lieu)
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-iri-gold rounded-lg flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Lieu</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $evenement->lieu }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Image de l'événement -->
                    @if($evenement->image)
                        <div class="relative h-64 md:h-96 overflow-hidden">
                            <img src="{{ asset('storage/' . $evenement->image) }}" 
                                 alt="{{ $evenement->titre }}"
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        </div>
                    @endif
                    
                    <!-- Description détaillée -->
                    @if($evenement->description)
                        <div class="p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-info-circle text-iri-accent mr-3"></i>
                                Description détaillée
                            </h2>
                            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                {!! nl2br(e($evenement->description)) !!}
                            </div>
                        </div>
                    @endif
                    
                </article>
            </div>
            
            <!-- Sidebar -->
            <aside class="lg:col-span-1">
                
                <!-- Autres événements -->
                @if($autresEvenements->count() > 0)
                    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-calendar-alt text-iri-accent mr-3"></i>
                            Autres événements
                        </h3>
                        
                        <div class="space-y-4">
                            @foreach($autresEvenements as $autre)
                                <div class="border-l-4 border-iri-accent pl-4 hover:bg-gray-50 rounded-r-lg p-3 transition-colors duration-200">
                                    <a href="{{ route('site.evenement.show', $autre->slug) }}" class="block">
                                        <h4 class="font-semibold text-gray-900 hover:text-iri-accent transition-colors duration-200 mb-1">
                                            {{ Str::limit($autre->titre, 60) }}
                                        </h4>
                                        <div class="flex items-center text-xs text-gray-500 mb-2">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::parse($autre->date_debut)->format('d M Y') }}
                                            @if($autre->lieu)
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ Str::limit($autre->lieu, 20) }}
                                            @endif
                                        </div>
                                        @if($autre->resume)
                                            <p class="text-xs text-gray-600">
                                                {{ Str::limit($autre->resume, 100) }}
                                            </p>
                                        @endif
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Retour à l'accueil -->
                <div class="bg-gradient-to-br from-iri-primary to-iri-secondary rounded-xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-bold mb-4">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Navigation
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('site.home') }}" 
                           class="block bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/30 rounded-lg p-3 transition-all duration-300 group">
                            <div class="flex items-center">
                                <i class="fas fa-home mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span class="font-medium">Retour à l'accueil</span>
                            </div>
                        </a>
                        
                        <a href="{{ route('site.home') }}#publications" 
                           class="block bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/30 rounded-lg p-3 transition-all duration-300 group">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span class="font-medium">Voir tous les événements</span>
                            </div>
                        </a>
                    </div>
                </div>
                
            </aside>
            
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        .print-break {
            page-break-before: always;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Afficher une notification de succès
            const notification = document.createElement('div');
            notification.innerHTML = '<i class="fas fa-check mr-2"></i>Lien copié !';
            notification.className = 'fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-all duration-300';
            document.body.appendChild(notification);
            
            // Supprimer la notification après 3 secondes
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }).catch(function(err) {
            console.error('Erreur lors de la copie: ', err);
            // Fallback pour les navigateurs plus anciens
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            
            const notification = document.createElement('div');
            notification.innerHTML = '<i class="fas fa-check mr-2"></i>Lien copié !';
            notification.className = 'fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        });
    }
</script>
@endpush
