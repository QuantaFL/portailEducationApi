<?php

namespace Modules\Admin\app\services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Models\Admin;
use Modules\Auth\Models\User;
use Modules\Admin\Exceptions\AdminException;

class AdminService
{
    public function createAdmin(array $data): Admin
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
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
            return $admin->load('user');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Error creating admin: " . $e->getMessage(), ['exception' => $e]);
            throw new AdminException('Failed to create admin.', 0, $e);
        }
    }
}
