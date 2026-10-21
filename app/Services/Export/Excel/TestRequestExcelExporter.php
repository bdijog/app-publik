<?php

namespace App\Services\Export\Excel;

use App\Models\TestRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TestRequestExcelExporter
{
    /**
     * Ekspor daftar permohonan pengujian dari query builder atau query aktif tabel Filament.
     *
     * @param  Builder<TestRequest>|null  $query
     */
    public static function export(?Builder $query = null, string $filename = 'Daftar_Permohonan_Pengujian'): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Permohonan Pengujian');

        // Header Title & Logo
        SpreadsheetExporter::addHeaderLogo($sheet, 'K1', 38);
        $sheet->setCellValue('A1', 'BALAI LABORATORIUM KESEHATAN MASYARAKAT AMBON');
        $sheet->setCellValue('A2', 'DAFTAR PERMOHONAN PEMERIKSAAN LABORATORIUM (FPPS)');
        $sheet->setCellValue('A3', 'Diekspor pada: '.now()->translatedFormat('d F Y, H:i').' WIT oleh '.(auth('employee')->user()?->name ?? 'Sistem'));

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor(new Color('0F766E'));
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9)->setColor(new Color('64748B'));

        $headers = [
            'A5' => 'No.',
            'B5' => 'Nomor FPPS',
            'C5' => 'Nomor Registrasi',
            'D5' => 'Tgl. Registrasi',
            'E5' => 'Nama Pemohon',
            'F5' => 'Instansi / Perusahaan',
            'G5' => 'Kelompok Layanan',
            'H5' => 'Kanal',
            'I5' => 'Biaya Retribusi',
            'J5' => 'Status Pembayaran',
            'K5' => 'Status Alur',
            'L5' => 'Target SLA',
        ];

        foreach ($headers as $cell => $title) {
            $sheet->setCellValue($cell, $title);
        }
        SpreadsheetExporter::applyHeaderStyle($sheet, 'A5:L5');

        $baseQuery = $query ?? TestRequest::query()->latest('created_at');
        $cursor = $baseQuery->with(['customer', 'serviceGroup'])->cursor();

        $row = 6;
        $no = 1;
        foreach ($cursor as $item) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", $item->fpps_number);
            $sheet->setCellValue("C{$row}", $item->request_number);
            $sheet->setCellValue("D{$row}", $item->registered_at ? Carbon::parse($item->registered_at)->format('d/m/Y H:i') : '-');
            $sheet->setCellValue("E{$row}", $item->customer?->name ?? '-');
            $sheet->setCellValue("F{$row}", $item->customer?->organization_name ?? '-');
            $sheet->setCellValue("G{$row}", $item->serviceGroup?->name ?? '-');
            $sheet->setCellValue("H{$row}", strtoupper((string) ($item->channel?->value ?? $item->channel)));
            $sheet->setCellValue("I{$row}", (float) $item->total_amount);
            $sheet->setCellValue("J{$row}", strtoupper((string) ($item->payment_status?->value ?? $item->payment_status)));
            $sheet->setCellValue("K{$row}", strtoupper((string) ($item->status?->value ?? $item->status)));
            $sheet->setCellValue("L{$row}", $item->estimated_completion_date ? Carbon::parse($item->estimated_completion_date)->format('d/m/Y') : '-');

            SpreadsheetExporter::formatCurrency($sheet, "I{$row}");
            SpreadsheetExporter::applyDataBorders($sheet, "A{$row}:L{$row}");
            $row++;
        }

        if ($row > 6) {
            $sheet->setCellValue("A{$row}", 'TOTAL RETRIBUSI');
            $sheet->mergeCells("A{$row}:H{$row}");
            $sheet->setCellValue("I{$row}", '=SUM(I6:I'.($row - 1).')');
            SpreadsheetExporter::applyTotalRowStyle($sheet, "A{$row}:L{$row}");
            SpreadsheetExporter::formatCurrency($sheet, "I{$row}");
        }

        SpreadsheetExporter::autoFitColumns($sheet, 'A', 'L');
        $sheet->setAutoFilter('A5:L'.max($row - 1, 5));
        $sheet->freezePane('A6');

        return SpreadsheetExporter::streamSpreadsheet($spreadsheet, $filename.'_'.date('Ymd_His').'.xlsx');
    }
}
