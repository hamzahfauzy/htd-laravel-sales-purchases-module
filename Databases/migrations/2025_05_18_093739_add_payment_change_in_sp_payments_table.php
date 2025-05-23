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
        Schema::table('sp_payments', function (Blueprint $table) {
            $table->decimal('change',15,2)->after('amount')->nullable()->default(0)->comment('Kembalian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sp_payments', function (Blueprint $table) {
            $table->dropColumn('change');
        });
    }
};
