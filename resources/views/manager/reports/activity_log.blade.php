<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-history mr-2"></i>
            {{ __('Log Aktivitas Sistem') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Filter Rentang Waktu</h3>
                    <form action="{{ route('manager.reports.activity_log') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <x-input-label for="start_date" :value="__('Dari Tanggal')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="request('start_date', now()->startOfMonth()->format('Y-m-d'))" required />
                            </div>
                            <div>
                                <x-input-label for="end_date" :value="__('Sampai Tanggal')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="request('end_date', now()->format('Y-m-d'))" required />
                            </div>
                            <div><x-primary-button><i class="fas fa-filter mr-2"></i>Tampilkan Log</x-primary-button></div>
                        </div>
                    </form>
                    @if ($activities && $activities->count() > 0)
                    <div class="mt-4 pt-4 border-t dark:border-gray-700 flex justify-end gap-x-4">
                        <a href="{{ route('manager.reports.activity_log.export', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-file-excel mr-2"></i>
                            Download Excel
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    @forelse ($activities as $activity)
                        <div class="flex items-start space-x-4 py-4 border-b dark:border-gray-700 last:border-b-0">
                            <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-full">
                                <i class="fas {{ $activity->icon }} text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center">
                                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $activity->type }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity->timestamp->diffForHumans() }}</p>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $activity->description }}</p>
                                <p class="text-xs text-gray-500 mt-1">Oleh: {{ $activity->user }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-500">Tidak ada aktivitas pada rentang tanggal yang dipilih.</p>
                        </div>
                    @endforelse

                    <div class="mt-6">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>