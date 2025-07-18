## Refonte de la Logique de Connexion et Ajout de la Fonctionnalité de Changement de Mot de Passe

Ce document détaille les modifications apportées au module `Auth` pour implémenter la détection de la première connexion et la fonctionnalité de changement de mot de passe, conformément aux exigences.

### 1. Mise à Jour du Comportement de Connexion (Détection de la Première Connexion)

La méthode `login` dans `AuthController.php` a été modifiée pour détecter si un utilisateur se connecte pour la première fois. Si `created_at` et `updated_at` sont égaux pour un utilisateur, cela indique une première connexion, et l'accès est bloqué avec une demande de changement de mot de passe.

**Logique Implémentée:**

*   **Authentification:** L'utilisateur est authentifié via ses identifiants (email/mot de passe) en utilisant une nouvelle méthode `attemptLogin` dans `AuthService`.
*   **Détection de Première Connexion:** Après une authentification réussie, le contrôleur vérifie si `$user->created_at->equalTo($user->updated_at)`.
*   **Réponse en Cas de Première Connexion:**
    *   Si c'est la première connexion, aucun token n'est généré.
    *   Une réponse JSON avec le statut `error`, un message clair (`First login detected. Please change your password before proceeding.`), et le code `403` (Forbidden) est retournée.
    *   L'événement est logué (`Log::info('First login detected for user ...')`).
*   **Connexion Normale:**
    *   Si ce n'est pas la première connexion, le processus de génération et de retour du token se déroule normalement.
    *   La réponse JSON suit le format standard (`status: success`, `message: Login successful`, `data: {token: ...}`, `code: 200`).

**Modifications Clés:**

*   **`Modules/Auth/app/Http/Controllers/AuthController.php`:**
    *   Modification de la méthode `login` pour inclure la logique de détection de première connexion et la gestion des réponses conditionnelles.
*   **`Modules/Auth/app/Services/AuthService.php`:**
    *   Ajout de la méthode `attemptLogin(array $credentials): User` qui authentifie l'utilisateur et retourne l'objet `User` ou lève une `AuthenticationException`.
    *   La méthode `login` existante utilise désormais `attemptLogin` pour obtenir l'utilisateur avant de générer le token.

```bash
git add Modules/Auth/app/Http/Controllers/AuthController.php
git add Modules/Auth/app/Services/AuthService.php
git commit -m "feat(auth): Implémenter la détection de première connexion dans la logique de login"
```

### 2. Ajout de la Fonctionnalité de Changement de Mot de Passe

Une nouvelle fonctionnalité de changement de mot de passe a été ajoutée au module `Auth`, avec un endpoint sécurisé et une logique de validation robuste.

**Composants Implémentés:**

*   **`changePassword` method in `AuthController`:**
    *   Gère la requête HTTP pour le changement de mot de passe.
    *   Utilise `ChangePasswordRequest` pour la validation.
    *   Appelle la méthode `changePassword` du `AuthService`.
    *   Gère les réponses de succès (200 OK) et d'échec (400 Bad Request, 401 Unauthorized, 500 Internal Server Error) avec le format JSON standardisé.
    *   Logue les opérations (validation, succès, échec).

*   **`Modules/Auth/app/Http/Requests/ChangePasswordRequest.php`:**
    *   Nouvelle classe de requête pour valider les données du changement de mot de passe:
        *   `email`: requis, email valide, doit exister dans la table `users`.
        *   `old_password`: requis, chaîne de caractères.
        *   `new_password`: requis, chaîne de caractères, minimum 8 caractères, confirmé (doit correspondre à `new_password_confirmation`).

*   **`changePassword` method in `AuthService`:**
    *   Reçoit l'email, l'ancien mot de passe et le nouveau mot de passe.
    *   Vérifie l'existence de l'utilisateur par email.
    *   Vérifie que l'`old_password` correspond au mot de passe actuel de l'utilisateur en utilisant `Hash::check`.
    *   Met à jour le mot de passe de l'utilisateur avec `Hash::make(new_password)`.
    *   Logue toutes les étapes (début, succès, échec).
    *   Lève des `AuthenticationException` pour les cas d'utilisateur non trouvé ou d'ancien mot de passe invalide.

**Modifications Clés:**

*   **`Modules/Auth/app/Http/Controllers/AuthController.php`:**
    *   Ajout de la méthode `changePassword`.
*   **`Modules/Auth/app/Http/Requests/ChangePasswordRequest.php`:**
    *   Création de la classe de requête.
*   **`Modules/Auth/app/Services/AuthService.php`:**
    *   Ajout de la méthode `changePassword`.

```bash
git add Modules/Auth/app/Http/Controllers/AuthController.php
git add Modules/Auth/app/Http/Requests/ChangePasswordRequest.php
git add Modules/Auth/app/Services/AuthService.php
git commit -m "feat(auth): Ajouter la fonctionnalité de changement de mot de passe"
```

### 3. Respect des Standards Requis

*   **Séparation des Préoccupations:** La logique métier est strictement maintenue dans `AuthService`, tandis que `AuthController` gère les requêtes et les réponses HTTP.
*   **Validation, Hachage et Logging:** Laravel's built-in fonctionnalités de validation, de hachage (`Hash::make`, `Hash::check`) et de logging (`Log::info`, `Log::warning`, `Log::error`) sont utilisées de manière cohérente.
*   **Format de Réponse JSON Standardisé:** Toutes les réponses suivent le format JSON spécifié (`status`, `message`, `errors`, `data`, `code`).
*   **Structure Modulaire:** Toutes les modifications sont effectuées dans le respect de la structure modulaire du projet.

