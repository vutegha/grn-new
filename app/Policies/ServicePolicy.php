<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any services.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view_services');
    }

    /**
     * Determine whether the user can view the service.
     */
    public function view(User $user, Service $service)
    {
        return $user->hasPermissionTo('view_service');
    }

    /**
     * Determine whether the user can create services.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_service');
    }

    /**
     * Determine whether the user can update the service.
     */
    public function update(User $user, Service $service)
    {
        return $user->hasPermissionTo('update_service');
    }

    /**
     * Determine whether the user can delete the service.
     */
    public function delete(User $user, Service $service)
    {
        return $user->hasPermissionTo('delete_service');
    }

    /**
     * Determine whether the user can moderate the service.
     */
    public function moderate(User $user, Service $service)
    {
        return $user->hasPermissionTo('moderate_service');
    }

    /**
     * Determine whether the user can restore the service.
     */
    public function restore(User $user, Service $service)
    {
        return $user->hasPermissionTo('update_service');
    }

    /**
     * Determine whether the user can permanently delete the service.
     */
    public function forceDelete(User $user, Service $service)
    {
        return $user->hasPermissionTo('delete_service');
    }
    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('publish_service') || 
               $user->hasRole(['super-admin', 'admin', 'communication_manager']);
    }

}