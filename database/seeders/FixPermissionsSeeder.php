<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class FixPermissionsSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🔧 Correction du système de permissions...');
        
        // Désactiver les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Nettoyer les permissions existantes
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_permissions')->delete();
        Permission::truncate();
        
        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Créer les permissions basées sur ce qui est utilisé dans les VUES
        $permissions = [
            // Actualités (basé sur les vues admin/actualite)
            'create actualites',
            'view actualites', 
            'update actualites',
            'delete actualites',
            'moderate actualites',
            'publish actualites',
            'unpublish actualites',
            
            // Projets (basé sur les vues admin/projets)
            'create projets',
            'view projets',
            'update projets', 
            'delete projets',
            'moderate projets',
            'publish projets',
            'unpublish projets',
            
            // Publications (basé sur les vues admin/publication)
            'create publications',
            'view publications',
            'update publications',
            'delete publications', 
            'moderate publications',
            'publish publications',
            'unpublish publications',
            
            // Événements (basé sur les vues admin/evenements)
            'create evenements',
            'view evenements',
            'update evenements',
            'delete evenements',
            'moderate evenements',
            'publish evenements', 
            'unpublish evenements',
            
            // Services (basé sur les vues admin/service)
            'create services',
            'view services',
            'update services', 
            'delete services',
            'moderate services',
            
            // Auteurs (basé sur les vues admin/auteurs)
            'create_author',
            'view_author',
            'edit_author',
            'delete_author',
            'export_authors',
            
            // Rapports (basé sur les vues admin/rapports)
            'create_rapport',
            'view_rapport',
            'update_rapport',
            'delete_rapport',
            'moderate_rapport',
            
            // Media (basé sur les vues admin/media)
            'view media',
            'update media',
            'delete media',
            'download media',
            'moderate media',
            'approve media',
            'reject media',
            'publish media',
            
            // Administration générale
            'view_admin_dashboard',
            'manage_users',
            'manage users',
            'access_admin',
            'manage_permissions',
            'manage_roles',
            'manage_services',
            'manage system',
            'view_dashboard',
            
            // Newsletter
            'manage_newsletter',
            'send_newsletter',
            'view_newsletter_stats',
            
            // Permissions génériques utilisées dans les vues
            'viewAny',
            'view',
            'create', 
            'update',
            'delete',
            'moderate',
            'publish',
            'approve',
            'reject',
            'download'
        ];
        
        $this->command->info('📝 Création de ' . count($permissions) . ' permissions...');
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
        
        // Récupérer les rôles existants
        $superAdmin = Role::where('name', 'super-admin')->first();
        $admin = Role::where('name', 'admin')->first();
        $moderator = Role::where('name', 'moderator')->first();
        $gestionnaire = Role::where('name', 'gestionnaire_projets')->first();
        $contributeur = Role::where('name', 'contributeur')->first();
        $user = Role::where('name', 'user')->first();
        
        if ($superAdmin) {
            // Super-admin: TOUTES les permissions
            $superAdmin->syncPermissions(Permission::all());
            $this->command->info('✅ Super-admin: toutes permissions assignées');
        }
        
        if ($admin) {
            // Admin: tout sauf manage system
            $adminPermissions = Permission::where('name', '!=', 'manage system')->get();
            $admin->syncPermissions($adminPermissions);
            $this->command->info('✅ Admin: permissions assignées');
        }
        
        if ($moderator) {
            // Modérateur: modération et visualisation
            $moderatorPerms = Permission::where('name', 'like', '%moderate%')
                ->orWhere('name', 'like', '%view%')
                ->orWhere('name', 'like', '%publish%')
                ->orWhere('name', 'like', '%unpublish%')
                ->orWhere('name', 'like', '%approve%')
                ->orWhere('name', 'like', '%reject%')
                ->orWhere('name', 'access_admin')
                ->get();
            $moderator->syncPermissions($moderatorPerms);
            $this->command->info('✅ Modérateur: permissions assignées');
        }
        
        if ($gestionnaire) {
            // Gestionnaire projets: gestion complète des projets
            $gestionnairePerms = Permission::where('name', 'like', '%projets%')
                ->orWhere('name', 'like', '%projet%')
                ->orWhere('name', 'view_dashboard')
                ->orWhere('name', 'access_admin')
                ->get();
            $gestionnaire->syncPermissions($gestionnairePerms);
            $this->command->info('✅ Gestionnaire projets: permissions assignées');
        }
        
        if ($contributeur) {
            // Contributeur: création et mise à jour de contenu
            $contributeurPerms = Permission::where('name', 'like', '%create%')
                ->orWhere('name', 'like', '%update%')
                ->orWhere('name', 'like', '%view%')
                ->where('name', 'not like', '%delete%')
                ->where('name', 'not like', '%manage%')
                ->get();
            $contributeur->syncPermissions($contributeurPerms);
            $this->command->info('✅ Contributeur: permissions assignées');
        }
        
        if ($user) {
            // Utilisateur simple: lecture seulement
            $userPerms = Permission::where('name', 'like', '%view%')->get();
            $user->syncPermissions($userPerms);
            $this->command->info('✅ User: permissions assignées');
        }
        
        $this->command->info('🎉 Système de permissions corrigé avec succès!');
        $this->command->info('📊 Total: ' . Permission::count() . ' permissions créées');
    }
}
