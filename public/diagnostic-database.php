<?php
/**
 * DIAGNOSTIC DE LA BASE DE DONNÉES
 * =================================
 * 
 * Affiche toutes les tables et colonnes de la base de données
 * Pour identifier les bons noms à utiliser
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Diagnostic Base de Données</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5}";
echo ".success{color:green;padding:10px;background:#d4edda;border:1px solid #c3e6cb;margin:10px 0;border-radius:5px}";
echo ".error{color:red;padding:10px;background:#f8d7da;border:1px solid #f5c6cb;margin:10px 0;border-radius:5px}";
echo ".info{color:#004085;padding:10px;background:#cce5ff;border:1px solid #b8daff;margin:10px 0;border-radius:5px}";
echo "h1{color:#333}h2{color:#666}table{border-collapse:collapse;width:100%;margin:20px 0}";
echo "th,td{border:1px solid #ddd;padding:8px;text-align:left}th{background:#007bff;color:white}";
echo ".col-name{font-family:monospace;background:#f8f9fa;padding:2px 6px;border-radius:3px}</style></head><body>";

echo "<h1>🔍 Diagnostic de la Base de Données</h1>";

// Charger les variables d'environnement depuis .env
$envPath = '/home/lediniti/public_html/iriucbc/.env';

if (!file_exists($envPath)) {
    echo "<div class='error'>❌ Fichier .env non trouvé : " . htmlspecialchars($envPath) . "</div>";
    exit;
}

// Parser le fichier .env
$envVars = [];
$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) continue;
    if (strpos($line, '=') !== false) {
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        $envVars[$key] = $value;
    }
}

try {
    // Connexion PDO
    $host = $envVars['DB_HOST'] ?? 'localhost';
    $dbname = $envVars['DB_DATABASE'] ?? '';
    $username = $envVars['DB_USERNAME'] ?? '';
    $password = $envVars['DB_PASSWORD'] ?? '';
    
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "<div class='success'>✅ Connexion à la base de données réussie</div>";
    echo "<p><strong>Base de données :</strong> " . htmlspecialchars($dbname) . "</p>";
    
    // Lister toutes les tables
    echo "<h2>📋 Tables dans la base de données</h2>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<div class='info'><strong>Nombre total de tables :</strong> " . count($tables) . "</div>";
    
    if (empty($tables)) {
        echo "<div class='error'>⚠️ Aucune table trouvée dans la base de données !</div>";
        echo "<p>Vous devez d'abord exécuter les migrations Laravel : <code>php artisan migrate</code></p>";
        exit;
    }
    
    // Tables qui nous intéressent pour les fichiers
    $relevantTables = ['medias', 'media', 'rapports', 'publications', 'actualites', 'evenements', 'projets'];
    
    echo "<h2>🔎 Colonnes des tables importantes</h2>";
    
    foreach ($tables as $table) {
        // Vérifier si c'est une table qui nous intéresse
        $isRelevant = false;
        foreach ($relevantTables as $relevantTable) {
            if (stripos($table, $relevantTable) !== false) {
                $isRelevant = true;
                break;
            }
        }
        
        if (!$isRelevant) continue;
        
        echo "<h3>📁 Table : <code>" . htmlspecialchars($table) . "</code></h3>";
        
        // Récupérer les colonnes
        $stmt = $pdo->query("SHOW COLUMNS FROM `$table`");
        $columns = $stmt->fetchAll();
        
        echo "<table>";
        echo "<tr><th>Colonne</th><th>Type</th><th>Null</th><th>Défaut</th></tr>";
        
        $fileColumns = [];
        foreach ($columns as $column) {
            $colName = $column['Field'];
            $isFileColumn = (
                stripos($colName, 'fichier') !== false ||
                stripos($colName, 'file') !== false ||
                stripos($colName, 'path') !== false ||
                stripos($colName, 'image') !== false ||
                stripos($colName, 'photo') !== false ||
                stripos($colName, 'media') !== false ||
                stripos($colName, 'document') !== false ||
                stripos($colName, 'pdf') !== false
            );
            
            if ($isFileColumn) {
                $fileColumns[] = $colName;
                echo "<tr style='background:#fff3cd'>";
            } else {
                echo "<tr>";
            }
            
            echo "<td><span class='col-name'>" . htmlspecialchars($colName) . "</span></td>";
            echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Default'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        if (!empty($fileColumns)) {
            echo "<div class='info'><strong>📎 Colonnes de fichiers détectées :</strong> ";
            foreach ($fileColumns as $col) {
                echo "<code>" . htmlspecialchars($col) . "</code> ";
            }
            echo "</div>";
            
            // Vérifier si des enregistrements contiennent "assets/"
            foreach ($fileColumns as $fileCol) {
                $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM `$table` WHERE `$fileCol` LIKE '%assets/%'");
                $stmt->execute();
                $result = $stmt->fetch();
                $count = $result['count'];
                
                if ($count > 0) {
                    echo "<div class='error'>⚠️ <strong>$count</strong> enregistrements contiennent 'assets/' dans la colonne <code>$fileCol</code></div>";
                    
                    // Afficher quelques exemples
                    $stmt = $pdo->prepare("SELECT `$fileCol` FROM `$table` WHERE `$fileCol` LIKE '%assets/%' LIMIT 3");
                    $stmt->execute();
                    $examples = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    
                    if (!empty($examples)) {
                        echo "<p><strong>Exemples :</strong></p><ul>";
                        foreach ($examples as $example) {
                            echo "<li><code>" . htmlspecialchars($example) . "</code></li>";
                        }
                        echo "</ul>";
                    }
                }
            }
        }
        
        echo "<hr>";
    }
    
    echo "<h2>✅ Configuration recommandée pour fix-media-paths.php</h2>";
    echo "<div class='success'>";
    echo "<p>Utilisez cette configuration dans le script :</p>";
    echo "<pre style='background:#1a1a1a;color:#0f0;padding:15px;border-radius:5px;overflow:auto'>";
    echo "\$tables = [\n";
    
    foreach ($tables as $table) {
        foreach ($relevantTables as $relevantTable) {
            if (stripos($table, $relevantTable) !== false) {
                $stmt = $pdo->query("SHOW COLUMNS FROM `$table`");
                $columns = $stmt->fetchAll();
                
                $fileColumns = [];
                foreach ($columns as $column) {
                    $colName = $column['Field'];
                    if (
                        stripos($colName, 'fichier') !== false ||
                        stripos($colName, 'file') !== false ||
                        stripos($colName, 'path') !== false ||
                        stripos($colName, 'image') !== false ||
                        stripos($colName, 'media') !== false ||
                        stripos($colName, 'pdf') !== false
                    ) {
                        $fileColumns[] = $colName;
                    }
                }
                
                if (!empty($fileColumns)) {
                    echo "    '" . htmlspecialchars($table) . "' => [";
                    echo "'" . implode("', '", array_map('htmlspecialchars', $fileColumns)) . "'";
                    echo "],\n";
                }
                
                break;
            }
        }
    }
    
    echo "];</pre>";
    echo "</div>";
    
    echo "<div class='info'><h3>📋 Prochaines étapes :</h3>";
    echo "<ol>";
    echo "<li>Notez la configuration ci-dessus</li>";
    echo "<li>Je vais créer un nouveau fix-media-paths.php avec les bons noms de colonnes</li>";
    echo "<li>Supprimez ce fichier de diagnostic après utilisation</li>";
    echo "</ol></div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>❌ Erreur de connexion à la base de données :</div>";
    echo "<pre style='background:#f8f9fa;padding:15px;border-radius:5px;overflow:auto'>" . htmlspecialchars($e->getMessage()) . "</pre>";
}

echo "</body></html>";
