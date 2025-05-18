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
        Schema::create('sp_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('inv_items')->onDelete('restrict');

            $table->string('unit');
            $table->decimal('amount_1', 15, 2)->default(0);
            $table->decimal('min_qty_1', 15, 2)->default(1);
            $table->decimal('amount_2', 15, 2)->nullable();
            $table->decimal('min_qty_2', 15, 2)->nullable();
            $table->decimal('amount_3', 15, 2)->nullable();
            $table->decimal('min_qty_3', 15, 2)->nullable();
            $table->decimal('amount_4', 15, 2)->nullable();
            $table->decimal('min_qty_4', 15, 2)->nullable();
            $table->decimal('amount_5', 15, 2)->nullable();
            $table->decimal('min_qty_5', 15, 2)->nullable();

            $table->timestamps();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('base_users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sp_prices');
    }
};
