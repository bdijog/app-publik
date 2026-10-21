<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title ?? 'Laporan Rekapitulasi Operasional - Balai Labkesmas Ambon' }}</title>
    <style>
        @page {
            margin: 15mm 15mm 18mm 15mm;
            size: a4 landscape;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8.5pt;
            line-height: 1.3;
            color: #111827;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .inst-title-1 {
            font-size: 10pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }

        .inst-title-2 {
            font-size: 11pt;
            font-weight: bold;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .inst-meta {
            font-size: 8pt;
            color: #475569;
        }

        .kop-separator {
            width: 100%;
            border-top: 2px solid #047857;
            margin-top: 4px;
            margin-bottom: 12px;
        }

        .report-header-box {
            margin-bottom: 12px;
        }

        .report-title {
            font-size: 12pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            margin: 0;
        }

        .report-subtitle {
            font-size: 8.5pt;
            color: #475569;
            margin-top: 3px;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 12px;
        }

        .table-data th {
            background-color: #0f766e;
            color: #ffffff;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
            padding: 5px 6px;
            border: 1px solid #065f46;
            text-align: left;
        }

        .table-data td {
            font-size: 8pt;
            padding: 4px 6px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }

        .table-data tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .table-data tfoot td {
            background-color: #ecfdf5;
            font-weight: bold;
            color: #064e3b;
            border-top: 1.5px solid #0f766e;
            border-bottom: 2px solid #0f766e;
        }

        .signature-box {
            width: 100%;
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            font-size: 8.5pt;
            vertical-align: top;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .no-split { page-break-inside: avoid; }
    </style>
    @stack('styles')
</head>
<body>

    {{-- Kop Ringkas Landscape --}}
    <table class="header-table">
        <tr>
            <td style="width: 20%; text-align: left; vertical-align: middle;">
                <img src="{{ \App\Services\Export\Pdf\DompdfExporter::getLogoDataUri() ?: asset('images/logo-labkesmas-ambon.png') }}" height="48" style="height: 48px; width: auto; max-height: 48px; display: block;" alt="Logo Balai Labkesmas Ambon">
            </td>
            <td style="width: 52%; text-align: left; vertical-align: middle; padding-left: 10px;">
                <div class="inst-title-1">Kementerian Kesehatan RI &bull; Ditjen Kesehatan Masyarakat</div>
                <div class="inst-title-2">Balai Laboratorium Kesehatan Masyarakat Ambon</div>
                <div class="inst-meta">Jl. Dr. Kayadoe, Kudamati, Kec. Nusaniwe, Kota Ambon, Maluku 97116 | Telepon: (0911) 361234</div>
            </td>
            <td style="width: 28%; text-align: right; vertical-align: middle;">
                <div style="font-size: 8pt; color: #475569;">
                    <strong>Tanggal Cetak:</strong> {{ now()->translatedFormat('d F Y, H:i') }} WIT<br>
                    <strong>Operator:</strong> {{ auth('employee')->user()?->name ?? 'Sistem Balai' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="kop-separator"></div>

    <div class="content-body">
        @yield('content')
    </div>

    {{-- Script Penomoran Halaman Landscape --}}
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("Helvetica", "normal");
            $size = 7.5;
            
            $pageText = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $y = $pdf->get_height() - 24;
            $x = $pdf->get_width() - 110;
            $pdf->page_text($x, $y, $pageText, $font, $size, [0.35, 0.35, 0.35]);
            
            $footerLeft = "Balai Labkesmas Ambon | Rekapitulasi Data Operasional Resmi";
            $pdf->page_text(42, $y, $footerLeft, $font, $size, [0.45, 0.45, 0.45]);
        }
    </script>
</body>
</html>
