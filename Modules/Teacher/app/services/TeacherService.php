<?php

namespace Modules\Teacher\app\services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Models\User;
use Modules\Teacher\Models\Teacher;
use Modules\Teacher\Exceptions\TeacherException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TeacherService
{
    public function getAllTeacher()
    {
        try {
            return Teacher::all();
        } catch (\Throwable $e) {
            Log::error("Error fetching all teachers: " . $e->getMessage(), ['exception' => $e]);
            throw new TeacherException('Failed to get all teachers.', 0, $e);
        }
    }

    public function createTeacher(array $data): Teacher
    {
        DB::beginTransaction();
        try {
            // Check for existing user with the same email or phone
            $existingUser = User::where('email', $data['email'])
                ->orWhere('phone', $data['phone'])
                ->first();

            if ($existingUser) {
                Log::warning("Teacher creation conflict: Email or phone already exists.", ['email' => $data['email'], 'phone' => $data['phone']]);
                throw new TeacherException('Email or phone already exists.');
            }

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => bcrypt('password'),
                'role_id' => 2, // Teacher role ID
                'address' => $data['address'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
            ]);

            $teacher = Teacher::create([
                'user_id' => $user->id,
                'hire_date' => $data['hire_date'],
            ]);

            DB::commit();
            Log::info("Teacher created successfully.", ['teacher_id' => $teacher->id, 'user_id' => $user->id]);
            return $teacher->load(['user', 'user.role']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Error creating teacher: " . $e->getMessage(), ['exception' => $e]);
            throw new TeacherException('Failed to create teacher.', 0, $e);
        }
    }

    public function getTeacher(int $id): Teacher
    {
        try {
            $teacher = Teacher::with(['user', 'user.role'])->findOrFail($id);
            Log::info("Teacher fetched successfully.", ['teacher_id' => $id]);
            return $teacher;
        } catch (ModelNotFoundException $e) {
            Log::warning("Teacher not found.", ['teacher_id' => $id]);
            throw new TeacherException('Teacher not found.', 0, $e);
        } catch (\Throwable $e) {
            Log::error("Error fetching teacher: " . $e->getMessage(), ['exception' => $e]);
            throw new TeacherException('Failed to fetch teacher.', 0, $e);
        }
    }

    public function updateTeacher(int $id, array $data): Teacher
    {
        DB::beginTransaction();
        try {
            $teacher = Teacher::findOrFail($id);

            $user = $teacher->user;

            // Check for existing user with the same email or phone, excluding the current user
            $existingUser = User::where(function ($query) use ($data) {
                $query->where('email', $data['email'])
                    ->orWhere('phone', $data['phone']);
            })
                ->where('id', '!=', $user->id)
                ->first();

            if ($existingUser) {
                Log::warning("Teacher update conflict: Email or phone already exists.", ['email' => $data['email'], 'phone' => $data['phone']]);
                throw new TeacherException('Email or phone already exists.');
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
            return $teacher->load(['user', 'user.role']);
        } catch (ModelNotFoundException $e) {
            Log::warning("Teacher not found for update.", ['teacher_id' => $id]);
            throw new TeacherException('Teacher not found.', 0, $e);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Error updating teacher: " . $e->getMessage(), ['exception' => $e]);
            throw new TeacherException('Failed to update teacher.', 0, $e);
        }
    }

    public function deleteTeacher(int $id): bool
    {
        DB::beginTransaction();
        try {
            $teacher = Teacher::findOrFail($id);

            $user = $teacher->user;
            if ($user) {
                $user->delete(); // This will also delete the teacher due to cascade on user_id
            } else {
                $teacher->delete();
            }

            DB::commit();
            Log::info("Teacher deleted successfully.", ['teacher_id' => $id]);
            return true;
        } catch (ModelNotFoundException $e) {
            Log::warning("Teacher not found for deletion.", ['teacher_id' => $id]);
            throw new TeacherException('Teacher not found.', 0, $e);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Error deleting teacher: " . $e->getMessage(), ['exception' => $e]);
            throw new TeacherException('Failed to delete teacher.', 0, $e);
        }
    }
}
