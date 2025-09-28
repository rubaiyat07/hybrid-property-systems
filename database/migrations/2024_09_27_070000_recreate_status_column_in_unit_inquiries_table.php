<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE unit_inquiries DROP COLUMN status');

        \Illuminate\Support\Facades\DB::statement('ALTER TABLE unit_inquiries ADD COLUMN status ENUM("pending", "responded", "approved", "leased", "closed") DEFAULT "pending" AFTER id');

        // Update existing records if needed
        \Illuminate\Support\Facades\DB::table('unit_inquiries')->update(['status' => 'pending']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE unit_inquiries DROP COLUMN status');

        \Illuminate\Support\Facades\DB::statement('ALTER TABLE unit_inquiries ADD COLUMN status ENUM("pending", "responded", "closed") DEFAULT "pending" AFTER id');
    }
};
