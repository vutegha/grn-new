<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contact extends Model
{
    protected $fillable = [
        'nom',
        'email', 
        'sujet',
        'message',
        'statut',
        'lu_a',
        'traite_a',
        'reponse'
    ];

    protected $casts = [
        'lu_a' => 'datetime',
        'traite_a' => 'datetime',
    ];

    // Scopes pour filtrer par statut
    public function scopeNouveaux($query)
    {
        return $query->where('statut', 'nouveau');
    }

    public function scopeLus($query)
    {
        return $query->where('statut', 'lu');
    }

    public function scopeTraites($query)
    {
        return $query->where('statut', 'traite');
    }

    // Marquer comme lu
    public function marquerCommeLu()
    {
        $this->update([
            'statut' => 'lu',
            'lu_a' => Carbon::now()
        ]);
    }

    // Marquer comme traité
    public function marquerCommeTraite($reponse = null)
    {
        $this->update([
            'statut' => 'traite',
            'traite_a' => Carbon::now(),
            'reponse' => $reponse
        ]);
    }
}
