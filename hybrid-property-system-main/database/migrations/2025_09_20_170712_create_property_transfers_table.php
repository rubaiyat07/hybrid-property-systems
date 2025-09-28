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
        Schema::create('property_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('current_owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('proposed_buyer_id')->constrained('users')->onDelete('cascade');
            $table->enum('transfer_type', ['sale', 'lease_transfer', 'ownership_transfer']);
            $table->decimal('proposed_price', 15, 2)->nullable();
            $table->date('transfer_date');
            $table->text('terms_conditions');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('initiated_at');
            $table->timestamp('buyer_response_at')->nullable();
            $table->text('buyer_response_notes')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('completion_notes')->nullable();
            $table->timestamps();

            $table->index(['property_id', 'status']);
            $table->index(['current_owner_id', 'status']);
            $table->index(['proposed_buyer_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_transfers');
    }
};
