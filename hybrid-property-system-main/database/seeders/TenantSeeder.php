<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;

class TenantSeeder extends Seeder
{
    public function run()
    {
        $tenants = User::role('Tenant')->get();

        foreach ($tenants as $user) {
            Tenant::create([
                'user_id' => $user->id,
                'emergency_contact' => '01700000000',
                'is_screened' => true,
                'move_in_date' => now()->subMonths(3),
                'move_out_date' => null,
            ]);
        }
    }
}
