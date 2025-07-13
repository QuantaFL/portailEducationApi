<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\User;
use Modules\Auth\Database\Factories\UserFactory;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin' => 'Administrateur',
            'enseignant' => 'Enseignant',
            'eleve' => 'Élève',
            'parent' => 'Parent',
        ];

        foreach ($roles as $code => $libelle) {
            Role::firstOrCreate(
                ['code_role' => $code],
                ['libelle_role' => $libelle]
            );
        }

        foreach ($roles as $code => $libelle) {
            $role = Role::where('code_role', $code)->first();

            User::firstOrCreate(
               // ['nom_utilisateur' => $code],
                [
                    'prenom' => ucfirst($code),
                    'nom' => strtoupper($code),
                    'email' => $code . '@example.com',
                    'telephone' => '01' . rand(10000000, 99999999),
                  //  'mot_de_passe' => bcrypt('password'),
                    'role_id' => $role->id,
                ]
            );
        }

        User::factory(10)->create();
    }
}
