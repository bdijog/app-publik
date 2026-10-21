<?php

namespace App\Services\Export\Pdf;

use App\Models\SampleReceipt;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use Illuminate\Http\Response;

class SampleRejectionPdfGenerator
{
    /**
     * Mempersiapkan objek DomPDF untuk Berita Acara Penolakan Sampel Fisik.
     */
    public static function preparePdf(SampleReceipt $receipt): DomPdfWrapper
    {
        $receipt->loadMissing([
            'sample.testRequest.customer',
            'sample.testRequest.serviceGroup',
            'sample.sampleType',
            'receivedBy',
        ]);

        $data = [
            'receipt' => $receipt,
            'sample' => $receipt->sample,
            'testRequest' => $receipt->sample?->testRequest,
            'customer' => $receipt->sample?->testRequest?->customer,
            'receivedBy' => $receipt->receivedBy,
            'title' => "BAP Penolakan Sampel - {$receipt->sample?->sample_code}",
        ];

        return DompdfExporter::renderView('exports.pdf.documents.sample-rejection', $data, 'a4', 'portrait');
    }

    public static function stream(SampleReceipt $receipt): Response
    {
        $pdf = static::preparePdf($receipt);
        $filename = 'BAP_Tolak_Sampel_'.($receipt->sample?->sample_code ?? $receipt->id).'.pdf';

        return DompdfExporter::streamPdf($pdf, $filename);
    }

    public static function download(SampleReceipt $receipt): mixed
    {
        $pdf = static::preparePdf($receipt);
        $filename = 'BAP_Tolak_Sampel_'.($receipt->sample?->sample_code ?? $receipt->id).'.pdf';

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
