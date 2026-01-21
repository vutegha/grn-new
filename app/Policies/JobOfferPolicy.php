<?php

namespace App\Policies;

use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobOfferPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any job offers.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view_joboffers');
    }

    /**
     * Determine whether the user can view the job offer.
     */
    public function view(User $user, JobOffer $jobOffer)
    {
        return $user->hasPermissionTo('view_joboffer');
    }

    /**
     * Determine whether the user can create job offers.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_joboffer');
    }

    /**
     * Determine whether the user can update the job offer.
     */
    public function update(User $user, JobOffer $jobOffer)
    {
        return $user->hasPermissionTo('update_joboffer');
    }

    /**
     * Determine whether the user can delete the job offer.
     */
    public function delete(User $user, JobOffer $jobOffer)
    {
        return $user->hasPermissionTo('delete_joboffer');
    }

    /**
     * Determine whether the user can publish the job offer.
     */
    public function publish(User $user, JobOffer $jobOffer)
    {
        return $user->hasPermissionTo('publish_joboffer');
    }

    /**
     * Determine whether the user can unpublish the job offer.
     */
    public function unpublish(User $user, JobOffer $jobOffer)
    {
        return $user->hasPermissionTo('unpublish_joboffer');
    }

    /**
     * Determine whether the user can moderate the job offer.
     */
    public function moderate(User $user, JobOffer $jobOffer)
    {
        return $user->hasPermissionTo('moderate_joboffer');
    }

    /**
     * Determine whether the user can restore the job offer.
     */
    public function restore(User $user, JobOffer $jobOffer)
    {
        return $user->hasPermissionTo('update_joboffer');
    }

    /**
     * Determine whether the user can permanently delete the job offer.
     */
    public function forceDelete(User $user, JobOffer $jobOffer)
    {
        return $user->hasPermissionTo('delete_joboffer');
    }
}
