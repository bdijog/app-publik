<?php

namespace App\Services\Export\Excel;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SpreadsheetExporter
{
    /**
     * Menyematkan logo resmi Balai Labkesmas Ambon pada worksheet Excel.
     */
    public static function addHeaderLogo(
        Worksheet $sheet,
        string $coordinate = 'A1',
        int $height = 36,
        int $offsetX = 4,
        int $offsetY = 4
    ): ?Drawing {
        $logoPath = public_path('images/logo-labkesmas-ambon.png');

        if (! file_exists($logoPath)) {
            return null;
        }

        $drawing = new Drawing;
        $drawing->setName('Logo Balai Labkesmas Ambon');
        $drawing->setDescription('Logo Balai Laboratorium Kesehatan Masyarakat Ambon');
        $drawing->setPath($logoPath);
        $drawing->setHeight($height);
        $drawing->setCoordinates($coordinate);
        $drawing->setOffsetX($offsetX);
        $drawing->setOffsetY($offsetY);
        $drawing->setWorksheet($sheet);

        // Berikan ruang tinggi baris yang cukup pada baris koordinat
        preg_match('/^([A-Z]+)(\d+)$/', $coordinate, $matches);
        if (! empty($matches[2])) {
            $rowNum = (int) $matches[2];
            $sheet->getRowDimension($rowNum)->setRowHeight(max((int) ($height * 0.85), 32));
        }

        return $drawing;
    }

    /**
     * Terapkan gaya header utama (Deep Emerald / Teal).
     */
    public static function applyHeaderStyle(
        Worksheet $sheet,
        string $cellRange,
        string $bgHex = '0F766E',
        string $textHex = 'FFFFFF',
        int $fontSize = 11
    ): void {
        $sheet->getStyle($cellRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => $textHex],
                'size' => $fontSize,
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $bgHex],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '064E3B'],
                ],
            ],
        ]);
    }

    /**
     * Terapkan gaya sub-header atau grup kolom.
     */
    public static function applySubHeaderStyle(Worksheet $sheet, string $cellRange, string $bgHex = 'F1F5F9'): void
    {
        $sheet->getStyle($cellRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '1E293B'],
                'size' => 10,
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $bgHex],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'],
                ],
            ],
        ]);
    }

    /**
     * Terapkan garis pembatas tipis pada sel data.
     */
    public static function applyDataBorders(Worksheet $sheet, string $cellRange): void
    {
        $sheet->getStyle($cellRange)->applyFromArray([
            'font' => [
                'size' => 10,
                'name' => 'Calibri',
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);
    }

    /**
     * Terapkan gaya baris total/kesimpulan dengan double bottom border.
     */
    public static function applyTotalRowStyle(Worksheet $sheet, string $cellRange, string $bgHex = 'ECFDF5'): void
    {
        $sheet->getStyle($cellRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '064E3B'],
                'size' => 10,
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $bgHex],
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '0F766E'],
                ],
                'bottom' => [
                    'borderStyle' => Border::BORDER_DOUBLE,
                    'color' => ['rgb' => '0F766E'],
                ],
            ],
        ]);
    }

    /**
     * Otomatiskan lebar kolom berdasarkan konten.
     */
    public static function autoFitColumns(Worksheet $sheet, string $startCol = 'A', ?string $endCol = null): void
    {
        $end = $endCol ?? $sheet->getHighestColumn();
        $startIdx = Coordinate::columnIndexFromString($startCol);
        $endIdx = Coordinate::columnIndexFromString($end);

        for ($col = $startIdx; $col <= $endIdx; $col++) {
            $colString = Coordinate::stringFromColumnIndex($col);
            $sheet->getColumnDimension($colString)->setAutoSize(true);
        }
    }

    /**
     * Format sel sebagai Mata Uang Rupiah (IDR).
     */
    public static function formatCurrency(Worksheet $sheet, string $cellRange): void
    {
        $sheet->getStyle($cellRange)->getNumberFormat()->setFormatCode(
            '_("Rp "* #,##0_);_("Rp "* \(#,##0\);_("Rp "* "-"_);_(@_)'
        );
    }

    /**
     * Format sel sebagai Tanggal / Waktu.
     */
    public static function formatDate(Worksheet $sheet, string $cellRange, string $format = 'dd/mm/yyyy hh:mm'): void
    {
        $sheet->getStyle($cellRange)->getNumberFormat()->setFormatCode($format);
    }

    /**
     * Format sel sebagai Persentase.
     */
    public static function formatPercentage(Worksheet $sheet, string $cellRange): void
    {
        $sheet->getStyle($cellRange)->getNumberFormat()->setFormatCode('0.0%');
    }

    /**
     * Mengalirkan (stream) spreadsheet langsung ke output browser tanpa membuat file sampah di disk.
     */
    public static function streamSpreadsheet(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        if (! str_ends_with(strtolower($filename), '.xlsx')) {
            $filename .= '.xlsx';
        }

        return response()->streamDownload(
            function () use ($spreadsheet): void {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            },
            $filename,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' => 'max-age=0, must-revalidate',
                'Pragma' => 'public',
            ]
        );
    }
}
