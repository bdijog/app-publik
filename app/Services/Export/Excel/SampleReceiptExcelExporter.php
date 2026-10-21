<?php

namespace App\Services\Export\Excel;

use App\Models\SampleReceipt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SampleReceiptExcelExporter
{
    /**
     * Ekspor data log penerimaan dan verifikasi kelayakan sampel fisik.
     *
     * @param  Builder<SampleReceipt>|null  $query
     */
    public static function export(?Builder $query = null, string $filename = 'Log_Penerimaan_Sampel'): StreamedResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Penerimaan Sampel');

        SpreadsheetExporter::addHeaderLogo($sheet, 'K1', 38);
        $sheet->setCellValue('A1', 'BALAI LABORATORIUM KESEHATAN MASYARAKAT AMBON');
        $sheet->setCellValue('A2', 'LOG PENERIMAAN DAN VERIFIKASI KELAYAKAN SPESIMEN FISIK');
        $sheet->setCellValue('A3', 'Diekspor pada: '.now()->translatedFormat('d F Y, H:i').' WIT');

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor(new Color('0F766E'));
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9)->setColor(new Color('64748B'));

        $headers = [
            'A5' => 'No.',
            'B5' => 'Kode Sampel',
            'C5' => 'Nama Sampel / Spesimen',
            'D5' => 'Nomor FPPS',
            'E5' => 'Pemohon',
            'F5' => 'Waktu Penerimaan',
            'G5' => 'Petugas Penerima',
            'H5' => 'Jumlah / Volume',
            'I5' => 'Kondisi Fisik',
            'J5' => 'Keputusan Verifikasi',
            'K5' => 'Alasan Penolakan',
            'L5' => 'Rekomendasi Tindak Lanjut',
        ];

        foreach ($headers as $cell => $title) {
            $sheet->setCellValue($cell, $title);
        }
        SpreadsheetExporter::applyHeaderStyle($sheet, 'A5:L5', '0F766E');

        $baseQuery = $query ?? SampleReceipt::query()->latest('received_at');
        $cursor = $baseQuery->with(['sample.testRequest.customer', 'sample.sampleType', 'receivedBy'])->cursor();

        $row = 6;
        $no = 1;
        foreach ($cursor as $rcp) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", $rcp->sample?->sample_code ?? '-');
            $sheet->setCellValue("C{$row}", $rcp->sample?->sample_name ?? '-');
            $sheet->setCellValue("D{$row}", $rcp->sample?->testRequest?->fpps_number ?? '-');
            $sheet->setCellValue("E{$row}", $rcp->sample?->testRequest?->customer?->name ?? '-');
            $sheet->setCellValue("F{$row}", $rcp->received_at ? Carbon::parse($rcp->received_at)->format('d/m/Y H:i') : '-');
            $sheet->setCellValue("G{$row}", $rcp->receivedBy?->name ?? '-');
            $sheet->setCellValue("H{$row}", ($rcp->received_volume ?? $rcp->sample?->quantity ?? 1).' '.($rcp->sample?->unit ?? 'Sampel'));
            $sheet->setCellValue("I{$row}", $rcp->condition ?? 'Baik');
            $sheet->setCellValue("J{$row}", strtoupper((string) ($rcp->decision?->value ?? $rcp->decision)));
            $sheet->setCellValue("K{$row}", $rcp->rejection_reason ?? '-');
            $sheet->setCellValue("L{$row}", $rcp->follow_up ?? '-');

            SpreadsheetExporter::applyDataBorders($sheet, "A{$row}:L{$row}");
            $row++;
        }

        SpreadsheetExporter::autoFitColumns($sheet, 'A', 'L');
        $sheet->setAutoFilter('A5:L'.max($row - 1, 5));
        $sheet->freezePane('A6');

        return SpreadsheetExporter::streamSpreadsheet($spreadsheet, $filename.'_'.date('Ymd_His').'.xlsx');
    }
}
