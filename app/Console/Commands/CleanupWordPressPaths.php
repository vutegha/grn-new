<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Actualite;

class CleanupWordPressPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:cleanup-wp-paths {--dry-run : Show what would be changed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up WordPress parameters (?w=XXX) from image paths';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        $this->info('🧹 Cleaning up WordPress parameters from file paths...');
        $this->newLine();

        if ($isDryRun) {
            $this->warn('⚠️ DRY RUN MODE - No changes will be made');
            $this->newLine();
        }

        // Nettoyer les actualités
        $this->cleanActualites($isDryRun);
        
        $this->newLine();
        $this->info('✅ Cleanup completed!');
        
        if ($isDryRun) {
            $this->info('Run without --dry-run to apply changes');
        }

        return 0;
    }

    protected function cleanActualites($isDryRun)
    {
        $this->info('📰 Actualités:');

        $actualites = Actualite::where('image', 'LIKE', '%?%')->get();
        
        if ($actualites->isEmpty()) {
            $this->line('  ✓ No WordPress parameters found in actualités images');
            return;
        }

        $this->line('  Found ' . $actualites->count() . ' actualités with WordPress parameters');
        $this->newLine();

        $table = [];
        foreach ($actualites as $actualite) {
            $oldPath = $actualite->image;
            $newPath = $this->cleanPath($oldPath);
            
            $table[] = [
                $actualite->id,
                $this->truncate($actualite->titre, 30),
                $this->truncate($oldPath, 40),
                $this->truncate($newPath, 40)
            ];

            if (!$isDryRun) {
                $actualite->image = $newPath;
                $actualite->save();
            }
        }

        $this->table(
            ['ID', 'Titre', 'Avant', 'Après'],
            $table
        );

        if (!$isDryRun) {
            $this->info('  ✓ Updated ' . $actualites->count() . ' actualités');
        } else {
            $this->warn('  ⚠️ Would update ' . $actualites->count() . ' actualités (dry run)');
        }
    }

    protected function cleanPath($path)
    {
        // Supprimer les paramètres WordPress (tout après ?)
        if (strpos($path, '?') !== false) {
            $path = substr($path, 0, strpos($path, '?'));
        }
        return $path;
    }

    protected function truncate($string, $length)
    {
        if (strlen($string) > $length) {
            return substr($string, 0, $length - 3) . '...';
        }
        return $string;
    }
}
