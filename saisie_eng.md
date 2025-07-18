## Saisie des Notes et Calculs Automatiques

Ce document détaille l'implémentation de la saisie des notes, des calculs automatiques et de la génération de bulletins PDF dans le module `Teacher`, en respectant la structure modulaire existante.

### 🔍 Analyse initiale

*   **Modules existants:** `Teacher`, `Subject`, `TeacherSubjectClass`.
*   **Relations:**
    *   `Teacher` a une relation `belongsTo` avec `User`.
    *   `TeacherSubjectClass` lie `Teacher`, `Subject` et `Class`.
    *   `Etudiant` a une relation `belongsTo` avec `User` et `Class`.
*   **Objectif:** Permettre aux enseignants de saisir des notes pour des étudiants, des matières et des classes spécifiques, puis de calculer des moyennes et générer des bulletins.

### ✅ Fonctionnalités implémentées

#### 1. Saisie des Notes (Grade Entry)

*   **Modèle `Note`:** Création d'un nouveau modèle `Note` dans le module `Teacher` pour stocker les informations de note.
    *   **Champs:** `etudiant_id`, `subject_id`, `teacher_id`, `class_id`, `period`, `value`.
    *   **Relations:** `belongsTo` avec `Etudiant`, `Subject`, `Teacher`, `Classes`.
*   **Migration `create_notes_table`:** Création d'une migration pour la table `notes` avec les champs définis et les clés étrangères appropriées.
*   **Form Request `StoreNoteRequest`:** Création d'une classe de requête pour valider les données de saisie des notes.
    *   **Règles de validation:** `etudiant_id` (requis, existe), `subject_id` (requis, existe), `teacher_id` (requis, existe), `class_id` (requis, existe), `period` (requis, chaîne), `value` (requis, numérique, min:0, max:20).
*   **Service `NoteService`:** Création d'un service pour encapsuler la logique métier de création des notes.
    *   **Méthode `createNote`:** Prend un tableau de données validées, crée une nouvelle note et la retourne. Inclut la journalisation des actions.
*   **Contrôleur `NoteController`:** Création d'un contrôleur pour gérer les requêtes HTTP liées aux notes.
    *   **Méthode `store`:** Utilise `StoreNoteRequest` pour la validation, appelle `NoteService::createNote`, et retourne une réponse JSON standardisée (201 Created en cas de succès, 400 Bad Request pour les erreurs de validation, 500 Internal Server Error pour les autres erreurs).
*   **Route API:** Ajout d'une route POST (`/api/v1/notes`) dans `Modules/Teacher/routes/api.php` pour la saisie des notes.

#### 2. Calculs Automatiques

*   **Modèle `ReportCard`:** Création d'un nouveau modèle `ReportCard` dans le module `Teacher` pour stocker les résultats des calculs de notes.
    *   **Champs:** `etudiant_id`, `class_id`, `period`, `general_average`, `mention`, `rank`, `appreciation`, `subject_averages` (JSON).
    *   **Relations:** `belongsTo` avec `Etudiant`, `Classes`.
*   **Migration `create_report_cards_table`:** Création d'une migration pour la table `report_cards` avec les champs définis et les clés étrangères appropriées.
*   **Service `GradeCalculationService`:** Création d'un service pour gérer la logique de calcul des notes.
    *   **Méthode `calculateSubjectAverages`:** Calcule la moyenne par matière à partir des notes d'un étudiant.
    *   **Méthode `calculateGeneralAverage`:** Calcule la moyenne générale à partir des moyennes par matière.
    *   **Méthode `getMention`:** Détermine la mention (e.g., "Passable", "Bien") basée sur la moyenne générale.
    *   **Méthode `getAppreciation`:** Génère une appréciation textuelle basée sur la moyenne générale.
    *   **Méthode `calculateAndSaveReportCard`:** Orchestre les calculs et sauvegarde les résultats dans le modèle `ReportCard`. Inclut la journalisation.
    *   **Méthode `calculateClassRank`:** Calcule le classement des étudiants au sein d'une classe pour une période donnée.

#### 3. Génération de Bulletins PDF

*   **Service `ReportCardGenerationService`:** Création d'un service pour gérer la génération des bulletins.
    *   **Méthode `generatePdf`:** Prend l'ID d'un bulletin, récupère les données et génère un fichier texte (placeholder pour un PDF réel) avec les informations du bulletin. Le chemin du fichier généré est retourné. Inclut la journalisation.
*   **Contrôleur `ReportCardController`:** Création d'un contrôleur pour gérer les requêtes HTTP liées aux bulletins.
    *   **Méthode `store`:** Prend les `etudiant_id`, `class_id`, `period`, appelle `GradeCalculationService::calculateAndSaveReportCard` pour calculer et sauvegarder le bulletin. Déclenche ensuite le calcul du classement pour la classe. Retourne une réponse JSON standardisée.
    *   **Méthode `show`:** Prend l'ID d'un bulletin, appelle `ReportCardGenerationService::generatePdf` pour générer le bulletin PDF (placeholder). Retourne une réponse JSON standardisée avec le chemin du fichier.
*   **Routes API:** Ajout de routes POST (`/api/v1/report-cards`) pour déclencher le calcul et la sauvegarde des bulletins, et GET (`/api/v1/report-cards/{id}`) pour générer et accéder au bulletin PDF, dans `Modules/Teacher/routes/api.php`.

### 📂 Git Commands

```bash
git add Modules/Teacher/app/Models/Note.php
git commit -m "feat(teacher): Création du modèle Note pour la saisie des notes"
```

```bash
git add Modules/Teacher/database/migrations/*_create_notes_table.php
git commit -m "feat(teacher): Création de la migration pour la table notes avec les champs et clés étrangères"
```

```bash
git add Modules/Teacher/app/Http/Requests/StoreNoteRequest.php
git commit -m "feat(teacher): Création de StoreNoteRequest pour la validation des données de note"
```

```bash
git add Modules/Teacher/app/Services/NoteService.php
git commit -m "feat(teacher): Création de NoteService pour la logique métier de gestion des notes"
```

```bash
git add Modules/Teacher/app/Http/Controllers/NoteController.php
git add Modules/Teacher/routes/api.php
git commit -m "feat(teacher): Implémentation du contrôleur NoteController et ajout de la route pour la saisie des notes"
```

```bash
git add Modules/Teacher/app/Models/ReportCard.php
git add Modules/Teacher/database/migrations/*_create_report_cards_table.php
git commit -m "feat(teacher): Création du modèle ReportCard et de sa migration pour les bulletins de notes"
```

```bash
git add Modules/Teacher/app/Services/GradeCalculationService.php
git commit -m "feat(teacher): Implémentation de GradeCalculationService pour les calculs de moyennes et mentions"
```

```bash
git add Modules/Teacher/app/Services/ReportCardGenerationService.php
git commit -m "feat(teacher): Implémentation de ReportCardGenerationService pour la génération de bulletins PDF"
```

```bash
git add Modules/Teacher/app/Http/Controllers/ReportCardController.php
git add Modules/Teacher/routes/api.php
git commit -m "feat(teacher): Implémentation du contrôleur ReportCardController et ajout des routes pour les bulletins"
```