<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            // Add new columns for tenant inquiries
            $table->string('inquirer_type')->default('buyer')->after('id'); // 'buyer' or 'tenant'
            $table->unsignedBigInteger('inquirer_id')->nullable()->after('inquirer_type'); // buyer_id or tenant_id
            $table->unsignedBigInteger('unit_id')->nullable()->after('property_id'); // for tenant inquiries
            $table->string('inquiry_type')->default('property_purchase')->after('status'); // 'property_purchase' or 'rental_inquiry'
            $table->text('message')->nullable()->after('inquiry_type');
            $table->json('contact_info')->nullable()->after('message');

            // Modify existing columns if needed
            $table->unsignedBigInteger('buyer_id')->nullable()->change(); // Make nullable since we'll use inquirer_id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['inquirer_type', 'inquirer_id', 'unit_id', 'inquiry_type', 'message', 'contact_info']);
            $table->unsignedBigInteger('buyer_id')->nullable(false)->change(); // Revert nullable
        });
    }
};
