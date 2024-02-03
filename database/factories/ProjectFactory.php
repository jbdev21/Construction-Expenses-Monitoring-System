<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'                      => $this->faker->name(),
            'contract_id'               => $this->faker->ean13(),
            'contractor_licence'        => $this->faker->ean13(),
            'contract_amount'           => $this->faker->ean8(),
            'ntp_date'                  => now()->addYears()->format('Y-m-d'),
            'project_duration_years'    => $this->faker->randomNumber(1, 2),
            'project_duration_months'   => $this->faker->randomNumber(1, 2),
            'project_duration_days'     => $this->faker->randomNumber(1, 15),
            'expiry_date'               => now()->addYears()->format('Y-m-d'),
            'project_year'              => now()->format('Y'),
            'category_id'               => null
        ];
    }
}
