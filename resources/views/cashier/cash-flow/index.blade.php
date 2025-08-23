<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-book mr-2"></i>
            {{ __('Buku Kas') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-7xl mx-auto">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg h-full">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Tambah Catatan Baru</h3>
                            <form method="POST" action="{{ route('cashier.cash-flow.store') }}">
                                @csrf
                                <div>
                                    <x-input-label :value="__('Jenis Catatan')" class="mb-2"/>
                                    <div class="flex gap-x-6">
                                        <div class="flex">
                                            <input type="radio" id="type_in" name="type" value="in" class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600" checked>
                                            <label for="type_in" class="text-sm text-gray-500 ms-2 dark:text-gray-400">Uang Masuk</label>
                                        </div>
                                        <div class="flex">
                                            <input type="radio" id="type_out" name="type" value="out" class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600">
                                            <label for="type_out" class="text-sm text-gray-500 ms-2 dark:text-gray-400">Uang Keluar</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <x-input-label for="amount" :value="__('Jumlah (Rp)')" />
                                    <x-text-input id="amount" class="block mt-1 w-full" type="number" name="amount" :value="old('amount')" required placeholder="50000"/>
                                    <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <x-input-label for="description" :value="__('Keterangan')" />
                                    <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-end mt-6">
                                    <x-primary-button>
                                        <i class="fas fa-save mr-2"></i>
                                        {{ __('Simpan Catatan') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Riwayat Arus Kas</h3>
                            <div class="relative overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-slate-100 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Tanggal</th>
                                            <th scope="col" class="px-6 py-3">Keterangan</th>
                                            <th scope="col" class="px-6 py-3">Dicatat oleh</th>
                                            <th scope="col" class="px-6 py-3 text-right">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($cashFlows as $cashFlow)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-gray-600">
                                            <td class="px-6 py-4">{{ $cashFlow->created_at->format('d M Y, H:i') }}</td>
                                            <td class="px-6 py-4">{{ $cashFlow->description }}</td>
                                            <td class="px-6 py-4">{{ $cashFlow->user->name }}</td>
                                            <td class="px-6 py-4 text-right font-medium
                                                @if($cashFlow->type == 'in') text-green-600 dark:text-green-500 @else text-red-600 dark:text-red-500 @endif">
                                                @if($cashFlow->type == 'in') + @else - @endif
                                                Rp {{ number_format($cashFlow->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center">
                                                <p class="text-gray-500">Belum ada catatan kas.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $cashFlows->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>