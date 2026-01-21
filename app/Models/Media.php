<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'medias', // Chemin de stockage du fichier
        'titre',
        'description', // Description détaillée du média
        'type', // Type de média (image, vidéo, etc.)
        'projet_id', // Lien vers le projet associé
        'status', // Statut de modération (pending, approved, rejected, published)
        'is_public', // Visibilité publique
        'moderated_by', // ID de l'utilisateur qui a modéré
        'moderated_at', // Date de modération
        'created_by', // ID de l'utilisateur créateur
        'tags', // Tags pour catégorisation
        'file_size', // Taille du fichier
        'mime_type', // Type MIME
        'alt_text', // Texte alternatif pour accessibilité
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'moderated_at' => 'datetime',
        'tags' => 'array',
    ];

    /**
     * Règles de validation pour le modèle Media
     */
    public static $rules = [
        'type' => 'nullable|string|max:255', // Exemple : image, vidéo, etc.
        'titre' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'medias' => 'required|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,video/mp4,video/quicktime,video/webm|max:40480',
        'projet_id' => 'nullable|exists:projets,id', // Lien vers le projet associé
        'alt_text' => 'nullable|string|max:255',
        'tags' => 'nullable|array',
        'is_public' => 'boolean',
    ];

    // Constantes pour les statuts
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PUBLISHED = 'published';

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_APPROVED => 'Approuvé',
            self::STATUS_REJECTED => 'Rejeté',
            self::STATUS_PUBLISHED => 'Publié',
        ];
    }

        // Optionnel : accès formaté à l'URL publique
    public function getPublicUrlAttribute()
    {
        return asset('storage/' . $this->medias);
    }

    // Optionnel : retour boolean sur le type
    public function isImage()
    {
        return $this->type === 'image';
    }

    public function isVideo()
    {
        return $this->type === 'video';
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isPublished()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->status] ?? 'Inconnu';
    }

    // Scopes pour les requêtes
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)->where('is_public', true);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function getFileExtensionAttribute()
    {
        return pathinfo($this->medias, PATHINFO_EXTENSION);
    }

    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) return 'Inconnue';
        
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Relations
    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }
    
}
