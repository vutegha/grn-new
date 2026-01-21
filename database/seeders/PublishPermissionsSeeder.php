<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PublishPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Définir toutes les permissions de publication
        $publishPermissions = [
            // Services
            'publish services',
            'unpublish services',
            
            // Actualités
            'publish actualites',
            'unpublish actualites',
            
            // Projets
            'publish projets',
            'unpublish projets',
            
            // Événements
            'publish evenements',
            'unpublish evenements',
            
            // Rapports
            'publish rapports',
            'unpublish rapports',
        ];

        // Créer toutes les permissions
        foreach ($publishPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Assigner toutes les permissions au rôle super admin
        $superAdminRole = Role::where('name', 'super admin')->first();
        
        if ($superAdminRole) {
            foreach ($publishPermissions as $permission) {
                if (!$superAdminRole->hasPermissionTo($permission)) {
                    $superAdminRole->givePermissionTo($permission);
                }
            }
        }

        $this->command->info('Permissions de publication créées et assignées au super admin.');
    }
}
