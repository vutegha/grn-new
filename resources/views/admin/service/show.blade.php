@extends('layouts.admin')

@section('title', 'Détail du Service')
@section('subtitle', 'Visualisation du service : ' . $service->nom)

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <a href="{{ route('admin.service.index') }}" 
           class="group inline-flex items-center text-iri-primary hover:text-iri-secondary transition-colors duration-200 font-medium">
            <i class="fas fa-cogs mr-2 group-hover:rotate-45 transition-transform duration-200"></i>
            Services
        </a>
    </li>
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <span class="text-iri-gray font-medium">{{ $service->nom }}</span>
    </li>
@endsection

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header avec actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-iri-dark flex items-center">
                <i class="fas fa-cog text-iri-primary mr-3"></i>
                {{ $service->nom }}
            </h1>
            <p class="text-iri-gray mt-2">
                <i class="fas fa-info-circle mr-2"></i>
                Détails complets du service
            </p>
        </div>
        
        <!-- Actions -->
        <div class="flex space-x-3">
            {{-- @can('update', $service) --}}
            <a href="{{ route('admin.service.edit', $service) }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            {{-- @endcan --}}
            <a href="{{ route('admin.service.index') }}" 
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
            <!-- Informations principales -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Informations Principales
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-tag mr-2"></i>Nom du service
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $service->nom }}
                            </div>
                        </div>
                        
                        @if($service->nom_menu)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-bars mr-2"></i>Nom dans le menu
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $service->nom_menu }}
                            </div>
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-calendar-plus mr-2"></i>Date de création
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $service->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">
                                <i class="fas fa-calendar-edit mr-2"></i>Dernière modification
                            </label>
                            <div class="p-3 bg-gray-50 rounded-lg border">
                                {{ $service->updated_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($service->description)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-align-left mr-3"></i>
                        Description
                    </h2>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none text-gray-700">
                        {!! $service->description !!}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
            <!-- Statut et visibilité -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-eye mr-3"></i>
                        Statut & Visibilité
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Statut de publication -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-globe mr-3 text-iri-gray"></i>
                            <span class="font-medium">Publication</span>
                        </div>
                        @if($service->is_published)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-accent/20 text-iri-accent border border-iri-accent/30">
                                <i class="fas fa-check-circle mr-1"></i>Publié
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-iri-gold/20 text-iri-gold border border-iri-gold/30">
                                <i class="fas fa-clock mr-1"></i>Brouillon
                            </span>
                        @endif
                    </div>

                    <!-- Présence dans le menu -->
                    <div class="flex items-center justify-between p-3 rounded-lg border">
                        <div class="flex items-center">
                            <i class="fas fa-bars mr-3 text-iri-gray"></i>
                            <div>
                                <span class="font-medium">Menu "Programmes"</span>
                                @if($service->nom_menu)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Apparaîtra comme : "<strong>{{ $service->nom_menu }}</strong>"
                                    </div>
                                @else
                                    <div class="text-xs text-gray-500 mt-1">
                                        Apparaîtra comme : "<strong>{{ $service->nom }}</strong>"
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($service->is_published && $service->show_in_menu && ($service->nom_menu || $service->nom))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                    <i class="fas fa-check mr-1"></i>Visible
                                </span>
                            @elseif($service->is_published && !$service->show_in_menu)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800 border border-orange-200">
                                    <i class="fas fa-eye-slash mr-1"></i>Masqué
                                </span>
                            @elseif(!$service->is_published)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    <i class="fas fa-times mr-1"></i>Non publié
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                    <i class="fas fa-clock mr-1"></i>En attente
                                </span>
                            @endif
                            
                            <!-- Actions de menu -->
                            {{-- @can('moderate', $service) --}}
                                @if($service->is_published)
                                    @if($service->show_in_menu)
                                        <form action="{{ route('admin.service.toggle-menu', $service) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('POST')
                                            <input type="hidden" name="show_in_menu" value="0">
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1 bg-orange-500 text-white text-xs rounded-lg hover:bg-orange-600 transition-colors duration-200"
                                                    title="Retirer du menu Programmes">
                                                <i class="fas fa-eye-slash mr-1"></i>
                                                Masquer
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.service.toggle-menu', $service) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('POST')
                                            <input type="hidden" name="show_in_menu" value="1">
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1 bg-purple-500 text-white text-xs rounded-lg hover:bg-purple-600 transition-colors duration-200"
                                                    title="Ajouter au menu Programmes">
                                                <i class="fas fa-eye mr-1"></i>
                                                Afficher
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-3 py-1 bg-gray-300 text-gray-500 text-xs rounded-lg" title="Le service doit être publié d'abord">
                                        <i class="fas fa-lock mr-1"></i>
                                        Indisponible
                                    </span>
                                @endif
                            {{-- @else
                                <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-600 text-xs rounded-lg" title="Permissions insuffisantes">
                                    <i class="fas fa-ban mr-1"></i>
                                    Non autorisé
                                </span>
                            @endcan --}}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de modération -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-indigo-600">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-shield-alt mr-3"></i>
                        Modération
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Statut de modération -->
                    <div class="p-4 rounded-lg border border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-medium text-gray-700">
                                <i class="fas fa-check-circle mr-2"></i>Statut
                            </span>
                            @if($service->is_published)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                    <i class="fas fa-check mr-1"></i>Approuvé
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    <i class="fas fa-clock mr-1"></i>En attente
                                </span>
                            @endif
                        </div>
                        
                        @if($service->published_at)
                        <div class="text-sm text-gray-600 mb-2">
                            <i class="fas fa-calendar mr-2"></i>
                            <strong>Date de publication :</strong> {{ $service->published_at->format('d/m/Y à H:i') }}
                        </div>
                        @endif
                        
                        @if($service->published_by)
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-user mr-2"></i>
                            <strong>Publié par :</strong> 
                            @if($service->publisher)
                                {{ $service->publisher->name }}
                            @else
                                Utilisateur #{{ $service->published_by }}
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Commentaire de modération -->
                    @if($service->moderation_comment)
                    <div class="p-4 rounded-lg border border-blue-200 bg-blue-50">
                        <div class="flex items-start">
                            <i class="fas fa-comment-alt text-blue-600 mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-blue-800 mb-2">Commentaire de modération</h4>
                                <p class="text-blue-700 text-sm leading-relaxed">{{ $service->moderation_comment }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

            <!-- Actions de modération -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-gavel mr-3"></i>
                        Actions
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <!-- Actions de modération (nécessitent des permissions spéciales) -->
                    @can('moderate', $service)
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-shield-alt mr-2"></i>Actions de modération
                        </h3>
                        <div class="space-y-2">
                            @if(!$service->is_published)
                            <button onclick="moderateService('publish', {{ $service->id }})" 
                                    class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-check mr-2"></i>Approuver et Publier
                            </button>
                            @else
                            <button onclick="moderateService('unpublish', {{ $service->id }})" 
                                    class="w-full flex items-center justify-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                                <i class="fas fa-pause mr-2"></i>Dépublier
                            </button>
                            @endif
                        </div>
                    </div>
                    @endcan

                    <!-- Actions d'édition standard -->
                    @can('update', $service)
                    <a href="{{ route('admin.service.edit', $service) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    @endcan

                    @can('delete', $service)
                    <form action="{{ route('admin.service.destroy', $service) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                    @endcan
                </div>
            </div>

            <!-- Image du service -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-gold to-iri-accent">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-image mr-3"></i>
                        Image
                    </h2>
                </div>
                <div class="p-6">
                    <div class="relative rounded-lg overflow-hidden border border-gray-200">
                        <img src="{{ $service->image_url }}" 
                             class="w-full h-auto object-cover" 
                             alt="Image du service {{ $service->nom }}"
                             loading="lazy">
                    </div>
                    @if(!$service->hasValidImage())
                        <p class="text-sm text-gray-500 mt-2 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Aucune image personnalisée - Image par défaut affichée
                        </p>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-cogs mr-3"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    {{-- @can('update', $service) --}}
                    <a href="{{ route('admin.service.edit', $service) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier ce service
                    </a>
                    {{-- @endcan --}}
                    
                    {{-- @can('delete', $service) --}}
                    <form action="{{ route('admin.service.destroy', $service) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')" 
                          class="w-full">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer ce service
                        </button>
                    </form>
                    {{-- @endcan --}}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de modération unifié -->
@can('moderate', $service)
<div id="moderationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-gavel mr-3"></i>
                    <span id="modalTitle">Action de modération</span>
                </h3>
                <button onclick="closeModerationModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form id="moderationForm" method="POST" class="p-6">
            @csrf
            
            <div class="mb-4" id="commentSection">
                <label for="moderation_comment" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-comment mr-2"></i>Commentaire
                </label>
                <textarea name="moderation_comment" 
                          id="moderation_comment" 
                          rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                          placeholder="Ajoutez un commentaire de modération...">{{ $service->moderation_comment ?? '' }}</textarea>
            </div>
            
            <div class="flex space-x-3">
                <button type="submit" 
                        id="confirmButton"
                        class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-check mr-2"></i>
                    Confirmer
                </button>
                <button type="button" 
                        onclick="closeModerationModal()" 
                        class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>
@endcan

<script>
let currentAction = null;
let currentServiceId = {{ $service->id }};

function moderateService(action) {
    currentAction = action;
    const modal = document.getElementById('moderationModal');
    const form = document.getElementById('moderationForm');
    const title = document.getElementById('modalTitle');
    const button = document.getElementById('confirmButton');
    const commentSection = document.getElementById('commentSection');
    
    // Configuration selon l'action
    switch(action) {
        case 'publish':
            form.action = '{{ route("admin.service.publish", $service) }}';
            title.textContent = 'Publier le service';
            button.innerHTML = '<i class="fas fa-eye mr-2"></i>Publier';
            button.className = 'flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg';
            commentSection.style.display = 'block';
            break;
            
        case 'unpublish':
            form.action = '{{ route("admin.service.unpublish", $service) }}';
            title.textContent = 'Dépublier le service';
            button.innerHTML = '<i class="fas fa-eye-slash mr-2"></i>Dépublier';
            button.className = 'flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg';
            commentSection.style.display = 'block';
            break;
            
        case 'delete':
            form.action = '{{ route("admin.service.destroy", $service) }}';
            form.querySelector('input[name="_method"]').value = 'DELETE';
            title.textContent = 'Supprimer le service';
            button.innerHTML = '<i class="fas fa-trash mr-2"></i>Supprimer';
            button.className = 'flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg';
            commentSection.style.display = 'none';
            break;
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function confirmModerationAction() {
    const form = document.getElementById('moderationForm');
    
    // Envoi AJAX pour une UX fluide
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Notification de succès
            showNotification(data.message || 'Action effectuée avec succès', 'success');
            
            // Fermer le modal
            closeModerationModal();
            
            // Recharger la page pour refléter les changements
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Une erreur est survenue', 'error');
    });
}

function closeModerationModal() {
    document.getElementById('moderationModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentAction = null;
}

function showNotification(message, type = 'info') {
    // Créer la notification
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    } transform translate-x-full transition-transform duration-300`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${
                type === 'success' ? 'fa-check-circle' : 
                type === 'error' ? 'fa-exclamation-circle' : 
                'fa-info-circle'
            } mr-3"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animer l'entrée
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Supprimer après 3 secondes
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Gestion des événements
document.addEventListener('DOMContentLoaded', function() {
    // Soumettre le formulaire avec AJAX
    document.getElementById('moderationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        confirmModerationAction();
    });
    
    // Fermer avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModerationModal();
        }
    });
    
    // Fermer en cliquant à l'extérieur
    document.getElementById('moderationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModerationModal();
        }
    });
});
</script>
@endsection
