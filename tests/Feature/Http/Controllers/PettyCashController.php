<?php

use App\Http\Controllers\PettyCashController;
use App\Models\CashAdvance;
use App\Models\CashOnHand;
use App\Models\Ledger;
use App\Models\Personnel;
use Carbon\Carbon;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;


beforeEach(function(){
    $this->withoutExceptionHandling();
    loginUser();
    CashOnHand::create(['amount' => 1000, 'user_id' => 1]);
});


it("can render index page")
    ->get('petty-cash')
    ->assertStatus(200);
    

it("can add new petty cash", function(){
    $this->travel(Carbon::make("2022-06-17"));
    $this->withoutExceptionHandling();
    CashOnHand::first()->update(['amount' => 714729]);

    post(action([PettyCashController::class, 'store']), [
        'payee' => 'ALIMODIAN POB/MAMBAWI/DESAMPARADOS/CALAJUNAN/VILLA MOLO',
        'effectivity_date' => now(),
        'items' => [
            'SALARY ERNIE DALE SECONG JUNE 1-15,2022'
        ],
        'category' => [
            'labor'
        ],
        'amount' => [7800]
    ]);

    assertDatabaseHas(Ledger::class, ['description' => 'SALARY ERNIE DALE SECONG JUNE 1-15,2022']);
    assertDatabaseHas(CashOnHand::class, ['amount' => 706929]); // check if cashonhand is deducted (1000 - 100 = 900)
    assertDatabaseHas(Ledger::class, ['amount' => 7800]); 
});

it("can add new petty cash and cash advance if personnel_id is provided", function(){
    
    $personnel = Personnel::factory()
                ->currentCashAdvance(0)
                ->create();

    post(action([PettyCashController::class, 'store']), [
        'payee' => 'sample',
        'personnel_id' => $personnel->id,
        'effectivity_date' => now(),
        'items' => [
            'something'
        ],
        'category' => [
            'labor'
        ],
        'amount' => [100]
    ]);

    assertDatabaseHas(Ledger::class, ['description' => 'something']);
    assertDatabaseHas(CashOnHand::class, ['amount' => 900]); // check if cashonhand is deducted (1000 - 100 = 900)
    assertDatabaseHas(CashAdvance::class, ['amount' => 100, 'personnel_id' => $personnel->id]); // check if cashonhand is deducted (1000 - 100 = 900)
    assertDatabaseHas(Ledger::class, ['amount' => 100]); 
});


it("it can create petty cash for salary", function(){
    
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
    ])->assertSessionHasNoErrors();

    assertDatabaseCount(Ledger::class, 1);
    assertDatabaseHas(Ledger::class, ['amount' => 300]);
    assertDatabaseHas(CashOnHand::class, ['amount' => 700]); 
    assertDatabaseHas(Personnel::class, ['current_cash_advance' => 0]); 
});



it("it can create petty cash for cash advance", function(){
    $personnel = Personnel::factory()
                ->currentCashAdvance(500)
                ->create();
            
    post(action([PettyCashController::class, 'store']), [
        'payee' => 'sample',
        'personnel_id' => $personnel->id,
        'effectivity_date' => now(),
        'items' => [
            'something'
        ],
        'category' => [
            'labor'
        ],
        'amount' => [100]
    ]);

    assertDatabaseHas(Ledger::class, ['description' => 'something', 'amount' => 100]);
    assertDatabaseHas(CashOnHand::class, ['amount' => 900]); 
    assertDatabaseHas(Personnel::class, ['current_cash_advance' => 600]); 
});
   
