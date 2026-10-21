<?php

namespace App\Services\Export\Pdf;

use App\Models\TestJob;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use Illuminate\Http\Response;

class WorkOrderPdfGenerator
{
    /**
     * Mempersiapkan objek DomPDF untuk Surat Perintah Kerja (SPK) Analis Laboratorium.
     */
    public static function preparePdf(TestJob $testJob): DomPdfWrapper
    {
        $testJob->loadMissing([
            'sample.testRequest.customer',
            'sample.testRequest.serviceGroup',
            'sample.sampleType',
            'assignedTo',
            'laboratoryUnit',
        ]);

        $data = [
            'job' => $testJob,
            'sample' => $testJob->sample,
            'testRequest' => $testJob->sample?->testRequest,
            'analyst' => $testJob->assignedTo,
            'assignedBy' => null,
            'unit' => $testJob->laboratoryUnit,
            'title' => "SPK Analis - {$testJob->job_number}",
        ];

        return DompdfExporter::renderView('exports.pdf.documents.work-order-spk', $data, 'a4', 'portrait');
    }

    public static function stream(TestJob $testJob): Response
    {
        $pdf = static::preparePdf($testJob);
        $filename = 'SPK_'.str_replace('/', '_', $testJob->job_number).'.pdf';

        return DompdfExporter::streamPdf($pdf, $filename);
    }

    public static function download(TestJob $testJob): Response
    {
        $pdf = static::preparePdf($testJob);
        $filename = 'SPK_'.str_replace('/', '_', $testJob->job_number).'.pdf';

        return DompdfExporter::downloadPdf($pdf, $filename);
    }
}
