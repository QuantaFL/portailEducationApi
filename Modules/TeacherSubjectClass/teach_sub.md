## TeacherSubjectClass Module Summary

### Database Structure

The `teacher_subject_class` table establishes a many-to-many relationship between teachers, subjects, and classes.

| Field       | Type      | Description                                  |
|-------------|-----------|----------------------------------------------|
| `id`        | `bigint`  | Primary key.                                 |
| `teacher_id`| `bigint`  | The ID of the teacher (Foreign Key to `teachers` table).|
| `subject_id`| `bigint`  | The ID of the subject (Foreign Key to `subjects` table).|
| `class_id`  | `bigint`  | The ID of the class (Foreign Key to `classes` table).|
| `created_at`| `timestamp` | The timestamp when the record was created.   |
| `updated_at`| `timestamp` | The timestamp when the record was updated.   |

### API Endpoints

All endpoints are prefixed with `/api/teacher-subject-classes` and require API authentication.

| Method | Endpoint                       | Description                                  |
|--------|--------------------------------|----------------------------------------------|
| `GET`  | `/api/teacher-subject-classes` | Retrieve all teacher-subject-class associations.|
| `POST` | `/api/teacher-subject-classes` | Create a new teacher-subject-class association.|
| `GET`  | `/api/teacher-subject-classes/{id}`| Retrieve a specific association by ID.       |
| `PUT`  | `/api/teacher-subject-classes/{id}`| Update an existing association by ID.        |
| `DELETE`| `/api/teacher-subject-classes/{id}`| Delete an association by ID.                 |

### Expected JSON Request Body Format for Create/Update

When creating or updating a teacher-subject-class association, the request body should be in JSON format with the following structure:

```json
{
    "teacherId": 1, 
    "subjectId": 2, 
    "classId": 3  
}
```

**Field Descriptions:**
- `teacherId`: (Required, integer) The ID of the teacher. Must exist in the `teachers` table.
- `subjectId`: (Required, integer) The ID of the subject. Must exist in the `subjects` table.
- `classId`: (Required, integer) The ID of the class. Must exist in the `classes` table.
