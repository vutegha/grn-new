<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContactPolicy
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
        
        return $user->hasPermissionTo('view_contacts', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Contact $contact): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->hasPermissionTo('view_contacts', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
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
        
        return $user->hasPermissionTo('create_contact', 'web') ||
               $user->hasAnyRole(['admin'], 'web');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Contact $contact): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->hasPermissionTo('update_contact', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contact $contact): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->hasPermissionTo('delete_contact', 'web') ||
               $user->hasAnyRole(['admin'], 'web');
    }

    /**
     * Determine whether the user can respond to contacts.
     */
    public function respond(User $user, Contact $contact): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->hasPermissionTo('respond_contacts', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }
}
