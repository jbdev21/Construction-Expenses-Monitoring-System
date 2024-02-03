<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code'          => $this->faker->numerify('CODE-####'),
            'name'          => $this->faker->word(),
            'description'   => $this->faker->sentence(),
            'unit'          => $this->faker->randomElement(['pcs', 'kg', 'm', 'm2', 'm3']),
            'price'         => $this->faker->randomNumber(4, false),
            'category_id'   => ''
        ];
    }

    public function category($type)
    {
        return $this->state(function (array $attributes) use ($type) {
            return [
                'category_id' => optional(Category::whereType($type)->inRandomOrder()->first())->id
            ];
        });
    }
}
