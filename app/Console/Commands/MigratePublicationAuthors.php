<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigratePublicationAuthors extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'publication:migrate-authors 
                            {--verify : Vérifier la migration sans exécuter}
                            {--force : Forcer la migration même si la table pivot existe}';

    /**
     * The console command description.
     */
    protected $description = 'Migre les relations auteur_id des publications vers la table pivot auteur_publication';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('verify')) {
            return $this->verifyMigration();
        }

        $this->info('🚀 Début de la migration des auteurs des publications...');

        // Vérifier si la table pivot existe
        if (!DB::getSchemaBuilder()->hasTable('auteur_publication')) {
            $this->error('❌ La table auteur_publication n\'existe pas. Exécutez d\'abord : php artisan migrate');
            return 1;
        }

        // Compter les données existantes
        $existingPivot = DB::table('auteur_publication')->count();
        $publicationsWithAuthor = DB::table('publications')->whereNotNull('auteur_id')->count();

        $this->info("📊 Publications avec auteur : {$publicationsWithAuthor}");
        $this->info("📊 Relations pivot existantes : {$existingPivot}");

        if ($existingPivot > 0 && !$this->option('force')) {
            $this->warn('⚠️  La table pivot contient déjà des données.');
            if (!$this->confirm('Voulez-vous continuer ?')) {
                return 0;
            }
        }

        // Migrer les données
        $migratedCount = 0;
        $errorCount = 0;

        $publications = DB::table('publications')
            ->whereNotNull('auteur_id')
            ->get(['id', 'auteur_id', 'titre', 'created_at', 'updated_at']);

        $this->withProgressBar($publications, function ($publication) use (&$migratedCount, &$errorCount) {
            try {
                // Vérifier que l'auteur existe
                $auteurExists = DB::table('auteurs')->where('id', $publication->auteur_id)->exists();

                if (!$auteurExists) {
                    $this->newLine();
                    $this->warn("⚠️  Auteur ID {$publication->auteur_id} introuvable pour la publication '{$publication->titre}'");
                    $errorCount++;
                    return;
                }

                // Vérifier si la relation existe déjà
                $pivotExists = DB::table('auteur_publication')
                    ->where('auteur_id', $publication->auteur_id)
                    ->where('publication_id', $publication->id)
                    ->exists();

                if (!$pivotExists) {
                    DB::table('auteur_publication')->insert([
                        'auteur_id' => $publication->auteur_id,
                        'publication_id' => $publication->id,
                        'created_at' => $publication->created_at ?? now(),
                        'updated_at' => $publication->updated_at ?? now(),
                    ]);
                    $migratedCount++;
                }
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("❌ Erreur lors de la migration de la publication ID {$publication->id}: " . $e->getMessage());
                $errorCount++;
            }
        });

        $this->newLine(2);
        $this->info("✅ Migration terminée !");
        $this->info("📈 {$migratedCount} relations migrées");
        
        if ($errorCount > 0) {
            $this->warn("⚠️  {$errorCount} erreurs rencontrées");
        }

        // Vérification finale
        $this->verifyMigration();

        return 0;
    }

    private function verifyMigration()
    {
        $this->info('🔍 Vérification de la migration...');

        $publicationsAvecAuteur = DB::table('publications')->whereNotNull('auteur_id')->count();
        $relationsPivot = DB::table('auteur_publication')->count();

        $this->table(
            ['Métrique', 'Valeur'],
            [
                ['Publications avec auteur_id', $publicationsAvecAuteur],
                ['Relations dans table pivot', $relationsPivot],
                ['Statut', $relationsPivot >= $publicationsAvecAuteur ? '✅ OK' : '❌ Problème'],
            ]
        );

        // Chercher les publications sans correspondance
        $sansCorrespondance = DB::table('publications as p')
            ->leftJoin('auteur_publication as ap', 'p.id', '=', 'ap.publication_id')
            ->whereNotNull('p.auteur_id')
            ->whereNull('ap.publication_id')
            ->select('p.id', 'p.titre', 'p.auteur_id')
            ->get();

        if ($sansCorrespondance->count() > 0) {
            $this->warn("⚠️  {$sansCorrespondance->count()} publications non migrées :");
            foreach ($sansCorrespondance as $pub) {
                $this->line("   - ID: {$pub->id}, Titre: {$pub->titre}");
            }
        } else {
            $this->info('✅ Toutes les publications ont été migrées correctement !');
        }

        return 0;
    }
}
