<?php

namespace Modules\Subject\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Modules\Subject\Http\Requests\SubjectRequest;
use Modules\Subject\Services\SubjectService;

class SubjectController extends Controller
{
    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $subjects = $this->subjectService->listSubjects();
            return response()->json([
                "data"=>$subjects
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error listing subjects', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubjectRequest $request): JsonResponse
    {
        try {
            $subject = $this->subjectService->createSubject($request->validated());
            return response()->json([
                "data"=>$subject
            ],201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json(['message' => 'Subject with this name already exists.'], 409);
            }
            return response()->json(['message' => 'Error creating subject', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Error creating subject', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $subject = $this->subjectService->getSubjectById($id);
            return response()->json(
                $subject
            );
        } catch (ModelNotFoundException $e) {
            Log::warning("[SubjectController] Subject not found: {$e->getMessage()}");

            return response()->json(['message' => 'Subject not found.'], 404);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving subject', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubjectRequest $request, string $id): JsonResponse
    {
        try {
            $updatedSubject = $this->subjectService->updateSubject((int)$id, $request->validated());
            return response()->json([
                "data"=>$updatedSubject
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Subject not found.'], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json(['message' => 'Subject with this name already exists.'], 409);
            }
            return response()->json(['message' => 'Error updating subject', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating subject', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->subjectService->deleteSubject((int)$id);
            return response()->json(['message' => 'Subject deleted successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Subject not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting subject', 'error' => $e->getMessage()], 500);
        }
    }
}
