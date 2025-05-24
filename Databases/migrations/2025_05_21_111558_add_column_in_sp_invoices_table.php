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
        Schema::table('sp_invoices', function (Blueprint $table) {
            //
            $table->decimal('invoice_discount', 15, 2)->default(0);
            $table->decimal('item_discount', 15, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sp_invoices', function (Blueprint $table) {
            //
            $table->dropColumn([
                'invoice_discount',
                'item_discount'
            ]);
        });
    }
};
