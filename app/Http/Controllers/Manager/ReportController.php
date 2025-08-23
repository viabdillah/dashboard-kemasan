<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\CashFlow;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\FinancialReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Order;
use App\Models\StockMovement;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exports\ActivityLogExport;

class ReportController extends Controller
{
   public function financialIndex(Request $request)
    {
        $cashFlows = null;
        $totalIncome = 0;
        $totalExpense = 0;
        $netProfit = 0;
        $startDate = null;
        $endDate = null;
        $chartData = null; // Variabel untuk data chart

        if ($request->has('start_date') && $request->has('end_date')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $cashFlows = CashFlow::with('user')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->latest('created_at')
                                ->get();

            $totalIncome = $cashFlows->where('type', 'in')->sum('amount');
            $totalExpense = $cashFlows->where('type', 'out')->sum('amount');
            $netProfit = $totalIncome - $totalExpense;

            // --- BAGIAN BARU UNTUK MEMPROSES DATA GRAFIK ---
            $dailyData = $cashFlows
                ->sortBy('created_at') // Urutkan dari tanggal terlama
                ->groupBy(function ($transaction) {
                    return Carbon::parse($transaction->created_at)->format('d M'); // Grup per hari
                });

            $labels = [];
            $incomeData = [];
            $expenseData = [];

            foreach ($dailyData as $day => $transactions) {
                $labels[] = $day;
                $incomeData[] = $transactions->where('type', 'in')->sum('amount');
                $expenseData[] = $transactions->where('type', 'out')->sum('amount');
            }

            $chartData = [
                'labels' => $labels,
                'income' => $incomeData,
                'expense' => $expenseData,
            ];
            // --- AKHIR BAGIAN BARU ---
        }

        return view('manager.reports.financial', compact(
            'cashFlows',
            'totalIncome',
            'totalExpense',
            'netProfit',
            'startDate',
            'endDate',
            'chartData' // Kirim data chart ke view
        ));
    }
    // [BARU] Method untuk menangani download Excel
    public function exportFinancialReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $fileName = 'Laporan Keuangan ' . $startDate->format('d-m-Y') . ' sampai ' . $endDate->format('d-m-Y') . '.xlsx';

        return Excel::download(new FinancialReportExport($startDate, $endDate), $fileName);
    }

    public function activityLog(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfDay();

        // 1. Ambil data Pengeluaran Kas
        $expenses = CashFlow::with('user')
            ->where('type', 'out')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($item) {
                return (object) [
                    'timestamp' => $item->created_at,
                    'type' => 'Pengeluaran Kas',
                    'description' => $item->description . ' (Rp ' . number_format($item->amount) . ')',
                    'user' => $item->user->name,
                    'icon' => 'fa-arrow-circle-up text-red-500',
                ];
            });

        // 2. Ambil data Pemasukan Barang
        $stockAdditions = StockMovement::with('user', 'productVariant.product')
            ->where('quantity_change', '>', 0)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($item) {
                return (object) [
                    'timestamp' => $item->created_at,
                    'type' => 'Pemasukan Barang',
                    'description' => 'Stok ' . $item->productVariant->product->name . ' (' . $item->productVariant->name . ') ditambah sebanyak ' . $item->quantity_change,
                    'user' => $item->user->name,
                    'icon' => 'fa-arrow-circle-down text-green-500',
                ];
            });

        // 3. Ambil data Update Produksi
        $productionUpdates = Order::whereIn('status', ['sedang_diproduksi', 'produksi_selesai'])
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->get()
            ->map(function ($item) {
                $statusText = $item->status == 'sedang_diproduksi' ? 'Mulai Produksi' : 'Produksi Selesai';
                return (object) [
                    'timestamp' => $item->updated_at,
                    'type' => 'Update Produksi',
                    'description' => $statusText . ' untuk pesanan #' . $item->id . ' (' . $item->product_name . ')',
                    'user' => 'Sistem', // Diasumsikan diubah oleh Operator, bisa diubah jika ada relasi
                    'icon' => 'fa-cogs text-yellow-500',
                ];
            });

        // Gabungkan semua data, urutkan, dan buat pagination manual
        $allActivities = $expenses->concat($stockAdditions)->concat($productionUpdates)->sortByDesc('timestamp');

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $currentPageItems = $allActivities->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedActivities = new LengthAwarePaginator($currentPageItems, count($allActivities), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        return view('manager.reports.activity_log', [
            'activities' => $paginatedActivities
        ]);
    }
    public function exportActivityLog(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $fileName = 'Log Aktivitas ' . $startDate->format('d-m-Y') . ' sampai ' . $endDate->format('d-m-Y') . '.xlsx';

        return Excel::download(new ActivityLogExport($startDate, $endDate), $fileName);
    }
}