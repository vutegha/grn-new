<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Projet;
use App\Models\Service;

class ProjetTestSeeder extends Seeder
{
    public function run()
    {
        // Récupérer ou créer des services
        $service1 = Service::firstOrCreate(
            ['slug' => 'gouvernance-fonciere'],
            ['nom' => 'Gouvernance Foncière']
        );
        
        $service2 = Service::firstOrCreate(
            ['slug' => 'ressources-naturelles'],
            ['nom' => 'Ressources Naturelles']
        );

        // Créer les projets de test
        $projets = [
            [
                'nom' => 'Cartographie Participative des Terres Communautaires',
                'slug' => 'cartographie-participative-terres-communautaires',
                'description' => '<p>Ce projet vise à cartographier les terres communautaires en RDC en impliquant les communautés locales dans le processus de collecte de données et de validation.</p><p>Objectifs : Renforcer les droits fonciers des communautés, prévenir les conflits fonciers, et promouvoir une gestion durable des ressources naturelles.</p>',
                'resume' => 'Cartographie participative des terres communautaires pour renforcer les droits fonciers et prévenir les conflits.',
                'service_id' => $service1->id,
                'date_debut' => '2024-01-15',
                'date_fin' => '2026-12-31',
                'etat' => 'en cours',
                'beneficiaires_hommes' => 450,
                'beneficiaires_femmes' => 520,
                'beneficiaires_enfants' => 230,
                'budget' => 150000.00,
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'nom' => 'Programme de Renforcement des Capacités des Leaders Communautaires',
                'slug' => 'renforcement-capacites-leaders-communautaires',
                'description' => '<p>Formation intensive des leaders communautaires sur la gouvernance foncière, les droits fonciers, et la résolution des conflits.</p><p>Ce programme comprend des ateliers pratiques, des études de cas, et un accompagnement sur le terrain.</p>',
                'resume' => 'Formation des leaders communautaires sur la gouvernance foncière et la résolution des conflits.',
                'service_id' => $service1->id,
                'date_debut' => '2023-06-01',
                'date_fin' => '2025-05-31',
                'etat' => 'en cours',
                'beneficiaires_hommes' => 120,
                'beneficiaires_femmes' => 80,
                'budget' => 85000.00,
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'nom' => 'Étude sur l\'Impact des Exploitations Minières sur les Forêts',
                'slug' => 'etude-impact-exploitations-minieres-forets',
                'description' => '<p>Recherche approfondie sur les impacts environnementaux et sociaux des activités minières dans les zones forestières de la RDC.</p><p>Méthodologie : Analyse satellite, enquêtes de terrain, et consultations avec les communautés affectées.</p>',
                'resume' => 'Évaluation des impacts environnementaux et sociaux des exploitations minières en zones forestières.',
                'service_id' => $service2->id,
                'date_debut' => '2024-03-01',
                'date_fin' => '2024-11-30',
                'etat' => 'terminé',
                'beneficiaires_total' => 0,
                'budget' => 45000.00,
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'nom' => 'Plaidoyer pour la Réforme de la Législation Foncière',
                'slug' => 'plaidoyer-reforme-legislation-fonciere',
                'description' => '<p>Campagne de plaidoyer visant à influencer les politiques foncières nationales pour une meilleure protection des droits des communautés.</p><p>Actions : Rencontres avec les décideurs, publications de rapports, mobilisation de la société civile.</p>',
                'resume' => 'Campagne de plaidoyer pour améliorer la législation foncière en faveur des communautés locales.',
                'service_id' => $service1->id,
                'date_debut' => '2025-01-01',
                'date_fin' => '2027-12-31',
                'etat' => 'planifié',
                'beneficiaires_total' => 0,
                'budget' => 200000.00,
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'nom' => 'Gestion Durable des Ressources en Eau',
                'slug' => 'gestion-durable-ressources-eau',
                'description' => '<p>Initiative pour promouvoir une gestion équitable et durable des ressources en eau dans les communautés rurales.</p><p>Comprend la construction de points d\'eau, la formation de comités de gestion, et la sensibilisation à l\'hygiène.</p>',
                'resume' => 'Promotion d\'une gestion équitable et durable des ressources en eau en milieu rural.',
                'service_id' => $service2->id,
                'date_debut' => '2023-09-01',
                'date_fin' => '2025-08-31',
                'etat' => 'en cours',
                'beneficiaires_hommes' => 680,
                'beneficiaires_femmes' => 720,
                'beneficiaires_enfants' => 450,
                'budget' => 175000.00,
                'is_published' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($projets as $projetData) {
            Projet::updateOrCreate(
                ['slug' => $projetData['slug']],
                $projetData
            );
        }

        $this->command->info('✅ 5 projets de test créés avec succès !');
        $this->command->info('États : en cours, terminé, planifié');
        $this->command->info('Total projets : ' . Projet::count());
    }
}
