<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Auteur;
use Illuminate\Support\Str;

class AuteurTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $auteurs = [
            [
                'nom' => 'Mukendi',
                'prenom' => 'Jean-Pierre',
                'email' => 'jp.mukendi@grn-ucbc.org',
                'telephone' => '+243 812 345 678',
                'institution' => 'Université Catholique de Bukavu',
                'titre_professionnel' => 'Professeur de Géographie et Développement Rural',
                'biographie' => "Dr. Jean-Pierre Mukendi est un géographe et expert en développement rural avec plus de 15 ans d'expérience dans la recherche sur la gestion des ressources naturelles en RDC. Il a dirigé de nombreux projets de cartographie participative et de sécurisation foncière dans les provinces du Sud-Kivu et du Nord-Kivu.\n\nSes recherches portent principalement sur les systèmes fonciers coutumiers, la gouvernance des terres et les conflits liés aux ressources naturelles. Il est auteur de plusieurs publications scientifiques dans des revues internationales et a contribué à l'élaboration de politiques foncières au niveau national.",
                'orcid' => '0000-0002-1234-5678',
                'linkedin' => 'https://linkedin.com/in/jp-mukendi',
                'twitter' => 'https://twitter.com/jpmukendi',
                'researchgate' => 'https://www.researchgate.net/profile/Jean-Pierre-Mukendi',
                'website' => 'https://jpmukendi.com',
                'active' => true,
            ],
            [
                'nom' => 'Kahindo',
                'prenom' => 'Marie-Claire',
                'email' => 'mc.kahindo@grn-ucbc.org',
                'telephone' => '+243 997 654 321',
                'institution' => 'Institut de Recherche en Sciences Humaines (IRSH)',
                'titre_professionnel' => 'Chercheuse en Anthropologie Sociale',
                'biographie' => "Marie-Claire Kahindo est une anthropologue spécialisée dans les questions de genre et de droits fonciers des femmes en milieu rural. Titulaire d'un doctorat en anthropologie sociale, elle mène des recherches ethnographiques approfondies sur les pratiques foncières et les dynamiques sociales dans les communautés rurales de l'est de la RDC.\n\nSes travaux ont permis de mettre en lumière les obstacles auxquels font face les femmes dans l'accès et le contrôle des terres. Elle collabore régulièrement avec des organisations internationales pour promouvoir l'égalité des genres dans les politiques foncières.",
                'orcid' => '0000-0003-9876-5432',
                'linkedin' => 'https://linkedin.com/in/marie-claire-kahindo',
                'facebook' => 'https://facebook.com/mc.kahindo.researcher',
                'researchgate' => 'https://www.researchgate.net/profile/Marie-Kahindo',
                'active' => true,
            ],
            [
                'nom' => 'Tshiama',
                'prenom' => 'Patrick',
                'email' => 'p.tshiama@grn-ucbc.org',
                'telephone' => '+243 826 789 012',
                'institution' => 'Centre de Recherche en Environnement et Développement',
                'titre_professionnel' => 'Expert en Cartographie et SIG',
                'biographie' => "Patrick Tshiama est un expert en systèmes d'information géographique (SIG) et en cartographie participative. Ingénieur géomètre de formation, il s'est spécialisé dans l'utilisation des technologies géospatiales pour la sécurisation foncière et la gestion des ressources naturelles.\n\nIl a développé plusieurs outils innovants de cartographie participative utilisés dans de nombreux projets de développement rural en RDC. Ses compétences incluent la télédétection, l'analyse spatiale, et la formation des communautés locales à l'utilisation des outils cartographiques.",
                'orcid' => '0000-0001-2468-1357',
                'linkedin' => 'https://linkedin.com/in/patrick-tshiama',
                'github' => 'https://github.com/ptshiama',
                'researchgate' => 'https://www.researchgate.net/profile/Patrick-Tshiama',
                'active' => true,
            ],
            [
                'nom' => 'Bisimwa',
                'prenom' => 'Claudine',
                'email' => 'c.bisimwa@grn-ucbc.org',
                'telephone' => '+243 978 123 456',
                'institution' => 'Université de Kinshasa',
                'titre_professionnel' => 'Sociologue et Spécialiste en Développement Communautaire',
                'biographie' => "Claudine Bisimwa est sociologue et experte en développement communautaire. Elle a consacré sa carrière à l'étude des dynamiques sociales et des mécanismes de résolution des conflits fonciers dans les communautés rurales.\n\nSes recherches actuelles portent sur les innovations sociales dans la gestion collective des ressources naturelles et sur les processus de médiation foncière. Elle a publié de nombreux articles sur les pratiques coutumières et leur évolution face aux changements socio-économiques contemporains.",
                'orcid' => '0000-0004-7890-1234',
                'linkedin' => 'https://linkedin.com/in/claudine-bisimwa',
                'twitter' => 'https://twitter.com/cbisimwa',
                'instagram' => 'https://instagram.com/cbisimwa_research',
                'active' => true,
            ],
            [
                'nom' => 'Kabongo',
                'prenom' => 'Emmanuel',
                'email' => 'e.kabongo@grn-ucbc.org',
                'telephone' => '+243 851 234 567',
                'institution' => 'Institut Supérieur de Développement Rural',
                'titre_professionnel' => 'Agronome et Expert en Agroforesterie',
                'biographie' => "Emmanuel Kabongo est agronome et spécialiste des systèmes agroforestiers. Ses recherches portent sur l'intégration des arbres dans les systèmes agricoles et leur contribution à la sécurité alimentaire et à la conservation des sols.\n\nIl a travaillé sur plusieurs projets pilotes d'agroforesterie dans l'est de la RDC, démontrant comment les pratiques agroforestières peuvent améliorer la productivité agricole tout en préservant l'environnement. Il est également formateur en techniques agricoles durables.",
                'orcid' => '0000-0002-3456-7890',
                'linkedin' => 'https://linkedin.com/in/emmanuel-kabongo',
                'researchgate' => 'https://www.researchgate.net/profile/Emmanuel-Kabongo',
                'website' => 'https://agroforesterie-rdc.org',
                'active' => true,
            ],
        ];

        foreach ($auteurs as $auteurData) {
            Auteur::updateOrCreate(
                ['email' => $auteurData['email']],
                $auteurData
            );
        }

        $this->command->info('✅ ' . count($auteurs) . ' auteurs de test créés avec succès!');
    }
}
