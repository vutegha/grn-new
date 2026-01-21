#!/bin/bash

##############################################
# Script de Déploiement - IRI UCBC
# Version: 1.0
# Date: 2025-12-07
##############################################

set -e  # Arrêter en cas d'erreur

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Fonctions d'affichage
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

# Variables
APP_DIR="/var/www/iri-ucbc"
PHP_BIN="php"
COMPOSER_BIN="composer"
NPM_BIN="npm"

print_info "🚀 Démarrage du déploiement..."
echo ""

# 1. Vérification des prérequis
print_info "Vérification des prérequis..."

if ! command -v $PHP_BIN &> /dev/null; then
    print_error "PHP n'est pas installé"
    exit 1
fi
print_success "PHP installé"

if ! command -v $COMPOSER_BIN &> /dev/null; then
    print_error "Composer n'est pas installé"
    exit 1
fi
print_success "Composer installé"

if ! command -v $NPM_BIN &> /dev/null; then
    print_error "NPM n'est pas installé"
    exit 1
fi
print_success "NPM installé"

echo ""

# 2. Mode maintenance
print_info "Activation du mode maintenance..."
$PHP_BIN artisan down || print_error "Impossible d'activer le mode maintenance"
print_success "Mode maintenance activé"

echo ""

# 3. Mise à jour du code
print_info "Mise à jour du code source..."
git pull origin main
print_success "Code mis à jour"

echo ""

# 4. Installation des dépendances
print_info "Installation des dépendances PHP..."
$COMPOSER_BIN install --no-interaction --prefer-dist --optimize-autoloader --no-dev
print_success "Dépendances PHP installées"

echo ""

print_info "Installation des dépendances Node.js..."
$NPM_BIN install --production=false
print_success "Dépendances Node.js installées"

echo ""

# 5. Compilation des assets
print_info "Compilation des assets..."
$NPM_BIN run build
print_success "Assets compilés"

echo ""

# 6. Migrations
print_info "Exécution des migrations..."
$PHP_BIN artisan migrate --force
print_success "Migrations exécutées"

echo ""

# 7. Nettoyage des caches
print_info "Nettoyage des caches..."
$PHP_BIN artisan cache:clear
$PHP_BIN artisan config:clear
$PHP_BIN artisan route:clear
$PHP_BIN artisan view:clear
$PHP_BIN artisan permission:cache-reset
print_success "Caches nettoyés"

echo ""

# 8. Optimisation
print_info "Optimisation de l'application..."
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
$PHP_BIN artisan event:cache
$COMPOSER_BIN dump-autoload --optimize
print_success "Application optimisée"

echo ""

# 9. Permissions
print_info "Configuration des permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
print_success "Permissions configurées"

echo ""

# 10. Redémarrage des services
print_info "Redémarrage des workers..."
if command -v supervisorctl &> /dev/null; then
    supervisorctl restart iri-ucbc-worker:*
    print_success "Workers redémarrés"
else
    print_info "Supervisor non installé, passage..."
fi

echo ""

# 11. Désactivation du mode maintenance
print_info "Désactivation du mode maintenance..."
$PHP_BIN artisan up
print_success "Application en ligne"

echo ""
print_success "🎉 Déploiement terminé avec succès !"
echo ""

# Afficher les informations
print_info "📊 Informations de déploiement:"
echo "  - Date: $(date)"
echo "  - Branche: $(git branch --show-current)"
echo "  - Commit: $(git log -1 --pretty=format:'%h - %s')"
echo "  - PHP Version: $($PHP_BIN -v | head -n 1)"
echo ""

print_info "💡 Prochaines étapes:"
echo "  1. Vérifier les logs: tail -f storage/logs/laravel.log"
echo "  2. Tester l'application"
echo "  3. Vérifier les queues: supervisorctl status"
echo ""
