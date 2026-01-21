<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class GrantSuperPermissions extends Command
{
    protected $signature = 'user:grant-super-permissions {email}';
    protected $description = 'Donne tous les rôles et permissions possibles à un utilisateur';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("🚀 ATTRIBUTION SUPER PERMISSIONS À {$email}");
        $this->info("============================================================");
        $this->newLine();

        try {
            // Rechercher l'utilisateur
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("❌ Utilisateur {$email} non trouvé.");
                
                if ($this->confirm("💡 Voulez-vous créer cet utilisateur ?")) {
                    $name = $this->ask("👤 Nom complet de l'utilisateur");
                    $password = $this->secret("🔐 Mot de passe (laissez vide pour générer automatiquement)");
                    
                    if (empty($password)) {
                        $password = 'SuperSecure123!';
                        $this->warn("🔐 Mot de passe généré automatiquement: {$password}");
                    }
                    
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => bcrypt($password),
                        'email_verified_at' => now(),
                    ]);
                    
                    $this->info("✅ Utilisateur créé avec succès !");
                    $this->info("📧 Email: {$email}");
                    $this->info("🔐 Mot de passe: {$password}");
                    $this->newLine();
                } else {
                    return;
                }
            } else {
                $this->info("✅ Utilisateur trouvé: {$user->name} ({$user->email})");
                $this->newLine();
            }

            // Récupérer tous les rôles existants
            $allRoles = Role::all();
            $this->info("📋 RÔLES DISPONIBLES ({$allRoles->count()}):");
            $this->info("=====================================");
            
            foreach ($allRoles as $role) {
                $this->line("  🏷️  {$role->name}");
            }
            $this->newLine();

            // Récupérer toutes les permissions existantes
            $allPermissions = Permission::all();
            $this->info("🔐 PERMISSIONS DISPONIBLES ({$allPermissions->count()}):");
            $this->info("==========================================");
            
            foreach ($allPermissions as $permission) {
                $this->line("  🔑 {$permission->name}");
            }
            $this->newLine();

            // Créer/Mettre à jour le rôle super admin
            $this->info("🦸 CRÉATION DU RÔLE SUPER ADMIN...");
            $this->info("==================================");
            
            $superAdminRole = Role::firstOrCreate([
                'name' => 'super-admin',
                'guard_name' => 'web'
            ]);
            
            // Assigner toutes les permissions au rôle super-admin
            $permissionNames = $allPermissions->pluck('name')->toArray();
            $superAdminRole->syncPermissions($permissionNames);
            $this->info("  ✅ Rôle 'super-admin' créé/mis à jour avec toutes les permissions");
            $this->newLine();

            // Assigner TOUS les rôles à l'utilisateur
            $this->info("👑 ATTRIBUTION DES RÔLES...");
            $this->info("============================");
            
            $roleNames = $allRoles->pluck('name')->toArray();
            $roleNames[] = 'super-admin'; // Ajouter le super-admin
            $user->syncRoles($roleNames);
            
            foreach ($roleNames as $roleName) {
                $this->info("  ✅ Rôle assigné: {$roleName}");
            }
            $this->newLine();

            // Assigner TOUTES les permissions directement à l'utilisateur
            $this->info("🔑 ATTRIBUTION DES PERMISSIONS DIRECTES...");
            $this->info("==========================================");
            
            $user->syncPermissions($permissionNames);
            
            foreach ($permissionNames as $permissionName) {
                $this->info("  ✅ Permission assignée: {$permissionName}");
            }
            $this->newLine();

            // Vérification finale
            $this->info("🔍 VÉRIFICATION FINALE...");
            $this->info("=========================");
            
            // Recharger l'utilisateur avec ses relations
            $user->refresh();
            $user->load('roles', 'permissions');
            
            $this->info("👤 Utilisateur: {$user->name}");
            $this->info("📧 Email: {$user->email}");
            $this->info("👑 Rôles: {$user->roles->count()} assignés");
            $this->info("🔑 Permissions directes: {$user->permissions->count()} assignées");
            $this->newLine();

            // Tester quelques permissions importantes
            $testPermissions = [
                'access admin',
                'manage system', 
                'manage users',
                'moderate_content',
                'publish_content',
                'manage services',
                'manage actualites',
                'manage publications'
            ];

            $this->info("🧪 TEST DES PERMISSIONS CRITIQUES...");
            $this->info("====================================");
            
            foreach ($testPermissions as $permission) {
                if ($user->can($permission)) {
                    $this->info("  ✅ {$permission}");
                } else {
                    $this->warn("  ❌ {$permission}");
                }
            }
            $this->newLine();

            $this->info("🎉 MISSION ACCOMPLIE !");
            $this->info("======================");
            $this->info("L'utilisateur {$email} a maintenant :");
            $this->info("  👑 Tous les rôles du système ({$user->roles->count()})");
            $this->info("  🔑 Toutes les permissions directes ({$user->permissions->count()})");
            $this->info("  🦸 Le rôle spécial 'super-admin'");
            $this->info("  💯 Accès complet à tout le système");
            $this->newLine();

            $this->info("🔐 INFORMATIONS DE CONNEXION :");
            $this->info("==============================");
            $this->info("📧 Email: {$email}");
            $this->info("🌐 URL Admin: /admin");
            $this->newLine();

            $this->warn("⚠️  SÉCURITÉ IMPORTANTE :");
            $this->warn("========================");
            $this->warn("Cet utilisateur a un accès COMPLET au système.");
            $this->warn("Assurez-vous que le mot de passe est sécurisé !");

        } catch (\Exception $e) {
            $this->error("❌ ERREUR: " . $e->getMessage());
            $this->error("📍 Ligne: " . $e->getLine());
            $this->error("📁 Fichier: " . $e->getFile());
            return 1;
        }

        return 0;
    }
}
