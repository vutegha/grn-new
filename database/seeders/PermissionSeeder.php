<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Création des permissions et rôles...');

        DB::beginTransaction();

        try {
            // Reset cached roles and permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // 1. Créer les permissions pour chaque modèle
            $permissions = [
                // Permissions générales
                'view admin' => 'Accéder à l\'interface d\'administration',
                
                // Permissions pour Services
                'view services' => 'Voir les services',
                'create services' => 'Créer des services',
                'update services' => 'Modifier les services',
                'delete services' => 'Supprimer les services',
                'publish services' => 'Publier les services',
                'unpublish services' => 'Dépublier les services',
                'moderate services' => 'Modérer les services',
                
                // Permissions pour Actualités
                'view actualites' => 'Voir les actualités',
                'create actualites' => 'Créer des actualités',
                'update actualites' => 'Modifier les actualités',
                'delete actualites' => 'Supprimer les actualités',
                'publish actualites' => 'Publier les actualités',
                'unpublish actualites' => 'Dépublier les actualités',
                'moderate actualites' => 'Modérer les actualités',
                
                // Permissions pour Projets
                'view projets' => 'Voir les projets',
                'create projets' => 'Créer des projets',
                'update projets' => 'Modifier les projets',
                'delete projets' => 'Supprimer les projets',
                'publish projets' => 'Publier les projets',
                'unpublish projets' => 'Dépublier les projets',
                'moderate projets' => 'Modérer les projets',
                
                // Permissions pour Users
                'view users' => 'Voir les utilisateurs',
                'create users' => 'Créer des utilisateurs',
                'update users' => 'Modifier les utilisateurs',
                'delete users' => 'Supprimer les utilisateurs',
                
                // Permissions pour Roles & Permissions
                'view roles' => 'Voir les rôles',
                'create roles' => 'Créer des rôles',
                'update roles' => 'Modifier les rôles',
                'delete roles' => 'Supprimer les rôles',
                'assign roles' => 'Assigner des rôles',
            ];

            foreach ($permissions as $name => $description) {
                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web'],
                    ['name' => $name, 'guard_name' => 'web']
                );
                $this->command->info("  ✅ Permission: {$name}");
            }

            // 2. Créer les rôles
            $this->command->info('👑 Création des rôles...');

            // Super Admin - Toutes les permissions
            $superAdminRole = Role::firstOrCreate(
                ['name' => 'super-admin', 'guard_name' => 'web']
            );
            $superAdminRole->syncPermissions(Permission::all());
            $this->command->info("  ✅ Rôle: super-admin (toutes permissions)");

            // Admin - Permissions complètes sauf gestion des rôles
            $adminRole = Role::firstOrCreate(
                ['name' => 'admin', 'guard_name' => 'web']
            );
            $adminPermissions = Permission::whereNotIn('name', [
                'create roles', 'update roles', 'delete roles', 'assign roles'
            ])->get();
            $adminRole->syncPermissions($adminPermissions);
            $this->command->info("  ✅ Rôle: admin");

            // Moderator - Permissions de modération et publication
            $moderatorRole = Role::firstOrCreate(
                ['name' => 'moderator', 'guard_name' => 'web']
            );
            $moderatorPermissions = [
                'view admin',
                'view services', 'update services', 'publish services', 'unpublish services', 'moderate services',
                'view actualites', 'update actualites', 'publish actualites', 'unpublish actualites', 'moderate actualites',
                'view projets', 'update projets', 'publish projets', 'unpublish projets', 'moderate projets',
            ];
            $moderatorRole->syncPermissions($moderatorPermissions);
            $this->command->info("  ✅ Rôle: moderator");

            // Editor - Permissions de création et édition
            $editorRole = Role::firstOrCreate(
                ['name' => 'editor', 'guard_name' => 'web']
            );
            $editorPermissions = [
                'view admin',
                'view services', 'create services', 'update services',
                'view actualites', 'create actualites', 'update actualites',
                'view projets', 'create projets', 'update projets',
            ];
            $editorRole->syncPermissions($editorPermissions);
            $this->command->info("  ✅ Rôle: editor");

            // Viewer - Permissions de lecture seulement
            $viewerRole = Role::firstOrCreate(
                ['name' => 'viewer', 'guard_name' => 'web']
            );
            $viewerPermissions = [
                'view admin',
                'view services',
                'view actualites',
                'view projets',
                'view users',
            ];
            $viewerRole->syncPermissions($viewerPermissions);
            $this->command->info("  ✅ Rôle: viewer");

            // 3. Assigner le rôle super-admin à l'utilisateur principal
            $adminUser = User::where('email', 'iri@ucbc.org')->first();
            if ($adminUser) {
                $adminUser->assignRole('super-admin');
                $this->command->info("  ✅ Rôle super-admin assigné à {$adminUser->email}");
            } else {
                $this->command->warn("  ⚠️ Utilisateur iri@ucbc.org non trouvé");
            }

            // Assigner admin à tous les autres utilisateurs existants
            $otherUsers = User::where('email', '!=', 'iri@ucbc.org')->get();
            foreach ($otherUsers as $user) {
                if (!$user->hasAnyRole(['super-admin', 'admin', 'moderator', 'editor', 'viewer'])) {
                    $user->assignRole('admin');
                    $this->command->info("  ✅ Rôle admin assigné à {$user->email}");
                }
            }

            DB::commit();

            $this->command->info('🎉 Permissions et rôles créés avec succès !');
            $this->command->info('📊 Résumé:');
            $this->command->info('  - Permissions: ' . Permission::count());
            $this->command->info('  - Rôles: ' . Role::count());
            $this->command->info('  - Utilisateurs avec rôles: ' . User::role(['super-admin', 'admin', 'moderator', 'editor', 'viewer'])->count());

        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error("❌ Erreur: {$e->getMessage()}");
            throw $e;
        }
    }
}
