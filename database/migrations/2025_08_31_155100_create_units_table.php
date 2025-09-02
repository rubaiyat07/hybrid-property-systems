<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->string('unit_number');
            $table->string('floor')->nullable();
            $table->string('size')->nullable();
            $table->decimal('rent_amount', 10, 2)->nullable();
            $table->enum('status', ['vacant', 'occupied'])->default('vacant');
            $table->json('features')->nullable();
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties')->cascadeOnDelete();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('units');
    }
};
