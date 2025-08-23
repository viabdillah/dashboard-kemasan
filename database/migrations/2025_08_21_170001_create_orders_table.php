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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name'); // Nama Pembeli
            $table->string('product_name'); // Nama Produk
            $table->string('packaging_label'); // Label Kemasan
            $table->string('size'); // Ukuran
            $table->integer('price_per_piece'); // Harga Per Pieces
            $table->integer('quantity'); // Jumlah
            $table->string('pirt_number')->nullable(); // No. PIRT (opsional)
            $table->string('halal_number')->nullable(); // No. Halal (opsional)
            $table->boolean('has_design')->default(false); // Sudah ada desain atau belum
            $table->timestamps(); // kapan pesanan dibuat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
