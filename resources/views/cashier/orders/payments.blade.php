<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-hand-holding-usd mr-2"></i>
            {{ __('Pembayaran & Pengambilan Pesanan') }}
        </h2>
    </x-slot>

    <div class="p-6" x-data="{ modalOpen: false, selectedOrder: null }">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Pesanan Siap Diambil</h3>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-slate-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ID Pesanan</th>
                                    <th scope="col" class="px-6 py-3">Nama Pembeli</th>
                                    <th scope="col" class="px-6 py-3">Produk</th>
                                    <th scope="col" class="px-6 py-3">Total Harga</th>
                                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($completedOrders as $order)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-bold">#{{ $order->id }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $order->customer_name }}</td>
                                    <td class="px-6 py-4">{{ $order->product_name }}</td>
                                    <td class="px-6 py-4 font-medium">Rp {{ number_format($order->price_per_piece * $order->quantity, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <button @click="modalOpen = true; selectedOrder = {{ $order->toJson() }}" class="px-3 py-1 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-500">
                                            Proses Bayar
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <p class="text-gray-500">Tidak ada pesanan yang siap untuk pembayaran.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $completedOrders->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div x-show="modalOpen" x-cloak x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click="modalOpen = false">
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-8 invoice-content" @click.stop>
                <div class="flex justify-between items-center pb-6 border-b">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">INVOICE</h1>
                        <p class="text-sm text-gray-500" x-text="'#' + selectedOrder.id"></p>
                    </div>
                    <div class="text-right">
                        <h2 class="text-xl font-bold">Pusat Layanan Kemasan Sintesa</h2>
                        <p class="text-xs text-gray-500">M63M+652, Jl. Soekarno-Hatta,</p>
                        <p class="text-xs text-gray-500">Kaduagung Tengah, Kec. Cibadak,</p>
                        <p class="text-xs text-gray-500">Kabupaten Lebak,<p>
                        <p class="text-xs text-gray-500">Banten 42317<p>
                    </div>
                </div>
                <div class="flex justify-between mt-6">
                    <div>
                        <p class="font-bold text-gray-700">Ditagihkan Kepada:</p>
                        <p class="text-gray-600" x-text="selectedOrder.customer_name"></p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-700">Tanggal Invoice:</p>
                        <p class="text-gray-600">{{ now()->format('d F Y') }}</p>
                    </div>
                </div>
                <div class="mt-8">
                    <table class="w-full text-left">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="p-3 text-sm font-semibold text-gray-600">Deskripsi</th>
                                <th class="p-3 text-sm font-semibold text-gray-600 text-center">Jumlah</th>
                                <th class="p-3 text-sm font-semibold text-gray-600 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="p-3">
                                    <p class="font-medium" x-text="selectedOrder.product_name"></p>
                                    <p class="text-xs text-gray-500" x-text="selectedOrder.packaging_label + ' - ' + selectedOrder.size + ' / ' + selectedOrder.net_weight"></p>
                                </td>
                                <td class="p-3 text-center" x-text="selectedOrder.quantity + ' pcs'"></td>
                                <td class="p-3 text-right" x-text="'Rp ' + (selectedOrder.price_per_piece * selectedOrder.quantity).toLocaleString('id-ID')"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end mt-6">
                    <div class="w-1/3">
                        <div class="flex justify-between py-2">
                            <span class="font-bold text-lg text-gray-800">Total</span>
                            <span class="font-bold text-lg text-gray-800" x-text="'Rp ' + (selectedOrder.price_per_piece * selectedOrder.quantity).toLocaleString('id-ID')"></span>
                        </div>
                    </div>
                </div>
                <div class="mt-10 flex justify-end gap-x-4 print-hidden">
                    <button @click="window.print()" class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-semibold rounded-md hover:bg-gray-400">
                        <i class="fas fa-print mr-2"></i>Cetak
                    </button>
                    <form :action="'/cashier/orders/' + selectedOrder.id + '/complete'" method="POST" onsubmit="return confirm('Konfirmasi bahwa pesanan ini sudah LUNAS dan diambil?');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-md hover:bg-green-500">
                            <i class="fas fa-check-circle mr-2"></i>Konfirmasi Lunas & Selesai
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>