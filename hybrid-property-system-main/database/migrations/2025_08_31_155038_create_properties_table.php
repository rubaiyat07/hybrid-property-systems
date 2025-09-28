<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip')->nullable();
            $table->enum('type', ['apartment', 'house', 'condo', 'townhouse','residential', 'commercial', 'land', 'plot', 'other']);
            $table->enum('status', ['rent', 'sale'])->default('rent');
            $table->text('description')->nullable();
            $table->decimal('price_or_rent', 15, 2)->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('properties');
    }
};
