<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MissingPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔍 Vérification des permissions manquantes...');

        // Permissions pour Publications
        $publicationPermissions = [
            'view_publications',
            'view_publication',
            'create_publication',
            'update_publication',
            'update_publications', // Variante pluriel utilisée dans les vues
            'delete_publication',
            'delete_publications', // Variante pluriel utilisée dans les vues
            'publish_publication',
            'unpublish_publication',
            'moderate_publication',
        ];

        // Permissions pour Contact Info
        $contactInfoPermissions = [
            'view_contact_infos',
            'view_contact_info',
            'create_contact_info',
            'update_contact_info',
            'delete_contact_info',
        ];

        // Autres permissions manquantes
        $otherPermissions = [
            'manage_email_settings',
            'manage_newsletter',
        ];

        $allPermissions = array_merge(
            $publicationPermissions,
            $contactInfoPermissions,
            $otherPermissions
        );

        $created = 0;
        $existing = 0;

        foreach ($allPermissions as $permissionName) {
            $permission = Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web'
            ]);

            if ($permission->wasRecentlyCreated) {
                $this->command->info("  ✅ Créée: {$permissionName}");
                $created++;
            } else {
                $existing++;
            }
        }

        $this->command->info("\n📊 Résumé:");
        $this->command->info("  - {$created} permissions créées");
        $this->command->info("  - {$existing} permissions existantes");

        // Attribuer toutes les permissions au super_admin
        $this->command->info("\n👑 Attribution des permissions aux rôles...");
        
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions(Permission::all());
            $this->command->info("  ✅ super_admin: toutes les permissions");
        }

        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            // Admin a toutes les permissions sauf manage_users et manage_roles
            $adminPermissions = Permission::whereNotIn('name', [
                'manage_users',
                'manage_roles',
                'manage_permissions'
            ])->get();
            $admin->syncPermissions($adminPermissions);
            $this->command->info("  ✅ admin: permissions étendues");
        }

        $this->command->info("\n🎉 Configuration terminée!");
    }
}
