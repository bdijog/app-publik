<?php

namespace App\Services\Export\Pdf;

use App\Models\Payment;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use Illuminate\Http\Response;

class PaymentReceiptPdfGenerator
{
    /**
     * Mempersiapkan objek DomPDF untuk Lembar Kuitansi Pembayaran Retribusi.
     */
    public static function preparePdf(Payment $payment): DomPdfWrapper
    {
        $payment->loadMissing([
            'testRequest.customer',
            'testRequest.serviceGroup',
            'recordedBy',
        ]);

        $testRequest = $payment->testRequest;
        $customer = $testRequest?->customer;
        $terbilangText = static::terbilang((int) $payment->amount).' Rupiah';

        $data = [
            'payment' => $payment,
            'testRequest' => $testRequest,
            'customer' => $customer,
            'terbilang' => $terbilangText,
            'title' => "Kuitansi Pembayaran #{$payment->id} - {$testRequest?->fpps_number}",
        ];

        return DompdfExporter::renderView('exports.pdf.documents.payment-receipt', $data, 'a4', 'portrait');
    }

    public static function stream(Payment $payment): Response
    {
        $pdf = static::preparePdf($payment);
        $filename = 'Kuitansi_Pembayaran_'.($payment->testRequest?->fpps_number ?? $payment->id).'.pdf';

        return DompdfExporter::streamPdf($pdf, $filename);
    }

    public static function download(Payment $payment): mixed
    {
        $pdf = static::preparePdf($payment);
        $filename = 'Kuitansi_Pembayaran_'.($payment->testRequest?->fpps_number ?? $payment->id).'.pdf';

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
     * Mengubah angka nominal menjadi teks terbilang bahasa Indonesia.
     */
    public static function terbilang(int $nilai): string
    {
        $nilai = abs($nilai);
        $huruf = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
        $temp = '';

        if ($nilai < 12) {
            $temp = ' '.$huruf[$nilai];
        } elseif ($nilai < 20) {
            $temp = static::terbilang($nilai - 10).' Belas ';
        } elseif ($nilai < 100) {
            $temp = static::terbilang(intval($nilai / 10)).' Puluh '.static::terbilang($nilai % 10);
        } elseif ($nilai < 200) {
            $temp = ' Seratus '.static::terbilang($nilai - 100);
        } elseif ($nilai < 1000) {
            $temp = static::terbilang(intval($nilai / 100)).' Ratus '.static::terbilang($nilai % 100);
        } elseif ($nilai < 2000) {
            $temp = ' Seribu '.static::terbilang($nilai - 1000);
        } elseif ($nilai < 1000000) {
            $temp = static::terbilang(intval($nilai / 1000)).' Ribu '.static::terbilang($nilai % 1000);
        } elseif ($nilai < 1000000000) {
            $temp = static::terbilang(intval($nilai / 1000000)).' Juta '.static::terbilang($nilai % 1000000);
        } elseif ($nilai < 1000000000000) {
            $temp = static::terbilang(intval($nilai / 1000000000)).' Milyar '.static::terbilang(fmod($nilai, 1000000000));
        }

        return preg_replace('/\s+/', ' ', trim($temp));
    }
}
