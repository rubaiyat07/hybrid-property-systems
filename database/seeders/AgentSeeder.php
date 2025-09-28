<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agent;
use App\Models\User;

class AgentSeeder extends Seeder
{
    public function run()
    {
        $agents = User::role('Agent')->get();

        foreach ($agents as $user) {
            Agent::create([
                'user_id' => $user->id,
                'commission_rate' => 5.00,
                'license_no' => 'LIC-' . strtoupper(substr($user->name, 0, 3)) . rand(1000, 9999),
            ]);
        }
    }
}
