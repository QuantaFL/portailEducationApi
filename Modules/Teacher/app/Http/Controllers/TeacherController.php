<?php

namespace Modules\Teacher\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Teacher\app\services\TeacherService;
use Modules\Teacher\Http\Requests\StoreTeacherRequest;
use Modules\Teacher\Http\Requests\UpdateTeacherRequest;
use Modules\Teacher\Exceptions\TeacherException;
use Modules\Teacher\Exceptions\TeacherConflictException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    protected $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $teachers = $this->teacherService->getAllTeacher();
            return response()->json($teachers);
        } catch (TeacherException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
    {
        try {
            $teacher = $this->teacherService->createTeacher($request->validated());
            return response()->json(['status' => 'success', 'data' => $teacher, 'code' => 201], 201);
        } catch (ValidationException $e) {
            Log::warning('Teacher creation validation failed.', ['errors' => $e->errors()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
                'code' => 400
            ], 400);
        } catch (TeacherConflictException $e) {
            Log::warning('Teacher creation conflict.', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => [],
                'code' => 409
            ], 409);
        } catch (TeacherException $e) {
            Log::error('Error creating teacher: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => [],
                'code' => 500
            ], 500);
        } catch (\Throwable $e) {
            Log::error('An unexpected error occurred during teacher creation: ' . $e->getMessage(), ['exception' => $e]);
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
        try {
            $teacher = $this->teacherService->getTeacher($id);
            return response()->json($teacher);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Teacher not found.'], 404);
        } catch (TeacherException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherRequest $request, $id)
    {
        try {
            $teacher = $this->teacherService->updateTeacher($id, $request->validated());
            return response()->json($teacher);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Teacher not found.'], 404);
        } catch (TeacherException $e) {
            if ($e->getMessage() === 'Email or phone already exists.') {
                return response()->json(['message' => $e->getMessage()], 409);
            }
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->teacherService->deleteTeacher($id);
            return response()->json(['message' => 'Teacher deleted successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Teacher not found.'], 404);
        } catch (TeacherException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }
}
