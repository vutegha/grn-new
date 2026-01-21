<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasModeration;
use App\Models\User;

class Service extends Model
{
    use HasFactory, HasModeration;

    protected $fillable = [
        'nom', 'nom_menu', 'resume', 'description', 'image',
        'is_published', 'show_in_menu', 'published_at', 'published_by', 'moderation_comment'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'show_in_menu' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->slug = now()->format('Ymd') . '-' .Str::slug($model->nom ?? $model->titre);
        });
    }
    public function projets()
    {
        return $this->hasMany(Projet::class);
    }

    public function actualites()
    {
        return $this->hasMany(Actualite::class);
    }

    /**
     * Relation avec l'utilisateur qui a publié le service
     */
    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * Scope pour récupérer uniquement les services publiés
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope pour récupérer les services affichés dans le menu
     */
    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true)->where('is_published', true);
    }

    /**
     * Obtenir le nom d'affichage pour le menu
     * Si nom_menu est vide, utiliser le nom principal
     */
    public function getMenuDisplayNameAttribute()
    {
        return !empty(trim($this->nom_menu)) ? trim($this->nom_menu) : $this->nom;
    }

    /**
     * Activer/Désactiver l'affichage dans le menu
     */
    public function toggleMenu()
    {
        $this->update(['show_in_menu' => !$this->show_in_menu]);
        return $this;
    }

    /**
     * Afficher dans le menu
     */
    public function showInMenu()
    {
        $this->update(['show_in_menu' => true]);
        return $this;
    }

    /**
     * Masquer du menu
     */
    public function hideFromMenu()
    {
        $this->update(['show_in_menu' => false]);
        return $this;
    }
    
    /**
     * Obtenir l'URL de l'image ou une image par défaut
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/service-placeholder.php');
        }

        // Le chemin est stocké comme : services/images/filename.ext
        // Laravel Storage utilise storage_path('app/public/') comme base
        if ($this->hasValidImage()) {
            // Utiliser Storage::url() qui gère automatiquement les liens symboliques
            return \Illuminate\Support\Facades\Storage::url($this->image);
        }

        return asset('images/service-placeholder.php');
    }

    /**
     * Vérifier si l'image existe
     */
    public function hasValidImage()
    {
        if (!$this->image) {
            return false;
        }

        // Utiliser Storage facade pour vérifier l'existence
        return \Illuminate\Support\Facades\Storage::disk('public')->exists($this->image);
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
}


