<?php

namespace App\Services\Export\Contracts;

use Symfony\Component\HttpFoundation\StreamedResponse;

interface ExcelExporterInterface
{
    /**
     * Generate and stream the Excel spreadsheet download directly to output.
     *
     * @param  array<string, mixed>  $filters
     */
    public function export(array $filters = []): StreamedResponse;
}
