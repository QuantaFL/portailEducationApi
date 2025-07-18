<?php

namespace Modules\ParentModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ParentModule\app\services\ParentService;
use Modules\ParentModule\Http\Requests\StoreParentRequest;
use Modules\ParentModule\Exceptions\ParentConflictException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ParentController extends Controller
{
    protected $parentService;

    public function __construct(ParentService $parentService)
    {
        $this->parentService = $parentService;
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
    public function store(StoreParentRequest $request)
    {
        try {
            $parent = $this->parentService->createParent($request->validated());
            return response()->json(['status' => 'success', 'data' => $parent, 'code' => 201], 201);
        } catch (ValidationException $e) {
            Log::warning('Parent creation validation failed.', ['errors' => $e->errors()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
                'code' => 400
            ], 400);
        } catch (ParentConflictException $e) {
            Log::warning('Parent creation conflict.', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => [],
                'code' => 409
            ], 409);
        }  catch (\Throwable $e) {
            Log::error('An unexpected error occurred during parent creation: ' . $e->getMessage(), ['exception' => $e]);
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
