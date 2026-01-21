<?php

namespace App\Policies;

use App\Models\Evenement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EvenementPolicy
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
        
        return $user->hasPermissionTo('view_evenements', 'web') ||
               $user->hasAnyRole(['admin', 'moderator', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Evenement $evenement): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->hasPermissionTo('view_evenements', 'web') ||
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
        
        return $user->hasPermissionTo('create_evenement', 'web') ||
               $user->hasAnyRole(['admin', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Evenement $evenement): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->hasPermissionTo('update_evenement', 'web') ||
               $user->hasAnyRole(['admin'], 'web');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Evenement $evenement): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->hasPermissionTo('delete_evenement', 'web') ||
               $user->hasAnyRole(['admin'], 'web');
    }

    /**
     * Determine whether the user can moderate the model.
     */
    public function moderate(User $user, ?Evenement $evenement = null): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->hasPermissionTo('moderate_evenement', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Evenement $evenement): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->hasPermissionTo('publish_evenement', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }



                }