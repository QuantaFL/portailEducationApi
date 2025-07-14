# Module Sujet

Ce module gère les opérations CRUD (Créer, Lire, Mettre à jour, Supprimer) pour les sujets (matières) dans l'application.
Il assure la validation des données, la gestion des erreurs et la journalisation des actions.

## Endpoints exposés

| Méthode | Endpoint                 | Description                                   | Statuts HTTP possibles |
|---------|--------------------------|-----------------------------------------------|------------------------|
| `GET`   | `/api/subjects`          | Récupère la liste de tous les sujets.         | 200, 500               |
| `POST`  | `/api/subjects`          | Crée un nouveau sujet.                        | 201, 409, 422, 500     |
| `GET`   | `/api/subjects/{id}`     | Récupère un sujet par son ID.                 | 200, 404, 500          |
| `PUT`   | `/api/subjects/{id}`     | Met à jour un sujet existant.                 | 200, 404, 409, 422, 500|
| `DELETE`| `/api/subjects/{id}`     | Supprime un sujet.                            | 200, 404, 500          |

## Exemple de JSON pour l'ajout d'une matière

```json
{
  "name": "Mathématiques",
  "coefficient": 3.5,
  "level": "Lycée"
}
```

## Statuts HTTP possibles

- `200 OK`: La requête a réussi.
- `201 Created`: La ressource a été créée avec succès (pour les requêtes POST).
- `404 Not Found`: La ressource demandée n'a pas été trouvée.
- `409 Conflict`: Conflit de données, par exemple, un sujet avec le même nom existe déjà.
- `422 Unprocessable Entity`: La validation des données a échoué.
- `500 Internal Server Error`: Une erreur inattendue est survenue sur le serveur.
