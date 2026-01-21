<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'nom',
        'titre',
        'adresse',
        'ville',
        'province',
        'pays',
        'email',
        'telephone',
        'telephone_secondaire',
        'responsable_nom',
        'responsable_fonction',
        'responsable_email',
        'responsable_telephone',
        'photo',
        'description',
        'horaires',
        'latitude',
        'longitude',
        'ordre',
        'actif'
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'actif' => 'boolean',
        'ordre' => 'integer'
    ];

    /**
     * Scopes
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre')->orderBy('nom');
    }

    public function scopeBureauPrincipal($query)
    {
        return $query->where('type', 'bureau_principal');
    }

    public function scopeBureauxRegionaux($query)
    {
        return $query->where('type', 'bureau_regional');
    }

    // Points focaux intégrés dans les bureaux régionaux via champs responsable_*
    // public function scopePointsFocaux($query)
    // {
    //     return $query->where('type', 'point_focal');
    // }

    /**
     * Accessors
     */
    public function getTypeLibelleAttribute()
    {
        $types = [
            'bureau_principal' => 'Bureau Principal',
            'bureau_regional' => 'Bureau Régional / Bureau de Liaison',
            // 'point_focal' => 'Point Focal', // Intégré dans bureaux régionaux
            'autre' => 'Autre'
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function getAdresseCompleteAttribute()
    {
        $parts = array_filter([
            $this->adresse,
            $this->ville,
            $this->province,
            $this->pays
        ]);

        return implode(', ', $parts);
    }

    /**
     * Obtenir les coordonnées géographiques
     */
    public function hasCoordinates()
    {
        return !empty($this->latitude) && !empty($this->longitude);
    }
}
