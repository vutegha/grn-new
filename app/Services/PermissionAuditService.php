<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PermissionAuditService
{
    /**
     * Log role changes
     */
    public static function logRoleChange(User $user, array $before, array $after, string $action = 'updated')
    {
        $logData = [
            'event' => 'role_change',
            'action' => $action,
            'user_id' => $user->id,
            'user_email' => $user->email,
            'roles_before' => $before,
            'roles_after' => $after,
            'changed_by' => Auth::id(),
            'changed_by_email' => Auth::user()?->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => Carbon::now()->toISOString()
        ];

        Log::channel('security')->info('Role change detected', $logData);
        
        // Also store in dedicated permission audit log
        self::storeAuditLog($logData);
    }

    /**
     * Log permission changes
     */
    public static function logPermissionChange(User $user, array $before, array $after, string $action = 'updated')
    {
        $logData = [
            'event' => 'permission_change',
            'action' => $action,
            'user_id' => $user->id,
            'user_email' => $user->email,
            'permissions_before' => $before,
            'permissions_after' => $after,
            'changed_by' => Auth::id(),
            'changed_by_email' => Auth::user()?->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => Carbon::now()->toISOString()
        ];

        Log::channel('security')->warning('Direct permission change detected', $logData);
        
        // Store in audit log
        self::storeAuditLog($logData);
    }

    /**
     * Log security violations
     */
    public static function logSecurityViolation(string $type, array $details = [])
    {
        $logData = [
            'event' => 'security_violation',
            'violation_type' => $type,
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => Carbon::now()->toISOString()
        ];

        Log::channel('security')->error('Security violation detected', $logData);
        
        // Store in audit log
        self::storeAuditLog($logData);
    }

    /**
     * Log admin approval requirements
     */
    public static function logApprovalRequired(string $action, array $details = [])
    {
        $logData = [
            'event' => 'approval_required',
            'action' => $action,
            'requested_by' => Auth::id(),
            'requested_by_email' => Auth::user()?->email,
            'details' => $details,
            'status' => 'pending',
            'ip_address' => request()->ip(),
            'timestamp' => Carbon::now()->toISOString()
        ];

        Log::channel('security')->info('Admin approval required', $logData);
        
        // Store in audit log
        self::storeAuditLog($logData);
        
        return $logData;
    }

    /**
     * Store audit log in dedicated file
     */
    private static function storeAuditLog(array $logData)
    {
        $logFile = storage_path('logs/permission_audit_' . date('Y-m') . '.json');
        
        // Read existing logs
        $existingLogs = [];
        if (file_exists($logFile)) {
            $existingContent = file_get_contents($logFile);
            $existingLogs = json_decode($existingContent, true) ?: [];
        }
        
        // Add new log entry
        $existingLogs[] = $logData;
        
        // Write back to file
        file_put_contents($logFile, json_encode($existingLogs, JSON_PRETTY_PRINT));
    }

    /**
     * Generate monthly audit report
     */
    public static function generateMonthlyReport(string $month = null)
    {
        $month = $month ?: date('Y-m');
        $logFile = storage_path("logs/permission_audit_{$month}.json");
        
        if (!file_exists($logFile)) {
            return null;
        }
        
        $logs = json_decode(file_get_contents($logFile), true);
        
        $report = [
            'period' => $month,
            'total_events' => count($logs),
            'events_by_type' => [],
            'security_violations' => 0,
            'role_changes' => 0,
            'permission_changes' => 0,
            'approval_requests' => 0,
            'most_active_users' => [],
            'summary' => []
        ];
        
        // Analyze logs
        foreach ($logs as $log) {
            $event = $log['event'] ?? 'unknown';
            
            if (!isset($report['events_by_type'][$event])) {
                $report['events_by_type'][$event] = 0;
            }
            $report['events_by_type'][$event]++;
            
            switch ($event) {
                case 'security_violation':
                    $report['security_violations']++;
                    break;
                case 'role_change':
                    $report['role_changes']++;
                    break;
                case 'permission_change':
                    $report['permission_changes']++;
                    break;
                case 'approval_required':
                    $report['approval_requests']++;
                    break;
            }
        }
        
        // Generate summary
        $report['summary'] = [
            'high_risk_events' => $report['security_violations'] + $report['permission_changes'],
            'administrative_changes' => $report['role_changes'],
            'governance_events' => $report['approval_requests']
        ];
        
        return $report;
    }

    /**
     * Check for anomalous patterns
     */
    public static function detectAnomalies(string $period = '24h')
    {
        $since = Carbon::now()->subtract(1, 'day');
        $logFile = storage_path('logs/permission_audit_' . date('Y-m') . '.json');
        
        if (!file_exists($logFile)) {
            return [];
        }
        
        $logs = json_decode(file_get_contents($logFile), true);
        $recentLogs = array_filter($logs, function($log) use ($since) {
            $timestamp = Carbon::parse($log['timestamp']);
            return $timestamp->greaterThan($since);
        });
        
        $anomalies = [];
        
        // Check for rapid role changes
        $roleChanges = array_filter($recentLogs, fn($log) => $log['event'] === 'role_change');
        if (count($roleChanges) > 5) {
            $anomalies[] = [
                'type' => 'rapid_role_changes',
                'count' => count($roleChanges),
                'severity' => 'HIGH'
            ];
        }
        
        // Check for direct permission grants
        $permissionChanges = array_filter($recentLogs, fn($log) => $log['event'] === 'permission_change');
        if (count($permissionChanges) > 0) {
            $anomalies[] = [
                'type' => 'direct_permission_grants',
                'count' => count($permissionChanges),
                'severity' => 'CRITICAL'
            ];
        }
        
        // Check for security violations
        $violations = array_filter($recentLogs, fn($log) => $log['event'] === 'security_violation');
        if (count($violations) > 2) {
            $anomalies[] = [
                'type' => 'multiple_security_violations',
                'count' => count($violations),
                'severity' => 'CRITICAL'
            ];
        }
        
        return $anomalies;
    }
}
