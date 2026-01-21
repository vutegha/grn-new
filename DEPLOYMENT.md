# Guide de Déploiement - IRI UCBC

## Prérequis du Serveur

### Exigences Minimales
- **PHP** : 8.1 ou supérieur
- **Composer** : 2.x
- **Base de données** : MySQL 8.0+ ou MariaDB 10.3+
- **Extensions PHP requises** :
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
  - GD ou Imagick (pour les images)
  - Zip

### Outils Recommandés
- **Serveur Web** : Nginx ou Apache
- **Node.js** : 18.x ou supérieur
- **NPM** : 9.x ou supérieur
- **Gestionnaire de processus** : Supervisor (pour les queues)
- **Cache** : Redis (optionnel mais recommandé)

## Étapes de Déploiement

### 1. Préparation du Serveur

```bash
# Mettre à jour le système
sudo apt update && sudo apt upgrade -y

# Installer PHP et extensions
sudo apt install php8.1-fpm php8.1-cli php8.1-mysql php8.1-xml php8.1-mbstring \
php8.1-curl php8.1-gd php8.1-zip php8.1-bcmath php8.1-intl -y

# Installer Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Installer Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

### 2. Cloner le Projet

```bash
# Créer le répertoire du projet
sudo mkdir -p /var/www/iri-ucbc
cd /var/www/iri-ucbc

# Cloner depuis Git
git clone https://github.com/vutegha/grn-new.git .

# Définir les permissions
sudo chown -R www-data:www-data /var/www/iri-ucbc
sudo chmod -R 755 /var/www/iri-ucbc
```

### 3. Installation des Dépendances

```bash
# Installer les dépendances PHP
composer install --optimize-autoloader --no-dev

# Installer les dépendances Node.js
npm install

# Compiler les assets
npm run build
```

### 4. Configuration de l'Environnement

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

**Éditer le fichier `.env`** :

```env
APP_NAME="IRI UCBC"
APP_ENV=production
APP_KEY=base64:VOTRE_CLE_GENEREE
APP_DEBUG=false
APP_URL=https://votre-domaine.com

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=iri_ucbc_prod
DB_USERNAME=iri_user
DB_PASSWORD=MOT_DE_PASSE_SECURISE

# Configuration Email (SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.votre-serveur.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@domaine.com
MAIL_PASSWORD=MOT_DE_PASSE_EMAIL
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@votre-domaine.com
MAIL_FROM_NAME="${APP_NAME}"

# Cache (Redis recommandé)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Filesystem
FILESYSTEM_DISK=public
```

### 5. Configuration de la Base de Données

```bash
# Créer la base de données
mysql -u root -p

# Dans MySQL
CREATE DATABASE iri_ucbc_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'iri_user'@'localhost' IDENTIFIED BY 'MOT_DE_PASSE_SECURISE';
GRANT ALL PRIVILEGES ON iri_ucbc_prod.* TO 'iri_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Exécuter les migrations
php artisan migrate --force

# Exécuter les seeders essentiels
php artisan db:seed --class=RolePermissionSeeder --force
php artisan db:seed --class=MissingPermissionsSeeder --force
php artisan db:seed --class=EmailSettingsPermissionSeeder --force
```

### 6. Configuration du Stockage

```bash
# Créer le lien symbolique pour le stockage
php artisan storage:link

# Définir les permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 7. Optimisation pour Production

```bash
# Optimiser l'autoloader
composer dump-autoload --optimize

# Mettre en cache les configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimiser les événements
php artisan event:cache

# Réinitialiser le cache des permissions
php artisan permission:cache-reset
```

### 8. Configuration Nginx

Créer le fichier `/etc/nginx/sites-available/iri-ucbc` :

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name votre-domaine.com www.votre-domaine.com;
    
    # Redirection vers HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name votre-domaine.com www.votre-domaine.com;

    root /var/www/iri-ucbc/public;
    index index.php index.html;

    # Certificats SSL (Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/votre-domaine.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/votre-domaine.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Logs
    access_log /var/log/nginx/iri-ucbc-access.log;
    error_log /var/log/nginx/iri-ucbc-error.log;

    # Gzip compression
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache des assets statiques
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

**Activer le site** :

```bash
sudo ln -s /etc/nginx/sites-available/iri-ucbc /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 9. Configuration SSL avec Let's Encrypt

```bash
# Installer Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtenir le certificat SSL
sudo certbot --nginx -d votre-domaine.com -d www.votre-domaine.com

# Renouvellement automatique (déjà configuré par défaut)
sudo certbot renew --dry-run
```

### 10. Configuration de Supervisor (Queues)

Créer `/etc/supervisor/conf.d/iri-ucbc-worker.conf` :

```ini
[program:iri-ucbc-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/iri-ucbc/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/iri-ucbc/storage/logs/worker.log
stopwaitsecs=3600
```

**Activer Supervisor** :

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start iri-ucbc-worker:*
```

### 11. Configuration des Tâches Planifiées (Cron)

```bash
# Éditer le crontab
sudo crontab -e -u www-data

# Ajouter cette ligne
* * * * * cd /var/www/iri-ucbc && php artisan schedule:run >> /dev/null 2>&1
```

### 12. Créer un Utilisateur Administrateur

```bash
php artisan tinker
```

```php
// Dans Tinker
$user = new App\Models\User();
$user->name = 'Administrateur';
$user->email = 'admin@iri.ucbc.org';
$user->password = Hash::make('MOT_DE_PASSE_SECURISE');
$user->save();

// Attribuer le rôle super_admin
$user->assignRole('super_admin');

exit
```

## Sécurité Post-Déploiement

### 1. Sauvegardes Automatiques

Créer un script de sauvegarde `/var/www/iri-ucbc/backup.sh` :

```bash
#!/bin/bash

BACKUP_DIR="/var/backups/iri-ucbc"
DATE=$(date +%Y%m%d_%H%M%S)

# Créer le répertoire de sauvegarde
mkdir -p $BACKUP_DIR

# Sauvegarder la base de données
mysqldump -u iri_user -p'MOT_DE_PASSE' iri_ucbc_prod > $BACKUP_DIR/db_$DATE.sql

# Compresser
gzip $BACKUP_DIR/db_$DATE.sql

# Sauvegarder les fichiers uploadés
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz /var/www/iri-ucbc/storage/app

# Supprimer les sauvegardes de plus de 30 jours
find $BACKUP_DIR -name "*.sql.gz" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
```

Ajouter au crontab :

```bash
0 2 * * * /var/www/iri-ucbc/backup.sh
```

### 2. Pare-feu (UFW)

```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

### 3. Fail2Ban

```bash
sudo apt install fail2ban -y
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

## Maintenance

### Mise à jour de l'Application

```bash
# Se placer dans le répertoire
cd /var/www/iri-ucbc

# Mettre en mode maintenance
php artisan down

# Récupérer les dernières modifications
git pull origin main

# Installer les nouvelles dépendances
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Exécuter les migrations
php artisan migrate --force

# Vider les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recréer les caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Sortir du mode maintenance
php artisan up
```

### Monitoring

**Vérifier les logs** :

```bash
# Logs Laravel
tail -f storage/logs/laravel.log

# Logs Nginx
tail -f /var/log/nginx/iri-ucbc-error.log

# Logs Worker
tail -f storage/logs/worker.log
```

**Vérifier les queues** :

```bash
php artisan queue:work --once  # Tester une job
sudo supervisorctl status      # Statut des workers
```

## Checklist Finale

- [ ] PHP 8.1+ installé avec toutes les extensions
- [ ] Base de données créée et migrée
- [ ] Variables d'environnement `.env` configurées
- [ ] Permissions correctes sur `storage/` et `bootstrap/cache/`
- [ ] Lien symbolique `storage/app/public` créé
- [ ] Certificat SSL configuré
- [ ] Nginx/Apache configuré et testé
- [ ] Supervisor configuré pour les queues
- [ ] Cron configuré pour les tâches planifiées
- [ ] Utilisateur admin créé
- [ ] Sauvegardes automatiques configurées
- [ ] Pare-feu activé
- [ ] Logs de production configurés
- [ ] Cache de production activé

## Support

Pour toute assistance, contactez :
- Email : admin@iri.ucbc.org
- GitHub : https://github.com/vutegha/grn-new

---
**Dernière mise à jour** : 7 décembre 2025
