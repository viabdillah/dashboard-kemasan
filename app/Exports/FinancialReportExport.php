<?php

namespace App\Exports;

use App\Models\CashFlow;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

class FinancialReportExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $summary;

    public function __construct(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $cashFlows = CashFlow::with('user')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->latest('created_at')
            ->get();

        // Hitung ringkasan
        $totalIncome = $cashFlows->where('type', 'in')->sum('amount');
        $totalExpense = $cashFlows->where('type', 'out')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        $this->summary = [
            'Total Pemasukan' => $totalIncome,
            'Total Pengeluaran' => $totalExpense,
            'Laba / Rugi Bersih' => $netProfit,
        ];

        return $cashFlows;
    }

    public function headings(): array
    {
        // Ini akan menjadi header tabel
        return [
            'Tanggal',
            'Keterangan',
            'Tipe',
            'Jumlah (Rp)',
            'Dicatat Oleh',
        ];
    }

    public function map($cashFlow): array
    {
        // Ini memformat setiap baris data
        return [
            Carbon::parse($cashFlow->created_at)->format('d-m-Y H:i:s'),
            $cashFlow->description,
            $cashFlow->type == 'in' ? 'Pemasukan' : 'Pengeluaran',
            $cashFlow->amount,
            $cashFlow->user->name,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;

                // Menambahkan baris ringkasan di atas
                $sheet->insertNewRowBefore(1, 5);

                // Judul Laporan
                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', 'Laporan Keuangan');

                // Rentang Tanggal
                $sheet->mergeCells('A2:E2');
                $sheet->setCellValue('A2', 'Periode: ' . $this->startDate->format('d M Y') . ' - ' . $this->endDate->format('d M Y'));

                // Data Ringkasan
                $sheet->setCellValue('D4', 'Total Pemasukan');
                $sheet->setCellValue('E4', $this->summary['Total Pemasukan']);
                $sheet->setCellValue('D5', 'Total Pengeluaran');
                $sheet->setCellValue('E5', $this->summary['Total Pengeluaran']);
                $sheet->setCellValue('D6', 'Laba / Rugi Bersih');
                $sheet->setCellValue('E6', $this->summary['Laba / Rugi Bersih']);

                // Memberi Style
                $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('D4:E6')->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
                $sheet->getStyle('E8:E' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
                $sheet->getStyle('A7:E7')->getFont()->setBold(true); // Header Tabel
            },
        ];
    }
}