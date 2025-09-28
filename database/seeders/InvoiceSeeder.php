<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;

class InvoiceSeeder extends Seeder
{
    public function run()
    {
        $lease = \App\Models\Lease::first();
        if ($lease) {
            Invoice::create([
                'lease_id' => $lease->id,
                'tenant_id' => $lease->tenant_id,
                'amount' => 50000.00,
                'due_date' => now()->addMonth(),
                'status' => 'unpaid',
            ]);
        }
    }
}
