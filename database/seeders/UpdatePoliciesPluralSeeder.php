<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UpdatePoliciesPluralSeeder extends Seeder
{
    /**
     * Mapping des permissions singulières vers plurielles dans les policies
     */
    private array $permissionMappings = [
        // Actualités
        'create_actualite' => 'create_actualites',
        'update_actualite' => 'update_actualites', 
        'delete_actualite' => 'delete_actualites',
        
        // Projets
        'create_projet' => 'create_projets',
        'update_projet' => 'update_projets',
        'delete_projet' => 'delete_projets',
        
        // Publications
        'create_publication' => 'create_publications',
        'update_publication' => 'update_publications',
        'delete_publication' => 'delete_publications',
        
        // Événements
        'create_evenement' => 'create_evenements',
        'update_evenement' => 'update_evenements',
        'delete_evenement' => 'delete_evenements',
        
        // Services
        'create_service' => 'create_services',
        'update_service' => 'update_services',
        'delete_service' => 'delete_services',
        
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
        'update_auteur' => 'update_auteurs',
        'delete_auteur' => 'delete_auteurs',
        
        // Rapports
        'create_rapport' => 'create_rapports',
        'update_rapport' => 'update_rapports',
        'delete_rapport' => 'delete_rapports',
        
        // Utilisateurs
        'create_user' => 'create_users',
        'update_user' => 'update_users',
        'delete_user' => 'delete_users',
    ];

    public function run(): void
    {
        $this->command->info("🔧 Mise à jour des policies vers format PLURIEL...");
        
        $policyPath = app_path('Policies');
        $policyFiles = [
            'ActualitePolicy.php',
            'ProjetPolicy.php', 
            'PublicationPolicy.php',
            'EvenementPolicy.php',
            'ServicePolicy.php',
            'MediaPolicy.php',
            'AuteurPolicy.php',
            'RapportPolicy.php',
            'UserPolicy.php'
        ];
        
        $updatedFiles = 0;
        $totalChanges = 0;
        
        foreach ($policyFiles as $file) {
            $filePath = $policyPath . DIRECTORY_SEPARATOR . $file;
            
            if (!file_exists($filePath)) {
                $this->command->warn("⚠️ Policy non trouvée: $file");
                continue;
            }
            
            // Créer une sauvegarde
            $backupPath = $filePath . '.backup_plural';
            copy($filePath, $backupPath);
            $this->command->info("📁 Sauvegarde: $file.backup_plural");
            
            $content = file_get_contents($filePath);
            $originalContent = $content;
            $changes = 0;
            
            // Remplacer les permissions dans hasPermissionTo()
            foreach ($this->permissionMappings as $oldPermission => $newPermission) {
                $patterns = [
                    // hasPermissionTo('old_permission', 'web')
                    "/hasPermissionTo\s*\(\s*['\"]" . preg_quote($oldPermission, '/') . "['\"]\s*,\s*['\"]web['\"]/",
                    // hasPermissionTo('old_permission')
                    "/hasPermissionTo\s*\(\s*['\"]" . preg_quote($oldPermission, '/') . "['\"]\s*\)/",
                ];
                
                foreach ($patterns as $pattern) {
                    $replacement = "hasPermissionTo('$newPermission', 'web')";
                    $newContent = preg_replace($pattern, $replacement, $content);
                    if ($newContent !== $content) {
                        $changesCount = preg_match_all($pattern, $content);
                        $changes += $changesCount;
                        $content = $newContent;
                    }
                }
            }
            
            // Sauvegarder si des changements ont été effectués
            if ($content !== $originalContent) {
                file_put_contents($filePath, $content);
                $updatedFiles++;
                $totalChanges += $changes;
                $this->command->info("✅ Mis à jour: $file ($changes changements)");
            } else {
                $this->command->info("ℹ️ Aucun changement: $file");
            }
        }
        
        $this->command->info("🎉 Mise à jour des policies terminée:");
        $this->command->info("   - Fichiers modifiés: $updatedFiles");
        $this->command->info("   - Total des changements: $totalChanges");
    }
}
