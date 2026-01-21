<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Partenaire;

class PartenaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partenaires = [
            [
                'nom' => 'Université de Kinshasa (UNIKIN)',
                'type' => 'universite',
                'description' => 'Partenariat académique et de recherche avec l\'Université de Kinshasa pour le développement de programmes d\'études et de projets de recherche conjoints.',
                'domaines_collaboration' => json_encode(['recherche', 'formation', 'echange_etudiants']),
                'statut' => 'actif',
                'date_debut_partenariat' => '2020-01-15',
                'site_web' => 'https://www.unikin.ac.cd',
                'email_contact' => 'partenariats@unikin.ac.cd',
                'telephone' => '+243 81 234 5678',
                'adresse' => 'Avenue de l\'Université, Kinshasa, RDC',
                'pays' => 'RDC',
                'ordre_affichage' => 1,
                'afficher_publiquement' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Université Pédagogique Nationale (UPN)',
                'type' => 'universite',
                'description' => 'Collaboration pour la formation des formateurs et le développement de curricula innovants.',
                'domaines_collaboration' => json_encode(['formation', 'recherche_pedagogique']),
                'statut' => 'actif',
                'date_debut_partenariat' => '2019-09-10',
                'site_web' => 'https://www.upn.ac.cd',
                'email_contact' => 'cooperation@upn.ac.cd',
                'telephone' => '+243 81 876 5432',
                'adresse' => 'Avenue Tombalbaye, Kinshasa, RDC',
                'pays' => 'RDC',
                'ordre_affichage' => 2,
                'afficher_publiquement' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Organisation Mondiale de la Santé (OMS)',
                'type' => 'organisation_internationale',
                'description' => 'Partenariat pour la recherche en santé publique et le développement de politiques sanitaires.',
                'domaines_collaboration' => json_encode(['sante_publique', 'recherche', 'politique_sanitaire']),
                'statut' => 'actif',
                'date_debut_partenariat' => '2021-03-20',
                'site_web' => 'https://www.who.int',
                'email_contact' => 'congo@who.int',
                'telephone' => '+243 81 345 6789',
                'adresse' => 'Avenue des Cliniques, Kinshasa, RDC',
                'pays' => 'RDC',
                'ordre_affichage' => 1,
                'afficher_publiquement' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Centre de Recherche en Sciences Naturelles (CRSN)',
                'type' => 'centre_recherche',
                'description' => 'Collaboration scientifique pour la recherche en biodiversité et conservation.',
                'domaines_collaboration' => json_encode(['biodiversite', 'conservation', 'recherche_scientifique']),
                'statut' => 'actif',
                'date_debut_partenariat' => '2020-06-15',
                'site_web' => 'https://www.crsn-lwiro.cd',
                'email_contact' => 'direction@crsn-lwiro.cd',
                'telephone' => '+243 81 456 7890',
                'adresse' => 'Lwiro, Sud-Kivu, RDC',
                'pays' => 'RDC',
                'ordre_affichage' => 2,
                'afficher_publiquement' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Banque Mondiale',
                'type' => 'organisation_internationale',
                'description' => 'Partenariat pour le développement économique et social à travers la recherche appliquée.',
                'domaines_collaboration' => json_encode(['developpement_economique', 'politique_sociale']),
                'statut' => 'actif',
                'date_debut_partenariat' => '2021-11-08',
                'site_web' => 'https://www.worldbank.org',
                'email_contact' => 'congo@worldbank.org',
                'telephone' => '+243 81 567 8901',
                'adresse' => 'Boulevard du 30 Juin, Kinshasa, RDC',
                'pays' => 'RDC',
                'ordre_affichage' => 1,
                'afficher_publiquement' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Institut Supérieur de Statistiques (ISS)',
                'type' => 'universite',
                'description' => 'Collaboration pour la recherche en statistiques appliquées et analyse de données.',
                'domaines_collaboration' => json_encode(['statistiques', 'analyse_donnees', 'methodologie']),
                'statut' => 'actif',
                'date_debut_partenariat' => '2020-02-12',
                'site_web' => 'https://www.iss-kinshasa.cd',
                'email_contact' => 'partenariats@iss-kinshasa.cd',
                'telephone' => '+243 81 678 9012',
                'adresse' => 'Avenue Kasa-Vubu, Kinshasa, RDC',
                'pays' => 'RDC',
                'ordre_affichage' => 3,
                'afficher_publiquement' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($partenaires as $partenaire) {
            Partenaire::create($partenaire);
        }
    }
}
