<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ChercheurAffilie;

class ChercheurAffilieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chercheurs = [
            [
                'nom' => 'Mukendi',
                'prenom' => 'Jean-Baptiste',
                'email' => 'jb.mukendi@iri.ucbc.org',
                'telephone' => '+243 81 234 5678',
                'titre_academique' => 'Professeur',
                'institution_origine' => 'Université de Kinshasa',
                'departement' => 'Faculté des Sciences',
                'domaine_recherche' => json_encode(['biotechnologie', 'genetique', 'biologie_moleculaire']),
                'specialites' => json_encode(['Biotechnologie Agricole']),
                'biographie' => 'Professeur Jean-Baptiste Mukendi est un expert reconnu en biotechnologie agricole avec plus de 15 ans d\'expérience dans la recherche et l\'enseignement. Il a dirigé plusieurs projets de recherche sur l\'amélioration des cultures vivrières en RDC.',
                'orcid' => '0000-0002-1234-5678',
                'google_scholar' => 'ABC123DEF',
                'researchgate' => 'Jean-Baptiste_Mukendi',
                'linkedin' => 'jean-baptiste-mukendi',
                'statut' => 'actif',
                'date_affiliation' => '2020-01-15',
                'publications_collaboratives' => json_encode(['Biotechnology in African Agriculture', 'Genetic Diversity in Cassava Crops']),
                'projets_collaboration' => json_encode(['Amélioration des rendements agricoles', 'Conservation de la biodiversité']),
                'contributions' => json_encode(['Direction de projets de recherche', 'Formation d\'étudiants']),
                'afficher_publiquement' => true,
                'ordre_affichage' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Kalala',
                'prenom' => 'Marie',
                'email' => 'm.kalala@iri.ucbc.org',
                'telephone' => '+243 81 876 5432',
                'titre_academique' => 'Docteur',
                'institution_origine' => 'Université Pédagogique Nationale',
                'departement' => 'Sciences de l\'Éducation',
                'domaine_recherche' => json_encode(['pedagogie', 'formation_enseignants', 'curriculum']),
                'specialites' => json_encode(['Pédagogie Innovante']),
                'biographie' => 'Dr. Marie Kalala se spécialise dans le développement de méthodes pédagogiques innovantes pour l\'enseignement supérieur. Elle a formé plus de 200 enseignants à travers le pays.',
                'orcid' => '0000-0003-9876-5432',
                'google_scholar' => 'XYZ789GHI',
                'researchgate' => 'Marie_Kalala',
                'linkedin' => 'marie-kalala',
                'statut' => 'actif',
                'date_affiliation' => '2019-09-10',
                'publications_collaboratives' => json_encode(['Innovative Teaching Methods in Higher Education', 'Teacher Training in Developing Countries']),
                'projets_collaboration' => json_encode(['Formation des formateurs', 'Développement curriculaire']),
                'contributions' => json_encode(['Développement de méthodes pédagogiques', 'Formation d\'enseignants']),
                'afficher_publiquement' => true,
                'ordre_affichage' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Batumike',
                'prenom' => 'Antoine',
                'email' => 'a.batumike@iri.ucbc.org',
                'telephone' => '+243 81 456 7890',
                'titre_academique' => 'Professeur',
                'institution_origine' => 'Centre de Recherche en Sciences Naturelles',
                'departement' => 'Biodiversité et Conservation',
                'domaine_recherche' => json_encode(['biodiversite', 'conservation', 'ecologie']),
                'specialites' => json_encode(['Conservation de la Biodiversité']),
                'biographie' => 'Prof. Antoine Batumike est un écologiste renommé spécialisé dans la conservation de la biodiversité en Afrique centrale. Il a publié plus de 50 articles scientifiques.',
                'orcid' => '0000-0001-2468-1357',
                'google_scholar' => 'DEF456JKL',
                'researchgate' => 'Antoine_Batumike',
                'linkedin' => 'antoine-batumike',
                'statut' => 'actif',
                'date_affiliation' => '2020-06-15',
                'publications_collaboratives' => json_encode(['Biodiversity Conservation in Central Africa', 'Ecosystem Services in Congo Basin']),
                'projets_collaboration' => json_encode(['Conservation du Bassin du Congo', 'Écosystèmes durables']),
                'contributions' => json_encode(['Recherche en conservation', 'Publications scientifiques']),
                'afficher_publiquement' => true,
                'ordre_affichage' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Mbombo',
                'prenom' => 'Claire',
                'email' => 'c.mbombo@iri.ucbc.org',
                'telephone' => '+243 81 678 9012',
                'titre_academique' => 'Professeur',
                'institution_origine' => 'Institut Supérieur de Statistiques',
                'departement' => 'Statistiques Appliquées',
                'domaine_recherche' => json_encode(['statistiques', 'analyse_donnees', 'methodologie']),
                'specialites' => json_encode(['Analyse de Données Complexes']),
                'biographie' => 'Prof. Claire Mbombo est experte en analyse statistique et méthodologie de recherche. Elle conseille plusieurs organisations internationales sur les méthodes d\'analyse de données.',
                'orcid' => '0000-0004-3691-2580',
                'google_scholar' => 'GHI789MNO',
                'researchgate' => 'Claire_Mbombo',
                'linkedin' => 'claire-mbombo',
                'statut' => 'actif',
                'date_affiliation' => '2020-02-12',
                'publications_collaboratives' => json_encode(['Advanced Statistical Methods', 'Data Analysis in Social Sciences']),
                'projets_collaboration' => json_encode(['Méthodologies de recherche', 'Analyse de données sociales']),
                'contributions' => json_encode(['Conseils méthodologiques', 'Formation en statistiques']),
                'afficher_publiquement' => true,
                'ordre_affichage' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Nsimba',
                'prenom' => 'David',
                'email' => 'd.nsimba@iri.ucbc.org',
                'telephone' => '+243 81 789 0123',
                'titre_academique' => 'Docteur',
                'institution_origine' => 'Université de Lubumbashi',
                'departement' => 'Sciences Économiques',
                'domaine_recherche' => json_encode(['economie_developpement', 'microfinance', 'entrepreneuriat']),
                'specialites' => json_encode(['Économie du Développement']),
                'biographie' => 'Dr. David Nsimba se concentre sur l\'économie du développement et l\'entrepreneuriat en Afrique. Il a développé plusieurs modèles économiques pour les PME.',
                'orcid' => '0000-0005-7410-9630',
                'google_scholar' => 'JKL012PQR',
                'researchgate' => 'David_Nsimba',
                'linkedin' => 'david-nsimba',
                'statut' => 'actif',
                'date_affiliation' => '2021-04-20',
                'publications_collaboratives' => json_encode(['Microfinance and Development', 'Entrepreneurship in Africa']),
                'projets_collaboration' => json_encode(['Développement économique local', 'Microfinance rurale']),
                'contributions' => json_encode(['Modèles économiques pour PME', 'Conseil en entrepreneuriat']),
                'afficher_publiquement' => true,
                'ordre_affichage' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($chercheurs as $chercheur) {
            ChercheurAffilie::create($chercheur);
        }
    }
}
