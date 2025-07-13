<?php

namespace Modules\Etudiant\app\services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Models\User;
use Modules\Etudiant\Http\Requests\EtudiantRequest;
use Modules\Etudiant\Models\Etudiant;
use Tymon\JWTAuth\Facades\JWTAuth;

class EtudiantService
{
    private $ServiceName = 'EtudiantService';

    public function createStd(array $data): array
    {
        try {
            Log::info("entry");

            /*
             *    $table->string('prenom')->nullable(false);
            $table->string('nom')->nullable(false);
         //   $table->string('mot_de_passe');
            $table->string('email')->unique()->nullable();
            $table->string('telephone')->unique()->nullable();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');

            $table->date('date_naissance');


            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('identifiant_unique_portail')->unique();
            $table->foreignId('classe_id')->constrained('classes');

             * */
          //  $data['mot_de_passe'] = Hash::make('password');
            $user = User::create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'telephone'=>$data['telephone'],
                'role_id'=>$data['role_id']
            ]);

            $etudiant = $user->etudiant()->create([
                'date_naissance' => $data['date_naissance'],
                'classe_id' => $data['classe_id'],
                'identifiant_unique_portail' => 'ETU-' . strtoupper(uniqid()),
            ]);
          //  $jwt = app(JWTAuth::class);

           // $token = $jwt->fromUser($user);

            $jwt = app(\Tymon\JWTAuth\JWTAuth::class);

            $token = $jwt->fromUser($user);
            Log::info("token");


            // $token = JWTAuth::fromUser($user);


            Log::info("Création réussie de l'utilisateur ID {$user->id} et de l'étudiant ID {$etudiant->id}");

            return [
                'token' => $token,
                'user' => $user,
                'etudiant' => $etudiant->fresh(),
            ];

        } catch (\Exception $e) {
            Log::error("[$this->ServiceName] Erreur lors de la creation d'un etudiant", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
    public function getAllStudent()
    {
        try {
            $etudiants = Etudiant::with(['user', 'classe'])->get();
            return $etudiants;

        }catch (\Exception $e) {
            Log::error("[$this->ServiceName] Erreur lors du chargement des étudiants", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
    public function getStudentById(int $id){
        try {
            return Etudiant::findOrFail($id);
        }catch (\Exception $e) {
            Log::error("[$this->ServiceName] Erreur lors du chargement des étudiants", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
    public function updateStudent(array $data, int $user_id)
    {
        try {
            $user = User::findOrFail($user_id);

            if ($user === null) {
                throw new ModelNotFoundException("utilisateur avec l'id {$user_id} non trouvé");
            }


            $user->update([
                'nom'       => $data['nom'],
                'prenom'    => $data['prenom'],
                'email'     => $data['email'],
                'telephone' => $data['telephone'],
                'role_id'   => $data['role_id'],
            ]);


            $etudiant = $user->etudiant;
            if (!$etudiant) {
                throw new ModelNotFoundException("Profil étudiant introuvable pour l'utilisateur ID {$user_id}");
            }

            $etudiant->update([
                'date_naissance' => $data['date_naissance'],
                'classe_id'      => $data['classe_id'],
            ]);

            return $etudiant->load(['user', 'classe']);

        }catch (\Exception $e) {
            Log::error("[$this->ServiceName] Erreur lors de la mise à jour de l'utilisateur  avec le profil étudiant  ", [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;

        }



    }


}
