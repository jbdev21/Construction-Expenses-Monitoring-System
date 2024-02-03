<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

test("user can have right permission via role", function(){
    $role = Role::create(['name' => 'admin']);
    $user = User::factory()->create(['name' => 'admin']);
    $user->assignRole($role);
    
    $permission = Permission::create(['name' => 'edit-project']);
    $role->givePermissionTo($permission);

    $this->assertTrue($user->hasPermissionTo('edit-project'));
});