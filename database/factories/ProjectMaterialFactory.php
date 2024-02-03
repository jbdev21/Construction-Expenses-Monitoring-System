<?php

namespace Database\Factories;

use App\Models\Material;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectMaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'project_id' => Project::inRandomOrder()->first()->id,
            'material_id' => Material::inRandomOrder()->first()->id,
            'quantity' => $quantity = $this->faker->randomDigit(),
            'price' => $price = $this->faker->randomFloat(2, 0, 100),
            'total_price' => $quantity * $price,
        ];
    }
}
