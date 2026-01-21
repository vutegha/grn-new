<?php
/**
 * SCRIPT DE DIAGNOSTIC - Vérifier les chemins et permissions
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Diagnostic Laravel</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5}";
echo ".success{color:green;padding:10px;background:#d4edda;border:1px solid #c3e6cb;margin:10px 0;border-radius:5px}";
echo ".error{color:red;padding:10px;background:#f8d7da;border:1px solid #f5c6cb;margin:10px 0;border-radius:5px}";
echo ".info{color:#004085;padding:10px;background:#cce5ff;border:1px solid #b8daff;margin:10px 0;border-radius:5px}";
echo "table{border-collapse:collapse;width:100%;margin:10px 0}th,td{border:1px solid #ddd;padding:8px;text-align:left}";
echo "th{background:#007bff;color:white}</style></head><body>";

echo "<h1>🔍 Diagnostic Laravel - Structure du Serveur</h1>";

echo "<h2>📂 Informations sur les répertoires</h2>";
echo "<table>";
echo "<tr><th>Information</th><th>Valeur</th></tr>";
echo "<tr><td>Répertoire actuel (__DIR__)</td><td>" . __DIR__ . "</td></tr>";
echo "<tr><td>Répertoire parent (dirname)</td><td>" . dirname(__DIR__) . "</td></tr>";
echo "<tr><td>Répertoire script (__FILE__)</td><td>" . __FILE__ . "</td></tr>";
echo "<tr><td>Document Root (\$_SERVER)</td><td>" . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "</td></tr>";
echo "</table>";

echo "<h2>🔍 Recherche de bootstrap/app.php</h2>";

$pathsToTest = [
    '__DIR__ . "/../bootstrap/app.php"' => __DIR__ . '/../bootstrap/app.php',
    'dirname(__DIR__) . "/bootstrap/app.php"' => dirname(__DIR__) . '/bootstrap/app.php',
    '__DIR__ . "/../../bootstrap/app.php"' => __DIR__ . '/../../bootstrap/app.php',
    '/home/lediniti/public_html/iriucbc/bootstrap/app.php' => '/home/lediniti/public_html/iriucbc/bootstrap/app.php',
];

echo "<table>";
echo "<tr><th>Chemin testé</th><th>Existe?</th><th>Lisible?</th></tr>";

$foundPath = null;
foreach ($pathsToTest as $label => $path) {
    $exists = file_exists($path);
    $readable = is_readable($path);
    
    echo "<tr>";
    echo "<td><code>" . htmlspecialchars($label) . "</code><br><small>" . htmlspecialchars($path) . "</small></td>";
    echo "<td>" . ($exists ? '✅ Oui' : '❌ Non') . "</td>";
    echo "<td>" . ($readable ? '✅ Oui' : '❌ Non') . "</td>";
    echo "</tr>";
    
    if ($exists && $readable && !$foundPath) {
        $foundPath = $path;
    }
}
echo "</table>";

if ($foundPath) {
    echo "<div class='success'>✅ Fichier bootstrap/app.php trouvé : <code>" . htmlspecialchars($foundPath) . "</code></div>";
} else {
    echo "<div class='error'>❌ Fichier bootstrap/app.php NON TROUVÉ dans aucun des chemins testés</div>";
}

echo "<h2>📁 Vérification de la structure des dossiers</h2>";

$dirsToCheck = [
    'bootstrap' => dirname(__DIR__) . '/bootstrap',
    'app' => dirname(__DIR__) . '/app',
    'storage' => dirname(__DIR__) . '/storage',
    'public' => __DIR__,
    'vendor' => dirname(__DIR__) . '/vendor',
];

echo "<table>";
echo "<tr><th>Dossier</th><th>Chemin</th><th>Existe?</th><th>Permissions</th></tr>";

foreach ($dirsToCheck as $name => $path) {
    $exists = is_dir($path);
    $perms = $exists ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A';
    
    echo "<tr>";
    echo "<td><strong>" . $name . "</strong></td>";
    echo "<td><code>" . htmlspecialchars($path) . "</code></td>";
    echo "<td>" . ($exists ? '✅ Oui' : '❌ Non') . "</td>";
    echo "<td>" . $perms . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>🔐 Vérification du fichier .env</h2>";

$envPath = dirname(__DIR__) . '/.env';
if (file_exists($envPath)) {
    echo "<div class='success'>✅ Fichier .env trouvé : " . htmlspecialchars($envPath) . "</div>";
    echo "<p><strong>Permissions :</strong> " . substr(sprintf('%o', fileperms($envPath)), -4) . "</p>";
} else {
    echo "<div class='error'>❌ Fichier .env NON TROUVÉ : " . htmlspecialchars($envPath) . "</div>";
}

echo "<h2>⚙️ Configuration PHP</h2>";

echo "<table>";
echo "<tr><th>Paramètre</th><th>Valeur</th></tr>";
echo "<tr><td>Version PHP</td><td>" . PHP_VERSION . "</td></tr>";
echo "<tr><td>Open Basedir</td><td>" . (ini_get('open_basedir') ?: 'Non défini') . "</td></tr>";
echo "<tr><td>Disable Functions</td><td>" . (ini_get('disable_functions') ?: 'Aucune') . "</td></tr>";
echo "<tr><td>Memory Limit</td><td>" . ini_get('memory_limit') . "</td></tr>";
echo "<tr><td>Max Execution Time</td><td>" . ini_get('max_execution_time') . "s</td></tr>";
echo "</table>";

echo "<h2>🧪 Test de chargement Laravel</h2>";

if ($foundPath) {
    echo "<p>Tentative de chargement de Laravel...</p>";
    
    try {
        require_once $foundPath;
        echo "<div class='success'>✅ Laravel chargé avec succès !</div>";
        
        // Test de la connexion à la base de données
        if (class_exists('Illuminate\Support\Facades\DB')) {
            try {
                $connection = \Illuminate\Support\Facades\DB::connection();
                $pdo = $connection->getPdo();
                echo "<div class='success'>✅ Connexion à la base de données réussie !</div>";
                echo "<p><strong>Driver :</strong> " . $connection->getDriverName() . "</p>";
            } catch (Exception $e) {
                echo "<div class='error'>❌ Erreur de connexion à la base de données :<br>" . htmlspecialchars($e->getMessage()) . "</div>";
            }
        }
        
    } catch (Exception $e) {
        echo "<div class='error'>❌ Erreur lors du chargement de Laravel :</div>";
        echo "<pre style='background:#f8f9fa;padding:15px;border-radius:5px;overflow:auto'>" . htmlspecialchars($e->getMessage()) . "</pre>";
        echo "<h3>Stack Trace :</h3>";
        echo "<pre style='background:#f8f9fa;padding:15px;border-radius:5px;overflow:auto;font-size:11px'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
} else {
    echo "<div class='error'>⚠️ Impossible de charger Laravel car bootstrap/app.php n'a pas été trouvé</div>";
}

echo "<hr><p style='text-align:center;color:#666'><em>Ce fichier de diagnostic peut être supprimé après utilisation</em></p>";
echo "</body></html>";
