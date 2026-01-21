<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'type', 'description', 'logo', 'site_web', 'email_contact',
        'telephone', 'adresse', 'pays', 'statut'
    ];

    protected $casts = [
        'statut' => 'string',
    ];

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopePublics($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdonnes($query)
    {
        return $query->orderBy('nom');
    }

    // Accessors
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            // Si le logo commence par "assets/", c'est un chemin direct
            if (str_starts_with($this->logo, 'assets/')) {
                return asset($this->logo);
            }
            // Sinon, c'est un fichier dans storage
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
