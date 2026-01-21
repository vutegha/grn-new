# Structure des Informations de Contact

## Vue d'ensemble

Les informations de contact sont organisées pour afficher uniquement les informations essentielles sur le site public, tout en conservant les détails complets pour l'administration interne.

## Types de Contact

### 1. Bureau Principal
- Type : `bureau_principal`
- Utilisation : Siège social de l'IRI
- Affichage public : Toutes les informations (adresse, email, téléphone, horaires)

### 2. Bureau Régional / Bureau de Liaison
- Type : `bureau_regional`
- Utilisation : Bureaux régionaux et bureaux de liaison
- **Particularité** : Les points focaux sont intégrés dans ce type via les champs `responsable_*`

### 3. Point Focal (DÉPRÉCIÉ)
- Type : `point_focal`
- **IMPORTANT** : Ne plus utiliser comme type séparé
- Les points focaux doivent être ajoutés dans la section "Responsable / Point Focal" d'un bureau régional

## Champs de la Base de Données

### Informations du Bureau (Usage interne - NON affichées sur le site public)
- `email` : Email du bureau
- `telephone` : Téléphone du bureau
- `telephone_secondaire` : Téléphone secondaire
- `horaires` : Horaires d'ouverture

### Informations du Responsable/Point Focal (AFFICHÉES sur le site public)
- `responsable_nom` : Nom complet du responsable
- `responsable_fonction` : Fonction (ex: "Coordinateur Régional", "Point Focal Régional")
- `responsable_email` : Email du responsable (affiché et utilisé pour le bouton contact)
- `responsable_telephone` : Téléphone du responsable
- `photo` : Photo du responsable (affichée dans la carte)

### Informations Générales (Affichées)
- `adresse` : Adresse du bureau (affichée)
- `ville`, `province`, `pays` : Localisation
- `nom` : Nom du bureau
- `titre` : Titre descriptif

## Affichage sur le Site Public

### Page Contact - Bureau Régional
Chaque carte de bureau régional affiche dans cet ordre :

1. **En-tête** (fond vert)
   - Nom du bureau
   - Ville et province
   - Badge "Bureau Régional"

2. **Point Focal** (zone verte mise en évidence)
   - Photo du responsable
   - Nom du responsable
   - Fonction
   - Email
   - Téléphone

3. **Informations du Bureau**
   - Adresse uniquement

4. **Bouton d'Action**
   - "Contacter le point focal" (lien vers `responsable_email`)

### Informations NON Affichées
- ❌ Email du bureau
- ❌ Téléphone du bureau
- ❌ Horaires d'ouverture

## Instructions pour l'Administration

### Créer un Bureau Régional avec Point Focal

1. **Aller dans** : Admin > Informations de Contact > Ajouter
2. **Sélectionner le type** : "Bureau Régional / Bureau de Liaison"
3. **Remplir les informations de base** :
   - Nom : Ex. "Bureau de Liaison - Tanganyika"
   - Titre : Ex. "Bureau Régional de Kalemie"
   - Adresse complète du bureau

4. **Coordonnées de contact du bureau** (Usage interne uniquement)
   - Ces champs sont optionnels et ne seront PAS affichés sur le site

5. **Responsable / Point Focal** (Section importante - AFFICHÉE publiquement)
   - ✅ Remplir obligatoirement :
     - Nom complet
     - Fonction (ex: "Coordinateur Régional")
     - Email (sera affiché et utilisé pour le contact)
     - Téléphone
     - Photo (recommandée)

### Modifier un Bureau Existant

1. Cliquer sur "Modifier" sur la carte du bureau
2. Mettre à jour les informations du responsable/point focal
3. Sauvegarder

## Structure du Code

### Vues

#### Site Public
- `resources/views/contact.blade.php` : Page de contact publique
  - Affiche uniquement adresse + responsable/point focal
  - Bouton contact vers `responsable_email`

#### Administration
- `resources/views/admin/contact-info/index.blade.php` : Liste des contacts
  - Section "Bureaux Régionaux" avec note explicative
  - Pas de section "Points Focaux" séparée
  
- `resources/views/admin/contact-info/_form.blade.php` : Formulaire
  - Type "Point Focal" supprimé du select
  - Notes explicatives ajoutées
  - Section "Responsable / Point Focal" bien identifiée

- `resources/views/admin/contact-info/_card.blade.php` : Carte d'affichage admin
  - Affiche toutes les informations

### Modèles
- `app/Models/ContactInfo.php`
  - Scope `scopePointsFocaux()` commenté
  - Type "point_focal" déprécié dans `getTypeLibelleAttribute()`

### Seeders
- `database/seeders/ContactInfoSeeder.php`
  - Vide la table avant chaque exécution (`truncate()`)
  - Exemple de bureau régional avec point focal intégré
  - Pas d'entrée de type "point_focal" indépendante

## Migration de Données Existantes

Si vous avez des points focaux existants de type `point_focal`, vous pouvez :

1. Créer un nouveau bureau régional
2. Copier les informations du point focal dans la section "Responsable / Point Focal"
3. Supprimer l'ancien enregistrement de type `point_focal`

Ou via SQL :
```sql
-- Désactiver les anciens points focaux
UPDATE contact_infos SET actif = 0 WHERE type = 'point_focal';
```

## Support

Pour toute question sur la structure, consulter :
- Ce document
- Les notes dans le formulaire admin
- Les commentaires dans la migration `2025_12_07_000000_create_contact_infos_table.php`
