<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AllTablesSeeder extends Seeder
{
    /**
     * Run all seeders for the application.
     *
     * @return void
     */
    public function run()
    {
        // Call individual seeders here
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PropertySeeder::class,
            TenantSeeder::class,
            LeaseSeeder::class,
            PaymentSeeder::class,
            MaintenanceRequestSeeder::class,
            AgentSeeder::class,
            BuyerSeeder::class,
            TransactionSeeder::class,
            LeadSeeder::class,
            NotificationSeeder::class,
            MessageSeeder::class,
            ReportSeeder::class,
            ExpenseSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
