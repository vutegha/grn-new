<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SyncPublicStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:sync {--force : Force overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize storage files to public/storage for Windows compatibility';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $storagePath = storage_path('app/public');
        $publicPath = public_path('storage');
        
        if (!is_dir($storagePath)) {
            $this->error('Storage directory does not exist: ' . $storagePath);
            return 1;
        }

        // Créer le répertoire public/storage s'il n'existe pas
        if (!is_dir($publicPath)) {
            mkdir($publicPath, 0755, true);
            $this->info('Created public/storage directory');
        }

        $force = $this->option('force');
        $copied = 0;
        $skipped = 0;

        $this->info('Synchronizing storage files...');
        
        $files = File::allFiles($storagePath);
        
        foreach ($files as $file) {
            $relativePath = $file->getRelativePathname();
            $sourcePath = $file->getPathname();
            $destPath = $publicPath . DIRECTORY_SEPARATOR . $relativePath;
            
            // Créer le répertoire de destination si nécessaire
            $destDir = dirname($destPath);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            
            // Vérifier si le fichier existe déjà
            if (file_exists($destPath) && !$force) {
                $skipped++;
                continue;
            }
            
            // Copier le fichier
            if (copy($sourcePath, $destPath)) {
                $copied++;
                $this->line("Copied: {$relativePath}");
            } else {
                $this->error("Failed to copy: {$relativePath}");
            }
        }
        
        $this->info("Synchronization complete!");
        $this->line("Files copied: {$copied}");
        
        if ($skipped > 0) {
            $this->line("Files skipped: {$skipped} (use --force to overwrite)");
        }
        
        return 0;
    }
}
