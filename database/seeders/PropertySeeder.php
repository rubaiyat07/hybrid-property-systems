<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\User;

class PropertySeeder extends Seeder
{
    public function run()
    {
        $landlord = User::where('email', 'landlord@example.com')->first();

        if ($landlord) {
            Property::create([
                'owner_id' => $landlord->id,
                'name' => 'Sunny Apartments',
                'address' => '123 Main St',
                'city' => 'Dhaka',
                'state' => 'Dhaka',
                'zip_code' => '1200',
                'type' => 'apartment',
                'status' => 'rent',
                'description' => 'A beautiful apartment complex with modern amenities.',
                'price_or_rent' => 50000.00,
            ]);

            Property::create([
                'owner_id' => $landlord->id,
                'name' => 'Green Villa',
                'address' => '456 Oak Ave',
                'city' => 'Chittagong',
                'state' => 'Chittagong',
                'zip_code' => '4000',
                'type' => 'house',
                'status' => 'sale',
                'description' => 'Spacious villa with garden.',
                'price_or_rent' => 5000000.00,
            ]);

            Property::create([
                'owner_id' => $landlord->id,
                'name' => 'Commercial Plaza',
                'address' => '789 Business Rd',
                'city' => 'Sylhet',
                'state' => 'Sylhet',
                'zip_code' => '3100',
                'type' => 'commercial',
                'status' => 'rent',
                'description' => 'Prime commercial space for retail.',
                'price_or_rent' => 100000.00,
            ]);
        }
    }
}
