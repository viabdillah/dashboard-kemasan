<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // 1. Ambil pesanan baru yang belum ada desain
        $ordersToDesign = Order::where('status', 'baru')
                               ->where('has_design', false)
                               ->latest()
                               ->get();

        // 2. Ambil pesanan baru yang sudah ada desain (perlu verifikasi)
        $ordersToVerify = Order::where('status', 'baru')
                               ->where('has_design', true)
                               ->latest()
                               ->get();

        return view('designer.orders.index', compact('ordersToDesign', 'ordersToVerify'));
    }
    public function show(Order $order)
    {
        return view('designer.orders.show', compact('order'));
    }
    /**
     * Memproses update dari designer (konfirmasi).
     */
    public function update(Request $request, Order $order)
    {
        // Langsung update status menjadi 'siap_produksi'
        $order->update(['status' => 'siap_produksi']);

        // Redirect kembali ke dashboard designer dengan pesan sukses
        return redirect()->route('designer.orders.index')->with('success', 'Pesanan #' . $order->id . ' telah dikonfirmasi dan siap untuk produksi.');
    }
}