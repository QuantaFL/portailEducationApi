<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Http\Requests\ChangePasswordRequest;
use Modules\Auth\Http\Requests\loginRequest;
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

    public function login(loginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $user = $this->authService->attemptLogin($credentials);

            if ($user->created_at->equalTo($user->updated_at)) {
                Log::info('First login detected for user.', ['user_id' => $user->id, 'email' => $user->email]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'First login detected. Please change your password before proceeding.',
                    'errors' => [],
                    'code' => 403
                ], 403);
            }

            $result = $this->authService->login($credentials);
            return response()->json(['status' => 'success', 'message' => 'Login successful', 'data' => $result, 'code' => 200], 200);
        } catch (AuthenticationException $e) {
            Log::warning('Authentication failed: ' . $e->getMessage(), ['email' => $request->input('email')]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => [],
                'code' => 401
            ], 401);
        } catch (\Throwable $e) {
            Log::error('An unexpected error occurred during login: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred.',
                'errors' => [],
                'code' => 500
            ], 500);
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

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $this->authService->changePassword(
                $request['email'],
                $request['old_password'],
                $request['new_password']
            );
            Log::info('Password changed successfully.', ['email' => $request['email']]);
            return response()->json([
                'status' => 'success',
                'message' => 'Password changed successfully. Please log in with your new credentials.',
                'code' => 200
            ], 200);
        } catch (ValidationException $e) {
            Log::warning('Password change validation failed.', ['errors' => $e->errors()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
                'code' => 400
            ], 400);
        } catch (AuthenticationException $e) {
            Log::warning('Password change failed: ' . $e->getMessage(), ['email' => $request['email']]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => [],
                'code' => 401
            ], 401);
        } catch (\Throwable $e) {
            Log::error('An unexpected error occurred during password change: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred.',
                'errors' => [],
                'code' => 500
            ], 500);
        }
    }
}
