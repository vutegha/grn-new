<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasModeration;

class Rapport extends Model
{
    use HasFactory, HasModeration;

    protected $fillable = [
        'titre', 'slug', 'description', 'fichier', 'date_publication', 'categorie_id',
        'is_published', 'published_at', 'published_by', 'moderation_comment'
    ];

    protected $casts = [
        'date_publication' => 'date',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * Relation avec les actualités qui référencent ce rapport
     */
    public function actualites()
    {
        return $this->belongsToMany(Actualite::class, 'actualite_rapport', 'rapport_id', 'actualite_id')
                    ->withTimestamps()
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Relation avec les projets associés à ce rapport
     */
    public function projets()
    {
        return $this->belongsToMany(Projet::class, 'projet_rapport', 'rapport_id', 'projet_id')
                    ->withTimestamps()
                    ->orderBy('date_debut', 'desc');
    }

    /**
     * Vérifie si ce rapport est associé à des actualités
     */
    public function hasActualites()
    {
        return $this->actualites()->count() > 0;
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = now()->format('Ymd') . '-' . Str::slug($model->titre);
            }
        });
        
        static::updating(function ($model) {
            if ($model->isDirty('titre') && empty($model->getOriginal('slug'))) {
                $model->slug = now()->format('Ymd') . '-' . Str::slug($model->titre);
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Obtient le type de fichier basé sur l'extension
     */
    public function getFileType()
    {
        if (!$this->fichier) {
            return null;
        }

        $extension = strtolower(pathinfo($this->fichier, PATHINFO_EXTENSION));
        
        $types = [
            'pdf' => 'PDF',
            'doc' => 'Word',
            'docx' => 'Word',
            'xls' => 'Excel',
            'xlsx' => 'Excel',
            'ppt' => 'PowerPoint',
            'pptx' => 'PowerPoint',
            'txt' => 'Texte',
            'rtf' => 'RTF',
        ];

        return $types[$extension] ?? 'Document';
    }

    /**
     * Vérifie si le fichier est un PDF
     */
    public function isPdf()
    {
        if (!$this->fichier) {
            return false;
        }

        $extension = strtolower(pathinfo($this->fichier, PATHINFO_EXTENSION));
        return $extension === 'pdf';
    }

    /**
     * Obtient l'icône CSS appropriée pour le type de fichier
     */
    public function getFileIcon()
    {
        if (!$this->fichier) {
            return 'fas fa-file';
        }

        $extension = strtolower(pathinfo($this->fichier, PATHINFO_EXTENSION));
        
        $icons = [
            'pdf' => 'fas fa-file-pdf text-red-500',
            'doc' => 'fas fa-file-word text-blue-600',
            'docx' => 'fas fa-file-word text-blue-600',
            'xls' => 'fas fa-file-excel text-green-600',
            'xlsx' => 'fas fa-file-excel text-green-600',
            'ppt' => 'fas fa-file-powerpoint text-orange-500',
            'pptx' => 'fas fa-file-powerpoint text-orange-500',
            'txt' => 'fas fa-file-alt text-gray-600',
            'rtf' => 'fas fa-file-alt text-gray-600',
        ];

        return $icons[$extension] ?? 'fas fa-file text-gray-500';
    }

    /**
     * Obtient la couleur d'arrière-plan pour le type de fichier
     */
    public function getFileColor()
    {
        if (!$this->fichier) {
            return 'bg-gray-100';
        }

        $extension = strtolower(pathinfo($this->fichier, PATHINFO_EXTENSION));
        
        $colors = [
            'pdf' => 'bg-red-50 border-red-200',
            'doc' => 'bg-blue-50 border-blue-200',
            'docx' => 'bg-blue-50 border-blue-200',
            'xls' => 'bg-green-50 border-green-200',
            'xlsx' => 'bg-green-50 border-green-200',
            'ppt' => 'bg-orange-50 border-orange-200',
            'pptx' => 'bg-orange-50 border-orange-200',
            'txt' => 'bg-gray-50 border-gray-200',
            'rtf' => 'bg-gray-50 border-gray-200',
        ];

        return $colors[$extension] ?? 'bg-gray-50 border-gray-200';
    }

    /**
     * Obtient la taille du fichier formatée
     */
    public function getFileSize()
    {
        if (!$this->fichier) {
            return null;
        }

        $filePath = storage_path('app/public/' . $this->fichier);
        
        if (!file_exists($filePath)) {
            return null;
        }

        $bytes = filesize($filePath);
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Détermine si le document peut avoir une miniature (PDF uniquement)
     */
    public function canHaveThumbnail()
    {
        return $this->isPdf();
    }

    /**
     * Obtient l'URL de téléchargement du fichier
     */
    public function getDownloadUrl()
    {
        if (!$this->fichier) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::url($this->fichier);
    }
}


