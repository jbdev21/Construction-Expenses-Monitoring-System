<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
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
            'address' => $this->faker->address(),
            'contact_number' => $this->faker->phoneNumber(),
            'is_active' => true,
        ];
    }

}
