<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view_users');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model)
    {
        return $user->hasPermissionTo('view_users') || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_user');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model)
    {
        return $user->hasPermissionTo('update_user') || $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model)
    {
        return $user->hasPermissionTo('delete_users') && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can export models.
     */
    public function export(User $user)
    {
        return $user->hasPermissionTo('export_users');
    }

    /**
     * Determine whether the user can manage models.
     */
    public function manage(User $user)
    {
        return $user->hasPermissionTo('manage_users');
    }

    /**
     * Determine whether the user can assign roles.
     */
    public function assignRoles(User $user)
    {
        return $user->hasPermissionTo('assign_roles');
    }

    /**
     * Determine whether the user can manage permissions.
     */
    public function managePermissions(User $user)
    {
        return $user->hasPermissionTo('manage_permissions');
    }
}
