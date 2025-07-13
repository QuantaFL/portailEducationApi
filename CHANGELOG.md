 utilisation du package  : nwidart/laravel-modules pour créer des modules
 php artisan module:make Auth:  commande pour créer des modules
 "Modules\\": "Modules/" dit à Composer que tous les modules sont dans le dossier Modules/.

 "Modules\\Auth\\": "Modules/Auth/app/" précise que les classes du module Auth sont dans Modules/Auth/app/.

 Cela permet de garder une structure propre (app/) tout en respectant PSR-4 pour l'autoload.
le faire pour chaque module 
puis faire un composer dump-autoload -o : pour recharger l'auto-load 
// à la racine 
php artisan module:make-model Role Auth --migration
php artisan module:make-controller RoleController Auth --api

Pour migrer uniquement un module, utilise la commande :


php artisan module:migrate Auth
Cela exécute uniquement les migrations dans le module Auth.

 php artisan migrate run toutes les migrations 

php artisan module:make-request RoleRequest Auth

php artisan module:make-factory Role Auth
php artisan module:make-seed Role Auth
php artisan module:make-factory User Auth
php artisan config:clear

php artisan jwt:secret

php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider" --tag="stubs"







