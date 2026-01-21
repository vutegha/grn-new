<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 
        'prenom', 
        'email',
        'orcid',
        'telephone',
        'institution', 
        'titre_professionnel',
        'biographie', 
        'photo',
        'linkedin',
        'twitter',
        'facebook',
        'instagram',
        'github',
        'researchgate',
        'website',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public static $rules = [
        'nom' => 'required|string|max:255',
        'prenom' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'orcid' => 'nullable|string|regex:/^\d{4}-\d{4}-\d{4}-\d{3}[0-9X]$/|max:19',
        'telephone' => 'nullable|string|max:255',
        'institution' => 'nullable|string|max:255',
        'titre_professionnel' => 'nullable|string|max:255',
        'biographie' => 'nullable|string',
        'photo' => 'nullable|string|max:255',
        'linkedin' => 'nullable|url|max:500',
        'twitter' => 'nullable|url|max:500',
        'facebook' => 'nullable|url|max:500',
        'instagram' => 'nullable|url|max:500',
        'github' => 'nullable|url|max:500',
        'researchgate' => 'nullable|url|max:500',
        'website' => 'nullable|url|max:500',
        'active' => 'boolean',
    ];

    // Relation many-to-many avec Publication
    public function publications()
    {
        return $this->belongsToMany(Publication::class);
    }

    /**
     * Génère un slug pour l'auteur avec nom-prenom-id
     */
    public function getSlug()
    {
        $parts = [];
        
        if ($this->nom) {
            $parts[] = \Illuminate\Support\Str::slug($this->nom);
        }
        
        if ($this->prenom) {
            $parts[] = \Illuminate\Support\Str::slug($this->prenom);
        }
        
        $parts[] = $this->id;
        
        return implode('-', $parts);
    }

    /**
     * Extrait l'ID depuis un slug d'auteur
     */
    public static function getIdFromSlug($slug)
    {
        // L'ID est toujours la dernière partie du slug
        $parts = explode('-', $slug);
        $id = end($parts);
        
        // Vérifier que c'est bien un nombre
        return is_numeric($id) ? (int)$id : null;
    }
}
