#!/bin/bash

# ========================================
# Script de Déploiement sur le Serveur
# GRN - IRI Admin Application
# ========================================
# Ce script doit être exécuté SUR LE SERVEUR après l'upload

echo "========================================="
echo "Déploiement sur le Serveur - GRN IRI"
echo "========================================="
echo ""

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

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

# Vérifier que le fichier .env existe
if [ ! -f ".env" ]; then
    error "Fichier .env manquant ! Créez-le avant de continuer."
    exit 1
fi

success "Fichier .env trouvé"

echo ""
echo "Étape 1: Vérification de l'environnement..."
echo "--------------------------------------"

# Vérifier PHP
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -r "echo PHP_VERSION;")
    success "PHP version: $PHP_VERSION"
else
    error "PHP n'est pas installé"
    exit 1
fi

# Vérifier que nous sommes en mode production
APP_ENV=$(php artisan env:get APP_ENV 2>/dev/null || echo "unknown")
if [ "$APP_ENV" != "production" ]; then
    warning "APP_ENV n'est pas en 'production' (actuellement: $APP_ENV)"
    read -p "Voulez-vous continuer ? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

echo ""
echo "Étape 2: Configuration des permissions..."
echo "--------------------------------------"

# Configurer les permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type f -exec chmod 644 {} \;
chmod 600 .env

success "Permissions configurées"

echo ""
echo "Étape 3: Génération de la clé d'application..."
echo "--------------------------------------"

# Vérifier si APP_KEY est vide
APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" == "base64:VOTRE_CLE_APPLICATION_ICI" ]; then
    info "Génération d'une nouvelle clé d'application..."
    php artisan key:generate --force
    success "Clé d'application générée"
else
    warning "APP_KEY déjà définie, passage à l'étape suivante"
fi

echo ""
echo "Étape 4: Création du lien symbolique de stockage..."
echo "--------------------------------------"

if [ -L "public/storage" ]; then
    warning "Le lien symbolique existe déjà"
else
    php artisan storage:link
    success "Lien symbolique créé"
fi

echo ""
echo "Étape 5: Exécution des migrations..."
echo "--------------------------------------"

read -p "Voulez-vous exécuter les migrations ? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --force
    success "Migrations exécutées"
else
    warning "Migrations ignorées"
fi

echo ""
echo "Étape 6: Création des tables système..."
echo "--------------------------------------"

# Créer les tables pour cache, sessions, queue si elles n'existent pas
info "Vérification des tables système..."
php artisan cache:table 2>/dev/null
php artisan session:table 2>/dev/null
php artisan queue:table 2>/dev/null

read -p "Voulez-vous migrer les tables système ? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --force
    success "Tables système créées"
fi

echo ""
echo "Étape 7: Optimisation de l'application..."
echo "--------------------------------------"

# Nettoyer d'abord
info "Nettoyage des caches existants..."
php artisan cache:clear > /dev/null 2>&1
php artisan config:clear > /dev/null 2>&1
php artisan route:clear > /dev/null 2>&1
php artisan view:clear > /dev/null 2>&1

success "Caches nettoyés"

# Optimiser
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

echo ""
echo "Étape 8: Vérification finale..."
echo "--------------------------------------"

# Vérifier la connexion à la base de données
info "Test de connexion à la base de données..."
if php artisan db:show > /dev/null 2>&1; then
    success "Connexion à la base de données OK"
else
    error "Impossible de se connecter à la base de données"
    warning "Vérifiez les paramètres DB dans .env"
fi

# Vérifier les dossiers critiques
if [ -d "storage/logs" ]; then
    success "Dossier storage/logs existe"
else
    warning "Dossier storage/logs manquant"
fi

if [ -d "public/build" ]; then
    success "Dossier public/build existe (assets compilés)"
else
    warning "Dossier public/build manquant - les assets ne sont peut-être pas compilés"
fi

echo ""
echo "========================================="
echo "✓ Déploiement terminé !"
echo "========================================="
echo ""
echo "Prochaines étapes:"
echo "1. Testez le site dans votre navigateur"
echo "2. Vérifiez les logs: storage/logs/laravel.log"
echo "3. Testez toutes les fonctionnalités critiques"
echo "4. Configurez les cron jobs si nécessaire"
echo "5. Configurez les sauvegardes automatiques"
echo ""
echo "En cas de problème, consultez les logs:"
echo "  tail -f storage/logs/laravel.log"
echo ""
