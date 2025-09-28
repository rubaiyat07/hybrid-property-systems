<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceRequest;
use App\Models\Tenant;
use App\Models\Unit;

class MaintenanceRequestSeeder extends Seeder
{
    public function run()
    {
        $tenants = Tenant::all();
        $units = Unit::all();

        foreach ($tenants as $index => $tenant) {
            if ($units->count() > $index) {
                MaintenanceRequest::create([
                    'tenant_id' => $tenant->id,
                    'unit_id' => $units[$index]->id,
                    'description' => 'Leaky faucet in kitchen',
                    'priority' => 'medium',
                    'status' => 'pending',
                ]);
            }
        }
    }
}
