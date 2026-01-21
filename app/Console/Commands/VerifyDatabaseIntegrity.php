<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VerifyDatabaseIntegrity extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'db:verify-integrity 
                            {--fix : Tenter de corriger automatiquement les problèmes détectés}
                            {--detailed : Afficher un rapport détaillé}';

    /**
     * The console command description.
     */
    protected $description = 'Vérifier l\'intégrité de toutes les relations de la base de données';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Vérification de l\'intégrité de la base de données...');
        $this->newLine();

        $issues = [];

        // Vérifier chaque relation
        $issues = array_merge($issues, $this->checkPublicationAuteurs());
        $issues = array_merge($issues, $this->checkActualiteAuteurs());
        $issues = array_merge($issues, $this->checkMediaProjets());
        $issues = array_merge($issues, $this->checkProjetServices());
        $issues = array_merge($issues, $this->checkCategorieRelations());

        // Résumé
        $this->newLine();
        if (empty($issues)) {
            $this->info('✅ Aucun problème d\'intégrité détecté !');
        } else {
            $this->warn("⚠️  {count($issues)} problème(s) détecté(s) :");
            foreach ($issues as $issue) {
                $this->line("   • {$issue}");
            }

            if ($this->option('fix')) {
                $this->newLine();
                $this->info('🔧 Tentative de correction automatique...');
                $this->fixIssues();
            } else {
                $this->newLine();
                $this->comment('💡 Utilisez --fix pour tenter une correction automatique');
            }
        }

        return 0;
    }

    private function checkPublicationAuteurs(): array
    {
        $issues = [];

        // Vérifier si la table pivot existe
        if (!Schema::hasTable('auteur_publication')) {
            $issues[] = "Table pivot 'auteur_publication' manquante";
            return $issues;
        }

        // Publications avec auteur_id mais sans relation pivot
        $publicationsSansRelation = DB::table('publications as p')
            ->leftJoin('auteur_publication as ap', 'p.id', '=', 'ap.publication_id')
            ->whereNotNull('p.auteur_id')
            ->whereNull('ap.publication_id')
            ->count();

        if ($publicationsSansRelation > 0) {
            $issues[] = "{$publicationsSansRelation} publications avec auteur_id mais sans relation pivot";
        }

        // Relations pivot avec auteurs inexistants
        $relationsInvalides = DB::table('auteur_publication as ap')
            ->leftJoin('auteurs as a', 'ap.auteur_id', '=', 'a.id')
            ->whereNull('a.id')
            ->count();

        if ($relationsInvalides > 0) {
            $issues[] = "{$relationsInvalides} relations pivot pointant vers des auteurs inexistants";
        }

        return $issues;
    }

    private function checkActualiteAuteurs(): array
    {
        $issues = [];

        if (!Schema::hasColumn('actualites', 'auteur_id')) {
            $issues[] = "Colonne 'auteur_id' manquante dans la table actualites";
            return $issues;
        }

        // Actualités avec auteur_id invalide
        $actualitesInvalides = DB::table('actualites as a')
            ->leftJoin('auteurs as au', 'a.auteur_id', '=', 'au.id')
            ->whereNotNull('a.auteur_id')
            ->whereNull('au.id')
            ->count();

        if ($actualitesInvalides > 0) {
            $issues[] = "{$actualitesInvalides} actualités avec auteur_id invalide";
        }

        return $issues;
    }

    private function checkMediaProjets(): array
    {
        $issues = [];

        if (!Schema::hasColumn('media', 'projet_id')) {
            $issues[] = "Colonne 'projet_id' manquante dans la table media";
            return $issues;
        }

        // Médias avec projet_id invalide
        $mediasInvalides = DB::table('media as m')
            ->leftJoin('projets as p', 'm.projet_id', '=', 'p.id')
            ->whereNotNull('m.projet_id')
            ->whereNull('p.id')
            ->count();

        if ($mediasInvalides > 0) {
            $issues[] = "{$mediasInvalides} médias avec projet_id invalide";
        }

        return $issues;
    }

    private function checkProjetServices(): array
    {
        $issues = [];

        if (!Schema::hasColumn('projets', 'service_id')) {
            $issues[] = "Colonne 'service_id' manquante dans la table projets";
            return $issues;
        }

        // Projets avec service_id invalide
        $projetsInvalides = DB::table('projets as p')
            ->leftJoin('services as s', 'p.service_id', '=', 's.id')
            ->whereNotNull('p.service_id')
            ->whereNull('s.id')
            ->count();

        if ($projetsInvalides > 0) {
            $issues[] = "{$projetsInvalides} projets avec service_id invalide";
        }

        return $issues;
    }

    private function checkCategorieRelations(): array
    {
        $issues = [];

        // Publications avec categorie_id invalide
        $publicationsInvalides = DB::table('publications as p')
            ->leftJoin('categories as c', 'p.categorie_id', '=', 'c.id')
            ->whereNotNull('p.categorie_id')
            ->whereNull('c.id')
            ->count();

        if ($publicationsInvalides > 0) {
            $issues[] = "{$publicationsInvalides} publications avec categorie_id invalide";
        }

        return $issues;
    }

    private function fixIssues(): void
    {
        $fixed = 0;

        // Corriger les relations invalides en les mettant à NULL
        $tables = [
            ['table' => 'actualites', 'column' => 'auteur_id', 'reference' => 'auteurs'],
            ['table' => 'media', 'column' => 'projet_id', 'reference' => 'projets'],
            ['table' => 'projets', 'column' => 'service_id', 'reference' => 'services'],
            ['table' => 'publications', 'column' => 'categorie_id', 'reference' => 'categories'],
        ];

        foreach ($tables as $relation) {
            if (!Schema::hasColumn($relation['table'], $relation['column'])) {
                continue;
            }

            $count = DB::table($relation['table'] . ' as t')
                ->leftJoin($relation['reference'] . ' as r', 't.' . $relation['column'], '=', 'r.id')
                ->whereNotNull('t.' . $relation['column'])
                ->whereNull('r.id')
                ->update(['t.' . $relation['column'] => null, 't.updated_at' => now()]);

            if ($count > 0) {
                $this->line("   ✅ {$count} relations invalides corrigées dans {$relation['table']}");
                $fixed += $count;
            }
        }

        // Supprimer les relations pivot invalides
        if (Schema::hasTable('auteur_publication')) {
            $deletedPivot = DB::table('auteur_publication as ap')
                ->leftJoin('auteurs as a', 'ap.auteur_id', '=', 'a.id')
                ->leftJoin('publications as p', 'ap.publication_id', '=', 'p.id')
                ->where(function($query) {
                    $query->whereNull('a.id')->orWhereNull('p.id');
                })
                ->delete();

            if ($deletedPivot > 0) {
                $this->line("   ✅ {$deletedPivot} relations pivot invalides supprimées");
                $fixed += $deletedPivot;
            }
        }

        if ($fixed > 0) {
            $this->info("🎉 {$fixed} problème(s) corrigé(s) automatiquement");
        } else {
            $this->comment("ℹ️  Aucune correction automatique nécessaire");
        }
    }
}
