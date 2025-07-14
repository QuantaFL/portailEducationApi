<?php

namespace Modules\TeacherSubjectClass\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TeacherSubjectClass\app\Http\Requests\TeacherSubjectClassRequest;
use Modules\TeacherSubjectClass\app\services\TeacherSubjectClassService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class TeacherSubjectClassController extends Controller
{
    protected $teacherSubjectClassService;

    public function __construct(TeacherSubjectClassService $teacherSubjectClassService)
    {
        $this->teacherSubjectClassService = $teacherSubjectClassService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $associations = $this->teacherSubjectClassService->getAllTeacherSubjectClasses();
            return response()->json($associations);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving associations', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeacherSubjectClassRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $association = $this->teacherSubjectClassService->createTeacherSubjectClass($validatedData);
            return response()->json($association, 201);
        } catch (ConflictHttpException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        } catch (NotFoundHttpException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating association', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $association = $this->teacherSubjectClassService->getTeacherSubjectClassById($id);
            return response()->json($association);
        } catch (NotFoundHttpException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving association', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherSubjectClassRequest $request, $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $association = $this->teacherSubjectClassService->updateTeacherSubjectClass($id, $validatedData);
            return response()->json($association);
        } catch (NotFoundHttpException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (ConflictHttpException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating association', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->teacherSubjectClassService->deleteTeacherSubjectClass($id);
            return response()->json(null, 204);
        } catch (NotFoundHttpException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting association', 'error' => $e->getMessage()], 500);
        }
    }
}