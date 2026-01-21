<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Partenaire;

class LogoPartenaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partenairesAvecLogos = [
            [
                'nom' => 'Institut de Recherche Innovante (IRI)',
                'type' => 'centre_recherche',
                'description' => 'L\'Institut de Recherche Innovante est un centre de recherche multidisciplinaire axé sur l\'innovation et le développement durable.',
                'logo' => 'assets/img/logos/iri.png',
                'domaines_collaboration' => json_encode(['recherche', 'innovation', 'developpement_durable']),
                'statut' => 'actif',
                'date_debut_partenariat' => '2018-01-15',
                'site_web' => 'https://www.iri-research.org',
                'email_contact' => 'iri@ucbc.org',
                'telephone' => '+243 81 123 4567',
                'adresse' => 'Avenue de la Recherche, Kinshasa, RDC',
                'pays' => 'RDC',
                'ordre_affichage' => 1,
                'afficher_publiquement' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Université Chrétienne Bilingue du Congo (UCBC)',
                'type' => 'universite',
                'description' => 'L\'Université Chrétienne Bilingue du Congo est une institution d\'enseignement supérieur dédiée à l\'excellence académique et aux valeurs chrétiennes.',
                'logo' => 'assets/img/logos/logo-ucbc.png',
                'domaines_collaboration' => json_encode(['formation', 'recherche', 'echange_etudiants']),
                'statut' => 'actif',
                'date_debut_partenariat' => '2015-09-01',
                'site_web' => 'https://www.ucbc.org',
                'email_contact' => 'iri@ucbc.org',
                'telephone' => '+243 81 234 5678',
                'adresse' => 'Avenue UCBC, Beni, Nord-Kivu, RDC',
                'pays' => 'RDC',
                'ordre_affichage' => 2,
                'afficher_publiquement' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Conseil National de Recherche et Formation (CONAREF)',
                'type' => 'organisation_internationale',
                'description' => 'Le CONAREF est un organisme national dédié à la coordination des activités de recherche et de formation dans le pays.',
                'logo' => 'assets/img/logos/logo-conaref.png',
                'domaines_collaboration' => json_encode(['recherche', 'formation', 'coordination_nationale']),
                'statut' => 'actif',
                'date_debut_partenariat' => '2019-03-20',
                'site_web' => 'https://www.conaref.cd',
                'email_contact' => 'iri@ucbc.org',
                'telephone' => '+243 81 345 6789',
                'adresse' => 'Boulevard du 30 Juin, Kinshasa, RDC',
                'pays' => 'RDC',
                'ordre_affichage' => 3,
                'afficher_publiquement' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Forum International de Gouvernance (FIG)',
                'type' => 'organisation_internationale',
                'description' => 'Le Forum International de Gouvernance promeut les bonnes pratiques de gouvernance et la transparence institutionnelle.',
                'logo' => 'assets/img/logos/logo-fig.jpg',
                'domaines_collaboration' => json_encode(['gouvernance', 'transparence', 'formation_institutionnelle']),
                'statut' => 'actif',
                'date_debut_partenariat' => '2020-11-15',
                'site_web' => 'https://www.fig-governance.org',
                'email_contact' => 'iri@ucbc.org',
                'telephone' => '+243 81 456 7890',
                'adresse' => 'Avenue de la Gouvernance, Kinshasa, RDC',
                'pays' => 'RDC',
                'ordre_affichage' => 4,
                'afficher_publiquement' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Global Land Tool Network (GLTN)',
                'type' => 'organisation_internationale',
                'description' => 'Le GLTN est un réseau international axé sur les outils fonciers et la gestion des terres pour un développement durable.',
                'logo' => 'assets/img/logos/logo-gltn.png',
                'domaines_collaboration' => json_encode(['gestion_fonciere', 'developpement_durable', 'outils_fonciers']),
                'statut' => 'actif',
                'date_debut_partenariat' => '2021-06-10',
                'site_web' => 'https://www.gltn.net',
                'email_contact' => 'iri@ucbc.org',
                'telephone' => '+254 20 762 3000',
                'adresse' => 'UN-Habitat, Nairobi, Kenya',
                'pays' => 'Kenya',
                'ordre_affichage' => 5,
                'afficher_publiquement' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Centre d\'Innovation (CI)',
                'type' => 'centre_recherche',
                'description' => 'Le Centre d\'Innovation se concentre sur le développement de technologies innovantes et de solutions créatives.',
                'logo' => 'assets/img/logos/cropped-ci-logo.webp',
                'domaines_collaboration' => json_encode(['innovation_technologique', 'recherche_appliquee', 'solutions_creatives']),
                'statut' => 'actif',
                'date_debut_partenariat' => '2022-02-28',
                'site_web' => 'https://www.centre-innovation.org',
                'email_contact' => 'iri@ucbc.org',
                'telephone' => '+243 81 567 8901',
                'adresse' => 'Quartier Innovation, Goma, Nord-Kivu, RDC',
                'pays' => 'RDC',
                'ordre_affichage' => 6,
                'afficher_publiquement' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Ajouter les partenaires avec leurs logos
        foreach ($partenairesAvecLogos as $partenaire) {
            // Vérifier si le partenaire n'existe pas déjà (par nom)
            $existant = Partenaire::where('nom', $partenaire['nom'])->first();
            if (!$existant) {
                Partenaire::create($partenaire);
                $this->command->info("Partenaire ajouté: " . $partenaire['nom']);
            } else {
                // Mettre à jour le logo s'il n'en a pas
                if (empty($existant->logo)) {
                    $existant->update(['logo' => $partenaire['logo']]);
                    $this->command->info("Logo mis à jour pour: " . $partenaire['nom']);
                } else {
                    $this->command->info("Partenaire existe déjà: " . $partenaire['nom']);
                }
            }
        }

        $this->command->info("Intégration des logos terminée!");
    }
}
