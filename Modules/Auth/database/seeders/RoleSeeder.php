<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Auth\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['code_role' => 'admin', 'libelle_role' => 'Administrateur'],
            ['code_role' => 'enseignant', 'libelle_role' => 'Enseignant'],
            ['code_role' => 'eleve', 'libelle_role' => 'Élève'],
            ['code_role' => 'parent', 'libelle_role' => 'Parent'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['code_role' => $role['code_role']], $role);
        }
    }
}
