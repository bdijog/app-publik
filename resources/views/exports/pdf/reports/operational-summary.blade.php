@extends('exports.pdf.layouts.landscape-report')

@section('content')
    <div class="report-header-box">
        <h1 class="report-title">LAPORAN REKAPITULASI EKSEKUTIF OPERASIONAL LABORATORIUM</h1>
        <div class="report-subtitle">
            <strong>Periode Pelaporan:</strong> {{ $start->translatedFormat('d F Y') }} s.d. {{ $end->translatedFormat('d F Y') }}
            &bull; <strong>Sistem Pelayanan:</strong> Balai Laboratorium Kesehatan Masyarakat Ambon
        </div>
    </div>

    {{-- Kartu Metrik Utama --}}
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 14px;">
        <tr>
            <td style="width: 20%; padding: 6px; background-color: #f1f5f9; border: 1px solid #cbd5e1; text-align: center;">
                <div style="font-size: 7pt; color: #64748b; font-weight: bold; text-transform: uppercase;">Total Permohonan</div>
                <div style="font-size: 13pt; font-weight: bold; color: #0f172a; margin-top: 2px;">
                    {{ number_format($totalRequests, 0, ',', '.') }}
                </div>
                <div style="font-size: 6.5pt; color: #475569;">Berkas FPPS</div>
            </td>
            <td style="width: 20%; padding: 6px; background-color: #f1f5f9; border: 1px solid #cbd5e1; text-align: center;">
                <div style="font-size: 7pt; color: #64748b; font-weight: bold; text-transform: uppercase;">Sampel Masuk</div>
                <div style="font-size: 13pt; font-weight: bold; color: #0284c7; margin-top: 2px;">
                    {{ number_format($totalSamples, 0, ',', '.') }}
                </div>
                <div style="font-size: 6.5pt; color: #475569;">Spesimen Diuji</div>
            </td>
            <td style="width: 20%; padding: 6px; background-color: #ecfdf5; border: 1.5px solid #047857; text-align: center;">
                <div style="font-size: 7pt; color: #065f46; font-weight: bold; text-transform: uppercase;">Realisasi Retribusi</div>
                <div style="font-size: 12pt; font-weight: bold; color: #047857; margin-top: 2px;">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <div style="font-size: 6.5pt; color: #065f46;">Lunas: {{ $paidRate }}%</div>
            </td>
            <td style="width: 20%; padding: 6px; background-color: #f1f5f9; border: 1px solid #cbd5e1; text-align: center;">
                <div style="font-size: 7pt; color: #64748b; font-weight: bold; text-transform: uppercase;">LHU Resmi Terbit</div>
                <div style="font-size: 13pt; font-weight: bold; color: #4f46e5; margin-top: 2px;">
                    {{ number_format($totalReportsIssued, 0, ',', '.') }}
                </div>
                <div style="font-size: 6.5pt; color: #475569;">Status Final Sah</div>
            </td>
            <td style="width: 20%; padding: 6px; background-color: #f1f5f9; border: 1px solid #cbd5e1; text-align: center;">
                <div style="font-size: 7pt; color: #64748b; font-weight: bold; text-transform: uppercase;">Kepatuhan SLA (TAT)</div>
                <div style="font-size: 13pt; font-weight: bold; color: {{ $complianceRate >= 90 ? '#047857' : '#d97706' }}; margin-top: 2px;">
                    {{ $complianceRate }}%
                </div>
                <div style="font-size: 6.5pt; color: #475569;">Tepat Waktu: {{ $completedOnTime }} | Overdue: {{ $overdue }}</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Distribusi 17 Layanan Brosur & Pendapatan --}}
    <table class="table-data" style="margin-bottom: 12px;">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No.</th>
                <th style="width: 40%;">Kelompok Layanan Pemeriksaan (Sesuai Brosur Resmi)</th>
                <th style="width: 15%; text-align: center;">Jumlah Permohonan</th>
                <th style="width: 15%; text-align: center;">Beban Layanan (%)</th>
                <th style="width: 25%; text-align: right;">Estimasi Retribusi (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalSrvCount = 0; $totalSrvRev = 0; @endphp
            @foreach ($services as $sidx => $srv)
                @php 
                    $totalSrvCount += $srv->test_requests_count; 
                    $totalSrvRev += $srv->revenue; 
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $sidx + 1 }}</td>
                    <td style="font-weight: 500;">{{ $srv->name }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ number_format($srv->test_requests_count, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $srv->percent }}%</td>
                    <td style="text-align: right; font-weight: bold;">
                        Rp {{ number_format($srv->revenue, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold;">TOTAL KESELURUHAN</td>
                <td style="text-align: center; font-weight: bold;">{{ number_format($totalSrvCount, 0, ',', '.') }}</td>
                <td style="text-align: center; font-weight: bold;">100.0%</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($totalSrvRev, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Ringkasan Mutu Penerimaan Sampel Fisik --}}
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 8pt;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                <div style="font-weight: bold; color: #0f172a; margin-bottom: 4px;">PENGENDALIAN MUTU SAMPEL FISIK</div>
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #cbd5e1;">
                    <tr style="background: #f8fafc;">
                        <td style="padding: 4px 6px; border: 1px solid #cbd5e1;">Total Sampel Diverifikasi</td>
                        <td style="padding: 4px 6px; border: 1px solid #cbd5e1; font-weight: bold; text-align: right;">{{ $totalReceipts }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 6px; border: 1px solid #cbd5e1; color: #047857;">Sampel Layak Uji (Diterima)</td>
                        <td style="padding: 4px 6px; border: 1px solid #cbd5e1; font-weight: bold; text-align: right; color: #047857;">{{ $acceptedCount }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 6px; border: 1px solid #cbd5e1; color: #b91c1c;">Sampel Tidak Layak (Ditolak)</td>
                        <td style="padding: 4px 6px; border: 1px solid #cbd5e1; font-weight: bold; text-align: right; color: #b91c1c;">{{ $rejectedCount }}</td>
                    </tr>
                    <tr style="background: #fef2f2;">
                        <td style="padding: 4px 6px; border: 1px solid #cbd5e1; color: #991b1b; font-weight: bold;">Tingkat Penolakan Sampel (Rejection Rate)</td>
                        <td style="padding: 4px 6px; border: 1px solid #cbd5e1; font-weight: bold; text-align: right; color: #991b1b;">{{ $rejectionRate }}%</td>
                    </tr>
                </table>
            </td>

            <td style="width: 50%; vertical-align: top; padding-left: 10px;">
                <div style="font-weight: bold; color: #0f172a; margin-bottom: 4px;">CATATAN EVALUASI OPERASIONAL</div>
                <div style="border: 1px solid #cbd5e1; background: #f8fafc; padding: 6px 8px; font-size: 7.5pt; color: #334155; line-height: 1.4;">
                    1. Rerata penyelesaian pengujian mematuhi standar estimasi waktu (SLA 10 hari kalender) sebesar {{ $complianceRate }}%.<br>
                    2. Seluruh penerimaan retribusi telah diverifikasi dan disinkronkan dengan laporan buku kas loket kasir.<br>
                    3. Pengawasan integritas dokumen dijamin melalui log akses digital berstandar keamanan ISO 17025.
                </div>
            </td>
        </tr>
    </table>

    {{-- Blok Pengesahan Pimpinan Landscape --}}
    <div class="signature-box no-split">
        <table class="signature-table">
            <tr>
                <td style="width: 65%; vertical-align: bottom;">
                    <div style="font-size: 7.5pt; color: #64748b;">
                        Dokumen dicetak secara otomatis melalui Sistem Informasi Laboratorium Balai Labkesmas Ambon.<br>
                        Keabsahan data terikat pada basis data terverifikasi per tanggal {{ now()->translatedFormat('d F Y') }}.
                    </div>
                </td>
                <td style="width: 35%; text-align: center; vertical-align: bottom;">
                    <div style="font-size: 8.5pt; color: #475569;">
                        Ambon, {{ now()->translatedFormat('d F Y') }}
                    </div>
                    <div style="font-size: 8.5pt; font-weight: bold; color: #0f172a;">
                        Kepala Balai Labkesmas Ambon
                    </div>
                    <div style="height: 48px;"></div>
                    <div style="font-weight: bold; font-size: 9pt; text-decoration: underline;">
                        dr. Hendrikus Patty, M.Kes.
                    </div>
                    <div style="font-size: 8pt; color: #475569;">
                        NIP. 19750821 200212 1 002
                    </div>
                </td>
            </tr>
        </table>
    </div>
@endsection
