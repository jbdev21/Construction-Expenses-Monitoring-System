<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PersonnelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'address' => $this->faker->address(),
            'contact_number' => $this->faker->phoneNumber(),
            'description' => $this->faker->sentence(),
        ];
    }

    public function currentCashAdvance($amount)
    {
        return $this->state(function (array $attributes) use ($amount) {
            return [
                'current_cash_advance' => $amount,
            ];
        });
    }
}
