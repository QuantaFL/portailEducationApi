<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\app\services\AdminService;
use Modules\Admin\Http\Requests\StoreAdminRequest;
use Modules\Admin\Exceptions\AdminException;
use Modules\Admin\Exceptions\AdminConflictException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
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
    public function store(StoreAdminRequest $request)
    {
        try {
            $admin = $this->adminService->createAdmin($request->validated());
            return response()->json(['status' => 'success', 'data' => $admin, 'code' => 201], 201);
        } catch (ValidationException $e) {
            Log::warning('Admin creation validation failed.', ['errors' => $e->errors()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
                'code' => 400
            ], 400);
        } catch (AdminConflictException $e) {
            Log::warning('Admin creation conflict.', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => [],
                'code' => 409
            ], 409);
        } catch (AdminException $e) {
            Log::error('Error creating admin: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => [],
                'code' => 500
            ], 500);
        } catch (\Throwable $e) {
            Log::error('An unexpected error occurred during admin creation: ' . $e->getMessage(), ['exception' => $e]);
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
