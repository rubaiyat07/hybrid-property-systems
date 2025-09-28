<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;

class ExpenseSeeder extends Seeder
{
    public function run()
    {
        Expense::create([
            'property_id' => 1,
            'description' => 'Maintenance cost',
            'amount' => 5000.00,
            'date' => now(),
            'category' => 'maintenance',
        ]);
    }
}
