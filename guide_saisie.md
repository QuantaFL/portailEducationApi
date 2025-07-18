
# Guide de Test Postman - Saisie des Notes et Bulletins

Ce guide détaille comment utiliser Postman pour tester les fonctionnalités de saisie de notes, de calcul automatique et de génération de bulletins PDF pour le module `Teacher`.

## Prérequis

Avant de commencer, assurez-vous d'avoir :

- Un client Postman installé.
- Un token d'authentification JWT valide pour un utilisateur ayant le rôle `teacher`.

Dans Postman, configurez vos requêtes avec le header suivant :

```
Authorization: Bearer {votre_token_jwt}
```

## 1. Saisie des Notes

Cette fonctionnalité permet de soumettre une note pour un étudiant, une matière, une classe et une période données.

- **Méthode HTTP :** `POST`
- **URL :** `/api/v1/notes`

### Corps de la Requête (JSON)

Le corps de la requête doit contenir les champs suivants :

```json
{
    "etudiant_id": 1,
    "subject_id": 1,
    "class_id": 1,
    "period": "Trimestre 1",
    "note": 15.5,
    "comment": "Très bon travail"
}
```

### Exemple de Réponse en Cas de Succès (Code 201)

```json
{
    "status": "success",
    "data": {
        "etudiant_id": 1,
        "subject_id": 1,
        "class_id": 1,
        "period": "Trimestre 1",
        "note": 15.5,
        "comment": "Très bon travail",
        "id": 123
    },
    "code": 201
}
```

### Cas d'Erreur

- **Validation (Code 400) :** Si des champs sont manquants ou invalides.

  ```json
  {
      "status": "error",
      "message": "Validation failed.",
      "errors": {
          "note": [
              "Le champ note est obligatoire."
          ]
      },
      "code": 400
  }
  ```

- **Conflit (Code 409) :** Si une note existe déjà pour les mêmes `etudiant_id`, `subject_id`, `class_id` et `period`.

  ```json
  {
      "status": "error",
      "message": "Une note pour cet étudiant dans cette matière existe déjà pour cette période.",
      "code": 409
  }
  ```

## 2. Calculs Automatiques des Bulletins

Cette fonctionnalité déclenche le calcul des moyennes, mentions, rangs et appréciations pour un étudiant donné.

- **Méthode HTTP :** `POST`
- **URL :** `/api/v1/report-cards`

### Corps de la Requête (JSON)

```json
{
    "etudiant_id": 1,
    "class_id": 1,
    "period": "Trimestre 1"
}
```

### Exemple de Réponse en Cas de Succès (Code 201)

La réponse inclut le bulletin de notes calculé.

```json
{
    "status": "success",
    "message": "Report card calculated and saved.",
    "data": {
        "etudiant_id": 1,
        "class_id": 1,
        "period": "Trimestre 1",
        "general_average": 14.75,
        "mention": "Bien",
        "rank": 3,
        "appreciation": "Bon trimestre, continuez vos efforts.",
        "id": 45
    },
    "code": 201
}
```

## 3. Génération de Bulletin PDF

Cette fonctionnalité génère un bulletin de notes en format PDF pour un étudiant.

- **Méthode HTTP :** `GET`
- **URL :** `/api/v1/report-cards/{id}`

Remplacez `{id}` par l'ID du bulletin de notes (obtenu lors du calcul).

### Exemple de Réponse en Cas de Succès (Code 200)

La réponse contient le chemin d'accès au fichier PDF généré.

```json
{
    "status": "success",
    "message": "Report card PDF generated.",
    "data": {
        "path": "storage/report_cards/bulletin_etudiant_1_trimestre_1.pdf"
    },
    "code": 200
}
```

Le fichier PDF sera disponible dans le dossier `storage/app/public/report_cards` de votre application Laravel. Pour y accéder via un navigateur, assurez-vous d'avoir créé un lien symbolique avec `php artisan storage:link`.

## 🛠️ Bonus : Conseils de Débogage

- **Vérifiez le Token :** Une erreur `401 Unauthorized` signifie que votre token est manquant, invalide ou expiré.
- **Logs Laravel :** Consultez les logs dans `storage/logs/laravel.log` pour des messages d'erreur détaillés côté serveur.
- **Console Postman :** Utilisez la console de Postman (accessible via `View > Show Postman Console`) pour inspecter les requêtes et les réponses brutes, y compris les en-têtes.
- **Erreurs de Validation :** Une réponse `400` ou `422` indique généralement un problème avec les données que vous envoyez. Vérifiez que tous les champs requis sont présents et que leurs types de données sont corrects.
