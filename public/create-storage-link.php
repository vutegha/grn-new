<?php
/**
 * SCRIPT DE CRÉATION DU LIEN SYMBOLIQUE STORAGE
 * ============================================
 * 
 * Ce script crée le lien symbolique storage/app/public -> public/storage
 * à utiliser quand vous n'avez pas accès au terminal SSH
 * 
 * INSTRUCTIONS:
 * 1. Uploadez ce fichier dans le dossier public/ de votre site
 * 2. Accédez à https://votre-domaine.com/create-storage-link.php
 * 3. SUPPRIMEZ IMMÉDIATEMENT ce fichier après utilisation pour la sécurité
 * 
 * ⚠️ IMPORTANT: Supprimez ce fichier après utilisation!
 */

// Pour éviter l'exécution accidentelle, décommentez la ligne suivante
// die("Script désactivé pour des raisons de sécurité. Lisez les instructions.");

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création du Lien Symbolique Storage</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 800px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
            text-align: center;
        }
        .status {
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            font-size: 16px;
            line-height: 1.6;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
        }
        .info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        .step {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #667eea;
            margin: 15px 0;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            color: #e83e8c;
        }
        .delete-warning {
            background: #dc3545;
            color: white;
            padding: 20px;
            border-radius: 5px;
            margin-top: 30px;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        ul {
            margin: 15px 0;
            padding-left: 30px;
        }
        li {
            margin: 8px 0;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔗 Création du Lien Symbolique Storage</h1>

<?php

// Détection de l'environnement
$isLaravel = file_exists('../artisan');
$storagePath = realpath('../storage/app/public');
$publicStoragePath = __DIR__ . '/storage';

echo '<div class="info">';
echo '<strong>📍 Détection de l\'environnement...</strong><br><br>';
echo '<strong>Répertoire actuel:</strong> ' . __DIR__ . '<br>';
echo '<strong>Laravel détecté:</strong> ' . ($isLaravel ? '✅ Oui' : '❌ Non') . '<br>';
echo '<strong>Chemin storage/app/public:</strong> ' . ($storagePath ? $storagePath : '❌ Non trouvé') . '<br>';
echo '<strong>Lien public/storage:</strong> ' . $publicStoragePath . '<br>';
echo '</div>';

if (!$isLaravel) {
    echo '<div class="error">';
    echo '<strong>❌ Erreur:</strong> Ce script doit être placé dans le dossier <code>public/</code> de votre application Laravel.';
    echo '</div>';
    exit;
}

if (!$storagePath) {
    echo '<div class="error">';
    echo '<strong>❌ Erreur:</strong> Le dossier <code>storage/app/public</code> n\'existe pas.<br><br>';
    echo '<strong>Solution:</strong> Créez d\'abord ce dossier via cPanel File Manager.';
    echo '</div>';
    exit;
}

// Vérifier si le lien existe déjà
if (file_exists($publicStoragePath)) {
    if (is_link($publicStoragePath)) {
        $target = readlink($publicStoragePath);
        echo '<div class="warning">';
        echo '<strong>⚠️ Le lien symbolique existe déjà!</strong><br><br>';
        echo '<strong>Cible actuelle:</strong> ' . $target . '<br>';
        echo '<strong>Cible attendue:</strong> ' . $storagePath . '<br><br>';
        
        if ($target === $storagePath) {
            echo '✅ Le lien pointe vers le bon endroit. Aucune action nécessaire.';
        } else {
            echo '❌ Le lien pointe vers le mauvais endroit. Supprimez-le manuellement et réessayez.';
        }
        echo '</div>';
    } else {
        echo '<div class="error">';
        echo '<strong>❌ Erreur:</strong> <code>public/storage</code> existe mais ce n\'est pas un lien symbolique.<br><br>';
        echo '<strong>Solution:</strong><br>';
        echo '1. Supprimez ou renommez <code>public/storage</code> via cPanel File Manager<br>';
        echo '2. Rechargez cette page';
        echo '</div>';
    }
    exit;
}

// Créer le lien symbolique
echo '<div class="step">';
echo '<strong>🔄 Création du lien symbolique en cours...</strong>';
echo '</div>';

$success = @symlink($storagePath, $publicStoragePath);

if ($success) {
    echo '<div class="success">';
    echo '<h2 style="margin-bottom: 15px;">✅ SUCCÈS!</h2>';
    echo '<p>Le lien symbolique a été créé avec succès.</p><br>';
    echo '<strong>De:</strong> <code>public/storage</code><br>';
    echo '<strong>Vers:</strong> <code>' . $storagePath . '</code><br><br>';
    echo '<p><strong>Vérifications à effectuer:</strong></p>';
    echo '<ul>';
    echo '<li>Vos rapports PDF devraient maintenant être accessibles</li>';
    echo '<li>Les publications devraient être téléchargeables</li>';
    echo '<li>Les images/médias devraient s\'afficher</li>';
    echo '</ul>';
    echo '</div>';
    
    // Créer les sous-dossiers nécessaires
    echo '<div class="step">';
    echo '<strong>📁 Création des sous-dossiers nécessaires...</strong><br><br>';
    
    $folders = ['rapports', 'publications', 'medias', 'documents'];
    $created = [];
    $errors = [];
    
    foreach ($folders as $folder) {
        $folderPath = $storagePath . '/' . $folder;
        if (!file_exists($folderPath)) {
            if (@mkdir($folderPath, 0755, true)) {
                $created[] = $folder;
            } else {
                $errors[] = $folder;
            }
        } else {
            echo '✅ <code>' . $folder . '</code> existe déjà<br>';
        }
    }
    
    if (!empty($created)) {
        echo '<br><strong>✅ Dossiers créés:</strong> ' . implode(', ', $created) . '<br>';
    }
    
    if (!empty($errors)) {
        echo '<br><strong>⚠️ Erreur lors de la création de:</strong> ' . implode(', ', $errors) . '<br>';
        echo '<em>Créez-les manuellement via cPanel File Manager</em>';
    }
    echo '</div>';
    
    // Test de permissions
    echo '<div class="step">';
    echo '<strong>🔐 Vérification des permissions...</strong><br><br>';
    
    $storagePerms = substr(sprintf('%o', fileperms($storagePath)), -4);
    $publicStoragePerms = is_link($publicStoragePath) ? 'lien symbolique' : substr(sprintf('%o', fileperms($publicStoragePath)), -4);
    
    echo '<strong>Permissions storage/app/public:</strong> ' . $storagePerms;
    if ($storagePerms < '0755') {
        echo ' ⚠️ <em>(Recommandé: 0755 ou supérieur)</em>';
    } else {
        echo ' ✅';
    }
    echo '<br>';
    echo '<strong>Lien public/storage:</strong> ' . $publicStoragePerms . ' ✅<br>';
    echo '</div>';
    
} else {
    $error = error_get_last();
    echo '<div class="error">';
    echo '<h2 style="margin-bottom: 15px;">❌ ÉCHEC</h2>';
    echo '<p>Impossible de créer le lien symbolique.</p><br>';
    echo '<strong>Erreur PHP:</strong> ' . ($error ? $error['message'] : 'Inconnue') . '<br><br>';
    echo '<p><strong>Solutions alternatives:</strong></p>';
    echo '<ul>';
    echo '<li><strong>Via cPanel Terminal:</strong> Si disponible, exécutez <code>php artisan storage:link</code></li>';
    echo '<li><strong>Via SSH:</strong> Connectez-vous en SSH et exécutez <code>cd /home/username/public_html && php artisan storage:link</code></li>';
    echo '<li><strong>Contactez votre hébergeur:</strong> Demandez-leur de créer le lien symbolique pour vous</li>';
    echo '<li><strong>Permissions:</strong> Vérifiez que le dossier <code>public/</code> a les permissions d\'écriture (755)</li>';
    echo '</ul>';
    echo '</div>';
}

?>

        <div class="delete-warning">
            ⚠️ SUPPRIMEZ CE FICHIER MAINTENANT POUR LA SÉCURITÉ ⚠️<br>
            <small style="font-weight: normal; margin-top: 10px; display: block;">
                Allez dans cPanel File Manager → public/ → create-storage-link.php → Delete
            </small>
        </div>

        <div class="info" style="margin-top: 20px;">
            <strong>📚 Prochaines étapes:</strong><br><br>
            <ol>
                <li>Supprimez immédiatement ce fichier (<code>public/create-storage-link.php</code>)</li>
                <li>Testez l'accès à vos rapports/publications/médias</li>
                <li>Si les fichiers ne sont toujours pas accessibles, consultez <code>FIX_STORAGE_PRODUCTION.md</code></li>
                <li>Vérifiez les permissions des dossiers storage (755 recommandé)</li>
            </ol>
        </div>
    </div>
</body>
</html>
