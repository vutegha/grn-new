<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use App\Traits\HasModeration;

class Evenement extends Model
{
    use HasFactory, HasModeration;

    protected $fillable = [
        'titre',
        'resume',
        'description',
        'slug',
        'image',
        'date_evenement',
        'lieu',
        'organisateur',
        'contact_email',
        'contact_telephone',
        'rapport_url',
        'type',
        'en_vedette',
        'a_la_une',
        'meta_data',
        'is_published',
        'published_at',
        'published_by'
    ];

    protected $casts = [
        'date_evenement' => 'datetime',
        'en_vedette' => 'boolean',
        'a_la_une' => 'boolean',
        'meta_data' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime'
    ];

    // Scopes
    public function scopeAVenir($query)
    {
        return $query->where('date_evenement', '>', now());
    }

    public function scopePasse($query)
    {
        return $query->where('date_evenement', '<', now());
    }

    public function scopeEnCours($query)
    {
        return $query->where('date_evenement', '=', now()->toDateString());
    }

    public function scopeEnVedette($query)
    {
        return $query->where('en_vedette', true);
    }

    public function scopeALaUne($query)
    {
        return $query->where('a_la_une', true);
    }

    // Accesseurs
    public function getEstAVenirAttribute()
    {
        return $this->date_evenement && $this->date_evenement > now();
    }

    public function getEstEnCoursAttribute()
    {
        return $this->date_evenement && $this->date_evenement->isToday();
    }

    public function getEstPasseAttribute()
    {
        return $this->date_evenement && $this->date_evenement->isPast();
    }

    public function getStatutAttribute()
    {
        if ($this->est_passe) {
            return 'passé';
        } elseif ($this->est_en_cours) {
            return 'en cours';
        } else {
            return 'à venir';
        }
    }

    // Créer le slug automatiquement si nécessaire
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($evenement) {
            if (empty($evenement->slug)) {
                $evenement->slug = \Str::slug($evenement->titre);
            }
        });

        static::updating(function ($evenement) {
            if ($evenement->isDirty('titre') && empty($evenement->slug)) {
                $evenement->slug = \Str::slug($evenement->titre);
            }
        });
    }

    // Utiliser le slug pour la résolution des routes
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
