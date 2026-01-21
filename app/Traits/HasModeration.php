<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Notifications\ContentPublished;
use Illuminate\Support\Facades\Notification;

trait HasModeration
{
    /**
     * Boot le trait
     */
    protected static function bootHasModeration()
    {
        // Quand un élément est créé, il n'est pas publié par défaut
        static::creating(function ($model) {
            if (!isset($model->attributes['is_published'])) {
                $model->is_published = false;
            }
        });

        // Quand un élément est publié, notifier les utilisateurs habilités
        static::updated(function ($model) {
            if ($model->wasChanged('is_published') && $model->is_published) {
                $model->sendPublicationNotification();
            }
        });
    }

    /**
     * Relation avec l'utilisateur qui a publié
     */
    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * Scope pour récupérer uniquement les éléments publiés
     */
    public function scopePublished(Builder $query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope pour récupérer les éléments en attente de modération
     */
    public function scopePendingModeration(Builder $query)
    {
        return $query->where('is_published', false);
    }

    /**
     * Publier l'élément
     */
    public function publish($user = null, $comment = null)
    {
        $this->update([
            'is_published' => true,
            'published_at' => now(),
            'published_by' => $user ? $user->id : auth()->id(),
            'moderation_comment' => $comment,
        ]);

        return $this;
    }

    /**
     * Dépublier l'élément
     */
    public function unpublish($comment = null)
    {
        $this->update([
            'is_published' => false,
            'published_at' => null,
            'published_by' => null,
            'moderation_comment' => $comment,
        ]);

        return $this;
    }

    /**
     * Vérifier si l'élément est publié
     */
    public function isPublished()
    {
        return $this->is_published;
    }

    /**
     * Vérifier si l'élément est en attente de modération
     */
    public function isPendingModeration()
    {
        return !$this->is_published;
    }

    /**
     * Envoyer une notification de publication
     */
    protected function sendPublicationNotification()
    {
        // Pour l'instant, on désactive les notifications de modération
        // car le système de rôles n'est pas encore implémenté
        
        // TODO: Implémenter le système de notifications quand les rôles seront créés
        /*
        // Récupérer tous les utilisateurs ayant le droit de censurer
        $moderators = User::whereHas('roles', function ($query) {
            $query->where('name', 'moderator')
                  ->orWhere('name', 'admin')
                  ->orWhere('name', 'super-admin');
        })->get();

        if ($moderators->isNotEmpty()) {
            Notification::send($moderators, new ContentPublished($this));
        }
        */
    }

    /**
     * Accesseur pour le statut de publication
     */
    public function getPublicationStatusAttribute()
    {
        return $this->is_published ? 'Publié' : 'En attente';
    }

    /**
     * Accesseur pour la couleur du badge de statut
     */
    public function getStatusBadgeColorAttribute()
    {
        return $this->is_published ? 'success' : 'warning';
    }
}
