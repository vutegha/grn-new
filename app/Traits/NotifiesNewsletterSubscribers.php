<?php

namespace App\Traits;

use App\Services\NewsletterService;
use Illuminate\Support\Facades\Log;

trait NotifiesNewsletterSubscribers
{
    /**
     * Boot du trait pour écouter les événements du modèle
     */
    public static function bootNotifiesNewsletterSubscribers()
    {
        // Notification lors de la création d'un nouveau contenu publié
        static::created(function ($model) {
            if ($model->isPublishedForNewsletter()) {
                $model->notifyNewsletterSubscribers();
            }
        });

        // Notification lors de la mise à jour si le statut passe à publié
        static::updated(function ($model) {
            if ($model->wasRecentlyPublishedForNewsletter()) {
                $model->notifyNewsletterSubscribers();
            }
        });
    }

    /**
     * Vérifie si le contenu est publié (compatible avec HasModeration)
     */
    public function isPublishedForNewsletter(): bool
    {
        // Si le modèle utilise HasModeration, utiliser sa méthode isPublished
        if (in_array('App\Traits\HasModeration', class_uses_recursive($this))) {
            return $this->isPublished();
        }
        
        // Sinon, vérifier différents champs possibles pour le statut de publication
        if (isset($this->is_published) && $this->is_published) {
            return true;
        }
        
        if (isset($this->statut) && $this->statut === 'publie') {
            return true;
        }
        
        if (isset($this->status) && $this->status === 'published') {
            return true;
        }
        
        if (isset($this->published_at) && $this->published_at && $this->published_at <= now()) {
            return true;
        }
        
        return false;
    }

    /**
     * Vérifie si le contenu vient d'être publié
     */
    public function wasRecentlyPublishedForNewsletter(): bool
    {
        if (!$this->isPublishedForNewsletter()) {
            return false;
        }

        // Vérifier si le statut de publication a changé
        if ($this->isDirty('is_published') && $this->is_published) {
            return true;
        }
        
        if ($this->isDirty('statut') && $this->statut === 'publie') {
            return true;
        }
        
        if ($this->isDirty('status') && $this->status === 'published') {
            return true;
        }
        
        if ($this->isDirty('published_at') && $this->published_at && $this->published_at <= now()) {
            return true;
        }
        
        return false;
    }

    /**
     * Notifie les abonnés de la newsletter
     */
    public function notifyNewsletterSubscribers()
    {
        try {
            $newsletterService = app(NewsletterService::class);
            $contentType = $this->getNewsletterContentType();
            
            if ($contentType) {
                $result = $newsletterService->notifySubscribersOfPublication($this, $contentType);
                
                Log::info('Notification newsletter envoyée', [
                    'model' => get_class($this),
                    'id' => $this->id,
                    'content_type' => $contentType,
                    'result' => $result
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erreur notification newsletter', [
                'model' => get_class($this),
                'id' => $this->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Détermine le type de contenu pour la newsletter
     * Cette méthode doit être implémentée dans chaque modèle qui utilise ce trait
     */
    abstract public function getNewsletterContentType(): ?string;
}
