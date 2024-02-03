<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => ucfirst($this->faker->word()),
            'type' => ''
        ];
    }


    public function projects()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'project',
            ];
        });
    }

    public function materials()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'material',
            ];
        });
    }

    public function equipments()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'equipment',
            ];
        });
    }
}
