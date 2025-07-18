<?php

namespace Modules\ParentModule\app\services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Models\User;
use Modules\ParentModule\Exceptions\ParentConflictException;
use Modules\ParentModule\Models\Parents as ParentModel;
use Modules\ParentModule\Exceptions\ParentException;

class ParentService
{
    public function createParent(array $data): ParentModel
    {
        Log::info('Starting parent creation process.', ['email' => $data['email']]);
        DB::beginTransaction();
        try {
            $query = User::where('email', $data['email']);

            if (!empty($data['phone'])) {
                $query->orWhere('phone', $data['phone']);
            }

            $existingUser = $query->first();

            if ($existingUser) {
                Log::warning('Parent creation conflict: Email or phone already exists.', ['email' => $data['email'], 'phone' => $data['phone']]);
                throw new ParentConflictException('Email or phone number already taken.');
            }

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make('password'),
                'role_id' => $data['role_id'],
                'address' => $data['address'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
            ]);

            $parent = ParentModel::create([
                'user_id' => $user->id,
                'student_id' => $data['student_id'],
                'phone_number' => $data['phone_number'] ?? null,
            ]);

            DB::commit();
            Log::info('Parent created successfully.', ['parent_id' => $parent->id, 'user_id' => $user->id]);
            return $parent->load(['user', 'student']);
        } catch (ParentConflictException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Error creating parent: " . $e->getMessage(), ['exception' => $e]);
            throw new ParentException('Failed to create parent.', 0, $e);
        }
    }
}
