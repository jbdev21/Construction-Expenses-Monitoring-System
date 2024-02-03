<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionGroups = config('system.roles_permissions');

        foreach ($permissionGroups as $group => $permissions) {
            foreach($permissions as $permission) {
                $permission = Permission::firstOrCreate(['name' => $permission]);
            }
        }

        $role = Role::create(['name' => 'Administrator']);
        $role->givePermissionTo(Permission::all());

        // asign user to as administrator
        $user = \App\Models\User::first();
        $user->assignRole($role);
    }
}
