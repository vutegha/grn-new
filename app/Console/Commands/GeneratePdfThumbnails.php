<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Publication;
use App\Services\PdfThumbnailServiceFallback;
use Illuminate\Support\Facades\Log;

class GeneratePdfThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thumbnails:generate {--force : Regénérer toutes les miniatures même si elles existent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les miniatures des PDFs pour toutes les publications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Début de la génération des miniatures PDF...');
        
        $force = $this->option('force');
        if ($force) {
            $this->warn('⚠️  Mode force activé : toutes les miniatures seront regénérées');
        }

        // Récupérer toutes les publications avec un fichier PDF
        $publications = Publication::whereNotNull('fichier_pdf')
                                 ->where('fichier_pdf', '!=', '')
                                 ->get();

        if ($publications->isEmpty()) {
            $this->warn('ℹ️  Aucune publication avec fichier PDF trouvée.');
            return 0;
        }

        $this->info("📁 {$publications->count()} publication(s) avec PDF trouvée(s)");
        
        $progressBar = $this->output->createProgressBar($publications->count());
        $progressBar->start();

        $generated = 0;
        $errors = 0;
        $skipped = 0;

        foreach ($publications as $publication) {
            try {
                // Vérifier si la miniature existe déjà (sauf si --force)
                if (!$force && $publication->hasThumbnail()) {
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }

                // Générer la miniature
                $thumbnailPath = PdfThumbnailServiceFallback::generateThumbnail(
                    $publication->fichier_pdf,
                    'thumb_pub_' . $publication->id . '_' . time()
                );

                if ($thumbnailPath) {
                    $generated++;
                    Log::info("Miniature générée pour publication #{$publication->id}: {$thumbnailPath}");
                } else {
                    $errors++;
                    Log::warning("Échec génération miniature pour publication #{$publication->id}");
                }

            } catch (\Exception $e) {
                $errors++;
                Log::error("Erreur génération miniature publication #{$publication->id}: " . $e->getMessage());
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Résumé
        $this->info('✅ Génération terminée !');
        $this->table(['Statut', 'Nombre'], [
            ['Générées', $generated],
            ['Ignorées (existantes)', $skipped],
            ['Erreurs', $errors],
            ['Total traité', $publications->count()]
        ]);

        if ($errors > 0) {
            $this->warn("⚠️  {$errors} erreur(s) détectée(s). Consultez les logs pour plus de détails.");
            $this->info("💡 Assurez-vous que l'extension ImageMagick est installée et configurée.");
        }

        return 0;
    }
}
