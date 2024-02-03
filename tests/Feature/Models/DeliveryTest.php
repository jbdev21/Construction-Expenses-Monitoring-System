<?php

use App\Models\Category;
use App\Models\Delivery;
use App\Models\Material;
use App\Models\Project;
use App\Models\Warehouse;
use Database\Seeders\CategorySeeder;


it("can has from warehouse and to project", function(){
    (new CategorySeeder())->run();
    // create project to be attached
    $project = new Project;
    $project->name = "Sample Project";
    $project->contract_id = "haha";
    $project->contral_amount = random_int(5, 100000);
    $project->ntp_date = now();
    $project->project_duration_years = 1;
    $project->project_duration_months = 1;
    $project->project_duration_days = 1;
    $project->expiry_date = now()->addDays(15);
    $project->contractor_licence = "hehe";
    $project->status = "ongoing";
    $project->category_id = Category::whereType('project')->first()->id;
    $project->save();

    // create warehouse to be attached
    $warehouse = Warehouse::factory()->create(['name' => 'Default Warehouse']);
    
    // attach to delivery
    $delivery = new Delivery;
    $delivery->description = "sample";
    $delivery->setFromAndTo($warehouse, $project);
    $delivery->save();
 
    $this->assertEquals($delivery->deliverableFrom->name, "Default Warehouse");
    $this->assertEquals($delivery->deliverableTo->name, "Sample Project");
});


it("can has materials and correct quantity", function(){
    (new CategorySeeder())->run();
    // create project to be attached
    $project = new Project;
    $project->name = "Sample Project";
    $project->contract_id = "haha";
    $project->contral_amount = random_int(5, 100000);
    $project->ntp_date = now();
    $project->project_duration_years = 1;
    $project->project_duration_months = 1;
    $project->project_duration_days = 1;
    $project->expiry_date = now()->addDays(15);
    $project->contractor_licence = "hehe";
    $project->status = "ongoing";
    $project->category_id = Category::whereType('project')->first()->id;
    $project->save();

    // create warehouse to be attached
    $warehouse = Warehouse::factory()->create(['name' => 'Default Warehouse']);
   
    // create materials
    $materials = Material::factory(10)->category('material')->create();
    
    // attach to delivery
    $delivery = new Delivery;
    $delivery->description = "sample";
    $delivery->setFromAndTo($warehouse, $project);
    $delivery->amount = 0;
    $delivery->save();

    foreach($materials as $material){
        $quantity = 1;
        $price_per_unit = 10;
        $amount = $price_per_unit * $quantity;
        $delivery
            ->materials()
            ->attach([
                $material->id => [
                    'quantity' => $quantity,
                    'price_per_unit' => $price_per_unit,
                    'amount' => $amount,
                    ]]);
    }

    $updatedDelivery = $delivery->fresh();

    $this->assertEquals($updatedDelivery->materials->count(), 10);
    $this->assertEquals($updatedDelivery->amount, 100);
});