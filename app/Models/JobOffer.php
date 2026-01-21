<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class JobOffer extends Model
{
    use HasFactory;

    // Constantes pour les statuts
    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';
    const STATUS_PAUSED = 'paused';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CLOSED = 'closed';
    
    // Constantes pour les sources
    const SOURCE_INTERNAL = 'internal';
    const SOURCE_PARTNER = 'partner';
    const SOURCE_EXTERNAL = 'external';
    
    // Constantes pour les types
    const TYPE_FULL_TIME = 'full-time';
    const TYPE_PART_TIME = 'part-time';
    const TYPE_CONTRACT = 'contract';
    const TYPE_INTERNSHIP = 'internship';
    const TYPE_FREELANCE = 'freelance';

    protected $fillable = [
        'title', 'slug', 'description', 'type', 'location', 'department',
        'source', 'partner_name', 'partner_logo', 'status', 'application_deadline',
        'requirements', 'criteria', 'benefits', 'salary_min', 'salary_max',
        'salary_negotiable', 'positions_available', 'contact_email',
        'contact_phone', 'document_appel_offre', 'document_appel_offre_nom',
        'is_featured'
    ];

    // Champs protégés contre mass assignment
    protected $guarded = [
        'views_count', 'applications_count', 'id', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'requirements' => 'array',
        'criteria' => 'array',
        'application_deadline' => 'date',
        'salary_negotiable' => 'boolean',
        'is_featured' => 'boolean',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    // Relations
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->where(function($q) {
                        $q->whereNull('application_deadline')
                          ->orWhere('application_deadline', '>=', now());
                    });
    }

    public function scopeExpired($query)
    {
        return $query->where(function($q) {
            $q->where('status', self::STATUS_EXPIRED)
              ->orWhere(function($subQuery) {
                  $subQuery->where('status', self::STATUS_ACTIVE)
                          ->whereNotNull('application_deadline')
                          ->where('application_deadline', '<', now());
              });
        });
    }

    public function scopeCanBeAppliedTo($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->where(function($q) {
                        $q->whereNull('application_deadline')
                          ->orWhere('application_deadline', '>=', now());
                    });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBySource($query, $source)
    {
        // Validation de la source
        $validSources = [self::SOURCE_INTERNAL, self::SOURCE_PARTNER, self::SOURCE_EXTERNAL];
        if (!in_array($source, $validSources)) {
            throw new \InvalidArgumentException("Source invalide: $source");
        }
        
        return $query->where('source', $source);
    }

    public function scopeByStatus($query, $status)
    {
        // Validation du statut
        $validStatuses = [
            self::STATUS_DRAFT, self::STATUS_ACTIVE, self::STATUS_PAUSED, 
            self::STATUS_EXPIRED, self::STATUS_CLOSED
        ];
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException("Statut invalide: $status");
        }
        
        return $query->where('status', $status);
    }

    // Accessors
    public function getIsExpiredAttribute()
    {
        return $this->application_deadline && $this->application_deadline->isPast();
    }

    public function getFormattedSalaryAttribute()
    {
        if ($this->salary_min && $this->salary_max) {
            return number_format($this->salary_min) . ' - ' . number_format($this->salary_max) . ' USD';
        } elseif ($this->salary_min) {
            return 'À partir de ' . number_format($this->salary_min) . ' USD';
        } elseif ($this->salary_negotiable) {
            return 'Salaire négociable';
        }
        return 'Non spécifié';
    }

    public function getDaysUntilDeadlineAttribute()
    {
        if (!$this->application_deadline) {
            return null;
        }
        return now()->diffInDays($this->application_deadline, false);
    }

    // Accesseur pour compatibilité avec la vue
    public function getDeadlineAttribute()
    {
        return $this->application_deadline;
    }

    // Nouvel accesseur pour le libellé du statut
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_ACTIVE => '✅ Active',
            self::STATUS_DRAFT => '📝 Brouillon',
            self::STATUS_PAUSED => '⏸️ En pause',
            self::STATUS_EXPIRED => '⏰ Expirée',
            self::STATUS_CLOSED => '🔒 Fermée',
            default => $this->status
        };
    }

    // Accesseur pour le libellé de la source
    public function getSourceLabelAttribute()
    {
        return match($this->source) {
            self::SOURCE_INTERNAL => '🏢 Interne',
            self::SOURCE_PARTNER => '🤝 Partenaire',
            self::SOURCE_EXTERNAL => '🌐 Externe',
            default => $this->source
        };
    }

    // Accesseur sécurisé pour le document d'appel d'offre
    public function getDocumentAppelOffreUrlAttribute()
    {
        if (!$this->document_appel_offre) {
            return null;
        }
        
        // Validation du chemin pour éviter path traversal
        if (!Storage::disk('public')->exists($this->document_appel_offre)) {
            return null;
        }
        
        return Storage::disk('public')->url($this->document_appel_offre);
    }

    public function hasDocumentAppelOffre()
    {
        return !empty($this->document_appel_offre);
    }

    // Méthodes helper pour les fichiers
    public function getDocumentAppelOffreSize()
    {
        if (!$this->document_appel_offre || !Storage::disk('public')->exists($this->document_appel_offre)) {
            return 0;
        }
        return Storage::disk('public')->size($this->document_appel_offre);
    }

    public function getDocumentAppelOffreSizeFormatted()
    {
        $size = $this->getDocumentAppelOffreSize();
        if ($size == 0) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 1) . ' ' . $units[$power];
    }

    public function getDocumentAppelOffreExtension()
    {
        if (!$this->document_appel_offre) return null;
        return strtoupper(pathinfo($this->document_appel_offre, PATHINFO_EXTENSION));
    }

    public function getPartnerLogoSize()
    {
        if (!$this->partner_logo || !Storage::disk('public')->exists($this->partner_logo)) {
            return 0;
        }
        return Storage::disk('public')->size($this->partner_logo);
    }

    public function getPartnerLogoSizeFormatted()
    {
        $size = $this->getPartnerLogoSize();
        if ($size == 0) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 1) . ' ' . $units[$power];
    }

    public function hasPartnerLogo()
    {
        return !empty($this->partner_logo);
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementApplications()
    {
        $this->increment('applications_count');
    }

    public function markAsExpired()
    {
        $this->update(['status' => self::STATUS_EXPIRED]);
    }

    // Nouvelles méthodes utilitaires
    public function canBeAppliedTo(): bool
    {
        return $this->status === self::STATUS_ACTIVE && 
               (!$this->application_deadline || $this->application_deadline->isFuture());
    }

    public function shouldBeExpired(): bool
    {
        return $this->status === self::STATUS_ACTIVE &&
               $this->application_deadline &&
               $this->application_deadline->isPast();
    }

    public function autoExpireIfNeeded(): bool
    {
        if ($this->shouldBeExpired()) {
            $this->update(['status' => self::STATUS_EXPIRED]);
            return true;
        }
        return false;
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isPaused(): bool
    {
        return $this->status === self::STATUS_PAUSED;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    // Génération automatique du slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jobOffer) {
            if (empty($jobOffer->slug)) {
                $jobOffer->slug = $jobOffer->generateUniqueSlug($jobOffer->title);
            }
        });

        static::updating(function ($jobOffer) {
            if ($jobOffer->isDirty('title') && empty($jobOffer->slug)) {
                $jobOffer->slug = $jobOffer->generateUniqueSlug($jobOffer->title);
            }
        });
    }

    /**
     * Générer un slug unique pour l'offre d'emploi
     */
    private function generateUniqueSlug($title)
    {
        $baseSlug = \Illuminate\Support\Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Trouver une offre d'emploi par son slug
     */
    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->firstOrFail();
    }

    /**
     * Obtenir l'URL de l'offre d'emploi
     */
    public function getUrlAttribute()
    {
        return route('admin.job-offers.show', $this->slug);
    }

    /**
     * Spécifier que les routes doivent utiliser le slug au lieu de l'ID
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Règles de validation pour le modèle
     */
    public static function validationRules($id = null)
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:job_offers,slug,' . $id,
            'description' => 'required|string|min:50',
            'status' => 'required|in:' . implode(',', [
                self::STATUS_DRAFT, self::STATUS_ACTIVE, self::STATUS_PAUSED,
                self::STATUS_EXPIRED, self::STATUS_CLOSED
            ]),
            'source' => 'required|in:' . implode(',', [
                self::SOURCE_INTERNAL, self::SOURCE_PARTNER, self::SOURCE_EXTERNAL
            ]),
            'type' => 'required|in:' . implode(',', [
                self::TYPE_FULL_TIME, self::TYPE_PART_TIME, self::TYPE_CONTRACT,
                self::TYPE_INTERNSHIP, self::TYPE_FREELANCE
            ]),
            'location' => 'required|string|max:255',
            'application_deadline' => 'nullable|date|after:today',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'positions_available' => 'required|integer|min:1|max:100',
            'contact_email' => 'required|email|max:255',
            'requirements' => 'required|array|min:1',
            'requirements.*' => 'required|string|max:500',
            'criteria' => 'nullable|array',
            'criteria.*.type' => 'nullable|string|in:select,radio,textarea,text',
            'criteria.*.question' => 'nullable|string|max:255',
            'criteria.*.required' => 'nullable|boolean',
            'partner_name' => 'required_if:source,' . self::SOURCE_PARTNER . '|nullable|string|max:255',
            'partner_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'document_appel_offre' => 'nullable|file|mimes:pdf,doc,docx,odt|max:10240',
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    public static function validationMessages()
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description est obligatoire.',
            'description.min' => 'La description doit contenir au moins 50 caractères.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
            'source.required' => 'La source est obligatoire.',
            'source.in' => 'La source sélectionnée n\'est pas valide.',
            'application_deadline.after' => 'La date limite doit être dans le futur.',
            'salary_max.gte' => 'Le salaire maximum doit être supérieur ou égal au salaire minimum.',
            'positions_available.min' => 'Au moins un poste doit être disponible.',
            'positions_available.max' => 'Le nombre de postes ne peut pas dépasser 100.',
            'contact_email.required' => 'L\'email de contact est obligatoire.',
            'contact_email.email' => 'L\'email de contact doit être valide.',
            'requirements.required' => 'Au moins une exigence est requise.',
            'requirements.*.required' => 'Chaque exigence doit être renseignée.',
            'partner_name.required_if' => 'Le nom du partenaire est obligatoire pour les offres partenaires.',
            'partner_logo.image' => 'Le logo doit être une image.',
            'partner_logo.mimes' => 'Le logo doit être au format jpeg, png, jpg, gif ou svg.',
            'partner_logo.max' => 'Le logo ne peut pas dépasser 2MB.',
            'document_appel_offre.mimes' => 'Le document doit être au format pdf, doc, docx ou odt.',
            'document_appel_offre.max' => 'Le document ne peut pas dépasser 10MB.',
        ];
    }

    /**
     * Valider une instance du modèle
     */
    public function validate(array $data = null)
    {
        $data = $data ?? $this->toArray();
        return validator($data, self::validationRules($this->id ?? null), self::validationMessages());
    }

    /**
     * Méthodes statiques pour obtenir les constantes
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_DRAFT => 'Brouillon',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_PAUSED => 'En pause',
            self::STATUS_EXPIRED => 'Expirée',
            self::STATUS_CLOSED => 'Fermée',
        ];
    }

    public static function getSources()
    {
        return [
            self::SOURCE_INTERNAL => 'Interne',
            self::SOURCE_PARTNER => 'Partenaire',
            self::SOURCE_EXTERNAL => 'Externe',
        ];
    }

    public static function getTypes()
    {
        return [
            self::TYPE_FULL_TIME => 'Temps plein',
            self::TYPE_PART_TIME => 'Temps partiel',
            self::TYPE_CONTRACT => 'Contrat',
            self::TYPE_INTERNSHIP => 'Stage',
            self::TYPE_FREELANCE => 'Freelance',
        ];
    }
}
