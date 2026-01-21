<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'name',
        'url',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope pour récupérer seulement les liens actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour ordonner par ordre défini
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Obtient l'icône appropriée basée sur la plateforme
     */
    public function getIconAttribute()
    {
        $platformIcons = [
            'facebook' => 'fab fa-facebook-f',
            'twitter' => 'fab fa-twitter', 
            'x' => 'fab fa-x-twitter',
            'instagram' => 'fab fa-instagram',
            'linkedin' => 'fab fa-linkedin-in',
            'youtube' => 'fab fa-youtube',
            'tiktok' => 'fab fa-tiktok',
            'whatsapp' => 'fab fa-whatsapp',
            'telegram' => 'fab fa-telegram',
            'snapchat' => 'fab fa-snapchat',
            'pinterest' => 'fab fa-pinterest',
            'reddit' => 'fab fa-reddit',
            'discord' => 'fab fa-discord',
            'github' => 'fab fa-github',
            'email' => 'fas fa-envelope',
            'website' => 'fas fa-globe',
            'blog' => 'fas fa-blog',
        ];

        $platform = strtolower($this->platform);
        return $platformIcons[$platform] ?? 'fas fa-link';
    }

    /**
     * Obtient la couleur appropriée basée sur la plateforme
     */
    public function getColorAttribute()
    {
        $platformColors = [
            'facebook' => 'bg-blue-600',
            'twitter' => 'bg-blue-400',
            'x' => 'bg-black',
            'instagram' => 'bg-pink-500',
            'linkedin' => 'bg-blue-700',
            'youtube' => 'bg-red-600',
            'tiktok' => 'bg-black',
            'whatsapp' => 'bg-green-500',
            'telegram' => 'bg-blue-500',
            'snapchat' => 'bg-yellow-400',
            'pinterest' => 'bg-red-500',
            'reddit' => 'bg-orange-600',
            'discord' => 'bg-indigo-600',
            'github' => 'bg-gray-800',
            'email' => 'bg-gray-600',
            'website' => 'bg-iri-primary',
            'blog' => 'bg-iri-secondary',
        ];

        $platform = strtolower($this->platform);
        return $platformColors[$platform] ?? 'bg-gray-500';
    }
}
