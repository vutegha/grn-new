<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EmailSettingsPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔧 Ajout de la permission manage_email_settings...');

        // Créer la permission si elle n'existe pas
        $permission = Permission::firstOrCreate([
            'name' => 'manage_email_settings',
            'guard_name' => 'web'
        ]);

        $this->command->info('✅ Permission créée: ' . $permission->name);

        // Attribuer la permission au rôle super_admin
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo('manage_email_settings');
            $this->command->info('✅ Permission attribuée au rôle super_admin');
        }

        // Attribuer la permission au rôle admin
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->givePermissionTo('manage_email_settings');
            $this->command->info('✅ Permission attribuée au rôle admin');
        }

        $this->command->info('🎉 Configuration des emails accessible !');
    }
}
