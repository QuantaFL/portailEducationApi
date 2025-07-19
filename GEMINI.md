🔧 Step 1: Update the Notes Entity
Locate the existing notes table (entity/model).

Replace the current value column with two new columns:

note_exam (float)

note_devoir (float)

Update all necessary parts of the application:

Migration (without running it, just prepare the correct structure)

Model: make sure fillables or casts are correct

Request validation classes: validate note_exam and note_devoir appropriately

Services: use both notes correctly for average calculation

Controllers: return clean and consistent JSON responses following the app’s standard

🧾 Step 2: Generate a Styled PDF Bulletin for a Student
Create a new PDF bulletin generation feature that includes:

🎓 Student Information
Full name (nom, prenom)

matricule

student_id

date_naissance (date of birth)

Class name

📚 Subject Rows (one line per subject of the class)
Each row must include:

Subject name

Coefficient

Average for the subject (calculated from note_exam and note_devoir)

📊 Footer Section
At the bottom of the bulletin, display:

General average (moyenne générale)

Rank (within class)

Appreciation (textual, based on performance)

Mention (e.g., “Passable”, “Assez Bien”, etc.)

Period (e.g., “Trimestre 1”)

🌈 Style Guidelines
PDF must be generated from an HTML Blade view

Use CSS for clean styling (tables, headers, school logo if needed)

Color support required (for header/footer, columns, etc.)

Use either Laravel Snappy or Dompdf depending on complexity needs

📄 Documentation Required
At the end of your implementation:

Document all changes in the file guide_saisie.md at the root of the project.

Write the guide in French.

Include:

What you modified in the notes structure

All changes in services, requests, or logic

How the bulletin PDF works

The path to the Blade view

The git commands used (git add, git commit) using Conventional Commits

Example of how to test the PDF generation (route, headers, token)

⚠️ Constraints
Do not break existing logic or structure.

Always use Laravel Modular architecture and Artisan commands.

Respect the JSON response structure (status, message, data, code) in every controller.

Log operations where appropriate.

Be methodical, and ensure the feature is testable and consistent with the existing project logic.
