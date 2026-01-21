<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\ManagesCompositeRoles;
use Spatie\Permission\Models\Role;

class CompositeRoleCommand extends Command
{
    use ManagesCompositeRoles;

    protected $signature = 'roles:composite 
                            {action : Action to perform (analyze|create|migrate|validate|report)}
                            {--name= : Name of the composite role}
                            {--roles= : Comma-separated list of roles to combine}
                            {--auto : Automatically create suggested composite roles}';
                            
    protected $description = 'Manage composite roles to simplify multiple role assignments';

    public function handle()
    {
        $action = $this->argument('action');

        match($action) {
            'analyze' => $this->analyzeRoleCombinations(),
            'create' => $this->createCompositeRole(),
            'migrate' => $this->migrateUsers(),
            'validate' => $this->validateRoles(),
            'report' => $this->generateReport(),
            default => $this->error("Unknown action: {$action}")
        };

        return 0;
    }

    private function analyzeRoleCombinations()
    {
        $this->info('🔍 Analyzing role combinations...');
        
        $suggestions = self::analyzeRoleCombinations();
        
        if (empty($suggestions)) {
            $this->info('✅ No users with multiple roles found');
            return;
        }

        $this->info('📊 Found the following role combinations:');
        $this->line('');

        foreach ($suggestions as $suggestion) {
            $priority = $suggestion['priority'];
            $icon = $priority === 'HIGH' ? '🔴' : '🟡';
            
            $this->line("{$icon} Suggested composite: {$suggestion['composite_name']}");
            $this->line("   Base roles: " . implode(', ', $suggestion['base_roles']));
            $this->line("   Users affected: {$suggestion['user_count']}");
            $this->line("   Priority: {$priority}");
            $this->line('');
        }

        if ($this->option('auto')) {
            $this->autoCreateCompositeRoles($suggestions);
        } else {
            $this->info('💡 To auto-create high-priority composites, use --auto flag');
        }
    }

    private function createCompositeRole()
    {
        $name = $this->option('name');
        $rolesOption = $this->option('roles');

        if (!$name) {
            $name = $this->ask('Enter composite role name:');
        }

        if (!$rolesOption) {
            $availableRoles = Role::pluck('name')->toArray();
            $this->line('Available roles: ' . implode(', ', $availableRoles));
            $rolesOption = $this->ask('Enter roles to combine (comma-separated):');
        }

        $roles = array_map('trim', explode(',', $rolesOption));
        $description = $this->ask('Enter description (optional):');

        try {
            $compositeRole = self::createCompositeRole($name, $roles, $description);
            $this->info("✅ Composite role '{$name}' created successfully");
            $this->line("   Permissions: {$compositeRole->permissions->count()}");
            
            // Ask if user wants to migrate users immediately
            if ($this->confirm('Migrate users with these role combinations now?')) {
                $migrated = self::migrateToCompositeRole($name, $roles);
                $this->info("✅ Migrated " . count($migrated) . " users to composite role");
            }
            
        } catch (\Exception $e) {
            $this->error("Failed to create composite role: " . $e->getMessage());
        }
    }

    private function migrateUsers()
    {
        $name = $this->option('name');
        $rolesOption = $this->option('roles');

        if (!$name) {
            $compositeRoles = Role::where('name', 'LIKE', '%composite%')
                ->orWhere('name', 'LIKE', '%_%')
                ->pluck('name')
                ->toArray();
            
            if (empty($compositeRoles)) {
                $this->error('No composite roles found. Create one first.');
                return;
            }

            $name = $this->choice('Select composite role:', $compositeRoles);
        }

        if (!$rolesOption) {
            $rolesOption = $this->ask('Enter source roles to migrate from (comma-separated):');
        }

        $roles = array_map('trim', explode(',', $rolesOption));

        try {
            $migrated = self::migrateToCompositeRole($name, $roles);
            
            if (empty($migrated)) {
                $this->info('No users found with the specified role combination');
                return;
            }

            $this->info("✅ Migration completed");
            $this->line("   Users migrated: " . count($migrated));
            
            foreach ($migrated as $user) {
                $this->line("   • {$user['user']}: " . 
                    implode(', ', $user['old_roles']) . 
                    " → " . 
                    implode(', ', $user['new_roles'])
                );
            }
            
        } catch (\Exception $e) {
            $this->error("Migration failed: " . $e->getMessage());
        }
    }

    private function validateRoles()
    {
        $name = $this->option('name');

        if (!$name) {
            $roles = Role::pluck('name')->toArray();
            $name = $this->choice('Select role to validate:', $roles);
        }

        $validation = self::validateCompositeRole($name);

        $this->line('');
        $this->info("🔍 Validation Results for '{$name}':");
        $this->line("   Status: " . ($validation['valid'] ? '✅ Valid' : '❌ Issues Found'));
        $this->line("   Permissions: {$validation['permission_count']}");
        $this->line("   Users: {$validation['users_count']}");

        if (!empty($validation['issues'])) {
            $this->line('');
            $this->warn('⚠️ Issues Found:');
            foreach ($validation['issues'] as $issue) {
                $this->warn("   • {$issue}");
            }
        }

        if (!empty($validation['recommendations'])) {
            $this->line('');
            $this->info('💡 Recommendations:');
            foreach ($validation['recommendations'] as $rec) {
                $this->info("   • {$rec}");
            }
        }
    }

    private function generateReport()
    {
        $this->info('📊 Generating Composite Role Analysis Report...');
        
        $report = self::generateCompositeRoleReport();
        
        // Display summary
        $this->line('');
        $this->info('📋 Analysis Summary:');
        $this->line("   Suggested composites: " . count($report['suggested_composites']));
        $this->line("   Existing composites: " . count($report['existing_composites']));
        $this->line("   Migration steps: " . count($report['migration_plan']));

        // Show high-priority suggestions
        $highPriority = array_filter($report['suggested_composites'], 
            fn($s) => $s['priority'] === 'HIGH');
        
        if (!empty($highPriority)) {
            $this->line('');
            $this->warn('🔴 High-Priority Suggestions:');
            foreach ($highPriority as $suggestion) {
                $this->warn("   • {$suggestion['composite_name']} ({$suggestion['user_count']} users)");
            }
        }

        // Show migration plan
        if (!empty($report['migration_plan'])) {
            $this->line('');
            $this->info('🚀 Recommended Migration Plan:');
            foreach ($report['migration_plan'] as $step) {
                $this->info("   {$step['step']}. Create '{$step['composite_name']}' from: " . 
                    implode(', ', $step['base_roles']) . 
                    " ({$step['affected_users']} users)");
            }
        }

        // Save detailed report
        $filename = storage_path('reports/composite_roles_' . date('Y-m-d_H-i-s') . '.json');
        file_put_contents($filename, json_encode($report, JSON_PRETTY_PRINT));
        
        $this->info("📄 Detailed report saved: {$filename}");
    }

    private function autoCreateCompositeRoles(array $suggestions)
    {
        $this->info('🤖 Auto-creating high-priority composite roles...');
        
        $created = 0;
        foreach ($suggestions as $suggestion) {
            if ($suggestion['priority'] === 'HIGH' && $suggestion['user_count'] >= 3) {
                try {
                    $compositeRole = self::createCompositeRole(
                        $suggestion['composite_name'],
                        $suggestion['base_roles'],
                        "Auto-generated composite role for {$suggestion['user_count']} users"
                    );
                    
                    $this->info("   ✅ Created: {$suggestion['composite_name']}");
                    
                    // Auto-migrate users
                    $migrated = self::migrateToCompositeRole(
                        $suggestion['composite_name'], 
                        $suggestion['base_roles']
                    );
                    
                    $this->info("      Migrated {$migrated} users");
                    $created++;
                    
                } catch (\Exception $e) {
                    $this->warn("   ❌ Failed to create {$suggestion['composite_name']}: {$e->getMessage()}");
                }
            }
        }
        
        $this->info("🎉 Auto-creation completed. Created {$created} composite roles.");
    }
}
