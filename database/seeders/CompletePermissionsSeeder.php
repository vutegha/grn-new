<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class CompletePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Ajout des permissions manquantes...');

        DB::beginTransaction();

        try {
            // Reset cached roles and permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Permissions générales
            $generalPermissions = [
                'view admin' => 'Accéder à l\'interface d\'administration',
            ];

            // Permissions complètes pour Services
            $servicePermissions = [
                'view services' => 'Voir les services',
                'create services' => 'Créer des services',
                'update services' => 'Modifier les services',
                'delete services' => 'Supprimer les services',
                'publish services' => 'Publier les services',
                'unpublish services' => 'Dépublier les services',
                'moderate services' => 'Modérer les services',
                'archive services' => 'Archiver les services',
                'restore services' => 'Restaurer les services',
            ];

            // Permissions complètes pour Projets
            $projetPermissions = [
                'view projets' => 'Voir les projets',
                'create projets' => 'Créer des projets',
                'update projets' => 'Modifier les projets',
                'delete projets' => 'Supprimer les projets',
                'publish projets' => 'Publier les projets',
                'unpublish projets' => 'Dépublier les projets',
                'moderate projets' => 'Modérer les projets',
                'archive projets' => 'Archiver les projets',
                'restore projets' => 'Restaurer les projets',
                'view projet statistics' => 'Voir les statistiques des projets',
                'export projets' => 'Exporter les projets',
            ];

            // Permissions pour Rapports (liés aux projets et actualités)
            $rapportPermissions = [
                'view rapports' => 'Voir les rapports',
                'create rapports' => 'Créer des rapports',
                'update rapports' => 'Modifier les rapports',
                'delete rapports' => 'Supprimer les rapports',
                'publish rapports' => 'Publier les rapports',
                'unpublish rapports' => 'Dépublier les rapports',
                'download rapports' => 'Télécharger les rapports',
            ];

            // Permissions pour Médias
            $mediaPermissions = [
                'view medias' => 'Voir les médias',
                'upload medias' => 'Téléverser des médias',
                'delete medias' => 'Supprimer des médias',
                'manage media library' => 'Gérer la bibliothèque de médias',
            ];

            // Permissions pour Catégories
            $categoriePermissions = [
                'view categories' => 'Voir les catégories',
                'create categories' => 'Créer des catégories',
                'update categories' => 'Modifier les catégories',
                'delete categories' => 'Supprimer des catégories',
            ];

            // Fusionner toutes les permissions
            $allPermissions = array_merge(
                $generalPermissions,
                $servicePermissions,
                $projetPermissions,
                $rapportPermissions,
                $mediaPermissions,
                $categoriePermissions
            );

            // Créer les permissions
            foreach ($allPermissions as $name => $description) {
                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web'],
                    ['name' => $name, 'guard_name' => 'web']
                );
                $this->command->info("  ✅ Permission: {$name}");
            }

            // Mettre à jour les rôles avec les nouvelles permissions
            $this->updateRolePermissions();

            DB::commit();

            $this->command->info('🎉 Permissions ajoutées et rôles mis à jour avec succès !');
            $this->command->info('📊 Total permissions: ' . Permission::count());

        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error("❌ Erreur: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Mettre à jour les permissions des rôles
     */
    private function updateRolePermissions()
    {
        $this->command->info('👑 Mise à jour des permissions par rôle...');

        // Super Admin - Toutes les permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());
        $this->command->info("  ✅ super-admin: toutes les permissions (" . Permission::count() . ")");

        // Admin - Toutes les permissions sauf gestion des rôles
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminPermissions = Permission::whereNotIn('name', [
            'create roles', 
            'update roles', 
            'delete roles', 
            'assign roles'
        ])->pluck('name')->toArray();
        $admin->syncPermissions($adminPermissions);
        $this->command->info("  ✅ admin: " . count($adminPermissions) . " permissions");

        // Moderator - Permissions de modération, publication et visualisation
        $moderator = Role::firstOrCreate(['name' => 'moderator', 'guard_name' => 'web']);
        $moderatorPermissions = [
            'view admin',
            // Services
            'view services', 'update services', 'publish services', 'unpublish services', 'moderate services',
            // Actualités
            'view actualites', 'update actualites', 'publish actualites', 'unpublish actualites', 'moderate actualites',
            // Projets
            'view projets', 'update projets', 'publish projets', 'unpublish projets', 'moderate projets', 'view projet statistics',
            // Rapports
            'view rapports', 'update rapports', 'publish rapports', 'unpublish rapports', 'download rapports',
            // Médias
            'view medias', 'upload medias', 'manage media library',
            // Catégories
            'view categories', 'update categories',
        ];
        $moderator->syncPermissions($moderatorPermissions);
        $this->command->info("  ✅ moderator: " . count($moderatorPermissions) . " permissions");

        // Editor - Permissions de création et édition (pas de publication)
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editorPermissions = [
            'view admin',
            // Services
            'view services', 'create services', 'update services',
            // Actualités
            'view actualites', 'create actualites', 'update actualites',
            // Projets
            'view projets', 'create projets', 'update projets', 'view projet statistics',
            // Rapports
            'view rapports', 'create rapports', 'update rapports', 'download rapports',
            // Médias
            'view medias', 'upload medias',
            // Catégories
            'view categories', 'create categories', 'update categories',
        ];
        $editor->syncPermissions($editorPermissions);
        $this->command->info("  ✅ editor: " . count($editorPermissions) . " permissions");

        // Viewer - Permissions de lecture seulement
        $viewer = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);
        $viewerPermissions = [
            'view admin',
            'view services',
            'view actualites',
            'view projets',
            'view projet statistics',
            'view rapports',
            'download rapports',
            'view medias',
            'view categories',
            'view users',
        ];
        $viewer->syncPermissions($viewerPermissions);
        $this->command->info("  ✅ viewer: " . count($viewerPermissions) . " permissions");

        // Contributor - Nouveau rôle pour les contributeurs externes
        $contributor = Role::firstOrCreate(['name' => 'contributor', 'guard_name' => 'web']);
        $contributorPermissions = [
            'view admin',
            'view services',
            'view actualites',
            'create actualites',
            'view projets',
            'view rapports',
            'download rapports',
            'view medias',
            'upload medias',
        ];
        $contributor->syncPermissions($contributorPermissions);
        $this->command->info("  ✅ contributor: " . count($contributorPermissions) . " permissions");
    }
}
