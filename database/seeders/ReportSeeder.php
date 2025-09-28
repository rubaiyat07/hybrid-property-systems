<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;

class ReportSeeder extends Seeder
{
    public function run()
    {
        Report::create([
            'type' => 'income',
            'period' => 'Monthly Income Report',
            'file_path' => null,
        ]);
    }
}
