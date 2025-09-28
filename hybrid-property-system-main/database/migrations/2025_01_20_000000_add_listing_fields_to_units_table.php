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
        Schema::table('units', function (Blueprint $table) {
            $table->boolean('is_published')->default(false)->after('status');
            $table->decimal('deposit_amount', 10, 2)->nullable()->after('rent_amount');
            $table->json('photos')->nullable()->after('features');
            $table->text('description')->nullable()->after('features');
            $table->string('room_type')->nullable()->after('size'); // 1BHK, 2BHK, etc.
            $table->integer('bedrooms')->nullable()->after('room_type');
            $table->integer('bathrooms')->nullable()->after('bedrooms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['is_published', 'deposit_amount', 'photos', 'description', 'room_type', 'bedrooms', 'bathrooms']);
        });
    }
};
