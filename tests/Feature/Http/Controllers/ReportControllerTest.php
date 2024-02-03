<?php

use App\Http\Controllers\PettyCashController;
use App\Http\Controllers\ReportController;
use App\Models\CashOnHand;
use App\Models\Ledger;
use App\Models\Personnel;
use Carbon\Carbon;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;
use function PHPUnit\Framework\assertEquals;

beforeEach(function(){
    $this->withoutExceptionHandling();
    loginUser();
    CashOnHand::create(['amount' => 1000, 'user_id' => 1]);
});

it("can provide correct cash history", function(){
    CashOnHand::query()->first()->update(['amount' => 1000]);
    assertDatabaseCount(CashOnHand::class, 1);
    $this->travelTo(Carbon::make("2022-06-09"));
        for($i = 0; $i < 9; $i++){
            Ledger::factory()
                ->debit()
                ->create(['amount' => 100]);
        } // COH = 100

        for($i = 0; $i < 5; $i++){
            Ledger::factory()
                ->credit()
                ->create(['amount' => 200]);
        } // COH 1100

        assertEquals(CashOnHand::first()->amount, 1100);


    $this->travelTo(Carbon::make("2022-06-10"));
        assertEquals(CashOnHand::first()->amount, 1100);

        for($i = 0; $i < 5; $i++){
            Ledger::factory()
                ->debit()
                ->create(['amount' => 100]);
        } // COH = 600

        for($i = 0; $i < 10; $i++){
            Ledger::factory()
                ->credit()
                ->create(['amount' => 100]);
        } // COH  = 1600
        
        assertEquals(Carbon::make("2022-06-10"), now());
        assertEquals(CashOnHand::first()->amount, 1600);

    $this->travelTo(Carbon::make("2022-06-11"));
        $personnel = Personnel::factory()
            ->currentCashAdvance(0)
            ->create();

        post(action([PettyCashController::class, 'salarySubmit']), [
            'payee' => 'sample',
            'personnel_id' => $personnel->id,
            'salary' => 100,
            'effectivity_date' => now(),
            'items' => ['Helper 1','Helper 2'],
            'amount' => [100, 100]
        ])->assertSessionHasNoErrors(); // deduct 300

        assertDatabaseHas(Ledger::class, ['amount' => 300]);
        assertDatabaseHas(CashOnHand::class, ['amount' => 1300]); 
        assertDatabaseHas(Personnel::class, ['current_cash_advance' => 0]);
    
    $this->travelTo(Carbon::make("2022-06-13"));
        assertEquals(Ledger::query()->latest()->first()->onhand_amount, 1300);

    $this->get(action([ReportController::class, 'casher']))
            ->assertSee([toPeso(1300), toPeso(1300)]);
    
    assertDatabaseCount(CashOnHand::class, 1);
});
