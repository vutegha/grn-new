<?php

namespace App\Policies;

use App\Models\Actualite;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ActualitePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_actualites');
    }

    /**
     * Determine whether the user can view the model.
     * Les actualités publiées sont accessibles à tous
     */
    public function view(?User $user, Actualite $actualite): bool
    {
        // Si l'actualité est publiée, tout le monde peut la voir
        if ($actualite->is_published) {
            return true;
        }
        
        // Sinon, vérifier les permissions (admin uniquement)
        if (!$user) {
            return false;
        }
        
        return $user->hasPermissionTo('view_actualite');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_actualite');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Actualite $actualite): bool
    {
        return $user->hasPermissionTo('update_actualite');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Actualite $actualite): bool
    {
        return $user->hasPermissionTo('delete_actualite');
    }

    /**
     * Determine whether the user can moderate the model.
     */
    public function moderate(User $user, ?Actualite $actualite = null): bool
    {
        return $user->hasPermissionTo('moderate_actualite');
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Actualite $actualite): bool
    {
        return $user->hasPermissionTo('publish_actualite');
    }

    /**
     * Determine whether the user can unpublish the model.
     */
    public function unpublish(User $user, Actualite $actualite): bool
    {
        return $user->hasPermissionTo('unpublish_actualite');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Actualite $actualite): bool
    {
        return $user->hasPermissionTo('update_actualite');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Actualite $actualite): bool
    {
        return $user->hasPermissionTo('delete_actualite');
    }
}