<?php

use App\Http\Controllers\Operator\ProductController;
use App\Http\Controllers\Operator\SparePartController;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cashier\OrderController;
use App\Http\Controllers\Cashier\CashFlowController;
use App\Http\Controllers\Designer\OrderController as DesignerOrderController;
use App\Http\Controllers\Operator\ProductionController;
use App\Http\Controllers\Manager\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute yang bisa diakses oleh semua user yang sudah login
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Group rute khusus untuk Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return "<h1>Selamat Datang di Dashboard Admin</h1>";
    })->name('dashboard');

    Route::resource('users', UserController::class);
});

// Rute khusus untuk Manajer
Route::middleware(['auth', 'role:manajer'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/reports/financial', [ReportController::class, 'financialIndex'])->name('reports.financial');
    // [BARU] Rute untuk download Excel
    Route::get('/reports/financial/export', [ReportController::class, 'exportFinancialReport'])->name('reports.financial.export');
});

// Rute yang bisa diakses oleh Admin ATAU Manajer
Route::get('/shared/data', function() {
    return "<h1>Halaman ini bisa diakses Admin dan Manajer</h1>";
})->middleware(['auth', 'role:admin,manajer']);

// Grup rute khusus untuk Kasir
Route::middleware(['auth', 'role:kasir'])->prefix('cashier')->name('cashier.')->group(function () {
    
    // --- Rute-rute Spesifik untuk Pesanan (Harus di atas Route::resource) ---

    // Halaman untuk melihat riwayat pesanan yang sudah selesai
    Route::get('/orders/history', [OrderController::class, 'historyIndex'])->name('orders.history');
    
    // Halaman untuk menampilkan invoice sebelum pembayaran
    
    
    // Halaman untuk menampilkan daftar pesanan yang siap bayar
    Route::get('/payments', [OrderController::class, 'paymentIndex'])->name('orders.payments');

    // Aksi untuk menandai pesanan sebagai lunas/selesai
    Route::put('/orders/{order}/complete', [OrderController::class, 'markAsCompleted'])->name('orders.complete');
    
    // --- Rute Resource untuk Pesanan (CRUD dasar) ---
    // Mencakup: create, store, index, edit, update, destroy
    Route::resource('orders', OrderController::class);

    // --- Rute untuk Buku Kas (Cash Flow) ---

    // Halaman untuk menampilkan & menambah catatan kas
    Route::get('cash-flow', [CashFlowController::class, 'index'])->name('cash-flow.index');
    
    // Aksi untuk menyimpan catatan kas baru
    Route::post('cash-flow', [CashFlowController::class, 'store'])->name('cash-flow.store');

});

// [BARU] Grup rute khusus untuk Designer
Route::middleware(['auth', 'role:designer'])->prefix('designer')->name('designer.')->group(function () {
    Route::get('/orders', [DesignerOrderController::class, 'index'])->name('orders.index');
    // Nanti kita akan tambahkan rute untuk detail dan update di sini
    // [BARU] Rute untuk menampilkan detail pesanan
    Route::get('/orders/{order}', [DesignerOrderController::class, 'show'])->name('orders.show');

    // [BARU] Rute untuk memproses form (upload/approve)
    Route::put('/orders/{order}', [DesignerOrderController::class, 'update'])->name('orders.update');
});

Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    // Halaman utama untuk melihat antrian produksi
    Route::get('/production', [ProductionController::class, 'index'])->name('production.index');

    // Rute untuk mengubah status menjadi "sedang diproduksi"
    Route::put('/production/{order}/start', [ProductionController::class, 'startProduction'])->name('production.start');

    // Rute untuk mengubah status menjadi "produksi selesai"
    Route::put('/production/{order}/finish', [ProductionController::class, 'finishProduction'])->name('production.finish');

    Route::resource('products', ProductController::class);
    Route::resource('spare-parts', SparePartController::class);
    Route::post('product-variants/{variant}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.variants.adjust');

    // [BARU] Rute untuk mengedit dan update varian
    Route::get('product-variants/{variant}/edit', [ProductController::class, 'editVariant'])->name('variants.edit');
    Route::put('product-variants/{variant}', [ProductController::class, 'updateVariant'])->name('variants.update');

    Route::delete('product-variants/{variant}', [ProductController::class, 'destroyVariant'])->name('variants.destroy');
    // ... (di dalam grup middleware 'role:operator')

Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    // Halaman utama untuk melihat antrian produksi
    Route::get('/production', [ProductionController::class, 'index'])->name('production.index');

    // [BARU] Rute untuk halaman riwayat produksi
    Route::get('/production/history', [ProductionController::class, 'history'])->name('production.history');

    // Rute untuk mengubah status menjadi "sedang diproduksi"
    Route::put('/production/{order}/start', [ProductionController::class, 'startProduction'])->name('production.start');

    // Rute untuk mengubah status menjadi "produksi selesai"
    Route::put('/production/{order}/finish', [ProductionController::class, 'finishProduction'])->name('production.finish');
});
});

// [BARU] Grup rute khusus untuk Manajer
Route::middleware(['auth', 'role:manajer'])->prefix('manager')->name('manager.')->group(function () {
    // Halaman utama untuk laporan keuangan
    Route::get('/reports/financial', [ReportController::class, 'financialIndex'])->name('reports.financial');
    // [BARU] Rute untuk Log Aktivitas
    Route::get('/activity-log', [ReportController::class, 'activityLog'])->name('reports.activity_log');
    });
    // [BARU] Rute untuk Ekspor Log Aktivitas
    Route::get('/activity-log/export', [ReportController::class, 'exportActivityLog'])->name('reports.activity_log.export');

require __DIR__.'/auth.php';