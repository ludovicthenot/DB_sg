# Journal de quêtes

Application PHP/MySQL pour ajouter, filtrer et consulter des quêtes, mémoriser un thème et exporter les données en JSON.

## Avancement

- [x] Partie 1 : filtres GET, tri décroissant, compteurs et message de liste vide.
- [x] Partie 2 : confirmation temporaire en session.
- [x] Partie 3 : thème clair/sombre par cookie valable sept jours.
- [x] Partie 4 : export et lecture JSON.
- [ ] Partie 5 : formulaire présent ; traitement de l'upload à terminer.
- [x] Partie 6 : documentation, configurations d'exemple et exclusions Git.
- [x] Partie 7 : classe Quete et découverte des objets.
- [x] Partie 8 : séparation du traitement et de la vue.
- [ ] Parties 9 et 10 : namespace et routage avec la formatrice.

Les cases décrivent le code en place. Les tests non confirmés sont précisés ci-dessous ; l'upload reste incomplet.

## Installation

1. Installer MAMP avec PHP 8.1 ou plus récent et MySQL. Activer `pdo_mysql`, `mbstring` et `fileinfo` dans la configuration du serveur. Redémarrer les serveurs après une modification.
2. Créer une base MySQL, par exemple `sg-save`, et la table `quete` :
   - `id_quete` : INT, clé primaire, AUTO_INCREMENT ;
   - `nom` : VARCHAR(255), NOT NULL ;
   - `etat` : TINYINT, NOT NULL, DEFAULT 0.
3. Copier `config.exemple.php` vers `config.php` et renseigner l'hôte, le port MySQL, la base et les identifiants locaux.
4. Copier `connexion.exemple.php` vers `connexion.php`. La connexion PDO utilise les réglages de `config.php`.
5. Copier `stockage.exemple.php` vers `stockage.php`. Adapter `$dossierPrive` à un dossier hors de la racine web, accessible en écriture par PHP. Exemple Windows/MAMP : `C:/MAMP/donnees-privees-quetes`.
6. Démarrer Apache et MySQL, puis ouvrir `index.php` via le serveur. Dans la configuration actuelle, le projet est la racine web : `http://localhost:8888/index.php`. Adapter l'adresse au port et à la racine web choisis.

L'application limite les noms à 150 caractères. L'état 0 signifie « à faire » et l'état 1 signifie « terminée ». Un dossier voisin du projet mais encore situé dans la racine web n'est pas privé.

## Organisation

| Fichier | Rôle |
| --- | --- |
| `index.php` | Lecture de la demande, requêtes et préparation des données |
| `vues/liste.php` | Affichage du journal et chargement du header/footer |
| `ajouter.php` | Validation, insertion et confirmation |
| `securite.php` | Session et contrôle du POST et du jeton CSRF |
| `preference.php` | Validation et enregistrement du thème |
| `fonction.php` | Comptage des quêtes à faire |
| `exporter.php` | Export de toutes les quêtes en JSON |
| `lire-export.php` | Lecture et validation du fichier JSON |
| `upload.php` | Formulaire d'upload, traitement encore à réaliser |
| `asset/` | Header, footer et CSS |
| `*.exemple.php` | Modèles de configuration locale |
| `donnees.php` | Anciennes données statiques, non utilisées par la liste actuelle |
| `decouverte-poo/Quete.php` | Classe de découverte, indépendante de MySQL |
| `decouverte-poo/test.php` | Affichage de deux objets et de leurs états |

Les exemples du sujet ont été adaptés aux noms du projet : table `quete`, colonne `etat`, filtres `a-faire` et `terminee`.

## Filtrage des quêtes avec GET

### Fonctionnement
Le paramètre `etat` accepte `toutes`, `a-faire` ou `terminee`. Une valeur absente ou inconnue affiche tout. Le tri est décroissant par identifiant et les compteurs concernent la sélection affichée.

### Tests effectués
Vérification HTTP du 5 novembre 2026 : liste complète, 12 quêtes ; à faire, 8 ; terminées, 4 ; filtre inconnu, 12. Les quatre réponses sont HTTP 200. Le message de liste vide est présent dans le code ; son affichage reste à confirmer avec une sélection vide. Vérifier aussi visuellement l'ordre après un ajout.

### Difficultés
Aucun message d'erreur relevé pour le filtre. Adaptation des noms SQL et des valeurs du sujet au projet. Le compteur a été renommé « Quêtes affichées ».

### Explication du code
`$_GET` lit le filtre et `in_array(..., true)` valide les choix. `prepare()` et `execute()` transmettent l'état séparément de la requête. `ORDER BY id_quete DESC` trie les résultats. GET convient à la consultation : le filtre reste dans une URL partageable et ne modifie pas les données.

## Confirmation avec une session

### Fonctionnement
Après insertion, le traitement place une confirmation dans la session et redirige vers la liste avec HTTP 303. La liste affiche puis supprime le message. Le nom doit être non vide et comporter au maximum 150 caractères ; l'état vaut 0 ou 1.

### Tests effectués
Syntaxe PHP et présence des contrôles vérifiées. Un test HTTP avec une session isolée a confirmé que le message apparaît une seule fois puis disparaît au rechargement. À confirmer dans le navigateur : parcours complet d'ajout et confirmation ; refus d'espaces seuls ; acceptation de 150 caractères et refus de 151 côté serveur ; refus d'un POST sans jeton CSRF.

### Difficultés
Aucun message d'erreur relevé. La limite HTML initiale de 255 caractères a été remplacée par 150 avec un contrôle serveur supplémentaire. L'extension `mbstring` est active dans la configuration MAMP.

### Explication du code
`mb_strlen($nom, 'UTF-8')` compte les caractères. `index.php` récupère le message dans `$message` puis le retire de la session avec `unset()`. La vue affiche cette variable si c'est une chaîne. Une redirection crée une nouvelle requête HTTP : une variable ordinaire n'y conserve pas sa valeur, contrairement à la session. La session est déjà démarrée dans `securite.php`.

## Préférence avec un cookie

### Fonctionnement
Un POST protégé transmet `clair` ou `sombre`. Le cookie expire après sept jours. La page valide sa valeur, sélectionne l'option correspondante et applique une classe CSS. Le thème clair sert de valeur par défaut.

### Tests effectués
Ludo a confirmé la conservation du choix au rechargement et le changement visuel des couleurs. Vérification HTTP du 5 novembre 2026 : `sombre` produit `theme-sombre`, `clair` produit `theme-clair`, et `violet` revient à `theme-clair`. La conservation après fermeture et réouverture du navigateur reste à confirmer.

### Difficultés
Un slash manquait avant `securite.php` et le `!` manquait devant `in_array`. Corrections effectuées ; les messages d'erreur exacts n'ont pas été relevés. Le CSS ne s'appliquait pas : le header cherchait `styles.css` alors que le fichier s'appelle `style.css`. Le chemin a été corrigé.

### Explication du code
`setcookie()` est appelé avant le HTML. `time() + 7 * 24 * 60 * 60` fixe l'expiration et `$_COOKIE` reçoit le choix à la requête suivante. Un cookie est conservé côté navigateur. Avec les sessions PHP usuelles, les données restent côté serveur et le navigateur garde un identifiant de session.

## Export JSON

### Fonctionnement
Un formulaire POST protégé déclenche l'export de toutes les quêtes vers `quetes.json`, dans le dossier privé. Un message en session confirme l'opération.

### Tests effectués
Ludo a confirmé la création du fichier et la présence des quêtes. Une vérification a confirmé un JSON sous forme de liste avec les champs `id_quete`, `nom` et `etat`. Syntaxe PHP valide.

### Difficultés
La variable du chemin a été harmonisée en `$fichierExport`. L'existence des dossiers et leur accessibilité en écriture ont été vérifiées. Aucun message d'erreur d'export signalé.

### Explication du code
`json_encode()` convertit le tableau PHP en JSON. Les options conservent les accents, facilitent la lecture et déclenchent une exception en cas d'erreur. `file_put_contents(..., LOCK_EX)` remplace le fichier sous verrou exclusif pendant l'écriture. L'export est une copie des données à un instant donné, pas une synchronisation automatique avec MySQL.

## Lecture JSON

### Fonctionnement
La page lit le fichier sans requête MySQL. Elle vérifie son existence, le JSON, la structure de liste et le nom de chaque quête. Les noms et les erreurs sont échappés avant affichage.

### Tests effectués
Ludo a confirmé la lecture et trois tests : une nouvelle quête n'apparaît qu'après un nouvel export ; un fichier absent produit un message ; un contenu remplacé temporairement par `{` produit une erreur JSON. Les trois tests ont été déclarés réussis. Le 5 novembre 2026, la page a répondu HTTP 200 avec 12 éléments. Restent à tester : liste vide et JSON valide de structure incorrecte.

### Difficultés
Aucune difficulté signalée pendant les tests confirmés.

### Explication du code
`is_file()` vérifie le fichier, `file_get_contents()` le lit et `json_decode(..., true, 512, JSON_THROW_ON_ERROR)` produit des tableaux PHP. `array_is_list()` vérifie la structure. `htmlspecialchars()` affiche les noms comme du texte.

## Upload de fichier texte

### Fonctionnement
Le formulaire POST utilise `multipart/form-data`, un champ `document` et le jeton CSRF. Le dossier privé `uploads` existe. Le traitement serveur n'est pas encore implémenté : aucun fichier envoyé n'est conservé par l'application.

### Tests effectués
Le 5 novembre 2026, le formulaire a répondu HTTP 200. Un fichier texte de test de 103 octets a été préparé. `fileinfo` a été activée et vérifiée en ligne de commande avec la configuration MAMP : le texte est reconnu comme `text/plain`. La prise en compte de cette modification par le serveur après redémarrage reste à confirmer.

### Difficultés
`fileinfo` était désactivée ; la configuration a été corrigée. Aucun message d'erreur exact d'upload n'a été relevé. Restent à programmer les contrôles du transfert, de taille (102 400 octets maximum), de fichier non vide, d'extension et de MIME, puis le nom unique et le déplacement. Les tests de refus, de réussite et de noms identiques restent à faire.

### Explication du code
`enctype="multipart/form-data"` permet l'envoi. `accept` aide seulement à sélectionner un fichier. Le traitement devra utiliser `$_FILES`, `is_uploaded_file()`, `filesize()`, `pathinfo()`, `finfo`, `random_bytes()` et `move_uploaded_file()`. Le type annoncé par le navigateur ne remplace pas la détection côté serveur.

## Découverte des objets : classe Quete

### Fonctionnement
La classe `Quete` possède les propriétés publiques typées `$nom` et `$terminee`. Le constructeur reçoit le nom et initialise l'état à `false`. `terminer()` passe l'état à `true` et `reprendre()` le remet à `false`. La page `decouverte-poo/test.php` crée « Explorer la forêt » et « Retrouver le grimoire », termine la première et affiche les deux objets dans une liste HTML avec des noms échappés.

### Tests effectués
Vérifications PHP réussies : deux noms distincts, état initial à faire pour les deux objets, terminaison du premier sans modifier le second, puis retour du premier à faire avec `reprendre()`. La page répond HTTP 200 et affiche les deux noms avec les états « Terminée » et « En cours ». Syntaxe des deux fichiers valide. Accès : `http://localhost:8888/decouverte-poo/test.php` dans la configuration actuelle.

### Difficultés
Aucune erreur constatée pendant ces vérifications. La classe utilise `nom`, conformément au vocabulaire du projet.

### Explication du code
Une propriété conserve une information ; une méthode réalise une action. `new` crée une nouvelle instance et déclenche son constructeur. `$this` désigne l'objet courant : modifier une instance ne modifie pas l'autre. `$quete->nom` accède à la propriété d'un objet, tandis que `$quete['nom']` accède à une entrée de tableau. Aucun de ces objets n'est relié à MySQL : appeler `terminer()` ne sauvegarde rien en base. Les objets de la page de test sont recréés à chaque requête.

## Séparation du traitement et de l'affichage

### Fonctionnement
`index.php` conserve le démarrage de session, la validation du filtre, les requêtes SQL, la lecture du cookie, les compteurs et la préparation du message. Il charge ensuite `vues/liste.php`, qui contient le HTML, les formulaires, la liste, les affichages PHP et les inclusions du header/footer.

### Tests effectués
Comparaison HTTP avant/après sur six cas : liste complète, deux filtres, filtre inconnu, thème sombre et cookie invalide. Les réponses et leur contenu sont identiques après normalisation des espaces et des jetons CSRF. Les pages de lecture JSON et d'upload ainsi que le CSS répondent HTTP 200. Le message de session a été vérifié sur deux requêtes successives. La syntaxe PHP de l'index et de la vue est valide. Les actions des formulaires sont conservées ; aucun ajout en base ni écrasement d'export n'a été nécessaire pour ces tests.

### Difficultés
Les inclusions du header et du footer ont été adaptées à `__DIR__ . '/../asset/...'` depuis le sous-dossier `vues`. Les liens HTML restent relatifs à l'adresse `index.php` demandée par le navigateur.

### Explication du code et lien avec MVC
La vue présente les données préparées, notamment `$quetes`, `$total`, `$restantes`, `$terminer`, `$theme` et `$message`. Le contrôleur reçoit la demande et coordonne les traitements : `index.php` commence à tenir ce rôle. Le modèle gère les données et les règles métier. Le projet n'est pas encore entièrement MVC : les requêtes SQL restent dans `index.php`. Lors d'une prochaine restructuration, elles pourraient être déplacées dans une classe dédiée à l'accès aux quêtes, par exemple `QueteRepository`. La classe de découverte `Quete` reste indépendante de cette évolution.

## Git et reprise

`config.php`, `connexion.php` et `stockage.php` sont locaux et exclus de Git. Après clonage, les reconstruire à partir des exemples. Les exports et uploads restent hors du dépôt et de la racine web.

Les configurations sont retirées du suivi avec `git rm --cached`, ce qui conserve les fichiers sur l'ordinateur. Cela ne supprime pas les anciennes versions de l'historique. Si des identifiants réels ont été publiés, ils doivent être remplacés.

Avant livraison, vérifier `git status` et `git diff --cached`, exécuter les contrôles adaptés, puis créer un commit et faire un push. La prochaine reprise concerne le traitement de `upload.php`, les tests non confirmés et la mise à jour de ce bilan.
