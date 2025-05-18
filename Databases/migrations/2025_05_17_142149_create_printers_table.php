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
        Schema::create('sp_printers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('USB'); // network, bluetooth, manual, rawbt
            $table->string('connection_string')->nullable(); // Alamat koneksi: nama device, IP address, MAC, atau port USB
            $table->string('paper_size')->nullable(); // Ukuran kertas: 80mm, 58mm, dll
            $table->string('character_set')->nullable(); // Charset printer, misalnya: PC437, PC858 (untuk ESC/POS)
            $table->string('auto_cut')->default('YES');
            $table->integer('is_default')->default(0);
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
        Schema::dropIfExists('sp_printers');
    }
};
