<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Réinitialiser le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions
        $permissions = [
            // Permissions système
            'access admin',
            'manage system',
            'view logs',
            
            // Permissions utilisateurs
            'manage users',
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Permissions services
            'manage services',
            'view services',
            'create services',
            'edit services',
            'delete services',
            'moderate services',
            'toggle service menu',
            
            // Permissions actualités
            'manage actualites',
            'view actualites',
            'create actualites',
            'edit actualites',
            'delete actualites',
            'moderate actualites',
            
            // Permissions publications
            'manage publications',
            'view publications',
            'create publications',
            'edit publications',
            'delete publications',
            'moderate publications',
            
            // Permissions événements
            'manage evenements',
            'view evenements',
            'create evenements',
            'edit evenements',
            'delete evenements',
            'moderate evenements',
            
            // Permissions projets
            'manage projets',
            'view projets',
            'create projets',
            'edit projets',
            'delete projets',
            'moderate projets',
            
            // Permissions rapports
            'manage_rapports',
            'view_rapports',
            'create_rapports',
            'edit_rapports',
            'delete_rapports',
            'moderate_rapports',
            
            // Permissions médias
            'manage media',
            'upload media',
            'delete media',
            
            // Permissions newsletter
            'manage newsletter',
            'send newsletter',
            'view newsletter stats',
            
            // Permissions contacts
            'view contacts',
            'manage contacts',
            'respond contacts',
            
            // Permissions emplois
            'manage jobs',
            'view job applications',
            'manage job applications',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer les rôles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $moderatorRole = Role::firstOrCreate(['name' => 'moderator']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Assigner toutes les permissions à l'administrateur
        $adminRole->givePermissionTo(Permission::all());

        // Assigner les permissions aux modérateurs
        $moderatorPermissions = [
            'access admin',
            'view users',
            'view services', 'edit services', 'moderate services', 'toggle service menu',
            'manage actualites', 'moderate actualites',
            'manage publications', 'moderate publications',
            'manage evenements', 'moderate evenements',
            'manage projets', 'moderate projets',
            'manage_rapports', 'moderate_rapports',
            'manage media', 'upload media',
            'view contacts', 'respond contacts',
            'view job applications',
        ];
        $moderatorRole->givePermissionTo($moderatorPermissions);

        // Assigner les permissions aux éditeurs
        $editorPermissions = [
            'access admin',
            'view services', 'create services', 'edit services',
            'view actualites', 'create actualites', 'edit actualites',
            'view publications', 'create publications', 'edit publications',
            'view evenements', 'create evenements', 'edit evenements',
            'view projets', 'create projets', 'edit projets',
            'view_rapports', 'create_rapports', 'edit_rapports',
            'upload media',
            'view contacts',
        ];
        $editorRole->givePermissionTo($editorPermissions);

        // Permissions de base pour les utilisateurs
        $userPermissions = [
            'view services',
            'view actualites',
            'view publications',
            'view evenements',
            'view projets',
        ];
        $userRole->givePermissionTo($userPermissions);

        $this->command->info('Rôles et permissions créés avec succès!');
    }
}
