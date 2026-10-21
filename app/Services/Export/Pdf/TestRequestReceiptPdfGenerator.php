<?php

namespace App\Services\Export\Pdf;

use App\Models\TestRequest;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use Illuminate\Http\Response;

class TestRequestReceiptPdfGenerator
{
    /**
     * Mempersiapkan objek DomPDF untuk Lembar Bukti Registrasi / Tanda Terima FPPS.
     */
    public static function preparePdf(TestRequest $testRequest): DomPdfWrapper
    {
        $testRequest->loadMissing([
            'customer',
            'serviceGroup',
            'servicePackage',
            'samples.sampleType',
            'parameters.testParameter.serviceGroup',
            'payments',
        ]);

        $trackingUrl = route('lacak', ['fpps' => $testRequest->fpps_number]);
        $qrCodeDataUri = DompdfExporter::generateQrCodeDataUri($trackingUrl, 3);

        $data = [
            'testRequest' => $testRequest,
            'customer' => $testRequest->customer,
            'serviceGroup' => $testRequest->serviceGroup,
            'servicePackage' => $testRequest->servicePackage,
            'samples' => $testRequest->samples,
            'parameters' => $testRequest->parameters,
            'trackingUrl' => $trackingUrl,
            'qrCodeDataUri' => $qrCodeDataUri,
            'title' => "Bukti Registrasi FPPS - {$testRequest->fpps_number}",
        ];

        return DompdfExporter::renderView('exports.pdf.documents.fpps-receipt', $data, 'a4', 'portrait');
    }

    /**
     * Tampilkan PDF di tab browser.
     */
    public static function stream(TestRequest $testRequest): Response
    {
        $pdf = static::preparePdf($testRequest);
        $filename = 'FPPS_'.str_replace('/', '_', $testRequest->fpps_number).'.pdf';

        return DompdfExporter::streamPdf($pdf, $filename);
    }

    /**
     * Unduh PDF langsung ke perangkat pemohon / petugas.
     */
    public static function download(TestRequest $testRequest): mixed
    {
        $pdf = static::preparePdf($testRequest);
        $filename = 'FPPS_'.str_replace('/', '_', $testRequest->fpps_number).'.pdf';

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
}
