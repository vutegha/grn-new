<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UpdateViewsPluralSeeder extends Seeder
{
    /**
     * Mapping des permissions singulières vers plurielles dans les vues
     */
    private array $permissionMappings = [
        // Actualités - les noms singuliers vers pluriels
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
        $this->command->info("🔄 Mise à jour des vues vers format PLURIEL...");
        
        $viewsPath = resource_path('views');
        $files = $this->getAllBladeFiles($viewsPath);
        
        $updatedFiles = 0;
        $totalChanges = 0;
        
        foreach ($files as $file) {
            $changes = $this->updateFilePermissions($file);
            if ($changes > 0) {
                $updatedFiles++;
                $totalChanges += $changes;
                $relativePath = str_replace($viewsPath . DIRECTORY_SEPARATOR, '', $file);
                $this->command->info("✅ Mis à jour: $relativePath ($changes changements)");
            }
        }
        
        $this->command->info("🎉 Mise à jour des vues terminée:");
        $this->command->info("   - Fichiers modifiés: $updatedFiles");
        $this->command->info("   - Total des changements: $totalChanges");
    }
    
    /**
     * Récupère tous les fichiers .blade.php récursivement
     */
    private function getAllBladeFiles(string $directory): array
    {
        $files = [];
        
        if (!is_dir($directory)) {
            return $files;
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );
        
        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php' && 
                str_ends_with($file->getFilename(), '.blade.php')) {
                $files[] = $file->getPathname();
            }
        }
        
        return $files;
    }
    
    /**
     * Met à jour les permissions dans un fichier
     */
    private function updateFilePermissions(string $filePath): int
    {
        $content = file_get_contents($filePath);
        $originalContent = $content;
        $changes = 0;
        
        // Mettre à jour les directives @can avec des permissions nommées
        foreach ($this->permissionMappings as $oldPermission => $newPermission) {
            $patterns = [
                // @can('permission')
                "/(@can\s*\(\s*['\"])" . preg_quote($oldPermission, '/') . "(['\"])/",
                // Dans les commentaires
                "/({{--\s*@can\s*\(\s*['\"])" . preg_quote($oldPermission, '/') . "(['\"])/",
            ];
            
            foreach ($patterns as $pattern) {
                $newContent = preg_replace($pattern, "$1$newPermission$2", $content);
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
        }
        
        return $changes;
    }
}
