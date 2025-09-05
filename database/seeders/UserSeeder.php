<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Roles using Spatie
        $roles = ['Admin', 'Landlord', 'Tenant', 'Agent', 'Buyer', 'Maintenance'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Default Users with role_id mapping
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'phone' => '01710000001',
                'password' => Hash::make('admin123'),
                'role' => 'Admin',
            ],
            [
                'name' => 'John Landlord',
                'email' => 'landlord@example.com',
                'phone' => '01710000002',
                'password' => Hash::make('landlord123'),
                'role' => 'Landlord',
            ],
            [
                'name' => 'Jane Tenant',
                'email' => 'tenant@example.com',
                'phone' => '01710000003',
                'password' => Hash::make('tenant123'),
                'role' => 'Tenant',
            ],
            [
                'name' => 'Alex Agent',
                'email' => 'agent@example.com',
                'phone' => '01710000004',
                'password' => Hash::make('agent123'),
                'role' => 'Agent',
            ],
            [
                'name' => 'Bob Buyer',
                'email' => 'buyer@example.com',
                'phone' => '01710000005',
                'password' => Hash::make('buyer123'),
                'role' => 'Buyer',
            ],
            [
                'name' => 'Mike Maintenance',
                'email' => 'maintenance@example.com',
                'phone' => '01710000006',
                'password' => Hash::make('maintenance123'),
                'role' => 'Maintenance',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'password' => $data['password'],
                    'role_id' => null, // যদি পরে DB relation করতে চান
                ]
            );

            $user->assignRole($data['role']);
        }
    }
}
