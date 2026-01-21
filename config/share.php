<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image de partage par défaut
    |--------------------------------------------------------------------------
    |
    | Chemin vers l'image utilisée par défaut quand une actualité/publication
    | n'a pas d'image de couverture. Cette image doit être accessible
    | publiquement et respecter les dimensions recommandées (1200x630px).
    |
    */
    'default_image' => 'images/default-share.jpg',

    /*
    |--------------------------------------------------------------------------
    | Dimensions des images sociales
    |--------------------------------------------------------------------------
    |
    | Dimensions recommandées pour les images de partage social.
    | Le ratio 1.91:1 (1200x630) est optimal pour la plupart des plateformes.
    |
    */
    'social_image' => [
        'width' => 1200,
        'height' => 630,
        'quality' => 85,
        'format' => 'jpg',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration du site
    |--------------------------------------------------------------------------
    |
    | Métadonnées générales du site utilisées pour le partage social.
    |
    */
    'site' => [
        'name' => env('APP_NAME', 'GRN'),
        'description' => 'Groupement des Ressources Naturelles - Actualités et publications',
        'logo_path' => 'images/logo-grn.png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Stockage des images sociales
    |--------------------------------------------------------------------------
    |
    | Configuration pour le stockage des images sociales générées.
    |
    */
    'storage' => [
        'disk' => 'public',
        'path' => 'social',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Configuration du cache pour les images sociales.
    |
    */
    'cache' => [
        'ttl' => 86400, // 24 heures
        'headers' => [
            'Cache-Control' => 'public, max-age=86400',
        ],
    ],
];
