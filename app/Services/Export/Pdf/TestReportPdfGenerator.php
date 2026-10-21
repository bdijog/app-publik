<?php

namespace App\Services\Export\Pdf;

use App\Enums\DocumentAccessAction;
use App\Enums\ReportStatus;
use App\Models\Customer;
use App\Models\DocumentAccessLog;
use App\Models\Employee;
use App\Models\TestReport;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class TestReportPdfGenerator
{
    /**
     * Mempersiapkan instance DomPDF untuk Laporan Hasil Uji (LHU).
     */
    public static function preparePdf(TestReport $report): DomPdfWrapper
    {
        $report->loadMissing([
            'testRequest.customer',
            'testRequest.serviceGroup',
            'testRequest.servicePackage',
            'template',
            'verifier',
            'validator',
            'issuer',
            'items.testResult',
        ]);

        $isFinal = ($report->status === ReportStatus::Final || $report->status === 'final');
        $watermark = $isFinal ? null : 'DRAF - BELUM VALID';

        // URL publik untuk verifikasi keaslian dokumen sesuai route scan-lhu frontend
        $verificationUrl = route('scan-lhu', ['number' => $report->report_number]);
        $qrCodeDataUri = DompdfExporter::generateQrCodeDataUri($verificationUrl, 3);

        $data = [
            'report' => $report,
            'testRequest' => $report->testRequest,
            'customer' => $report->testRequest?->customer,
            'items' => $report->items,
            'isFinal' => $isFinal,
            'watermark' => $watermark,
            'verificationUrl' => $verificationUrl,
            'qrCodeDataUri' => $qrCodeDataUri,
            'title' => "LHU - {$report->report_number}",
        ];

        return DompdfExporter::renderView('exports.pdf.documents.lhu-report', $data, 'a4', 'portrait');
    }

    /**
     * Stream LHU di browser dan catat log akses dokumen.
     */
    public static function stream(
        TestReport $report,
        ?Employee $employee = null,
        ?Customer $customer = null
    ): Response {
        static::logAccess($report, DocumentAccessAction::View, $employee, $customer);

        $pdf = static::preparePdf($report);
        $filename = 'LHU_'.str_replace('/', '_', $report->report_number).'.pdf';

        return DompdfExporter::streamPdf($pdf, $filename);
    }

    /**
     * Unduh berkas PDF LHU dan catat log akses dokumen.
     */
    public static function download(
        TestReport $report,
        ?Employee $employee = null,
        ?Customer $customer = null
    ): mixed {
        static::logAccess($report, DocumentAccessAction::Download, $employee, $customer);

        $filename = 'LHU_'.str_replace('/', '_', $report->report_number).'.pdf';

        if ($report->file_path && Storage::disk('local')->exists($report->file_path)) {
            $fullPath = Storage::disk('local')->path($report->file_path);

            return response()->download(
                $fullPath,
                $filename,
                [
                    'Content-Type' => 'application/pdf',
                ]
            );
        }

        $pdf = static::preparePdf($report);

        return response()->streamDownload(
            function () use ($pdf): void {
                echo $pdf->output();
            },
            $filename,
            [
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]
        );
    }

    /**
     * Hasilkan PDF dan simpan secara fisik ke private storage disk saat LHU disahkan (Final).
     */
    public static function generateAndSave(TestReport $report): string
    {
        $pdf = static::preparePdf($report);
        $relativePath = 'reports/'.str_replace('/', '_', $report->report_number).'.pdf';

        // Simpan ke storage lokal
        Storage::disk('local')->put($relativePath, $pdf->output());

        $report->updateQuietly([
            'file_path' => $relativePath,
        ]);

        return $relativePath;
    }

    /**
     * Catat riwayat akses / unduhan ke tabel document_access_logs.
     */
    protected static function logAccess(
        TestReport $report,
        DocumentAccessAction $action,
        ?Employee $employee = null,
        ?Customer $customer = null
    ): void {
        $employeeId = $employee?->id ?? (auth('employee')->check() ? auth('employee')->id() : null);
        $customerId = $customer?->id ?? (auth('customer')->check() ? auth('customer')->id() : null);

        DocumentAccessLog::create([
            'test_report_id' => $report->id,
            'employee_id' => $employeeId,
            'customer_id' => $customerId,
            'action' => $action,
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'accessed_at' => now(),
        ]);
    }
}
