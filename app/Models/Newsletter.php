<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'nom',
        'token',
        'actif',
        'confirme_a',
        'preferences',
        'last_email_sent',
        'emails_sent_count',
        'unsubscribe_reason',
        'unsubscribe_reasons',
        'unsubscribed_at'
    ];

    protected $casts = [
        'actif' => 'boolean',
        'confirme_a' => 'datetime',
        'preferences' => 'array',
        'unsubscribe_reasons' => 'array',
        'last_email_sent' => 'datetime',
        'unsubscribed_at' => 'datetime'
    ];

    /**
     * Boot du modèle pour générer un token unique
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($newsletter) {
            if (empty($newsletter->token)) {
                $newsletter->token = Str::random(64);
            }
            
            // Définir les préférences par défaut
            if (empty($newsletter->preferences)) {
                $newsletter->preferences = [
                    'actualites' => true,
                    'publications' => true,
                    'rapports' => true,
                    'projets' => true,
                    'evenements' => true
                ];
            }
        });
    }

    /**
     * Vérifie si l'abonné a une préférence pour un type donné
     */
    public function hasPreference($type)
    {
        return isset($this->preferences[$type]) && $this->preferences[$type] === true;
    }

    /**
     * Met à jour les préférences
     */
    public function updatePreferences(array $preferences)
    {
        $this->update(['preferences' => $preferences]);
    }

    /**
     * Obtient les abonnés actifs avec une préférence spécifique
     */
    public static function getSubscribersForContent($contentType)
    {
        return self::where('actif', true)
                  ->whereNotNull('confirme_a')
                  ->where(function ($query) use ($contentType) {
                      $query->whereJsonContains('preferences->' . $contentType, true)
                            ->orWhereNull('preferences'); // Inclure ceux sans préférences (par défaut tout)
                  })
                  ->get();
    }

    /**
     * Marque qu'un email a été envoyé
     */
    public function markEmailSent()
    {
        $this->increment('emails_sent_count');
        $this->update(['last_email_sent' => now()]);
    }

    /**
     * Scope pour les abonnés actifs
     */
    public function scopeActive($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour les abonnés confirmés
     */
    public function scopeConfirmed($query)
    {
        return $query->whereNotNull('confirme_a');
    }

    /**
     * Obtient les raisons de désabonnement disponibles
     */
    public static function getUnsubscribeReasons()
    {
        return [
            'too_many_emails' => 'Trop d\'emails',
            'not_relevant' => 'Contenu non pertinent',
            'not_interested' => 'Plus intéressé par nos services',
            'technical_issues' => 'Problème technique',
            'other' => 'Autres'
        ];
    }

    /**
     * Vérifie si l'abonné a sélectionné une raison spécifique
     */
    public function hasUnsubscribeReason($reason)
    {
        return in_array($reason, $this->unsubscribe_reasons ?? []);
    }
}
