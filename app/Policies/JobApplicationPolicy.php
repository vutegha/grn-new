<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any job applications.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view_jobapplications');
    }

    /**
     * Determine whether the user can view the job application.
     */
    public function view(User $user, JobApplication $jobApplication)
    {
        // Users can view their own applications or if they have the view permission
        return $user->hasPermissionTo('view_jobapplication') || 
               $jobApplication->user_id === $user->id;
    }

    /**
     * Determine whether the user can create job applications.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_jobapplication');
    }

    /**
     * Determine whether the user can update the job application.
     */
    public function update(User $user, JobApplication $jobApplication)
    {
        // Users can update their own applications or if they have the update permission
        return $user->hasPermissionTo('update_jobapplication') || 
               $jobApplication->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the job application.
     */
    public function delete(User $user, JobApplication $jobApplication)
    {
        // Users can delete their own applications or if they have the delete permission
        return $user->hasPermissionTo('delete_jobapplication') || 
               $jobApplication->user_id === $user->id;
    }

    /**
     * Determine whether the user can approve the job application.
     */
    public function approve(User $user, JobApplication $jobApplication)
    {
        return $user->hasPermissionTo('approve_jobapplication');
    }

    /**
     * Determine whether the user can reject the job application.
     */
    public function reject(User $user, JobApplication $jobApplication)
    {
        return $user->hasPermissionTo('reject_jobapplication');
    }

    /**
     * Determine whether the user can review the job application.
     */
    public function review(User $user, JobApplication $jobApplication)
    {
        return $user->hasPermissionTo('review_jobapplication');
    }

    /**
     * Determine whether the user can restore the job application.
     */
    public function restore(User $user, JobApplication $jobApplication)
    {
        return $user->hasPermissionTo('update_jobapplication');
    }

    /**
     * Determine whether the user can permanently delete the job application.
     */
    public function forceDelete(User $user, JobApplication $jobApplication)
    {
        return $user->hasPermissionTo('delete_jobapplication');
    }
}
