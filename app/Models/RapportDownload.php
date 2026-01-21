<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportDownload extends Model
{
    protected $fillable = [
        'rapport_id',
        'actualite_id',
        'email',
        'ip_address',
        'user_agent',
        'downloaded_at',
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    /**
     * Relation avec le rapport téléchargé
     */
    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }

    /**
     * Relation avec l'actualité depuis laquelle le téléchargement a été fait
     */
    public function actualite(): BelongsTo
    {
        return $this->belongsTo(Actualite::class);
    }

    /**
     * Enregistrer un nouveau téléchargement
     */
    public static function recordDownload(int $rapportId, string $email, ?int $actualiteId = null): self
    {
        return self::create([
            'rapport_id' => $rapportId,
            'actualite_id' => $actualiteId,
            'email' => $email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'downloaded_at' => now(),
        ]);
    }
}
