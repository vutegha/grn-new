<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'type', 'description', 'logo', 'site_web', 'email_contact',
        'telephone', 'adresse', 'pays', 'statut', 'date_debut_partenariat',
        'date_fin_partenariat', 'message_specifique', 'domaines_collaboration',
        'ordre_affichage', 'afficher_publiquement'
    ];

    protected $casts = [
        'domaines_collaboration' => 'array',
        'date_debut_partenariat' => 'date',
        'date_fin_partenariat' => 'date',
        'afficher_publiquement' => 'boolean',
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

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdonnes($query)
    {
        return $query->orderBy('ordre_affichage')->orderBy('nom');
    }

    // Accessors
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return asset('assets/img/default-partner-logo.png');
    }

    public function getTypeLibelleAttribute()
    {
        $types = [
            'universite' => 'Université',
            'centre_recherche' => 'Centre de Recherche',
            'organisation_internationale' => 'Organisation Internationale',
            'ong' => 'ONG',
            'entreprise' => 'Entreprise',
            'autre' => 'Autre'
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function getStatutLibelleAttribute()
    {
        $statuts = [
            'actif' => 'Actif',
            'inactif' => 'Inactif',
            'en_negociation' => 'En négociation'
        ];

        return $statuts[$this->statut] ?? $this->statut;
    }
}
