<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicationDownload extends Model
{
    use HasFactory;

    protected $fillable = [
        'publication_id',
        'ip_address',
        'user_agent',
        'downloaded_at'
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    /**
     * Relation avec la publication
     */
    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }

    /**
     * Obtenir les publications les plus téléchargées
     */
    public static function getMostDownloadedPublications($limit = 5)
    {
        return self::join('publications', 'publication_downloads.publication_id', '=', 'publications.id')
            ->selectRaw('publications.id, publications.title, COUNT(*) as downloads')
            ->groupBy('publications.id', 'publications.title')
            ->orderByDesc('downloads')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir le nombre total de téléchargements
     */
    public static function getTotalDownloads()
    {
        return self::count();
    }

    /**
     * Obtenir les téléchargements de ce mois
     */
    public static function getMonthDownloads()
    {
        return self::whereMonth('downloaded_at', now()->month)
            ->whereYear('downloaded_at', now()->year)
            ->count();
    }
}
