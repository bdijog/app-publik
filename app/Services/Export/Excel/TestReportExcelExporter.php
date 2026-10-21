<?php

namespace App\Services\Export\Excel;

use App\Models\TestReport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TestReportExcelExporter
{
    /**
     * Ekspor daftar Laporan Hasil Uji (LHU) resmi laboratorium.
     *
     * @param  Builder<TestReport>|null  $query
     */
    public static function export(?Builder $query = null, string $filename = 'Rekapitulasi_LHU_Resmi'): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('LHU Resmi');

        SpreadsheetExporter::addHeaderLogo($sheet, 'J1', 38);
        $sheet->setCellValue('A1', 'BALAI LABORATORIUM KESEHATAN MASYARAKAT AMBON');
        $sheet->setCellValue('A2', 'REKAPITULASI LAPORAN HASIL PENGUJIAN (LHU) RESMI');
        $sheet->setCellValue('A3', 'Diekspor pada: '.now()->translatedFormat('d F Y, H:i').' WIT');

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor(new Color('0F766E'));
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9)->setColor(new Color('64748B'));

        $headers = [
            'A5' => 'No.',
            'B5' => 'Nomor LHU',
            'C5' => 'Edisi/Versi',
            'D5' => 'Nomor FPPS',
            'E5' => 'Nama Pemohon',
            'F5' => 'Instansi',
            'G5' => 'Kelompok Layanan',
            'H5' => 'Tgl. Pengesahan (Final)',
            'I5' => 'Pejabat Penandatangan',
            'J5' => 'Status Dokumen',
            'K5' => 'Status Serah Terima',
        ];

        foreach ($headers as $cell => $title) {
            $sheet->setCellValue($cell, $title);
        }
        SpreadsheetExporter::applyHeaderStyle($sheet, 'A5:K5', '047857');

        $baseQuery = $query ?? TestReport::query()->latest('created_at');
        $cursor = $baseQuery->with(['testRequest.customer', 'testRequest.serviceGroup', 'issuer', 'handovers'])->cursor();

        $row = 6;
        $no = 1;
        foreach ($cursor as $rep) {
            $hasHandover = $rep->handovers->isNotEmpty();
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", $rep->report_number);
            $sheet->setCellValue("C{$row}", 'v'.($rep->version ?? 1));
            $sheet->setCellValue("D{$row}", $rep->testRequest?->fpps_number ?? '-');
            $sheet->setCellValue("E{$row}", $rep->testRequest?->customer?->name ?? '-');
            $sheet->setCellValue("F{$row}", $rep->testRequest?->customer?->organization_name ?? '-');
            $sheet->setCellValue("G{$row}", $rep->testRequest?->serviceGroup?->name ?? '-');
            $sheet->setCellValue("H{$row}", $rep->issued_at ? Carbon::parse($rep->issued_at)->format('d/m/Y H:i') : '-');
            $sheet->setCellValue("I{$row}", $rep->issuer?->name ?? '-');
            $sheet->setCellValue("J{$row}", strtoupper((string) ($rep->status?->value ?? $rep->status)));
            $sheet->setCellValue("K{$row}", $hasHandover ? 'SUDAH DISERAHKAN' : 'BELUM DIAMBIL');

            SpreadsheetExporter::applyDataBorders($sheet, "A{$row}:K{$row}");
            $row++;
        }

        SpreadsheetExporter::autoFitColumns($sheet, 'A', 'K');
        $sheet->setAutoFilter('A5:K'.max($row - 1, 5));
        $sheet->freezePane('A6');

        return SpreadsheetExporter::streamSpreadsheet($spreadsheet, $filename.'_'.date('Ymd_His').'.xlsx');
    }
}
