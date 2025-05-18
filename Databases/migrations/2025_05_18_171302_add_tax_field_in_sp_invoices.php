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
            $table->decimal('tax',15,2)->default(0);
            $table->string('tax_alias')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sp_invoices', function (Blueprint $table) {
            //
            $table->dropColumn(['tax','tax_alias']);
        });
    }
};
