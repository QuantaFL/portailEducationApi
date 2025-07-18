<?php

namespace Modules\Etudiant\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Auth\Models\User;
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
        try {
            $year = date('Y');
            $random = strtoupper(substr(uniqid(), -5));
            $studentIdNumber = "MAT-{$year}-{$random}";
            return DB::transaction(function () use ($data) {
                $user = User::create([
                    'first_name' => $data['firstName'],
                    'last_name' => $data['lastName'],
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?? null,
                    'password' => Hash::make('password'),
                    'role_id' => $data['roleId'],
                    'address' => $data['address'] ?? null,
                    'date_of_birth' => $data['dateOfBirth'] ?? null,
                    'gender' => $data['gender'] ?? null,
                ]);

                return Etudiant::create([
                    'user_id' => $user->id,
                    'enrollment_date' => $data['enrollmentDate'],
                    'class_id' => $data['classId'],
                    'parent_user_id' => $data['parentUserId'] ?? null,
                    'student_id_number' => 'ETU-' . Str::uuid()->toString()
                ]);
            });
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
