<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Auth\Http\Requests\UserRequest;
use Modules\Auth\Services\AuthService;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $result = (new \Modules\Auth\Services\AuthService)->register($request->validated());

        return response()->json($result, $result['success'] ? 201 : 400);

    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $result = (new \Modules\Auth\Services\AuthService)->login($credentials);

        return response()->json($result, $result['success'] ? 200 : 401);
    }

    public function me()
    {
        $user = auth()->user();
        return response()->json($user);
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
