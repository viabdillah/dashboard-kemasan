<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\CashFlow;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // [BARU] Tambahkan ->where('status', '!=', 'selesai')
        $orders = Order::where('status', '!=', 'selesai')
                    ->latest()
                    ->paginate(10);

        return view('cashier.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cashier.orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi semua input dari form
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'packaging_type' => 'required|string|max:255',
            'packaging_label' => 'required|string|max:255',
            'size' => 'required|string|max:255',
            'net_weight' => 'required|string|max:255',
            'price_per_piece' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:1',
            'pirt_number' => 'nullable|string|max:255',
            'halal_number' => 'nullable|string|max:255',
            'has_design' => 'required|boolean',
        ]);

        // 2. Simpan data yang sudah divalidasi ke database
        Order::create($validatedData);

        // 3. Arahkan kembali ke halaman daftar pesanan dengan pesan sukses
        //    (Kita akan buat halaman index ini di langkah berikutnya)
        return redirect()->route('cashier.orders.index')->with('success', 'Pesanan baru berhasil dibuat!');
    }

    /**
     * [BARU] Menampilkan detail sederhana dari sebuah pesanan untuk Kasir.
     */
    public function show(Order $order)
    {
        return view('cashier.orders.show', compact('order'));
    }

    /**
     * [BARU] Menampilkan halaman daftar pesanan yang siap bayar.
     */
    public function paymentIndex()
    {
        // Ambil pesanan yang sudah selesai diproduksi tapi belum dibayar
        $completedOrders = Order::where('status', 'produksi_selesai')
                                ->whereNull('paid_at')
                                ->latest()
                                ->paginate(15);

        return view('cashier.orders.payments', compact('completedOrders'));
    }



    public function markAsCompleted(Order $order)
    {
        // 1. Update status pesanan dan catat waktu pembayaran
        $order->update([
            'status' => 'selesai',
            'paid_at' => Carbon::now()
        ]);

        // 2. [BARU] Buat entri baru di Buku Kas (cash_flows)
        CashFlow::create([
            'user_id' => auth()->id(), // Dicatat oleh kasir yang login
            'type' => 'in', // Jenisnya adalah Uang Masuk
            'amount' => $order->price_per_piece * $order->quantity, // Ambil total harga dari pesanan
            'description' => 'Pembayaran untuk Pesanan #' . $order->id . ' - ' . $order->customer_name, // Buat keterangan otomatis
        ]);

        // 3. Redirect kembali ke halaman pembayaran dengan pesan sukses
        return redirect()->route('cashier.orders.payments')->with('success', 'Pesanan #' . $order->id . ' telah ditandai lunas dan dicatat di Buku Kas.');
    }

    public function historyIndex()
    {
        $historyOrders = Order::where('status', 'selesai')
                            ->latest()
                            ->paginate(15);

        return view('cashier.orders.history', compact('historyOrders'));
    }

    /**
     * Menampilkan halaman invoice untuk satu pesanan.
     */
    public function showInvoice(Order $order)
    {
        // [BARU] Izinkan jika statusnya 'produksi_selesai' ATAU 'selesai'.
        if (!in_array($order->status, ['produksi_selesai', 'selesai'])) {
            abort(404);
        }

        // Kita juga perlu membuat halaman invoice bisa menampilkan tombol yang berbeda
        // tergantung apakah sudah lunas atau belum.
        return view('cashier.orders.invoice', [
            'order' => $order,
            'isPaid' => $order->status === 'selesai'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
