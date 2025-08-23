<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-history mr-2"></i>
            {{ __('Riwayat Produksi Selesai') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-slate-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ID Pesanan</th>
                                    <th scope="col" class="px-6 py-3">Nama Pembeli</th>
                                    <th scope="col" class="px-6 py-3">Produk</th>
                                    <th scope="col" class="px-6 py-3">Tgl. Selesai Produksi</th>
                                    <th scope="col" class="px-6 py-3">Status Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($finishedOrders as $order)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4 font-bold">#{{ $order->id }}</td>
                                    <td class="px-6 py-4">{{ $order->customer_name }}</td>
                                    <td class="px-6 py-4">{{ $order->product_name }}</td>
                                    <td class="px-6 py-4">{{ $order->updated_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">
                                        @if ($order->status == 'selesai')
                                            <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-1 rounded-full dark:bg-green-900 dark:text-green-300">Lunas</span>
                                        @else
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-1 rounded-full dark:bg-blue-900 dark:text-blue-300">Menunggu Pembayaran</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <p class="text-gray-500">Belum ada riwayat produksi yang selesai.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $finishedOrders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>