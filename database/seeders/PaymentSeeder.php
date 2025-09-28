<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Lease;
use App\Models\Tenant;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $leases = Lease::all();

        foreach ($leases as $lease) {
            Payment::create([
                'lease_id' => $lease->id,
                'tenant_id' => $lease->tenant_id,
                'amount' => $lease->rent_amount,
                'date' => now()->subMonth(),
                'method' => 'credit_card',
                'status' => 'paid',
            ]);

            Payment::create([
                'lease_id' => $lease->id,
                'tenant_id' => $lease->tenant_id,
                'amount' => $lease->rent_amount,
                'date' => now(),
                'method' => 'bank_transfer',
                'status' => 'pending',
            ]);
        }
    }
}
