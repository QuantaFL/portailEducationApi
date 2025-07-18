<?php

namespace Modules\TeacherSubjectClass\app\services;

use Illuminate\Support\Facades\Log;
use Modules\Classes\Models\Classes;
use Modules\Subject\Models\Subject;
use Modules\Teacher\Models\Teacher;

use Modules\TeacherSubjectClass\app\Models\TeacherSubjectClass;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeacherSubjectClassService
{
    protected $teacherSubjectClassModel;

    public function __construct(TeacherSubjectClass $teacherSubjectClassModel)
    {
        $this->teacherSubjectClassModel = $teacherSubjectClassModel;
    }

    public function getAllTeacherSubjectClasses(): \Illuminate\Database\Eloquent\Collection|array
    {
        Log::info('Attempting to retrieve all teacher-subject-class associations.');
        try {
            $associations = $this->teacherSubjectClassModel->with(['teacher', 'subject', 'class'])->get();
            Log::info('Successfully retrieved all teacher-subject-class associations.', ['count' => $associations->count()]);
            return $associations;
        } catch (\Throwable $e) {
            Log::error('Error retrieving all teacher-subject-class associations: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getTeacherSubjectClassById($id): TeacherSubjectClass
    {
        Log::info('Attempting to retrieve teacher-subject-class association by ID.', ['id' => $id]);
        try {
            $association = $this->teacherSubjectClassModel->with(['teacher', 'subject', 'class'])->findOrFail($id);
            Log::info('Successfully retrieved teacher-subject-class association by ID.', ['id' => $id]);
            return $association;
        } catch (ModelNotFoundException $e) {
            Log::warning('Teacher-subject-class association not found.', ['id' => $id]);
            throw new NotFoundHttpException('Teacher-subject-class association not found.');
        } catch (\Throwable $e) {
            Log::error('Error retrieving teacher-subject-class association by ID: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createTeacherSubjectClass(array $data): TeacherSubjectClass
    {
        Log::info('Attempting to create a new teacher-subject-class association.', $data);
        try {
            $teacher = Teacher::findOrFail($data['teacherId']);
            $subject = Subject::findOrFail($data['subjectId']);
            $class = Classes::findOrFail($data['classId']);

            $existingAssociation = $this->teacherSubjectClassModel->where(
                [
                    'teacher_id' => $data['teacherId'],
                    'subject_id' => $data['subjectId'],
                    'class_id' => $data['classId'],
                ]
            )->first();

            if ($existingAssociation) {
                Log::warning('Attempt to create duplicate teacher-subject-class association.', $data);
                throw new ConflictHttpException('This teacher-subject-class association already exists.');
            }

            $association = $this->teacherSubjectClassModel->create([
                'teacher_id' => $data['teacherId'],
                'subject_id' => $data['subjectId'],
                'class_id' => $data['classId'],
            ]);
            Log::info('Successfully created teacher-subject-class association.', ['id' => $association->id]);
            return $association->load(['teacher', 'subject', 'class']);
        } catch (ModelNotFoundException $e) {
            Log::warning('One or more related entities not found for creating association.', $data);
            throw new NotFoundHttpException('Teacher, Subject, or Class not found.');
        } catch (\Throwable $e) {
            Log::error('Error creating teacher-subject-class association: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateTeacherSubjectClass($id, array $data): TeacherSubjectClass
    {
        Log::info('Attempting to update teacher-subject-class association.', ['id' => $id, 'data' => $data]);
        try {
            $association = $this->teacherSubjectClassModel->findOrFail($id);

            $teacher = Teacher::findOrFail($data['teacherId']);
            $subject = Subject::findOrFail($data['subjectId']);
            $class = Classes::findOrFail($data['classId']);

            $existingAssociation = $this->teacherSubjectClassModel->where(
                [
                    'teacher_id' => $data['teacherId'],
                    'subject_id' => $data['subjectId'],
                    'class_id' => $data['classId'],
                ]
            )->where('id', '!=', $id)->first();

            if ($existingAssociation) {
                Log::warning('Attempt to update to a duplicate teacher-subject-class association.', ['id' => $id, 'data' => $data]);
                throw new ConflictHttpException('This teacher-subject-class association already exists.');
            }

            $association->update([
                'teacher_id' => $data['teacherId'],
                'subject_id' => $data['subjectId'],
                'class_id' => $data['classId'],
            ]);
            Log::info('Successfully updated teacher-subject-class association.', ['id' => $association->id]);
            return $association->load(['teacher', 'subject', 'class']);
        } catch (ModelNotFoundException $e) {
            Log::warning('Teacher-subject-class association not found for update or one or more related entities not found.', ['id' => $id, 'data' => $data]);
            throw new NotFoundHttpException('Teacher-subject-class association not found or related entities missing.');
        } catch (\Throwable $e) {
            Log::error('Error updating teacher-subject-class association: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteTeacherSubjectClass($id): bool
    {
        Log::info('Attempting to delete teacher-subject-class association.', ['id' => $id]);
        try {
            $association = $this->teacherSubjectClassModel->findOrFail($id);
            return $association->delete();
        } catch (ModelNotFoundException $e) {
            Log::warning('Teacher-subject-class association not found for deletion.', ['id' => $id]);
            throw new NotFoundHttpException('Teacher-subject-class association not found.');
        } catch (\Throwable $e) {
            Log::error('Error deleting teacher-subject-class association: ' . $e->getMessage());
            throw $e;
        }
    }
}
