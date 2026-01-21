<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SetupModerationRoles extends Command
{
    protected $signature = 'moderation:setup-roles';
    protected $description = 'Configure les rôles et permissions pour le système de modération';

    public function handle()
    {
        $this->info('🚀 Configuration des rôles et permissions de modération...');

        // Créer les permissions
        $permissions = [
            'moderate_content',
            'publish_content',
            'unpublish_content',
            'manage_users',
            'manage_roles'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $this->info("✅ Permission créée: {$permission}");
        }

        // Créer les rôles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $moderatorRole = Role::firstOrCreate(['name' => 'moderator']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);

        // Assigner les permissions aux rôles
        $adminRole->syncPermissions($permissions);
        $moderatorRole->syncPermissions(['moderate_content', 'publish_content', 'unpublish_content']);
        $editorRole->syncPermissions(['publish_content']);

        $this->info("✅ Rôles créés et permissions assignées");

        // Assigner le rôle admin à l'utilisateur iri@ucbc.org
        $adminUser = User::where('email', 'iri@ucbc.org')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
            $this->info("✅ Rôle admin assigné à {$adminUser->email}");
        } else {
            $this->warn("⚠️ Utilisateur iri@ucbc.org non trouvé");
        }

        $this->info('🎉 Configuration terminée !');
        
        // Test de validation
        $this->info("\n📊 Validation:");
        $users = User::all();
        foreach ($users as $user) {
            $canModerate = $user->canModerate() ? 'Oui' : 'Non';
            $roles = $user->getRoleNames()->implode(', ') ?: 'Aucun';
            $this->info("  - {$user->name} ({$user->email}): Modérer={$canModerate}, Rôles={$roles}");
        }
    }
}
