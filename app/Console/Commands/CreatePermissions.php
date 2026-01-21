<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreatePermissions extends Command
{
    protected $signature = 'permissions:create';
    protected $description = 'Créer toutes les permissions et rôles nécessaires';

    public function handle()
    {
        $this->info('🚀 Création des permissions et rôles...');

        try {
            DB::beginTransaction();

            // Vider le cache des permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // 1. Créer les permissions
            $permissions = [
                // Admin
                'view admin' => 'Accéder à l\'admin',
                
                // Services
                'view services' => 'Voir les services',
                'create services' => 'Créer des services',
                'update services' => 'Modifier les services',
                'delete services' => 'Supprimer les services',
                'publish services' => 'Publier les services',
                'unpublish services' => 'Dépublier les services',
                'moderate services' => 'Modérer les services',
                
                // Actualités
                'view actualites' => 'Voir les actualités',
                'create actualites' => 'Créer des actualités',
                'update actualites' => 'Modifier les actualités',
                'delete actualites' => 'Supprimer les actualités',
                'publish actualites' => 'Publier les actualités',
                'unpublish actualites' => 'Dépublier les actualités',
                'moderate actualites' => 'Modérer les actualités',
                
                // Projets
                'view projets' => 'Voir les projets',
                'create projets' => 'Créer des projets',
                'update projets' => 'Modifier les projets',
                'delete projets' => 'Supprimer les projets',
                'publish projets' => 'Publier les projets',
                'unpublish projets' => 'Dépublier les projets',
                'moderate projets' => 'Modérer les projets',

                // Publications
                'view publications' => 'Voir les publications',
                'create publications' => 'Créer des publications',
                'update publications' => 'Modifier les publications',
                'delete publications' => 'Supprimer les publications',
                'publish publications' => 'Publier les publications',
                'unpublish publications' => 'Dépublier les publications',
                'moderate publications' => 'Modérer les publications',

                // Rapports
                'view rapports' => 'Voir les rapports',
                'create rapports' => 'Créer des rapports',
                'update rapports' => 'Modifier les rapports',
                'delete rapports' => 'Supprimer les rapports',
                'publish rapports' => 'Publier les rapports',
                'unpublish rapports' => 'Dépublier les rapports',
                'moderate rapports' => 'Modérer les rapports',

                // Événements
                'view evenements' => 'Voir les événements',
                'create evenements' => 'Créer des événements',
                'update evenements' => 'Modifier les événements',
                'delete evenements' => 'Supprimer les événements',
                'publish evenements' => 'Publier les événements',
                'unpublish evenements' => 'Dépublier les événements',
                'moderate evenements' => 'Modérer les événements',
            ];

            foreach ($permissions as $name => $description) {
                Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
                $this->info("✅ Permission: {$name}");
            }

            // 2. Créer les rôles
            $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
            $superAdmin->syncPermissions(Permission::all());
            $this->info("✅ Rôle: super-admin");

            $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
            $admin->syncPermissions(Permission::all());
            $this->info("✅ Rôle: admin");

            // 3. Assigner le rôle admin à tous les utilisateurs
            $users = User::all();
            foreach ($users as $user) {
                if (!$user->hasAnyRole(['super-admin', 'admin'])) {
                    $user->assignRole('admin');
                    $this->info("✅ Rôle admin assigné à: {$user->email}");
                }
            }

            DB::commit();

            $this->info('🎉 Permissions créées avec succès !');
            $this->info('📊 Total permissions: ' . Permission::count());
            $this->info('📊 Total rôles: ' . Role::count());

        } catch (\Exception $e) {
            DB::rollback();
            $this->error("❌ Erreur: {$e->getMessage()}");
            return 1;
        }

        return 0;
    }
}
