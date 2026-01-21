<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Projet;
use App\Models\Rapport;

class ProjetRapportSeeder extends Seeder
{
    public function run()
    {
        // Récupérer le projet
        $projet = Projet::where('slug', 'renforcement-capacites-leaders-communautaires')->first();

        if (!$projet) {
            $this->command->error('Projet non trouvé');
            return;
        }

        // Créer quelques rapports de test
        $rapports = [
            [
                'titre' => 'Rapport Annuel - Renforcement des Capacités 2024',
                'slug' => '20241204-rapport-annuel-renforcement-capacites-2024',
                'description' => '<p>Rapport annuel détaillant les activités et résultats du programme de renforcement des capacités des leaders communautaires pour l\'année 2024.</p>',
                'fichier' => 'rapports/rapport-annuel-2024.pdf',
                'date_publication' => '2024-12-01',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'titre' => 'Étude d\'Impact - Leadership Communautaire',
                'slug' => '20241204-etude-impact-leadership-communautaire',
                'description' => '<p>Étude approfondie sur l\'impact du programme de formation sur le leadership communautaire et la gouvernance locale.</p>',
                'fichier' => 'rapports/etude-impact-leadership.pdf',
                'date_publication' => '2024-11-15',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'titre' => 'Guide Pratique - Résolution de Conflits Fonciers',
                'slug' => '20241204-guide-pratique-resolution-conflits',
                'description' => '<p>Guide pratique destiné aux leaders communautaires pour la résolution pacifique des conflits fonciers.</p>',
                'fichier' => 'rapports/guide-resolution-conflits.pdf',
                'date_publication' => '2024-10-20',
                'is_published' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($rapports as $rapportData) {
            $rapport = Rapport::firstOrCreate(
                ['slug' => $rapportData['slug']],
                $rapportData
            );
            
            // Attacher le rapport au projet
            if (!$projet->rapports->contains($rapport->id)) {
                $projet->rapports()->attach($rapport->id);
                $this->command->info("✅ Rapport attaché : {$rapport->titre}");
            } else {
                $this->command->warn("⚠️  Rapport déjà attaché : {$rapport->titre}");
            }
        }

        $this->command->info("📊 Total rapports liés au projet : " . $projet->rapports()->count());
    }
}
