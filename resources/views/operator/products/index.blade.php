<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-boxes-stacked mr-2"></i>
            {{ __('Manajemen Stok Barang') }}
        </h2>
    </x-slot>

    <div class="p-6" x-data="{ modalOpen: false, currentVariant: null, adjustmentType: 'add' }">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-end mb-6">
                <a href="{{ route('operator.products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 ...">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Produk Baru
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">{{ session('error') }}</div>
            @endif

            <div class="space-y-4">
                @forelse ($products as $product)
                    <div x-data="{ open: true }" class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                        <div @click="open = !open" class="p-4 cursor-pointer ...">
                            </div>
                        <div x-show="open" x-transition class="p-4">
                            <div class="space-y-3">
                                @forelse ($product->variants as $variant)
                                    {{-- GANTI KESELURUHAN BLOK INI UNTUK SETIAP VARIAN --}}
                                    <div class="grid grid-cols-2 md:grid-cols-12 gap-x-4 gap-y-3 items-center p-4 border-b dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-gray-700/50 transition duration-150 ease-in-out">

                                        <div class="col-span-2 md:col-span-4">
                                            <p class="font-bold text-gray-900 dark:text-white">{{ $variant->name }}</p>
                                            <p class="text-xs text-gray-500">
                                                SKU: {{ $variant->sku ?? '-' }} | Ukuran: {{ $variant->size ?? '-' }}
                                            </p>
                                        </div>

                                        <div class="col-span-1 md:col-span-2 text-left md:text-center">
                                            @if ($variant->quantity <= 0)
                                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-300">Habis</span>
                                            @elseif ($variant->quantity <= $variant->low_stock_threshold)
                                                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-900 dark:text-yellow-300">Menipis</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">Aman</span>
                                            @endif
                                        </div>

                                        <div class="col-span-2 md:col-span-4 flex items-center justify-start md:justify-center gap-x-3">
                                            <button @click="modalOpen = true; currentVariant = {{ $variant }}; adjustmentType = 'subtract'" class="w-7 h-7 rounded-full bg-red-500 text-white hover:bg-red-600 transition font-bold flex-shrink-0">-</button>
                                            <span class="text-lg font-bold text-gray-900 dark:text-white w-12 text-center">{{ $variant->quantity }}</span>
                                            <button @click="modalOpen = true; currentVariant = {{ $variant }}; adjustmentType = 'add'" class="w-7 h-7 rounded-full bg-green-500 text-white hover:bg-green-600 transition font-bold flex-shrink-0">+</button>
                                            <span class="text-sm text-gray-500 ml-2">{{ $variant->unit }}</span>
                                        </div>

                                        <div class="col-span-1 md:col-span-2 text-right">
                                            <a href="{{ route('operator.variants.edit', $variant) }}" class="text-gray-500 hover:text-blue-600 transition px-2" title="Edit Varian">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('operator.variants.destroy', $variant) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus varian \'{{ $variant->name }}\'? Tindakan ini tidak dapat dibatalkan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-500 hover:text-red-600 transition px-2" title="Hapus Varian">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    {{-- AKHIR DARI BLOK YANG PERLU DIGANTI --}}
                                @empty
                                    <p class="text-gray-500 text-center py-4">Belum ada varian untuk produk ini.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @empty
                    @endforelse
            </div>

            <div class="mt-6">{{ $products->links() }}</div>
        </div>

        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.away="modalOpen = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6" @click.stop>
                <h3 class="text-lg font-bold mb-4">Ubah Stok: <span x-text="currentVariant ? currentVariant.name : ''"></span></h3>
                <p class="text-sm mb-4">Stok Saat Ini: <span class="font-bold" x-text="currentVariant ? currentVariant.quantity : 0"></span></p>

                <form :action="`/operator/product-variants/${currentVariant ? currentVariant.id : ''}/adjust-stock`" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <x-input-label value="Jenis Penyesuaian" />
                            <div class="flex gap-x-6 mt-2">
                                <div class="flex items-center">
                                    <input id="type_add" type="radio" name="type" value="add" x-model="adjustmentType" class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600">
                                    <label for="type_add" class="text-sm ms-2">Tambah Stok</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="type_subtract" type="radio" name="type" value="subtract" x-model="adjustmentType" class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600">
                                    <label for="type_subtract" class="text-sm ms-2">Kurangi Stok</label>
                                </div>
                            </div>
                        </div>
                        <div>
                            <x-input-label for="quantity" value="Jumlah" />
                            <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" value="1" min="1" required />
                        </div>
                        <div>
                            <x-input-label for="reason" value="Keterangan / Alasan" />
                            <textarea id="reason" name="reason" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required placeholder="Contoh: Stok masuk dari supplier, barang rusak, hasil stock opname, dll."></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6 gap-x-4">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Batal</button>
                        <x-primary-button>Simpan Perubahan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>