<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class HarmonizePermissionsSnakeCaseSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🔄 Harmonisation complète des permissions en snake_case...');
        
        // Désactiver les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Nettoyer les permissions existantes
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_permissions')->delete();
        Permission::truncate();
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Créer les permissions en format snake_case strict
        $permissions = [
            // Actualités
            'view_actualites',
            'create_actualite',
            'update_actualite', 
            'delete_actualite',
            'moderate_actualites',
            'publish_actualites',
            'unpublish_actualites',
            
            // Projets
            'view_projets',
            'create_projet',
            'update_projet',
            'delete_projet', 
            'moderate_projets',
            'publish_projets',
            'unpublish_projets',
            
            // Publications
            'view_publications',
            'create_publication',
            'update_publication',
            'delete_publication',
            'moderate_publications', 
            'publish_publications',
            'unpublish_publications',
            
            // Événements
            'view_evenements',
            'create_evenement',
            'update_evenement',
            'delete_evenement',
            'moderate_evenements',
            'publish_evenements',
            'unpublish_evenements',
            
            // Services
            'view_services',
            'create_service',
            'update_service',
            'delete_service',
            'moderate_services',
            'publish_services',
            'unpublish_services',
            
            // Auteurs
            'view_auteurs',
            'create_auteur',
            'update_auteur',
            'delete_auteur',
            'export_auteurs',
            
            // Rapports
            'view_rapports',
            'create_rapport',
            'update_rapport',
            'delete_rapport',
            'moderate_rapports',
            'publish_rapports',
            
            // Media
            'view_media',
            'create_media',
            'update_media',
            'delete_media',
            'moderate_media',
            'approve_media',
            'reject_media',
            'publish_media',
            'download_media',
            
            // Utilisateurs
            'view_users',
            'create_user',
            'update_user',
            'delete_user',
            'manage_users',
            
            // Administration
            'access_admin',
            'view_admin_dashboard',
            'view_dashboard',
            'manage_permissions',
            'manage_roles',
            'manage_services',
            'manage_system',
            
            // Newsletter
            'manage_newsletter',
            'send_newsletter',
            'view_newsletter_stats'
        ];
        
        $this->command->info('📝 Création de ' . count($permissions) . ' permissions en snake_case...');
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
        
        // Attribution des permissions aux rôles
        $this->assignPermissionsToRoles();
        
        $this->command->info('🎉 Harmonisation terminée!');
        $this->command->info('📊 Total: ' . Permission::count() . ' permissions créées en snake_case');
    }
    
    private function assignPermissionsToRoles()
    {
        $superAdmin = Role::where('name', 'super-admin')->first();
        $admin = Role::where('name', 'admin')->first();
        $moderator = Role::where('name', 'moderator')->first();
        $gestionnaire = Role::where('name', 'gestionnaire_projets')->first();
        $contributeur = Role::where('name', 'contributeur')->first();
        $user = Role::where('name', 'user')->first();
        
        if ($superAdmin) {
            $superAdmin->syncPermissions(Permission::all());
            $this->command->info('✅ Super-admin: toutes permissions assignées');
        }
        
        if ($admin) {
            $adminPermissions = Permission::where('name', '!=', 'manage_system')->get();
            $admin->syncPermissions($adminPermissions);
            $this->command->info('✅ Admin: permissions assignées');
        }
        
        if ($moderator) {
            $moderatorPerms = Permission::where('name', 'like', '%moderate_%')
                ->orWhere('name', 'like', '%view_%')
                ->orWhere('name', 'like', '%publish_%')
                ->orWhere('name', 'like', '%unpublish_%')
                ->orWhere('name', 'like', '%approve_%')
                ->orWhere('name', 'like', '%reject_%')
                ->orWhere('name', 'access_admin')
                ->get();
            $moderator->syncPermissions($moderatorPerms);
            $this->command->info('✅ Modérateur: permissions assignées');
        }
        
        if ($gestionnaire) {
            $gestionnairePerms = Permission::where('name', 'like', '%projet%')
                ->orWhere('name', 'view_dashboard')
                ->orWhere('name', 'access_admin')
                ->get();
            $gestionnaire->syncPermissions($gestionnairePerms);
            $this->command->info('✅ Gestionnaire projets: permissions assignées');
        }
        
        if ($contributeur) {
            $contributeurPerms = Permission::where('name', 'like', '%create_%')
                ->orWhere('name', 'like', '%update_%')
                ->orWhere('name', 'like', '%view_%')
                ->where('name', 'not like', '%delete_%')
                ->where('name', 'not like', '%manage_%')
                ->get();
            $contributeur->syncPermissions($contributeurPerms);
            $this->command->info('✅ Contributeur: permissions assignées');
        }
        
        if ($user) {
            $userPerms = Permission::where('name', 'like', '%view_%')->get();
            $user->syncPermissions($userPerms);
            $this->command->info('✅ User: permissions assignées');
        }
    }
}
