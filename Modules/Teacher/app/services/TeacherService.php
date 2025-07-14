<?php

namespace Modules\Teacher\app\services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Models\User;
use Modules\Teacher\Models\Teacher;

class TeacherService
{
    public function getAllTeacher()
    {
        try {
         $teachers =    Teacher::all();
            Log::info("Teacher  successfully  retrieve ");
            return ['status' => 'success', 'teachers' => $teachers];
        }catch (\Exception $e) {
            Log::error("Error creating teacher: " . $e->getMessage(), ['exception' => $e]);
            return ['status' => 'error', 'message' => 'Failed to get all teachers.'];
        }

    }
    public function createTeacher(array $data)
    {
        DB::beginTransaction();
        try {
            // Check for existing user with the same email or phone
            $existingUser = User::where('email', $data['email'])
                ->orWhere('phone', $data['phone'])
                ->first();

            if ($existingUser) {
                Log::warning("Teacher creation conflict: Email or phone already exists.", ['email' => $data['email'], 'phone' => $data['phone']]);
                return ['status' => 'conflict', 'message' => 'Email or phone already exists.'];
            }

            $user = User::create([
                'first_name' => $data['firstName'],
                'last_name' => $data['lastName'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => bcrypt('password'),
                'role_id' => 2, // Teacher role ID
                'address' => $data['address'] ?? null,
                'date_of_birth' => $data['dateOfBirth'] ?? null,
                'gender' => $data['gender'] ?? null,
            ]);

            $teacher = Teacher::create([
                'user_id' => $user->id,
                'hire_date' => $data['hireDate'],
            ]);

            DB::commit();
            Log::info("Teacher created successfully.", ['teacher_id' => $teacher->id, 'user_id' => $user->id]);
            return ['status' => 'success', 'teacher' => $teacher->load(['user', 'user.role'])];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error creating teacher: " . $e->getMessage(), ['exception' => $e]);
            return ['status' => 'error', 'message' => 'Failed to create teacher.'];
        }
    }

    public function getTeacher(int $id)
    {
        try {
            $teacher = Teacher::with(['user', 'user.role'])->find($id);
            if (!$teacher) {
                Log::warning("Teacher not found.", ['teacher_id' => $id]);
                return ['status' => 'not_found', 'message' => 'Teacher not found.'];
            }
            Log::info("Teacher fetched successfully.", ['teacher_id' => $id]);
            return ['status' => 'success', 'teacher' => $teacher];
        } catch (\Exception $e) {
            Log::error("Error fetching teacher: " . $e->getMessage(), ['exception' => $e]);
            return ['status' => 'error', 'message' => 'Failed to fetch teacher.'];
        }
    }

    public function updateTeacher(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $teacher = Teacher::findOrFail($id);
            if (!$teacher) {
                Log::warning("Teacher not found for update.", ['teacher_id' => $id]);
                return ['status' => 'not_found', 'message' => 'Teacher not found.'];
            }

            $user = $teacher->user;
            if (!$user) {
                Log::warning("User associated with teacher not found for update.", ['teacher_id' => $id]);
                return ['status' => 'not_found', 'message' => 'User associated with teacher not found.'];
            }

            // Check for existing user with the same email or phone, excluding the current user
            $existingUser = User::where(function ($query) use ($data) {
                $query->where('email', $data['email'])
                    ->orWhere('phone', $data['phone']);
            })
                ->where('id', '!=', $user->id)
                ->first();

            if ($existingUser) {
                Log::warning("Teacher update conflict: Email or phone already exists.", ['email' => $data['email'], 'phone' => $data['phone']]);
                return ['status' => 'conflict', 'message' => 'Email or phone already exists.'];
            }

            $user->update([
                'first_name' => $data['first_name'] ?? $user->first_name,
                'last_name' => $data['last_name'] ?? $user->last_name,
                'email' => $data['email'] ?? $user->email,
                'phone' => $data['phone'] ?? $user->phone,
                'address' => $data['address'] ?? $user->address,
                'date_of_birth' => $data['date_of_birth'] ?? $user->date_of_birth,
                'gender' => $data['gender'] ?? $user->gender,
            ]);

            $teacher->update([
                'hire_date' => $data['hire_date'] ?? $teacher->hire_date,
            ]);

            DB::commit();
            Log::info("Teacher updated successfully.", ['teacher_id' => $id, 'user_id' => $user->id]);
            return ['status' => 'success', 'teacher' => $teacher->load(['user', 'user.role'])];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating teacher: " . $e->getMessage(), ['exception' => $e]);
            return ['status' => 'error', 'message' => 'Failed to update teacher.'];
        }
    }

    public function deleteTeacher(int $id)
    {
        DB::beginTransaction();
        try {
            $teacher = Teacher::findOrFail($id);
            if (!$teacher) {
                Log::warning("Teacher not found for deletion.", ['teacher_id' => $id]);
                return ['status' => 'not_found', 'message' => 'Teacher not found.'];
            }

            $user = $teacher->user;
            if ($user) {
                $user->delete(); // This will also delete the teacher due to cascade on user_id
            } else {
                $teacher->delete();
            }

            DB::commit();
            Log::info("Teacher deleted successfully.", ['teacher_id' => $id]);
            return ['status' => 'success', 'message' => 'Teacher deleted successfully.'];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting teacher: " . $e->getMessage(), ['exception' => $e]);
            return ['status' => 'error', 'message' => 'Failed to delete teacher.'];
        }
    }

}
