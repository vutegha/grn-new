<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class HarmonizePermissionsPluralSeeder extends Seeder
{
    /**
     * Mapping des permissions singulières vers plurielles
     */
    private array $permissionMappings = [
        // Actualités
        'create_actualite' => 'create_actualites',
        'view_actualites' => 'view_actualites', // déjà pluriel
        'update_actualite' => 'update_actualites',
        'delete_actualite' => 'delete_actualites',
        'moderate_actualites' => 'moderate_actualites', // déjà pluriel
        'publish_actualites' => 'publish_actualites', // déjà pluriel
        'unpublish_actualites' => 'unpublish_actualites', // déjà pluriel
        
        // Projets
        'create_projet' => 'create_projets',
        'view_projets' => 'view_projets', // déjà pluriel
        'update_projet' => 'update_projets',
        'delete_projet' => 'delete_projets',
        'moderate_projets' => 'moderate_projets', // déjà pluriel
        'publish_projets' => 'publish_projets', // déjà pluriel
        'unpublish_projets' => 'unpublish_projets', // déjà pluriel
        
        // Publications
        'create_publication' => 'create_publications',
        'view_publications' => 'view_publications', // déjà pluriel
        'update_publication' => 'update_publications',
        'delete_publication' => 'delete_publications',
        'moderate_publications' => 'moderate_publications', // déjà pluriel
        'publish_publications' => 'publish_publications', // déjà pluriel
        'unpublish_publications' => 'unpublish_publications', // déjà pluriel
        
        // Événements
        'create_evenement' => 'create_evenements',
        'view_evenements' => 'view_evenements', // déjà pluriel
        'update_evenement' => 'update_evenements',
        'delete_evenement' => 'delete_evenements',
        'moderate_evenements' => 'moderate_evenements', // déjà pluriel
        'publish_evenements' => 'publish_evenements', // déjà pluriel
        'unpublish_evenements' => 'unpublish_evenements', // déjà pluriel
        
        // Services
        'create_service' => 'create_services',
        'view_services' => 'view_services', // déjà pluriel
        'update_service' => 'update_services',
        'delete_service' => 'delete_services',
        'moderate_services' => 'moderate_services', // déjà pluriel
        'publish_services' => 'publish_services', // déjà pluriel
        'unpublish_services' => 'unpublish_services', // déjà pluriel
        
        // Media
        'create_media' => 'create_medias',
        'view_media' => 'view_medias',
        'update_media' => 'update_medias',
        'delete_media' => 'delete_medias',
        'moderate_media' => 'moderate_medias',
        'approve_media' => 'approve_medias',
        'reject_media' => 'reject_medias',
        'publish_media' => 'publish_medias',
        'download_media' => 'download_medias',
        
        // Auteurs
        'create_auteur' => 'create_auteurs',
        'view_auteurs' => 'view_auteurs', // déjà pluriel
        'update_auteur' => 'update_auteurs',
        'delete_auteur' => 'delete_auteurs',
        'export_auteurs' => 'export_auteurs', // déjà pluriel
        
        // Rapports
        'create_rapport' => 'create_rapports',
        'view_rapports' => 'view_rapports', // déjà pluriel
        'update_rapport' => 'update_rapports',
        'delete_rapport' => 'delete_rapports',
        'moderate_rapports' => 'moderate_rapports', // déjà pluriel
        'publish_rapports' => 'publish_rapports', // déjà pluriel
        
        // Utilisateurs
        'create_user' => 'create_users',
        'view_users' => 'view_users', // déjà pluriel
        'update_user' => 'update_users',
        'delete_user' => 'delete_users',
        'manage_users' => 'manage_users', // déjà pluriel
    ];

    public function run(): void
    {
        $this->command->info("🔄 Harmonisation des permissions vers format PLURIEL...");
        
        // 1. Mettre à jour les permissions en base de données
        $this->updateDatabasePermissions();
        
        // 2. Réassigner les permissions aux rôles
        $this->reassignPermissionsToRoles();
        
        $this->command->info("🎉 Harmonisation PLURIEL terminée avec succès!");
    }
    
    /**
     * Met à jour les permissions en base de données
     */
    private function updateDatabasePermissions(): void
    {
        $this->command->info("📊 Mise à jour des permissions en base de données...");
        
        $updatedCount = 0;
        $createdCount = 0;
        
        foreach ($this->permissionMappings as $oldName => $newName) {
            // Si les noms sont identiques, on skip
            if ($oldName === $newName) {
                continue;
            }
            
            // Vérifier si l'ancienne permission existe
            $oldPermission = Permission::where('name', $oldName)->where('guard_name', 'web')->first();
            
            if ($oldPermission) {
                // Vérifier si la nouvelle permission existe déjà
                $newPermission = Permission::where('name', $newName)->where('guard_name', 'web')->first();
                
                if (!$newPermission) {
                    // Créer la nouvelle permission
                    $newPermission = Permission::create([
                        'name' => $newName,
                        'guard_name' => 'web'
                    ]);
                    $createdCount++;
                    $this->command->info("  ✅ Créé: $newName");
                } else {
                    $this->command->info("  ℹ️ Existe déjà: $newName");
                }
                
                // Transférer les associations des rôles
                $roleIds = DB::table('role_has_permissions')
                    ->where('permission_id', $oldPermission->id)
                    ->pluck('role_id');
                
                foreach ($roleIds as $roleId) {
                    // Vérifier si l'association existe déjà pour éviter les doublons
                    $exists = DB::table('role_has_permissions')
                        ->where('role_id', $roleId)
                        ->where('permission_id', $newPermission->id)
                        ->exists();
                    
                    if (!$exists) {
                        DB::table('role_has_permissions')->insert([
                            'role_id' => $roleId,
                            'permission_id' => $newPermission->id
                        ]);
                    }
                }
                
                // Transférer les associations directes des utilisateurs
                $userAssociations = DB::table('model_has_permissions')
                    ->where('permission_id', $oldPermission->id)
                    ->where('model_type', 'App\\Models\\User')
                    ->get();
                
                foreach ($userAssociations as $association) {
                    // Vérifier si l'association existe déjà
                    $exists = DB::table('model_has_permissions')
                        ->where('model_id', $association->model_id)
                        ->where('model_type', $association->model_type)
                        ->where('permission_id', $newPermission->id)
                        ->exists();
                    
                    if (!$exists) {
                        DB::table('model_has_permissions')->insert([
                            'permission_id' => $newPermission->id,
                            'model_type' => $association->model_type,
                            'model_id' => $association->model_id
                        ]);
                    }
                }
                
                // Supprimer l'ancienne permission et ses associations
                DB::table('role_has_permissions')->where('permission_id', $oldPermission->id)->delete();
                DB::table('model_has_permissions')->where('permission_id', $oldPermission->id)->delete();
                $oldPermission->delete();
                
                $updatedCount++;
                $this->command->info("  🔄 Migré: $oldName → $newName");
            }
        }
        
        $this->command->info("📈 Résultats:");
        $this->command->info("  - Permissions migrées: $updatedCount");
        $this->command->info("  - Permissions créées: $createdCount");
    }
    
    /**
     * Réassigne les permissions aux rôles selon la hiérarchie
     */
    private function reassignPermissionsToRoles(): void
    {
        $this->command->info("👥 Réassignation des permissions aux rôles...");
        
        // Définir les permissions par rôle avec les nouveaux noms pluriels
        $rolePermissions = [
            'super-admin' => 'all', // Toutes les permissions
            'admin' => [
                // Actualités
                'view_actualites', 'create_actualites', 'update_actualites', 'delete_actualites',
                'moderate_actualites', 'publish_actualites', 'unpublish_actualites',
                
                // Projets
                'view_projets', 'create_projets', 'update_projets', 'delete_projets',
                'moderate_projets', 'publish_projets', 'unpublish_projets',
                
                // Publications
                'view_publications', 'create_publications', 'update_publications', 'delete_publications',
                'moderate_publications', 'publish_publications', 'unpublish_publications',
                
                // Événements
                'view_evenements', 'create_evenements', 'update_evenements', 'delete_evenements',
                'moderate_evenements', 'publish_evenements', 'unpublish_evenements',
                
                // Services
                'view_services', 'create_services', 'update_services', 'delete_services',
                'moderate_services', 'publish_services', 'unpublish_services',
                
                // Media
                'view_medias', 'create_medias', 'update_medias', 'delete_medias',
                'moderate_medias', 'approve_medias', 'reject_medias', 'publish_medias', 'download_medias',
                
                // Administration
                'manage_users', 'access_admin', 'view_admin', 'manage_permissions', 'manage_roles',
            ],
            'moderator' => [
                'view_actualites', 'moderate_actualites', 'publish_actualites', 'unpublish_actualites',
                'view_projets', 'moderate_projets', 'publish_projets', 'unpublish_projets',
                'view_publications', 'moderate_publications', 'publish_publications', 'unpublish_publications',
                'view_evenements', 'moderate_evenements', 'publish_evenements', 'unpublish_evenements',
                'view_services', 'moderate_services', 'publish_services', 'unpublish_services',
                'view_medias', 'moderate_medias', 'approve_medias', 'reject_medias', 'publish_medias',
                'view_rapports', 'moderate_rapports', 'publish_rapports',
            ],
            'contributeur' => [
                'view_actualites', 'create_actualites', 'update_actualites',
                'view_projets', 'create_projets', 'update_projets',
                'view_publications', 'create_publications', 'update_publications',
                'view_evenements', 'create_evenements', 'update_evenements',
                'view_services', 'create_services', 'update_services',
                'view_medias', 'create_medias', 'update_medias',
            ],
            'user' => [
                'view_actualites', 'view_projets', 'view_publications', 
                'view_evenements', 'view_services', 'view_medias',
            ]
        ];
        
        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
            
            if (!$role) {
                $this->command->warn("⚠️ Rôle '$roleName' introuvable, création...");
                $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);
            }
            
            // Supprimer les anciennes permissions
            $role->permissions()->detach();
            
            if ($permissions === 'all') {
                // Super-admin obtient toutes les permissions
                $allPermissions = Permission::where('guard_name', 'web')->get();
                $role->givePermissionTo($allPermissions);
                $this->command->info("  👑 super-admin: " . $allPermissions->count() . " permissions assignées");
            } else {
                // Assigner les permissions spécifiques
                $validPermissions = [];
                foreach ($permissions as $permissionName) {
                    $permission = Permission::where('name', $permissionName)->where('guard_name', 'web')->first();
                    if ($permission) {
                        $validPermissions[] = $permission;
                    } else {
                        $this->command->warn("  ⚠️ Permission '$permissionName' introuvable pour rôle '$roleName'");
                    }
                }
                
                if (!empty($validPermissions)) {
                    $role->givePermissionTo($validPermissions);
                    $this->command->info("  🏷️ $roleName: " . count($validPermissions) . " permissions assignées");
                }
            }
        }
    }
}
