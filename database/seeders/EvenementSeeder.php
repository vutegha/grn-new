<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Evenement;
use Carbon\Carbon;

class EvenementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vider la table d'abord
        Evenement::truncate();

        $evenements = [
            [
                'titre' => 'Conférence internationale sur les droits de l\'homme',
                'resume' => 'Une conférence importante réunissant experts et activistes pour discuter des enjeux actuels des droits humains en Afrique centrale.',
                'description' => 'Cette conférence internationale rassemblera des experts, des militants et des décideurs pour examiner les défis et opportunités en matière de droits de l\'homme dans la région. Les discussions porteront sur la justice transitionnelle, la protection des défenseurs des droits humains, et l\'impact des conflits sur les populations civiles.',
                'image' => null,
                'date_evenement' => Carbon::now()->addDays(30)->toDateString(),
                'lieu' => 'Centre de Conférences de Bujumbura',
                'rapport_url' => null
            ],
            [
                'titre' => 'Formation des journalistes sur la couverture des droits humains',
                'resume' => 'Atelier de formation destiné aux journalistes locaux pour améliorer leur couverture des questions de droits de l\'homme.',
                'description' => 'Un programme de formation intensive pour renforcer les capacités des journalistes dans le traitement éthique et professionnel des sujets liés aux droits humains.',
                'image' => null,
                'date_evenement' => Carbon::now()->subDays(1)->toDateString(),
                'lieu' => 'Salle de formation IRI',
                'rapport_url' => null
            ],
            [
                'titre' => 'Forum national sur la réconciliation',
                'resume' => 'Grand forum réunissant la société civile, le gouvernement et les communautés pour discuter des mécanismes de réconciliation.',
                'description' => 'Ce forum a permis de créer un dialogue constructif entre les différentes parties prenantes pour identifier les voies vers une réconciliation durable au Burundi.',
                'image' => null,
                'date_evenement' => Carbon::now()->subDays(38)->toDateString(),
                'lieu' => 'Palais des Congrès, Bujumbura',
                'rapport_url' => 'https://example.com/rapport-forum-reconciliation.pdf'
            ],
            [
                'titre' => 'Atelier sur la protection des femmes défenseures',
                'resume' => 'Atelier de renforcement des capacités pour la protection et la sécurité des femmes défenseures des droits humains.',
                'description' => 'Session de formation spécialisée axée sur les stratégies de protection et de sécurité pour les femmes qui défendent les droits humains.',
                'image' => null,
                'date_evenement' => Carbon::now()->subDays(13)->toDateString(),
                'lieu' => 'Centre communautaire Kamenge',
                'rapport_url' => 'https://example.com/rapport-atelier-femmes.pdf'
            ],
            [
                'titre' => 'Campagne de sensibilisation dans les écoles',
                'resume' => 'Programme de sensibilisation sur les droits de l\'enfant dans les établissements scolaires de la capitale.',
                'description' => 'Une série de visites dans les écoles pour sensibiliser les élèves et enseignants aux droits de l\'enfant et à la prévention de la violence en milieu scolaire.',
                'image' => null,
                'date_evenement' => Carbon::now()->addDays(45)->toDateString(),
                'lieu' => 'Écoles de Bujumbura Mairie',
                'rapport_url' => null
            ]
        ];

        foreach ($evenements as $evenement) {
            Evenement::create($evenement);
        }
    }
}
