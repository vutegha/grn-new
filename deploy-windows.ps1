# Script de Déploiement PowerShell - IRI UCBC
# Version: 1.0
# Date: 2025-12-07

param(
    [switch]$SkipMaintenance = $false,
    [switch]$SkipMigrations = $false,
    [switch]$SkipAssets = $false
)

# Configuration
$ErrorActionPreference = "Stop"
$AppDir = "C:\inetpub\iri-ucbc"
$PhpBin = "php"
$ComposerBin = "composer"
$NpmBin = "npm"

# Couleurs
function Write-Success { Write-Host "✓ $args" -ForegroundColor Green }
function Write-Failure { Write-Host "✗ $args" -ForegroundColor Red }
function Write-Info { Write-Host "ℹ $args" -ForegroundColor Yellow }

Write-Info "🚀 Démarrage du déploiement..."
Write-Host ""

# 1. Vérification des prérequis
Write-Info "Vérification des prérequis..."

try {
    & $PhpBin --version | Out-Null
    Write-Success "PHP installé"
} catch {
    Write-Failure "PHP n'est pas installé"
    exit 1
}

try {
    & $ComposerBin --version | Out-Null
    Write-Success "Composer installé"
} catch {
    Write-Failure "Composer n'est pas installé"
    exit 1
}

try {
    & $NpmBin --version | Out-Null
    Write-Success "NPM installé"
} catch {
    Write-Failure "NPM n'est pas installé"
    exit 1
}

Write-Host ""

# 2. Mode maintenance
if (-not $SkipMaintenance) {
    Write-Info "Activation du mode maintenance..."
    try {
        & $PhpBin artisan down
        Write-Success "Mode maintenance activé"
    } catch {
        Write-Failure "Impossible d'activer le mode maintenance"
    }
    Write-Host ""
}

# 3. Mise à jour du code
Write-Info "Mise à jour du code source..."
try {
    git pull origin main
    Write-Success "Code mis à jour"
} catch {
    Write-Failure "Erreur lors de la mise à jour du code"
    exit 1
}
Write-Host ""

# 4. Installation des dépendances
Write-Info "Installation des dépendances PHP..."
try {
    & $ComposerBin install --no-interaction --prefer-dist --optimize-autoloader --no-dev
    Write-Success "Dépendances PHP installées"
} catch {
    Write-Failure "Erreur lors de l'installation des dépendances PHP"
    exit 1
}
Write-Host ""

Write-Info "Installation des dépendances Node.js..."
try {
    & $NpmBin install --production=false
    Write-Success "Dépendances Node.js installées"
} catch {
    Write-Failure "Erreur lors de l'installation des dépendances Node.js"
    exit 1
}
Write-Host ""

# 5. Compilation des assets
if (-not $SkipAssets) {
    Write-Info "Compilation des assets..."
    try {
        & $NpmBin run build
        Write-Success "Assets compilés"
    } catch {
        Write-Failure "Erreur lors de la compilation des assets"
        exit 1
    }
    Write-Host ""
}

# 6. Migrations
if (-not $SkipMigrations) {
    Write-Info "Exécution des migrations..."
    try {
        & $PhpBin artisan migrate --force
        Write-Success "Migrations exécutées"
    } catch {
        Write-Failure "Erreur lors des migrations"
        exit 1
    }
    Write-Host ""
}

# 7. Nettoyage des caches
Write-Info "Nettoyage des caches..."
try {
    & $PhpBin artisan cache:clear
    & $PhpBin artisan config:clear
    & $PhpBin artisan route:clear
    & $PhpBin artisan view:clear
    & $PhpBin artisan permission:cache-reset
    Write-Success "Caches nettoyés"
} catch {
    Write-Failure "Erreur lors du nettoyage des caches"
}
Write-Host ""

# 8. Optimisation
Write-Info "Optimisation de l'application..."
try {
    & $PhpBin artisan config:cache
    & $PhpBin artisan route:cache
    & $PhpBin artisan view:cache
    & $PhpBin artisan event:cache
    & $ComposerBin dump-autoload --optimize
    Write-Success "Application optimisée"
} catch {
    Write-Failure "Erreur lors de l'optimisation"
}
Write-Host ""

# 9. Permissions (Windows - ACL)
Write-Info "Configuration des permissions..."
try {
    $acl = Get-Acl "storage"
    $permission = "IIS_IUSRS", "FullControl", "ContainerInherit,ObjectInherit", "None", "Allow"
    $accessRule = New-Object System.Security.AccessControl.FileSystemAccessRule $permission
    $acl.SetAccessRule($accessRule)
    Set-Acl "storage" $acl
    
    $acl = Get-Acl "bootstrap\cache"
    $acl.SetAccessRule($accessRule)
    Set-Acl "bootstrap\cache" $acl
    
    Write-Success "Permissions configurées"
} catch {
    Write-Info "Configuration manuelle des permissions requise"
}
Write-Host ""

# 10. Redémarrage des services
Write-Info "Redémarrage des workers..."
try {
    # Redémarrer le pool d'applications IIS
    Import-Module WebAdministration
    Restart-WebAppPool -Name "iri-ucbc"
    Write-Success "Pool IIS redémarré"
} catch {
    Write-Info "Redémarrage manuel du pool IIS recommandé"
}
Write-Host ""

# 11. Désactivation du mode maintenance
if (-not $SkipMaintenance) {
    Write-Info "Désactivation du mode maintenance..."
    try {
        & $PhpBin artisan up
        Write-Success "Application en ligne"
    } catch {
        Write-Failure "Impossible de désactiver le mode maintenance"
    }
    Write-Host ""
}

Write-Success "🎉 Déploiement terminé avec succès !"
Write-Host ""

# Afficher les informations
Write-Info "📊 Informations de déploiement:"
Write-Host "  - Date: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
try {
    $branch = git branch --show-current
    $commit = git log -1 --pretty=format:'%h - %s'
    Write-Host "  - Branche: $branch"
    Write-Host "  - Commit: $commit"
} catch {}

$phpVersion = & $PhpBin -v | Select-Object -First 1
Write-Host "  - PHP Version: $phpVersion"
Write-Host ""

Write-Info "💡 Prochaines étapes:"
Write-Host "  1. Vérifier les logs: Get-Content storage\logs\laravel.log -Tail 50"
Write-Host "  2. Tester l'application"
Write-Host "  3. Vérifier le pool IIS"
Write-Host ""

# Exemples d'utilisation:
# .\deploy-windows.ps1                    # Déploiement complet
# .\deploy-windows.ps1 -SkipMaintenance   # Sans mode maintenance
# .\deploy-windows.ps1 -SkipMigrations    # Sans migrations
# .\deploy-windows.ps1 -SkipAssets        # Sans compilation assets
