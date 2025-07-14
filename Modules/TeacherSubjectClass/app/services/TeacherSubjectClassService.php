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

    public function getAllTeacherSubjectClasses()
    {
        Log::info('Attempting to retrieve all teacher-subject-class associations.');
        $associations = $this->teacherSubjectClassModel->with(['teacher', 'subject', 'class'])->get();
        Log::info('Successfully retrieved all teacher-subject-class associations.', ['count' => $associations->count()]);
        return $associations;
    }

    public function getTeacherSubjectClassById($id)
    {
        Log::info('Attempting to retrieve teacher-subject-class association by ID.', ['id' => $id]);
        $association = $this->teacherSubjectClassModel->with(['teacher', 'subject', 'class'])->find($id);
        if (!$association) {
            Log::warning('Teacher-subject-class association not found.', ['id' => $id]);
            throw new NotFoundHttpException('Teacher-subject-class association not found.');
        }
        Log::info('Successfully retrieved teacher-subject-class association by ID.', ['id' => $id]);
        return $association;
    }

    public function createTeacherSubjectClass(array $data)
    {
        Log::info('Attempting to create a new teacher-subject-class association.', $data);

        $teacher = Teacher::findOrFail($data['teacherId']);
        $subject = Subject::findOrFail($data['subjectId']);
        $class = Classes::findOrFail($data['classId']);

        if (!$teacher || !$subject || !$class) {
            Log::warning('One or more related entities not found for creating association.', $data);
            throw new NotFoundHttpException('Teacher, Subject, or Class not found.');
        }

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
    }

    public function updateTeacherSubjectClass($id, array $data)
    {
        Log::info('Attempting to update teacher-subject-class association.', ['id' => $id, 'data' => $data]);
        $association = $this->teacherSubjectClassModel->find($id);

        if (!$association) {
            Log::warning('Teacher-subject-class association not found for update.', ['id' => $id]);
            throw new NotFoundHttpException('Teacher-subject-class association not found.');
        }

        $teacher = Teacher::findOrFail($data['teacherId']);
        $subject = Subject::findOrFail($data['subjectId']);
        $class = Classes::findOrFail($data['classId']);


        if (!$teacher || !$subject || !$class) {
            Log::warning('One or more related entities not found for updating association.', $data);
            throw new NotFoundHttpException('Teacher, Subject, or Class not found.');
        }

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
    }

    public function deleteTeacherSubjectClass($id)
    {
        Log::info('Attempting to delete teacher-subject-class association.', ['id' => $id]);
        $association = $this->teacherSubjectClassModel->find($id);

        if (!$association) {
            Log::warning('Teacher-subject-class association not found for deletion.', ['id' => $id]);
            throw new NotFoundHttpException('Teacher-subject-class association not found.');
        }

        $association->delete();
        Log::info('Successfully deleted teacher-subject-class association.', ['id' => $id]);
        return true;
    }
}
