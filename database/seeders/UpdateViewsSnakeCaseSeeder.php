<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UpdateViewsSnakeCaseSeeder extends Seeder
{
    /**
     * Mapping des anciens formats vers le nouveau format snake_case
     */
    private array $permissionMappings = [
        // Actualités
        'create actualites' => 'create_actualites',
        'view actualites' => 'view_actualites',
        'update actualites' => 'update_actualites',
        'delete actualites' => 'delete_actualites',
        'publish actualites' => 'publish_actualites',
        'unpublish actualites' => 'unpublish_actualites',
        'moderate actualites' => 'moderate_actualites',
        
        // Projets
        'create projets' => 'create_projets',
        'view projets' => 'view_projets',
        'update projets' => 'update_projets',
        'delete projets' => 'delete_projets',
        'publish projets' => 'publish_projets',
        'moderate projets' => 'moderate_projets',
        
        // Publications
        'create publications' => 'create_publications',
        'view publications' => 'view_publications',
        'update publications' => 'update_publications',
        'delete publications' => 'delete_publications',
        'publish publications' => 'publish_publications',
        'moderate publications' => 'moderate_publications',
        
        // Services
        'create services' => 'create_services',
        'view services' => 'view_services',
        'update services' => 'update_services',
        'delete services' => 'delete_services',
        'publish services' => 'publish_services',
        'moderate services' => 'moderate_services',
        
        // Événements
        'create evenements' => 'create_evenements',
        'view evenements' => 'view_evenements',
        'update evenements' => 'update_evenements',
        'delete evenements' => 'delete_evenements',
        'publish evenements' => 'publish_evenements',
        'moderate evenements' => 'moderate_evenements',
        
        // Media
        'create media' => 'create_media',
        'view media' => 'view_media',
        'update media' => 'update_media',
        'delete media' => 'delete_media',
        'approve media' => 'approve_media',
        'reject media' => 'reject_media',
        'moderate media' => 'moderate_media',
        
        // Rapports (déjà en snake_case mais on s'assure)
        'create_rapport' => 'create_rapports',
        'view_rapport' => 'view_rapports',
        'update_rapport' => 'update_rapports',
        'delete_rapport' => 'delete_rapports',
        'moderate_rapport' => 'moderate_rapports',
        
        // Autres
        'manage users' => 'manage_users',
    ];

    public function run(): void
    {
        $this->command->info("🔄 Mise à jour des vues pour utiliser le format snake_case...");
        
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
        
        $this->command->info("🎉 Mise à jour terminée:");
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
                "/(@can\(\s*['\"])$oldPermission(['\"])/",
                // @endcan correspondant (pour les espaces)
                "/(@endcan\s*{{--\s*)$oldPermission(\s*--}})/",
                // Dans les commentaires
                "/({{--\s*@can\(\s*['\"])$oldPermission(['\"])/",
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
