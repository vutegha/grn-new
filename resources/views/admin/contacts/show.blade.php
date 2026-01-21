@extends('layouts.admin')

@section('title', 'Détail du Message')
@section('subtitle', 'Consultation et gestion du message de contact')

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <a href="{{ route('admin.contacts.index') }}" 
           class="group inline-flex items-center text-iri-primary hover:text-iri-secondary transition-colors duration-200 font-medium">
            <i class="fas fa-envelope mr-2 group-hover:rotate-12 transition-transform duration-200"></i>
            Messages
        </a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <span class="text-iri-gray font-medium">{{ Str::limit($contact->sujet, 30) }}</span>
    </li>
@endsection

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header avec actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-iri-dark flex items-center">
                <i class="fas fa-envelope-open text-iri-primary mr-3"></i>
                Détail du Message
            </h1>
            <p class="text-iri-gray mt-2">
                <i class="fas fa-info-circle mr-2"></i>
                Consultation et gestion du message de contact
            </p>
        </div>
        
        <!-- Actions -->
        <div class="flex space-x-3">
            <a href="{{ route('admin.contacts.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-iri-gray/10 text-iri-gray rounded-lg hover:bg-iri-gray/20 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2">
            <!-- Informations du contact -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-user mr-3"></i>
                        Informations du Contact
                    </h2>
                    @switch($contact->statut)
                        @case('nouveau')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                <i class="fas fa-exclamation-circle mr-1"></i>Nouveau
                            </span>
                            @break
                        @case('lu')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-gold/20 text-iri-gold border border-iri-gold/30">
                                <i class="fas fa-eye mr-1"></i>Lu
                            </span>
                            @break
                        @case('traite')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-accent/20 text-iri-accent border border-iri-accent/30">
                                <i class="fas fa-check-circle mr-1"></i>Traité
                            </span>
                            @break
                        @case('ferme')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                <i class="fas fa-times-circle mr-1"></i>Fermé
                            </span>
                            @break
                    @endswitch
                </div>
                <div class="p-6">
                    <!-- En-tête contact -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-xl font-semibold text-iri-primary mb-2">{{ $contact->nom }}</h3>
                            <p class="text-iri-gray flex items-center">
                                <i class="fas fa-envelope mr-2"></i>{{ $contact->email }}
                            </p>
                        </div>
                        <div class="md:text-right">
                            <small class="text-iri-gray flex items-center md:justify-end">
                                <i class="fas fa-calendar mr-2"></i>
                                Reçu le {{ $contact->created_at->format('d/m/Y à H:i') }}
                            </small>
                        </div>
                    </div>

                    <!-- Sujet -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-tag mr-2"></i>Sujet
                        </label>
                        <div class="p-4 bg-gradient-to-r from-iri-light to-gray-50 rounded-lg border border-iri-primary/20">
                            <p class="text-iri-dark font-medium">{{ $contact->sujet }}</p>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-iri-gray mb-2">
                            <i class="fas fa-comment mr-2"></i>Message
                        </label>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $contact->message }}</p>
                        </div>
                    </div>

                    <!-- Métadonnées -->
                    @if($contact->lu_a || $contact->traite_a)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-6 border-t border-gray-200">
                            @if($contact->lu_a)
                                <div class="flex items-center text-sm text-iri-gray">
                                    <i class="fas fa-eye mr-2 text-iri-gold"></i>
                                    Lu le {{ $contact->lu_a->format('d/m/Y à H:i') }}
                                </div>
                            @endif
                            @if($contact->traite_a)
                                <div class="flex items-center text-sm text-iri-gray">
                                    <i class="fas fa-check-circle mr-2 text-iri-accent"></i>
                                    Traité le {{ $contact->traite_a->format('d/m/Y à H:i') }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Réponse/Notes -->
                    @if($contact->reponse)
                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-reply mr-2"></i>Réponse/Notes internes
                            </label>
                            <div class="p-4 bg-gradient-to-r from-iri-primary/10 to-iri-secondary/10 rounded-lg border-l-4 border-iri-primary">
                                <p class="text-iri-dark whitespace-pre-line leading-relaxed">{{ $contact->reponse }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-bolt mr-3"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <!-- Répondre par email -->
                    <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->sujet }}&body=Bonjour {{ $contact->nom }},%0D%0A%0D%0ANous accusons réception de votre message concernant : {{ $contact->sujet }}%0D%0A%0D%0A"
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-reply mr-2"></i>
                        Répondre par email
                    </a>

                    <!-- Copier l'email -->
                    <button onclick="copyToClipboard('{{ $contact->email }}')" 
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-iri-gray/10 text-iri-gray rounded-lg hover:bg-iri-gray/20 transition-colors duration-200">
                        <i class="fas fa-clipboard mr-2"></i>
                        Copier l'email
                    </button>
                </div>
            </div>

            <!-- Gestion du statut -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-sync-alt mr-3"></i>
                        Gestion du Statut
                    </h2>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.contacts.update', $contact) }}" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <label for="statut" class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-flag mr-2"></i>Statut
                            </label>
                            <select name="statut" id="statut" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-transparent">
                                <option value="nouveau" {{ $contact->statut == 'nouveau' ? 'selected' : '' }}>Nouveau</option>
                                <option value="lu" {{ $contact->statut == 'lu' ? 'selected' : '' }}>Lu</option>
                                <option value="traite" {{ $contact->statut == 'traite' ? 'selected' : '' }}>Traité</option>
                                <option value="ferme" {{ $contact->statut == 'ferme' ? 'selected' : '' }}>Fermé</option>
                            </select>
                        </div>

                        <div>
                            <label for="reponse" class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-comment-dots mr-2"></i>Réponse/Notes internes
                            </label>
                            <textarea name="reponse" id="reponse" rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-transparent resize-none"
                                      placeholder="Ajoutez une note ou votre réponse...">{{ $contact->reponse }}</textarea>
                        </div>

                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-accent to-iri-gold text-white rounded-lg hover:from-iri-gold hover:to-iri-accent transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            Mettre à jour
                        </button>
                    </form>
                </div>
            </div>

            <!-- Zone de danger -->
            <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-red-500 to-red-600">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3"></i>
                        Zone de Danger
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 text-sm mb-4">
                        La suppression de ce contact est irréversible. Toutes les données seront perdues.
                    </p>
                    <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce contact ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer ce contact
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Créer une notification Tailwind
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-iri-accent text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300';
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    Email copié dans le presse-papier !
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animer l'apparition
            setTimeout(() => {
                notification.classList.remove('translate-x-full', 'opacity-0');
            }, 100);
            
            // Animer la disparition après 3 secondes
            setTimeout(() => {
                notification.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }, function(err) {
            console.error('Erreur lors de la copie : ', err);
        });
    }
</script>
@endsection
