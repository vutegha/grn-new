<?php

namespace App\Services;

use App\Models\JobOffer;
use Illuminate\Support\Str;

class JobCriteriaService
{
    /**
     * Obtenir des templates de critères basés sur le titre du poste
     */
    public static function getTemplatesByJobTitle($title)
    {
        $title = strtolower($title);
        $templates = [];

        // Développement & IT
        if (Str::contains($title, ['développeur', 'developer', 'programmeur', 'full stack', 'backend', 'frontend', 'web'])) {
            $templates = array_merge($templates, [
                [
                    'type' => 'select',
                    'question' => 'Niveau d\'expérience en développement',
                    'options' => ['Junior (0-2 ans)', 'Confirmé (3-5 ans)', 'Senior (5+ ans)', 'Lead/Architect (7+ ans)'],
                    'required' => true,
                    'description' => 'Sélectionnez votre niveau d\'expérience principal'
                ],
                [
                    'type' => 'select',
                    'question' => 'Framework principal maîtrisé',
                    'options' => ['Laravel', 'Symfony', 'Vue.js', 'React', 'Angular', 'Node.js', 'Django'],
                    'required' => false,
                    'description' => 'Quel framework maîtrisez-vous le mieux ?'
                ],
                [
                    'type' => 'radio',
                    'question' => 'Préférence de travail',
                    'options' => ['100% présentiel', 'Hybride (2-3j télétravail/semaine)', '100% télétravail'],
                    'required' => true
                ],
                [
                    'type' => 'textarea',
                    'question' => 'Décrivez un projet technique dont vous êtes fier',
                    'required' => false,
                    'description' => 'Partagez un exemple concret de votre travail'
                ]
            ]);
        }

        // Recherche & Académique
        if (Str::contains($title, ['chercheur', 'recherche', 'scientifique', 'étude', 'analyse', 'enquête'])) {
            $templates = array_merge($templates, [
                [
                    'type' => 'textarea',
                    'question' => 'Décrivez votre expérience de terrain',
                    'required' => true,
                    'description' => 'Détaillez vos missions de terrain et collecte de données'
                ],
                [
                    'type' => 'select',
                    'question' => 'Logiciels statistiques maîtrisés',
                    'options' => ['SPSS', 'R', 'Python/Pandas', 'SAS', 'Stata', 'Excel avancé', 'Autre'],
                    'required' => true
                ],
                [
                    'type' => 'radio',
                    'question' => 'Expérience avec les communautés rurales',
                    'options' => ['Très expérimenté (5+ missions)', 'Expérimenté (2-4 missions)', 'Quelque expérience (1 mission)', 'Débutant'],
                    'required' => true
                ],
                [
                    'type' => 'select',
                    'question' => 'Langues parlées pour le terrain',
                    'options' => ['Français uniquement', 'Français + Anglais', 'Français + langues locales', 'Multilingue'],
                    'required' => false
                ]
            ]);
        }

        // Management & Direction
        if (Str::contains($title, ['manager', 'chef', 'responsable', 'directeur', 'coordinateur', 'superviseur'])) {
            $templates = array_merge($templates, [
                [
                    'type' => 'select',
                    'question' => 'Taille d\'équipe managée',
                    'options' => ['1-3 personnes', '4-10 personnes', '11-20 personnes', '20+ personnes', 'Pas d\'expérience management'],
                    'required' => true
                ],
                [
                    'type' => 'textarea',
                    'question' => 'Décrivez votre approche de management',
                    'required' => false,
                    'description' => 'Quel est votre style de leadership et de gestion d\'équipe ?'
                ],
                [
                    'type' => 'radio',
                    'question' => 'Expérience budgétaire',
                    'options' => ['Gestion budget < 50k€', 'Gestion budget 50k-200k€', 'Gestion budget > 200k€', 'Pas d\'expérience budgétaire'],
                    'required' => false
                ]
            ]);
        }

        // Communication & Marketing
        if (Str::contains($title, ['communication', 'marketing', 'rédacteur', 'content', 'social media', 'digital'])) {
            $templates = array_merge($templates, [
                [
                    'type' => 'select',
                    'question' => 'Outils de création maîtrisés',
                    'options' => ['Suite Adobe (Photoshop, Illustrator)', 'Canva/Figma', 'Outils vidéo (Premiere, After Effects)', 'WordPress/CMS', 'Autre'],
                    'required' => true
                ],
                [
                    'type' => 'textarea',
                    'question' => 'Portfolio ou exemples de réalisations',
                    'required' => false,
                    'description' => 'Partagez des liens ou décrivez vos meilleures créations'
                ],
                [
                    'type' => 'radio',
                    'question' => 'Spécialité privilégiée',
                    'options' => ['Réseaux sociaux', 'Création graphique', 'Rédaction/Contenu', 'Stratégie digitale', 'Événementiel'],
                    'required' => true
                ]
            ]);
        }

        // Finance & Comptabilité
        if (Str::contains($title, ['comptable', 'finance', 'contrôleur', 'audit', 'gestion'])) {
            $templates = array_merge($templates, [
                [
                    'type' => 'select',
                    'question' => 'Logiciels comptables maîtrisés',
                    'options' => ['SAP', 'Sage', 'Ciel', 'QuickBooks', 'Excel avancé', 'ERP spécifique'],
                    'required' => true
                ],
                [
                    'type' => 'radio',
                    'question' => 'Certifications professionnelles',
                    'options' => ['Expert-comptable', 'DSCG/DECF', 'Licence comptabilité', 'Formation interne', 'Autodidacte'],
                    'required' => false
                ]
            ]);
        }

        return $templates;
    }

    /**
     * Analyser les candidatures existantes pour suggérer des critères
     */
    public static function analyzeCandidatesAndSuggest($jobOffer)
    {
        $suggestions = [];
        $applications = $jobOffer->applications;

        if ($applications->count() < 3) {
            return [
                'message' => 'Pas assez de candidatures pour une analyse pertinente (minimum 3 requis)',
                'suggestions' => []
            ];
        }

        // Analyser les patterns dans les candidatures
        $commonSkills = self::extractCommonSkills($applications);
        $experienceLevels = self::analyzeExperienceLevels($applications);
        $educationPatterns = self::analyzeEducationPatterns($applications);

        // Suggérer des critères basés sur l'analyse
        if (count($commonSkills) > 2) {
            $suggestions[] = [
                'type' => 'select',
                'question' => 'Compétences techniques identifiées',
                'options' => array_slice($commonSkills, 0, 6),
                'required' => true,
                'source' => 'analysis',
                'confidence' => min(0.9, count($commonSkills) / 10),
                'description' => 'Basé sur l\'analyse de ' . $applications->count() . ' candidatures'
            ];
        }

        if (count($experienceLevels) > 1) {
            $suggestions[] = [
                'type' => 'radio',
                'question' => 'Niveau d\'expérience requis',
                'options' => array_keys($experienceLevels),
                'required' => true,
                'source' => 'analysis',
                'confidence' => 0.8
            ];
        }

        if (count($educationPatterns) > 1) {
            $suggestions[] = [
                'type' => 'select',
                'question' => 'Formation/Diplôme privilégié',
                'options' => array_slice(array_keys($educationPatterns), 0, 5),
                'required' => false,
                'source' => 'analysis',
                'confidence' => 0.7
            ];
        }

        return [
            'message' => 'Analyse basée sur ' . $applications->count() . ' candidatures',
            'suggestions' => $suggestions,
            'stats' => [
                'total_candidates' => $applications->count(),
                'common_skills' => $commonSkills,
                'experience_distribution' => $experienceLevels
            ]
        ];
    }

    /**
     * Extraire les compétences communes des candidatures
     */
    private static function extractCommonSkills($applications)
    {
        $skillsCount = [];
        $commonSkills = ['Laravel', 'JavaScript', 'PHP', 'Python', 'React', 'Vue.js', 'MySQL', 'Git', 'Docker', 'AWS'];

        foreach ($applications as $application) {
            $text = strtolower($application->skills . ' ' . $application->experience . ' ' . $application->motivation_letter);
            
            foreach ($commonSkills as $skill) {
                if (Str::contains($text, strtolower($skill))) {
                    $skillsCount[$skill] = ($skillsCount[$skill] ?? 0) + 1;
                }
            }
        }

        // Trier par fréquence et garder celles présentes dans au moins 30% des candidatures
        $threshold = max(1, $applications->count() * 0.3);
        $filtered = array_filter($skillsCount, fn($count) => $count >= $threshold);
        arsort($filtered);

        return array_keys($filtered);
    }

    /**
     * Analyser les niveaux d'expérience
     */
    private static function analyzeExperienceLevels($applications)
    {
        $levels = [];
        
        foreach ($applications as $application) {
            $text = strtolower($application->experience ?? '');
            
            if (Str::contains($text, ['senior', '5 ans', '6 ans', '7 ans', '8 ans', '9 ans', '10 ans'])) {
                $levels['Senior (5+ ans)'] = ($levels['Senior (5+ ans)'] ?? 0) + 1;
            } elseif (Str::contains($text, ['3 ans', '4 ans', 'confirmé', 'expérimenté'])) {
                $levels['Confirmé (3-5 ans)'] = ($levels['Confirmé (3-5 ans)'] ?? 0) + 1;
            } elseif (Str::contains($text, ['junior', '1 an', '2 ans', 'débutant'])) {
                $levels['Junior (0-2 ans)'] = ($levels['Junior (0-2 ans)'] ?? 0) + 1;
            }
        }

        return $levels;
    }

    /**
     * Analyser les patterns d'éducation
     */
    private static function analyzeEducationPatterns($applications)
    {
        $patterns = [];
        
        foreach ($applications as $application) {
            $text = strtolower($application->education ?? '');
            
            if (Str::contains($text, ['master', 'mastère', 'bac+5'])) {
                $patterns['Master/Bac+5'] = ($patterns['Master/Bac+5'] ?? 0) + 1;
            } elseif (Str::contains($text, ['licence', 'bachelor', 'bac+3'])) {
                $patterns['Licence/Bac+3'] = ($patterns['Licence/Bac+3'] ?? 0) + 1;
            } elseif (Str::contains($text, ['bts', 'dut', 'bac+2'])) {
                $patterns['BTS/DUT/Bac+2'] = ($patterns['BTS/DUT/Bac+2'] ?? 0) + 1;
            }
        }

        return $patterns;
    }

    /**
     * Obtenir des critères par défaut génériques
     */
    public static function getDefaultCriteria()
    {
        return [
            [
                'type' => 'select',
                'question' => 'Niveau d\'expérience général',
                'options' => ['Débutant (0-1 an)', 'Junior (1-3 ans)', 'Confirmé (3-7 ans)', 'Senior (7+ ans)'],
                'required' => true
            ],
            [
                'type' => 'radio',
                'question' => 'Disponibilité pour déplacements',
                'options' => ['Très disponible', 'Occasionnellement', 'Pas de déplacement'],
                'required' => false
            ],
            [
                'type' => 'textarea',
                'question' => 'Motivation pour ce poste',
                'required' => true,
                'description' => 'Expliquez pourquoi ce poste vous intéresse'
            ]
        ];
    }
}
