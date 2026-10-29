# Journal de quêtes

Petit projet PHP / MySQL : afficher et ajouter des quêtes.

## Installation

1. Lancer MAMP.
2. Dans phpMyAdmin, créer la base `sg-save` avec la table `quete` :
   - `id_quete` INT, clé primaire, AUTO_INCREMENT
   - `nom` VARCHAR(255), non nul
   - `etat` TINYINT, non nul, défaut 0 (0 = à faire, 1 = terminée)
3. Adapter `config.php` (port, utilisateur, mot de passe).
4. Ouvrir `index.php` dans le navigateur.

## Fichiers

- `config.php` : réglages de connexion
- `connexion.php` : connexion PDO à MySQL
- `fonction.php` : compte les quêtes restantes
- `index.php` : liste des quêtes + formulaire d'ajout (nom et état)
- `ajouter.php` : vérifie le nom et l'état, enregistre la quête (INSERT préparé) et revient à la liste
- `asset/` : header et footer
