<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Services\PermissionAuditService;
use Carbon\Carbon;

class AnnualAccessReviewCommand extends Command
{
    protected $signature = 'security:annual-review 
                            {--generate-report : Generate annual access review report}
                            {--start-review : Start interactive review process}
                            {--user= : Review specific user}
                            {--export= : Export format (json|csv|html)}';
                            
    protected $description = 'Conduct annual access review for all users';

    public function handle()
    {
        $this->info('🔍 Annual Access Review System');
        $this->line('');

        if ($this->option('generate-report')) {
            $this->generateAnnualReport();
        } elseif ($this->option('start-review')) {
            $this->startInteractiveReview();
        } elseif ($this->option('user')) {
            $this->reviewSpecificUser($this->option('user'));
        } else {
            $this->showReviewMenu();
        }

        return 0;
    }

    private function showReviewMenu()
    {
        $this->info('Annual Access Review Options:');
        $this->line('');
        $this->line('1. Generate comprehensive report');
        $this->line('2. Start interactive review');
        $this->line('3. Review specific user');
        $this->line('4. Export access matrix');
        $this->line('');

        $choice = $this->choice('What would you like to do?', [
            'generate-report',
            'interactive-review', 
            'specific-user',
            'export-matrix'
        ]);

        switch ($choice) {
            case 'generate-report':
                $this->generateAnnualReport();
                break;
            case 'interactive-review':
                $this->startInteractiveReview();
                break;
            case 'specific-user':
                $email = $this->ask('Enter user email:');
                $this->reviewSpecificUser($email);
                break;
            case 'export-matrix':
                $this->exportAccessMatrix();
                break;
        }
    }

    private function generateAnnualReport()
    {
        $this->info('📊 Generating Annual Access Review Report...');
        
        $users = User::with(['roles', 'permissions'])->get();
        $roles = Role::with('permissions')->get();
        
        $report = [
            'review_date' => Carbon::now()->toDateString(),
            'total_users' => $users->count(),
            'total_roles' => $roles->count(),
            'user_analysis' => [],
            'role_analysis' => [],
            'security_concerns' => [],
            'recommendations' => []
        ];

        // Analyze users
        foreach ($users as $user) {
            $userAnalysis = $this->analyzeUser($user);
            $report['user_analysis'][] = $userAnalysis;
            
            // Check for security concerns
            if ($userAnalysis['risk_level'] === 'HIGH') {
                $report['security_concerns'][] = [
                    'user' => $user->email,
                    'concerns' => $userAnalysis['concerns']
                ];
            }
        }

        // Analyze roles
        foreach ($roles as $role) {
            $report['role_analysis'][] = [
                'name' => $role->name,
                'users_count' => $role->users()->count(),
                'permissions_count' => $role->permissions->count(),
                'last_used' => $this->getRoleLastUsed($role),
                'utilization' => $this->getRoleUtilization($role)
            ];
        }

        // Generate recommendations
        $report['recommendations'] = $this->generateRecommendations($report);

        // Save report
        $filename = $this->saveReport($report);
        
        $this->info("✅ Annual review report generated: {$filename}");
        $this->displayReportSummary($report);
    }

    private function analyzeUser(User $user): array
    {
        $analysis = [
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'roles' => $user->roles->pluck('name')->toArray(),
            'role_count' => $user->roles->count(),
            'direct_permissions' => $user->permissions->pluck('name')->toArray(),
            'direct_permission_count' => $user->permissions->count(),
            'total_permissions' => $user->getAllPermissions()->count(),
            'email_verified' => $user->hasVerifiedEmail(),
            'last_login' => $user->updated_at,
            'account_age_days' => $user->created_at->diffInDays(now()),
            'concerns' => [],
            'risk_level' => 'LOW'
        ];

        // Identify concerns
        if ($analysis['direct_permission_count'] > 0) {
            $analysis['concerns'][] = 'Has direct permissions (should use roles only)';
            $analysis['risk_level'] = 'MEDIUM';
        }

        if ($analysis['role_count'] > 1) {
            $analysis['concerns'][] = 'Has multiple roles (should use composite role)';
            $analysis['risk_level'] = 'MEDIUM';
        }

        $adminRoles = array_intersect($analysis['roles'], ['admin', 'super-admin', 'moderator']);
        if (!empty($adminRoles) && !$analysis['email_verified']) {
            $analysis['concerns'][] = 'Admin user with unverified email';
            $analysis['risk_level'] = 'HIGH';
        }

        if ($analysis['total_permissions'] > 50) {
            $analysis['concerns'][] = 'Excessive permissions (over 50)';
            $analysis['risk_level'] = 'HIGH';
        }

        // Check for inactive accounts with privileges
        $daysSinceLastLogin = $user->updated_at->diffInDays(now());
        if ($daysSinceLastLogin > 90 && !empty($adminRoles)) {
            $analysis['concerns'][] = 'Inactive admin account (90+ days)';
            $analysis['risk_level'] = 'HIGH';
        }

        return $analysis;
    }

    private function startInteractiveReview()
    {
        $this->info('🔍 Starting Interactive Access Review...');
        
        $users = User::with(['roles', 'permissions'])->get();
        $reviewedCount = 0;
        $changesCount = 0;

        foreach ($users as $user) {
            $analysis = $this->analyzeUser($user);
            
            // Skip low-risk users unless requested
            if ($analysis['risk_level'] === 'LOW' && !$this->confirm("Review low-risk user {$user->email}?", false)) {
                continue;
            }

            $this->reviewUserInteractively($user, $analysis);
            $reviewedCount++;

            if (!$this->confirm('Continue to next user?')) {
                break;
            }
        }

        $this->info("✅ Interactive review completed");
        $this->info("📊 Reviewed {$reviewedCount} users");
    }

    private function reviewUserInteractively(User $user, array $analysis)
    {
        $this->line('');
        $this->info("👤 Reviewing: {$user->name} ({$user->email})");
        $this->line("🎭 Roles: " . implode(', ', $analysis['roles']));
        $this->line("🔑 Total Permissions: {$analysis['total_permissions']}");
        $this->line("📧 Email Verified: " . ($analysis['email_verified'] ? 'Yes' : 'No'));
        $this->line("📅 Last Login: {$analysis['last_login']}");
        $this->line("⚠️ Risk Level: {$analysis['risk_level']}");

        if (!empty($analysis['concerns'])) {
            $this->warn("🚨 Concerns:");
            foreach ($analysis['concerns'] as $concern) {
                $this->warn("   • {$concern}");
            }
        }

        $actions = ['keep-as-is', 'modify-roles', 'remove-permissions', 'deactivate', 'flag-for-review'];
        $action = $this->choice('What action should be taken?', $actions, 'keep-as-is');

        switch ($action) {
            case 'modify-roles':
                $this->modifyUserRoles($user);
                break;
            case 'remove-permissions':
                $this->removeDirectPermissions($user);
                break;
            case 'deactivate':
                $this->deactivateUser($user);
                break;
            case 'flag-for-review':
                $this->flagUserForReview($user);
                break;
        }
    }

    private function reviewSpecificUser(string $email)
    {
        $user = User::where('email', $email)->with(['roles', 'permissions'])->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found");
            return;
        }

        $analysis = $this->analyzeUser($user);
        $this->reviewUserInteractively($user, $analysis);
    }

    private function modifyUserRoles(User $user)
    {
        $currentRoles = $user->roles->pluck('name')->toArray();
        $availableRoles = Role::all()->pluck('name')->toArray();
        
        $this->line("Current roles: " . implode(', ', $currentRoles));
        
        $newRoles = $this->choice(
            'Select new roles (multiple selection with comma)',
            $availableRoles,
            null,
            null,
            true
        );

        if ($newRoles !== $currentRoles) {
            $user->syncRoles($newRoles);
            PermissionAuditService::logRoleChange($user, $currentRoles, $newRoles, 'annual_review');
            $this->info("✅ Roles updated for {$user->email}");
        }
    }

    private function removeDirectPermissions(User $user)
    {
        $permissionCount = $user->permissions->count();
        if ($permissionCount > 0) {
            $user->permissions()->detach();
            PermissionAuditService::logPermissionChange(
                $user,
                $user->permissions->pluck('name')->toArray(),
                [],
                'annual_review_cleanup'
            );
            $this->info("✅ Removed {$permissionCount} direct permissions from {$user->email}");
        } else {
            $this->info("User has no direct permissions to remove");
        }
    }

    private function deactivateUser(User $user)
    {
        $reason = $this->ask('Reason for deactivation:');
        
        // Remove all roles and permissions
        $user->roles()->detach();
        $user->permissions()->detach();
        
        PermissionAuditService::logSecurityViolation('user_deactivated_annual_review', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'reason' => $reason
        ]);
        
        $this->warn("⚠️ User {$user->email} deactivated and access removed");
    }

    private function flagUserForReview(User $user)
    {
        $notes = $this->ask('Review notes:');
        
        PermissionAuditService::logApprovalRequired('flagged_for_detailed_review', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'notes' => $notes,
            'flagged_during' => 'annual_review'
        ]);
        
        $this->info("🏃 User {$user->email} flagged for detailed review");
    }

    private function getRoleLastUsed(Role $role): ?string
    {
        // This would require login tracking - simplified for now
        return 'unknown';
    }

    private function getRoleUtilization(Role $role): string
    {
        $userCount = $role->users()->count();
        if ($userCount === 0) return 'unused';
        if ($userCount === 1) return 'single-user';
        if ($userCount <= 5) return 'limited';
        return 'active';
    }

    private function generateRecommendations(array $report): array
    {
        $recommendations = [];
        
        // High-risk users
        $highRiskCount = count($report['security_concerns']);
        if ($highRiskCount > 0) {
            $recommendations[] = "Immediately review {$highRiskCount} high-risk users";
        }
        
        // Unused roles
        $unusedRoles = array_filter($report['role_analysis'], fn($role) => $role['utilization'] === 'unused');
        if (!empty($unusedRoles)) {
            $recommendations[] = "Consider removing " . count($unusedRoles) . " unused roles";
        }
        
        // Direct permissions
        $usersWithDirectPermissions = array_filter($report['user_analysis'], fn($user) => $user['direct_permission_count'] > 0);
        if (!empty($usersWithDirectPermissions)) {
            $recommendations[] = "Eliminate direct permissions for " . count($usersWithDirectPermissions) . " users";
        }
        
        return $recommendations;
    }

    private function saveReport(array $report): string
    {
        $format = $this->option('export') ?: 'json';
        $timestamp = date('Y-m-d_H-i-s');
        
        switch ($format) {
            case 'csv':
                $filename = storage_path("reports/annual_access_review_{$timestamp}.csv");
                $this->saveReportAsCsv($report, $filename);
                break;
            case 'html':
                $filename = storage_path("reports/annual_access_review_{$timestamp}.html");
                $this->saveReportAsHtml($report, $filename);
                break;
            default:
                $filename = storage_path("reports/annual_access_review_{$timestamp}.json");
                file_put_contents($filename, json_encode($report, JSON_PRETTY_PRINT));
                break;
        }
        
        return $filename;
    }

    private function saveReportAsCsv(array $report, string $filename): void
    {
        $csv = fopen($filename, 'w');
        
        // Headers
        fputcsv($csv, ['Email', 'Roles', 'Direct Permissions', 'Total Permissions', 'Risk Level', 'Concerns']);
        
        // Data
        foreach ($report['user_analysis'] as $user) {
            fputcsv($csv, [
                $user['email'],
                implode(';', $user['roles']),
                $user['direct_permission_count'],
                $user['total_permissions'],
                $user['risk_level'],
                implode(';', $user['concerns'])
            ]);
        }
        
        fclose($csv);
    }

    private function saveReportAsHtml(array $report, string $filename): void
    {
        $html = $this->generateHtmlReport($report);
        file_put_contents($filename, $html);
    }

    private function generateHtmlReport(array $report): string
    {
        // Simplified HTML report generation
        $html = "<html><head><title>Annual Access Review Report</title></head><body>";
        $html .= "<h1>Annual Access Review Report</h1>";
        $html .= "<p>Generated: {$report['review_date']}</p>";
        $html .= "<h2>Summary</h2>";
        $html .= "<ul>";
        $html .= "<li>Total Users: {$report['total_users']}</li>";
        $html .= "<li>Total Roles: {$report['total_roles']}</li>";
        $html .= "<li>Security Concerns: " . count($report['security_concerns']) . "</li>";
        $html .= "</ul>";
        $html .= "</body></html>";
        
        return $html;
    }

    private function displayReportSummary(array $report): void
    {
        $this->line('');
        $this->info('📊 Report Summary:');
        $this->line("• Total Users: {$report['total_users']}");
        $this->line("• Total Roles: {$report['total_roles']}");
        $this->line("• Security Concerns: " . count($report['security_concerns']));
        $this->line("• Recommendations: " . count($report['recommendations']));
        
        if (!empty($report['recommendations'])) {
            $this->line('');
            $this->warn('🎯 Key Recommendations:');
            foreach ($report['recommendations'] as $rec) {
                $this->warn("   • {$rec}");
            }
        }
    }

    private function exportAccessMatrix()
    {
        $this->info('📋 Exporting Access Matrix...');
        
        $users = User::with(['roles.permissions'])->get();
        $allPermissions = \Spatie\Permission\Models\Permission::all()->pluck('name')->sort()->values();
        
        $matrix = [];
        $matrix[] = array_merge(['User', 'Email', 'Roles'], $allPermissions->toArray());
        
        foreach ($users as $user) {
            $row = [
                $user->name,
                $user->email,
                $user->roles->pluck('name')->implode(';')
            ];
            
            $userPermissions = $user->getAllPermissions()->pluck('name');
            
            foreach ($allPermissions as $permission) {
                $row[] = $userPermissions->contains($permission) ? 'Yes' : 'No';
            }
            
            $matrix[] = $row;
        }
        
        $filename = storage_path('reports/access_matrix_' . date('Y-m-d_H-i-s') . '.csv');
        $file = fopen($filename, 'w');
        
        foreach ($matrix as $row) {
            fputcsv($file, $row);
        }
        
        fclose($file);
        
        $this->info("✅ Access matrix exported: {$filename}");
    }
}
