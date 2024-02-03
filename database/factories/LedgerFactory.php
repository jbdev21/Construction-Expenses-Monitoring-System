<?php

namespace Database\Factories;

use App\Models\Personnel;
use App\Models\PettyCash;
use Illuminate\Database\Eloquent\Factories\Factory;


// $table->id();
// $table->string('payee');
// $table->unsignedBigInteger('personnel_id')->nullable();
// $table->unsignedBigInteger('petty_cash_id')->nullable();
// $table->string('description')->nullable();
// $table->double('amount');
// $table->string('type')->default('debit');
// $table->date('effectivity_date');
// $table->double('onhand_amount')->nullable();
// $table->timestamps();

class LedgerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->catchPhrase(),
            'amount' => random_int(100, 5000),
            'effectivity_date' => now(),
        ];
    }


    public function personnel(Personnel $personnel)
    {
        return $this->state(function (array $attributes) use ($personnel) {
            return [
                'personnel_id' => $personnel->id,
            ];
        });
    }

    
    public function pettyCash(PettyCash $pettyCash)
    {
        return $this->state(function (array $attributes) use ($pettyCash) {
            return [
                'petty_cash_id_id' => $pettyCash->id,
            ];
        });
    }


    public function debit()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'debit',
            ];
        });
    }

    public function credit()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'credit',
            ];
        });
    }
}
