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
    public function attemptLogin(array $credentials): User
    {
        Log::info('Attempting login for user.', ['email' => $credentials['email']]);
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            Log::warning('Login failed: Invalid credentials.', ['email' => $credentials['email']]);
            throw new AuthenticationException('Identifiants invalides.');
        }
        return $user;
    }

    public function login(array $credentials): array
    {
        try {
            Log::info('Generating token for user.', ['email' => $credentials['email']]);
            $user = $this->attemptLogin($credentials);

            $jwt = app(JWTAuth::class);
            $token = $jwt->fromUser($user);

            return [
                'token'   => $token,
                'user'    => $user,
            ];
        } catch (\Throwable $e) {
            Log::error('Token generation failed: ' . $e->getMessage(), ['error' => $e->getMessage()]);
            throw new AuthenticationException('Erreur lors de la connexion.', 0, $e);
        }
    }

    public function changePassword(string $email, string $oldPassword, string $newPassword): bool
    {
        Log::info('Attempting to change password for user.', ['email' => $email]);
        try {
            $user = User::where('email', $email)->first();

            if (!$user) {
                Log::warning('Password change failed: User not found.', ['email' => $email]);
                throw new AuthenticationException('User not found.');
            }

            if (!Hash::check($oldPassword, $user->password)) {
                Log::warning('Password change failed: Invalid old password.', ['email' => $email]);
                throw new AuthenticationException('Invalid old password.');
            }

            $user->password = Hash::make($newPassword);
            $user->save();

            Log::info('Password changed successfully for user.', ['email' => $email]);
            return true;
        } catch (AuthenticationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Error changing password for user: ' . $e->getMessage(), ['email' => $email, 'exception' => $e]);
            throw new Exception('Failed to change password.', 0, $e);
        }
    }
}