<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SocialLinksPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Créer les permissions pour les liens sociaux
        $permissions = [
            'view_social_links',
            'create_social_links', 
            'update_social_links',
            'delete_social_links',
            'moderate_social_links',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assigner les permissions aux rôles
        $superAdmin = Role::where('name', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }

        $admin = Role::where('name', 'admin')->first(); 
        if ($admin) {
            $admin->givePermissionTo([
                'view_social_links',
                'create_social_links',
                'update_social_links',
                'moderate_social_links'
            ]);
        }

        $moderator = Role::where('name', 'moderator')->first();
        if ($moderator) {
            $moderator->givePermissionTo([
                'view_social_links',
                'moderate_social_links'
            ]);
        }

        echo "✅ Permissions des liens sociaux créées et assignées avec succès !\n";
    }
}
