<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        // Ambil produk dan semua varian terkait menggunakan eager loading
        $products = \App\Models\Product::with('variants')->latest()->paginate(10);

        return view('operator.products.index', compact('products'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('operator.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'variants' => 'required|array|min:1',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.size' => 'nullable|string|max:255',
            'variants.*.sku' => 'nullable|string|max:255|unique:product_variants,sku',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.unit' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // 1. Buat Produk Induk
            $product = \App\Models\Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
            ]);

            // 2. Loop dan buat setiap varian
            foreach ($validated['variants'] as $variantData) {
                $product->variants()->create([
                    'name' => $variantData['name'],
                    'sku' => $variantData['sku'],
                    'quantity' => $variantData['quantity'],
                    'unit' => $variantData['unit'],
                    // Anda bisa menambahkan input untuk low_stock_threshold di form jika perlu
                ]);
            }

            DB::commit(); // Jika semua berhasil, simpan permanen

            return redirect()->route('operator.products.index')->with('success', 'Produk baru beserta variannya berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack(); // Jika ada error, batalkan semua proses
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function adjustStock(Request $request, ProductVariant $variant)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:add,subtract',
            'reason' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($validated, $variant) {
                $quantityChange = $validated['type'] === 'add' ? $validated['quantity'] : -$validated['quantity'];

                // 1. Update jumlah stok di varian produk
                $variant->increment('quantity', $quantityChange);

                // 2. Catat pergerakan stok di log
                StockMovement::create([
                    'product_variant_id' => $variant->id,
                    'user_id' => auth()->id(),
                    'quantity_change' => $quantityChange,
                    'reason' => $validated['reason'],
                ]);
            });

            return back()->with('success', 'Stok berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui stok: ' . $e->getMessage());
        }
    }

     /**
     * [BARU] Menampilkan form untuk mengedit varian.
     */
    public function editVariant(ProductVariant $variant)
    {
        return view('operator.products.edit_variant', compact('variant'));
    }

    /**
     * [BARU] Memperbarui data varian di database.
     */
    public function updateVariant(Request $request, ProductVariant $variant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Pastikan SKU unik, tapi abaikan SKU dari varian yang sedang diedit
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('product_variants')->ignore($variant->id)],
            'size' => 'nullable|string|max:255',
            'unit' => 'required|string|max:255',
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        $variant->update($validated);

        return redirect()->route('operator.products.index')->with('success', 'Varian produk berhasil diperbarui.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    /**
     * [BARU] Menghapus varian produk dari database.
     */
    public function destroyVariant(ProductVariant $variant)
    {
        try {
            // Simpan nama produk induk untuk pesan notifikasi
            $productName = $variant->product->name;

            // Hapus varian
            $variant->delete();

            // Cek apakah produk induknya masih punya varian lain
            $product = \App\Models\Product::find($variant->product_id);
            if ($product && $product->variants()->count() === 0) {
                // Jika sudah tidak ada varian, hapus produk induknya juga
                $product->delete();
                return redirect()->route('operator.products.index')->with('success', 'Varian terakhir dari produk "' . $productName . '" telah dihapus, dan produk induknya juga ikut terhapus.');
            }

            return back()->with('success', 'Varian berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus varian: ' . $e->getMessage());
        }
    }
}
