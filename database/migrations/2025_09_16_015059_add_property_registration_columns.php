<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPropertyRegistrationColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            // Add registration status column
            $table->enum('registration_status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('status');
            
            // Add approval tracking columns
            $table->text('registration_notes')->nullable()->after('description');
            $table->timestamp('approved_at')->nullable()->after('registration_notes');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            
            // Add image column if not exists
            if (!Schema::hasColumn('properties', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
            
            // Add zip_code column if not exists (fix naming from 'zip' in original)
            if (!Schema::hasColumn('properties', 'zip_code') && Schema::hasColumn('properties', 'zip')) {
                $table->renameColumn('zip', 'zip_code');
            } elseif (!Schema::hasColumn('properties', 'zip_code')) {
                $table->string('zip_code')->nullable()->after('state');
            }
            
            // Add foreign key constraint for approved_by
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            // Update existing properties to have approved status if they don't have a registration_status
            // This will be handled in the migration itself
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['approved_by']);
            
            // Drop the added columns
            $table->dropColumn([
                'registration_status',
                'registration_notes', 
                'approved_at',
                'approved_by'
            ]);
            
            // Note: We don't drop 'image' and 'zip_code' as they might be needed
            // and could have been added by other migrations
        });
    }
}