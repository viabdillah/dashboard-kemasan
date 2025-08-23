<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\CashFlow;
use Illuminate\Http\Request;

class CashFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil data arus kas, urutkan dari terbaru, dengan nama pencatatnya
        $cashFlows = CashFlow::with('user')->latest()->paginate(15);

        return view('cashier.cash-flow.index', compact('cashFlows'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi data dari form
        $validated = $request->validate([
            'type' => 'required|in:in,out',
            'amount' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
        ]);

        // 2. Tambahkan user_id dari user yang sedang login
        $validated['user_id'] = auth()->id();

        // 3. Simpan ke database
        CashFlow::create($validated);

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->route('cashier.cash-flow.index')->with('success', 'Catatan kas berhasil ditambahkan.');
    }
}