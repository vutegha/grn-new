<?php

namespace App\Policies;

use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsletterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any newsletters.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('viewAny_newsletter') || 
               $user->can('view_newsletter') || 
               $user->can('manage_newsletter');
    }

    /**
     * Determine whether the user can view the newsletter.
     */
    public function view(User $user, Newsletter $newsletter): bool
    {
        return $user->can('view_newsletter') || 
               $user->can('manage_newsletter');
    }

    /**
     * Determine whether the user can create newsletters.
     */
    public function create(User $user): bool
    {
        return $user->can('create_newsletter') || 
               $user->can('manage_newsletter');
    }

    /**
     * Determine whether the user can update the newsletter.
     */
    public function update(User $user, Newsletter $newsletter): bool
    {
        return $user->can('update_newsletter') || 
               $user->can('manage_newsletter');
    }

    /**
     * Determine whether the user can delete the newsletter.
     */
    public function delete(User $user, Newsletter $newsletter): bool
    {
        return $user->can('delete_newsletter') || 
               $user->can('manage_newsletter');
    }

    /**
     * Determine whether the user can export newsletters.
     */
    public function export(User $user): bool
    {
        return $user->can('export_newsletter') || 
               $user->can('manage_newsletter');
    }

    /**
     * Determine whether the user can send newsletters.
     */
    public function send(User $user): bool
    {
        return $user->can('send_newsletter') || 
               $user->can('manage_newsletter');
    }

    /**
     * Determine whether the user can view newsletter statistics.
     */
    public function viewStats(User $user): bool
    {
        return $user->can('view_newsletter_stats') || 
               $user->can('manage_newsletter');
    }

    /**
     * Determine whether the user can moderate newsletters.
     */
    public function moderate(User $user): bool
    {
        return $user->can('moderate_newsletter') || 
               $user->can('manage_newsletter');
    }
}
