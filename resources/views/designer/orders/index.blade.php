<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-paint-brush mr-2"></i>
            {{ __('Tugas Desain') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-pencil-ruler text-blue-500 mr-2"></i>
                            Perlu Didesain ({{ $ordersToDesign->count() }})
                        </h3>
                        <div class="space-y-4">
                            @forelse ($ordersToDesign as $order)
                                <div class="p-4 border rounded-md dark:border-gray-700 flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-gray-800 dark:text-gray-200">{{ $order->product_name }}</p>
                                        <p class="text-sm text-gray-500">Oleh: {{ $order->customer_name }}</p>
                                    </div>
                                    <a href="{{ route('designer.orders.show', $order) }}" class="px-3 py-1 bg-teal-600 text-white text-xs font-semibold rounded-md hover:bg-teal-500">
                                        Lihat & Setujui
                                    </a>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Tidak ada tugas desain baru.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-check-circle text-teal-500 mr-2"></i>
                            Perlu Diverifikasi ({{ $ordersToVerify->count() }})
                        </h3>
                        <div class="space-y-4">
                            @forelse ($ordersToVerify as $order)
                                <div class="p-4 border rounded-md dark:border-gray-700 flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-gray-800 dark:text-gray-200">{{ $order->product_name }}</p>
                                        <p class="text-sm text-gray-500">Oleh: {{ $order->customer_name }}</p>
                                    </div>
                                    <a href="{{ route('designer.orders.show', $order) }}" class="px-3 py-1 bg-teal-600 text-white text-xs font-semibold rounded-md hover:bg-teal-500">
                                        Lihat & Setujui
                                    </a>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Tidak ada desain yang perlu diverifikasi.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>