<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\UserPermissionObserver;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SecurityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register observers
        User::observe(UserPermissionObserver::class);

        // Listen to Spatie Permission events for additional security logging
        $this->registerPermissionEventListeners();
    }

    private function registerPermissionEventListeners(): void
    {
        // Listen to role attachment events
        \Illuminate\Support\Facades\Event::listen(
            \Spatie\Permission\Events\RoleAttached::class,
            function ($event) {
                \App\Services\PermissionAuditService::logRoleChange(
                    $event->model,
                    [], // We don't have before state in this event
                    $event->model->roles->pluck('name')->toArray(),
                    'role_attached'
                );
            }
        );

        // Listen to role detachment events
        \Illuminate\Support\Facades\Event::listen(
            \Spatie\Permission\Events\RoleDetached::class,
            function ($event) {
                \App\Services\PermissionAuditService::logRoleChange(
                    $event->model,
                    [], // We don't have before state in this event
                    $event->model->roles->pluck('name')->toArray(),
                    'role_detached'
                );
            }
        );

        // Listen to permission attachment events (direct permissions - security concern)
        \Illuminate\Support\Facades\Event::listen(
            \Spatie\Permission\Events\PermissionAttached::class,
            function ($event) {
                \App\Services\PermissionAuditService::logSecurityViolation('direct_permission_attached', [
                    'user_id' => $event->model->id,
                    'user_email' => $event->model->email,
                    'permission' => $event->permission->name
                ]);
            }
        );

        // Listen to permission detachment events
        \Illuminate\Support\Facades\Event::listen(
            \Spatie\Permission\Events\PermissionDetached::class,
            function ($event) {
                \App\Services\PermissionAuditService::logPermissionChange(
                    $event->model,
                    [], // We don't have before state in this event
                    $event->model->permissions->pluck('name')->toArray(),
                    'permission_detached'
                );
            }
        );
    }
}
