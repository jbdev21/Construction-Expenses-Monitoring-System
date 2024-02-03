<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $quantity = random_int(1, 5);
        $amount = random_int(10, 1500);
        return [
            'items' => $this->faker->text(),
            'unit_quantity' => $quantity,
            'unit_price' => $amount,
            'amount' => $amount * $quantity,
            'type' => ''
        ];
    }

    public function inRandomDateTime($date){
        return $this->state(function (array $attributes) use ($date) {
            return [
                'created_at' => $data ?? now()->subDays(random_int(1, 30))->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function type($type)
    {
        return $this->state(function (array $attributes) use ($type) {
            return [
                'type' => $type ? $type : strtolower($this->faker->randomElement(config('system.category.expenses')))
            ];
        });
    }

    public function project($project = ""){
        return $this->state(function (array $attributes) use ($project) {
            return [
                'expensable_type' => "App\Models\Project",
                'expensable_id' => $project ? $project : Project::inRandomOrder()->first()->id
            ];
        });
    }
}
