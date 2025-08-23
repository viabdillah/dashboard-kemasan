<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    /**
     * Menampilkan dashboard produksi dengan dua daftar: antrian dan sedang diproduksi.
     */
    public function index()
    {
        // Ambil pesanan yang siap diproduksi
        $productionQueue = Order::where('status', 'siap_produksi')->latest()->get();

        // Ambil pesanan yang sedang diproduksi
        $inProduction = Order::where('status', 'sedang_diproduksi')->latest()->get();

        return view('operator.production.index', compact('productionQueue', 'inProduction'));
    }

    /**
     * Memulai proses produksi untuk sebuah pesanan.
     */
    public function startProduction(Order $order)
    {
        // Ubah status menjadi 'sedang_diproduksi'
        $order->update(['status' => 'sedang_diproduksi']);

        return redirect()->route('operator.production.index')->with('success', 'Pesanan #' . $order->id . ' telah dimulai proses produksinya.');
    }

    /**
     * Menyelesaikan proses produksi untuk sebuah pesanan.
     */
    public function finishProduction(Order $order)
    {
        // Ubah status menjadi 'produksi_selesai'
        $order->update(['status' => 'produksi_selesai']);

        return redirect()->route('operator.production.index')->with('success', 'Pesanan #' . $order->id . ' telah selesai diproduksi.');
    }
    /**
     * [BARU] Menampilkan riwayat pesanan yang sudah selesai diproduksi.
     */
    public function history()
    {
        // Ambil pesanan yang statusnya 'produksi_selesai' atau sudah 'selesai' (lunas)
        $finishedOrders = Order::whereIn('status', ['produksi_selesai', 'selesai'])
                            ->latest('updated_at') // Urutkan berdasarkan kapan diselesaikan
                            ->paginate(15);

        return view('operator.production.history', compact('finishedOrders'));
    }
}