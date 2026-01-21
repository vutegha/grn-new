# ========================================
# Script de Préparation au Déploiement
# GRN - IRI Admin Application (Windows)
# ========================================

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "Préparation du Déploiement - GRN IRI" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

# Fonction pour afficher les messages
function Write-Success {
    param($Message)
    Write-Host "✓ $Message" -ForegroundColor Green
}

function Write-Error-Message {
    param($Message)
    Write-Host "✗ $Message" -ForegroundColor Red
}

function Write-Warning-Message {
    param($Message)
    Write-Host "⚠ $Message" -ForegroundColor Yellow
}

function Write-Info {
    param($Message)
    Write-Host "→ $Message" -ForegroundColor White
}

# Vérifier que nous sommes dans le bon répertoire
if (-not (Test-Path "artisan")) {
    Write-Error-Message "Ce script doit être exécuté depuis la racine du projet Laravel"
    exit 1
}

Write-Success "Répertoire du projet validé"

# Étape 1: Vérification des prérequis
Write-Host ""
Write-Host "Étape 1: Vérification des prérequis..." -ForegroundColor Yellow
Write-Host "--------------------------------------"

# Vérifier PHP
try {
    $phpVersion = & php -r "echo PHP_VERSION;" 2>&1
    Write-Success "PHP version: $phpVersion"
} catch {
    Write-Error-Message "PHP n'est pas installé ou n'est pas dans le PATH"
    exit 1
}

# Vérifier Composer
try {
    $composerVersion = & composer --version 2>&1 | Select-String -Pattern "Composer version" | ForEach-Object { $_.ToString().Split(' ')[2] }
    Write-Success "Composer version: $composerVersion"
} catch {
    Write-Error-Message "Composer n'est pas installé ou n'est pas dans le PATH"
    exit 1
}

# Vérifier Node.js
try {
    $nodeVersion = & node -v 2>&1
    Write-Success "Node.js version: $nodeVersion"
} catch {
    Write-Error-Message "Node.js n'est pas installé ou n'est pas dans le PATH"
    exit 1
}

# Vérifier NPM
try {
    $npmVersion = & npm -v 2>&1
    Write-Success "NPM version: $npmVersion"
} catch {
    Write-Error-Message "NPM n'est pas installé ou n'est pas dans le PATH"
    exit 1
}

# Étape 2: Nettoyer les caches existants
Write-Host ""
Write-Host "Étape 2: Nettoyage des caches..." -ForegroundColor Yellow
Write-Host "--------------------------------------"

& php artisan cache:clear 2>&1 | Out-Null
Write-Success "Cache applicatif nettoyé"

& php artisan config:clear 2>&1 | Out-Null
Write-Success "Cache de configuration nettoyé"

& php artisan route:clear 2>&1 | Out-Null
Write-Success "Cache des routes nettoyé"

& php artisan view:clear 2>&1 | Out-Null
Write-Success "Cache des vues nettoyé"

# Étape 3: Installer les dépendances
Write-Host ""
Write-Host "Étape 3: Installation des dépendances..." -ForegroundColor Yellow
Write-Host "--------------------------------------"

Write-Info "Installation des dépendances Composer (mode production)..."
& composer install --optimize-autoloader --no-dev --quiet
if ($LASTEXITCODE -eq 0) {
    Write-Success "Dépendances Composer installées"
} else {
    Write-Error-Message "Erreur lors de l'installation des dépendances Composer"
    exit 1
}

Write-Info "Installation des dépendances NPM (mode production)..."
& npm install --production --silent
if ($LASTEXITCODE -eq 0) {
    Write-Success "Dépendances NPM installées"
} else {
    Write-Error-Message "Erreur lors de l'installation des dépendances NPM"
    exit 1
}

# Étape 4: Compilation des assets
Write-Host ""
Write-Host "Étape 4: Compilation des assets pour production..." -ForegroundColor Yellow
Write-Host "--------------------------------------"

& npm run production
if ($LASTEXITCODE -eq 0) {
    Write-Success "Assets compilés avec succès"
} else {
    Write-Error-Message "Erreur lors de la compilation des assets"
    exit 1
}

# Vérifier que le dossier build existe
if (Test-Path "public\build") {
    Write-Success "Dossier public\build généré"
} else {
    Write-Warning-Message "Le dossier public\build n'a pas été généré"
}

# Étape 5: Optimisation Laravel
Write-Host ""
Write-Host "Étape 5: Optimisation de Laravel..." -ForegroundColor Yellow
Write-Host "--------------------------------------"

Write-Info "Optimisation de l'autoloader Composer..."
& composer dump-autoload --optimize --quiet
Write-Success "Autoloader optimisé"

Write-Info "Mise en cache de la configuration..."
& php artisan config:cache
Write-Success "Configuration mise en cache"

Write-Info "Mise en cache des routes..."
& php artisan route:cache
Write-Success "Routes mises en cache"

Write-Info "Mise en cache des vues..."
& php artisan view:cache
Write-Success "Vues mises en cache"

Write-Info "Mise en cache des événements..."
& php artisan event:cache 2>&1 | Out-Null
Write-Success "Événements mis en cache"

# Étape 6: Vérification des fichiers critiques
Write-Host ""
Write-Host "Étape 6: Vérification des fichiers..." -ForegroundColor Yellow
Write-Host "--------------------------------------"

# Vérifier .env.production.example
if (Test-Path ".env.production.example") {
    Write-Success "Fichier .env.production.example présent"
} else {
    Write-Warning-Message "Fichier .env.production.example manquant"
}

# Vérifier .htaccess
if (Test-Path "public\.htaccess") {
    Write-Success "Fichier public\.htaccess présent"
} else {
    Write-Warning-Message "Fichier public\.htaccess manquant"
}

# Étape 7: Création de l'archive de déploiement
Write-Host ""
Write-Host "Étape 7: Création de l'archive de déploiement..." -ForegroundColor Yellow
Write-Host "--------------------------------------"

# Nom de l'archive avec date
$archiveName = "grn-deploy-$(Get-Date -Format 'yyyyMMdd-HHmmss').zip"

Write-Info "Création de l'archive: $archiveName"

# Liste des fichiers/dossiers à exclure
$excludeList = @(
    ".git",
    ".gitignore",
    ".gitattributes",
    "node_modules",
    ".env",
    ".env.backup",
    ".env.production",
    "storage\logs\*.log",
    "storage\framework\cache",
    "storage\framework\sessions",
    "storage\framework\views",
    ".DS_Store",
    "Thumbs.db",
    "grn-deploy-*.zip",
    "*.md"
)

try {
    # Créer une liste temporaire de tous les fichiers
    $allFiles = Get-ChildItem -Path . -Recurse -File | Where-Object {
        $file = $_
        $shouldExclude = $false
        
        foreach ($exclude in $excludeList) {
            if ($file.FullName -like "*$exclude*") {
                $shouldExclude = $true
                break
            }
        }
        
        -not $shouldExclude
    }
    
    # Créer l'archive
    Compress-Archive -Path $allFiles.FullName -DestinationPath $archiveName -Force
    
    if (Test-Path $archiveName) {
        $archiveSize = (Get-Item $archiveName).Length / 1MB
        Write-Success "Archive créée: $archiveName ($([math]::Round($archiveSize, 2)) MB)"
    } else {
        Write-Error-Message "Impossible de créer l'archive"
    }
} catch {
    Write-Warning-Message "Erreur lors de la création de l'archive: $_"
}

# Étape 8: Résumé et prochaines étapes
Write-Host ""
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "✓ Préparation du déploiement terminée !" -ForegroundColor Green
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Prochaines étapes:" -ForegroundColor Yellow
Write-Host "1. Créer le fichier .env sur le serveur de production"
Write-Host "2. Configurer les paramètres de base de données"
Write-Host "3. Uploader l'archive sur le serveur"
Write-Host "4. Exécuter les migrations: php artisan migrate --force"
Write-Host "5. Créer le lien symbolique: php artisan storage:link"
Write-Host "6. Générer la clé d'application: php artisan key:generate"
Write-Host ""
Write-Host "Consultez DEPLOYMENT_CHECKLIST.md pour plus de détails" -ForegroundColor Cyan
Write-Host ""

# Afficher le contenu de l'archive si elle existe
if (Test-Path $archiveName) {
    Write-Host "Archive de déploiement prête: $archiveName" -ForegroundColor Green
    Write-Host ""
}
