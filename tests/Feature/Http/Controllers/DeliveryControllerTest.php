<?php

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

beforeEach(function(){
    loginUser();
});


it('can list all deliveries in index page', function () {
    $this->get('delivery')
        ->assertStatus(200)
        ->assertSee('Deliveries');
});

it("can render add delivery form page")->get('delivery/create')
    ->assertStatus(200)
    ->assertSee('Add New Delivery');


it("can create a delivery", function(){
    post('delivery', [
        'description' => 'Delivery 1',
        'materials' => [
            [
                'id' => 1,
                'quantity' => 1,
                'price_per_unit' => 100,
                'amount' => 100
            ],
            [
                'id' => 2,
                'quantity' => 1,
                'price_per_unit' => 100,
                'amount' => 100
            ],
        ]
    ])->assertSessionHasNoErrors();

    assertDatabaseHas('deliveries', ['description' => "Delivery 1"]);
});
    
