<?php

namespace Modules\Subject\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Modules\Subject\Models\Subject;

class SubjectService
{
    public function createSubject(array $data): Subject
    {
        Log::info('Attempting to create a new subject.', ['data' => $data]);
        try {
            $subject = Subject::create($data);
            Log::info('Subject created successfully.', ['subject_id' => $subject->id]);
            return $subject;
        } catch (\Exception $e) {
            Log::error('Error creating subject.', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }

    public function updateSubject(int $id, array $data): Subject
    {
        Log::info('Attempting to update subject.', ['subject_id' => $id, 'data' => $data]);
        try {
            $subject = Subject::findOrFail($id);
            $subject->update([
                "name"=>$data['name'],
                "level"=>$data['level'],
                "coefficient"=>$data['coefficient']
            ]);
            Log::info('Subject updated successfully.', ['subject_id' => $subject->id]);
            return $subject;
        } catch (\Exception $e) {
            Log::error('Error updating subject.', ['error' => $e->getMessage(), 'subject_id' => $id, 'data' => $data]);
            throw $e;
        }
    }

    public function deleteSubject(int $id): bool
    {
        Log::info('Attempting to delete subject.', ['subject_id' =>$id]);
        try {
            $subject = Subject::findOrFail($id);
            return $subject->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting subject.', ['error' => $e->getMessage(), 'subject_id' => $id]);
            throw $e;
        }
    }

    public function listSubjects(): \Illuminate\Database\Eloquent\Collection|array
    {
        Log::info('Attempting to list all subjects.');
        try {
            $subjects = Subject::all();
            Log::info('Subjects listed successfully.', ['count' => $subjects->count()]);
            return $subjects;
        } catch (\Exception $e) {
            Log::error('Error listing subjects.', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getSubjectById(int $id): Subject
    {
        Log::info('Attempting to retrieve subject by ID.', ['subject_id' => $id]);
        try {
            $subject = Subject::findOrFail($id);
            Log::info('Subject retrieved successfully.', ['subject_id' => $id]);
            return $subject;
        }

        catch (\Exception $e) {
            Log::error('Error retrieving subject by ID.', ['error' => $e->getMessage(), 'subject_id' => $id]);
            throw $e;
        }
    }
}
