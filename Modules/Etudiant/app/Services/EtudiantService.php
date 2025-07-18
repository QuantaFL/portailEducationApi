<?php

namespace Modules\Etudiant\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Auth\Models\User;
use Modules\Etudiant\Exceptions\EtudiantConflictException;
use Modules\Etudiant\Models\Etudiant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Etudiant\Exceptions\EtudiantException;

class EtudiantService
{
    public function getAllEtudiants()
    {
        try {
            return Etudiant::all();
        } catch (\Throwable $e) {
            Log::error('Error fetching students: ' . $e->getMessage());
            throw new EtudiantException('Error fetching students.', 0, $e);
        }
    }

    public function getEtudiantById($id)
    {
        try {
            return Etudiant::findOrFail($id);
        } catch (\Throwable $e) {
            Log::error('Error fetching student by ID: ' . $e->getMessage());
            throw new EtudiantException('Student not found.', 0, $e);
        }
    }

    public function createEtudiant(array $data)
    {
        Log::info('Starting student creation process.', ['email' => $data['email']]);
        try {
            $query = User::where('email', $data['email']);

            if (!empty($data['phone'])) {
                $query->orWhere('phone', $data['phone']);
            }

            $existingUser = $query->first();

            if ($existingUser) {
                Log::warning('Student creation conflict: Email or phone already exists.', ['email' => $data['email'], 'phone' => $data['phone']]);
                throw new EtudiantConflictException('Email or phone number already taken.');
            }

            // Check for existing tutor phone number
            if (isset($data['tutor_phone_number'])) {
                $existingTutorPhone = Etudiant::where('tutor_phone_number', $data['tutor_phone_number'])->first();
                if ($existingTutorPhone) {
                    Log::warning('Student creation conflict: Tutor phone number already exists.', ['tutor_phone_number' => $data['tutor_phone_number']]);
                    throw new EtudiantConflictException('Tutor phone number already taken.');
                }
            }

            return DB::transaction(function () use ($data) {
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
                $nextId = Etudiant::max('id') + 1;
                $year = now()->year;
                $matricule = "ETD-{$year}-{$nextId}";
                $etudiant = Etudiant::create([
                    'user_id' => $user->id,
                    'enrollment_date' => $data['enrollment_date'],
                    'class_id' => $data['class_id'],
                    'parent_user_id' => $data['parent_user_id'] ?? null,
                    'student_id_number' => $matricule,
                    'tutor_phone_number'=>$data['tutor_phone_number']
                ]);
                Log::info('Student created successfully.', ['etudiant_id' => $etudiant->id, 'user_id' => $user->id]);
                return $etudiant;
            });
        } catch (EtudiantConflictException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Error creating student: ' . $e->getMessage());
            throw new EtudiantException('Error creating student.', 0, $e);
        }
    }

    public function updateEtudiant($id, array $data)
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $etudiant = Etudiant::findOrFail($id);

                Log::info('Etudiant found with id ' . $id);

                $etudiant->update([
                    'class_id' => $data['classId'] ?? $etudiant->classId,
                    'parent_user_id' => $data['parentUserId'] ?? $etudiant->parentUserId,
                ]);

                if (isset($data['firstName']) || isset($data['lastName']) || isset($data['email']) || isset($data['phone']) || isset($data['password']) || isset($data['roleId']) || isset($data['address']) || isset($data['dateOfBirth']) || isset($data['gender'])) {
                    $etudiant->user->update([
                        'first_name' => $data['firstName'] ?? $etudiant->user->firstName,
                        'last_name' => $data['lastName'] ?? $etudiant->user->lastName,
                        'email' => $data['email'] ?? $etudiant->user->email,
                        'phone' => $data['phone'] ?? $etudiant->user->phone,
                        //   'password' => isset($data['password']) ? Hash::make($data['password']) : $etudiant->user->password,
                        'role_id' => $data['roleId'] ?? $etudiant->user->roleId,
                        'address' => $data['address'] ?? $etudiant->user->address,
                        'date_of_birth' => $data['dateOfBirth'] ?? $etudiant->user->dateOfBirth,
                        'gender' => $data['gender'] ?? $etudiant->user->gender,
                    ]);
                }

                return $etudiant;
            });
        } catch (\Throwable $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            throw new EtudiantException('Error updating student.', 0, $e);
        }
    }

    public function deleteEtudiant($id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $etudiant = Etudiant::findOrFail($id);

                $etudiant->user->delete();
                return $etudiant->delete();
            });
        } catch (\Throwable $e) {
            Log::error('Error deleting student: ' . $e->getMessage());
            throw new EtudiantException('Error deleting student.', 0, $e);
        }
    }

    private function generateStudentIdNumber()
    {
        // This is a placeholder. Implement your actual student ID generation logic here.
        // For example, you could use a sequence, a random string, or a combination of date and counter.
        return 'S' . time();
    }
}
