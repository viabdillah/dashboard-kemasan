<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Import semua controller yang kita gunakan
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Operator\ProductController;
use App\Http\Controllers\Operator\SparePartController;
use App\Http\Controllers\Cashier\OrderController;
use App\Http\Controllers\Cashier\CashFlowController;
use App\Http\Controllers\Designer\OrderController as DesignerOrderController;
use App\Http\Controllers\Operator\ProductionController;
use App\Http\Controllers\Manager\ReportController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Halaman Depan (Publik)
// [PENTING] Rute untuk homepage publik
Route::get('/', function () {
    // Ganti 'home' dengan nama file view homepage Anda.
    // Berdasarkan histori kita, namanya adalah 'home'.
    return view('home'); 
})->name('home');

// [BARU] Rute untuk Toko dan Blog
Route::get('/toko', [HomeController::class, 'toko'])->name('toko');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');

Route::middleware(['auth', 'verified'])->group(function () {

    // Rute untuk dashboard utama setelah login
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Grup rute untuk Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});

// Grup rute untuk Kasir
Route::middleware(['auth', 'role:kasir'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/orders/history', [OrderController::class, 'historyIndex'])->name('orders.history');
    Route::get('/payments', [OrderController::class, 'paymentIndex'])->name('orders.payments');
    Route::put('/orders/{order}/complete', [OrderController::class, 'markAsCompleted'])->name('orders.complete');
    Route::resource('orders', OrderController::class);
    Route::get('cash-flow', [CashFlowController::class, 'index'])->name('cash-flow.index');
    Route::post('cash-flow', [CashFlowController::class, 'store'])->name('cash-flow.store');
});

// Grup rute untuk Designer
Route::middleware(['auth', 'role:designer'])->prefix('designer')->name('designer.')->group(function () {
    Route::get('/orders', [DesignerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [DesignerOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}', [DesignerOrderController::class, 'update'])->name('orders.update');
});

// Grup rute untuk Operator
Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/production', [ProductionController::class, 'index'])->name('production.index');
    Route::get('/production/history', [ProductionController::class, 'history'])->name('production.history');
    Route::put('/production/{order}/start', [ProductionController::class, 'startProduction'])->name('production.start');
    Route::put('/production/{order}/finish', [ProductionController::class, 'finishProduction'])->name('production.finish');

    Route::resource('products', ProductController::class);
    Route::resource('spare-parts', SparePartController::class);
    Route::post('product-variants/{variant}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.variants.adjust');
    Route::get('product-variants/{variant}/edit', [ProductController::class, 'editVariant'])->name('variants.edit');
    Route::put('product-variants/{variant}', [ProductController::class, 'updateVariant'])->name('variants.update');
    Route::delete('product-variants/{variant}', [ProductController::class, 'destroyVariant'])->name('variants.destroy');
});

// Grup rute untuk Manajer
Route::middleware(['auth', 'role:manajer'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/reports/financial', [ReportController::class, 'financialIndex'])->name('reports.financial');
    Route::get('/reports/financial/export', [ReportController::class, 'exportFinancialReport'])->name('reports.financial.export');
    Route::get('/activity-log', [ReportController::class, 'activityLog'])->name('reports.activity_log');
    Route::get('/activity-log/export', [ReportController::class, 'exportActivityLog'])->name('reports.activity_log.export');
});

});

// Rute Profile (bawaan Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// PENTING: Panggil file rute otentikasi di sini
require __DIR__.'/auth.php';
