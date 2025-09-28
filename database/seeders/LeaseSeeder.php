<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Unit;

class LeaseSeeder extends Seeder
{
    public function run()
    {
        $tenants = Tenant::all();
        $units = Unit::where('status', 'occupied')->get();

        foreach ($tenants as $index => $tenant) {
            if ($units->count() > $index) {
                Lease::create([
                    'tenant_id' => $tenant->id,
                    'unit_id' => $units[$index]->id,
                    'start_date' => now()->subMonths(3),
                    'end_date' => now()->addMonths(9),
                    'rent_amount' => 50000.00,
                    'deposit' => 100000.00,
                    'status' => 'active',
                    'document_path' => null,
                ]);
            }
        }
    }
}
