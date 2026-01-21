<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobOffer;
use Carbon\Carbon;

class JobOffersSeeder extends Seeder
{
    public function run(): void
    {
        $jobOffers = [
            [
                'title' => 'Chargé(e) de Recherche en Développement Rural',
                'description' => "Nous recherchons un(e) chargé(e) de recherche passionné(e) pour rejoindre notre équipe et contribuer aux projets de développement rural.\n\nMissions principales :\n- Concevoir et conduire des études de terrain\n- Analyser les données socio-économiques\n- Rédiger des rapports de recherche\n- Participer aux missions sur le terrain\n- Collaborer avec les équipes pluridisciplinaires",
                'type' => 'full-time',
                'location' => 'Kinshasa, RDC',
                'department' => 'Recherche et Développement',
                'source' => 'internal',
                'status' => 'active',
                'application_deadline' => Carbon::now()->addDays(30),
                'requirements' => [
                    'Master en Développement Rural, Agriculture ou domaine connexe',
                    'Minimum 3 ans d\'expérience en recherche appliquée',
                    'Excellentes compétences en rédaction et communication',
                    'Maîtrise du français et de l\'anglais',
                    'Capacité à travailler sur le terrain',
                    'Expérience avec les outils statistiques (SPSS, R, etc.)'
                ],
                'criteria' => [
                    [
                        'question' => 'Avez-vous déjà mené des enquêtes de terrain ?',
                        'type' => 'radio',
                        'options' => ['Oui, plusieurs fois', 'Oui, quelques fois', 'Non, jamais'],
                        'required' => true
                    ],
                    [
                        'question' => 'Décrivez votre expérience avec les communautés rurales',
                        'type' => 'textarea',
                        'required' => true,
                        'description' => 'Expliquez votre approche de travail avec les communautés locales'
                    ],
                    [
                        'question' => 'Niveau de maîtrise des logiciels statistiques',
                        'type' => 'select',
                        'options' => ['Débutant', 'Intermédiaire', 'Avancé', 'Expert'],
                        'required' => true
                    ]
                ],
                'benefits' => "Avantages :\n- Salaire compétitif\n- Assurance maladie\n- Formation continue\n- Opportunités de mission internationale\n- Environnement de travail stimulant",
                'salary_min' => 800,
                'salary_max' => 1200,
                'positions_available' => 2,
                'contact_email' => 'iri@ucbc.org',
                'is_featured' => true,
            ],
            [
                'title' => 'Coordinateur(trice) de Projets',
                'description' => "Rejoignez notre équipe pour coordonner et superviser l'exécution de nos projets de développement communautaire.\n\nResponsabilités :\n- Planifier et coordonner les activités des projets\n- Superviser les équipes sur le terrain\n- Assurer le suivi-évaluation des activités\n- Gérer les relations avec les partenaires\n- Produire les rapports d'avancement",
                'type' => 'contract',
                'location' => 'Kinshasa, RDC',
                'department' => 'Gestion de Projets',
                'source' => 'internal',
                'status' => 'active',
                'application_deadline' => Carbon::now()->addDays(20),
                'requirements' => [
                    'Licence en Gestion de Projets, Sciences Sociales ou équivalent',
                    'Expérience en coordination de projets de développement',
                    'Compétences en planification et suivi-évaluation',
                    'Capacité à travailler en équipe multiculturelle',
                    'Maîtrise des outils bureautiques',
                    'Permis de conduire souhaité'
                ],
                'criteria' => [
                    [
                        'question' => 'Combien d\'années d\'expérience avez-vous en gestion de projets ?',
                        'type' => 'select',
                        'options' => ['Moins de 1 an', '1-3 ans', '3-5 ans', '5-10 ans', 'Plus de 10 ans'],
                        'required' => true
                    ],
                    [
                        'question' => 'Avez-vous déjà travaillé avec des bailleurs de fonds internationaux ?',
                        'type' => 'radio',
                        'options' => ['Oui', 'Non'],
                        'required' => true
                    ]
                ],
                'benefits' => "Package attractif incluant :\n- Contrat de 18 mois renouvelable\n- Prime de performance\n- Transport assuré\n- Formation en gestion de projet",
                'salary_min' => 600,
                'salary_max' => 900,
                'positions_available' => 1,
                'contact_email' => 'iri@ucbc.org',
            ],
            [
                'title' => 'Assistant(e) de Communication',
                'description' => "Opportunité de stage pour un(e) étudiant(e) motivé(e) souhaitant acquérir une expérience en communication institutionnelle.\n\nActivités :\n- Gestion des réseaux sociaux\n- Rédaction d'articles et communiqués\n- Support événementiel\n- Création de contenus visuels\n- Veille médiatique",
                'type' => 'internship',
                'location' => 'Kinshasa, RDC',
                'department' => 'Communication',
                'source' => 'internal',
                'status' => 'active',
                'application_deadline' => Carbon::now()->addDays(15),
                'requirements' => [
                    'Étudiant(e) en Communication, Journalisme ou domaine connexe',
                    'Compétences en rédaction et réseaux sociaux',
                    'Créativité et sens de l\'initiative',
                    'Disponibilité minimum 3 mois',
                    'Maîtrise des outils de design (Photoshop, Canva, etc.)',
                    'Portfolio de travaux précédents souhaité'
                ],
                'criteria' => [
                    [
                        'question' => 'Quels outils de design maîtrisez-vous ?',
                        'type' => 'text',
                        'required' => false,
                        'description' => 'Listez les logiciels que vous savez utiliser'
                    ],
                    [
                        'question' => 'Avez-vous un portfolio en ligne ?',
                        'type' => 'text',
                        'required' => false,
                        'description' => 'Si oui, partagez le lien'
                    ]
                ],
                'benefits' => "Stage rémunéré avec :\n- Indemnité mensuelle\n- Encadrement personnalisé\n- Certificat de stage\n- Possibilité d'embauche",
                'salary_min' => 200,
                'salary_max' => 300,
                'positions_available' => 1,
                'contact_email' => 'iri@ucbc.org',
            ],
            [
                'title' => 'Consultant en Microfinance',
                'description' => "Notre partenaire MicroCredit Plus recherche un consultant expérimenté en microfinance pour accompagner le développement de leurs programmes.\n\nMission :\n- Évaluer les besoins en microfinance\n- Développer des produits financiers adaptés\n- Former les équipes locales\n- Suivre la performance des programmes",
                'type' => 'freelance',
                'location' => 'Lubumbashi, RDC',
                'department' => 'Finance',
                'source' => 'partner',
                'partner_name' => 'MicroCredit Plus',
                'status' => 'active',
                'application_deadline' => Carbon::now()->addDays(25),
                'requirements' => [
                    'Master en Finance, Économie ou domaine connexe',
                    'Minimum 5 ans d\'expérience en microfinance',
                    'Expérience en Afrique subsaharienne',
                    'Compétences en analyse financière',
                    'Capacité à former et encadrer'
                ],
                'benefits' => "Mission de consultation avec :\n- Honoraires compétitifs\n- Frais de mission pris en charge\n- Hébergement assuré\n- Flexibilité horaire",
                'salary_negotiable' => true,
                'positions_available' => 1,
                'contact_email' => 'iri@ucbc.org',
                'is_featured' => false,
            ],
            [
                'title' => 'Spécialiste en Suivi-Évaluation',
                'description' => "Poste expiré pour référence - Nous recherchions un spécialiste en suivi-évaluation pour nos programmes de développement.",
                'type' => 'full-time',
                'location' => 'Kinshasa, RDC',
                'department' => 'Suivi-Évaluation',
                'source' => 'internal',
                'status' => 'expired',
                'application_deadline' => Carbon::now()->subDays(5),
                'requirements' => [
                    'Master en Suivi-Évaluation ou équivalent',
                    'Expérience avec les cadres logiques',
                    'Maîtrise des outils de collecte de données'
                ],
                'salary_min' => 700,
                'salary_max' => 1000,
                'positions_available' => 1,
                'contact_email' => 'iri@ucbc.org',
            ]
        ];

        foreach ($jobOffers as $jobData) {
            JobOffer::create($jobData);
        }
    }
}
