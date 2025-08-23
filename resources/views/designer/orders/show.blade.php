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
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Informasi Pesanan</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <div><span class="font-bold text-gray-800 dark:text-gray-200">Nama Pembeli:</span> {{ $order->customer_name }}</div>
                        <div><span class="font-bold text-gray-800 dark:text-gray-200">Nama Produk:</span> {{ $order->product_name }}</div>
                        <div><span class="font-bold text-gray-800 dark:text-gray-200">Jenis Kemasan:</span> {{ $order->packaging_type }}</div>
                        <div><span class="font-bold text-gray-800 dark:text-gray-200">Label Merek Kemasan:</span> {{ $order->packaging_label }}</div>
                        <div><span class="font-bold text-gray-800 dark:text-gray-200">Ukuran:</span> {{ $order->size }}</div>
                        <div><span class="font-bold text-gray-800 dark:text-gray-200">Berat Bersih:</span> {{ $order->net_weight }}</div>
                        <div><span class="font-bold text-gray-800 dark:text-gray-200">Jumlah:</span> {{ $order->quantity }} pcs</div>
                        <div><span class="font-bold text-gray-800 dark:text-gray-200">Harga Satuan:</span> Rp {{ number_format($order->price_per_piece, 0, ',', '.') }}</div>
                        @if($order->pirt_number)
                            <div><span class="font-bold text-gray-800 dark:text-gray-200">No. PIRT:</span> {{ $order->pirt_number }}</div>
                        @endif
                        @if($order->halal_number)
                            <div><span class="font-bold text-gray-800 dark:text-gray-200">No. Halal:</span> {{ $order->halal_number }}</div>
                        @endif
                    </div>

                    <hr class="my-6 dark:border-gray-700">

                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Tindakan Desainer</h3>

                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Setelah desain untuk pesanan ini selesai dan siap, klik tombol di bawah ini untuk meneruskannya ke tahap produksi.
                    </p>
                    <form action="{{ route('designer.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <x-primary-button>
                            <i class="fas fa-check-double mr-2"></i>
                            Konfirmasi & Lanjutkan ke Produksi
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>