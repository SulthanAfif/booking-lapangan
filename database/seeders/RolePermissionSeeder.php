<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage fields',
            'manage bookings',
            'view all bookings',
            'view own bookings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);

        $staff = Role::firstOrCreate(['name' => 'staff']);
        $staff->syncPermissions(['manage bookings', 'view all bookings']);

        $customer = Role::firstOrCreate(['name' => 'customer']);
        $customer->syncPermissions(['view own bookings']);
    }
}