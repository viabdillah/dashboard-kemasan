<?php

namespace App\Exports;

use App\Models\CashFlow;
use App\Models\Order;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ActivityLogExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $summary;

    public function __construct(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection(): Collection
    {
        // 1. Ambil data Pengeluaran Kas
        $expenses = CashFlow::with('user')
            ->where('type', 'out')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get()
            ->map(function ($item) {
                return (object) [
                    'timestamp' => $item->created_at,
                    'type' => 'Pengeluaran Kas',
                    'description' => $item->description,
                    'user' => $item->user->name,
                    'amount' => -$item->amount // as negative
                ];
            });

        // 2. Ambil data Pemasukan Barang
        $stockAdditions = StockMovement::with('user', 'productVariant.product')
            ->where('quantity_change', '>', 0)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get()
            ->map(function ($item) {
                return (object) [
                    'timestamp' => $item->created_at,
                    'type' => 'Pemasukan Barang',
                    'description' => 'Stok ' . $item->productVariant->product->name . ' (' . $item->productVariant->name . ') ditambah ' . $item->quantity_change,
                    'user' => $item->user->name,
                    'amount' => null // No monetary value
                ];
            });

        // 3. Ambil data Update Produksi
        $productionUpdates = Order::whereIn('status', ['sedang_diproduksi', 'produksi_selesai'])
            ->whereBetween('updated_at', [$this->startDate, $this->endDate])
            ->get()
            ->map(function ($item) {
                $statusText = $item->status == 'sedang_diproduksi' ? 'Mulai Produksi' : 'Produksi Selesai';
                return (object) [
                    'timestamp' => $item->updated_at,
                    'type' => 'Update Produksi',
                    'description' => $statusText . ' untuk pesanan #' . $item->id . ' (' . $item->product_name . ')',
                    'user' => 'Operator', // Assuming Operator did this
                    'amount' => null // No monetary value
                ];
            });

        // 4. Ambil data Pemasukan dari Pembayaran
        $incomes = Order::where('status', 'selesai')
            ->whereBetween('paid_at', [$this->startDate, $this->endDate])
            ->get()
            ->map(function ($item) {
                return (object) [
                    'timestamp' => $item->paid_at,
                    'type' => 'Pemasukan Kas',
                    'description' => 'Pembayaran pesanan #' . $item->id . ' - ' . $item->customer_name,
                    'user' => 'Sistem', // Or find the cashier who processed it if needed
                    'amount' => $item->price_per_piece * $item->quantity
                ];
            });

        // Gabungkan semua data, urutkan
        $allActivities = $expenses->concat($stockAdditions)->concat($productionUpdates)->concat($incomes)->sortByDesc('timestamp');

        // Hitung ringkasan total
        $totalIncome = $allActivities->where('type', 'Pemasukan Kas')->sum('amount');
        $totalExpense = abs($allActivities->where('type', 'Pengeluaran Kas')->sum('amount')); // abs() to make it positive
        $this->summary = [
            'Total Pemasukan' => $totalIncome,
            'Total Pengeluaran' => $totalExpense
        ];

        return $allActivities;
    }

    public function headings(): array
    {
        return ['Tanggal', 'Tipe Aktivitas', 'Deskripsi', 'Pelaku', 'Nilai (Rp)'];
    }

    public function map($activity): array
    {
        return [
            $activity->timestamp->format('d-m-Y H:i:s'),
            $activity->type,
            $activity->description,
            $activity->user,
            $activity->amount,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $sheet->getStyle('A1:E1')->getFont()->setBold(true);

                // Menambahkan ringkasan di atas
                $sheet->insertNewRowBefore(1, 4);
                $sheet->setCellValue('A1', 'Log Aktivitas Lengkap');
                $sheet->setCellValue('A2', 'Periode: ' . $this->startDate->format('d M Y') . ' - ' . $this->endDate->format('d M Y'));
                $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);

                $sheet->setCellValue('D3', 'Total Pemasukan (dari Penjualan)');
                $sheet->setCellValue('E3', $this->summary['Total Pemasukan']);
                $sheet->setCellValue('D4', 'Total Pengeluaran (dari Buku Kas)');
                $sheet->setCellValue('E4', $this->summary['Total Pengeluaran']);
                $sheet->getStyle('D3:E4')->getFont()->setBold(true);

                // Format kolom Rupiah
                $sheet->getStyle('E')->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
                $sheet->getDelegate()->getColumnDimension('A')->setWidth(20);
                $sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $sheet->getDelegate()->getColumnDimension('C')->setWidth(50);
                $sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
            },
        ];
    }
}