<template>
    <div class="moderation-controls">
        <!-- Bouton Publier/Dépublier -->
        <button 
            @click="togglePublication"
            :disabled="isLoading"
            :class="buttonClass"
            class="btn btn-sm me-2">
            <i :class="iconClass" class="me-1"></i>
            {{ buttonText }}
        </button>

        <!-- Statut actuel -->
        <span :class="statusBadgeClass" class="badge">
            {{ statusText }}
        </span>

        <!-- Modal pour commentaire de modération -->
        <div v-if="showCommentModal" class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ modalTitle }}</h5>
                        <button @click="closeModal" type="button" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Commentaire de modération (optionnel)</label>
                            <textarea 
                                v-model="moderationComment" 
                                class="form-control" 
                                rows="3" 
                                placeholder="Ajouter un commentaire pour justifier cette action...">
                            </textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="closeModal" type="button" class="btn btn-secondary">Annuler</button>
                        <button @click="confirmAction" type="button" class="btn btn-primary" :disabled="isLoading">
                            <span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>
                            Confirmer
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showCommentModal" class="modal-backdrop fade show"></div>
    </div>
</template>

<script>
export default {
    name: 'ModerationControl',
    props: {
        itemId: {
            type: [String, Number],
            required: true
        },
        itemType: {
            type: String,
            required: true // 'actualite', 'publication', 'projet', 'service', 'rapport'
        },
        isPublished: {
            type: Boolean,
            default: false
        },
        publishedAt: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            published: this.isPublished,
            isLoading: false,
            showCommentModal: false,
            moderationComment: '',
            pendingAction: null
        }
    },
    computed: {
        buttonClass() {
            return this.published ? 'btn-warning' : 'btn-success';
        },
        iconClass() {
            return this.published ? 'fas fa-eye-slash' : 'fas fa-eye';
        },
        buttonText() {
            return this.published ? 'Dépublier' : 'Publier';
        },
        statusText() {
            return this.published ? 'Publié' : 'En attente';
        },
        statusBadgeClass() {
            return this.published ? 'badge-success' : 'badge-warning';
        },
        modalTitle() {
            return this.pendingAction === 'publish' ? 'Publier le contenu' : 'Dépublier le contenu';
        }
    },
    methods: {
        togglePublication() {
            this.pendingAction = this.published ? 'unpublish' : 'publish';
            this.showCommentModal = true;
        },
        
        async confirmAction() {
            this.isLoading = true;
            
            try {
                const endpoint = `/admin/${this.itemType}/${this.itemId}/${this.pendingAction}`;
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        comment: this.moderationComment
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.published = this.pendingAction === 'publish';
                    this.closeModal();
                    
                    // Notification de succès
                    this.showNotification(data.message, 'success');
                    
                    // Émettre un événement pour le parent
                    this.$emit('status-changed', {
                        published: this.published,
                        message: data.message
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                this.showNotification('Erreur: ' + error.message, 'error');
            } finally {
                this.isLoading = false;
            }
        },
        
        closeModal() {
            this.showCommentModal = false;
            this.moderationComment = '';
            this.pendingAction = null;
        },
        
        showNotification(message, type) {
            // Utiliser le système de notification global ou créer une notification simple
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertHtml = `<div class="alert ${alertClass} alert-dismissible fade show">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            
            // Insérer la notification au début du body ou dans un container spécifique
            const container = document.querySelector('.alerts-container') || document.body;
            container.insertAdjacentHTML('afterbegin', alertHtml);
            
            // Auto-supprimer après 5 secondes
            setTimeout(() => {
                const alert = container.querySelector('.alert');
                if (alert) alert.remove();
            }, 5000);
        }
    }
}
</script>

<style scoped>
.moderation-controls {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.badge-success {
    background-color: #198754;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}

.modal {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
