<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasModeration;
use App\Models\User;

class Projet extends Model
{
    use HasFactory, HasModeration;

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'resume',
        'image',
        'service_id',
        'date_debut',
        'date_fin',
        'etat',
        'beneficiaires_hommes',
        'beneficiaires_femmes',
        'beneficiaires_enfants',
        'beneficiaires_total',
        'budget',
        'is_published',
        'published_at',
        'published_by',
        'moderation_comment'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'beneficiaires_hommes' => 'integer',
        'beneficiaires_femmes' => 'integer',
        'beneficiaires_enfants' => 'integer',
        'beneficiaires_total' => 'integer',
        'budget' => 'decimal:2',
    ];

    /**
     * Mutator pour calculer automatiquement le total des bénéficiaires
     */
    public function setBeneficiairesHommesAttribute($value)
    {
        $this->attributes['beneficiaires_hommes'] = $value;
        $this->calculateBeneficiairesTotal();
    }

    public function setBeneficiairesFemmesAttribute($value)
    {
        $this->attributes['beneficiaires_femmes'] = $value;
        $this->calculateBeneficiairesTotal();
    }

    public function setBeneficiairesEnfantsAttribute($value)
    {
        $this->attributes['beneficiaires_enfants'] = $value;
        $this->calculateBeneficiairesTotal();
    }

    protected function calculateBeneficiairesTotal()
    {
        $hommes = (int) ($this->attributes['beneficiaires_hommes'] ?? 0);
        $femmes = (int) ($this->attributes['beneficiaires_femmes'] ?? 0);
        $enfants = (int) ($this->attributes['beneficiaires_enfants'] ?? 0);
        
        $this->attributes['beneficiaires_total'] = $hommes + $femmes + $enfants;
    }

    /**
     * Règles de validation
     */
    public static $rules = [
        'nom' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5048',
        'service_id' => 'nullable|exists:services,id',
        'resume'=>'nullable| string|max:255',
        'date_debut' => 'nullable|date',
        'date_fin' => 'nullable|date|after_or_equal:date_debut',
        'etat' => 'nullable|in:en cours,terminé,suspendu',
        'budget' => 'nullable|numeric|min:0|max:999999999.99',
        'beneficiaires_hommes' => 'nullable|integer|min:0',
        'beneficiaires_femmes' => 'nullable|integer|min:0',
        'beneficiaires_enfants' => 'nullable|integer|min:0',
        'beneficiaires_total' => 'nullable|integer|min:0',
    ];

    /**
     * Relations
     */

    public function medias()
    {
        return $this->hasMany(Media::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * Relation avec les rapports associés au projet
     */
    public function rapports()
    {
        return $this->belongsToMany(Rapport::class, 'projet_rapport', 'projet_id', 'rapport_id')
                    ->withTimestamps()
                    ->orderBy('date_publication', 'desc');
    }

    /**
     * Obtenir uniquement les rapports publiés
     */
    public function publishedRapports()
    {
        return $this->rapports()->where('is_published', true);
    }

    /**
     * Vérifie si ce projet a des rapports associés
     */
    public function hasRapports()
    {
        return $this->rapports()->count() > 0;
    }

    /**
     * Calcule la durée du projet en mois
     */
    public function getDureeEnMoisAttribute()
    {
        if (!$this->date_debut || !$this->date_fin) {
            return null;
        }

        $debut = $this->date_debut;
        $fin = $this->date_fin;
        
        // Calcul de la différence en mois
        $mois = $debut->diffInMonths($fin);
        
        // Arrondir à 1 décimale pour plus de précision
        $jours = $debut->diffInDays($fin);
        $moisDecimal = round($jours / 30.44, 1); // Utilisation de la moyenne mensuelle
        
        return $moisDecimal;
    }

    /**
     * Retourne la durée formatée en texte
     */
    public function getDureeFormateeAttribute()
    {
        $duree = $this->duree_en_mois;
        
        if ($duree === null) {
            return 'Non définie';
        }
        
        if ($duree < 1) {
            $jours = round($duree * 30.44);
            return $jours . ' jour' . ($jours > 1 ? 's' : '');
        }
        
        if ($duree == 1) {
            return '1 mois';
        }
        
        if ($duree < 12) {
            return number_format($duree, 1) . ' mois';
        }
        
        $annees = floor($duree / 12);
        $moisRestants = $duree % 12;
        
        $texte = $annees . ' an' . ($annees > 1 ? 's' : '');
        if ($moisRestants > 0) {
            $texte .= ' et ' . round($moisRestants, 1) . ' mois';
        }
        
        return $texte;
    }

    /**
     * Méthodes pour les statistiques
     */
    public static function getTotalProjets()
    {
        return self::count();
    }

    public static function getTotalBeneficiaires()
    {
        return self::sum('beneficiaires_total') ?: 0;
    }

    public static function getTotalBeneficiairesHommes()
    {
        return self::sum('beneficiaires_hommes') ?: 0;
    }

    public static function getTotalBeneficiairesFemmes()
    {
        return self::sum('beneficiaires_femmes') ?: 0;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Générer un slug unique pour le projet
     */
    public function generateUniqueSlug($nom)
    {
        // Nettoyer le nom en remplaçant les caractères spéciaux
        $cleanedNom = $this->cleanNameForSlug($nom);
        $baseSlug = now()->format('Ymd') . '-' . Str::slug($cleanedNom);
        $slug = $baseSlug;
        $counter = 1;

        // Vérifier si le slug existe déjà (en excluant le projet actuel si c'est une mise à jour)
        while (static::where('slug', $slug)
                     ->when($this->exists, function ($query) {
                         return $query->where('id', '!=', $this->id);
                     })
                     ->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
            
            // Sécurité : éviter les boucles infinies
            if ($counter > 999) {
                $slug = $baseSlug . '-' . uniqid();
                break;
            }
        }

        return $slug;
    }

    /**
     * Nettoyer le nom pour le slug en remplaçant les caractères spéciaux
     */
    private function cleanNameForSlug($nom)
    {
        // Tableau de remplacement des caractères spéciaux
        $replacements = [
            // Caractères accentués
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O',
            'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
            'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
            'Ç' => 'C', 'ç' => 'c', 'Ñ' => 'N', 'ñ' => 'n',
            'Ÿ' => 'Y', 'ÿ' => 'y',
            
            // Caractères spéciaux en mots
            '&' => 'et',
            '@' => 'at',
            '%' => 'pourcent',
            '+' => 'plus',
            '=' => 'egal',
            '$' => 'dollar',
            '€' => 'euro',
            '£' => 'livre',
            '#' => 'diese',
            
            // Espaces multiples et caractères de ponctuation
            '//' => '-',
            '/' => '-',
            '\\' => '-',
            '|' => '-',
            '*' => '',
            '^' => '',
            '~' => '',
            '`' => '',
            '"' => '',
            "'" => '',
            
            // Nettoyer les espaces
            '  ' => ' ',
            '   ' => ' ',
        ];

        // Appliquer les remplacements
        $cleaned = str_replace(array_keys($replacements), array_values($replacements), $nom);
        
        // Nettoyer les caractères restants non alphanumériques (sauf espaces et tirets)
        $cleaned = preg_replace('/[^\p{L}\p{N}\s\-]/u', '', $cleaned);
        
        // Nettoyer les espaces multiples
        $cleaned = preg_replace('/\s+/', ' ', trim($cleaned));
        
        return $cleaned;
    }

    /**
     * Boot : génération automatique du slug si non défini et calcul automatique du total bénéficiaires
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug($model->nom);
            }
            
            // Calcul automatique du total des bénéficiaires
            $model->beneficiaires_total = (($model->beneficiaires_hommes ?? 0) + 
                                           ($model->beneficiaires_femmes ?? 0) + 
                                           ($model->beneficiaires_enfants ?? 0));
        });

        static::updating(function ($model) {
            // Régénérer le slug si le nom a changé et qu'il n'y a pas déjà un slug personnalisé
            if ($model->isDirty('nom') && !empty($model->nom)) {
                // Ne régénérer que si le slug suit le pattern automatique
                $currentSlug = $model->getOriginal('slug');
                if (empty($currentSlug) || preg_match('/^\d{8}-/', $currentSlug)) {
                    $model->slug = $model->generateUniqueSlug($model->nom);
                }
            }
            
            // Calcul automatique du total des bénéficiaires lors des mises à jour
            $model->beneficiaires_total = (($model->beneficiaires_hommes ?? 0) + 
                                           ($model->beneficiaires_femmes ?? 0) + 
                                           ($model->beneficiaires_enfants ?? 0));
        });
    }
}
