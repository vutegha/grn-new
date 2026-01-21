<?php

namespace App\Policies;

use App\Models\Partenaire;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PartenairePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_partenaires', 'web') ||
               $user->hasAnyRole(['super-admin', 'admin', 'moderator', 'editor', 'contributor']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Partenaire $partenaire): bool
    {
        return $user->hasPermissionTo('view_partenaires', 'web') ||
               $user->hasAnyRole(['super-admin', 'admin', 'moderator', 'editor', 'contributor']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_partenaire', 'web') ||
               $user->hasAnyRole(['super-admin', 'admin', 'moderator']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Partenaire $partenaire): bool
    {
        // Super admin peut tout modifier
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Vérifier les permissions générales
        return $user->hasPermissionTo('update_partenaire', 'web') ||
               $user->hasAnyRole(['super-admin', 'admin', 'moderator']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Partenaire $partenaire): bool
    {
        // Super admin peut tout supprimer
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Seuls les admins et super-admins peuvent supprimer
        return $user->hasPermissionTo('delete_partenaire', 'web') ||
               $user->hasAnyRole(['super-admin', 'admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Partenaire $partenaire): bool
    {
        return $user->isSuperAdmin() || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Partenaire $partenaire): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can moderate partners (publish/unpublish, change visibility).
     */
    public function moderate(User $user, Partenaire $partenaire): bool
    {
        return $user->hasPermissionTo('moderate_partenaire', 'web') ||
               $user->hasAnyRole(['super-admin', 'admin', 'moderator']);
    }

    /**
     * Determine whether the user can manage partner logos.
     */
    public function manageLogo(User $user, Partenaire $partenaire): bool
    {
        return $user->hasPermissionTo('update_partenaire', 'web') ||
               $user->hasAnyRole(['super-admin', 'admin', 'moderator']);
    }

    /**
     * Determine whether the user can change partner visibility.
     */
    public function changeVisibility(User $user, Partenaire $partenaire): bool
    {
        return $user->hasPermissionTo('moderate_partenaire', 'web') ||
               $user->hasAnyRole(['super-admin', 'admin', 'moderator']);
    }
}
