<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Messages d'erreur personnalisés pour les actualités
    |--------------------------------------------------------------------------
    |
    | Ces messages sont utilisés pour afficher des erreurs conviviales
    | lorsque des problèmes surviennent avec la gestion des actualités.
    |
    */

    'database_errors' => [
        'connection_failed' => 'Impossible de se connecter à la base de données. Veuillez réessayer dans quelques instants.',
        'duplicate_title' => 'Ce titre d\'actualité est déjà utilisé. Veuillez en choisir un autre.',
        'duplicate_slug' => 'Une actualité avec un titre similaire existe déjà pour cette date. Veuillez modifier légèrement le titre.',
        'category_not_found' => 'La catégorie sélectionnée n\'existe plus. Veuillez en choisir une autre.',
        'service_not_found' => 'Le service sélectionné n\'existe plus. Veuillez en choisir un autre.',
        'user_not_found' => 'Votre session a expiré. Veuillez vous reconnecter.',
        'title_too_long' => 'Le titre est trop long. Maximum 255 caractères.',
        'resume_too_long' => 'Le résumé dépasse la limite autorisée.',
        'data_too_long' => 'Une des données saisies est trop longue.',
        'table_missing' => 'Table de base de données manquante. Contactez l\'administrateur.',
        'column_missing' => 'Structure de base de données incorrecte. Contactez l\'administrateur.',
        'access_denied' => 'Accès refusé à la base de données. Contactez l\'administrateur.',
        'foreign_key_constraint' => 'Une référence dans le formulaire n\'est plus valide.',
        'cannot_delete_referenced' => 'Impossible de supprimer cet élément car il est utilisé par d\'autres données.',
    ],

    'file_errors' => [
        'upload_failed' => 'Erreur lors de l\'upload du fichier.',
        'file_too_large' => 'Le fichier est trop volumineux. Taille maximum : 5MB.',
        'invalid_file_type' => 'Type de fichier non autorisé. Formats acceptés : JPG, PNG, GIF, WebP.',
        'file_corrupted' => 'Le fichier semble être corrompu. Veuillez en choisir un autre.',
        'storage_full' => 'Espace de stockage insuffisant sur le serveur.',
        'storage_error' => 'Erreur de stockage des fichiers.',
    ],

    'validation_errors' => [
        'title_required' => 'Le titre est obligatoire.',
        'title_too_short' => 'Le titre doit contenir au moins 5 caractères.',
        'title_too_long' => 'Le titre ne peut pas dépasser 255 caractères.',
        'content_required' => 'Le contenu de l\'actualité est obligatoire.',
        'content_too_short' => 'Le contenu doit être plus détaillé (au moins 20 caractères).',
        'category_required' => 'Veuillez sélectionner une catégorie.',
        'resume_too_short' => 'Si vous remplissez le résumé, il doit contenir au moins 10 caractères.',
        'image_invalid' => 'Le fichier doit être une image valide.',
        'image_too_large' => 'L\'image ne peut pas dépasser 5 MB.',
        'alt_text_too_long' => 'Le texte alternatif ne peut pas dépasser 255 caractères.',
    ],

    'system_errors' => [
        'memory_exhausted' => 'Mémoire serveur insuffisante. Réduisez la taille de votre contenu.',
        'timeout' => 'Délai d\'attente dépassé. Veuillez réessayer.',
        'disk_full' => 'Espace disque insuffisant sur le serveur.',
        'permission_denied' => 'Permissions insuffisantes pour effectuer cette action.',
        'network_error' => 'Erreur réseau. Vérifiez votre connexion internet.',
        'server_error' => 'Erreur interne du serveur. Contactez l\'administrateur.',
        'maintenance_mode' => 'Le site est en maintenance. Veuillez réessayer plus tard.',
    ],

    'success_messages' => [
        'created' => 'Actualité créée avec succès.',
        'updated' => 'Actualité mise à jour avec succès.',
        'deleted' => 'Actualité supprimée avec succès.',
        'published' => 'Actualité publiée avec succès.',
        'unpublished' => 'Actualité dépubliée avec succès.',
        'image_uploaded' => 'Image uploadée avec succès.',
    ],

    'warnings' => [
        'draft_mode' => 'L\'actualité est en mode brouillon et ne sera pas visible publiquement.',
        'no_image' => 'Aucune image sélectionnée. Une image améliore l\'attractivité de l\'actualité.',
        'no_resume' => 'Aucun résumé fourni. Un résumé aide les lecteurs à comprendre le sujet.',
        'long_content' => 'Le contenu est très long. Considérez diviser en plusieurs parties.',
        'special_characters' => 'Attention aux caractères spéciaux dans le titre.',
    ],

    'tips' => [
        'seo_title' => 'Conseil SEO : Utilisez des mots-clés pertinents dans le titre.',
        'image_alt' => 'Conseil accessibilité : Ajoutez un texte alternatif pour votre image.',
        'content_structure' => 'Conseil : Structurez votre contenu avec des titres et paragraphes.',
        'category_selection' => 'Conseil : Choisissez la catégorie la plus spécifique possible.',
        'preview_before_publish' => 'Conseil : Prévisualisez votre actualité avant de la publier.',
    ]
];
