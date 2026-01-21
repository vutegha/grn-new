<?php

namespace App\Policies;

use App\Models\SocialLink;
use App\Models\User;

class SocialLinkPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_social_links');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SocialLink $socialLink): bool
    {
        return $user->can('view_social_links');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_social_links');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SocialLink $socialLink): bool
    {
        return $user->can('update_social_links');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SocialLink $socialLink): bool
    {
        return $user->can('delete_social_links');
    }

    /**
     * Determine whether the user can moderate social links.
     */
    public function moderate(User $user, ?SocialLink $socialLink = null): bool
    {
        return $user->can('moderate_social_links');
    }
}
