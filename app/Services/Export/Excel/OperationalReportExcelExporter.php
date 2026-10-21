<?php

namespace App\Services\Export\Excel;

use App\Models\Payment;
use App\Models\Sample;
use App\Models\SampleReceipt;
use App\Models\ServiceGroup;
use App\Models\TestReport;
use App\Models\TestRequest;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OperationalReportExcelExporter
{
    /**
     * Hasilkan berkas multi-sheet workbook Excel komprehensif berdasarkan rentang tanggal.
     */
    public static function export(?string $startDate = null, ?string $endDate = null): StreamedResponse
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : now()->startOfMonth()->startOfDay();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : now()->endOfMonth()->endOfDay();

        $spreadsheet = new Spreadsheet;
        $spreadsheet->getProperties()
            ->setCreator('Balai Laboratorium Kesehatan Masyarakat Ambon')
            ->setLastModifiedBy(auth('employee')->user()?->name ?? 'Sistem Balai')
            ->setTitle('Laporan Rekapitulasi Operasional Laboratorium')
            ->setSubject('Rekapitulasi Layanan, Retribusi, & Mutu Sampel')
            ->setDescription('Dokumen resmi rekapitulasi data operasional Balai Labkesmas Ambon.');

        // 1. Sheet 1: Ringkasan Eksekutif & KPI
        $sheetSummary = $spreadsheet->getActiveSheet();
        $sheetSummary->setTitle('Ringkasan Eksekutif');
        static::buildSummarySheet($sheetSummary, $start, $end);

        // 2. Sheet 2: Data Permohonan (FPPS)
        $sheetRequests = $spreadsheet->createSheet();
        $sheetRequests->setTitle('Rincian Permohonan');
        static::buildRequestsSheet($sheetRequests, $start, $end);

        // 3. Sheet 3: Penerimaan Retribusi
        $sheetPayments = $spreadsheet->createSheet();
        $sheetPayments->setTitle('Penerimaan Retribusi');
        static::buildPaymentsSheet($sheetPayments, $start, $end);

        // 4. Sheet 4: Mutu & Penerimaan Sampel
        $sheetSamples = $spreadsheet->createSheet();
        $sheetSamples->setTitle('Penerimaan Sampel');
        static::buildSamplesSheet($sheetSamples, $start, $end);

        // Aktifkan sheet pertama
        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'Laporan_Operasional_Labkesmas_'.$start->format('Ymd').'_'.$end->format('Ymd').'.xlsx';

        return SpreadsheetExporter::streamSpreadsheet($spreadsheet, $filename);
    }

    /**
     * Membangun lembar kerja Ringkasan Eksekutif & Distribusi Layanan.
     */
    protected static function buildSummarySheet(Worksheet $sheet, Carbon $start, Carbon $end): void
    {
        // Judul Kop Lembar Kerja
        SpreadsheetExporter::addHeaderLogo($sheet, 'D1', 40);
        $sheet->setCellValue('A1', 'KEMENTERIAN KESEHATAN REPUBLIK INDONESIA - BALAI LABKESMAS AMBON');
        $sheet->setCellValue('A2', 'REKAPITULASI EKSEKUTIF OPERASIONAL LABORATORIUM');
        $sheet->setCellValue('A3', 'Periode Data: '.$start->translatedFormat('d F Y').' s.d. '.$end->translatedFormat('d F Y').' | Dicetak: '.now()->translatedFormat('d F Y, H:i').' WIT');

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(11)->setColor(new Color('0F766E'));
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14)->setColor(new Color('0F172A'));
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9)->setColor(new Color('475569'));

        // Statistik Metrik Utama
        $totalRequests = TestRequest::whereBetween('created_at', [$start, $end])->count();
        $totalRevenue = (float) Payment::whereBetween('created_at', [$start, $end])->sum('amount');
        $totalSamples = Sample::whereBetween('created_at', [$start, $end])->count();
        $totalReportsIssued = TestReport::where('status', 'final')->whereBetween('issued_at', [$start, $end])->count();
        $paidRequests = TestRequest::whereBetween('created_at', [$start, $end])->where('payment_status', 'paid')->count();

        $sheet->setCellValue('A5', 'INDIKATOR KINERJA UTAMA (KPI)');
        $sheet->setCellValue('B5', 'NILAI CAPAIAN');
        $sheet->setCellValue('C5', 'KETERANGAN');
        SpreadsheetExporter::applyHeaderStyle($sheet, 'A5:C5');

        $kpis = [
            ['Total Permohonan Pengujian Terdaftar', $totalRequests, 'Berkas FPPS masuk (Online, Loket, WhatsApp)'],
            ['Total Sampel / Spesimen Diterima', $totalSamples, 'Spesimen fisik yang masuk ke laboratorium'],
            ['Realisasi Penerimaan Retribusi Pelayanan', $totalRevenue, 'Total penerimaan kas disetor'],
            ['Laporan Hasil Uji (LHU) Resmi Terbit', $totalReportsIssued, 'LHU status Final bersertifikasi'],
            ['Tingkat Pelunasan Retribusi', $totalRequests > 0 ? ($paidRequests / $totalRequests) : 1, 'Rasio permohonan lunas bayar'],
        ];

        $row = 6;
        foreach ($kpis as $kpi) {
            $sheet->setCellValue("A{$row}", $kpi[0]);
            $sheet->setCellValue("B{$row}", $kpi[1]);
            $sheet->setCellValue("C{$row}", $kpi[2]);

            if ($row === 8) {
                SpreadsheetExporter::formatCurrency($sheet, "B{$row}");
            } elseif ($row === 10) {
                SpreadsheetExporter::formatPercentage($sheet, "B{$row}");
            }

            SpreadsheetExporter::applyDataBorders($sheet, "A{$row}:C{$row}");
            $row++;
        }

        // Distribusi 17 Kelompok Layanan Brosur
        $row += 2;
        $sheet->setCellValue("A{$row}", 'DISTRIBUSI KELOMPOK LAYANAN (BROSUR LABKESMAS)');
        $sheet->setCellValue("B{$row}", 'JUMLAH PERMOHONAN');
        $sheet->setCellValue("C{$row}", 'ESTIMASI RETRIBUSI');
        $sheet->setCellValue("D{$row}", 'PERSENTASE BEBAN (%)');
        SpreadsheetExporter::applyHeaderStyle($sheet, "A{$row}:D{$row}", '047857');

        $startServiceRow = $row + 1;
        $services = ServiceGroup::withCount([
            'testRequests' => fn ($q) => $q->whereBetween('created_at', [$start, $end]),
        ])->get();

        $curRow = $startServiceRow;
        foreach ($services as $srv) {
            $srvRevenue = (float) TestRequest::where('service_group_id', $srv->id)
                ->whereBetween('created_at', [$start, $end])
                ->sum('total_amount');

            $sheet->setCellValue("A{$curRow}", $srv->name);
            $sheet->setCellValue("B{$curRow}", $srv->test_requests_count);
            $sheet->setCellValue("C{$curRow}", $srvRevenue);
            $sheet->setCellValue("D{$curRow}", $totalRequests > 0 ? ($srv->test_requests_count / $totalRequests) : 0);

            SpreadsheetExporter::formatCurrency($sheet, "C{$curRow}");
            SpreadsheetExporter::formatPercentage($sheet, "D{$curRow}");
            SpreadsheetExporter::applyDataBorders($sheet, "A{$curRow}:D{$curRow}");
            $curRow++;
        }

        // Baris Total Layanan
        $sheet->setCellValue("A{$curRow}", 'TOTAL KESELURUHAN');
        $sheet->setCellValue("B{$curRow}", "=SUM(B{$startServiceRow}:B".($curRow - 1).')');
        $sheet->setCellValue("C{$curRow}", "=SUM(C{$startServiceRow}:C".($curRow - 1).')');
        $sheet->setCellValue("D{$curRow}", "=SUM(D{$startServiceRow}:D".($curRow - 1).')');

        SpreadsheetExporter::applyTotalRowStyle($sheet, "A{$curRow}:D{$curRow}");
        SpreadsheetExporter::formatCurrency($sheet, "C{$curRow}");
        SpreadsheetExporter::formatPercentage($sheet, "D{$curRow}");

        SpreadsheetExporter::autoFitColumns($sheet, 'A', 'D');
    }

    /**
     * Membangun lembar kerja Rincian Permohonan (FPPS).
     */
    protected static function buildRequestsSheet(Worksheet $sheet, Carbon $start, Carbon $end): void
    {
        SpreadsheetExporter::addHeaderLogo($sheet, 'K1', 36);
        $sheet->setCellValue('A1', 'REKAPITULASI PERMOHONAN PEMERIKSAAN LABORATORIUM (FPPS)');
        $sheet->setCellValue('A2', 'Periode: '.$start->format('d/m/Y').' s.d. '.$end->format('d/m/Y'));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);

        $headers = [
            'A4' => 'No.',
            'B4' => 'Nomor FPPS',
            'C4' => 'Nomor Registrasi',
            'D4' => 'Waktu Registrasi',
            'E4' => 'Nama Pemohon / Pelanggan',
            'F4' => 'Instansi / Perusahaan',
            'G4' => 'Kelompok Layanan',
            'H4' => 'Kanal',
            'I4' => 'Tarif Retribusi',
            'J4' => 'Status Pembayaran',
            'K4' => 'Status Pengerjaan',
            'L4' => 'Target SLA',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }
        SpreadsheetExporter::applyHeaderStyle($sheet, 'A4:L4');

        $requests = TestRequest::with(['customer', 'serviceGroup'])
            ->whereBetween('created_at', [$start, $end])
            ->latest('created_at')
            ->cursor();

        $row = 5;
        $no = 1;
        foreach ($requests as $req) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", $req->fpps_number);
            $sheet->setCellValue("C{$row}", $req->request_number);
            $sheet->setCellValue("D{$row}", $req->registered_at ? Carbon::parse($req->registered_at)->format('d/m/Y H:i') : '-');
            $sheet->setCellValue("E{$row}", $req->customer?->name ?? '-');
            $sheet->setCellValue("F{$row}", $req->customer?->organization_name ?? '-');
            $sheet->setCellValue("G{$row}", $req->serviceGroup?->name ?? '-');
            $sheet->setCellValue("H{$row}", strtoupper((string) ($req->channel?->value ?? $req->channel)));
            $sheet->setCellValue("I{$row}", (float) $req->total_amount);
            $sheet->setCellValue("J{$row}", strtoupper((string) ($req->payment_status?->value ?? $req->payment_status)));
            $sheet->setCellValue("K{$row}", strtoupper((string) ($req->status?->value ?? $req->status)));
            $sheet->setCellValue("L{$row}", $req->estimated_completion_date ? Carbon::parse($req->estimated_completion_date)->format('d/m/Y') : '-');

            SpreadsheetExporter::formatCurrency($sheet, "I{$row}");
            SpreadsheetExporter::applyDataBorders($sheet, "A{$row}:L{$row}");
            $row++;
        }

        if ($row > 5) {
            $sheet->setCellValue("A{$row}", 'TOTAL RETRIBUSI PERMOHONAN');
            $sheet->mergeCells("A{$row}:H{$row}");
            $sheet->setCellValue("I{$row}", '=SUM(I5:I'.($row - 1).')');
            SpreadsheetExporter::applyTotalRowStyle($sheet, "A{$row}:L{$row}");
            SpreadsheetExporter::formatCurrency($sheet, "I{$row}");
        }

        SpreadsheetExporter::autoFitColumns($sheet, 'A', 'L');
        $sheet->setAutoFilter('A4:L'.max($row - 1, 4));
        $sheet->freezePane('A5');
    }

    /**
     * Membangun lembar kerja Penerimaan Retribusi.
     */
    protected static function buildPaymentsSheet(Worksheet $sheet, Carbon $start, Carbon $end): void
    {
        SpreadsheetExporter::addHeaderLogo($sheet, 'H1', 36);
        $sheet->setCellValue('A1', 'BUKU KAS PENERIMAAN RETRIBUSI PELAYANAN LABORATORIUM');
        $sheet->setCellValue('A2', 'Periode: '.$start->format('d/m/Y').' s.d. '.$end->format('d/m/Y'));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);

        $headers = [
            'A4' => 'No.',
            'B4' => 'ID Bukti Kas',
            'C4' => 'Waktu Pembayaran',
            'D4' => 'Nomor FPPS',
            'E4' => 'Nama Pemohon / Wajib Retribusi',
            'F4' => 'Kelompok Layanan',
            'G4' => 'Petugas Kasir',
            'H4' => 'Nominal Retribusi',
            'I4' => 'Status Transaksi',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }
        SpreadsheetExporter::applyHeaderStyle($sheet, 'A4:I4', '047857');

        $payments = Payment::with(['testRequest.customer', 'testRequest.serviceGroup', 'recordedBy'])
            ->whereBetween('created_at', [$start, $end])
            ->latest('paid_at')
            ->cursor();

        $row = 5;
        $no = 1;
        foreach ($payments as $pay) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", 'KWT/'.date('Ym').'/'.sprintf('%05d', $pay->id));
            $sheet->setCellValue("C{$row}", $pay->paid_at ? Carbon::parse($pay->paid_at)->format('d/m/Y H:i') : '-');
            $sheet->setCellValue("D{$row}", $pay->testRequest?->fpps_number ?? '-');
            $sheet->setCellValue("E{$row}", $pay->testRequest?->customer?->name ?? '-');
            $sheet->setCellValue("F{$row}", $pay->testRequest?->serviceGroup?->name ?? '-');
            $sheet->setCellValue("G{$row}", $pay->recordedBy?->name ?? 'Kasir Loket');
            $sheet->setCellValue("H{$row}", (float) $pay->amount);
            $sheet->setCellValue("I{$row}", strtoupper((string) ($pay->status?->value ?? $pay->status)));

            SpreadsheetExporter::formatCurrency($sheet, "H{$row}");
            SpreadsheetExporter::applyDataBorders($sheet, "A{$row}:I{$row}");
            $row++;
        }

        if ($row > 5) {
            $sheet->setCellValue("A{$row}", 'TOTAL REALISASI PENERIMAAN KAS');
            $sheet->mergeCells("A{$row}:G{$row}");
            $sheet->setCellValue("H{$row}", '=SUM(H5:H'.($row - 1).')');
            SpreadsheetExporter::applyTotalRowStyle($sheet, "A{$row}:I{$row}");
            SpreadsheetExporter::formatCurrency($sheet, "H{$row}");
        }

        SpreadsheetExporter::autoFitColumns($sheet, 'A', 'I');
        $sheet->setAutoFilter('A4:I'.max($row - 1, 4));
        $sheet->freezePane('A5');
    }

    /**
     * Membangun lembar kerja Mutu & Penerimaan Sampel Fisik.
     */
    protected static function buildSamplesSheet(Worksheet $sheet, Carbon $start, Carbon $end): void
    {
        SpreadsheetExporter::addHeaderLogo($sheet, 'K1', 36);
        $sheet->setCellValue('A1', 'LOG PENERIMAAN DAN KELAYAKAN SPESIMEN FISIK LABORATORIUM');
        $sheet->setCellValue('A2', 'Periode: '.$start->format('d/m/Y').' s.d. '.$end->format('d/m/Y'));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);

        $headers = [
            'A4' => 'No.',
            'B4' => 'Kode Sampel',
            'C4' => 'Nama Spesimen',
            'D4' => 'Nomor FPPS',
            'E4' => 'Pemohon',
            'F4' => 'Waktu Terima',
            'G4' => 'Petugas Penerima',
            'H4' => 'Volume / Jumlah',
            'I4' => 'Kondisi Fisik',
            'J4' => 'Keputusan Verifikasi',
            'K4' => 'Alasan Penolakan (Jika Ada)',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }
        SpreadsheetExporter::applyHeaderStyle($sheet, 'A4:K4', '0F766E');

        $receipts = SampleReceipt::with(['sample.testRequest.customer', 'sample.sampleType', 'receivedBy'])
            ->whereBetween('received_at', [$start, $end])
            ->latest('received_at')
            ->cursor();

        $row = 5;
        $no = 1;
        foreach ($receipts as $rcp) {
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

            SpreadsheetExporter::applyDataBorders($sheet, "A{$row}:K{$row}");
            $row++;
        }

        SpreadsheetExporter::autoFitColumns($sheet, 'A', 'K');
        $sheet->setAutoFilter('A4:K'.max($row - 1, 4));
        $sheet->freezePane('A5');
    }
}
