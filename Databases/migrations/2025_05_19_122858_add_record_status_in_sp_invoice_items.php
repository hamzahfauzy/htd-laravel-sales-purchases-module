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
        Schema::table('sp_invoice_items', function (Blueprint $table) {
            //
            $table->string('record_status')->default('DRAFT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sp_invoice_items', function (Blueprint $table) {
            //
            $table->dropColumn('record_status');
        });
    }
};
