<?php

use App\Http\Controllers\ExpenseController;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Project;

use function Pest\Laravel\assertDatabaseHas;
use function PHPUnit\Framework\assertEquals;

beforeEach(function(){
    $this->withoutExceptionHandling();
    loginUser();
});


it('can add expense', function () {
    $this->post(action([ExpenseController::class, 'store']),[
        'items' => "sample",
        'amount' => 1,
        'unit_quantity' => 1,
        'unit_price' => 1,
        'type' => "labor",
        'effectivity_date' => now()
    ])->assertSessionHasNoErrors();
    
    assertDatabaseHas(Expense::class, ['items' => 'sample']);
});


it('can add expense in a project', function () {
    $category = Category::factory()->projects()->create();

    $project = Project::factory()->create([
            'category_id' => $category->id
        ]);

    $this->post(action([ExpenseController::class, 'store']),[
        'items' => "sample",
        'amount' => 1,
        'unit_quantity' => 1,
        'unit_price' => 1,
        'type' => "labor",
        'effectivity_date' => now(),
        'project_id' => $project->id
    ])->assertSessionHasNoErrors();
    
    $freshedProject = $project->fresh();
    assertEquals($freshedProject->expenses()->count(), 1);
    assertDatabaseHas(Expense::class, ['items' => 'sample']);
});
