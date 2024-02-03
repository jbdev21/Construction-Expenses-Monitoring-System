<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //categories for projects
        Category::factory()->projects()->count(5)->create();

        //categories for materials
        Category::factory()->materials()->count(5)->create();

        //categories for materials
        Category::factory()->equipments()->count(5)->create();
        
    }
}
