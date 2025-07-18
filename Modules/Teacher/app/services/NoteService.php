<?php

namespace Modules\Teacher\Services;

use Modules\Teacher\Models\Note;
use Illuminate\Support\Facades\Log;

class NoteService
{
    public function createNote(array $data): Note
    {
        Log::info('Attempting to create a new note.', ['data' => $data]);
        try {
            $note = Note::create($data);
            Log::info('Note created successfully.', ['note_id' => $note->id]);
            return $note;
        } catch (\Throwable $e) {
            Log::error('Error creating note.', ['error' => $e->getMessage(), 'data' => $data]);
            throw $e;
        }
    }
}
