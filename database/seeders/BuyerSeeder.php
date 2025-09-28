<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buyer;
use App\Models\User;

class BuyerSeeder extends Seeder
{
    public function run()
    {
        $buyers = User::role('Buyer')->get();

        foreach ($buyers as $user) {
            Buyer::create([
                'user_id' => $user->id,
                'contact_info' => 'Phone: ' . $user->phone,
                'preferences' => json_encode(['type' => 'house', 'budget' => '5000000']),
            ]);
        }
    }
}
