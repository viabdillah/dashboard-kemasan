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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Barang
            $table->string('sku')->unique()->nullable(); // Kode SKU, unik
            $table->text('description')->nullable();
            $table->integer('quantity')->default(0); // Jumlah Stok
            $table->string('unit')->default('pcs'); // Satuan (pcs, kg, liter, dll)
            $table->integer('low_stock_threshold')->default(10); // Ambang batas stok rendah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
