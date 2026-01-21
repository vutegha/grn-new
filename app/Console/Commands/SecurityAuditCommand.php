<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SecurityAuditCommand extends Command
{
    protected $signature = 'security:audit {--fix : Automatically fix detected issues}';
    protected $description = 'Audit and optionally fix security issues in permissions system';

    public function handle()
    {
        $this->info('🔒 Starting Security Audit...');
        $this->line('');

        $issues = [];
        $fixes = [];

        // 1. Check for direct permissions
        $this->info('🔍 Checking for users with direct permissions...');
        $usersWithDirectPermissions = User::whereHas('permissions')->with('permissions')->get();
        
        if ($usersWithDirectPermissions->count() > 0) {
            $issues[] = [
                'type' => 'direct_permissions',
                'severity' => 'HIGH',
                'count' => $usersWithDirectPermissions->count(),
                'users' => $usersWithDirectPermissions
            ];

            foreach ($usersWithDirectPermissions as $user) {
                $this->warn("   ⚠️ {$user->name} ({$user->email}) has {$user->permissions->count()} direct permissions");
            }
        } else {
            $this->info('   ✅ No users with direct permissions found');
        }

        // 2. Check for multiple roles
        $this->info('🔍 Checking for users with multiple roles...');
        $usersWithMultipleRoles = User::whereHas('roles', function($query) {
            $query->havingRaw('COUNT(*) > 1');
        })->with('roles')->get();

        if ($usersWithMultipleRoles->count() > 0) {
            $issues[] = [
                'type' => 'multiple_roles',
                'severity' => 'MEDIUM',
                'count' => $usersWithMultipleRoles->count(),
                'users' => $usersWithMultipleRoles
            ];

            foreach ($usersWithMultipleRoles as $user) {
                $roleNames = $user->roles->pluck('name')->implode(', ');
                $this->warn("   ⚠️ {$user->name} ({$user->email}) has roles: {$roleNames}");
            }
        } else {
            $this->info('   ✅ No users with multiple roles found');
        }

        // 3. Check for unverified admin accounts
        $this->info('🔍 Checking for unverified admin accounts...');
        $unverifiedAdmins = User::whereNull('email_verified_at')
            ->whereHas('roles', function($query) {
                $query->whereIn('name', ['admin', 'super-admin', 'moderator']);
            })->with('roles')->get();

        if ($unverifiedAdmins->count() > 0) {
            $issues[] = [
                'type' => 'unverified_admins',
                'severity' => 'CRITICAL',
                'count' => $unverifiedAdmins->count(),
                'users' => $unverifiedAdmins
            ];

            foreach ($unverifiedAdmins as $user) {
                $roleNames = $user->roles->pluck('name')->implode(', ');
                $this->error("   🔴 {$user->name} ({$user->email}) has admin roles but unverified email: {$roleNames}");
            }
        } else {
            $this->info('   ✅ All admin accounts have verified emails');
        }

        // Summary
        $this->line('');
        $this->info('📊 Security Audit Summary:');
        $totalIssues = collect($issues)->sum('count');
        
        if ($totalIssues === 0) {
            $this->info('✅ No security issues found!');
            return 0;
        }

        $this->warn("⚠️ Found {$totalIssues} security issues");
        foreach ($issues as $issue) {
            $severity = $issue['severity'];
            $icon = match($severity) {
                'CRITICAL' => '🔴',
                'HIGH' => '🟠',
                'MEDIUM' => '🟡',
                default => '🔵'
            };
            $this->line("   {$icon} {$severity}: {$issue['count']} users with {$issue['type']}");
        }

        // Apply fixes if requested
        if ($this->option('fix')) {
            $this->line('');
            $this->info('🔧 Applying security fixes...');
            $this->applySecurityFixes($issues);
        } else {
            $this->line('');
            $this->comment('💡 To automatically fix these issues, run: php artisan security:audit --fix');
        }

        // Log audit results
        $this->logAuditResults($issues, $fixes);

        return $totalIssues > 0 ? 1 : 0;
    }

    private function applySecurityFixes(array $issues)
    {
        $fixes = [];

        foreach ($issues as $issue) {
            switch ($issue['type']) {
                case 'direct_permissions':
                    $fixes[] = $this->fixDirectPermissions($issue['users']);
                    break;
                
                case 'multiple_roles':
                    $fixes[] = $this->fixMultipleRoles($issue['users']);
                    break;
                
                case 'unverified_admins':
                    $fixes[] = $this->fixUnverifiedAdmins($issue['users']);
                    break;
            }
        }

        $this->info('✅ Security fixes applied successfully');
        return $fixes;
    }

    private function fixDirectPermissions($users)
    {
        $fixed = [];
        
        foreach ($users as $user) {
            $permissionCount = $user->permissions->count();
            
            // Get user's roles and their permissions
            $rolePermissions = $user->getPermissionsViaRoles();
            $directPermissions = $user->permissions;
            
            // Check if direct permissions are covered by roles
            $uncoveredPermissions = $directPermissions->diff($rolePermissions);
            
            if ($uncoveredPermissions->isEmpty()) {
                // All direct permissions are covered by roles, safe to remove
                $user->permissions()->detach();
                $this->info("   ✅ Removed {$permissionCount} direct permissions from {$user->name}");
                $fixed[] = [
                    'user' => $user->email,
                    'action' => 'removed_direct_permissions',
                    'count' => $permissionCount
                ];
            } else {
                // Some permissions not covered, need manual review
                $this->warn("   ⚠️ {$user->name} has permissions not covered by roles, requires manual review");
                $fixed[] = [
                    'user' => $user->email,
                    'action' => 'requires_manual_review',
                    'uncovered_permissions' => $uncoveredPermissions->pluck('name')->toArray()
                ];
            }
        }
        
        return $fixed;
    }

    private function fixMultipleRoles($users)
    {
        $fixed = [];
        $roleHierarchy = [
            'super-admin' => 6,
            'admin' => 5,
            'gestionnaire_projets' => 4,
            'moderator' => 3,
            'editor' => 2,
            'user' => 1
        ];
        
        foreach ($users as $user) {
            $currentRoles = $user->roles->pluck('name')->toArray();
            
            // Find highest role
            $highestRole = null;
            $highestLevel = 0;
            
            foreach ($currentRoles as $roleName) {
                if (isset($roleHierarchy[$roleName]) && $roleHierarchy[$roleName] > $highestLevel) {
                    $highestLevel = $roleHierarchy[$roleName];
                    $highestRole = $roleName;
                }
            }
            
            if ($highestRole) {
                $user->syncRoles([$highestRole]);
                $this->info("   ✅ {$user->name} roles simplified to: {$highestRole}");
                $fixed[] = [
                    'user' => $user->email,
                    'action' => 'simplified_roles',
                    'from' => $currentRoles,
                    'to' => $highestRole
                ];
            }
        }
        
        return $fixed;
    }

    private function fixUnverifiedAdmins($users)
    {
        $fixed = [];
        
        foreach ($users as $user) {
            $currentRoles = $user->roles->pluck('name')->toArray();
            
            // Store admin roles for later restoration
            $adminRoles = array_intersect($currentRoles, ['admin', 'super-admin', 'moderator']);
            
            // Remove admin roles and assign user role
            $user->syncRoles(['user']);
            
            $this->warn("   ⚠️ {$user->name} admin privileges suspended until email verification");
            $fixed[] = [
                'user' => $user->email,
                'action' => 'suspended_admin_privileges',
                'suspended_roles' => $adminRoles,
                'note' => 'Privileges can be restored after email verification'
            ];
        }
        
        return $fixed;
    }

    private function logAuditResults(array $issues, array $fixes = [])
    {
        $logData = [
            'audit_timestamp' => Carbon::now()->toISOString(),
            'total_issues' => collect($issues)->sum('count'),
            'issues_by_type' => collect($issues)->mapWithKeys(function($issue) {
                return [$issue['type'] => $issue['count']];
            })->toArray(),
            'fixes_applied' => !empty($fixes),
            'detailed_fixes' => $fixes,
            'issues' => $issues
        ];

        $logFile = storage_path('logs/security_audit_' . date('Y-m-d_H-i-s') . '.json');
        file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT));
        
        $this->info("📝 Audit log saved: {$logFile}");
    }
}
