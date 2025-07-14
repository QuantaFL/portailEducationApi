<?php

namespace Modules\Subject\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Modules\Subject\Models\Subject;

class SubjectService
{
    public function createSubject(array $data)
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

    public function updateSubject(int $id, array $data)
    {
        Log::info('Attempting to update subject.', ['subject_id' => $id, 'data' => $data]);
        try {
            $subject = Subject::findOrFail($id);
            Subject::Update([
                "name"=>$data['name'],
                "level"=>$data['level'],
                "coefficient"=>$data['coefficient']
            ]);
            Log::info('Subject updated successfully.', ['subject_id' => $subject->id]);
            return $subject;
        } catch (\Exception $e) {
            Log::error('Error updating subject.', ['error' => $e->getMessage(), 'subject_id' => $subject->id, 'data' => $data]);
            throw $e;
        }
    }

    public function deleteSubject(Subject $id)
    {
        Log::info('Attempting to delete subject.', ['subject_id' =>$id]);
        try {
            Subject::destroy($id);
            Log::info('Subject deleted successfully.', ['subject_id' => $id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting subject.', ['error' => $e->getMessage(), 'subject_id' => $id]);
            throw $e;
        }
    }

    public function listSubjects()
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

    public function getSubjectById(int $id)
    {
        Log::info('Attempting to retrieve subject by ID.', ['subject_id' => $id]);
        try {
            $subject = Subject::findOrFail($id);
            if (!$subject) {
                throw new ModelNotFoundException("Subject with ID {$id} not found.");
            }
            if ($subject) {
                Log::info('Subject retrieved successfully.', ['subject_id' => $id]);
            } else {
                Log::warning('Subject not found.', ['subject_id' => $id]);
            }
            return $subject;
        }

        catch (\Exception $e) {
            Log::error('Error retrieving subject by ID.', ['error' => $e->getMessage(), 'subject_id' => $id]);
            throw $e;
        }
    }
}
