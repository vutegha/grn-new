<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Services\PermissionAuditService;
use Illuminate\Support\Facades\Mail;

class ApproveRoleCommand extends Command
{
    protected $signature = 'security:approve-role 
                            {user_email : Email of the user}
                            {role : Role to assign}
                            {--approve : Approve the role assignment}
                            {--reject : Reject the role assignment}
                            {--list-pending : List all pending approvals}';
    
    protected $description = 'Approve or reject sensitive role assignments';

    public function handle()
    {
        if ($this->option('list-pending')) {
            $this->listPendingApprovals();
            return 0;
        }

        $userEmail = $this->argument('user_email');
        $roleName = $this->argument('role');

        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            $this->error("User with email {$userEmail} not found");
            return 1;
        }

        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->error("Role {$roleName} not found");
            return 1;
        }

        // Check if role is sensitive
        $sensitiveRoles = ['super-admin', 'admin', 'moderator'];
        if (!in_array($roleName, $sensitiveRoles)) {
            $this->info("Role {$roleName} is not sensitive, assigning directly...");
            $user->assignRole($role);
            $this->info("✅ Role {$roleName} assigned to {$user->email}");
            return 0;
        }

        // Handle approval/rejection
        if ($this->option('approve')) {
            $this->approveRole($user, $role);
        } elseif ($this->option('reject')) {
            $this->rejectRole($user, $role);
        } else {
            $this->showRoleInfo($user, $role);
            $this->requestApprovalDecision($user, $role);
        }

        return 0;
    }

    private function listPendingApprovals()
    {
        $logFile = storage_path('logs/permission_audit_' . date('Y-m') . '.json');
        
        if (!file_exists($logFile)) {
            $this->info('No pending approvals found');
            return;
        }
        
        $logs = json_decode(file_get_contents($logFile), true);
        $pendingApprovals = array_filter($logs, function($log) {
            return $log['event'] === 'approval_required' && 
                   ($log['status'] ?? 'pending') === 'pending';
        });
        
        if (empty($pendingApprovals)) {
            $this->info('No pending approvals found');
            return;
        }
        
        $this->info('📋 Pending Role Approvals:');
        $this->line('');
        
        foreach ($pendingApprovals as $approval) {
            $details = $approval['details'];
            $this->line("📅 {$approval['timestamp']}");
            $this->line("👤 User: {$details['user_email']} (ID: {$details['user_id']})");
            $this->line("🎭 Requested roles: " . implode(', ', $details['escalated_to_roles']));
            $this->line("📧 Requested by: {$approval['requested_by_email']}");
            $this->line("🔍 Action: {$approval['action']}");
            $this->line('');
        }
    }

    private function showRoleInfo(User $user, Role $role)
    {
        $this->info("📋 Role Assignment Request");
        $this->line("👤 User: {$user->name} ({$user->email})");
        $this->line("🎭 Requested Role: {$role->name}");
        $this->line("📧 Email Verified: " . ($user->hasVerifiedEmail() ? 'Yes' : 'No'));
        $this->line("🔑 Current Roles: " . $user->roles->pluck('name')->implode(', '));
        $this->line("📊 Current Permissions: " . $user->getAllPermissions()->count());
        $this->line("🎯 Role Permissions: " . $role->permissions->count());
        
        $this->line('');
        $this->line("🔍 Role Permissions:");
        foreach ($role->permissions as $permission) {
            $this->line("   • {$permission->name}");
        }
    }

    private function requestApprovalDecision(User $user, Role $role)
    {
        $this->line('');
        
        // Security checks
        $warnings = [];
        
        if (!$user->hasVerifiedEmail()) {
            $warnings[] = "⚠️ User email is not verified";
        }
        
        if ($user->roles->count() > 0) {
            $warnings[] = "⚠️ User already has roles: " . $user->roles->pluck('name')->implode(', ');
        }
        
        if ($user->permissions->count() > 0) {
            $warnings[] = "⚠️ User has direct permissions: " . $user->permissions->count();
        }
        
        if (!empty($warnings)) {
            $this->warn("Security Warnings:");
            foreach ($warnings as $warning) {
                $this->warn("   {$warning}");
            }
            $this->line('');
        }
        
        $decision = $this->choice(
            'What would you like to do?',
            ['approve', 'reject', 'defer'],
            'defer'
        );
        
        switch ($decision) {
            case 'approve':
                $this->approveRole($user, $role);
                break;
            case 'reject':
                $this->rejectRole($user, $role);
                break;
            default:
                $this->info('Decision deferred. Use --approve or --reject flags when ready.');
                break;
        }
    }

    private function approveRole(User $user, Role $role)
    {
        // Pre-assignment checks
        if (!$user->hasVerifiedEmail() && in_array($role->name, ['admin', 'super-admin'])) {
            if (!$this->confirm("User email is not verified. Continue anyway?")) {
                $this->info('Role assignment cancelled');
                return;
            }
        }
        
        // Assign role
        $user->assignRole($role);
        
        // Log approval
        PermissionAuditService::logRoleChange(
            $user,
            $user->roles->where('name', '!=', $role->name)->pluck('name')->toArray(),
            $user->roles->pluck('name')->toArray(),
            'approved'
        );
        
        $this->info("✅ Role {$role->name} approved and assigned to {$user->email}");
        
        // Send notification email
        $this->sendRoleApprovalNotification($user, $role, 'approved');
    }

    private function rejectRole(User $user, Role $role)
    {
        $reason = $this->ask('Reason for rejection (optional):');
        
        // Log rejection
        PermissionAuditService::logSecurityViolation('role_assignment_rejected', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'rejected_role' => $role->name,
            'reason' => $reason,
            'reviewer' => auth()->user()?->email ?? 'system'
        ]);
        
        $this->warn("❌ Role {$role->name} rejected for {$user->email}");
        if ($reason) {
            $this->line("Reason: {$reason}");
        }
        
        // Send notification email
        $this->sendRoleApprovalNotification($user, $role, 'rejected', $reason);
    }

    private function sendRoleApprovalNotification(User $user, Role $role, string $status, string $reason = null)
    {
        // In a real implementation, you would send an email here
        $this->info("📧 Notification sent to {$user->email} about role {$status}");
        
        if ($status === 'rejected' && $reason) {
            $this->line("   Reason: {$reason}");
        }
    }
}
