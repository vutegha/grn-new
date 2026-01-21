<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'emails',
        'description',
        'active',
        'required'
    ];

    protected $casts = [
        'emails' => 'array',
        'active' => 'boolean',
        'required' => 'boolean'
    ];

    /**
     * Obtenir les emails actifs pour une clé donnée
     */
    public static function getActiveEmails(string $key): array
    {
        $setting = self::where('key', $key)->where('active', true)->first();
        return $setting ? $setting->emails : [];
    }

    /**
     * Mettre à jour les emails pour une clé
     */
    public static function updateEmails(string $key, array $emails): bool
    {
        $setting = self::where('key', $key)->first();
        if ($setting) {
            $setting->update(['emails' => $emails]);
            return true;
        }
        return false;
    }

    /**
     * Scope pour les configurations actives
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope pour les configurations obligatoires
     */
    public function scopeRequired($query)
    {
        return $query->where('required', true);
    }
}
