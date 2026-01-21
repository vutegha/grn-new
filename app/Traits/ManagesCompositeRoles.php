<?php

namespace App\Traits;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\PermissionAuditService;

trait ManagesCompositeRoles
{
    /**
     * Create a composite role based on multiple existing roles
     */
    public static function createCompositeRole(string $name, array $roleNames, string $description = null): Role
    {
        // Check if composite role already exists
        if (Role::where('name', $name)->exists()) {
            throw new \InvalidArgumentException("Composite role '{$name}' already exists");
        }

        // Get all permissions from the specified roles
        $permissions = collect();
        foreach ($roleNames as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $permissions = $permissions->merge($role->permissions);
            }
        }

        // Remove duplicates
        $uniquePermissions = $permissions->unique('id');

        // Create the composite role
        $compositeRole = Role::create([
            'name' => $name,
            'guard_name' => 'web',
            'description' => $description ?? "Composite role combining: " . implode(', ', $roleNames)
        ]);

        // Assign all permissions to the composite role
        $compositeRole->syncPermissions($uniquePermissions);

        // Log the creation
        PermissionAuditService::logRoleChange(
            new \App\Models\User(), // Placeholder for system actions
            [],
            [$name],
            'composite_role_created'
        );

        return $compositeRole;
    }

    /**
     * Analyze common role combinations and suggest composite roles
     */
    public static function analyzeRoleCombinations(): array
    {
        $users = \App\Models\User::with('roles')->get();
        $combinations = [];

        foreach ($users as $user) {
            if ($user->roles->count() > 1) {
                $roleNames = $user->roles->pluck('name')->sort()->implode('|');
                
                if (!isset($combinations[$roleNames])) {
                    $combinations[$roleNames] = [
                        'roles' => $user->roles->pluck('name')->toArray(),
                        'count' => 0,
                        'users' => []
                    ];
                }
                
                $combinations[$roleNames]['count']++;
                $combinations[$roleNames]['users'][] = $user->email;
            }
        }

        // Sort by frequency
        uasort($combinations, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        // Generate suggestions
        $suggestions = [];
        foreach ($combinations as $combination => $data) {
            if ($data['count'] >= 2) { // Only suggest if 2+ users have this combination
                $suggestions[] = [
                    'composite_name' => self::generateCompositeName($data['roles']),
                    'base_roles' => $data['roles'],
                    'user_count' => $data['count'],
                    'affected_users' => $data['users'],
                    'priority' => $data['count'] > 5 ? 'HIGH' : 'MEDIUM'
                ];
            }
        }

        return $suggestions;
    }

    /**
     * Generate a composite role name from base roles
     */
    private static function generateCompositeName(array $roles): string
    {
        // Remove 'user' role from composite names as it's too generic
        $meaningfulRoles = array_filter($roles, fn($role) => $role !== 'user');
        
        if (empty($meaningfulRoles)) {
            return 'composite_' . implode('_', $roles);
        }

        // Sort for consistency
        sort($meaningfulRoles);

        // Create meaningful names
        if (count($meaningfulRoles) === 1) {
            return $meaningfulRoles[0] . '_extended';
        }

        if (in_array('admin', $meaningfulRoles) && in_array('moderator', $meaningfulRoles)) {
            return 'admin_moderator';
        }

        if (in_array('editor', $meaningfulRoles) && in_array('moderator', $meaningfulRoles)) {
            return 'content_moderator';
        }

        if (in_array('gestionnaire_projets', $meaningfulRoles)) {
            return 'project_manager_plus';
        }

        // Default: combine first two meaningful roles
        return implode('_', array_slice($meaningfulRoles, 0, 2));
    }

    /**
     * Migrate users from multiple roles to composite role
     */
    public static function migrateToCompositeRole(string $compositeName, array $sourceRoles): array
    {
        $compositeRole = Role::where('name', $compositeName)->first();
        
        if (!$compositeRole) {
            throw new \InvalidArgumentException("Composite role '{$compositeName}' does not exist");
        }

        $users = \App\Models\User::whereHas('roles', function($query) use ($sourceRoles) {
            $query->whereIn('name', $sourceRoles);
        })->with('roles')->get();

        $migrated = [];

        foreach ($users as $user) {
            $userRoles = $user->roles->pluck('name')->toArray();
            
            // Check if user has all the source roles
            if (array_diff($sourceRoles, $userRoles) === []) {
                $oldRoles = $userRoles;
                
                // Remove source roles and add composite role
                $user->syncRoles(array_merge(
                    array_diff($userRoles, $sourceRoles),
                    [$compositeName]
                ));

                PermissionAuditService::logRoleChange(
                    $user,
                    $oldRoles,
                    $user->roles->pluck('name')->toArray(),
                    'migrated_to_composite'
                );

                $migrated[] = [
                    'user' => $user->email,
                    'old_roles' => $oldRoles,
                    'new_roles' => $user->roles->pluck('name')->toArray()
                ];
            }
        }

        return $migrated;
    }

    /**
     * Validate composite role permissions
     */
    public static function validateCompositeRole(string $roleName): array
    {
        $role = Role::where('name', $roleName)->with('permissions')->first();
        
        if (!$role) {
            return ['valid' => false, 'error' => 'Role not found'];
        }

        $validation = [
            'valid' => true,
            'role_name' => $roleName,
            'permission_count' => $role->permissions->count(),
            'users_count' => $role->users()->count(),
            'issues' => [],
            'recommendations' => []
        ];

        // Check for excessive permissions
        if ($role->permissions->count() > 60) {
            $validation['issues'][] = 'Excessive permissions (over 60)';
            $validation['recommendations'][] = 'Consider splitting into more specific roles';
        }

        // Check for unused role
        if ($role->users()->count() === 0) {
            $validation['issues'][] = 'No users assigned to this role';
            $validation['recommendations'][] = 'Consider removing unused role';
        }

        // Check for conflicting permissions
        $permissions = $role->permissions->pluck('name');
        $conflicts = self::detectPermissionConflicts($permissions->toArray());
        
        if (!empty($conflicts)) {
            $validation['issues'] = array_merge($validation['issues'], $conflicts);
        }

        $validation['valid'] = empty($validation['issues']);

        return $validation;
    }

    /**
     * Detect conflicting permissions
     */
    private static function detectPermissionConflicts(array $permissions): array
    {
        $conflicts = [];

        // Check for view vs create conflicts (users shouldn't need both in most cases)
        $viewPermissions = array_filter($permissions, fn($p) => str_starts_with($p, 'view'));
        $createPermissions = array_filter($permissions, fn($p) => str_starts_with($p, 'create'));
        
        if (count($viewPermissions) > 10 && count($createPermissions) > 5) {
            $conflicts[] = 'Role may have too broad access (many view + create permissions)';
        }

        // Check for admin permissions with user role
        if (in_array('manage users', $permissions) && count($permissions) < 10) {
            $conflicts[] = 'User management permission with limited other permissions may indicate role misuse';
        }

        return $conflicts;
    }

    /**
     * Generate composite role migration report
     */
    public static function generateCompositeRoleReport(): array
    {
        $suggestions = self::analyzeRoleCombinations();
        $existingComposites = Role::where('name', 'LIKE', '%composite%')
            ->orWhere('name', 'LIKE', '%_%_')
            ->get();

        $report = [
            'analysis_date' => now()->toISOString(),
            'suggested_composites' => $suggestions,
            'existing_composites' => [],
            'migration_plan' => [],
            'expected_benefits' => [
                'simplified_user_management' => 'Reduce role assignments per user',
                'improved_security' => 'Eliminate multiple role combinations',
                'easier_auditing' => 'Clear role definitions and permissions'
            ]
        ];

        // Analyze existing composite roles
        foreach ($existingComposites as $role) {
            $report['existing_composites'][] = self::validateCompositeRole($role->name);
        }

        // Create migration plan
        foreach ($suggestions as $suggestion) {
            if ($suggestion['priority'] === 'HIGH' && $suggestion['user_count'] >= 3) {
                $report['migration_plan'][] = [
                    'step' => count($report['migration_plan']) + 1,
                    'action' => 'create_composite',
                    'composite_name' => $suggestion['composite_name'],
                    'base_roles' => $suggestion['base_roles'],
                    'affected_users' => $suggestion['user_count'],
                    'estimated_effort' => 'Low',
                    'risk_level' => 'Low'
                ];
            }
        }

        return $report;
    }
}
