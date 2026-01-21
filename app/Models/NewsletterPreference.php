<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'newsletter_id',
        'type',
        'actif'
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    /**
     * Types de préférences disponibles
     */
    const TYPES = [
        'actualites' => 'Actualités',
        'publications' => 'Publications',
        'rapports' => 'Rapports',
        'projets' => 'Projets',
        'evenements' => 'Événements'
    ];

    /**
     * Relation avec Newsletter
     */
    public function newsletter()
    {
        return $this->belongsTo(Newsletter::class);
    }

    /**
     * Scope pour les préférences actives
     */
    public function scopeActive($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope par type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
