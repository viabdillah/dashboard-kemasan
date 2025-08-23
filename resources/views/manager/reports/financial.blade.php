<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-chart-line mr-2"></i>
            {{ __('Laporan Keuangan') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Pilih Rentang Waktu</h3>
                    <form action="{{ route('manager.reports.financial') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <x-input-label for="start_date" :value="__('Dari Tanggal')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="request('start_date')" required />
                            </div>
                            <div>
                                <x-input-label for="end_date" :value="__('Sampai Tanggal')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="request('end_date')" required />
                            </div>
                            <div><x-primary-button><i class="fas fa-filter mr-2"></i>Tampilkan Laporan</x-primary-button></div>
                        </div>
                    </form>
                </div>
            </div>

            @if($cashFlows)
            <div x-data="{ show: false }" x-init="() => { setTimeout(() => show = true, 100) }" class="printable-area">
                <div x-show="show" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg"><h4 class="text-gray-500 font-medium">Total Pemasukan</h4><p class="text-3xl font-bold text-green-500 mt-2">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p></div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg"><h4 class="text-gray-500 font-medium">Total Pengeluaran</h4><p class="text-3xl font-bold text-red-500 mt-2">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p></div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg"><h4 class="text-gray-500 font-medium">Laba / Rugi Bersih</h4><p class="text-3xl font-bold {{ $netProfit >= 0 ? 'text-blue-500' : 'text-red-500' }} mt-2">Rp {{ number_format($netProfit, 0, ',', '.') }}</p></div>
                </div>

                <div x-show="show" x-transition:enter="transition ease-out duration-500 delay-200" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Grafik Arus Kas Harian</h3>
                        <canvas id="financialChart"></canvas>
                    </div>
                </div>

                <div x-show="show" x-transition:enter="transition ease-out duration-500 delay-400" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Detail Transaksi ({{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }})</h3>
                        </div>
                        <div class="mb-6 flex justify-end gap-x-4 print-hidden">
                            <a href="{{ route('manager.reports.financial.export', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-file-excel mr-2"></i>
                                Download Excel
                            </a>
                            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 print-hidden">
                                <i class="fas fa-print mr-2"></i>
                                Cetak Laporan
                            </button>
                        </div>
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
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-6 py-4">{{ $cashFlow->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-6 py-4">{{ $cashFlow->description }}</td>
                                        <td class="px-6 py-4">{{ $cashFlow->user->name }}</td>
                                        <td class="px-6 py-4 text-right font-medium @if($cashFlow->type == 'in') text-green-600 dark:text-green-500 @else text-red-600 dark:text-red-500 @endif">
                                            @if($cashFlow->type == 'in') + @else - @endif
                                            Rp {{ number_format($cashFlow->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center"><p class="text-gray-500">Tidak ada data transaksi pada rentang tanggal ini.</p></td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    @if($chartData && count($chartData['labels']) > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('financialChart').getContext('2d');
            const financialChart = new Chart(ctx, {
                type: 'line', // Jenis grafik: garis
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: @json($chartData['income']),
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: 'Pengeluaran',
                            data: @json($chartData['expense']),
                            backgroundColor: 'rgba(239, 68, 68, 0.2)',
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endif
</x-app-layout>