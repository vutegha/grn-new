<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'page_title',
        'ip_address',
        'user_agent',
        'referer',
        'viewed_at'
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    /**
     * Obtenir les pages les plus consultées
     */
    public static function getMostViewedPages($limit = 5)
    {
        return self::selectRaw('url, page_title, COUNT(*) as views')
            ->groupBy('url', 'page_title')
            ->orderByDesc('views')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les vues d'aujourd'hui
     */
    public static function getTodayViews()
    {
        return self::whereDate('viewed_at', today())->count();
    }

    /**
     * Obtenir les vues de cette semaine
     */
    public static function getWeekViews()
    {
        return self::whereBetween('viewed_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
    }
}
