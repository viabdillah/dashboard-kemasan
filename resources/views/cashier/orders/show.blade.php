<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-search-plus mr-2"></i>
            {{ __('Detail Pesanan #') }}{{ $order->id }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Informasi Lengkap Pesanan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-sm text-gray-600 dark:text-gray-400">

                        <div class="border-b pb-2 dark:border-gray-700"><span class="font-bold text-gray-800 dark:text-gray-200">Nama Pembeli:</span><br>{{ $order->customer_name }}</div>
                        <div class="border-b pb-2 dark:border-gray-700"><span class="font-bold text-gray-800 dark:text-gray-200">Nama Produk:</span><br>{{ $order->product_name }}</div>
                        <div class="border-b pb-2 dark:border-gray-700"><span class="font-bold text-gray-800 dark:text-gray-200">Jenis Kemasan:</span><br>{{ $order->packaging_type }}</div>
                        <div class="border-b pb-2 dark:border-gray-700"><span class="font-bold text-gray-800 dark:text-gray-200">Label/Merek:</span><br>{{ $order->packaging_label }}</div>
                        <div class="border-b pb-2 dark:border-gray-700"><span class="font-bold text-gray-800 dark:text-gray-200">Ukuran Kemasan:</span><br>{{ $order->size }}</div>
                        <div class="border-b pb-2 dark:border-gray-700"><span class="font-bold text-gray-800 dark:text-gray-200">Berat Bersih:</span><br>{{ $order->net_weight }}</div>
                        <div class="border-b pb-2 dark:border-gray-700"><span class="font-bold text-gray-800 dark:text-gray-200">Jumlah:</span><br>{{ $order->quantity }} pcs</div>
                        <div class="border-b pb-2 dark:border-gray-700"><span class="font-bold text-gray-800 dark:text-gray-200">Harga Satuan:</span><br>Rp {{ number_format($order->price_per_piece, 0, ',', '.') }}</div>

                        @if($order->pirt_number)
                            <div class="border-b pb-2 dark:border-gray-700"><span class="font-bold text-gray-800 dark:text-gray-200">No. PIRT:</span><br>{{ $order->pirt_number }}</div>
                        @endif
                        @if($order->halal_number)
                            <div class="border-b pb-2 dark:border-gray-700"><span class="font-bold text-gray-800 dark:text-gray-200">No. Halal:</span><br>{{ $order->halal_number }}</div>
                        @endif
                    </div>

                    <div class="mt-8 border-t pt-6 dark:border-gray-700">
                        <a href="{{ route('cashier.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-sm font-semibold rounded-md hover:bg-gray-400">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Daftar Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>