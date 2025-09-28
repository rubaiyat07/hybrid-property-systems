<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Property;
use App\Models\Buyer;
use App\Models\Agent;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $properties = Property::all();
        $buyers = Buyer::all();
        $agents = Agent::all();

        foreach ($properties as $index => $property) {
            if ($buyers->count() > $index) {
                Transaction::create([
                    'property_id' => $property->id,
                    'buyer_id' => $buyers[$index]->id,
                    'agent_id' => $agents->count() > $index ? $agents[$index]->id : null,
                    'amount' => $property->price_or_rent ?? 1000000,
                    'payment_status' => 'paid',
                    'agreement_path' => null,
                    'payment_milestones' => json_encode(['initial' => 500000, 'final' => 500000]),
                ]);
            }
        }
    }
}
