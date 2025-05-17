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
        Schema::create('sp_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->integer('total_item')->default(0);
            $table->decimal('total_qty', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->decimal('total_discount', 15, 2)->default(0);
            $table->decimal('final_price', 15, 2)->default(0);
            $table->string('record_type')->default('SALES'); // SALES / PURCHASES
            $table->string('record_status')->default('DRAFT'); // SALES / PURCHASES

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('base_users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sp_invoices');
    }
};
