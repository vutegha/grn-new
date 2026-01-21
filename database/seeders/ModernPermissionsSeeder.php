<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class ModernPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Utilise l'approche action_model (ex: create_projet, update_service)
     */
    public function run(): void
    {
        $this->command->info('🚀 Création des permissions avec approche action_model...');

        DB::beginTransaction();

        try {
            // Reset cached roles and permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Définir les modèles et leurs actions
            $models = [
                'service' => ['view', 'create', 'update', 'delete', 'publish', 'unpublish', 'moderate', 'archive', 'restore'],
                'projet' => ['view', 'create', 'update', 'delete', 'publish', 'unpublish', 'moderate', 'archive', 'restore', 'export', 'view_statistics'],
                'actualite' => ['view', 'create', 'update', 'delete', 'publish', 'unpublish', 'moderate', 'archive', 'restore'],
                'rapport' => ['view', 'create', 'update', 'delete', 'publish', 'unpublish', 'download', 'archive'],
                'media' => ['view', 'upload', 'update', 'delete', 'manage_library'],
                'categorie' => ['view', 'create', 'update', 'delete'],
                'user' => ['view', 'create', 'update', 'delete', 'ban', 'unban'],
                'role' => ['view', 'create', 'update', 'delete', 'assign'],
            ];

            // Permissions spéciales
            $specialPermissions = [
                'access_admin' => 'Accéder à l\'interface d\'administration',
                'view_dashboard' => 'Voir le tableau de bord',
                'view_analytics' => 'Voir les statistiques globales',
                'manage_settings' => 'Gérer les paramètres du site',
                'manage_newsletter' => 'Gérer la newsletter',
            ];

            $allPermissions = [];

            // Créer les permissions pour chaque modèle
            foreach ($models as $model => $actions) {
                foreach ($actions as $action) {
                    $permissionName = "{$action}_{$model}";
                    $allPermissions[] = $permissionName;
                    
                    Permission::firstOrCreate(
                        ['name' => $permissionName, 'guard_name' => 'web'],
                        ['name' => $permissionName, 'guard_name' => 'web']
                    );
                    $this->command->info("  ✅ {$permissionName}");
                }
            }

            // Créer les permissions spéciales
            foreach ($specialPermissions as $name => $description) {
                $allPermissions[] = $name;
                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web'],
                    ['name' => $name, 'guard_name' => 'web']
                );
                $this->command->info("  ✅ {$name}");
            }

            // Mettre à jour les rôles avec les nouvelles permissions
            $this->assignPermissionsToRoles();

            DB::commit();

            $this->command->info('🎉 Permissions créées avec succès !');
            $this->command->info('📊 Total permissions: ' . Permission::count());

        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error("❌ Erreur: {$e->getMessage()}");
            $this->command->error($e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Assigner les permissions aux rôles
     */
    private function assignPermissionsToRoles()
    {
        $this->command->info('👑 Attribution des permissions aux rôles...');

        // Super Admin - Toutes les permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());
        $this->command->info("  ✅ super-admin: " . Permission::count() . " permissions");

        // Admin - Toutes les permissions sauf gestion des rôles
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminPermissions = Permission::where('name', 'not like', '%_role')
            ->pluck('name')
            ->toArray();
        $admin->syncPermissions($adminPermissions);
        $this->command->info("  ✅ admin: " . count($adminPermissions) . " permissions");

        // Moderator - Permissions de modération et publication
        $moderator = Role::firstOrCreate(['name' => 'moderator', 'guard_name' => 'web']);
        $moderatorPermissions = [
            // Général
            'access_admin',
            'view_dashboard',
            'view_analytics',
            
            // Services
            'view_service',
            'update_service',
            'publish_service',
            'unpublish_service',
            'moderate_service',
            
            // Projets
            'view_projet',
            'update_projet',
            'publish_projet',
            'unpublish_projet',
            'moderate_projet',
            'view_statistics_projet',
            
            // Actualités
            'view_actualite',
            'update_actualite',
            'publish_actualite',
            'unpublish_actualite',
            'moderate_actualite',
            
            // Rapports
            'view_rapport',
            'update_rapport',
            'publish_rapport',
            'unpublish_rapport',
            'download_rapport',
            
            // Médias
            'view_media',
            'upload_media',
            'manage_library_media',
            
            // Catégories
            'view_categorie',
            'update_categorie',
            
            // Utilisateurs (lecture seule)
            'view_user',
        ];
        $moderator->syncPermissions($moderatorPermissions);
        $this->command->info("  ✅ moderator: " . count($moderatorPermissions) . " permissions");

        // Editor - Permissions de création et édition
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editorPermissions = [
            // Général
            'access_admin',
            'view_dashboard',
            
            // Services
            'view_service',
            'create_service',
            'update_service',
            
            // Projets
            'view_projet',
            'create_projet',
            'update_projet',
            'view_statistics_projet',
            
            // Actualités
            'view_actualite',
            'create_actualite',
            'update_actualite',
            
            // Rapports
            'view_rapport',
            'create_rapport',
            'update_rapport',
            'download_rapport',
            
            // Médias
            'view_media',
            'upload_media',
            
            // Catégories
            'view_categorie',
            'create_categorie',
            'update_categorie',
        ];
        $editor->syncPermissions($editorPermissions);
        $this->command->info("  ✅ editor: " . count($editorPermissions) . " permissions");

        // Contributor - Contributeur externe (création uniquement)
        $contributor = Role::firstOrCreate(['name' => 'contributor', 'guard_name' => 'web']);
        $contributorPermissions = [
            // Général
            'access_admin',
            'view_dashboard',
            
            // Services (lecture seule)
            'view_service',
            
            // Projets (lecture seule)
            'view_projet',
            
            // Actualités (création et lecture)
            'view_actualite',
            'create_actualite',
            
            // Rapports (lecture et téléchargement)
            'view_rapport',
            'download_rapport',
            
            // Médias (upload seulement)
            'view_media',
            'upload_media',
        ];
        $contributor->syncPermissions($contributorPermissions);
        $this->command->info("  ✅ contributor: " . count($contributorPermissions) . " permissions");

        // Viewer - Lecture seule
        $viewer = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);
        $viewerPermissions = [
            'access_admin',
            'view_dashboard',
            'view_service',
            'view_projet',
            'view_statistics_projet',
            'view_actualite',
            'view_rapport',
            'download_rapport',
            'view_media',
            'view_categorie',
            'view_user',
        ];
        $viewer->syncPermissions($viewerPermissions);
        $this->command->info("  ✅ viewer: " . count($viewerPermissions) . " permissions");
    }
}
