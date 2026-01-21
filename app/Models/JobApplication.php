<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    // Constantes pour les statuts
    const STATUS_PENDING = 'pending';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_SHORTLISTED = 'shortlisted';
    const STATUS_INTERVIEWED = 'interviewed';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'job_offer_id', 'first_name', 'last_name', 'email', 'phone',
        'address', 'birth_date', 'gender', 'nationality', 'education',
        'experience', 'skills', 'motivation_letter', 'criteria_responses',
        'cv_path', 'portfolio_path', 'additional_documents'
    ];

    protected $guarded = [
        'status', 'admin_notes', 'reviewed_at', 'reviewed_by', 'score'
    ];

    protected $casts = [
        'criteria_responses' => 'array',
        'additional_documents' => 'array',
        'birth_date' => 'date',
        'reviewed_at' => 'datetime',
        'score' => 'decimal:2',
    ];

    // Relations
    public function jobOffer()
    {
        return $this->belongsTo(JobOffer::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    public function scopeShortlisted($query)
    {
        return $query->where('status', 'shortlisted');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getStatusLabelAttribute()
    {
        $config = self::getStatusConfig();
        return $config[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $config = self::getStatusConfig();
        $color = $config[$this->status]['color'] ?? 'gray';
        return "text-{$color}-600 bg-{$color}-100";
    }

    public function getStatusIconAttribute()
    {
        $config = self::getStatusConfig();
        return $config[$this->status]['icon'] ?? 'fa-question';
    }

    public function getStatusConfigAttribute()
    {
        $config = self::getStatusConfig();
        return $config[$this->status] ?? [
            'label' => $this->status,
            'color' => 'gray',
            'icon' => 'fa-question'
        ];
    }

    // Methods
    /**
     * Méthode générique pour mettre à jour le statut
     */
    public function updateStatus($status, $reviewerId = null, $notes = null, $score = null)
    {
        if (!in_array($status, self::getValidStatuses())) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }
        
        $this->update([
            'status' => $status,
            'reviewed_at' => now(),
            'reviewed_by' => $reviewerId,
            'admin_notes' => $notes,
            'score' => $score
        ]);
        
        return $this;
    }

    /**
     * Obtenir tous les statuts valides
     */
    public static function getValidStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_REVIEWED,
            self::STATUS_SHORTLISTED,
            self::STATUS_INTERVIEWED,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED
        ];
    }

    /**
     * Obtenir la configuration des statuts pour l'affichage
     */
    public static function getStatusConfig(): array
    {
        return [
            self::STATUS_PENDING => [
                'label' => 'En attente',
                'color' => 'yellow',
                'icon' => 'fa-clock'
            ],
            self::STATUS_REVIEWED => [
                'label' => 'Révisée',
                'color' => 'blue',
                'icon' => 'fa-eye'
            ],
            self::STATUS_SHORTLISTED => [
                'label' => 'Présélectionnée',
                'color' => 'purple',
                'icon' => 'fa-star'
            ],
            self::STATUS_INTERVIEWED => [
                'label' => 'Entretien passé',
                'color' => 'indigo',
                'icon' => 'fa-comments'
            ],
            self::STATUS_ACCEPTED => [
                'label' => 'Acceptée',
                'color' => 'green',
                'icon' => 'fa-check-circle'
            ],
            self::STATUS_REJECTED => [
                'label' => 'Rejetée',
                'color' => 'red',
                'icon' => 'fa-times-circle'
            ]
        ];
    }

    // Méthodes de compatibilité (deprecated)
    public function markAsReviewed($reviewerId = null, $notes = null)
    {
        return $this->updateStatus(self::STATUS_REVIEWED, $reviewerId, $notes);
    }

    public function shortlist($reviewerId = null, $notes = null)
    {
        return $this->updateStatus(self::STATUS_SHORTLISTED, $reviewerId, $notes);
    }

    public function accept($reviewerId = null, $notes = null)
    {
        return $this->updateStatus(self::STATUS_ACCEPTED, $reviewerId, $notes);
    }

    public function reject($reviewerId = null, $notes = null)
    {
        return $this->updateStatus(self::STATUS_REJECTED, $reviewerId, $notes);
    }
}
