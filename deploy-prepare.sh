#!/bin/bash

# ========================================
# Script de Préparation au Déploiement
# GRN - IRI Admin Application
# ========================================

echo "========================================="
echo "Préparation du Déploiement - GRN IRI"
echo "========================================="
echo ""

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Fonction pour afficher les messages
success() {
    echo -e "${GREEN}✓ $1${NC}"
}

error() {
    echo -e "${RED}✗ $1${NC}"
}

warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

info() {
    echo -e "→ $1"
}

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    error "Ce script doit être exécuté depuis la racine du projet Laravel"
    exit 1
fi

success "Répertoire du projet validé"

# Étape 1: Vérifier les prérequis
echo ""
echo "Étape 1: Vérification des prérequis..."
echo "--------------------------------------"

# Vérifier PHP
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -r "echo PHP_VERSION;")
    success "PHP version: $PHP_VERSION"
else
    error "PHP n'est pas installé"
    exit 1
fi

# Vérifier Composer
if command -v composer &> /dev/null; then
    COMPOSER_VERSION=$(composer --version | cut -d ' ' -f 3)
    success "Composer version: $COMPOSER_VERSION"
else
    error "Composer n'est pas installé"
    exit 1
fi

# Vérifier Node.js
if command -v node &> /dev/null; then
    NODE_VERSION=$(node -v)
    success "Node.js version: $NODE_VERSION"
else
    error "Node.js n'est pas installé"
    exit 1
fi

# Vérifier NPM
if command -v npm &> /dev/null; then
    NPM_VERSION=$(npm -v)
    success "NPM version: $NPM_VERSION"
else
    error "NPM n'est pas installé"
    exit 1
fi

# Étape 2: Nettoyer les caches existants
echo ""
echo "Étape 2: Nettoyage des caches..."
echo "--------------------------------------"

php artisan cache:clear > /dev/null 2>&1
success "Cache applicatif nettoyé"

php artisan config:clear > /dev/null 2>&1
success "Cache de configuration nettoyé"

php artisan route:clear > /dev/null 2>&1
success "Cache des routes nettoyé"

php artisan view:clear > /dev/null 2>&1
success "Cache des vues nettoyé"

# Étape 3: Installer les dépendances
echo ""
echo "Étape 3: Installation des dépendances..."
echo "--------------------------------------"

info "Installation des dépendances Composer (mode production)..."
composer install --optimize-autoloader --no-dev --quiet
if [ $? -eq 0 ]; then
    success "Dépendances Composer installées"
else
    error "Erreur lors de l'installation des dépendances Composer"
    exit 1
fi

info "Installation des dépendances NPM (mode production)..."
npm install --production --silent
if [ $? -eq 0 ]; then
    success "Dépendances NPM installées"
else
    error "Erreur lors de l'installation des dépendances NPM"
    exit 1
fi

# Étape 4: Compilation des assets
echo ""
echo "Étape 4: Compilation des assets pour production..."
echo "--------------------------------------"

npm run production
if [ $? -eq 0 ]; then
    success "Assets compilés avec succès"
else
    error "Erreur lors de la compilation des assets"
    exit 1
fi

# Vérifier que le dossier build existe
if [ -d "public/build" ]; then
    success "Dossier public/build généré"
else
    warning "Le dossier public/build n'a pas été généré"
fi

# Étape 5: Optimisation Laravel
echo ""
echo "Étape 5: Optimisation de Laravel..."
echo "--------------------------------------"

info "Optimisation de l'autoloader Composer..."
composer dump-autoload --optimize --quiet
success "Autoloader optimisé"

info "Mise en cache de la configuration..."
php artisan config:cache
success "Configuration mise en cache"

info "Mise en cache des routes..."
php artisan route:cache
success "Routes mises en cache"

info "Mise en cache des vues..."
php artisan view:cache
success "Vues mises en cache"

info "Mise en cache des événements..."
php artisan event:cache > /dev/null 2>&1
success "Événements mis en cache"

# Étape 6: Vérification des fichiers critiques
echo ""
echo "Étape 6: Vérification des fichiers..."
echo "--------------------------------------"

# Vérifier .env.production.example
if [ -f ".env.production.example" ]; then
    success "Fichier .env.production.example présent"
else
    warning "Fichier .env.production.example manquant"
fi

# Vérifier .htaccess
if [ -f "public/.htaccess" ]; then
    success "Fichier public/.htaccess présent"
else
    warning "Fichier public/.htaccess manquant"
fi

# Vérifier les permissions (Linux/Mac uniquement)
if [[ "$OSTYPE" != "msys" && "$OSTYPE" != "win32" ]]; then
    echo ""
    echo "Étape 7: Vérification des permissions..."
    echo "--------------------------------------"
    
    chmod -R 755 storage
    chmod -R 755 bootstrap/cache
    success "Permissions configurées pour storage/ et bootstrap/cache/"
fi

# Étape 8: Création de l'archive de déploiement
echo ""
echo "Étape 8: Création de l'archive de déploiement..."
echo "--------------------------------------"

# Nom de l'archive avec date
ARCHIVE_NAME="grn-deploy-$(date +%Y%m%d-%H%M%S).zip"

info "Création de l'archive: $ARCHIVE_NAME"

# Créer l'archive en excluant les fichiers non nécessaires
if command -v zip &> /dev/null; then
    zip -r "$ARCHIVE_NAME" . \
        -x "*.git*" \
        -x "*node_modules*" \
        -x "*.env" \
        -x "*.env.backup" \
        -x "*storage/logs/*" \
        -x "*storage/framework/cache/*" \
        -x "*storage/framework/sessions/*" \
        -x "*storage/framework/views/*" \
        -x "*.DS_Store" \
        -x "*Thumbs.db" \
        -x "grn-deploy-*.zip" \
        > /dev/null 2>&1
    
    if [ -f "$ARCHIVE_NAME" ]; then
        ARCHIVE_SIZE=$(du -h "$ARCHIVE_NAME" | cut -f1)
        success "Archive créée: $ARCHIVE_NAME ($ARCHIVE_SIZE)"
    else
        error "Impossible de créer l'archive"
    fi
else
    warning "La commande 'zip' n'est pas disponible. Archive non créée."
fi

# Étape 9: Résumé et prochaines étapes
echo ""
echo "========================================="
echo "✓ Préparation du déploiement terminée !"
echo "========================================="
echo ""
echo "Prochaines étapes:"
echo "1. Créer le fichier .env sur le serveur de production"
echo "2. Configurer les paramètres de base de données"
echo "3. Uploader l'archive sur le serveur"
echo "4. Exécuter les migrations: php artisan migrate --force"
echo "5. Créer le lien symbolique: php artisan storage:link"
echo "6. Générer la clé d'application: php artisan key:generate"
echo ""
echo "Consultez DEPLOYMENT_CHECKLIST.md pour plus de détails"
echo ""

# Afficher le contenu de l'archive si elle existe
if [ -f "$ARCHIVE_NAME" ]; then
    echo "Archive de déploiement prête: $ARCHIVE_NAME"
    echo ""
fi
