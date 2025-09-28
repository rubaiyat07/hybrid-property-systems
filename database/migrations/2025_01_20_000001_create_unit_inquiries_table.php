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
        Schema::create('unit_inquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id');
            $table->string('inquirer_name');
            $table->string('inquirer_email');
            $table->string('inquirer_phone')->nullable();
            $table->enum('inquiry_type', ['general_inquiry', 'booking_request', 'viewing_request']);
            $table->text('message')->nullable();
            $table->date('preferred_viewing_date')->nullable();
            $table->time('preferred_viewing_time')->nullable();
            $table->enum('status', ['pending', 'responded', 'closed'])->default('pending');
            $table->text('response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_inquiries');
    }
};
