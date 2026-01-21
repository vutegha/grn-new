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
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->hasPermissionTo('view actualites', 'web') || 
               $user->hasPermissionTo('viewAny', 'web') ||
               $user->hasAnyRole(['admin', 'moderator', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Actualite $actualite): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->hasPermissionTo('view actualites', 'web') || 
               $user->hasPermissionTo('view', 'web') ||
               $user->hasAnyRole(['admin', 'moderator', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->hasPermissionTo('create actualites', 'web') || 
               $user->hasPermissionTo('create', 'web') ||
               $user->hasAnyRole(['admin', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Actualite $actualite): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        // L'utilisateur peut modifier ses propres actualités ou s'il a les permissions admin
        return $user->hasPermissionTo('update actualites', 'web') || 
               $user->hasPermissionTo('update', 'web') ||
               $user->hasAnyRole(['admin'], 'web') ||
               ($actualite->auteur_id === $user->id && $user->hasRole('contributeur', 'web'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Actualite $actualite): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        // L'utilisateur peut supprimer ses propres actualités ou s'il a les permissions admin
        return $user->hasPermissionTo('delete actualites', 'web') || 
               $user->hasPermissionTo('delete', 'web') ||
               $user->hasAnyRole(['admin'], 'web') ||
               ($actualite->auteur_id === $user->id && $user->hasRole('contributeur', 'web'));
    }

    /**
     * Determine whether the user can moderate the model.
     */
    public function moderate(User $user, ?Actualite $actualite = null): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->hasPermissionTo('moderate actualites', 'web') || 
               $user->hasPermissionTo('moderate', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Actualite $actualite): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->hasPermissionTo('publish actualites', 'web') || 
               $user->hasPermissionTo('publish', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }

    /**
     * Determine whether the user can unpublish the model.
     */
    public function unpublish(User $user, Actualite $actualite): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->hasPermissionTo('unpublish actualites', 'web') || 
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }
}
