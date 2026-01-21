<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasModeration;
use App\Traits\NotifiesNewsletterSubscribers;
use App\Services\WordPressContentProcessor;

class Actualite extends Model
{
    use HasFactory, HasModeration, NotifiesNewsletterSubscribers;

    protected $fillable = [
        'titre', 'resume', 'texte', 'image', 'en_vedette', 'a_la_une', 'service_id',
        'categorie_id', 'is_published', 'published_at', 'published_by', 'moderation_comment', 'slug',
        'user_id'
    ];

    protected $casts = [
        'en_vedette' => 'boolean',
        'a_la_une' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    protected static function booted()
    {
        static::creating(function ($model) {
            // Vérifier que le titre existe
            if (empty($model->titre)) {
                throw new \InvalidArgumentException('Le titre est requis pour créer une actualité.');
            }
            
            // Générer le slug unique
            $model->slug = $model->generateUniqueSlug($model->titre);
        });
        
        static::updating(function ($model) {
            // Régénérer le slug si le titre a changé et qu'il n'y a pas déjà un slug
            if ($model->isDirty('titre') && !empty($model->titre) && empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug($model->titre);
            }
        });
    }

    /**
     * Générer un slug unique pour l'actualité
     */
    public function generateUniqueSlug($titre)
    {
        $baseSlug = now()->format('Ymd') . '-' . Str::slug($titre);
        $slug = $baseSlug;
        $counter = 1;
        
        // Vérifier si le slug existe déjà (en excluant l'actualité actuelle si c'est une mise à jour)
        while (static::where('slug', $slug)
                     ->when($this->exists, function ($query) {
                         return $query->where('id', '!=', $this->id);
                     })
                     ->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
            
            // Sécurité pour éviter une boucle infinie
            if ($counter > 999) {
                $slug = $baseSlug . '-' . uniqid();
                break;
            }
        }
        
        return $slug;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Obtenir la date de création formatée de manière sécurisée
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y à H:i') : 'Date non disponible';
    }

    /**
     * Obtenir la date de modification formatée de manière sécurisée
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('d/m/Y à H:i') : 'Date non disponible';
    }

    /**
     * Obtenir la date de création relative de manière sécurisée
     */
    public function getCreatedAtForHumansAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans() : 'Date inconnue';
    }

    /**
     * Obtient l'URL de l'image de façon sécurisée
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        // Vérifier si l'image existe dans le storage
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->image)) {
            return \Illuminate\Support\Facades\Storage::url($this->image);
        }
        
        return null;
    }

        /**
     * Obtient l'URL de l'image sociale pour les partages
     */
    public function getSocialImageUrlAttribute()
    {
        $baseUrl = request()->getSchemeAndHttpHost();
        
        // 1. Image de couverture principale
        if ($this->image_url) {
            $imageUrl = $this->image_url;
            // Ajouter le domaine si l'URL est relative
            if (!str_starts_with($imageUrl, 'http')) {
                $imageUrl = $baseUrl . ($imageUrl[0] === '/' ? '' : '/') . $imageUrl;
            }
            return $imageUrl;
        }

        // 2. Image par défaut pour le partage social
        $defaultImage = config('share.default_image', 'images/default-share.jpg');
        
        // Vérifier si l'image par défaut existe dans public/
        $publicPath = public_path($defaultImage);
        if (file_exists($publicPath)) {
            return $baseUrl . '/' . ltrim($defaultImage, '/');
        }
        
        // 3. Fallback vers le storage public
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($defaultImage)) {
            $storageUrl = \Illuminate\Support\Facades\Storage::url($defaultImage);
            return str_starts_with($storageUrl, 'http') ? $storageUrl : $baseUrl . $storageUrl;
        }

        // 4. Créer une image placeholder dynamique si aucune image n'est trouvée
        $placeholderUrl = route('actualite.placeholder.image', [
            'title' => Str::limit($this->titre, 50),
            'width' => 1200,
            'height' => 630
        ]);
        
        return $placeholderUrl;
    }

    /**
     * Obtient le texte alternatif pour l'image sociale
     */
    public function getSocialImageAltAttribute()
    {
        return $this->titre;
    }

    /**
     * Obtient l'URL canonique de l'actualité
     */
    public function getCanonicalUrlAttribute()
    {
        return \App\Support\UrlHelper::canonicalUrl($this);
    }

    /**
     * Obtient l'excerpt/résumé pour les métadonnées sociales
     */
    public function getSocialDescriptionAttribute()
    {
        if ($this->resume) {
            return strip_tags($this->resume);
        }

        // Fallback : extraire du début du texte
        $text = strip_tags($this->texte);
        return \Illuminate\Support\Str::limit($text, 160);
    }

    /**
     * Vérifie si l'actualité a une image disponible
     */
    public function hasImage()
    {
        return !empty($this->image_url);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec les rapports associés à cette actualité
     */
    public function rapports()
    {
        return $this->belongsToMany(Rapport::class, 'actualite_rapport', 'actualite_id', 'rapport_id')
                    ->withTimestamps()
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Vérifie si cette actualité a des rapports associés
     */
    public function hasRapports()
    {
        return $this->rapports()->count() > 0;
    }

    /**
     * Obtient les rapports publiés associés à cette actualité
     */
    public function getPublishedRapportsAttribute()
    {
        return $this->rapports()->where('is_published', true)->get();
    }

    /**
     * Détermine le type de contenu pour la newsletter
     */
    public function getNewsletterContentType(): ?string
    {
        return 'actualites';
    }

    /**
     * Obtient le contenu traité avec améliorations d'affichage
     */
    public function getProcessedContentAttribute()
    {
        $content = $this->texte ?? '';
        
        if (empty($content)) {
            return $content;
        }

        // Traiter le contenu avec notre processeur
        $processedContent = WordPressContentProcessor::processContent($content);
        
        // Optimiser les images
        $processedContent = WordPressContentProcessor::optimizeImagesForPerformance($processedContent);
        
        // Convertir les URLs WordPress si nécessaire
        $processedContent = WordPressContentProcessor::convertWordPressImageUrls($processedContent);
        
        return $processedContent;
    }

    /**
     * Obtient le contenu avec préservation de la mise en cache
     */
    public function getCachedProcessedContent()
    {
        $cacheKey = 'actualite_processed_content_' . $this->id . '_' . $this->updated_at->timestamp;
        
        return cache()->remember($cacheKey, 3600, function () {
            return $this->processed_content;
        });
    }

    /**
     * Obtient le contenu WordPress nettoyé et stylisé (conserve les URLs externes)
     * Idéal pour afficher du contenu WordPress importé avec images externes
     */
    public function getContenuNettoyeAttribute()
    {
        return get_cleaned_wordpress_content($this->texte);
    }

    /**
     * Obtient le résumé WordPress nettoyé et stylisé
     */
    public function getResumeNettoyeAttribute()
    {
        return get_cleaned_wordpress_content($this->resume);
    }

}




