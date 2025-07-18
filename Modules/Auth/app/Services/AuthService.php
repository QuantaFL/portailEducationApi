<?php

namespace Modules\Auth\Services;

use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
use Modules\Auth\Exceptions\AuthenticationException;
use Modules\Auth\Exceptions\RegistrationException;

class AuthService
{

    public function __construct()
    {
    }
    public function register(array $data): User
    {
        try {
            Log::info('Register attempt', $data);

            $user = User::create($data);

            return $user;
        } catch (\Throwable $e) {
            Log::error('Register failed', ['error' => $e->getMessage()]);
            throw new RegistrationException('Erreur lors de l’enregistrement.', 0, $e);
        }
    }
    public function login(array $credentials): array
    {
        try {
            Log::info('Login attempt', $credentials);

            $user = User::where('email', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                throw new AuthenticationException('Identifiants invalides.');
            }

            $jwt = app(JWTAuth::class);

            $token = $jwt->fromUser($user);


            return [
                'token'   => $token,
                'user'    => $user,
            ];
        } catch (\Throwable $e) {
            Log::error('Login failed', ['error' => $e->getMessage()]);
            throw new AuthenticationException('Erreur lors de la connexion.', 0, $e);
        }
    }

}
