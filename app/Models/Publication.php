<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasModeration;
use App\Traits\NotifiesNewsletterSubscribers;

class Publication extends Model
{
    use HasFactory, HasModeration, NotifiesNewsletterSubscribers;

    protected $fillable = [
        'titre', 'resume', 'fichier_pdf', 'categorie_id', 'citation', 'en_vedette', 'a_la_une',
        'is_published', 'published_at', 'published_by', 'moderation_comment'
    ];

    protected $casts = [
        'en_vedette' => 'boolean',
        'a_la_une' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public static function rules()
    {
        return [
            'titre' => 'required|string|max:255',
            'resume' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:publication,slug',
            'fichier_pdf' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,odt,odp|max:40240',
            'auteurs' => 'required|array',
            'auteurs.*' => 'exists:auteurs,id',
            'categorie_id' => 'required|exists:categories,id',
            'citation' => 'nullable|string|max:255',
            'en_vedette' => 'boolean',
            'a_la_une' => 'boolean',
        ];
    }

    // Relation many-to-many avec Auteur
    public function auteurs()
    {
        return $this->belongsToMany(Auteur::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * Accesseur pour maintenir la compatibilité avec 'title'
     */
    public function getTitleAttribute()
    {
        return $this->titre;
    }

    /**
     * Détermine le type de contenu pour la newsletter
     */
    public function getNewsletterContentType(): ?string
    {
        return 'publications';
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->slug = now()->format('Ymd') . '-' . Str::slug($model->nom ?? $model->titre);
        });
    }

    /**
     * Détermine si cette publication est un brouillon
     */
    public function isDraft()
    {
        return !$this->is_published && (
            !$this->fichier_pdf || 
            Str::startsWith($this->titre, ['Brouillon', 'Draft']) || 
            !$this->resume ||
            !$this->categorie_id
        );
    }

    /**
     * Scope pour les brouillons
     */
    public function scopeDraft($query)
    {
        return $query->where('is_published', false)
                    ->where(function($subQuery) {
                        $subQuery->whereNull('fichier_pdf')
                                 ->orWhere('titre', 'like', 'Brouillon%')
                                 ->orWhere('titre', 'like', 'Draft%')
                                 ->orWhereNull('resume')
                                 ->orWhereNull('categorie_id');
                    });
    }

    /**
     * Obtient l'URL de la miniature du PDF (première page)
     * Note: Cette méthode est désormais obsolète car nous utilisons PDF.js côté client
     * Conservée pour la compatibilité
     */
    public function getThumbnailUrl()
    {
        // Les miniatures sont maintenant générées côté client avec PDF.js
        // Cette méthode retourne null pour forcer l'utilisation de PDF.js
        return null;
    }

    /**
     * Vérifie si une miniature existe pour cette publication
     * Note: Cette méthode est désormais obsolète car nous utilisons PDF.js côté client
     * Conservée pour la compatibilité
     */
    public function hasThumbnail()
    {
        // Les miniatures sont maintenant générées côté client avec PDF.js
        return false;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Obtient l'URL de l'image sociale (pour les publications, on utilise une image par défaut)
     */
    public function getSocialImageUrlAttribute()
    {
        // 1. Image sociale générée (si disponible)
        $socialImagePath = "social/publication/{$this->id}.jpg";
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($socialImagePath)) {
            return \Illuminate\Support\Facades\Storage::url($socialImagePath);
        }

        // 2. Image par défaut pour les publications
        $defaultImage = config('share.default_image');
        if ($defaultImage) {
            return \App\Support\UrlHelper::absolute($defaultImage);
        }

        return null;
    }

    /**
     * Obtient le texte alternatif pour l'image sociale
     */
    public function getSocialImageAltAttribute()
    {
        return $this->titre . ' - Publication GRN';
    }

    /**
     * Obtient l'URL canonique de la publication
     */
    public function getCanonicalUrlAttribute()
    {
        if (!\Illuminate\Support\Facades\Route::has('site.publication.show')) {
            return \App\Support\UrlHelper::absolute('/publications/' . $this->slug);
        }
        
        $url = route('site.publication.show', $this->slug);
        return \App\Support\UrlHelper::absolute($url);
    }

    /**
     * Obtient l'excerpt/résumé pour les métadonnées sociales
     */
    public function getSocialDescriptionAttribute()
    {
        if ($this->resume) {
            return strip_tags($this->resume);
        }

        // Fallback avec citation
        if ($this->citation) {
            return strip_tags($this->citation);
        }

        return 'Publication du Groupement des Ressources Naturelles';
    }

    /**
     * Vérifie si la publication a une image disponible
     */
    public function hasImage()
    {
        return !empty($this->social_image_url);
    }

    /**
     * Obtient le résumé WordPress nettoyé et stylisé
     * Conserve les URLs d'images WordPress externes intactes
     */
    public function getResumeNettoyeAttribute()
    {
        return get_cleaned_wordpress_content($this->resume);
    }

    /**
     * Obtient la citation WordPress nettoyée
     */
    public function getCitationNettoyeeAttribute()
    {
        return get_cleaned_wordpress_content($this->citation);
    }
}


