<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 🔒 SÉCURITÉ: Audit quotidien des permissions
Schedule::command('security:audit')
    ->daily()
    ->at('02:00')
    ->appendOutputTo(storage_path('logs/security_audit.log'))
    ->emailOutputOnFailure(env('ADMIN_EMAIL', 'admin@iriucbc.com'))
    ->description('Daily security audit of permissions system');

// 🔒 SÉCURITÉ: Monitoring continu des anomalies
Schedule::command('security:audit --fix')
    ->weekly()
    ->sundays()
    ->at('03:00')
    ->appendOutputTo(storage_path('logs/security_fixes.log'))
    ->emailOutputOnFailure(env('ADMIN_EMAIL', 'admin@iriucbc.com'))
    ->description('Weekly automated security fixes');

// 🔒 SÉCURITÉ: Analyse des rôles composites (mensuel)
Schedule::command('roles:composite analyze')
    ->monthly()
    ->description('Monthly analysis of role combinations for composite role suggestions');

// 🔒 SÉCURITÉ: Nettoyage des logs de sécurité (garder 90 jours)
Schedule::command('logs:clear', ['--older-than' => '90'])
    ->monthly()
    ->description('Clean old security logs');

// 🔒 SÉCURITÉ: Génération du rapport de sécurité mensuel
Schedule::call(function () {
    $auditService = new \App\Services\PermissionAuditService();
    $report = $auditService::generateMonthlyReport();
    
    if ($report) {
        $filename = storage_path('reports/monthly_security_' . date('Y-m') . '.json');
        file_put_contents($filename, json_encode($report, JSON_PRETTY_PRINT));
        
        // Envoyer par email aux administrateurs si des anomalies sont détectées
        if ($report['security_violations'] > 0 || $report['permission_changes'] > 0) {
            \Illuminate\Support\Facades\Mail::raw(
                "Rapport de sécurité mensuel généré avec {$report['security_violations']} violations détectées.",
                function ($message) {
                    $message->to(env('ADMIN_EMAIL', 'admin@iriucbc.com'))
                           ->subject('Rapport de Sécurité - ' . date('Y-m'));
                }
            );
        }
    }
})->monthly()->description('Generate monthly security report');

// 🔒 SÉCURITÉ: Révision annuelle des accès (notification)
Schedule::call(function () {
    \Illuminate\Support\Facades\Mail::raw(
        "Il est temps de procéder à la révision annuelle des accès utilisateurs. Exécutez: php artisan security:annual-review",
        function ($message) {
            $message->to(env('ADMIN_EMAIL', 'admin@iriucbc.com'))
                   ->subject('Révision Annuelle des Accès Requise');
        }
    );
})->yearly()->description('Annual access review reminder');
