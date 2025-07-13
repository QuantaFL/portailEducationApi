<?php

namespace Modules\Etudiant\Services;

use Illuminate\Support\Str;
use Modules\Auth\Models\User;
use Modules\Etudiant\Models\Etudiant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EtudiantService
{
    public function getAllEtudiants()
    {
       // return Etudiant::with(['user', 'classes'])->get();
        return Etudiant::all();
    }

    public function getEtudiantById($id)
    {
      return Etudiant::findOrFail($id);
    }

    public function createEtudiant(array $data)
    {
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
    }

    public function updateEtudiant($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $etudiant = Etudiant::find($id);

            if (!$etudiant) {
                return null;
            }

            $etudiant->update([
                'enrollment_date' => $data['enrollmentDate'] ?? $etudiant->enrollmentDate,
                'class_id' => $data['classId'] ?? $etudiant->classId,
                'parent_user_id' => $data['parentUserId'] ?? $etudiant->parentUserId,
            ]);

            if (isset($data['firstName']) || isset($data['lastName']) || isset($data['email']) || isset($data['phone']) || isset($data['password']) || isset($data['roleId']) || isset($data['address']) || isset($data['dateOfBirth']) || isset($data['gender'])) {
                $etudiant->user->update([
                    'first_name' => $data['firstName'] ?? $etudiant->user->firstName,
                    'last_name' => $data['lastName'] ?? $etudiant->user->lastName,
                    'email' => $data['email'] ?? $etudiant->user->email,
                    'phone' => $data['phone'] ?? $etudiant->user->phone,
                    'password' => isset($data['password']) ? Hash::make($data['password']) : $etudiant->user->password,
                    'role_id' => $data['roleId'] ?? $etudiant->user->roleId,
                    'address' => $data['address'] ?? $etudiant->user->address,
                    'date_of_birth' => $data['dateOfBirth'] ?? $etudiant->user->dateOfBirth,
                    'gender' => $data['gender'] ?? $etudiant->user->gender,
                ]);
            }

            return $etudiant;
        });
    }

    public function deleteEtudiant($id)
    {
        return DB::transaction(function () use ($id) {
            $etudiant = Etudiant::find($id);

            if (!$etudiant) {
                return false;
            }

            $etudiant->user->delete();
            $etudiant->delete();

            return true;
        });
    }

    private function generateStudentIdNumber()
    {
        // This is a placeholder. Implement your actual student ID generation logic here.
        // For example, you could use a sequence, a random string, or a combination of date and counter.
        return 'S' . time();
    }
}
