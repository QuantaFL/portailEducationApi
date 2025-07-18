<?php

namespace Modules\Admin\app\services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Exceptions\AdminConflictException;
use Modules\Admin\Models\Admin;
use Modules\Auth\Models\User;
use Modules\Admin\Exceptions\AdminException;

class AdminService
{
    public function createAdmin(array $data): Admin
    {
        Log::info('Starting admin creation process.', ['email' => $data['email']]);
        DB::beginTransaction();
        try {
            $query = User::where('email', $data['email']);

            if (!empty($data['phone'])) {
                $query->orWhere('phone', $data['phone']);
            }

            $existingUser = $query->first();

            if ($existingUser) {
                Log::warning('Admin creation conflict: Email or phone already exists.', ['email' => $data['email'], 'phone' => $data['phone']]);
                throw new AdminConflictException('Email or phone number already taken.');
            }

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make('password'),
                'role_id' => 1, // Admin role ID
                'address' => $data['address'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
            ]);

            $nextId = Admin::max('id') + 1;
            $year = now()->year;
            $matricule = "ADM-{$year}-{$nextId}";

            $admin = Admin::create([
                'user_id' => $user->id,
                'admin_code' => $matricule,
            ]);

            DB::commit();
            Log::info('Admin created successfully.', ['admin_id' => $admin->id, 'user_id' => $user->id]);
            return $admin->load('user');
        } catch (AdminConflictException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Error creating admin: " . $e->getMessage(), ['exception' => $e]);
            throw new AdminException('Failed to create admin.', 0, $e);
        }
    }
}
