<?php

namespace Modules\Auth\Services;

use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

class AuthService
{

    public function __construct()
    {
    }
    public function register(array $data): array
    {
        try {
            Log::info('Register attempt', $data);

            //$data['mot_de_passe'] = Hash::make('password');
            $user = User::create($data);

            return [
                'success' => true,
                'message' => 'Utilisateur enregistré avec succès.',
                'user'    => $user,
            ];
        } catch (\Throwable $e) {
            Log::error('Register failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Erreur lors de l’enregistrement.',
            ];
        }
    }
    public function login(array $credentials): array
    {
        try {
            Log::info('Login attempt', $credentials);

            $user = User::where('email', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['mot_de_passe'], $user->mot_de_passe)) {
                return [
                    'success' => false,
                    'message' => 'Identifiants invalides.',
                ];
            }

            $jwt = app(JWTAuth::class);

            $token = $jwt->fromUser($user);


            return [
                'success' => true,
                'message' => 'Connexion réussie.',
                'token'   => $token,
                'user'    => $user,
            ];
        } catch (\Throwable $e) {
            Log::error('Login failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Erreur lors de la connexion.',
            ];
        }
    }

}
