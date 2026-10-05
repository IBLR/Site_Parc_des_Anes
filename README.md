# Parc des Ânes

Documentation technique de l'application web **Parc des Anes**.
Développeur du projet : **Isabelle BAILLY LE ROCH**

---

## 1. Présentation du projet

### 1.1. Description

**Parc des Anes** est une application web développée en PHP permettant de présenter un parc animalier, d'informer les visiteurs, de gérer une boutique en ligne et de proposer un système de dons.

L'application comprend deux espaces principaux :

- un espace public accessible aux visiteurs ;
- un espace d'administration permettant de gérer les données du site.

L'application repose sur une architecture MVC développée sans framework PHP, avec une séparation entre :

- les contrôleurs ;
- les modèles d'accès aux données ;
- les vues ;
- les composants techniques communs ;
- les ressources frontend.

### 1.2. Fonctionnalités principales

#### Espace public

- page d'accueil ;
- présentation des ânes du parc ;
- page de contact ;
- formulaire de contact ;
- carte interactive ;
- boutique ;
- affichage des produits disponibles ;
- filtrage des produits par catégorie ;
- panier ;
- création de commande ;
- authentification ;
- création de compte ;
- gestion du compte utilisateur ;
- consultation de l'historique des commandes ;
- consultation de l'historique des dons ;
- modification du mot de passe ;
- système de dons ;
- pages légales ;
- page de maintenance.

#### Espace d'administration

- tableau de bord ;
- gestion des produits ;
- création, modification et suppression de produits ;
- gestion des catégories ;
- création, modification et suppression de catégories ;
- consultation des messages de contact ;
- modification du statut des messages ;
- consultation des commandes ;
- modification du statut des commandes ;
- consultation des dons ;
- modification du statut des dons.

### 1.3. Principe général

L'application utilise `public/index.php` comme point d'entrée HTTP.

Une URL est transmise au routeur via le paramètre `url`. Le routeur détermine ensuite le contrôleur et l'action à exécuter.

Exemple :

```text
/?url=boutique
```

est transmis au :

```text
ProductController::index()
```

Les contrôleurs assurent la coordination entre :

- la requête HTTP ;
- la validation ;
- la session ;
- les règles d'accès ;
- les modèles ;
- les vues.

---

# 2. Technologies utilisées

## 2.1. Backend

- **PHP 8.2+**
- architecture MVC personnalisée ;
- programmation orientée objet ;
- espaces de noms PHP ;
- PDO pour l'accès à la base de données ;
- sessions PHP ;
- Apache et `.htaccess`.

Le projet ne repose pas sur un framework PHP ni sur Composer.

## 2.2. Frontend

- HTML5 ;
- CSS3 ;
- JavaScript ;
- API Fetch ;
- manipulation du DOM ;
- JavaScript natif sans framework frontend.

Le CSS est organisé par niveaux :

- fondations ;
- layout ;
- composants ;
- pages ;
- utilitaires.

## 2.3. Base de données

- MariaDB / MySQL ;
- accès via PDO ;
- moteur InnoDB ;
- encodage `utf8mb4`.

Le dump fourni a été généré avec :

```text
MariaDB 10.4.32
PHP 8.2.12
phpMyAdmin 5.2.1
```

Le fichier d'import est :

```text
db_parc_des_anes.sql
```

## 2.4. Bibliothèque externe

La carte interactive utilise **Leaflet 1.9.4**.

Les données cartographiques sont fournies par **OpenStreetMap**.

Leaflet est chargé depuis `unpkg.com` et la carte utilise les tuiles OpenStreetMap.

---

# 3. Architecture du projet

## 3.1. Organisation générale

```text
PARC_DES_ANES/
│
├── config/
│   ├── .htaccess
│   ├── app.php
│   ├── database.php
│   └── meta.php
│
├── logs/
│   ├── .htaccess
│   └── app_errors.log
│
├── public/
│   ├── .htaccess
│   ├── index.php
│   ├── assets/
│   ├── icons/
│   ├── images/
│   ├── scripts/
│   ├── styles/
│   └── uploads/
│
├── src/
│   ├── Controllers/
│   ├── Core/
│   ├── Models/
│   └── Views/
│
└── db_parc_des_anes.sql
```

## 3.2. Répertoire `config`

Contient la configuration technique de l'application :

- `app.php` : constantes générales et chemins ;
- `database.php` : paramètres de connexion à la base ;
- `meta.php` : données utilisées pour les métadonnées des pages.

Le répertoire est protégé par `.htaccess` :

```apache
Require all denied
```

Il n'est donc pas destiné à être directement accessible depuis le navigateur.

## 3.3. Répertoire `logs`

Contient les journaux d'erreurs de l'application.

Le répertoire est également protégé par :

```apache
Require all denied
```

afin d'empêcher l'accès direct aux fichiers de log depuis le navigateur.

## 3.4. Répertoire `public`

`public/` constitue la partie exposée au serveur web.

Il contient :

- le point d'entrée `index.php` ;
- les ressources CSS ;
- les scripts JavaScript ;
- les images ;
- les icônes ;
- les polices ;
- les fichiers uploadés.

Le `.htaccess` redirige les URLs qui ne correspondent pas directement à un fichier ou un répertoire vers `index.php`.

```apache
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [L]
```

## 3.5. Répertoire `src`

Contient le code applicatif non directement exposé au navigateur.

Il est organisé selon une architecture MVC :

```text
src/
├── Controllers/
├── Core/
├── Models/
└── Views/
```

---

# 4. Architecture MVC

## 4.1. Contrôleurs

Les contrôleurs reçoivent les requêtes et coordonnent le traitement.

Ils sont organisés par fonctionnalité :

```text
Controllers/
├── Admin/
├── Api/
├── Auth/
├── Cart/
├── Contact/
├── Donation/
├── Donkeys/
├── Home/
├── Legal/
├── Shop/
├── System/
└── User/
```

Cette organisation permet de limiter le couplage entre les différentes fonctionnalités.

## 4.2. Modèles

Les modèles assurent l'accès aux données :

```text
Models/
├── ContactModel.php
├── DonationModel.php
├── OrderModel.php
├── ProductCategoryModel.php
├── ProductModel.php
└── UserModel.php
```

Les requêtes SQL sont regroupées dans ces classes plutôt que directement dans les vues.

## 4.3. Vues

Les vues sont organisées par fonctionnalité :

```text
Views/
├── admin/
├── auth/
├── cart/
├── contact/
├── donation/
├── donkeys/
├── home/
├── layouts/
├── legal/
├── partials/
├── shop/
├── system/
└── user/
```

Les pages complexes sont elles-mêmes découpées en sections réutilisables.

Exemple :

```text
Views/shop/
├── index.php
└── sections/
    ├── hero.php
    └── products.php
```

## 4.4. Composants techniques communs

Le répertoire `src/Core` contient les composants transversaux :

```text
Core/
├── Autoload.php
├── CsrfProtect.php
├── DatabaseConnect.php
├── FormValidator.php
├── Logger.php
├── Meta.php
└── Router.php
```

### `Autoload.php`

Assure le chargement automatique des classes de l'application.

### `DatabaseConnect.php`

Gère la connexion PDO à la base de données selon un principe de connexion partagée.

### `Router.php`

Analyse la route demandée et appelle le contrôleur correspondant.

### `CsrfProtect.php`

Génère et vérifie les jetons CSRF associés aux formulaires.

### `FormValidator.php`

Centralise la validation de plusieurs champs utilisés par les formulaires.

### `Logger.php`

Enregistre les exceptions dans le fichier de log.

### `Meta.php`

Gère les données utilisées pour les métadonnées des pages.

---

# 5. Point d'entrée et routage

## 5.1. Point d'entrée

Le fichier :

```text
public/index.php
```

est le point d'entrée principal de l'application.

Son fonctionnement est volontairement minimal :

1. démarrage de la session ;
2. détermination du chemin racine ;
3. chargement de l'autoloader ;
4. chargement de la configuration ;
5. récupération de l'URL demandée ;
6. transmission de la route au `Router`.

Exemple :

```php
$url = $_GET['url'] ?? 'accueil';

$router = new Router();
$router->renderPage($url);
```

## 5.2. Routes principales

| Route | Contrôleur | Fonction |
|---|---|---|
| `accueil` | `HomeController` | Accueil |
| `connexion` | `AuthController` | Connexion |
| `inscription` | `AuthController` | Création de compte |
| `deconnexion` | `AuthController` | Déconnexion |
| `compte` | `UserController` | Compte utilisateur |
| `compte/update-password` | `UserController` | Modification du mot de passe |
| `nos-anes` | `DonkeysController` | Présentation des ânes |
| `contact` | `ContactController` | Page contact |
| `contact/send` | `ContactController` | Traitement du formulaire |
| `boutique` | `ProductController` | Boutique |
| `api/products` | `ApiProductController` | Données produits |
| `panier` | `CartController` | Panier |
| `panier/send` | `CartController` | Validation du panier |
| `don` | `DonationController` | Page de don |
| `don/checkout` | `DonationController` | Formulaire du don |
| `don/send` | `DonationController` | Enregistrement du don |
| `mentions-legales` | `LegalController` | Mentions légales |
| `politique-confidentialite` | `LegalController` | Politique de confidentialité |
| `cgv` | `LegalController` | CGV |

## 5.3. Routes d'administration

```text
/admin
/admin/produits
/admin/produits/create
/admin/produits/update
/admin/produits/delete

/admin/categories
/admin/categories/create
/admin/categories/update
/admin/categories/delete

/admin/contacts
/admin/contacts/show
/admin/contacts/update-status

/admin/commandes
/admin/commandes/show
/admin/commandes/update-status

/admin/dons
/admin/dons/show
/admin/dons/update-status
```

Les routes d'administration commencent par vérifier que l'utilisateur est authentifié.

L'autorisation administrateur est ensuite contrôlée par le `BaseController`.

---

# 6. Gestion des utilisateurs et des sessions

## 6.1. Authentification

L'authentification est assurée par `AuthController`.

Lors de la connexion :

1. le formulaire est soumis ;
2. le jeton CSRF est vérifié ;
3. l'adresse e-mail est validée ;
4. l'utilisateur est recherché en base ;
5. le mot de passe est vérifié avec `password_verify()` ;
6. l'identifiant de session est renouvelé ;
7. les informations nécessaires sont enregistrées dans `$_SESSION['user']`.

Le mot de passe n'est jamais enregistré dans la session.

## 6.2. Création de compte

Lors de l'inscription :

- l'adresse e-mail est contrôlée ;
- l'unicité de l'e-mail est vérifiée ;
- le mot de passe doit comporter au minimum 8 caractères ;
- le mot de passe est haché avec `password_hash()` avant son enregistrement.

## 6.3. Gestion des rôles

La table `users` possède un champ :

```text
role
```

Les rôles actuellement utilisés sont :

```text
user
admin
```

Lors de la connexion, le rôle est copié dans la session et une valeur booléenne :

```php
'isAdmin' => ($user['role'] === 'admin')
```

est également créée.

## 6.4. Protection des routes

Le routeur dispose d'une méthode `checkAuth()`.

Elle vérifie la présence de :

```php
$_SESSION['user']
```

Si l'utilisateur n'est pas connecté, il est redirigé vers la page de connexion.

La route demandée est conservée dans :

```text
$_SESSION['redirect_after_login']
```

afin de permettre une redirection après authentification.

---

# 7. Gestion de la boutique

## 7.1. Produits

Les produits sont stockés dans la table :

```text
products
```

Chaque produit possède notamment :

- une référence ;
- un nom ;
- une description ;
- un prix ;
- un poids éventuel ;
- un indicateur de disponibilité ;
- une image ;
- une catégorie.

Le champ :

```text
is_available
```

détermine si le produit est visible dans la boutique publique.

## 7.2. Catégories

Les catégories sont stockées dans :

```text
products_categories
```

Elles possèdent :

- un libellé ;
- une description ;
- un ordre d'affichage.

L'ordre est rendu unique par une contrainte SQL.

## 7.3. Administration des produits

`AdminProductController` permet :

- l'affichage de la liste ;
- la création ;
- la modification ;
- la suppression.

Lors de la création d'un produit, une référence est générée à partir de son identifiant technique.

Le format utilisé est de type :

```text
PROD-000001
```

## 7.4. Images produits

Les images sont enregistrées dans :

```text
public/uploads/products/
```

Le contrôleur d'administration prend en charge :

- la réception du fichier ;
- la vérification du type MIME ;
- la génération du nom de fichier ;
- le déplacement du fichier ;
- la suppression sécurisée du fichier lors d'une suppression ou d'un remplacement.

Les chemins de stockage sont centralisés dans `config/app.php`.

---

# 8. Panier et commandes

## 8.1. Panier

Le panier est géré côté client avec plusieurs scripts JavaScript :

```text
cart_form.js
cart_products.js
cart_submit.js
```

Les données sont ensuite transmises au backend via :

```text
panier/send
```

Le serveur ne doit pas considérer les données JavaScript comme une source de confiance.

Le contrôleur reconstruit les informations nécessaires à partir des données disponibles côté serveur avant l'enregistrement.

## 8.2. Création d'une commande

Les commandes sont enregistrées dans deux tables :

```text
orders
order_items
```

Une commande possède :

- un numéro de commande ;
- un utilisateur ;
- les coordonnées du client ;
- l'adresse de livraison ;
- un statut ;
- une date de création.

Les lignes de commande possèdent :

- la commande ;
- le produit ;
- la référence produit ;
- le nom du produit ;
- le prix unitaire ;
- la quantité ;
- le sous-total.

## 8.3. Transaction SQL

La création d'une commande est effectuée dans une transaction.

Le principe est :

```text
BEGIN TRANSACTION
    création de la commande
    récupération de l'ID
    génération du numéro de commande
    création des lignes de commande
COMMIT
```

En cas d'échec d'une étape :

```text
ROLLBACK
```

est effectué.

Cela évite notamment d'obtenir une commande sans ses lignes associées.

## 8.4. Numéro de commande

Le numéro affichable est généré à partir de l'identifiant technique.

Format :

```text
CMD-AAAA-XXXXXX
```

Exemple :

```text
CMD-2026-000017
```

Le numéro est unique en base de données.

## 8.5. Conservation des données produit

Les lignes de commande enregistrent une copie de plusieurs informations du produit :

```text
product_reference
product_name
unit_price
```

Cela permet de conserver les informations commerciales utilisées au moment de la commande même si le produit est ensuite modifié.

---

# 9. Paiement

Le projet ne contient actuellement **aucune intégration réelle à un prestataire de paiement**.

Le parcours de commande prévoit néanmoins une étape correspondant au paiement.

Le fonctionnement actuel est donc une **simulation de paiement** destinée à permettre de tester le parcours utilisateur.

Les services tels que Stripe ou PayPal ne sont pas intégrés au backend dans la version actuelle.

Pour une mise en production avec paiement réel, il serait nécessaire d'ajouter :

- un prestataire de paiement ;
- une gestion des identifiants/API sécurisée ;
- la création d'une session de paiement ;
- la vérification serveur du paiement ;
- le traitement des retours et webhooks ;
- une gestion robuste des statuts de paiement.

---

# 10. Système de dons

## 10.1. Parcours

Le don est réalisé en plusieurs étapes :

```text
/don
   ↓
sélection du montant
   ↓
/don/checkout
   ↓
saisie des informations
   ↓
/don/send
   ↓
enregistrement en base
```

Le montant sélectionné est temporairement conservé dans la session.

## 10.2. Validation

Le montant doit :

- être présent ;
- être numérique ;
- être strictement supérieur à zéro.

Les informations du donateur sont ensuite validées avant l'enregistrement.

## 10.3. Enregistrement transactionnel

Comme pour les commandes, le numéro de don est généré à partir de l'identifiant technique.

Format :

```text
DON-AAAA-XXXXXX
```

Exemple :

```text
DON-2026-000015
```

Le numéro est unique.

## 10.4. Restriction administrateur

Un compte administrateur ne peut pas effectuer de don.

Cette restriction est contrôlée côté serveur dans `DonationController`, et pas uniquement via l'interface JavaScript.

---

# 11. Restriction des commandes administrateur

Les comptes administrateurs ne doivent pas pouvoir utiliser le parcours commercial destiné aux utilisateurs.

La restriction est appliquée côté serveur lors du traitement du panier.

Cette vérification est importante car une simple désactivation du bouton côté frontend ne constitue pas une protection suffisante.

Le frontend peut informer l'administrateur que l'action est impossible, mais le backend reste l'autorité finale.

---

# 12. Gestion des contacts

Les messages envoyés depuis le formulaire de contact sont enregistrés dans :

```text
contact_messages
```

Un message contient :

- nom ;
- prénom ;
- e-mail ;
- téléphone facultatif ;
- message ;
- statut ;
- date de création.

Les statuts permettent de suivre le traitement du message.

L'administration permet :

- de consulter la liste ;
- d'afficher le détail ;
- de modifier le statut.

---

# 13. Espace utilisateur

L'espace utilisateur est accessible après authentification.

La page de compte permet notamment :

- d'afficher les informations du compte ;
- de consulter les commandes ;
- de consulter les dons ;
- de modifier le mot de passe.

Les commandes et les dons sont récupérés à partir de l'identifiant de l'utilisateur connecté.

---

# 14. Espace d'administration

## 14.1. Tableau de bord

Le `AdminHomeController` gère le tableau de bord administratif.

L'administration est séparée de l'espace public avec un header spécifique :

```text
header_admin.php
```

## 14.2. Produits

Contrôleur :

```text
AdminProductController
```

Fonctions :

- lecture ;
- création ;
- modification ;
- suppression ;
- gestion des images ;
- gestion de la disponibilité.

## 14.3. Catégories

Contrôleur :

```text
AdminCategoryController
```

Fonctions :

- lecture ;
- création ;
- modification ;
- suppression.

Avant la suppression d'une catégorie, le contrôleur vérifie l'existence éventuelle de produits associés.

## 14.4. Messages

Contrôleur :

```text
AdminContactController
```

Fonctions :

- liste des messages ;
- détail ;
- modification du statut.

## 14.5. Commandes

Contrôleur :

```text
AdminOrderController
```

Fonctions :

- liste ;
- détail ;
- modification du statut.

## 14.6. Dons

Contrôleur :

```text
AdminDonationController
```

Fonctions :

- liste ;
- détail ;
- modification du statut.

---

# 15. Base de données

## 15.1. Tables

La base contient les tables principales suivantes :

```text
users
products_categories
products
orders
order_items
donations
contact_messages
```

## 15.2. Relations

### Utilisateurs

Un utilisateur peut posséder :

- plusieurs commandes ;
- plusieurs dons.

```text
users
  │
  ├──< orders
  │
  └──< donations
```

### Catégories

Une catégorie peut contenir plusieurs produits :

```text
products_categories
        │
        └──< products
```

### Commandes

Une commande possède plusieurs lignes :

```text
orders
  │
  └──< order_items
```

Une ligne de commande fait référence à un produit :

```text
products
  │
  └──< order_items
```

## 15.3. Clés étrangères

Les contraintes suivantes sont présentes :

```text
donations.user_id
    → users.id

orders.user_id
    → users.id

order_items.order_id
    → orders.id

order_items.product_id
    → products.id

products.category_id
    → products_categories.id
```

La suppression d'une commande entraîne la suppression de ses lignes grâce à :

```sql
ON DELETE CASCADE
```

sur :

```text
order_items.order_id
```

## 15.4. Index

Des index sont présents notamment sur :

- les identifiants ;
- les clés étrangères ;
- les statuts ;
- les dates ;
- les références uniques ;
- les numéros de commande ;
- les numéros de don ;
- les adresses e-mail ;
- l'ordre d'affichage des catégories.

## 15.5. Contraintes d'unicité

Sont notamment uniques :

```text
users.email
products.reference
orders.order_number
donations.donation_number
products_categories.display_order
```

---

# 16. Accès aux données

L'accès à la base est effectué avec PDO.

Les requêtes utilisant des données utilisateur utilisent des requêtes préparées avec des paramètres nommés.

Exemple :

```php
$stmt = $this->db->prepare("
    SELECT *
    FROM users
    WHERE email = :email
");

$stmt->execute([
    'email' => $email
]);
```

Cette approche permet notamment de limiter les risques d'injection SQL.

---

# 17. Sécurité

## 17.1. Protection CSRF

Les formulaires sensibles utilisent la classe :

```text
CsrfProtect
```

Les jetons sont :

- générés côté serveur ;
- stockés dans la session ;
- associés à un formulaire ;
- vérifiés avec `hash_equals()` ;
- supprimés après validation réussie.

Les principaux formulaires utilisent des jetons distincts, par exemple :

```text
connexion
register
contact
cart
donation
```

ainsi que les formulaires d'administration.

## 17.2. Mots de passe

Les mots de passe sont stockés sous forme de hash avec :

```php
password_hash($password, PASSWORD_DEFAULT)
```

La vérification est réalisée avec :

```php
password_verify()
```

Le mot de passe en clair n'est pas conservé dans la session.

## 17.3. Protection contre la fixation de session

Après une connexion réussie :

```php
session_regenerate_id(true);
```

est exécuté.

Cela renouvelle l'identifiant de session après authentification.

## 17.4. Contrôle des accès

L'accès aux fonctionnalités protégées est contrôlé côté serveur.

La présence d'une session authentifiée est vérifiée pour les pages nécessitant un compte.

Les fonctionnalités administratives disposent en plus d'un contrôle du rôle.

## 17.5. Validation des données

`FormValidator` centralise notamment la validation :

- des noms ;
- prénoms ;
- e-mails ;
- téléphones ;
- adresses ;
- codes postaux ;
- villes ;
- messages.

Le numéro de téléphone français peut être normalisé avant validation et enregistrement.

## 17.6. Échappement HTML

Les données réinjectées dans les vues sont échappées avec :

```php
htmlspecialchars()
```

afin de limiter les risques d'injection HTML/JavaScript lors de l'affichage de données issues de la base ou des formulaires.

## 17.7. Fichiers sensibles

Les répertoires suivants sont protégés par `.htaccess` :

```text
config/
logs/
src/
```

Ils ne doivent pas être accessibles directement depuis le web.

---

# 18. Gestion des erreurs

Les contrôleurs disposent de mécanismes de gestion des exceptions.

Les exceptions sont transmises au système de journalisation :

```text
Logger
```

et enregistrées dans :

```text
logs/app_errors.log
```

Le comportement dépend de :

```php
DEV_MODE
```

dans `config/app.php`.

```php
define('DEV_MODE', false);
```

### Mode développement

Lorsque `DEV_MODE` est activé, les erreurs peuvent être affichées directement afin de faciliter le développement et le débogage.

### Mode production

Lorsque `DEV_MODE` est désactivé, les exceptions sont journalisées et l'utilisateur est redirigé vers une page adaptée au contexte.

**Il est recommandé de conserver `DEV_MODE` à `false` en production.**

---

# 19. Configuration

## 19.1. `config/app.php`

Ce fichier définit notamment :

```text
DEV_MODE
ROOT_PATH
PUBLIC_PATH
LOG_PATH
UPLOAD_PRODUCTS_DIR
BASE_URL
UPLOAD_PRODUCTS_URL
```

`BASE_URL` est construite automatiquement à partir du protocole, du domaine et du chemin du script exécuté.

Cela permet au projet de fonctionner aussi bien dans un environnement local que dans une installation où le chemin public est différent.

## 19.2. `config/database.php`

Les paramètres de connexion sont définis dans :

```text
config/database.php
```

Configuration locale de référence :

```text
Host     : localhost
Port     : 3306
Database : db_parc_des_anes
User     : root
Password : ""
```

Ces valeurs doivent être adaptées à l'environnement utilisé.

**Ne pas conserver de véritables identifiants de production dans un dépôt public.**

---

# 20. Installation en environnement local

## 20.1. Prérequis

Installer un environnement comprenant :

- Apache ;
- PHP 8.2 ou version compatible ;
- MariaDB ou MySQL ;
- phpMyAdmin, facultatif mais pratique pour l'import de la base.

Un environnement tel que XAMPP peut être utilisé.

## 20.2. Installation des fichiers

Copier le projet dans le répertoire servi par Apache.

Exemple avec XAMPP :

```text
htdocs/PARC_DES_ANES/
```

Le dossier :

```text
public/
```

est le répertoire destiné à être exposé au serveur web.

## 20.3. Création de la base

Créer une base nommée :

```text
db_parc_des_anes
```

avec un encodage compatible `utf8mb4`.

## 20.4. Import du dump

Importer :

```text
db_parc_des_anes.sql
```

dans la base.

Le dump contient :

- la structure ;
- les index ;
- les clés étrangères ;
- les données de démonstration.

## 20.5. Configuration de la connexion

Modifier :

```text
config/database.php
```

avec les paramètres correspondant à l'environnement local.

## 20.6. Démarrage

Démarrer :

```text
Apache
MariaDB
```

puis accéder à l'application via l'URL correspondant à l'installation.

Le routage nécessite que le module Apache `mod_rewrite` soit actif.

---

# 21. Déploiement

## 21.1. Répertoire public

Le projet est conçu pour pouvoir fonctionner avec un répertoire `public` séparé.

La configuration détecte automatiquement la présence de ce répertoire pour déterminer le chemin public.

Lors d'un déploiement, il est recommandé que seul le contenu destiné à être exposé soit directement accessible depuis le serveur web.

## 21.2. Vérifications avant mise en ligne

Avant le déploiement :

- vérifier la configuration de la base ;
- vérifier les droits d'écriture du répertoire `uploads/products` ;
- vérifier les droits d'écriture du répertoire `logs` ;
- désactiver le mode développement ;
- vérifier le fonctionnement des règles `.htaccess` ;
- tester l'authentification ;
- tester les droits administrateur ;
- tester le panier ;
- tester la création de commande ;
- tester les dons ;
- tester les formulaires ;
- tester la carte ;
- vérifier les ressources externes.

## 21.3. Données de démonstration

Le fichier SQL contient des données de démonstration pour :

- les utilisateurs ;
- les produits ;
- les catégories ;
- les commandes ;
- les dons ;
- les messages de contact.

Ces données doivent être remplacées ou supprimées dans un environnement de production réel.

---

# 22. Frontend

## 22.1. Organisation CSS

Les feuilles de style sont organisées par responsabilité :

```text
styles/
├── foundations/
├── layout/
├── components/
├── pages/
├── utilities/
└── main.css
```

### Foundations

Contient notamment :

- normalisation ;
- variables CSS ;
- styles de base.

### Layout

Gère les structures globales :

- header ;
- footer ;
- contenu ;
- layout général.

### Components

Contient les styles de composants réutilisables :

- boutons ;
- hero ;
- logo.

### Pages

Contient les styles spécifiques aux différentes pages.

### Utilities

Contient les classes utilitaires.

## 22.2. JavaScript

Les scripts sont séparés par fonctionnalité :

```text
scripts/
├── account.js
├── admin_categories.js
├── admin_contacts.js
├── admin_donations.js
├── admin_orders.js
├── admin_products.js
├── burger.js
├── cart_form.js
├── cart_products.js
├── cart_submit.js
├── contact_form.js
├── contact_map.js
├── donation_checkout_form.js
├── donation_form.js
├── form_validator.js
├── header.js
├── shop.js
└── utilities.js
```

Cette organisation permet de charger uniquement les scripts nécessaires aux pages concernées.

## 22.3. Validation frontend

Le JavaScript fournit une première validation et améliore l'expérience utilisateur.

Cette validation ne remplace pas la validation backend.

Toute donnée envoyée au serveur doit être considérée comme potentiellement modifiable par le client.

---

# 23. Carte interactive

La page de contact utilise :

```text
contact_map.js
```

et la bibliothèque :

```text
Leaflet 1.9.4
```

Les tuiles cartographiques utilisent OpenStreetMap.

La carte est initialisée côté navigateur et comporte notamment :

- une position géographique ;
- un marqueur ;
- une navigation accessible au clavier ;
- une attribution OpenStreetMap.

La disponibilité de la carte dépend du chargement des ressources externes.

---

# 24. API interne des produits

Une route dédiée est disponible :

```text
api/products
```

Elle est traitée par :

```text
ApiProductController
```

Elle permet au frontend de récupérer les données produits nécessaires à certaines interactions de la boutique.

Le modèle utilisé est :

```text
ProductModel
```

Les produits actifs sont sélectionnés avec :

```text
is_available = 1
```

---

# 25. Maintenance du code

## 25.1. Ajouter une nouvelle page

Pour ajouter une page :

1. créer le contrôleur correspondant ;
2. ajouter l'action ;
3. créer les vues nécessaires ;
4. ajouter la route dans `Router.php` ;
5. ajouter les styles nécessaires ;
6. ajouter les scripts nécessaires ;
7. ajouter les métadonnées correspondantes si nécessaire.

## 25.2. Ajouter une fonctionnalité nécessitant une base de données

Il est recommandé de suivre le découpage :

```text
Controller
    ↓
Model
    ↓
Database
```

Le contrôleur doit gérer la logique de traitement de la requête tandis que le modèle assure l'accès aux données.

## 25.3. Ajouter une table

Lorsqu'une table est ajoutée :

- définir une clé primaire ;
- définir les index nécessaires ;
- définir les clés étrangères lorsque cela est pertinent ;
- documenter les relations ;
- mettre à jour le dump SQL ;
- adapter ou créer le modèle correspondant.

## 25.4. Ajouter une fonctionnalité d'administration

Une fonctionnalité d'administration doit notamment prévoir :

- un contrôleur spécialisé dans `Controllers/Admin` ;
- les routes correspondantes ;
- les vues dans `Views/admin` ;
- les contrôles d'accès ;
- les jetons CSRF pour les actions sensibles ;
- les validations backend ;
- les scripts et styles nécessaires.

---

# 26. Tests et vérifications

## 26.1. Authentification

Vérifier :

- inscription ;
- connexion avec identifiants valides ;
- refus d'identifiants invalides ;
- déconnexion ;
- accès à une page protégée sans connexion ;
- redirection après connexion ;
- modification du mot de passe.

## 26.2. Autorisations

Avec un compte utilisateur :

- vérifier l'accès à l'espace personnel ;
- vérifier l'impossibilité d'accéder aux fonctionnalités d'administration.

Avec un compte administrateur :

- vérifier l'accès au tableau de bord ;
- vérifier la gestion des produits ;
- vérifier la gestion des catégories ;
- vérifier les commandes ;
- vérifier les dons ;
- vérifier les messages.

Vérifier également qu'un administrateur ne peut pas créer de commande ou de don.

## 26.3. Boutique

Tester :

- affichage des produits disponibles ;
- filtrage par catégorie ;
- ajout au panier ;
- modification des quantités ;
- suppression d'un produit ;
- validation du panier ;
- création d'une commande.

## 26.4. Dons

Tester :

- montant invalide ;
- montant nul ou négatif ;
- formulaire incomplet ;
- téléphone invalide ;
- enregistrement d'un don ;
- consultation du don dans l'administration ;
- consultation de l'historique utilisateur.

## 26.5. Sécurité

Tester notamment :

- absence de jeton CSRF ;
- jeton CSRF incorrect ;
- accès direct aux routes protégées ;
- tentative d'accès à une fonctionnalité admin avec un compte utilisateur ;
- saisie de données HTML dans les formulaires ;
- saisies SQL particulières ;
- upload de fichiers non autorisés.

---

# 27. Dépannage

## 27.1. Erreur de connexion à la base

Vérifier :

```text
config/database.php
```

et contrôler :

- serveur MariaDB/MySQL démarré ;
- nom de la base ;
- utilisateur ;
- mot de passe ;
- port.

## 27.2. Les URLs ne fonctionnent pas

Vérifier :

- activation de `mod_rewrite` ;
- prise en compte du `.htaccess` ;
- configuration `AllowOverride` d'Apache ;
- présence de `public/.htaccess`.

## 27.3. Les images produits ne s'affichent pas

Vérifier :

```text
public/uploads/products/
```

et :

- l'existence du fichier ;
- le nom enregistré en base ;
- les permissions du répertoire ;
- la valeur de `UPLOAD_PRODUCTS_DIR` ;
- la valeur de `UPLOAD_PRODUCTS_URL`.

## 27.4. Les erreurs ne sont pas affichées

Vérifier :

```php
DEV_MODE
```

dans :

```text
config/app.php
```

En production, l'absence d'affichage détaillé des erreurs est volontaire.

Consulter :

```text
logs/app_errors.log
```

pour rechercher les exceptions enregistrées.

## 27.5. La carte ne s'affiche pas

Vérifier :

- le chargement de Leaflet ;
- la connexion permettant de charger les ressources externes ;
- la présence du conteneur HTML de la carte ;
- les erreurs JavaScript dans la console du navigateur.

---

# 28. Arborescence fonctionnelle des contrôleurs

```text
Controllers/
│
├── BaseController
│
├── Home/
│   └── HomeController
│
├── Auth/
│   └── AuthController
│
├── User/
│   └── UserController
│
├── Donkeys/
│   └── DonkeysController
│
├── Contact/
│   └── ContactController
│
├── Shop/
│   └── ProductController
│
├── Cart/
│   └── CartController
│
├── Donation/
│   └── DonationController
│
├── Legal/
│   └── LegalController
│
├── Api/
│   └── ApiProductController
│
├── System/
│   └── UnderConstructController
│
└── Admin/
    ├── AdminHomeController
    ├── AdminProductController
    ├── AdminCategoryController
    ├── AdminContactController
    ├── AdminOrderController
    └── AdminDonationController
```

---

# 29. Arborescence des modèles

```text
Models/
│
├── UserModel.php
├── ProductModel.php
├── ProductCategoryModel.php
├── OrderModel.php
├── DonationModel.php
└── ContactModel.php
```

Les modèles correspondent directement aux principales entités persistées en base.

---

# 30. Principes de développement utilisés

Le projet repose notamment sur les principes suivants :

### Séparation des responsabilités

Les responsabilités sont réparties entre :

```text
Router
   ↓
Controller
   ↓
Model
   ↓
Database
```

et :

```text
Controller
   ↓
View
```

### Centralisation

Les fonctions transversales sont regroupées dans `Core`.

Cela concerne notamment :

- connexion BDD ;
- validation ;
- CSRF ;
- journalisation ;
- métadonnées ;
- autoloading.

### Réutilisation

Les éléments communs de l'interface sont regroupés dans :

```text
Views/partials/
Views/layouts/
```

Les pages complexes sont découpées en sections.

### Défense en profondeur

Les fonctionnalités sensibles combinent plusieurs niveaux de contrôle :

- validation frontend ;
- validation backend ;
- contrôle de session ;
- contrôle de rôle ;
- protection CSRF ;
- requêtes préparées ;
- échappement HTML.

---

# 31. Limites actuelles et évolutions possibles

La version actuelle constitue une application fonctionnelle mais plusieurs évolutions sont envisageables.

## Paiement réel

Remplacer la simulation par l'intégration d'un prestataire de paiement avec validation serveur et webhooks.

## Gestion avancée des permissions

Le système actuel repose essentiellement sur les rôles :

```text
user
admin
```

Une évolution pourrait introduire des permissions plus fines.

## Gestion du stock

Le modèle produit contient actuellement la disponibilité :

```text
is_available
```

mais pas de gestion quantitative du stock.

Une future version pourrait introduire :

```text
stock_quantity
```

et une gestion des décréments lors des commandes.

## Gestion des emails

Une future évolution pourrait intégrer l'envoi automatique :

- de confirmation de commande ;
- de confirmation de don ;
- de notification administrative ;
- de récupération de mot de passe.

## Tests automatisés

Le projet pourrait être complété par une suite de tests automatisés backend et frontend.

---

# 32. Fichiers importants

| Fichier | Rôle |
|---|---|
| `public/index.php` | Point d'entrée HTTP |
| `src/Core/Router.php` | Routage |
| `src/Core/DatabaseConnect.php` | Connexion BDD |
| `src/Core/CsrfProtect.php` | Protection CSRF |
| `src/Core/FormValidator.php` | Validation des formulaires |
| `src/Core/Logger.php` | Journalisation |
| `src/Controllers/BaseController.php` | Fonctions communes des contrôleurs |
| `src/Models/UserModel.php` | Utilisateurs |
| `src/Models/ProductModel.php` | Produits |
| `src/Models/ProductCategoryModel.php` | Catégories |
| `src/Models/OrderModel.php` | Commandes |
| `src/Models/DonationModel.php` | Dons |
| `src/Models/ContactModel.php` | Messages |
| `config/app.php` | Configuration générale |
| `config/database.php` | Configuration BDD |
| `config/meta.php` | Métadonnées |
| `db_parc_des_anes.sql` | Structure et données de la BDD |

---

# 33. Résumé de l'architecture

Le fonctionnement général peut être résumé ainsi :

```text
                  Navigateur
                      │
                      ▼
              public/index.php
                      │
                      ▼
                   Router
                      │
          ┌───────────┴───────────┐
          ▼                       ▼
     Controller                API Controller
          │
     ┌────┴────┐
     ▼         ▼
   Model      View
     │         │
     ▼         ▼
  MariaDB   HTML/CSS/JS
```

Pour une fonctionnalité nécessitant une modification des données :

```text
Requête HTTP
     │
     ▼
   Router
     │
     ▼
 Controller
     │
 ┌───┴───────────────┐
 ▼                   ▼
Validation       Contrôle accès
 │
 ▼
 Model
 │
 ▼
 PDO
 │
 ▼
MariaDB
```

Pour l'affichage :

```text
MariaDB
   │
   ▼
 Model
   │
   ▼
Controller
   │
   ▼
View
   │
   ▼
HTML
   │
   ├── CSS
   └── JavaScript
```

---

# 34. Licence et crédits

Projet réalisé dans le cadre du développement du site **Parc des Anes**.

Les ressources graphiques, images, polices et éléments externes utilisés dans le projet doivent être considérés selon leurs propres conditions d'utilisation.

La carte interactive utilise :

- Leaflet ;
- OpenStreetMap.

Les fichiers de police fournis dans le projet sont accompagnés de leurs informations de licence dans les répertoires correspondants.

---
