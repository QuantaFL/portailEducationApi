<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\User;
use Modules\Auth\Database\Factories\UserFactory;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $roles = [
            'admin' => 'Administrateur',
            'enseignant' => 'Enseignant',
            'eleve' => 'Élève',
            'parent' => 'ParentModule',
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
                [
                    'first_name' => ucfirst($code),
                    'last_name' => strtoupper($code),
                    'email' => $code . '@example.com',
                    'phone' => '01' . rand(10000000, 99999999),
                    'date_of_birth' => now()->subYears(rand(18, 60))->format('Y-m-d'),
                    'gender' => $code === 'admin' ? 'Male' : 'Female',
                    'address' => $faker->address,
                    'password' => bcrypt('password'),
                    'role_id' => $role->id,
                ]
            );
        }

        User::factory(10)->create();
    }
}
