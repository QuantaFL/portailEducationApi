<?php

namespace Modules\Parent\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Parent\app\services\ParentService;
use Modules\Parent\Http\Requests\StoreParentRequest;
use Modules\Parent\Exceptions\ParentException;

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
            return response()->json($parent, 201);
        } catch (ParentException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
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