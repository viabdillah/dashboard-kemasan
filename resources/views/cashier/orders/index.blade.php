<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-list-alt mr-2"></i>
            {{ __('Daftar Pesanan') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('cashier.orders.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Pesanan Baru
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-slate-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nama Pembeli</th>
                                    <th scope="col" class="px-6 py-3">Produk</th>
                                    <th scope="col" class="px-6 py-3 text-center">Jumlah</th>
                                    <th scope="col" class="px-6 py-3">Total Harga</th>
                                    <th scope="col" class="px-6 py-3 text-center">Status Desain</th>
                                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap dark:text-white">{{ $order->customer_name }}</th>
                                    <td class="px-6 py-4">{{ $order->product_name }}</td>
                                    <td class="px-6 py-4 text-center">{{ $order->quantity }} pcs</td>
                                    <td class="px-6 py-4 font-medium">Rp {{ number_format($order->price_per_piece * $order->quantity, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($order->has_design)
                                            <span class="bg-teal-100 text-teal-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-teal-900 dark:text-teal-300">Sudah Ada</span>
                                        @else
                                            <span class="bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-amber-900 dark:text-amber-300">Belum Ada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('cashier.orders.show', $order->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-center">
                                            <i class="fas fa-box-open fa-3x text-gray-400 mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum Ada Pesanan</h3>
                                            <p class="mt-1 text-sm text-gray-500">Saat ada pesanan baru, datanya akan muncul di sini.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>