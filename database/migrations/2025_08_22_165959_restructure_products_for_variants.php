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
        // 1. Mengubah tabel products menjadi tabel induk
        Schema::table('products', function (Blueprint $table) {
            // Hapus kolom yang akan kita pindahkan ke varian
            $table->dropColumn(['sku', 'quantity', 'unit', 'low_stock_threshold']);
        });

        // 2. Membuat tabel baru untuk varian produk
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nama varian, cth: "Merah, L" atau "250 gram"
            $table->string('sku')->unique()->nullable();
            $table->integer('quantity')->default(0);
            $table->string('unit')->default('pcs');
            $table->integer('low_stock_threshold')->default(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');

        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->unique()->nullable();
            $table->integer('quantity')->default(0);
            $table->string('unit')->default('pcs');
            $table->integer('low_stock_threshold')->default(10);
        });
    }
};