<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(
    Tests\TestCase::class, 
    LazilyRefreshDatabase::class
    )->in('Feature');


expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});


function loginUser($user = null, $isApi = false)
{
    $user = $user ?? User::factory()->create();
    return test()->actingAs($user, $isApi ? 'api' : '');
}


