<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions pour les services
        $permissions = [
            'manage-services' => 'Gérer les services (créer, modifier, supprimer)',
            'moderate-services' => 'Modérer les services (approuver, dépublier, gérer les menus)',
            'view-services' => 'Voir les services'
        ];

        foreach ($permissions as $permission => $description) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Créer les rôles
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        $moderatorRole = Role::firstOrCreate([
            'name' => 'moderator',
            'guard_name' => 'web'
        ]);

        $editorRole = Role::firstOrCreate([
            'name' => 'editor',
            'guard_name' => 'web'
        ]);

        // Assigner les permissions aux rôles
        $adminRole->givePermissionTo(array_keys($permissions));
        $moderatorRole->givePermissionTo(['moderate-services', 'view-services']);
        $editorRole->givePermissionTo(['manage-services', 'view-services']);

        // Assigner le rôle admin au premier utilisateur s'il existe
        $firstUser = User::first();
        if ($firstUser && !$firstUser->hasAnyRole(['admin', 'moderator', 'editor'])) {
            $firstUser->assignRole('admin');
            $this->command->info("Rôle 'admin' assigné à l'utilisateur: {$firstUser->email}");
        }

        $this->command->info('Permissions et rôles créés avec succès !');
    }
}
