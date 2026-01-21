<?php

namespace App\Policies;

use App\Models\Projet;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any projets.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view_projets');
    }

    /**
     * Determine whether the user can view the projet.
     * Les projets publiés sont accessibles à tous
     */
    public function view(?User $user, Projet $projet)
    {
        // Si le projet est publié, tout le monde peut le voir
        if ($projet->is_published) {
            return true;
        }
        
        // Sinon, vérifier les permissions (admin uniquement)
        if (!$user) {
            return false;
        }
        
        return $user->hasPermissionTo('view_projet');
    }

    /**
     * Determine whether the user can create projets.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_projet');
    }

    /**
     * Determine whether the user can update the projet.
     */
    public function update(User $user, Projet $projet)
    {
        return $user->hasPermissionTo('update_projet');
    }

    /**
     * Determine whether the user can delete the projet.
     */
    public function delete(User $user, Projet $projet)
    {
        return $user->hasPermissionTo('delete_projet');
    }

    /**
     * Determine whether the user can moderate the projet.
     */
    public function moderate(User $user, Projet $projet)
    {
        return $user->hasPermissionTo('moderate_projet');
    }

    /**
     * Determine whether the user can publish the projet.
     */
    public function publish(User $user, Projet $projet)
    {
        return $user->hasPermissionTo('publish_projet');
    }

    /**
     * Determine whether the user can unpublish the projet.
     */
    public function unpublish(User $user, Projet $projet)
    {
        return $user->hasPermissionTo('unpublish_projet');
    }

    /**
     * Determine whether the user can restore the projet.
     */
    public function restore(User $user, Projet $projet)
    {
        return $user->hasPermissionTo('update_projet');
    }

    /**
     * Determine whether the user can permanently delete the projet.
     */
    public function forceDelete(User $user, Projet $projet)
    {
        return $user->hasPermissionTo('delete_projet');
    }
}