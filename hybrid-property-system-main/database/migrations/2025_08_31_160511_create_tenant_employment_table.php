<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('tenant_employment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('employer_name');
            $table->string('position');
            $table->decimal('monthly_income', 10, 2)->nullable();
            $table->string('contact_info')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('tenant_employment');
    }
};
