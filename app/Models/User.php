<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
        ];
    }

    /**
     * Vérifier si l'utilisateur est super administrateur
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super admin');
    }

    /**
     * Vérifier si l'utilisateur peut modérer le contenu
     */
    public function canModerate(): bool
    {
        // Super admin a tous les droits
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        // Utiliser Spatie Laravel Permission pour vérifier les permissions de modération
        return $this->hasAnyPermission([
            'moderate publications',
            'moderate actualites', 
            'moderate evenements',
            'moderate services',
            'moderate_partenaires',
            'moderate_projet'
        ]) || $this->hasAnyRole(['super-admin', 'admin', 'moderator', 'gestionnaire_projets']);
    }

    /**
     * Vérifier si l'utilisateur peut accéder à l'interface admin
     */
    public function canAccessAdmin(): bool
    {
        // Super admin a accès complet
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        return $this->hasPermissionTo('view_admin_dashboard') ||
               $this->hasAnyRole(['super-admin', 'admin', 'moderator', 'editor', 'contributor', 'gestionnaire_projets']);
    }

    /**
     * Vérifier si l'utilisateur peut gérer les utilisateurs
     */
    public function canManageUsers(): bool
    {
        // Super admin a tous les droits
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        return $this->hasPermissionTo('manage users') ||
               $this->hasAnyRole(['super-admin', 'admin']);
    }

    /**
     * Permissions pour les projets
     */
    public function canViewProjets(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('view_projets') ||
               $this->hasAnyRole(['super-admin', 'admin', 'gestionnaire_projets']);
    }

    public function canCreateProjets(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('create_projet') ||
               $this->hasAnyRole(['super-admin', 'admin', 'gestionnaire_projets']);
    }

    public function canUpdateProjets(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('update_projet') ||
               $this->hasAnyRole(['super-admin', 'admin', 'gestionnaire_projets']);
    }

    public function canDeleteProjets(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('delete_projet') ||
               $this->hasAnyRole(['super-admin', 'admin', 'gestionnaire_projets']);
    }

    public function canModerateProjets(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('moderate_projet') ||
               $this->hasAnyRole(['super-admin', 'admin', 'gestionnaire_projets']);
    }

    /**
     * Permissions pour les actualités
     */
    public function canViewActualites(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('view actualites') ||
               $this->hasAnyRole(['super-admin', 'admin', 'moderator', 'editor', 'contributor', 'gestionnaire_projets']);
    }

    public function canCreateActualites(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('create actualites') ||
               $this->hasAnyRole(['super-admin', 'admin', 'moderator', 'editor', 'contributor', 'gestionnaire_projets']);
    }

    public function canUpdateActualites(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('update actualites') ||
               $this->hasAnyRole(['super-admin', 'admin', 'moderator', 'gestionnaire_projets']);
    }

    public function canDeleteActualites(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('delete actualites') ||
               $this->hasAnyRole(['super-admin', 'admin', 'moderator', 'gestionnaire_projets']);
    }

    /**
     * Permissions pour les services
     */
    public function canViewServices(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('view services') ||
               $this->hasAnyRole(['super-admin', 'admin', 'moderator', 'editor', 'contributor']);
    }

    public function canCreateServices(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('create services') ||
               $this->hasAnyRole(['super-admin', 'admin', 'moderator', 'editor', 'contributor']);
    }

    public function canUpdateServices(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('update services') ||
               $this->hasAnyRole(['super-admin', 'admin', 'moderator']);
    }

    public function canDeleteServices(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('delete services') ||
               $this->hasAnyRole(['super-admin', 'admin', 'moderator']);
    }

    /**
     * Permissions pour les partenaires
     */
    public function canViewPartenaires(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('view_partenaires') ||
               $this->hasAnyRole(['super-admin', 'admin', 'moderator', 'editor', 'contributor']);
    }

    public function canCreatePartenaires(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('create_partenaires') ||
               $this->hasAnyRole(['super-admin', 'admin', 'moderator']);
    }

    public function canUpdatePartenaires(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('update_partenaires') ||
               $this->hasAnyRole(['super-admin', 'admin', 'moderator']);
    }

    public function canDeletePartenaires(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('delete_partenaires') ||
               $this->hasAnyRole(['super-admin', 'admin']);
    }

    public function canModeratePartenaires(): bool
    {
        if ($this->isSuperAdmin()) return true;
        
        return $this->hasPermissionTo('moderate_partenaires') ||
               $this->hasAnyRole(['super-admin', 'admin', 'moderator']);
    }

    /**
     * Send the password reset notification using our custom template.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\CustomResetPasswordNotification($token));
    }
}
