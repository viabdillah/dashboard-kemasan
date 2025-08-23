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
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Spare Part
            $table->string('part_number')->unique()->nullable(); // Nomor Part
            $table->text('description')->nullable();
            $table->integer('quantity')->default(0); // Jumlah Stok
            $table->string('location')->nullable(); // Lokasi penyimpanan
            $table->integer('low_stock_threshold')->default(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};
