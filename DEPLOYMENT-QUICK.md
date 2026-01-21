# Guide de Déploiement Rapide

## 🚀 Déploiement Automatisé

### Linux/Ubuntu

```bash
# Rendre le script exécutable
chmod +x deploy.sh

# Déploiement complet
./deploy.sh
```

### Windows Server (PowerShell)

```powershell
# Exécuter en tant qu'administrateur
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass

# Déploiement complet
.\deploy-windows.ps1

# Options disponibles:
.\deploy-windows.ps1 -SkipMaintenance   # Sans mode maintenance
.\deploy-windows.ps1 -SkipMigrations    # Sans migrations
.\deploy-windows.ps1 -SkipAssets        # Sans compilation assets
```

---

## 📋 Checklist Pré-Déploiement

### 1. Vérifications Locales
- [ ] Tests unitaires passent: `php artisan test`
- [ ] Code validé: `git status` (tout commité)
- [ ] `.env.example` mis à jour
- [ ] Documentation à jour
- [ ] Assets compilés en local: `npm run build`

### 2. Préparation Serveur
- [ ] Backup de la base de données
- [ ] Backup des fichiers uploadés (`storage/app/public`)
- [ ] Espace disque suffisant: `df -h`
- [ ] Services actifs: Nginx/Apache, MySQL, Supervisor
- [ ] Certificat SSL valide

### 3. Configuration Production
- [ ] `.env` configuré correctement
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` correct
- [ ] Credentials base de données
- [ ] Credentials email (SMTP)
- [ ] Clés API (Google Maps, etc.)

---

## 🔧 Déploiement Manuel (Étape par Étape)

### Étape 1: Connexion au Serveur
```bash
ssh utilisateur@votre-serveur.com
cd /var/www/iri-ucbc
```

### Étape 2: Mode Maintenance
```bash
php artisan down
```

### Étape 3: Mise à Jour du Code
```bash
git pull origin main
```

### Étape 4: Dépendances
```bash
# PHP
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Node.js
npm install --production=false
```

### Étape 5: Compilation Assets
```bash
npm run build
```

### Étape 6: Base de Données
```bash
# Backup avant migration
mysqldump -u root -p iri_ucbc > backup-$(date +%Y%m%d-%H%M%S).sql

# Migrations
php artisan migrate --force
```

### Étape 7: Nettoyage & Optimisation
```bash
# Nettoyage
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan permission:cache-reset

# Optimisation
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
composer dump-autoload --optimize
```

### Étape 8: Permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Étape 9: Redémarrage Services
```bash
# Supervisor (queues)
sudo supervisorctl restart iri-ucbc-worker:*

# Nginx
sudo systemctl reload nginx

# PHP-FPM
sudo systemctl restart php8.2-fpm
```

### Étape 10: Sortie de Maintenance
```bash
php artisan up
```

---

## 🔍 Vérification Post-Déploiement

### 1. Tests Fonctionnels
```bash
# Vérifier l'état de l'application
php artisan optimize:clear
php artisan about

# Tester les queues
php artisan queue:work --once

# Vérifier les permissions
php artisan permission:show
```

### 2. Vérifications Manuelles
- [ ] Page d'accueil charge correctement
- [ ] Connexion admin fonctionne
- [ ] Upload de fichiers fonctionne
- [ ] Envoi d'emails fonctionne
- [ ] Carte Google Maps affichée
- [ ] Menu mobile/desktop responsive
- [ ] HTTPS actif (cadenas vert)

### 3. Monitoring
```bash
# Logs en temps réel
tail -f storage/logs/laravel.log

# Logs Nginx
sudo tail -f /var/log/nginx/error.log

# Statut Supervisor
sudo supervisorctl status
```

---

## 🆘 Troubleshooting

### Problème: Page blanche / Erreur 500
**Solution:**
```bash
# Vérifier les logs
tail -50 storage/logs/laravel.log
sudo tail -50 /var/log/nginx/error.log

# Permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Régénérer les caches
php artisan optimize:clear
```

### Problème: Assets non chargés (CSS/JS)
**Solution:**
```bash
# Recompiler
npm run build

# Vérifier le lien symbolique
php artisan storage:link

# Permissions public/storage
chmod -R 755 public/storage
```

### Problème: Migrations échouent
**Solution:**
```bash
# Vérifier la connexion DB
php artisan db:monitor

# Rollback si nécessaire
php artisan migrate:rollback --step=1

# Migration pas à pas
php artisan migrate --step
```

### Problème: Queue ne fonctionne pas
**Solution:**
```bash
# Vérifier Supervisor
sudo supervisorctl status

# Redémarrer les workers
sudo supervisorctl restart iri-ucbc-worker:*

# Logs des queues
tail -f storage/logs/laravel.log | grep queue
```

### Problème: Permissions insuffisantes
**Solution:**
```bash
# Vérifier l'utilisateur web
ps aux | grep nginx

# Ajuster les permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# SELinux (si applicable)
sudo chcon -R -t httpd_sys_rw_content_t storage
```

---

## 🔄 Rollback Rapide

### En cas de problème majeur:

```bash
# 1. Activer maintenance
php artisan down

# 2. Revenir au commit précédent
git log --oneline -5  # Trouver le commit précédent
git reset --hard <commit-hash>

# 3. Restaurer la base de données
mysql -u root -p iri_ucbc < backup-YYYYMMDD-HHMMSS.sql

# 4. Réinstaller dépendances
composer install --no-dev
npm install --production=false
npm run build

# 5. Nettoyer les caches
php artisan optimize:clear

# 6. Désactiver maintenance
php artisan up
```

---

## 📅 Maintenance Planifiée

### Avant Déploiement
- Planifier en heures creuses (2h-6h du matin)
- Notifier les utilisateurs 24h à l'avance
- Prévoir 30-60 minutes de fenêtre

### Pendant Déploiement
- Mode maintenance avec message personnalisé:
  ```bash
  php artisan down --message="Mise à jour en cours. Retour dans 30 minutes."
  ```

### Après Déploiement
- Surveiller les logs pendant 1-2 heures
- Garder le backup 7 jours minimum
- Documenter les changements déployés

---

## 📊 Métriques de Succès

### Performance
- Temps de chargement < 3 secondes
- Temps de réponse API < 500ms
- Disponibilité > 99.9%

### Sécurité
- HTTPS actif
- Headers de sécurité configurés
- Backup quotidien fonctionnel

### Fonctionnel
- 0 erreurs 500
- Toutes les fonctionnalités testées
- Email de confirmation fonctionnel

---

## 📞 Support

En cas de problème:
1. Vérifier les logs (`storage/logs/laravel.log`)
2. Consulter cette documentation
3. Vérifier DEPLOYMENT.md pour détails avancés
4. Contacter l'équipe technique

**Fichiers de référence:**
- `DEPLOYMENT.md` - Guide complet de déploiement
- `deploy.sh` - Script automatisé Linux
- `deploy-windows.ps1` - Script automatisé Windows
- `.env.example` - Variables d'environnement
