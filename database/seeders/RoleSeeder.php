<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Default Roles
        $roles = [
            'Admin',
            'Landlord',
            'Tenant',
            'Agent',
            'Buyer',
            'Maintenance'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web', // Ensure correct guard
            ]);
        }

        // Example Permissions (optional)
        $permissions = [
            'manage properties',
            'manage tenants',
            'manage leases',
            'manage payments',
            'approve documents',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web', // Ensure correct guard
            ]);
        }

        // Assign all permissions to Admin
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo(Permission::all());
        }
    }
}
