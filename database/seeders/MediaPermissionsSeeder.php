<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MediaPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions pour les médias
        $mediaPermissions = [
            'media.view',
            'media.create', 
            'media.edit',
            'media.delete',
            'media.moderate',
            'media.publish',
            'media.download',
        ];

        foreach ($mediaPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Assigner les permissions aux rôles existants
        
        // Super Admin - toutes les permissions
        $superAdmin = Role::where('name', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions($mediaPermissions);
        }

        // Admin - toutes les permissions
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->givePermissionTo($mediaPermissions);
        }

        // Moderator - permissions de modération
        $moderator = Role::where('name', 'moderator')->first();
        if ($moderator) {
            $moderator->givePermissionTo([
                'media.view',
                'media.moderate',
                'media.download',
            ]);
        }

        // Editor - permissions de création et édition
        $editor = Role::where('name', 'editor')->first();
        if ($editor) {
            $editor->givePermissionTo([
                'media.view',
                'media.create',
                'media.edit',
                'media.download',
            ]);
        }

        // User - permissions de base
        $user = Role::where('name', 'user')->first();
        if ($user) {
            $user->givePermissionTo([
                'media.view',
                'media.download',
            ]);
        }

        $this->command->info('Permissions des médias créées et assignées avec succès !');
    }
}
