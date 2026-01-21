<?php

namespace App\Policies;

use App\Models\Publication;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PublicationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_publications');
    }

    /**
     * Determine whether the user can view the model.
     * Les publications publiées sont accessibles à tous
     */
    public function view(?User $user, Publication $publication): bool
    {
        // Si la publication est publiée (a_la_une ou en_vedette), tout le monde peut la voir
        if ($publication->a_la_une || $publication->en_vedette) {
            return true;
        }
        
        // Sinon, vérifier les permissions (admin uniquement)
        if (!$user) {
            return false;
        }
        
        return $user->hasPermissionTo('view_publication');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_publication');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Publication $publication): bool
    {
        return $user->hasPermissionTo('update_publication');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Publication $publication): bool
    {
        return $user->hasPermissionTo('delete_publication');
    }

    /**
     * Determine whether the user can moderate the model.
     */
    public function moderate(User $user, ?Publication $publication = null): bool
    {
        return $user->hasPermissionTo('moderate_publication');
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Publication $publication): bool
    {
        return $user->hasPermissionTo('publish_publication');
    }

    /**
     * Determine whether the user can unpublish the model.
     */
    public function unpublish(User $user, Publication $publication): bool
    {
        return $user->hasPermissionTo('unpublish_publication');
    }

    /**
     * Determine whether the user can download the model.
     */
    public function download(User $user, Publication $publication): bool
    {
        return $user->hasPermissionTo('download_publication');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Publication $publication): bool
    {
        return $user->hasPermissionTo('update_publication');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Publication $publication): bool
    {
        return $user->hasPermissionTo('delete_publication');
    }
}