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
        Schema::table('tenant_screenings', function (Blueprint $table) {
            $table->string('document_type')->nullable()->after('tenant_id');
            $table->string('file_path')->nullable()->after('document_type');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('status');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->text('notes')->nullable()->after('reviewed_at');
            
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenant_screenings', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['document_type', 'file_path', 'reviewed_by', 'reviewed_at', 'notes']);
        });
    }
};
