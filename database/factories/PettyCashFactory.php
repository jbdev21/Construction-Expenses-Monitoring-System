<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PettyCashFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'payee' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'amount' => $this->faker->randomFloat(2, 0, 100),
            'type' => $this->faker->randomElement(['others', 'labor', 'materials']),
            'effectivity_date' => now(),
            'personnel_id' => $this->faker->randomElement([null]),

        ];
    }
}
