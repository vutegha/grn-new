<?php

namespace App\Services;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class DatabaseErrorHandler
{
    /**
     * Analyse une exception de base de données et retourne un message d'erreur convivial
     */
    public static function handleQueryException(QueryException $e, string $context = 'opération'): array
    {
        $errorCode = $e->errorInfo[1] ?? null;
        $errorMessage = $e->getMessage();
        $sqlState = $e->errorInfo[0] ?? null;
        
        // Logger l'erreur avec plus de détails
        Log::error("Erreur de base de données lors de {$context}", [
            'error_code' => $errorCode,
            'sql_state' => $sqlState,
            'error_message' => $errorMessage,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
        
        return self::translateError($errorCode, $errorMessage, $context);
    }
    
    /**
     * Traduit les codes d'erreur MySQL en messages conviviaux
     */
    private static function translateError(?int $errorCode, string $errorMessage, string $context): array
    {
        $messages = config('actualite_messages.database_errors', []);
        
        switch ($errorCode) {
            // Erreur de clé dupliquée
            case 1062:
                return self::handleDuplicateKeyError($errorMessage, $messages);
                
            // Erreur de contrainte de clé étrangère (référence inexistante)
            case 1452:
                return self::handleForeignKeyConstraintError($errorMessage, $messages);
                
            // Erreur de contrainte de clé étrangère (suppression interdite)
            case 1451:
                return [
                    'type' => 'warning',
                    'message' => $messages['cannot_delete_referenced'] ?? 'Impossible de supprimer cet élément car il est référencé par d\'autres données.',
                    'alert' => '<span class="alert alert-warning"><strong>Suppression bloquée !</strong> Cet élément est utilisé par d\'autres éléments et ne peut pas être supprimé.</span>'
                ];
                
            // Erreur de données trop longues
            case 1406:
                return self::handleDataTooLongError($errorMessage, $messages);
                
            // Erreur de valeur incorrecte
            case 1366:
                return [
                    'type' => 'warning',
                    'message' => 'Format de données incorrect. Vérifiez les caractères spéciaux.',
                    'alert' => '<span class="alert alert-warning"><strong>Format incorrect !</strong> Certains caractères ne sont pas acceptés.</span>'
                ];
                
            // Erreur de colonne manquante
            case 1054:
                return [
                    'type' => 'error',
                    'message' => $messages['column_missing'] ?? 'Erreur de structure de base de données. Contactez l\'administrateur.',
                    'alert' => '<span class="alert alert-danger"><strong>Erreur de structure !</strong> La base de données nécessite une mise à jour.</span>'
                ];
                
            // Erreur de table manquante
            case 1146:
                return [
                    'type' => 'error',
                    'message' => $messages['table_missing'] ?? 'Table de base de données manquante. Contactez l\'administrateur.',
                    'alert' => '<span class="alert alert-danger"><strong>Table manquante !</strong> La base de données est incomplète.</span>'
                ];
                
            // Erreurs de connexion
            case 2002:
            case 2003:
            case 2006:
                return [
                    'type' => 'error',
                    'message' => $messages['connection_failed'] ?? 'Impossible de se connecter à la base de données. Veuillez réessayer dans quelques instants.',
                    'alert' => '<span class="alert alert-danger"><strong>Connexion échouée !</strong> Le serveur de base de données est temporairement indisponible.</span>'
                ];
                
            // Erreur de permission
            case 1044:
            case 1045:
                return [
                    'type' => 'error',
                    'message' => $messages['access_denied'] ?? 'Erreur d\'authentification à la base de données. Contactez l\'administrateur.',
                    'alert' => '<span class="alert alert-danger"><strong>Accès refusé !</strong> Problème d\'authentification avec la base de données.</span>'
                ];
                
            // Erreur générale
            default:
                return [
                    'type' => 'error',
                    'message' => "Erreur de base de données (Code: {$errorCode}). Si le problème persiste, contactez l'administrateur.",
                    'alert' => '<span class="alert alert-danger"><strong>Erreur technique !</strong> Code d\'erreur : ' . ($errorCode ?? 'inconnu') . '</span>'
                ];
        }
    }
    
    /**
     * Gère les erreurs de clé dupliquée
     */
    private static function handleDuplicateKeyError(string $errorMessage, array $messages): array
    {
        if (str_contains($errorMessage, 'actualites_slug_unique')) {
            return [
                'type' => 'warning',
                'message' => $messages['duplicate_slug'] ?? 'Une actualité avec un titre similaire existe déjà pour cette date. Veuillez modifier légèrement le titre.',
                'alert' => '<span class="alert alert-warning"><strong>Doublons détecté !</strong> Une actualité avec un titre très similaire existe déjà aujourd\'hui.</span>'
            ];
        }
        
        if (str_contains($errorMessage, 'titre')) {
            return [
                'type' => 'warning',
                'message' => $messages['duplicate_title'] ?? 'Ce titre est déjà utilisé. Veuillez en choisir un autre.',
                'alert' => '<span class="alert alert-warning"><strong>Titre déjà utilisé !</strong> Ce titre existe déjà dans la base de données.</span>'
            ];
        }
        
        if (str_contains($errorMessage, 'email')) {
            return [
                'type' => 'warning',
                'message' => 'Cette adresse email est déjà utilisée.',
                'alert' => '<span class="alert alert-warning"><strong>Email déjà utilisé !</strong> Cette adresse email est déjà enregistrée.</span>'
            ];
        }
        
        if (str_contains($errorMessage, 'slug')) {
            return [
                'type' => 'warning',
                'message' => 'Cet identifiant (slug) est déjà utilisé. Modifiez le titre pour générer un nouvel identifiant.',
                'alert' => '<span class="alert alert-warning"><strong>Identifiant déjà utilisé !</strong> Cet URL existe déjà.</span>'
            ];
        }
        
        return [
            'type' => 'warning',
            'message' => 'Cette information existe déjà dans la base de données.',
            'alert' => '<span class="alert alert-warning"><strong>Doublon détecté !</strong> Ces données existent déjà.</span>'
        ];
    }
    
    /**
     * Gère les erreurs de contrainte de clé étrangère
     */
    private static function handleForeignKeyConstraintError(string $errorMessage, array $messages): array
    {
        if (str_contains($errorMessage, 'categorie_id')) {
            return [
                'type' => 'warning',
                'message' => $messages['category_not_found'] ?? 'La catégorie sélectionnée n\'est plus disponible. Veuillez en choisir une autre.',
                'alert' => '<span class="alert alert-warning"><strong>Catégorie invalide !</strong> La catégorie sélectionnée a été supprimée.</span>'
            ];
        }
        
        if (str_contains($errorMessage, 'service_id')) {
            return [
                'type' => 'warning',
                'message' => $messages['service_not_found'] ?? 'Le service sélectionné n\'est plus disponible. Veuillez en choisir un autre.',
                'alert' => '<span class="alert alert-warning"><strong>Service invalide !</strong> Le service sélectionné a été supprimé.</span>'
            ];
        }
        
        if (str_contains($errorMessage, 'user_id')) {
            return [
                'type' => 'error',
                'message' => $messages['user_not_found'] ?? 'Votre session a expiré. Veuillez vous reconnecter.',
                'alert' => '<span class="alert alert-danger"><strong>Session expirée !</strong> Veuillez vous reconnecter.</span>'
            ];
        }
        
        return [
            'type' => 'warning',
            'message' => $messages['foreign_key_constraint'] ?? 'Une référence dans le formulaire n\'est plus valide. Veuillez vérifier vos sélections.',
            'alert' => '<span class="alert alert-warning"><strong>Référence invalide !</strong> Un élément sélectionné n\'existe plus.</span>'
        ];
    }
    
    /**
     * Gère les erreurs de données trop longues
     */
    private static function handleDataTooLongError(string $errorMessage, array $messages): array
    {
        if (str_contains($errorMessage, 'titre')) {
            return [
                'type' => 'warning',
                'message' => $messages['title_too_long'] ?? 'Le titre est trop long. Maximum 255 caractères.',
                'alert' => '<span class="alert alert-warning"><strong>Titre trop long !</strong> Le titre ne peut pas dépasser 255 caractères.</span>'
            ];
        }
        
        if (str_contains($errorMessage, 'resume')) {
            return [
                'type' => 'warning',
                'message' => $messages['resume_too_long'] ?? 'Le résumé est trop long. Veuillez le raccourcir.',
                'alert' => '<span class="alert alert-warning"><strong>Résumé trop long !</strong> Le résumé dépasse la limite autorisée.</span>'
            ];
        }
        
        if (str_contains($errorMessage, 'email')) {
            return [
                'type' => 'warning',
                'message' => 'L\'adresse email est trop longue.',
                'alert' => '<span class="alert alert-warning"><strong>Email trop long !</strong> L\'adresse email dépasse la limite.</span>'
            ];
        }
        
        return [
            'type' => 'warning',
            'message' => $messages['data_too_long'] ?? 'Une des données saisies est trop longue.',
            'alert' => '<span class="alert alert-warning"><strong>Données trop longues !</strong> Certaines informations dépassent la limite autorisée.</span>'
        ];
    }
    
    /**
     * Analyse une exception générale et retourne un message d'erreur convivial
     */
    public static function handleGeneralException(\Exception $e, string $context = 'opération'): array
    {
        $errorType = get_class($e);
        $errorMessage = $e->getMessage();
        
        // Logger l'erreur
        Log::error("Erreur lors de {$context}", [
            'error_type' => $errorType,
            'error_message' => $errorMessage,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        // Messages d'erreur contextuels
        if ($e instanceof \InvalidArgumentException) {
            return [
                'type' => 'warning',
                'message' => 'Données invalides : ' . $errorMessage,
                'alert' => '<span class="alert alert-warning"><strong>Données invalides !</strong> ' . e($errorMessage) . '</span>'
            ];
        }
        
        if ($e instanceof \Illuminate\Http\Exceptions\PostTooLargeException) {
            return [
                'type' => 'warning',
                'message' => 'Le fichier uploadé est trop volumineux. Taille maximum : 5MB.',
                'alert' => '<span class="alert alert-warning"><strong>Fichier trop gros !</strong> La taille dépasse la limite de 5MB.</span>'
            ];
        }
        
        if ($e instanceof \Symfony\Component\HttpFoundation\File\Exception\FileException) {
            return [
                'type' => 'error',
                'message' => 'Erreur lors du traitement du fichier. Vérifiez que le fichier n\'est pas corrompu.',
                'alert' => '<span class="alert alert-danger"><strong>Erreur fichier !</strong> Impossible de traiter le fichier.</span>'
            ];
        }
        
        // Messages selon le contenu de l'erreur
        if (str_contains($errorMessage, 'storage') || str_contains($errorMessage, 'disk')) {
            return [
                'type' => 'error',
                'message' => 'Erreur de stockage. L\'espace disque est peut-être insuffisant.',
                'alert' => '<span class="alert alert-danger"><strong>Erreur stockage !</strong> Impossible d\'enregistrer sur le serveur.</span>'
            ];
        }
        
        if (str_contains($errorMessage, 'memory') || str_contains($errorMessage, 'Memory')) {
            return [
                'type' => 'error',
                'message' => 'Mémoire serveur insuffisante. Réduisez la taille du contenu.',
                'alert' => '<span class="alert alert-danger"><strong>Mémoire insuffisante !</strong> Le serveur manque de mémoire.</span>'
            ];
        }
        
        if (str_contains($errorMessage, 'timeout') || str_contains($errorMessage, 'time')) {
            return [
                'type' => 'warning',
                'message' => 'Délai d\'attente dépassé. Veuillez réessayer.',
                'alert' => '<span class="alert alert-warning"><strong>Délai dépassé !</strong> L\'opération a pris trop de temps.</span>'
            ];
        }
        
        return [
            'type' => 'error',
            'message' => 'Erreur technique inattendue. Si le problème persiste, contactez l\'administrateur.',
            'alert' => '<span class="alert alert-danger"><strong>Erreur technique !</strong> Erreur de type : ' . class_basename($errorType) . '</span>'
        ];
    }
}
