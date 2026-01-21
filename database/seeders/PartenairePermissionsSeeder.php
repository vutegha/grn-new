<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PartenairePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions pour les partenaires
        $permissions = [
            'view_partenaires',
            'create_partenaires', 
            'update_partenaires',
            'delete_partenaires',
            'moderate_partenaires'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $this->command->info("Permission créée : {$permission}");
        }

        // Assigner les permissions aux rôles
        $rolePermissions = [
            'super-admin' => ['view_partenaires', 'create_partenaires', 'update_partenaires', 'delete_partenaires', 'moderate_partenaires'],
            'admin' => ['view_partenaires', 'create_partenaires', 'update_partenaires', 'delete_partenaires', 'moderate_partenaires'],
            'moderator' => ['view_partenaires', 'create_partenaires', 'update_partenaires', 'moderate_partenaires'],
            'editor' => ['view_partenaires', 'create_partenaires', 'update_partenaires'],
            'contributor' => ['view_partenaires']
        ];

        foreach ($rolePermissions as $roleName => $rolePermissionsList) {
            $role = Role::where('name', $roleName)->first();
            
            if ($role) {
                foreach ($rolePermissionsList as $permissionName) {
                    $permission = Permission::where('name', $permissionName)->first();
                    if ($permission && !$role->hasPermissionTo($permission)) {
                        $role->givePermissionTo($permission);
                        $this->command->info("Permission '{$permissionName}' assignée au rôle '{$roleName}'");
                    }
                }
            } else {
                $this->command->warn("Rôle '{$roleName}' non trouvé");
            }
        }

        $this->command->info("Toutes les permissions pour les partenaires ont été créées et assignées !");
    }
}
