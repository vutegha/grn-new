<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Publication;
use App\Models\Actualite;
use App\Models\Projet;
use App\Models\Media;

class DiagnoseStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:diagnose {--fix : Attempt to fix missing file references}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnose storage issues and find missing files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Starting Storage Diagnostic...');
        $this->newLine();

        // Vérifier la configuration
        $this->checkConfiguration();
        $this->newLine();

        // Vérifier le lien symbolique
        $this->checkSymlink();
        $this->newLine();

        // Vérifier les publications
        $this->checkPublications();
        $this->newLine();

        // Vérifier les actualités
        $this->checkActualites();
        $this->newLine();

        // Vérifier les projets
        $this->checkProjets();
        $this->newLine();

        // Vérifier les médias
        $this->checkMedias();
        $this->newLine();

        $this->info('✅ Diagnostic completed!');
        
        return 0;
    }

    protected function checkConfiguration()
    {
        $this->info('📋 Configuration Check:');
        
        $disk = config('filesystems.disks.public');
        $this->line('  Storage Root: ' . $disk['root']);
        $this->line('  Storage URL: ' . $disk['url']);
        $this->line('  APP_ENV: ' . config('app.env'));
        
        if (file_exists($disk['root'])) {
            $this->info('  ✓ Storage root exists');
        } else {
            $this->error('  ✗ Storage root does not exist!');
        }
    }

    protected function checkSymlink()
    {
        $this->info('🔗 Symlink Check:');
        
        $target = public_path('storage');
        $source = storage_path('app/public');
        
        if (is_link($target)) {
            $this->info('  ✓ Symbolic link exists');
            $linkTarget = readlink($target);
            $this->line('  Link points to: ' . $linkTarget);
        } elseif (is_dir($target)) {
            $this->warn('  ⚠ Directory exists but is not a symlink');
            $this->line('  Run: php artisan storage:link-alt to sync files');
        } else {
            $this->error('  ✗ Neither symlink nor directory exists!');
            $this->line('  Run: php artisan storage:link');
        }
    }

    protected function checkPublications()
    {
        $this->info('📄 Publications Check:');
        
        $publications = Publication::all();
        $total = $publications->count();
        $missing = 0;
        $found = 0;

        foreach ($publications as $pub) {
            if ($pub->fichier_pdf) {
                if (Storage::disk('public')->exists($pub->fichier_pdf)) {
                    $found++;
                } else {
                    $missing++;
                    $this->error('  ✗ Missing: ' . $pub->fichier_pdf . ' (ID: ' . $pub->id . ')');
                }
            }
        }

        $this->line("  Total: $total | Found: $found | Missing: $missing");
        
        if ($missing > 0) {
            $this->warn("  ⚠ $missing publication file(s) not found!");
        }
    }

    protected function checkActualites()
    {
        $this->info('📰 Actualités Check:');
        
        $actualites = Actualite::whereNotNull('image')->get();
        $total = $actualites->count();
        $missing = 0;
        $found = 0;

        foreach ($actualites as $actu) {
            if ($actu->image) {
                if (Storage::disk('public')->exists($actu->image)) {
                    $found++;
                } else {
                    $missing++;
                    $this->error('  ✗ Missing: ' . $actu->image . ' (ID: ' . $actu->id . ')');
                }
            }
        }

        $this->line("  Total: $total | Found: $found | Missing: $missing");
        
        if ($missing > 0) {
            $this->warn("  ⚠ $missing actualité image(s) not found!");
        }
    }

    protected function checkProjets()
    {
        $this->info('🏗️ Projets Check:');
        
        $projets = Projet::whereNotNull('image')->get();
        $total = $projets->count();
        $missing = 0;
        $found = 0;

        foreach ($projets as $projet) {
            if ($projet->image) {
                if (Storage::disk('public')->exists($projet->image)) {
                    $found++;
                } else {
                    $missing++;
                    $this->error('  ✗ Missing: ' . $projet->image . ' (ID: ' . $projet->id . ')');
                }
            }
        }

        $this->line("  Total: $total | Found: $found | Missing: $missing");
        
        if ($missing > 0) {
            $this->warn("  ⚠ $missing projet image(s) not found!");
        }
    }

    protected function checkMedias()
    {
        $this->info('🎬 Médias Check:');
        
        $medias = Media::whereNotNull('medias')->get();
        $total = $medias->count();
        $missing = 0;
        $found = 0;

        foreach ($medias as $media) {
            if ($media->medias) {
                if (Storage::disk('public')->exists($media->medias)) {
                    $found++;
                } else {
                    $missing++;
                    $this->error('  ✗ Missing: ' . $media->medias . ' (ID: ' . $media->id . ')');
                }
            }
        }

        $this->line("  Total: $total | Found: $found | Missing: $missing");
        
        if ($missing > 0) {
            $this->warn("  ⚠ $missing media file(s) not found!");
        }
    }
}
