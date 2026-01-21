-- ========================================
-- Script de Nettoyage des Chemins de Fichiers
-- ========================================
-- Date: 3 Décembre 2025
-- Objectif: Nettoyer les chemins de fichiers avec paramètres WordPress

-- ========================================
-- 1. NETTOYER LES IMAGES DES ACTUALITÉS
-- ========================================

-- Afficher les images avec paramètres WordPress avant nettoyage
SELECT 
    id, 
    titre, 
    image AS 'Avant Nettoyage'
FROM actualites 
WHERE image LIKE '%?%'
ORDER BY id;

-- Nettoyer les paramètres WordPress (?w=XXX, etc.)
UPDATE actualites 
SET image = SUBSTRING_INDEX(image, '?', 1)
WHERE image LIKE '%?%';

-- Vérifier après nettoyage
SELECT 
    id, 
    titre, 
    image AS 'Après Nettoyage'
FROM actualites 
WHERE image IS NOT NULL
ORDER BY id;

-- ========================================
-- 2. METTRE À NULL LES FICHIERS INEXISTANTS
-- ========================================

-- Cette section nécessite une vérification manuelle des fichiers
-- NE PAS EXÉCUTER AUTOMATIQUEMENT

-- Option A: Mettre à NULL toutes les images d'actualités
-- (À n'utiliser que si vous allez re-téléverser toutes les images)
/*
UPDATE actualites SET image = NULL WHERE id IN (
    5,6,7,8,9,10,11,12,13,14,15,16,17,20,21,22,23,24,25,26,27,28,29,30,32,33,34,35,36,37,38,39
);
*/

-- Option B: Mettre à NULL uniquement les images manquantes spécifiques
-- Remplacer par les IDs réels vérifiés
/*
UPDATE actualites SET image = NULL WHERE id IN (5,6,7);
*/

-- ========================================
-- 3. STATISTIQUES APRÈS NETTOYAGE
-- ========================================

-- Compter les actualités avec/sans images
SELECT 
    COUNT(*) AS total,
    SUM(CASE WHEN image IS NOT NULL THEN 1 ELSE 0 END) AS avec_image,
    SUM(CASE WHEN image IS NULL THEN 1 ELSE 0 END) AS sans_image
FROM actualites;

-- Compter les publications avec/sans fichiers
SELECT 
    COUNT(*) AS total,
    SUM(CASE WHEN fichier_pdf IS NOT NULL THEN 1 ELSE 0 END) AS avec_fichier,
    SUM(CASE WHEN fichier_pdf IS NULL THEN 1 ELSE 0 END) AS sans_fichier
FROM publications;

-- Compter les médias avec/sans fichiers
SELECT 
    COUNT(*) AS total,
    SUM(CASE WHEN medias IS NOT NULL THEN 1 ELSE 0 END) AS avec_fichier,
    SUM(CASE WHEN medias IS NULL THEN 1 ELSE 0 END) AS sans_fichier
FROM medias;

-- ========================================
-- 4. LISTER LES CHEMINS ACTUELS
-- ========================================

-- Lister tous les chemins d'images des actualités
SELECT id, titre, image
FROM actualites
WHERE image IS NOT NULL
ORDER BY created_at DESC;

-- Lister tous les chemins de fichiers des publications
SELECT id, titre, fichier_pdf
FROM publications
WHERE fichier_pdf IS NOT NULL
ORDER BY created_at DESC;

-- Lister tous les chemins de fichiers des médias
SELECT id, titre, medias, type
FROM medias
WHERE medias IS NOT NULL
ORDER BY created_at DESC;

-- ========================================
-- 5. BACKUP DES DONNÉES AVANT MODIFICATION
-- ========================================

-- Créer une table de backup des actualités
CREATE TABLE IF NOT EXISTS actualites_backup_20251203 AS
SELECT * FROM actualites;

-- Créer une table de backup des publications
CREATE TABLE IF NOT EXISTS publications_backup_20251203 AS
SELECT * FROM publications;

-- Créer une table de backup des médias
CREATE TABLE IF NOT EXISTS medias_backup_20251203 AS
SELECT * FROM medias;

-- ========================================
-- 6. RESTAURER DEPUIS BACKUP (SI NÉCESSAIRE)
-- ========================================

/*
-- Restaurer les actualités
UPDATE actualites a
INNER JOIN actualites_backup_20251203 b ON a.id = b.id
SET a.image = b.image;

-- Restaurer les publications
UPDATE publications p
INNER JOIN publications_backup_20251203 b ON p.id = b.id
SET p.fichier_pdf = b.fichier_pdf;

-- Restaurer les médias
UPDATE medias m
INNER JOIN medias_backup_20251203 b ON m.id = b.id
SET m.medias = b.medias;
*/
