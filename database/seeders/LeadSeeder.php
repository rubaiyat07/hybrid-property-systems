<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\Buyer;
use App\Models\Property;
use App\Models\User;

class LeadSeeder extends Seeder
{
    public function run()
    {
        $buyers = Buyer::all();
        $properties = Property::all();
        $agents = User::role('Agent')->get();

        foreach ($buyers as $index => $buyer) {
            if ($properties->count() > $index) {
                Lead::create([
                    'buyer_id' => $buyer->id,
                    'property_id' => $properties[$index]->id,
                    'assigned_to' => $agents->count() > $index ? $agents[$index]->id : null,
                    'status' => 'new',
                    'notes' => 'Interested in viewing the property.',
                ]);
            }
        }
    }
}
