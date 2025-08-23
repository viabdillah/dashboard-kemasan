<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-cogs mr-2"></i>
            {{ __('Antrian Produksi') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-7xl mx-auto">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-inbox text-blue-500 mr-2"></i>
                            Antrian Produksi ({{ $productionQueue->count() }})
                        </h3>
                        <div class="space-y-4">
                            @forelse ($productionQueue as $order)
                                <div class="p-4 border rounded-md dark:border-gray-700">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-gray-800 dark:text-gray-200">{{ $order->product_name }} ({{ $order->quantity }} pcs)</p>
                                            <p class="text-sm text-gray-500">Oleh: {{ $order->customer_name }}</p>
                                        </div>
                                        <form action="{{ route('operator.production.start', $order) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-md hover:bg-blue-500">Mulai Produksi</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Tidak ada pesanan yang siap diproduksi.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-sync-alt fa-spin text-amber-500 mr-2"></i>
                            Sedang Diproduksi ({{ $inProduction->count() }})
                        </h3>
                        <div class="space-y-4">
                            @forelse ($inProduction as $order)
                                <div class="p-4 border rounded-md dark:border-gray-700">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-gray-800 dark:text-gray-200">{{ $order->product_name }} ({{ $order->quantity }} pcs)</p>
                                            <p class="text-sm text-gray-500">Oleh: {{ $order->customer_name }}</p>
                                        </div>
                                        <form action="{{ route('operator.production.finish', $order) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="px-3 py-1 bg-teal-600 text-white text-xs font-semibold rounded-md hover:bg-teal-500">Selesaikan</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Tidak ada pesanan yang sedang diproduksi.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>