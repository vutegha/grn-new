@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="fas fa-chevron-right text-iri-gray/50 mx-2"></i>
        <a href="{{ route('admin.publication.index') }}" 
           class="text-iri-gray hover:text-iri-primary transition-colors duration-200 font-medium">
            Publications
        </a>
    </li>
    <li aria-current="page">
        <div class="flex items-center">
            <i class="fas fa-chevron-right mx-2 text-iri-gray/50"></i>
            <span class="text-iri-primary font-medium">{{ Str::limit($publication->titre, 30) }}</span>
        </div>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- En-tête de la publication -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-primary to-iri-secondary">
                    <h1 class="text-2xl font-bold text-white">{{ $publication->titre }}</h1>
                </div>
                <div class="p-6">
                    @if($publication->image)
                    <div class="mb-6">
                        <img src="{{ Storage::url($publication->image) }}" 
                             alt="{{ $publication->titre }}" 
                             class="w-full h-64 object-cover rounded-lg border border-gray-200">
                    </div>
                    @endif
                    
                    <div class="prose max-w-none">
                        <div class="space-y-4">
                            <div class="text-sm text-iri-gray flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                Publié le {{ $publication->created_at->format('d/m/Y à H:i') }}
                            </div>
                            
                            @if($publication->updated_at && $publication->updated_at != $publication->created_at)
                            <div class="text-sm text-iri-gray flex items-center">
                                <i class="fas fa-edit mr-2"></i>
                                Modifié le {{ $publication->updated_at->format('d/m/Y à H:i') }}
                            </div>
                            @endif
                        </div>
                        
                        <div class="mt-6 prose prose-lg max-w-none text-iri-dark leading-relaxed">
                            {!! nl2br(e($publication->contenu)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mots-clés -->
            @if($publication->mots_cles)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-tags mr-3"></i>
                        Mots-clés
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', $publication->mots_cles) as $motCle)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-iri-primary/10 text-iri-primary border border-iri-primary/20">
                                <i class="fas fa-tag mr-1 text-xs"></i>
                                {{ trim($motCle) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Auteurs -->
            @if($publication->auteurs && $publication->auteurs->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-users mr-3"></i>
                        Auteurs ({{ $publication->auteurs->count() }})
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($publication->auteurs as $auteur)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg border">
                                <div class="w-10 h-10 bg-gradient-to-br from-iri-primary to-iri-secondary rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-iri-dark">{{ $auteur->nom }}</p>
                                    @if($auteur->email)
                                        <p class="text-sm text-iri-gray">{{ $auteur->email }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Lecteur PDF simplifié -->
            @if($publication->fichier_pdf)
            @php
                $fileUrl = Storage::url($publication->fichier_pdf);
                $extension = pathinfo($publication->fichier_pdf, PATHINFO_EXTENSION);
            @endphp
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-file-pdf mr-3"></i>
                        Document PDF
                    </h2>
                </div>
                
                @if ($extension === 'pdf')
                <div class="p-6">
                    <!-- Actions rapides -->
                    <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                        <div class="flex items-center space-x-4">
                            <div class="bg-red-50 p-2 rounded-lg">
                                <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ basename($publication->fichier_pdf) }}</h3>
                                <p class="text-sm text-gray-600">Document PDF</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <a href="{{ $fileUrl }}" target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-all">
                                <i class="fas fa-external-link-alt mr-2"></i>Nouvel onglet
                            </a>
                            <a href="{{ $fileUrl }}" download 
                               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">
                                <i class="fas fa-download mr-2"></i>Télécharger
                            </a>
                        </div>
                    </div>
                    
                    <!-- Affichage PDF avec iframe -->
                    <div class="relative rounded-lg overflow-hidden border border-gray-200">
                        <iframe src="{{ $fileUrl }}" 
                                class="w-full h-96 md:h-[600px]" 
                                frameborder="0">
                            <div class="flex flex-col items-center justify-center h-full bg-gray-50 text-gray-600 p-8">
                                <i class="fas fa-file-pdf text-4xl mb-4 text-red-500"></i>
                                <p class="text-lg font-medium mb-2">Aperçu PDF non disponible</p>
                                <p class="text-sm mb-4">Votre navigateur ne supporte pas l'affichage des PDF.</p>
                                <a href="{{ $fileUrl }}" target="_blank" 
                                   class="inline-flex items-center px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors">
                                    <i class="fas fa-external-link-alt mr-2"></i>Ouvrir le PDF
                                </a>
                            </div>
                        </iframe>
                    </div>
                </div>
                @else
                <div class="p-6">
                    <div class="text-center">
                        <i class="fas fa-file text-iri-gray text-4xl mb-4"></i>
                        <p class="text-iri-gray mb-4">Document non-PDF disponible</p>
                        <a href="{{ $fileUrl }}" target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-iri-primary to-iri-secondary text-white rounded-lg hover:from-iri-secondary hover:to-iri-primary transition-all duration-200">
                            <i class="fas fa-download mr-2"></i>Télécharger le fichier
                        </a>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
            <!-- Statut et métadonnées -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-eye mr-3"></i>
                        Statut & Métadonnées
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Statut de publication -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Statut</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                              {{ $publication->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            <i class="fas {{ $publication->is_published ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                            {{ $publication->is_published ? 'Publié' : 'Brouillon' }}
                        </span>
                    </div>

                    <!-- Date de création -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Créé le</span>
                        <span class="text-sm text-gray-600">{{ $publication->created_at->format('d/m/Y') }}</span>
                    </div>

                    <!-- Dernière modification -->
                    @if($publication->updated_at && $publication->updated_at != $publication->created_at)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Modifié le</span>
                        <span class="text-sm text-gray-600">{{ $publication->updated_at->format('d/m/Y') }}</span>
                    </div>
                    @endif

                    <!-- Catégorie -->
                    @if($publication->category)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Catégorie</span>
                        <span class="text-sm text-gray-600">{{ $publication->category->nom ?? 'Non définie' }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions de modération -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-iri-secondary to-iri-primary">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-gavel mr-3"></i>
                        Actions
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    @if(!$publication->is_published)
                    <button onclick="moderatePublication('publish', {{ $publication->id }})" 
                            class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-check mr-2"></i>Approuver et Publier
                    </button>
                    @else
                    <button onclick="moderatePublication('unpublish', {{ $publication->id }})" 
                            class="w-full flex items-center justify-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-pause mr-2"></i>Dépublier
                    </button>
                    @endif

                    <a href="{{ route('admin.publication.edit', $publication) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>

                    <form action="{{ route('admin.publication.destroy', $publication) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
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
            <div class="mt-2 px-2 py-3">
                <p id="modal-description" class="text-sm text-gray-500 mb-4"></p>
                <div class="mb-4">
                    <label for="moderation_comment" class="block text-sm font-medium text-gray-700 mb-2">
                        Commentaire (optionnel)
                    </label>
                    <textarea id="moderation_comment" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary"
                              placeholder="Ajoutez un commentaire sur cette action..."></textarea>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-2">
                <button onclick="closeModerationModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    Annuler
                </button>
                <button onclick="confirmModerationAction()" 
                        class="px-4 py-2 bg-iri-primary text-white rounded-lg hover:bg-iri-secondary transition-colors">
                    Confirmer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Styles pour l'iframe PDF -->
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
</style>

<!-- Script pour les actions de modération -->
<script>
    let currentModerationAction = null;
    let currentPublicationId = null;

    function moderatePublication(action, publicationId) {
        currentModerationAction = action;
        currentPublicationId = publicationId;
        
        const modal = document.getElementById('moderationModal');
        const title = document.getElementById('modal-title');
        const description = document.getElementById('modal-description');
        const commentField = document.getElementById('moderation_comment');
        
        // Configurer le modal selon l'action
        if (action === 'publish') {
            title.textContent = 'Approuver la Publication';
            description.textContent = 'Êtes-vous sûr de vouloir approuver et publier cette publication ?';
        } else if (action === 'unpublish') {
            title.textContent = 'Dépublier la Publication';
            description.textContent = 'Êtes-vous sûr de vouloir dépublier cette publication ?';
        }
        
        // Réinitialiser le commentaire
        commentField.value = '';
        
        // Afficher le modal
        modal.classList.remove('hidden');
    }

    function closeModerationModal() {
        const modal = document.getElementById('moderationModal');
        modal.classList.add('hidden');
        currentModerationAction = null;
        currentPublicationId = null;
    }

    function confirmModerationAction() {
        if (!currentModerationAction || !currentPublicationId) {
            return;
        }

        const comment = document.getElementById('moderation_comment').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Afficher un indicateur de chargement
        const confirmButton = event.target;
        const originalText = confirmButton.innerHTML;
        confirmButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Traitement...';
        confirmButton.disabled = true;

        // Simulation d'une requête AJAX (ajustez selon vos routes)
        setTimeout(() => {
            showNotification('Action de modération effectuée avec succès', 'success');
            closeModerationModal();
            
            // Restaurer le bouton
            confirmButton.innerHTML = originalText;
            confirmButton.disabled = false;
        }, 1000);
    }

    function showNotification(message, type = 'info') {
        // Créer la notification
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
        
        if (type === 'success') {
            notification.classList.add('bg-green-500', 'text-white');
            notification.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${message}`;
        } else if (type === 'error') {
            notification.classList.add('bg-red-500', 'text-white');
            notification.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i>${message}`;
        } else {
            notification.classList.add('bg-blue-500', 'text-white');
            notification.innerHTML = `<i class="fas fa-info-circle mr-2"></i>${message}`;
        }
        
        document.body.appendChild(notification);
        
        // Animer l'entrée
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Supprimer après 5 secondes
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }

    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('moderationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModerationModal();
        }
    });

    // Fermer le modal avec la touche Échap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModerationModal();
        }
    });
</script>

@endsection
