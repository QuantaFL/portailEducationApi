<?php

namespace Modules\Classes\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Classes\Models\Classes;

class ClassesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Classes::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'academic_year' => $this->faker->year,
        ];
    }
}
