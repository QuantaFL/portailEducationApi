<?php

namespace Modules\Auth\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\Role;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Auth\Models\User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nom_utilisateur' => $this->faker->unique()->userName,
            'mot_de_passe' => bcrypt('passer'),
            'email' => $this->faker->unique()->safeEmail,
            'telephone' => $this->faker->unique()->phoneNumber,
            'role_id' => Role::count() ? Role::pluck('id')->random() : 1,
        ];
    }

}

