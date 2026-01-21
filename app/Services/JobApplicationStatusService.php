<?php

namespace App\Services;

use App\Models\JobApplication;

class JobApplicationStatusService
{
    /**
     * Obtenir la configuration du statut pour l'affichage
     */
    public static function getStatusConfig($status): array
    {
        $config = JobApplication::getStatusConfig();
        return $config[$status] ?? [
            'label' => $status,
            'color' => 'gray',
            'icon' => 'fa-question'
        ];
    }

    /**
     * Obtenir tous les statuts disponibles pour les filtres
     */
    public static function getStatusOptionsForFilter(): array
    {
        $statuses = JobApplication::getStatusConfig();
        $options = [];
        
        foreach ($statuses as $key => $config) {
            $options[$key] = $config['label'];
        }
        
        return $options;
    }

    /**
     * Obtenir les statuts disponibles pour les actions en lot
     */
    public static function getStatusOptionsForBulkActions(): array
    {
        return [
            JobApplication::STATUS_REVIEWED => '👀 Marquer comme révisées',
            JobApplication::STATUS_SHORTLISTED => '⭐ Présélectionner',
            JobApplication::STATUS_INTERVIEWED => '💬 Marquer entretien passé',
            JobApplication::STATUS_REJECTED => '❌ Rejeter'
        ];
    }

    /**
     * Obtenir les statuts disponibles pour les actions individuelles
     */
    public static function getStatusOptionsForActions(): array
    {
        return [
            JobApplication::STATUS_REVIEWED => [
                'label' => 'Marquer révisée',
                'icon' => 'fa-eye',
                'color' => 'blue'
            ],
            JobApplication::STATUS_SHORTLISTED => [
                'label' => 'Présélectionner',
                'icon' => 'fa-star',
                'color' => 'purple'
            ],
            JobApplication::STATUS_INTERVIEWED => [
                'label' => 'Entretien passé',
                'icon' => 'fa-comments',
                'color' => 'indigo'
            ],
            JobApplication::STATUS_ACCEPTED => [
                'label' => 'Accepter',
                'icon' => 'fa-check',
                'color' => 'green'
            ],
            JobApplication::STATUS_REJECTED => [
                'label' => 'Rejeter',
                'icon' => 'fa-times',
                'color' => 'red'
            ]
        ];
    }
}
