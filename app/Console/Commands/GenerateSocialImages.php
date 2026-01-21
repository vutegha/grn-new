<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Actualite;
use App\Jobs\GenerateSocialImage;
use Illuminate\Support\Facades\Storage;

class GenerateSocialImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'social:build 
                          {--model=Actualite : Le modèle à traiter (Actualite|Publication)}
                          {--id=* : IDs spécifiques à traiter (par défaut tous)}
                          {--force : Forcer la régénération même si l\'image existe}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les images sociales pour les actualités et publications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelName = $this->option('model');
        $ids = $this->option('id');
        $force = $this->option('force');

        // Validation du modèle
        if (!in_array($modelName, ['Actualite', 'Publication'])) {
            $this->error("Modèle non supporté: {$modelName}. Modèles supportés: Actualite, Publication");
            return 1;
        }

        $this->info("🚀 Génération des images sociales pour le modèle: {$modelName}");

        // Récupération des enregistrements
        $query = match($modelName) {
            'Actualite' => \App\Models\Actualite::query(),
            'Publication' => \App\Models\Publication::query(),
            default => null
        };

        if (!$query) {
            $this->error("Impossible de créer la requête pour le modèle {$modelName}");
            return 1;
        }

        // Filtrer par IDs si spécifiés
        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

        $records = $query->get();

        if ($records->isEmpty()) {
            $this->warn("Aucun enregistrement trouvé pour le modèle {$modelName}");
            return 0;
        }

        $this->info("📊 {$records->count()} enregistrement(s) à traiter");

        $progressBar = $this->output->createProgressBar($records->count());
        $progressBar->start();

        $generated = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($records as $record) {
            try {
                // Vérifier si l'image sociale existe déjà
                $socialImagePath = $this->getSocialImagePath($record, strtolower($modelName));
                
                if (!$force && Storage::disk('public')->exists($socialImagePath)) {
                    $this->newLine();
                    $this->line("⏭️  Image sociale existe déjà pour {$modelName} #{$record->id} (utilisez --force pour régénérer)");
                    $skipped++;
                } else {
                    // Générer l'image sociale
                    GenerateSocialImage::dispatch($record);
                    $generated++;
                }

            } catch (\Exception $e) {
                $this->newLine();
                $this->error("❌ Erreur pour {$modelName} #{$record->id}: " . $e->getMessage());
                $errors++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Résumé
        $this->info("✅ Génération terminée !");
        $this->table(['Statut', 'Nombre'], [
            ['Générées', $generated],
            ['Ignorées (existantes)', $skipped],
            ['Erreurs', $errors],
            ['Total', $records->count()]
        ]);

        if ($generated > 0) {
            $this->info("🔄 Les jobs de génération ont été ajoutés à la queue.");
            $this->info("💡 Exécutez 'php artisan queue:work' pour traiter les jobs en arrière-plan.");
        }

        return 0;
    }

    /**
     * Génère le chemin de l'image sociale pour un enregistrement
     */
    protected function getSocialImagePath($record, string $modelType): string
    {
        $format = config('share.social_image.format', 'jpg');
        $basePath = config('share.storage.path', 'social');
        
        return "{$basePath}/{$modelType}/{$record->id}.{$format}";
    }
}
