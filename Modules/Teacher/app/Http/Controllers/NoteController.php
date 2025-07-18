<?php

namespace Modules\Teacher\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Teacher\Services\NoteService;
use Modules\Teacher\Http\Requests\StoreNoteRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class NoteController extends Controller
{
    protected $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Implement index logic if needed
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        try {
            $note = $this->noteService->createNote($request->validated());
            return response()->json(['status' => 'success', 'data' => $note, 'code' => 201], 201);
        } catch (ValidationException $e) {
            Log::warning('Note creation validation failed.', ['errors' => $e->errors()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
                'code' => 400
            ], 400);
        } catch (\Throwable $e) {
            Log::error('An unexpected error occurred during note creation: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred.',
                'errors' => [],
                'code' => 500
            ], 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        // Implement show logic if needed
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Implement update logic if needed
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Implement destroy logic if needed
    }
}
