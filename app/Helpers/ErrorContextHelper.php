<?php

namespace App\Helpers;

class ErrorContextHelper
{
    /**
     * Ajoute du contexte et des suggestions aux messages d'erreur
     */
    public static function enhanceErrorMessage(string $message, array $context = []): array
    {
        $suggestions = [];
        $troubleshooting = [];
        
        // Analyser le message pour fournir des suggestions
        if (str_contains(strtolower($message), 'titre')) {
            $suggestions[] = "💡 Essayez d'ajouter la date ou l'heure dans le titre pour le rendre unique";
            $suggestions[] = "💡 Utilisez des synonymes ou reformulez légèrement";
            $troubleshooting[] = "Vérifiez s'il y a déjà une actualité avec un titre similaire aujourd'hui";
        }
        
        if (str_contains(strtolower($message), 'catégorie')) {
            $suggestions[] = "💡 Rafraîchissez la page pour voir les catégories les plus récentes";
            $suggestions[] = "💡 Contactez un administrateur si le problème persiste";
            $troubleshooting[] = "La catégorie a peut-être été supprimée par un autre utilisateur";
        }
        
        if (str_contains(strtolower($message), 'connexion') || str_contains(strtolower($message), 'base de données')) {
            $suggestions[] = "💡 Vérifiez votre connexion internet";
            $suggestions[] = "💡 Sauvegardez votre travail localement";
            $suggestions[] = "💡 Réessayez dans quelques minutes";
            $troubleshooting[] = "Le serveur de base de données est peut-être temporairement surchargé";
            $troubleshooting[] = "Votre session a peut-être expiré";
        }
        
        if (str_contains(strtolower($message), 'fichier') || str_contains(strtolower($message), 'image')) {
            $suggestions[] = "💡 Vérifiez que le fichier n'est pas corrompu";
            $suggestions[] = "💡 Utilisez un format d'image standard (JPG, PNG)";
            $suggestions[] = "💡 Réduisez la taille du fichier si nécessaire";
            $troubleshooting[] = "Le fichier dépasse peut-être la limite de taille";
            $troubleshooting[] = "Le format de fichier n'est peut-être pas supporté";
        }
        
        if (str_contains(strtolower($message), 'long') || str_contains(strtolower($message), 'caractères')) {
            $suggestions[] = "💡 Raccourcissez le texte en supprimant les mots superflus";
            $suggestions[] = "💡 Utilisez des abréviations appropriées";
            $troubleshooting[] = "Le champ a une limite de caractères définie";
        }
        
        if (str_contains(strtolower($message), 'session') || str_contains(strtolower($message), 'reconnecter')) {
            $suggestions[] = "💡 Cliquez sur 'Se reconnecter' pour renouveler votre session";
            $suggestions[] = "💡 Sauvegardez vos données importantes avant de vous reconnecter";
            $troubleshooting[] = "Votre session a expiré pour des raisons de sécurité";
        }
        
        return [
            'suggestions' => $suggestions,
            'troubleshooting' => $troubleshooting,
            'next_steps' => self::getNextSteps($message)
        ];
    }
    
    /**
     * Suggère les prochaines étapes selon le type d'erreur
     */
    private static function getNextSteps(string $message): array
    {
        $steps = [];
        
        if (str_contains(strtolower($message), 'titre')) {
            $steps = [
                "1. Modifiez légèrement le titre actuel",
                "2. Ajoutez un détail spécifique (date, lieu, etc.)",
                "3. Vérifiez la liste des actualités existantes",
                "4. Resoumettez le formulaire"
            ];
        } elseif (str_contains(strtolower($message), 'connexion')) {
            $steps = [
                "1. Vérifiez votre connexion internet",
                "2. Actualisez la page",
                "3. Réessayez dans 2-3 minutes",
                "4. Contactez le support si le problème persiste"
            ];
        } elseif (str_contains(strtolower($message), 'catégorie')) {
            $steps = [
                "1. Actualisez la page",
                "2. Sélectionnez une autre catégorie",
                "3. Vérifiez que la catégorie existe toujours",
                "4. Contactez un administrateur si nécessaire"
            ];
        } elseif (str_contains(strtolower($message), 'fichier')) {
            $steps = [
                "1. Vérifiez le format du fichier (JPG, PNG acceptés)",
                "2. Réduisez la taille si > 5MB",
                "3. Essayez avec un autre fichier",
                "4. Contactez le support technique si l'erreur persiste"
            ];
        } else {
            $steps = [
                "1. Vérifiez les données saisies",
                "2. Actualisez la page si nécessaire",
                "3. Réessayez l'opération",
                "4. Contactez le support si le problème persiste"
            ];
        }
        
        return $steps;
    }
    
    /**
     * Génère un identifiant unique pour l'erreur (pour le support technique)
     */
    public static function generateErrorId(): string
    {
        return 'ERR-' . date('Ymd-His') . '-' . substr(md5(uniqid()), 0, 6);
    }
    
    /**
     * Analyse la fréquence des erreurs pour détecter les problèmes récurrents
     */
    public static function trackErrorFrequency(string $errorType, int $userId = null): void
    {
        $cacheKey = 'error_frequency_' . md5($errorType);
        $currentCount = cache()->get($cacheKey, 0);
        
        cache()->put($cacheKey, $currentCount + 1, now()->addHours(24));
        
        // Si l'erreur se répète souvent, logger pour investigation
        if ($currentCount > 5) {
            \Log::warning('Erreur récurrente détectée', [
                'error_type' => $errorType,
                'occurrences' => $currentCount + 1,
                'user_id' => $userId,
                'period' => '24h'
            ]);
        }
    }
    
    /**
     * Suggestions de prévention basées sur le type d'erreur
     */
    public static function getPreventionTips(string $errorType): array
    {
        $tips = [];
        
        switch (strtolower($errorType)) {
            case 'duplicate':
            case 'doublon':
                $tips = [
                    "🛡️ Vérifiez toujours l'unicité avant de créer",
                    "🛡️ Utilisez des titres descriptifs et datés",
                    "🛡️ Consultez la liste existante avant d'ajouter"
                ];
                break;
                
            case 'connection':
            case 'connexion':
                $tips = [
                    "🛡️ Sauvegardez régulièrement votre travail",
                    "🛡️ Évitez les sessions trop longues",
                    "🛡️ Vérifiez votre connexion avant de commencer"
                ];
                break;
                
            case 'file':
            case 'fichier':
                $tips = [
                    "🛡️ Optimisez vos images avant upload",
                    "🛡️ Utilisez des formats standard (JPG, PNG)",
                    "🛡️ Respectez la limite de 5MB par fichier"
                ];
                break;
                
            default:
                $tips = [
                    "🛡️ Vérifiez vos données avant de soumettre",
                    "🛡️ Sauvegardez les brouillons importants",
                    "🛡️ Maintenez une session active"
                ];
                break;
        }
        
        return $tips;
    }
}
