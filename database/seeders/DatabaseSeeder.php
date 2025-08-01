<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Auth\Database\Seeders\AuthDatabaseSeeder;
use Modules\Auth\Database\Seeders\UserSeeder;
use Modules\Auth\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       /*
        *  User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        * */
        $this->call([
            AuthDatabaseSeeder::class
        ]);
    }
}
