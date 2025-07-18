<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Auth\Http\Requests\UserRequest;
use Modules\Auth\Services\AuthService;
use Modules\Auth\Exceptions\AuthenticationException;
use Modules\Auth\Exceptions\RegistrationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(UserRequest $request)
    {
        try {
            $user = $this->authService->register($request->validated());
            return response()->json(['status' => 'success', 'data' => $user], 201);
        } catch (RegistrationException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $result = $this->authService->login($credentials);
            return response()->json(['status' => 'success', 'data' => $result], 200);
        } catch (AuthenticationException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }

    public function me()
    {
        $user = auth()->user();
        return response()->json(['status' => 'success', 'data' => $user]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json([]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return response()->json([]);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        //
        return response()->json([]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        return response()->json([]);
    }
}
