<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\Property;

class UnitSeeder extends Seeder
{
    public function run()
    {
        $properties = Property::all();

        foreach ($properties as $property) {
            Unit::create([
                'property_id' => $property->id,
                'unit_number' => '101',
                'floor' => '1',
                'size' => '1000 sq ft',
                'rent_amount' => 50000.00,
                'status' => 'occupied',
                'features' => json_encode(['balcony', 'parking']),
            ]);

            Unit::create([
                'property_id' => $property->id,
                'unit_number' => '102',
                'floor' => '1',
                'size' => '1200 sq ft',
                'rent_amount' => 60000.00,
                'status' => 'vacant',
                'features' => json_encode(['garden', 'parking']),
            ]);
        }
    }
}
