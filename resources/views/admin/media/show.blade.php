@extends('layouts.admin')

@section('breadcrumbs')
<nav class="text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-white/70 hover:text-white">
                <i class="fas fa-home mr-2"></i>Tableau de bord
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <a href="{{ route('admin.media.index') }}" class="text-white/70 hover:text-white">Médiathèque</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right mx-2 text-white/50"></i>
                <span class="text-white">{{ Str::limit($media->titre, 30) }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-iri-light via-white to-iri-light/50">
    <div class="max-w-6xl mx-auto px-4 py-8">
        
        {{-- En-tête avec actions --}}
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-iri-dark mb-2">{{ $media->titre }}</h1>
                    <div class="flex items-center gap-4 text-sm text-iri-gray">
                        <span class="flex items-center">
                            <i class="fas fa-user mr-1"></i>
                            {{ $media->creator->name ?? 'Utilisateur supprimé' }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $media->created_at->format('d/m/Y à H:i') }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                            @if($media->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($media->status === 'approved') bg-green-100 text-green-800
                            @elseif($media->status === 'rejected') bg-red-100 text-red-800
                            @elseif($media->status === 'published') bg-blue-100 text-blue-800
                            @endif">
                            {{ $media->status_label }}
                        </span>
                    </div>
                </div>
                
                {{-- Actions principales --}}
                <div class="flex flex-wrap gap-3">
                    @can('update', $media)
                        <a href="{{ route('admin.media.edit', $media) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-accent to-iri-gold hover:from-iri-gold hover:to-iri-accent text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-edit mr-2"></i>Modifier
                        </a>
                    @endcan
                    
                    @can('download', $media)
                        <a href="{{ route('admin.media.download', $media) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-primary text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-download mr-2"></i>Télécharger
                        </a>
                    @endcan
                    
                    <a href="{{ route('admin.media.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Aperçu du média --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-6 py-4">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            @if($media->isImage())
                                <i class="fas fa-image mr-2"></i>Aperçu de l'image
                            @else
                                <i class="fas fa-video mr-2"></i>Aperçu de la vidéo
                            @endif
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="relative bg-gray-50 rounded-xl overflow-hidden mb-4">
                            @if($media->isImage())
                                <img src="{{ asset('storage/' . $media->medias) }}" 
                                     alt="{{ $media->alt_text ?: $media->titre }}" 
                                     class="w-full h-auto max-h-96 object-contain mx-auto">
                            @elseif($media->isVideo())
                                <video controls class="w-full h-auto max-h-96 mx-auto">
                                    <source src="{{ asset('storage/' . $media->medias) }}" type="{{ $media->mime_type }}">
                                    Votre navigateur ne prend pas en charge la vidéo.
                                </video>
                            @endif
                        </div>
                        
                        {{-- Actions sur le média --}}
                        <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                            <button onclick="copyToClipboard('{{ asset('storage/' . $media->medias) }}')" 
                                    class="inline-flex items-center px-3 py-2 bg-iri-accent hover:bg-iri-gold text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-copy mr-2"></i>Copier le lien
                            </button>
                            
                            <button onclick="copyToClipboard('{{ asset('storage/' . $media->medias) }}', 'html')" 
                                    class="inline-flex items-center px-3 py-2 bg-purple-500 hover:bg-purple-600 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-code mr-2"></i>Copier HTML
                            </button>
                            
                            <button onclick="openFullscreen('{{ asset('storage/' . $media->medias) }}', '{{ $media->titre }}')" 
                                    class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-expand mr-2"></i>Plein écran
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Informations et actions --}}
            <div class="space-y-6">
                {{-- Informations générales --}}
                <div class="bg-white rounded-2xl shadow-lg border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-iri-primary to-iri-secondary px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">
                            <i class="fas fa-info-circle mr-2"></i>Informations
                        </h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-1">Type de fichier</label>
                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-sm bg-iri-light text-iri-primary">
                                {{ strtoupper($media->file_extension) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-1">Taille</label>
                            <span class="text-sm text-iri-dark">{{ $media->file_size_formatted }}</span>
                        </div>
                        
                        @if($media->description)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-1">Description</label>
                            <p class="text-sm text-iri-dark">{{ $media->description }}</p>
                        </div>
                        @endif
                        
                        @if($media->alt_text)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-1">Texte alternatif</label>
                            <p class="text-sm text-iri-dark">{{ $media->alt_text }}</p>
                        </div>
                        @endif
                        
                        @if($media->tags && count($media->tags) > 0)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-2">Tags</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($media->tags as $tag)
                                    <span class="px-2 py-1 bg-iri-accent/10 text-iri-accent text-xs rounded-lg">
                                        #{{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        @if($media->projet)
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-1">Projet associé</label>
                            <a href="{{ route('admin.projets.show', $media->projet) }}" 
                               class="text-iri-accent hover:text-iri-gold transition-colors">
                                {{ $media->projet->titre }}
                            </a>
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-iri-gray mb-1">Visibilité</label>
                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-sm 
                                {{ $media->is_public ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                <i class="fas {{ $media->is_public ? 'fa-globe' : 'fa-lock' }} mr-1"></i>
                                {{ $media->is_public ? 'Public' : 'Privé' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                {{-- Actions de modération --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                        <h2 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-gavel mr-3"></i>
                            Actions de modération
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Historique de modération -->
                        @if($media->moderated_at || $media->moderated_by || $media->rejection_reason)
                        <div class="bg-blue-50 rounded-lg p-4 mb-4 border border-blue-200">
                            <h3 class="text-sm font-medium text-blue-800 mb-3 flex items-center">
                                <i class="fas fa-history mr-2"></i>Historique de modération
                            </h3>
                            <div class="space-y-2 text-sm">
                                <!-- Statut actuel -->
                                <div class="flex items-center">
                                    <i class="fas {{ $media->status === 'approved' ? 'fa-check-circle text-green-600' : ($media->status === 'rejected' ? 'fa-times-circle text-red-600' : 'fa-clock text-yellow-600') }} mr-2"></i>
                                    <span class="font-medium {{ $media->status === 'approved' ? 'text-green-800' : ($media->status === 'rejected' ? 'text-red-800' : 'text-yellow-800') }}">
                                        @switch($media->status)
                                            @case('approved')
                                                Approuvé
                                                @break
                                            @case('rejected')
                                                Rejeté
                                                @break
                                            @case('published')
                                                Publié
                                                @break
                                            @default
                                                En attente de modération
                                        @endswitch
                                    </span>
                                </div>

                                <!-- Date et modérateur -->
                                @if($media->moderated_at)
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-calendar mr-2 text-gray-500"></i>
                                    <span>{{ $media->moderated_at->format('d/m/Y à H:i') }}</span>
                                </div>
                                @endif

                                @if($media->moderated_by)
                                    @php
                                        $moderator = \App\Models\User::find($media->moderated_by);
                                    @endphp
                                    @if($moderator)
                                    <div class="flex items-center text-gray-700">
                                        <i class="fas fa-user-shield mr-2 text-gray-500"></i>
                                        <span>par {{ $moderator->prenom }} {{ $moderator->nom }}</span>
                                    </div>
                                    @endif
                                @endif

                                <!-- Commentaire de modération -->
                                @if($media->rejection_reason)
                                <div class="mt-3 p-3 bg-white rounded border-l-4 border-red-400">
                                    <div class="flex items-start">
                                        <i class="fas fa-comment-dots mr-2 text-red-600 mt-0.5"></i>
                                        <div>
                                            <div class="text-xs text-gray-500 mb-1">Commentaire du modérateur :</div>
                                            <div class="text-gray-800 italic">
                                                "{{ $media->rejection_reason }}"
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Actions de modération (nécessitent des permissions spéciales) -->
                        @can('moderate', $media)
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-shield-alt mr-2"></i>Actions de modération
                            </h3>
                            <div class="space-y-2">
                                @if($media->status === 'pending')
                                <button onclick="moderateMedia('approve', {{ $media->id }})" 
                                        class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check mr-2"></i>Approuver
                                </button>
                                <button onclick="moderateMedia('reject', {{ $media->id }})" 
                                        class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Rejeter
                                </button>
                                @elseif($media->status === 'approved')
                                <button onclick="moderateMedia('publish', {{ $media->id }})" 
                                        class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-globe mr-2"></i>Publier
                                </button>
                                <button onclick="moderateMedia('reject', {{ $media->id }})" 
                                        class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Rejeter
                                </button>
                                @elseif($media->status === 'published' || $media->status === 'active')
                                <button onclick="moderateMedia('unpublish', {{ $media->id }})" 
                                        class="w-full flex items-center justify-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                    <i class="fas fa-eye-slash mr-2"></i>Dépublier
                                </button>
                                @elseif($media->status === 'rejected')
                                <button onclick="moderateMedia('approve', {{ $media->id }})" 
                                        class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check mr-2"></i>Réapprouver
                                </button>
                                @endif
                            </div>
                        </div>
                        @endcan

                        <!-- Actions d'édition standard -->
                        @can('update', $media)
                        <a href="{{ route('admin.media.edit', $media) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors">
                            <i class="fas fa-edit mr-2"></i>Modifier
                        </a>
                        @endcan
                    </div>
                </div>
                
                {{-- Actions de suppression --}}
                @can('delete', $media)
                <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-red-500 to-red-600">
                        <h3 class="text-lg font-semibold text-white">
                            <i class="fas fa-trash mr-2"></i>Zone de danger
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">
                            La suppression de ce média est irréversible. Tous les liens vers ce média seront brisés.
                        </p>
                        <form method="POST" action="{{ route('admin.media.destroy', $media) }}" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce média ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-trash mr-2"></i>Supprimer définitivement
                            </button>
                        </form>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>

{{-- Modal de rejet --}}
<div id="rejectModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <h3 class="text-xl font-semibold text-white">Rejeter le média</h3>
        </div>
        
        <form method="POST" action="{{ route('admin.media.reject', $media) }}">
            @csrf
            <div class="p-6">
                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Raison du rejet *
                </label>
                <textarea name="rejection_reason" id="rejection_reason" rows="4" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Expliquez pourquoi ce média est rejeté..."></textarea>
                
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeRejectModal()" 
                            class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors">
                        Rejeter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal plein écran --}}
<div id="fullscreenModal" class="fixed inset-0 bg-black/90 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="relative max-w-full max-h-full p-4">
        <button onclick="closeFullscreen()" 
                class="absolute top-4 right-4 text-white/70 hover:text-white bg-black/50 rounded-full p-2 transition-all duration-200">
            <i class="fas fa-times text-xl"></i>
        </button>
        <div id="fullscreenContent"></div>
    </div>
</div>

{{-- Notification de copie --}}
<div id="copyNotification" class="fixed bottom-4 right-4 bg-iri-primary text-white px-6 py-3 rounded-xl shadow-lg transform translate-x-full transition-transform duration-300">
    <div class="flex items-center">
        <i class="fas fa-check mr-2"></i>
        <span id="copyMessage">Lien copié dans le presse-papiers !</span>
    </div>
</div>

<!-- Modal de modération -->
<div id="moderationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modal-title" class="text-lg font-medium text-gray-900"></h3>
                <button onclick="closeModerationModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="moderationForm" class="space-y-4">
                @csrf
                <div>
                    <label for="moderationComment" class="block text-sm font-medium text-gray-700 mb-2">
                        Commentaire (optionnel)
                    </label>
                    <textarea name="moderationComment" id="moderationComment" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary"
                              placeholder="Ajoutez un commentaire sur cette action..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        Ce commentaire sera visible dans l'historique de modération
                    </p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModerationModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" id="confirmModerationBtn" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function copyToClipboard(url, format = 'url') {
    let textToCopy = url;
    
    if (format === 'html') {
        if (url.match(/\.(jpeg|jpg|gif|png|svg)$/i)) {
            textToCopy = `<img src="${url}" alt="{{ $media->alt_text ?: $media->titre }}" />`;
        } else if (url.match(/\.(mp4|webm|mov)$/i)) {
            textToCopy = `<video controls><source src="${url}" type="{{ $media->mime_type }}"></video>`;
        }
    }
    
    navigator.clipboard.writeText(textToCopy).then(function() {
        showCopyNotification(format === 'html' ? 'Code HTML copié !' : 'Lien copié !');
    }).catch(function(err) {
        console.error('Erreur lors de la copie:', err);
        
        // Fallback pour les navigateurs plus anciens
        const textArea = document.createElement('textarea');
        textArea.value = textToCopy;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showCopyNotification(format === 'html' ? 'Code HTML copié !' : 'Lien copié !');
    });
}

function showCopyNotification(message) {
    const notification = document.getElementById('copyNotification');
    const messageSpan = document.getElementById('copyMessage');
    messageSpan.textContent = message;
    notification.classList.remove('hidden');
    
    setTimeout(() => {
        notification.classList.add('hidden');
    }, 2000);
}

function openFullscreen(imageSrc, title) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="relative max-w-full max-h-full p-4">
            <button onclick="this.parentElement.parentElement.remove()" 
                    class="absolute top-2 right-2 text-white bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-75 transition-all duration-200 z-10">
                <i class="fas fa-times text-xl"></i>
            </button>
            <img src="${imageSrc}" alt="${title}" class="max-w-full max-h-full rounded-lg shadow-2xl">
        </div>
    `;
    document.body.appendChild(modal);
    
    // Fermer avec Échap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            modal.remove();
        }
    });
    
    // Fermer en cliquant sur le fond
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

// Variables globales pour la modération
let currentModerationAction = null;
let currentMediaId = null;

function moderateMedia(action, mediaId) {
    currentModerationAction = action;
    currentMediaId = mediaId;
    
    const modal = document.getElementById('moderationModal');
    const title = document.getElementById('modal-title');
    const confirmBtn = document.getElementById('confirmModerationBtn');
    
    // Configuration du modal selon l'action
    switch(action) {
        case 'approve':
            title.textContent = 'Approuver le média';
            confirmBtn.textContent = 'Approuver';
            confirmBtn.className = 'px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors';
            break;
        case 'reject':
            title.textContent = 'Rejeter le média';
            confirmBtn.textContent = 'Rejeter';
            confirmBtn.className = 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors';
            break;
        case 'publish':
            title.textContent = 'Publier le média';
            confirmBtn.textContent = 'Publier';
            confirmBtn.className = 'px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors';
            break;
        case 'unpublish':
            title.textContent = 'Dépublier le média';
            confirmBtn.textContent = 'Dépublier';
            confirmBtn.className = 'px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors';
            break;
    }
    
    modal.classList.remove('hidden');
}

function closeModerationModal() {
    const modal = document.getElementById('moderationModal');
    modal.classList.add('hidden');
    document.getElementById('moderationComment').value = '';
    currentModerationAction = null;
    currentMediaId = null;
}

// Gestion du formulaire de modération
document.getElementById('moderationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!currentModerationAction || !currentMediaId) {
        return;
    }
    
    const comment = document.getElementById('moderationComment').value;
    
    // Validation pour le rejet (commentaire obligatoire)
    if (currentModerationAction === 'reject' && !comment.trim()) {
        showNotification('Veuillez fournir une raison pour le rejet', 'error');
        return;
    }
    
    const confirmBtn = document.getElementById('confirmModerationBtn');
    const originalText = confirmBtn.textContent;
    
    // Afficher le loading
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Traitement...';
    
    // Construire l'URL et les données
    const url = `/admin/media/${currentMediaId}/${currentModerationAction}`;
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    if (comment) {
        if (currentModerationAction === 'reject') {
            formData.append('rejection_reason', comment);
        } else {
            formData.append('comment', comment);
        }
    }
    
    // Debug: afficher les données envoyées
    console.log('=== DEBUG MODÉRATION ===');
    console.log('Action:', currentModerationAction);
    console.log('Media ID:', currentMediaId);
    console.log('URL:', url);
    console.log('Comment:', comment);
    console.log('FormData entries:');
    for (let [key, value] of formData.entries()) {
        console.log(`  ${key}: ${value}`);
    }
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            showNotification(data.message || 'Action de modération effectuée avec succès', 'success');
            // Recharger la page après 1 seconde
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Erreur lors de l\'action de modération', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur complète:', error);
        console.error('URL appelée:', url);
        console.error('Données envoyées:', formData);
        
        showNotification('Erreur lors de l\'action de modération: ' + error.message, 'error');
    })
    .finally(() => {
        confirmBtn.disabled = false;
        confirmBtn.textContent = originalText;
        closeModerationModal();
    });
});

function showNotification(message, type = 'success') {
    // Créer la notification
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Supprimer après 4 secondes
    setTimeout(() => {
        notification.remove();
    }, 4000);
}

function showCopyNotification(message) {
    const notification = document.getElementById('copyNotification');
    const messageElement = document.getElementById('copyMessage');
    
    messageElement.textContent = message;
    notification.classList.remove('translate-x-full');
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
    }, 3000);
}

function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

function openFullscreen(url, title) {
    const modal = document.getElementById('fullscreenModal');
    const content = document.getElementById('fullscreenContent');
    
    if (url.match(/\.(jpeg|jpg|gif|png|svg)$/i)) {
        content.innerHTML = `<img src="${url}" alt="${title}" class="max-w-full max-h-full object-contain">`;
    } else if (url.match(/\.(mp4|webm|mov)$/i)) {
        content.innerHTML = `<video controls class="max-w-full max-h-full"><source src="${url}" type="{{ $media->mime_type }}"></video>`;
    }
    
    modal.classList.remove('hidden');
}

function closeFullscreen() {
    document.getElementById('fullscreenModal').classList.add('hidden');
}

// Fermer les modaux avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRejectModal();
        closeFullscreen();
    }
});
</script>
@endsection
