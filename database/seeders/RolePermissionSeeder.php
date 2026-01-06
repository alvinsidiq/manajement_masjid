<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Create permissions (if any specific permissions are needed)
        // Example: Permission::create(['name' => 'edit articles']);

        // Create roles and assign existing permissions
        foreach (['admin','user','takmir','bendahara'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // You can assign permissions to roles here if needed
        // $role = Role::findByName('admin');
        // $role->givePermissionTo('edit articles');
    }
}
