<?php

namespace App\Policies;

use App\Models\ContactInfo;
use App\Models\User;

class ContactInfoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_contact_infos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContactInfo $contactInfo): bool
    {
        return $user->hasPermissionTo('view_contact_info');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_contact_info');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContactInfo $contactInfo): bool
    {
        return $user->hasPermissionTo('update_contact_info');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContactInfo $contactInfo): bool
    {
        return $user->hasPermissionTo('delete_contact_info');
    }

    /**
     * Determine whether the user can toggle active status.
     */
    public function toggleActive(User $user, ContactInfo $contactInfo): bool
    {
        return $user->hasPermissionTo('update_contact_info');
    }
}
