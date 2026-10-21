<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title ?? 'Dokumen Resmi - Balai Labkesmas Ambon' }}</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.35;
            color: #111827;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* Kop Surat Instansi */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }

        .header-table td {
            vertical-align: middle;
            text-align: center;
        }

        .inst-title-1 {
            font-size: 11pt;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .inst-title-2 {
            font-size: 10pt;
            font-weight: bold;
            color: #1e293b;
            margin: 1px 0;
            text-transform: uppercase;
        }

        .inst-title-3 {
            font-size: 12pt;
            font-weight: bold;
            color: #047857;
            margin: 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .inst-address {
            font-size: 8pt;
            color: #475569;
            margin: 2px 0 0 0;
            line-height: 1.25;
        }

        /* Garis Pembatas Kop Ganda */
        .kop-separator {
            width: 100%;
            border-top: 2.5px solid #0f172a;
            border-bottom: 0.75px solid #0f172a;
            height: 3px;
            margin-top: 6px;
            margin-bottom: 15px;
        }

        /* Judul Dokumen */
        .doc-title-box {
            text-align: center;
            margin-bottom: 16px;
        }

        .doc-main-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            text-decoration: underline;
            margin: 0;
        }

        .doc-number {
            font-size: 9.5pt;
            color: #334155;
            margin-top: 3px;
            font-weight: 500;
        }

        /* Gaya Tabel Umum */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 12px;
        }

        .table-data th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }

        .table-data td {
            font-size: 8.5pt;
            padding: 5px 8px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }

        .table-data tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 35%;
            left: 5%;
            width: 90%;
            text-align: center;
            opacity: 0.10;
            transform: rotate(-35deg);
            font-size: 42pt;
            font-weight: bold;
            color: #dc2626;
            z-index: -1000;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        /* Tanda Tangan & Footer */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .signature-table td {
            vertical-align: top;
            font-size: 9pt;
        }

        .page-break {
            page-break-after: always;
        }

        .no-split {
            page-break-inside: avoid;
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- Watermark Status --}}
    @if (!empty($watermark))
        <div class="watermark">{{ $watermark }}</div>
    @endif

    {{-- Kop Surat Instansi Resmi --}}
    <table class="header-table">
        <tr>
            <td style="width: 24%; text-align: left; vertical-align: middle;">
                <img src="{{ \App\Services\Export\Pdf\DompdfExporter::getLogoDataUri() ?: asset('images/logo-labkesmas-ambon.png') }}" height="52" style="height: 52px; width: auto; max-height: 52px; display: block;" alt="Logo Balai Labkesmas Ambon">
            </td>
            <td style="width: 61%; text-align: center; vertical-align: middle;">
                <div class="inst-title-1">Kementerian Kesehatan Republik Indonesia</div>
                <div class="inst-title-2">Direktorat Jenderal Kesehatan Masyarakat</div>
                <div class="inst-title-3">Balai Laboratorium Kesehatan Masyarakat Ambon</div>
                <div class="inst-address">
                    Jl. Dr. Kayadoe, Kudamati, Kec. Nusaniwe, Kota Ambon, Maluku 97116<br>
                    Telepon: (0911) 361234 | Email: labkesmas.ambon@kemkes.go.id | Web: labkesmasambon.kemkes.go.id
                </div>
            </td>
            <td style="width: 15%; text-align: right; vertical-align: middle;">
                {{-- Akreditasi KAN LP Badge --}}
                <div style="border: 1.5px solid #047857; padding: 4px 6px; display: inline-block; text-align: center; border-radius: 3px;">
                    <span style="font-size: 7.5pt; font-weight: bold; color: #047857; display: block;">KAN</span>
                    <span style="font-size: 6.5pt; color: #334155;">LP-892-IDN</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="kop-separator"></div>

    {{-- Konten Dokumen Utama --}}
    <div class="content-body">
        @yield('content')
    </div>

    {{-- Script Penomoran Halaman DomPDF --}}
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("Helvetica", "normal");
            $size = 8;
            
            // Halaman X dari Y di pojok kanan bawah
            $pageText = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $y = $pdf->get_height() - 28;
            $x = $pdf->get_width() - 110;
            $pdf->page_text($x, $y, $pageText, $font, $size, [0.35, 0.35, 0.35]);
            
            // Footer Integritas Dokumen di kiri bawah
            $footerLeft = "Balai Labkesmas Ambon | Sistem Informasi Laboratorium (Dokumen Resmi)";
            $pdf->page_text(42, $y, $footerLeft, $font, $size, [0.45, 0.45, 0.45]);
        }
    </script>
</body>
</html>
