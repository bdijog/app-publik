<?php

namespace App\Services\Export\Pdf;

use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DompdfExporter
{
    /**
     * Render Blade view ke objek DomPDF dengan ukuran dan orientasi kertas yang ditentukan.
     *
     * @param  array<string, mixed>  $data
     */
    public static function renderView(
        string $view,
        array $data = [],
        string $paperSize = 'a4',
        string $orientation = 'portrait'
    ): DomPdfWrapper {
        return Pdf::loadView($view, $data)
            ->setPaper($paperSize, $orientation)
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'helvetica',
                'dpi' => 96,
                'chroot' => [base_path(), public_path(), storage_path()],
            ]);
    }

    /**
     * Mengambil logo resmi dalam format Base64 Data URI agar selalu berhasil dirender di PDF.
     */
    public static function getLogoDataUri(): string
    {
        $candidates = [
            public_path('images/logo-labkesmas-ambon.png'),
            base_path('public/images/logo-labkesmas-ambon.png'),
            base_path('template/logo-labkesmas-ambon.png'),
            public_path('images/logo-labkesmas-icon.png'),
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate) && is_readable($candidate)) {
                $content = file_get_contents($candidate);
                if ($content !== false && strlen($content) > 0) {
                    return 'data:image/png;base64,'.base64_encode($content);
                }
            }
        }

        return '';
    }

    /**
     * Buka PDF langsung di browser (Stream inline) dengan header anti-cache.
     */
    public static function streamPdf(DomPdfWrapper $pdf, string $filename): Response
    {
        if (! str_ends_with(strtolower($filename), '.pdf')) {
            $filename .= '.pdf';
        }

        return $pdf->stream($filename, ['Attachment' => 0])
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Unduh berkas PDF sebagai file attachment dengan header anti-cache.
     */
    public static function downloadPdf(DomPdfWrapper $pdf, string $filename): Response
    {
        if (! str_ends_with(strtolower($filename), '.pdf')) {
            $filename .= '.pdf';
        }

        return $pdf->download($filename)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Simpan berkas PDF ke disk storage lokal secara persisten.
     */
    public static function saveToDisk(DomPdfWrapper $pdf, string $relativePath, string $disk = 'local'): string
    {
        if (! str_ends_with(strtolower($relativePath), '.pdf')) {
            $relativePath .= '.pdf';
        }

        Storage::disk($disk)->put($relativePath, $pdf->output());

        return $relativePath;
    }

    /**
     * Menghasilkan QR Code dalam format Data URI (PNG base64) untuk disematkan pada PDF.
     */
    public static function generateQrCodeDataUri(string $content, int $pixelSize = 4): string
    {
        try {
            $options = new QROptions([
                'version' => QRCode::VERSION_AUTO,
                'scale' => $pixelSize,
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel' => QRCode::ECC_L,
                'imageBase64' => true,
            ]);

            return (new QRCode($options))->render($content);
        } catch (\Throwable) {
            // Fallback sederhana jika terjadi kendala pada QROptions
            return (new QRCode)->render($content);
        }
    }
}
