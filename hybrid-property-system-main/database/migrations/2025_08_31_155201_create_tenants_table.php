<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('emergency_contact')->nullable();
            $table->boolean('is_screened')->default(false);
            $table->date('move_in_date')->nullable();
            $table->date('move_out_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('tenants');
    }
};
