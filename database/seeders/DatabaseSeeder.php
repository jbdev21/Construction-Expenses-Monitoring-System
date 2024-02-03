<?php

namespace Database\Seeders;

use App\Models\CashOnHand;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {   
        //create admin
        \App\Models\User::factory(1)->create(['username' => 'admin']);

        $this->call([
            CategorySeeder::class,
            RolesAndPermissionsSeeder::class
        ]);


        Warehouse::create([
            'name' => 'Sara Warehouse',
            'address' => 'Sara, Iloilo',
            'contact_number' => '000-000-0000',
        ]);
        
        CashOnHand::create([
            'user_id' => User::first()->id, 
            'amount' => 2000000
        ]);
    }
}
