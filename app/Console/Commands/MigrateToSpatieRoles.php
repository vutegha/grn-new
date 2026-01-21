<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MigrateToSpatieRoles extends Command
{
    protected $signature = 'roles:migrate-to-spatie';
    protected $description = 'Migre du système de rôles simple vers Spatie Laravel Permission';

    public function handle()
    {
        $this->info('🚀 Migration vers le système Spatie Laravel Permission...');

        try {
            DB::beginTransaction();

            // 1. Créer les permissions de base
            $this->info('📝 Création des permissions...');
            $permissions = [
                'manage_users' => 'Gérer les utilisateurs',
                'manage_roles' => 'Gérer les rôles et permissions',
                'moderate_content' => 'Modérer le contenu',
                'publish_content' => 'Publier le contenu',
                'unpublish_content' => 'Dépublier le contenu',
                'create_content' => 'Créer du contenu',
                'edit_content' => 'Modifier le contenu',
                'delete_content' => 'Supprimer le contenu',
                'view_admin' => 'Accéder à l\'interface admin',
                'manage_publications' => 'Gérer les publications',
                'manage_projets' => 'Gérer les projets',
                'manage_services' => 'Gérer les services',
                'manage_actualites' => 'Gérer les actualités',
                'manage_rapports' => 'Gérer les rapports'
            ];

            foreach ($permissions as $name => $description) {
                $permission = Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => 'web']
                );
                $this->info("  ✅ Permission: {$name}");
            }

            // 2. Créer les rôles avec leurs permissions
            $this->info('👑 Création des rôles...');
            
            // Super Admin - Toutes les permissions
            $superAdmin = Role::firstOrCreate([
                'name' => 'super-admin',
                'guard_name' => 'web'
            ]);
            $superAdmin->syncPermissions(Permission::all());
            $this->info('  ✅ Super Admin - Toutes permissions');

            // Admin - Presque toutes les permissions
            $admin = Role::firstOrCreate([
                'name' => 'admin', 
                'guard_name' => 'web'
            ]);
            $adminPermissions = [
                'manage_users', 'moderate_content', 'publish_content', 'unpublish_content',
                'create_content', 'edit_content', 'delete_content', 'view_admin',
                'manage_publications', 'manage_projets', 'manage_services', 
                'manage_actualites', 'manage_rapports'
            ];
            $admin->syncPermissions($adminPermissions);
            $this->info('  ✅ Admin - Permissions administratives');

            // Moderator - Permissions de modération
            $moderator = Role::firstOrCreate([
                'name' => 'moderator',
                'guard_name' => 'web'
            ]);
            $moderatorPermissions = [
                'moderate_content', 'publish_content', 'unpublish_content',
                'create_content', 'edit_content', 'view_admin',
                'manage_publications', 'manage_projets', 'manage_services', 
                'manage_actualites', 'manage_rapports'
            ];
            $moderator->syncPermissions($moderatorPermissions);
            $this->info('  ✅ Moderator - Permissions de modération');

            // Editor - Permissions d'édition
            $editor = Role::firstOrCreate([
                'name' => 'editor',
                'guard_name' => 'web'
            ]);
            $editorPermissions = [
                'create_content', 'edit_content', 'view_admin',
                'manage_publications', 'manage_projets', 'manage_services', 
                'manage_actualites', 'manage_rapports'
            ];
            $editor->syncPermissions($editorPermissions);
            $this->info('  ✅ Editor - Permissions d\'édition');

            // Contributor - Permissions de base
            $contributor = Role::firstOrCreate([
                'name' => 'contributor',
                'guard_name' => 'web'
            ]);
            $contributorPermissions = ['create_content', 'view_admin'];
            $contributor->syncPermissions($contributorPermissions);
            $this->info('  ✅ Contributor - Permissions de base');

            // 3. Migrer les utilisateurs existants
            $this->info('👥 Migration des utilisateurs...');
            $users = User::all();
            
            foreach ($users as $user) {
                $oldRole = $user->role ?? 'user';
                
                // Mapper les anciens rôles vers les nouveaux
                switch ($oldRole) {
                    case 'admin':
                        $user->assignRole('admin');
                        $this->info("  ✅ {$user->email} → Admin");
                        break;
                    case 'moderator':
                        $user->assignRole('moderator');
                        $this->info("  ✅ {$user->email} → Moderator");
                        break;
                    case 'editor':
                        $user->assignRole('editor');
                        $this->info("  ✅ {$user->email} → Editor");
                        break;
                    default:
                        $user->assignRole('contributor');
                        $this->info("  ✅ {$user->email} → Contributor");
                        break;
                }
            }

            DB::commit();
            $this->info('🎉 Migration terminée avec succès !');

            // 4. Test de validation
            $this->info("\n📊 Validation post-migration:");
            foreach ($users as $user) {
                $userRoles = $user->getRoleNames()->implode(', ');
                $canModerate = $user->canModerate() ? 'Oui' : 'Non';
                $this->info("  - {$user->name}: Rôles=[{$userRoles}], Modérer=[{$canModerate}]");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Erreur lors de la migration: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
