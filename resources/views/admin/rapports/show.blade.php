@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <a href="{{ route('admin.rapports.index') }}" 
           class="text-iri-gray hover:text-iri-primary transition-colors duration-200 font-medium">
            Rapports
        </a>
    </li>
    <li aria-current="page">
        <div class="flex items-center">
            <i class="fas fa-chevron-right mx-2 text-iri-gray/50"></i>
            <span class="text-iri-primary font-medium">{{ Str::limit($rapport->titre, 30) }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- En-tête du rapport -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h1 class="text-2xl font-bold text-white font-poppins">{{ $rapport->titre }}</h1>
                </div>
                <div class="p-6">
                    @if($rapport->image)
                    <div class="mb-6">
                        <img src="{{ Storage::url($rapport->image) }}" 
                             alt="{{ $rapport->titre }}" 
                             class="w-full h-64 object-cover rounded-lg border border-gray-200">
                    </div>
                    @endif
                    
                    <div class="prose max-w-none">
                        <div class="space-y-4">
                            <div class="text-sm text-iri-gray flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                @if($rapport->date_publication)
                                    Publié le {{ \Carbon\Carbon::parse($rapport->date_publication)->format('d/m/Y') }}
                                @else
                                    Date de publication non définie
                                @endif
                            </div>
                            
                            @if($rapport->updated_at && $rapport->updated_at != $rapport->created_at)
                            <div class="text-sm text-iri-gray flex items-center">
                                <i class="fas fa-edit mr-2"></i>
                                Modifié le {{ $rapport->updated_at->format('d/m/Y à H:i') }}
                            </div>
                            @endif
                        </div>
                        
                        @if($rapport->description)
                        <div class="mt-6 prose prose-lg max-w-none text-iri-dark leading-relaxed">
                            <h3 class="text-lg font-semibold text-iri-primary mb-3">Description</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $rapport->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Informations sur la catégorie -->
            @if($rapport->categorie)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-folder mr-3"></i>
                        Catégorie
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-iri-accent to-iri-gold rounded-lg flex items-center justify-center">
                            <i class="fas fa-tag text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-iri-primary">{{ $rapport->categorie->nom }}</h3>
                            @if($rapport->categorie->description)
                                <p class="text-iri-gray">{{ $rapport->categorie->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Fichier PDF si disponible -->
            @if($rapport->fichier && Storage::disk('public')->exists($rapport->fichier))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-file-pdf mr-3"></i>
                        Document PDF
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-pdf text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-iri-primary">{{ basename($rapport->fichier) }}</h3>
                                @php
                                    $filePath = storage_path('app/public/' . $rapport->fichier);
                                    $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                    $fileSizeFormatted = $fileSize > 1024 * 1024 
                                        ? round($fileSize / (1024 * 1024), 1) . ' MB'
                                        : round($fileSize / 1024, 1) . ' KB';
                                @endphp
                                <p class="text-iri-gray">Taille: {{ $fileSizeFormatted }}</p>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <a href="{{ Storage::url($rapport->fichier) }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-300 shadow-md">
                                <i class="fas fa-eye mr-2"></i>
                                Visualiser
                            </a>
                            <a href="{{ Storage::url($rapport->fichier) }}" download
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-accent to-iri-gold text-white font-semibold rounded-lg hover:from-iri-gold hover:to-iri-accent transition-all duration-300 shadow-md">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger
                            </a>
                        </div>
                    </div>
                    
                    <!-- Intégration PDF -->
                    <div class="mt-6">
                        <iframe src="{{ Storage::url($rapport->fichier) }}" 
                                class="w-full h-96 border border-gray-300 rounded-lg"
                                title="Aperçu du rapport PDF">
                        </iframe>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Panneau latéral -->
        <div class="space-y-6">
            <!-- Informations générales -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Informations
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-iri-gray">Statut:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                              {{ isset($rapport->is_published) && $rapport->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            <i class="fas {{ isset($rapport->is_published) && $rapport->is_published ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                            {{ isset($rapport->is_published) && $rapport->is_published ? 'Publié' : 'Brouillon' }}
                        </span>
                    </div>
                    
                    @if($rapport->date_publication)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-iri-gray">Date de publication:</span>
                        <span class="text-sm text-iri-primary">{{ \Carbon\Carbon::parse($rapport->date_publication)->format('d/m/Y') }}</span>
                    </div>
                    @endif
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-iri-gray">Créé le:</span>
                        <span class="text-sm text-iri-primary">{{ $rapport->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    @if($rapport->fichier)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-iri-gray">Fichier:</span>
                        <span class="text-sm text-green-600">
                            <i class="fas fa-check mr-1"></i>Disponible
                        </span>
                    </div>
                    @else
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-iri-gray">Fichier:</span>
                        <span class="text-sm text-red-600">
                            <i class="fas fa-times mr-1"></i>Non disponible
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informations de modération (similaire à publication/show) -->
            @if(isset($rapport->moderation_comment) || isset($rapport->published_at) || isset($rapport->published_by))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-accent to-iri-gold">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-shield-alt mr-3"></i>
                        Modération
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center space-x-3">
                        <i class="fas {{ isset($rapport->is_published) && $rapport->is_published ? 'fa-check-circle text-green-600' : 'fa-clock text-yellow-600' }} mr-2"></i>
                        <span class="font-medium {{ isset($rapport->is_published) && $rapport->is_published ? 'text-green-800' : 'text-yellow-800' }}">
                            {{ isset($rapport->is_published) && $rapport->is_published ? 'Publié' : 'En attente de modération' }}
                        </span>
                    </div>
                    
                    @if(isset($rapport->published_at))
                    <div class="text-sm text-iri-gray">
                        <i class="fas fa-calendar-check mr-2"></i>
                        <span>{{ \Carbon\Carbon::parse($rapport->published_at)->format('d/m/Y à H:i') }}</span>
                    </div>
                    @endif
                    
                    @if(isset($rapport->published_by))
                        @php
                            $moderator = \App\Models\User::find($rapport->published_by);
                        @endphp
                        @if($moderator)
                        <div class="text-sm text-iri-gray">
                            <i class="fas fa-user-shield mr-2"></i>
                            <span>Par {{ $moderator->name }}</span>
                        </div>
                        @endif
                    @endif
                    
                    @if(isset($rapport->moderation_comment))
                    <div class="mt-4 p-4 bg-iri-light rounded-lg border border-iri-primary/20">
                        <h4 class="text-sm font-semibold text-iri-primary mb-2">
                            <i class="fas fa-comment mr-2"></i>Commentaire de modération
                        </h4>
                        <p class="text-sm text-iri-gray italic leading-relaxed">
                            "{{ $rapport->moderation_comment }}"
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Actions de modération -->
            @can('moderate', $rapport)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-gavel mr-3"></i>
                        Actions de Modération
                    </h2>
                </div>
                <div class="p-6">
                    <!-- Informations de modération -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4 space-y-3">
                        <h3 class="font-semibold text-gray-700 mb-3">
                            <i class="fas fa-info-circle mr-2"></i>Informations de Modération
                        </h3>
                        
                        <!-- Statut actuel -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Statut</label>
                                @if($rapport->is_published)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                        <i class="fas fa-check-circle mr-1"></i>Publié
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <i class="fas fa-clock mr-1"></i>En attente
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Date de publication -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Date de publication</label>
                                <div class="text-sm text-gray-700">
                                    @if($rapport->published_at)
                                        <i class="fas fa-calendar-check mr-1 text-green-600"></i>
                                        {{ $rapport->published_at->format('d/m/Y à H:i') }}
                                    @else
                                        <i class="fas fa-calendar-times mr-1 text-gray-400"></i>
                                        Non publié
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Publié par -->
                        @if($rapport->published_by)
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Publié par</label>
                            <div class="text-sm text-gray-700">
                                <i class="fas fa-user mr-1 text-blue-600"></i>
                                {{ $rapport->publisher ? $rapport->publisher->name : 'Utilisateur supprimé' }}
                            </div>
                        </div>
                        @endif
                        
                        <!-- Commentaire de modération -->
                        <div id="moderation-comment-section" class="{{ $rapport->moderation_comment ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Commentaire de modération</label>
                            <div id="moderation-comment-display" class="text-sm text-gray-700 bg-white p-3 rounded border">
                                <i class="fas fa-comment mr-1 text-blue-600"></i>
                                <span id="moderation-comment-text">{{ $rapport->moderation_comment }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="space-y-3">
                        @if(!$rapport->is_published)
                            <button onclick="moderateRapport('publish')" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-eye mr-2"></i>
                                Publier ce rapport
                            </button>
                        @else
                            <button onclick="moderateRapport('unpublish')" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-eye-slash mr-2"></i>
                                Dépublier ce rapport
                            </button>
                        @endif
                        
                        @can('delete', $rapport)
                            <button onclick="moderateRapport('delete')" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-trash mr-2"></i>
                                Supprimer ce rapport
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
            @endcan

            <!-- Actions principales -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-tools mr-3"></i>
                        Actions
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    @can('update', $rapport)
                    <a href="{{ route('admin.rapports.edit', $rapport) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-md">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier le rapport
                    </a>
                    @endcan
                    
                    <a href="{{ route('admin.rapports.index') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-iri-gray text-white font-semibold rounded-lg hover:bg-iri-dark transition-all duration-300 shadow-md">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour à la liste
                    </a>
                    
                    @can('delete', $rapport)
                    <form action="{{ route('admin.rapports.destroy', $rapport) }}" method="POST" class="w-full" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ? Cette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-md">
                            <i class="fas fa-trash-alt mr-2"></i>
                            Supprimer le rapport
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de modération (identique à publication/show) -->
<div id="moderationModal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-md bg-white rounded-2xl shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 id="modal-title" class="text-xl font-bold text-iri-primary font-poppins">Action de Modération</h3>
            <button onclick="closeModerationModal()" class="text-iri-gray hover:text-iri-primary transition-colors duration-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="mb-6">
            <p id="modal-description" class="text-iri-gray mb-4">Êtes-vous sûr de vouloir effectuer cette action ?</p>
            
            <div class="mb-4">
                <label for="moderation_comment" class="block text-sm font-semibold text-iri-primary mb-2">
                    <i class="fas fa-comment mr-2"></i>Commentaire de modération (optionnel)
                </label>
                <textarea id="moderation_comment" rows="3" 
                          class="w-full p-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200"
                          placeholder="Ajoutez un commentaire sur cette action..."
                          oninput="updateCommentPreview()"></textarea>
                
                <!-- Aperçu du commentaire -->
                <div id="comment-preview" class="hidden mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="text-sm font-medium text-blue-700 mb-1">
                        <i class="fas fa-eye mr-1"></i>Aperçu du commentaire :
                    </div>
                    <div id="comment-preview-text" class="text-sm text-gray-700 italic"></div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3">
            <button onclick="closeModerationModal()" 
                    class="px-6 py-3 bg-iri-gray text-white rounded-lg hover:bg-iri-dark transition-colors duration-200 font-semibold">
                Annuler
            </button>
            <button onclick="confirmModerationAction()" 
                    class="px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-300 font-semibold">
                Confirmer
            </button>
        </div>
    </div>
</div>

<style>
    /* Styles pour l'iframe PDF avec amélioration responsive */
    iframe {
        background: #f8f9fa;
        transition: all 0.3s ease;
        min-height: 24rem; /* 96 en Tailwind */
    }
    
    iframe:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    @media (max-width: 768px) {
        iframe {
            height: 20rem !important; /* Plus compact sur mobile */
            min-height: 20rem;
        }
    }
    
    @media (max-width: 640px) {
        iframe {
            height: 16rem !important; /* Très compact sur très petit écran */
            min-height: 16rem;
        }
    }
</style>

<!-- Script pour les actions de modération (identique à publication/show) -->
<script>
    let currentModerationAction = null;
    let currentRapportId = {{ $rapport->id }};

    function moderateRapport(action) {
        currentModerationAction = action;
        
        const modal = document.getElementById('moderationModal');
        const title = document.getElementById('modal-title');
        const description = document.getElementById('modal-description');
        const commentField = document.getElementById('moderation_comment');
        
        // Configurer le modal selon l'action
        if (action === 'publish') {
            title.textContent = 'Approuver le Rapport';
            description.textContent = 'Êtes-vous sûr de vouloir approuver et publier ce rapport ?';
        } else if (action === 'unpublish') {
            title.textContent = 'Dépublier le Rapport';
            description.textContent = 'Êtes-vous sûr de vouloir dépublier ce rapport ?';
        }
        
        // Réinitialiser le commentaire et l'aperçu
        commentField.value = '';
        document.getElementById('comment-preview').classList.add('hidden');
        
        // Afficher le modal
        modal.classList.remove('hidden');
    }

    function closeModerationModal() {
        const modal = document.getElementById('moderationModal');
        modal.classList.add('hidden');
        currentModerationAction = null;
        
        // Nettoyer le commentaire et l'aperçu
        document.getElementById('moderation_comment').value = '';
        document.getElementById('comment-preview').classList.add('hidden');
    }

    function confirmModerationAction() {
        if (!currentModerationAction || !currentRapportId) {
            console.error('Action ou ID manquant:', currentModerationAction, currentRapportId);
            return;
        }

        const comment = document.getElementById('moderation_comment').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Afficher un indicateur de chargement
        const confirmButton = event.target;
        const originalText = confirmButton.innerHTML;
        confirmButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Traitement...';
        confirmButton.disabled = true;

        // Déterminer l'URL selon l'action
        let url = '';
        let method = 'POST';
        
        if (currentModerationAction === 'publish') {
            url = '{{ route("admin.rapports.publish", $rapport) }}';
        } else if (currentModerationAction === 'unpublish') {
            url = '{{ route("admin.rapports.unpublish", $rapport) }}';
        } else if (currentModerationAction === 'delete') {
            url = '{{ route("admin.rapports.destroy", $rapport) }}';
            method = 'DELETE';
        }

        // Faire la requête AJAX
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('moderation_comment', comment);
        
        if (method === 'DELETE') {
            formData.append('_method', 'DELETE');
        }

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour l'affichage du commentaire de modération avant de fermer le modal
                updateModerationCommentDisplay(comment);
                
                showNotification(data.message || 'Action effectuée avec succès', 'success');
                closeModerationModal();
                
                // Si c'est une suppression, rediriger vers l'index
                if (currentModerationAction === 'delete') {
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.rapports.index") }}';
                    }, 1000);
                } else {
                    // Sinon, recharger la page
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } else {
                showNotification(data.message || 'Une erreur est survenue', 'error');
                
                // Restaurer le bouton
                confirmButton.innerHTML = originalText;
                confirmButton.disabled = false;
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Une erreur est survenue lors de la requête', 'error');
            
            // Restaurer le bouton
            confirmButton.innerHTML = originalText;
            confirmButton.disabled = false;
        });
    }

    function updateModerationCommentDisplay(comment) {
        const commentSection = document.getElementById('moderation-comment-section');
        const commentText = document.getElementById('moderation-comment-text');
        
        if (comment && comment.trim() !== '') {
            // Afficher la section et mettre à jour le texte
            commentSection.classList.remove('hidden');
            commentText.textContent = comment;
        } else {
            // Masquer la section si pas de commentaire
            commentSection.classList.add('hidden');
        }
    }

    function updateCommentPreview() {
        const commentInput = document.getElementById('moderation_comment');
        const preview = document.getElementById('comment-preview');
        const previewText = document.getElementById('comment-preview-text');
        
        const comment = commentInput.value.trim();
        
        if (comment !== '') {
            preview.classList.remove('hidden');
            previewText.textContent = comment;
        } else {
            preview.classList.add('hidden');
        }
    }

    function showNotification(message, type = 'info') {
        // Créer la notification avec le style IRI
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-gradient-to-r from-green-500 to-green-600 text-white' : 
            type === 'error' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white' : 
            'bg-gradient-to-r from-iri-primary to-iri-secondary text-white'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-3"></i>
                <span class="font-semibold">${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animer l'apparition
        setTimeout(() => notification.classList.remove('translate-x-full'), 100);
        
        // Supprimer après 5 secondes
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => document.body.removeChild(notification), 300);
        }, 5000);
    }
</script>
@endsection
