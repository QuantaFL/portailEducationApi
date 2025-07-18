<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Auth\Services\RoleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $roles = $this->roleService->getAllRoles();
            return response()->json($roles);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error fetching roles', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $role = $this->roleService->createRole($request->all());
            return response()->json($role, 201);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error creating role', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $role = $this->roleService->getRoleById($id);
            return response()->json($role);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error fetching role', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $role = $this->roleService->updateRole($id, $request->all());
            return response()->json($role);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error updating role', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->roleService->deleteRole($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error deleting role', 'error' => $e->getMessage()], 500);
        }
    }
}
