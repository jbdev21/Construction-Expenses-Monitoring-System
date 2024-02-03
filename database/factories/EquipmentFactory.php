<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'code' => $this->faker->numerify('CODE-####'),
            'plate_number' => $this->faker->numerify('PLATE-####'),
            'rate_per_hour' => $this->faker->randomNumber(4, true),
            'status' => $this->faker->randomElement(['functional', 'non-functional']),
            'description' => $this->faker->sentence(),
            'category_id' => ''
        ];
    }
}
