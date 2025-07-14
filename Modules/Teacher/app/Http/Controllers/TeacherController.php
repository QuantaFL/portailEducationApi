<?php

namespace Modules\Teacher\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Teacher\app\services\TeacherService;
use Modules\Teacher\Http\Requests\StoreTeacherRequest;
use Modules\Teacher\Http\Requests\UpdateTeacherRequest;

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
        //
        $result = $this->teacherService->getAllTeacher();
        if ($result['status'] === 'success') {
            return response()->json($result['teachers']);
        }
        return response()->json(['message' => $result['message']], 500);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
    {
        $result = $this->teacherService->createTeacher($request->validated());

        if ($result['status'] === 'success') {
            return response()->json($result['teacher'], 201);
        } elseif ($result['status'] === 'conflict') {
            return response()->json(['message' => $result['message']], 409);
        }
        return response()->json(['message' => $result['message']], 500);

    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $result = $this->teacherService->getTeacher($id);

        if ($result['status'] === 'success') {
            return response()->json($result['teacher'], 200);
        } elseif ($result['status'] === 'not_found') {
            return response()->json(['message' => $result['message']], 404);
        }
        return response()->json(['message' => $result['message']], 500);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherRequest $request, $id)
    {
        $result = $this->teacherService->updateTeacher($id, $request->validated());

        if ($result['status'] === 'success') {
            return response()->json($result['teacher'], 200);
        } elseif ($result['status'] === 'not_found') {
            return response()->json(['message' => $result['message']], 404);
        } elseif ($result['status'] === 'conflict') {
            return response()->json(['message' => $result['message']], 409);
        }
        return response()->json(['message' => $result['message']], 500);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $result = $this->teacherService->deleteTeacher($id);

        if ($result['status'] === 'success') {
            return response()->json(['message' => $result['message']], 200);
        } elseif ($result['status'] === 'not_found') {
            return response()->json(['message' => $result['message']], 404);
        }
        return response()->json(['message' => $result['message']], 500);

    }
}
