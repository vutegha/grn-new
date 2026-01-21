<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'country_code',
        'country_name',
        'city',
        'region',
        'latitude',
        'longitude',
        'visit_count',
        'first_visit',
        'last_visit'
    ];

    protected $casts = [
        'first_visit' => 'datetime',
        'last_visit' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Obtenir les pays les plus visités
     */
    public static function getTopCountries($limit = 5)
    {
        return self::selectRaw('country_name, country_code, SUM(visit_count) as total_visits')
            ->whereNotNull('country_name')
            ->groupBy('country_name', 'country_code')
            ->orderByDesc('total_visits')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les villes les plus visitées
     */
    public static function getTopCities($limit = 5)
    {
        return self::selectRaw('city, country_name, SUM(visit_count) as total_visits')
            ->whereNotNull('city')
            ->groupBy('city', 'country_name')
            ->orderByDesc('total_visits')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir le nombre total de visiteurs uniques
     */
    public static function getTotalUniqueVisitors()
    {
        return self::count();
    }

    /**
     * Obtenir les nouveaux visiteurs de ce mois
     */
    public static function getNewVisitorsThisMonth()
    {
        return self::whereMonth('first_visit', now()->month)
            ->whereYear('first_visit', now()->year)
            ->count();
    }
}
