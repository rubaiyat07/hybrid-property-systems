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
        // Creating Roles using Spatie
        $roles = ['Admin', 'Landlord', 'Tenant', 'Agent', 'Buyer', 'Maintenance'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Default Users with role mapping
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'phone' => '01710000001',
                'password' => Hash::make('admin123'),
                'role' => 'Admin',
            ],
            [
                'name' => 'Aftab Alamgir',
                'email' => 'landlord@example.com',
                'phone' => '01710000002',
                'password' => Hash::make('landlord123'),
                'role' => 'Landlord',
            ],
            [
                'name' => 'Jannat Ara',
                'email' => 'tenant@example.com',
                'phone' => '01710000003',
                'password' => Hash::make('tenant123'),
                'role' => 'Tenant',
            ],
            [
                'name' => 'Hasanuzzaman Khan',
                'email' => 'agent@example.com',
                'phone' => '01710000004',
                'password' => Hash::make('agent123'),
                'role' => 'Agent',
            ],
            [
                'name' => 'Bashir Ahmed',
                'email' => 'buyer@example.com',
                'phone' => '01710000005',
                'password' => Hash::make('buyer123'),
                'role' => 'Buyer',
            ],
            [
                'name' => 'Kalam Ali',
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
                ]
            );

            // Assign role using Spatie
            $user->assignRole($data['role']);
        }
    }
}
