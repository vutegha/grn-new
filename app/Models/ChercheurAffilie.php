<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChercheurAffilie extends Model
{
    use HasFactory;

    protected $table = 'chercheur_affilies';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'titre_academique',
        'institution_origine',
        'departement',
        'domaine_recherche',
        'specialites',
        'photo',
        'biographie',
        'orcid',
        'google_scholar',
        'researchgate',
        'linkedin',
        'statut',
        'date_affiliation',
        'date_fin_affiliation',
        'publications_collaboratives',
        'projets_collaboration',
        'contributions',
        'afficher_publiquement',
        'ordre_affichage'
    ];

    protected $casts = [
        'domaine_recherche' => 'array',
        'specialites' => 'array',
        'publications_collaboratives' => 'array',
        'projets_collaboration' => 'array',
        'contributions' => 'array',
        'date_affiliation' => 'date',
        'date_fin_affiliation' => 'date',
        'afficher_publiquement' => 'boolean'
    ];

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopePublics($query)
    {
        return $query->where('afficher_publiquement', true);
    }

    public function scopeParDomaine($query, $domaine)
    {
        return $query->whereJsonContains('domaine_recherche', $domaine);
    }

    // Accessors
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return asset('images/default-avatar.png');
    }

    public function getCvUrlAttribute()
    {
        // La table n'a pas de colonne cv_path, on peut retourner null ou une URL par défaut
        return null;
    }

    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getDomainesRecherchePrintAttribute()
    {
        if (is_array($this->domaine_recherche)) {
            return implode(', ', $this->domaine_recherche);
        }
        return $this->domaine_recherche;
    }

    public function getAnneeAffiliationAttribute()
    {
        return $this->date_affiliation ? $this->date_affiliation->year : null;
    }
}
