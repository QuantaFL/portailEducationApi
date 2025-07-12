# Portail Éducation API

Ce projet est une API construite avec Laravel et structurée en modules grâce au package `nwidart/laravel-modules`. Cette approche modulaire permet de développer des fonctionnalités de manière isolée et organisée.

## Installation et Lancement

Suivez ces étapes pour configurer et lancer le projet en environnement de développement.

### Prérequis

- PHP 8.2 ou supérieur
- Composer
- Node.js & NPM

### 1. Cloner le projet


### 2. Installer les dépendances

Installez les dépendances PHP et JavaScript.

```bash
composer install
```

### 3. Configuration de l'environnement

Copiez le fichier d'environnement et générez les clés nécessaires.

```bash
# Créez votre fichier .env
copy .env.example .env

# Générez la clé de l'application
php artisan key:generate

# Générez le secret pour JWT
php artisan jwt:secret
```

N'oubliez pas de configurer les informations de votre base de données dans le fichier `.env`.

### 4. Base de données

Exécutez les migrations pour créer les tables nécessaires.

```bash
php artisan migrate
```

### 5. Lancer le projet

Vous pouvez lancer le serveur de développement  :

```bash
php artisan serve 
```



## Architecture Modulaire avec `nwidart/laravel-modules`

Ce projet utilise une architecture modulaire. Chaque fonctionnalité majeure est isolée dans son propre module, situé dans le dossier `Modules/`.

### Commandes utiles pour les modules

Voici un résumé des commandes `artisan` les plus importantes pour la gestion des modules.

#### Création d'un module

Pour créer un nouveau module (par exemple, `Auth`) :

```bash
php artisan module:make Auth
```

N'oubliez pas d'ajouter le chemin du nouveau module dans la section `autoload.psr-4` de votre fichier `composer.json`, puis de rafraîchir l'autoloader.
exemple ci dessous  : 

```json
 "autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/",
        "Modules\\": "Modules/",
        "Modules\\Auth\\": "Modules/Auth/app/"
    }
},
```

```bash
composer dump-autoload -o
```
NB: a chaque fois que vous créer un nouveau module il faut le faire .
#### Génération de composants dans un module

Remplacez `Auth` par le nom de votre module.

- **Contrôleur API :**
  ```bash
  php artisan module:make-controller RoleController Auth --api
  ```

- **Modèle et sa migration :**
  ```bash
  php artisan module:make-model Role Auth --migration
  ```

- **Request :**
  ```bash
  php artisan module:make-request RoleRequest Auth
  ```

- **Factory & Seeder :**
  ```bash
  php artisan module:make-factory Role Auth
  php artisan module:make-seed Role Auth
  ```

#### Migrations de modules

- **Exécuter les migrations d'un module spécifique :**
  ```bash
  php artisan module:migrate Auth
  ```

- **Exécuter toutes les migrations (Laravel + tous les modules) :**
  ```bash
  php artisan migrate
  ```

#### Personnalisation des stubs

Pour modifier les modèles de fichiers (stubs) utilisés par `nwidart/laravel-modules` :

```bash
php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider" --tag="stubs"
```
Les stubs seront publiés dans le dossier `stubs/nwidart-stubs`.

## Journal des modifications (Changelog)

Ce projet a été initialisé avec Laravel et configuré pour utiliser `nwidart/laravel-modules` afin de favoriser une architecture modulaire.

- **Initialisation :**
  - Installation de `nwidart/laravel-modules`.
  - Création du premier module `Auth`.
  - Configuration de l'autoload PSR-4 pour les modules dans `composer.json`.

- **Développement du module `Auth` :**
  - Création des modèles `Role` et `User` avec leurs migrations et factories.
  - Mise en place des contrôleurs et des requests pour la gestion des rôles.
  - Installation et configuration de `tymon/jwt-auth` pour l'authentification par token.
# nwidart/laravel-modules

nwidart/laravel-modules est un package laravel qui permet de découper l'application en module cela permet une meilleure grannularité 
et facilite la collaboration entre membre car chacun peut travailler sur un ou plusiuers module. chaque module peut être considéré comme un mini projet laravel 

toutes les commandes doivent s'éffectuer depuis la racine car à l'interieur d'un module les commandes artisan ne sont pas reocnnues 
les commandes sont les mêmes dans le fond seulement il faut ajouter le mot "module" et souvent le nom du module ( sauf pour sa création).
celà est illustré dans les commandes ci-dessus : 

pour créer un controller, un model ou une migration la fin de la commande se termine par le nom du module Auth.
