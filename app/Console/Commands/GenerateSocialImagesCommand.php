<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Actualite;
use App\Jobs\GenerateSocialImage;

class GenerateSocialImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'social:build 
                           {--model=Actualite : Le modèle à traiter (Actualite|Publication)}
                           {--id=* : IDs spécifiques à traiter (optionnel)}
                           {--force : Régénérer même si l\'image existe déjà}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère ou régénère les images sociales pour les actualités/publications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelName = $this->option('model');
        $ids = $this->option('id');
        $force = $this->option('force');

        // Validation du modèle
        $modelClass = match($modelName) {
            'Actualite' => \App\Models\Actualite::class,
            'Publication' => \App\Models\Publication::class ?? null,
            default => null
        };

        if (!$modelClass || !class_exists($modelClass)) {
            $this->error("Modèle '$modelName' non supporté. Utilisez: Actualite ou Publication");
            return 1;
        }

        $this->info("🚀 Génération des images sociales pour: $modelName");

        // Construire la query
        $query = $modelClass::query();

        // Filtrer par IDs si spécifié
        if (!empty($ids)) {
            $query->whereIn('id', $ids);
            $this->info("📋 Filtrage par IDs: " . implode(', ', $ids));
        }

        // Si pas de force, exclure ceux qui ont déjà une image sociale
        if (!$force) {
            $query->where(function($q) {
                $q->whereNull('social_image_path')
                  ->orWhere('social_image_path', '');
            });
        }

        $items = $query->get();

        if ($items->isEmpty()) {
            $this->warn("Aucun élément à traiter.");
            return 0;
        }

        $this->info("📊 {$items->count()} éléments à traiter");

        // Barre de progression
        $bar = $this->output->createProgressBar($items->count());
        $bar->start();

        $processed = 0;
        $errors = 0;

        foreach ($items as $item) {
            try {
                // Dispatcher le job
                GenerateSocialImage::dispatch($item);
                
                $this->line(""); // Nouvelle ligne pour l'affichage
                $this->info("✅ Traitement lancé pour: {$item->titre} (ID: {$item->id})");
                
                $processed++;
            } catch (\Exception $e) {
                $this->line(""); // Nouvelle ligne pour l'affichage
                $this->error("❌ Erreur pour: {$item->titre} (ID: {$item->id}) - {$e->getMessage()}");
                $errors++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Résumé
        $this->info("🎉 Génération terminée!");
        $this->table(
            ['Statut', 'Nombre'],
            [
                ['Traités', $processed],
                ['Erreurs', $errors],
                ['Total', $items->count()]
            ]
        );

        if ($errors > 0) {
            $this->warn("⚠️ Des erreurs sont survenues. Vérifiez les logs pour plus de détails.");
        }

        $this->info("💡 Les images sont générées en arrière-plan via les jobs de queue.");
        $this->info("💡 Lancez 'php artisan queue:work' pour traiter les jobs immédiatement.");

        return 0;
    }
}
