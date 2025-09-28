<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'phone' => '01700000000',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->assignRole('Admin');

        // Landlord user
        $landlord = User::updateOrCreate(
            ['email' => 'landlord@example.com'],
            [
                'name' => 'John Landlord',
                'phone' => '01711111111',
                'password' => Hash::make('password123'),
            ]
        );
        $landlord->assignRole('Landlord');

        $landlord = User::updateOrCreate(
            ['email' => 'landlord2@example.com'],
            [
                'name' => 'Larry Landlord',
                'phone' => '01711114311',
                'password' => Hash::make('password123'),
            ]
        );
        $landlord->assignRole('Landlord');

        // Tenant user
        $tenant = User::updateOrCreate(
            ['email' => 'tenant@example.com'],
            [
                'name' => 'Jane Tenant',
                'phone' => '01722222222',
                'password' => Hash::make('password123'),
            ]
        );
        $tenant->assignRole('Tenant');

        $tenant = User::updateOrCreate(
            ['email' => 'tenant2@example.com'],
            [
                'name' => 'Austin Tenant',
                'phone' => '01722222223',
                'password' => Hash::make('password123'),
            ]
        );
        $tenant->assignRole('Tenant');

        $tenant = User::updateOrCreate(
            ['email' => 'tenant3@example.com'],
            [
                'name' => 'Margarette Tenant',
                'phone' => '01722222224',
                'password' => Hash::make('password123'),
            ]
        );
        $tenant->assignRole('Tenant');

        // Agent user
        $agent = User::updateOrCreate(
            ['email' => 'agent@example.com'],
            [
                'name' => 'Alex Agent',
                'phone' => '01733333333',
                'password' => Hash::make('password123'),
            ]
        );
        $agent->assignRole('Agent');

        // Buyer user
        $buyer = User::updateOrCreate(
            ['email' => 'buyer@example.com'],
            [
                'name' => 'Brian Buyer',
                'phone' => '01744444444',
                'password' => Hash::make('password123'),
            ]
        );
        $buyer->assignRole('Buyer');

        // Maintenance user
        $maintenance = User::updateOrCreate(
            ['email' => 'maintenance@example.com'],
            [
                'name' => 'Mark Maintenance',
                'phone' => '01755555555',
                'password' => Hash::make('password123'),
            ]
        );
        $maintenance->assignRole('Maintenance');
    }
}
