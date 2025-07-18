<?php

namespace Modules\ParentModule\app\services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Models\User;
use Modules\ParentModule\Exceptions\ParentException;
use Modules\ParentModule\Models\Parents;

class ParentService
{
    public function createParent(array $data): Parents
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'role_id' => 4, // ParentModule role ID
                'address' => $data['address'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
            ]);
            $nextId = \Modules\ParentModule\Models\Parents::max('id') + 1;
            $year = now()->year;
            $matricule = "PRT-{$year}-{$nextId}";

            $parent = Parents::create([
                'user_id' => $user->id,
                'student_id' => $data['student_id'],
                'phone_number' => $data['phone_number'] ?? null,
                'matricule'=>$matricule
            ]);

            DB::commit();
            return $parent->load(['user', 'student']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Error creating parent: " . $e->getMessage(), ['exception' => $e]);
            throw new ParentException('Failed to create parent.', 0, $e);
        }
    }
}
