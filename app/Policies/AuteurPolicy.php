<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Auteur;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuteurPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view_auteurs', 'web') ||
               $user->hasPermissionTo('manage_auteurs', 'web');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Auteur $auteur)
    {
        return $user->hasPermissionTo('view_auteurs', 'web') ||
               $user->hasPermissionTo('manage_auteurs', 'web');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_auteur', 'web') ||
               $user->hasPermissionTo('manage_auteurs', 'web');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Auteur $auteur)
    {
        return $user->hasPermissionTo('update_auteur', 'web') ||
               $user->hasPermissionTo('manage_auteurs', 'web');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Auteur $auteur)
    {
        return $user->hasPermissionTo('delete_auteur', 'web') ||
               $user->hasPermissionTo('manage_auteurs', 'web');
    }

    /**
     * Determine whether the user can export models.
     */
    public function export(User $user)
    {
        return $user->hasPermissionTo('export_auteurs', 'web') ||
               $user->hasPermissionTo('manage_auteurs', 'web');
    }

    /**
     * Determine whether the user can manage models.
     */
    public function manage(User $user)
    {
        return $user->hasPermissionTo('manage_auteurs', 'web');
    }
}
