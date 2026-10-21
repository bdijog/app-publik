<?php

namespace App\Services\Export\Contracts;

use Illuminate\Http\Response;

interface PdfExporterInterface
{
    /**
     * Stream the generated PDF directly in the browser.
     */
    public function stream(mixed $data = null): Response;

    /**
     * Download the generated PDF file.
     */
    public function download(mixed $data = null, ?string $filename = null): Response;
}
