<?php

namespace App\Observers;

use App\Models\User;
use App\Services\PermissionAuditService;
use Illuminate\Support\Facades\Cache;

class UserPermissionObserver
{
    /**
     * Handle the User "updating" event.
     */
    public function updating(User $user): void
    {
        // Store original roles and permissions before update
        if ($user->isDirty()) {
            $original = $user->getOriginal();
            
            // Cache original state for comparison in updated event
            Cache::put("user_original_state_{$user->id}", [
                'roles' => $user->roles->pluck('name')->toArray(),
                'permissions' => $user->permissions->pluck('name')->toArray(),
            ], now()->addMinutes(10));
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $originalState = Cache::get("user_original_state_{$user->id}");
        
        if ($originalState) {
            $currentRoles = $user->roles->pluck('name')->toArray();
            $currentPermissions = $user->permissions->pluck('name')->toArray();
            
            // Check for role changes
            if ($originalState['roles'] !== $currentRoles) {
                PermissionAuditService::logRoleChange(
                    $user,
                    $originalState['roles'],
                    $currentRoles,
                    'updated'
                );
                
                // Check for security violations
                $this->checkRoleSecurityViolations($user, $originalState['roles'], $currentRoles);
            }
            
            // Check for permission changes
            if ($originalState['permissions'] !== $currentPermissions) {
                PermissionAuditService::logPermissionChange(
                    $user,
                    $originalState['permissions'],
                    $currentPermissions,
                    'updated'
                );
                
                // Direct permissions are a security concern
                if (count($currentPermissions) > 0) {
                    PermissionAuditService::logSecurityViolation('direct_permissions_assigned', [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'direct_permissions' => $currentPermissions
                    ]);
                }
            }
            
            // Clean up cache
            Cache::forget("user_original_state_{$user->id}");
        }
    }

    /**
     * Check for security violations in role assignments
     */
    private function checkRoleSecurityViolations(User $user, array $oldRoles, array $newRoles): void
    {
        $adminRoles = ['admin', 'super-admin', 'moderator'];
        
        // Check if admin role was assigned to unverified user
        $gainedAdminRoles = array_intersect(array_diff($newRoles, $oldRoles), $adminRoles);
        if (!empty($gainedAdminRoles) && !$user->hasVerifiedEmail()) {
            PermissionAuditService::logSecurityViolation('admin_role_assigned_to_unverified_user', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'assigned_admin_roles' => $gainedAdminRoles,
                'email_verified' => false
            ]);
        }
        
        // Check for multiple roles (should use composite roles instead)
        if (count($newRoles) > 1) {
            PermissionAuditService::logSecurityViolation('multiple_roles_assigned', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'roles' => $newRoles,
                'role_count' => count($newRoles)
            ]);
        }
        
        // Check for role escalation without approval
        $sensitiveRoles = ['super-admin', 'admin'];
        $escalatedRoles = array_intersect(array_diff($newRoles, $oldRoles), $sensitiveRoles);
        if (!empty($escalatedRoles)) {
            PermissionAuditService::logApprovalRequired('role_escalation', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'escalated_to_roles' => $escalatedRoles,
                'previous_roles' => $oldRoles
            ]);
        }
    }
}
