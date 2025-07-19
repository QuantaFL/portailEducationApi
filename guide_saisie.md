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

Cette fonctionnalité permet de soumettre les notes d'examen et de devoir pour un étudiant, une matière, une classe et une période données.

- **Méthode HTTP :** `POST`
- **URL :** `/api/v1/notes`

### Modifications de la structure des notes

La colonne `value` a été remplacée par deux nouvelles colonnes :
- `note_exam` (float, nullable)
- `note_devoir` (float, nullable)

### Corps de la Requête (JSON)

Le corps de la requête doit contenir les champs suivants :

```json
{
    "etudiant_id": 1,
    "subject_id": 1,
    "class_id": 1,
    "period": "Trimestre 1",
    "teacher_id": 1,
    "note_exam": 15.5,
    "note_devoir": 12
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
        "teacher_id": 1,
        "note_exam": 15.5,
        "note_devoir": 12,
        "id": 123
    },
    "code": 201
}
```

### Cas d'Erreur

- **Validation (Code 400) :** Si des champs sont invalides.

  ```json
  {
      "status": "error",
      "message": "Validation failed.",
      "errors": {
          "note_exam": [
              "Le champ note_exam doit être un nombre."
          ]
      },
      "code": 400
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

### Logique de calcul

Le service `GradeCalculationService` a été modifié pour calculer la moyenne par matière en utilisant `note_exam` et `note_devoir` avec une pondération (2 pour l'examen, 1 pour le devoir).

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
        "general_average": 14.33,
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

### Fonctionnement

La génération du PDF est gérée par le service `ReportCardGenerationService` qui utilise une vue Blade (`Modules/Teacher/resources/views/bulletin.blade.php`) pour structurer le document.

### Exemple de Réponse en Cas de Succès (Code 200)

La réponse contient l'URL d'accès au fichier PDF généré.

```json
{
    "status": "success",
    "message": "Report card PDF generated.",
    "data": {
        "path": "http://localhost/storage/report_cards/bulletin_etudiant_1_Trimestre_1.pdf"
    },
    "code": 200
}
```

Le fichier PDF sera disponible dans le dossier `storage/app/public/report_cards` de votre application Laravel. Pour y accéder via un navigateur, assurez-vous d'avoir créé un lien symbolique avec `php artisan storage:link`.

## Commandes Git

Voici les commandes Git utilisées pour cette mise à jour :

```bash
git add .
git commit -m "feat(teacher): update notes entity and implement PDF bulletin generation"
```

## 🛠️ Bonus : Conseils de Débogage

- **Vérifiez le Token :** Une erreur `401 Unauthorized` signifie que votre token est manquant, invalide ou expiré.
- **Logs Laravel :** Consultez les logs dans `storage/logs/laravel.log` pour des messages d'erreur détaillés côté serveur.
- **Console Postman :** Utilisez la console de Postman (accessible via `View > Show Postman Console`) pour inspecter les requêtes et les réponses brutes, y compris les en-têtes.
- **Erreurs de Validation :** Une réponse `400` ou `422` indique généralement un problème avec les données que vous envoyez. Vérifiez que tous les champs requis sont présents et que leurs types de données sont corrects.

##
foreach ($notes->groupBy(fn($note) => $note->subject->name) as $subjectName => $subjectNotes) {
// Moyenne simple des notes
$average = $subjectNotes->avg(fn($note) => ($note->note_devoir + $note->note_exam) / 2);

    // Récupération du coefficient de la matière via la relation
    $coefficient = $subjectNotes->first()->subject->coefficient ?? 1;

    // Construction du tableau final
    $subjectAverages[$subjectName] = [
        'coefficient' => $coefficient,
        'average' => $average,
    ];
}
Étape par étape :
$notes->groupBy(fn($note) => $note->subject->name)

$notes est une collection d’objets Note.

Cette ligne regroupe les notes par nom de matière (subject->name).

groupBy crée un tableau où chaque clé est le nom d’une matière, et la valeur est une collection de toutes les notes appartenant à cette matière.

foreach (... as $subjectName => $subjectNotes)

Parcourt chaque groupe.

$subjectName contient le nom de la matière.

$subjectNotes est une collection de notes pour cette matière.

Calcul de la moyenne des notes pour la matière


$average = $subjectNotes->avg(fn($note) => ($note->note_devoir + $note->note_exam) / 2);
Pour chaque note de la matière, on calcule la moyenne entre la note du devoir et celle de l'examen.

avg() calcule la moyenne de ces valeurs pour toutes les notes de cette matière.

Récupération du coefficient


$coefficient = $subjectNotes->first()->subject->coefficient ?? 1;
On prend la première note de la collection (c’est suffisant car toutes ont la même matière).

On récupère le coefficient de la matière via la relation subject.

Si le coefficient est null, on met une valeur par défaut 1.

Construction du tableau

php
Copier
Modifier
$subjectAverages[$subjectName] = [
'coefficient' => $coefficient,
'average' => $average,
];
On crée un tableau associatif où la clé est le nom de la matière.

Pour chaque matière, on stocke :

Son coefficient,

La moyenne calculée des notes.

En résumé
Ce code prend toutes les notes d’un étudiant, les regroupe par matière, calcule la moyenne des notes pour chaque matière, récupère le coefficient de chaque matière, et construit un tableau final avec ces données.

Ce tableau ($subjectAverages) peut ensuite être utilisé pour afficher les résultats ou calculer la moyenne générale.

