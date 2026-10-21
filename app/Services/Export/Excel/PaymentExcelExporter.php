<?php

namespace App\Services\Export\Excel;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentExcelExporter
{
    /**
     * Ekspor data transaksi pembayaran retribusi dari query tabel Filament.
     *
     * @param  Builder<Payment>|null  $query
     */
    public static function export(?Builder $query = null, string $filename = 'Buku_Kas_Penerimaan_Retribusi'): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Penerimaan Retribusi');

        SpreadsheetExporter::addHeaderLogo($sheet, 'I1', 38);
        $sheet->setCellValue('A1', 'BALAI LABORATORIUM KESEHATAN MASYARAKAT AMBON');
        $sheet->setCellValue('A2', 'BUKU KAS PENERIMAAN RETRIBUSI PELAYANAN LABORATORIUM');
        $sheet->setCellValue('A3', 'Diekspor pada: '.now()->translatedFormat('d F Y, H:i').' WIT oleh '.(auth('employee')->user()?->name ?? 'Kasir'));

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor(new Color('047857'));
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9)->setColor(new Color('64748B'));

        $headers = [
            'A5' => 'No.',
            'B5' => 'ID Kuitansi',
            'C5' => 'Waktu Bayar',
            'D5' => 'Nomor FPPS',
            'E5' => 'Nama Pemohon / Wajib Retribusi',
            'F5' => 'Instansi',
            'G5' => 'Kelompok Layanan',
            'H5' => 'Petugas Kasir',
            'I5' => 'Nominal Bayar (Rp)',
            'J5' => 'Status Transaksi',
        ];

        foreach ($headers as $cell => $title) {
            $sheet->setCellValue($cell, $title);
        }
        SpreadsheetExporter::applyHeaderStyle($sheet, 'A5:J5', '047857');

        $baseQuery = $query ?? Payment::query()->latest('paid_at');
        $cursor = $baseQuery->with(['testRequest.customer', 'testRequest.serviceGroup', 'recordedBy'])->cursor();

        $row = 6;
        $no = 1;
        foreach ($cursor as $pay) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", 'KWT/'.date('Ym').'/'.sprintf('%05d', $pay->id));
            $sheet->setCellValue("C{$row}", $pay->paid_at ? Carbon::parse($pay->paid_at)->format('d/m/Y H:i') : '-');
            $sheet->setCellValue("D{$row}", $pay->testRequest?->fpps_number ?? '-');
            $sheet->setCellValue("E{$row}", $pay->testRequest?->customer?->name ?? '-');
            $sheet->setCellValue("F{$row}", $pay->testRequest?->customer?->organization_name ?? '-');
            $sheet->setCellValue("G{$row}", $pay->testRequest?->serviceGroup?->name ?? '-');
            $sheet->setCellValue("H{$row}", $pay->recordedBy?->name ?? 'Kasir Loket');
            $sheet->setCellValue("I{$row}", (float) $pay->amount);
            $sheet->setCellValue("J{$row}", strtoupper((string) ($pay->status?->value ?? $pay->status)));

            SpreadsheetExporter::formatCurrency($sheet, "I{$row}");
            SpreadsheetExporter::applyDataBorders($sheet, "A{$row}:J{$row}");
            $row++;
        }

        if ($row > 6) {
            $sheet->setCellValue("A{$row}", 'TOTAL REALISASI PENERIMAAN');
            $sheet->mergeCells("A{$row}:H{$row}");
            $sheet->setCellValue("I{$row}", '=SUM(I6:I'.($row - 1).')');
            SpreadsheetExporter::applyTotalRowStyle($sheet, "A{$row}:J{$row}");
            SpreadsheetExporter::formatCurrency($sheet, "I{$row}");
        }

        SpreadsheetExporter::autoFitColumns($sheet, 'A', 'J');
        $sheet->setAutoFilter('A5:J'.max($row - 1, 5));
        $sheet->freezePane('A6');

        return SpreadsheetExporter::streamSpreadsheet($spreadsheet, $filename.'_'.date('Ymd_His').'.xlsx');
    }
}
