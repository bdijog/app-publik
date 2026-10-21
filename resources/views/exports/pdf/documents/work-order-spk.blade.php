@extends('exports.pdf.layouts.official-document')

@section('content')
    <div class="doc-title-box">
        <h1 class="doc-main-title">SURAT PERINTAH KERJA (SPK) PENGUJIAN LABORATORIUM</h1>
        <div style="font-size: 8.5pt; color: #475569; margin-top: 1px;">Lembar Penugasan Analis & Catatan Kerja Pengujian</div>
        <div class="doc-number">
            <strong>Nomor SPK:</strong> <span style="color: #047857; font-weight: bold;">{{ $job->job_number }}</span>
            &bull; <strong>Unit / Instalasi:</strong> {{ $unit->name ?? 'Laboratorium Pengujian' }}
        </div>
    </div>

    {{-- Detail Penugasan & Sampel --}}
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 8.5pt;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 8px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="3" style="font-weight: bold; background: #f1f5f9; padding: 4px 6px; border: 1px solid #cbd5e1; color: #0f172a;">
                            DATA PENUGASAN ANALIS
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 40%; padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Nama Analis</td>
                        <td style="width: 2%; padding: 3px 0; border: 1px solid #e2e8f0;">:</td>
                        <td style="width: 58%; padding: 3px 4px; border: 1px solid #e2e8f0; font-weight: bold; color: #047857;">
                            {{ $analyst->name ?? 'Belum Ditugaskan' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">NIP Analis</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0;">{{ $analyst->nip ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Pemberi Tugas</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0;">{{ $assignedBy->name ?? 'Kepala Instalasi' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Status Pekerjaan</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; font-weight: bold;">
                            {{ strtoupper($job->status?->value ?? (string) $job->status) }}
                        </td>
                    </tr>
                </table>
            </td>

            <td style="width: 50%; vertical-align: top; padding-left: 8px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="3" style="font-weight: bold; background: #f1f5f9; padding: 4px 6px; border: 1px solid #cbd5e1; color: #0f172a;">
                            IDENTITAS SAMPEL UJI
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 40%; padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Kode Sampel</td>
                        <td style="width: 2%; padding: 3px 0; border: 1px solid #e2e8f0;">:</td>
                        <td style="width: 58%; padding: 3px 4px; border: 1px solid #e2e8f0; font-weight: bold;">
                            {{ $sample->sample_code ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Nomor FPPS</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0;">{{ $testRequest->fpps_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Jenis Spesimen</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0;">{{ $sample->sampleType?->name ?? 'Spesimen Lab' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Volume / Jumlah</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0;">
                            {{ $sample->quantity ?? 1 }} {{ $sample->unit ?? 'Sampel' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Lembar Kerja Pengujian Lab --}}
    <div style="font-weight: bold; font-size: 9pt; color: #0f172a; margin-top: 10px; margin-bottom: 4px;">
        LEMBAR KERJA & INSTRUKSI PENGUJIAN
    </div>
    <table class="table-data" style="margin-top: 4px; margin-bottom: 15px;">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No.</th>
                <th style="width: 30%;">Instruksi / Tahap Kerja</th>
                <th style="width: 30%;">Metode Acuan Standar</th>
                <th style="width: 20%; text-align: center;">Target Selesai</th>
                <th style="width: 15%; text-align: center;">Paraf Analis</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td>Preparasi & Pengkondisian Sampel</td>
                <td>SNI / Standard Methods Labkes</td>
                <td style="text-align: center;">H + 1 Penerimaan</td>
                <td style="height: 32px;"></td>
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td>Pengujian / Analisis Kimia & Mikrobiologi</td>
                <td>Metode Uji Akreditasi KAN</td>
                <td style="text-align: center;">Sesuai SLA Layanan</td>
                <td style="height: 32px;"></td>
            </tr>
            <tr>
                <td style="text-align: center;">3</td>
                <td>Input Data & Perhitungan Hasil Uji</td>
                <td>Sistem Informasi Labkesmas</td>
                <td style="text-align: center;">Sebelum Batas SLA</td>
                <td style="height: 32px;"></td>
            </tr>
        </tbody>
    </table>

    {{-- Catatan Kerja Khusus --}}
    <div class="no-split" style="border: 1px solid #cbd5e1; background-color: #f8fafc; padding: 10px; margin-bottom: 20px; font-size: 8pt; color: #334155;">
        <strong>Catatan Khusus Instalasi Laboratorium:</strong><br>
        {{ $job->notes ?? 'Lakukan pengujian sesuai prosedur operasional standar (SOP) keselamatan kerja laboratorium (K3) dan pastikan kalibrasi alat uji telah terverifikasi.' }}
    </div>

    {{-- Tanda Tangan SPK --}}
    <table class="signature-table no-split">
        <tr>
            <td style="width: 50%; text-align: center; vertical-align: bottom;">
                <div style="font-size: 8.5pt; color: #475569;">Analis yang Ditugaskan,</div>
                <div style="height: 48px;"></div>
                <div style="font-weight: bold; font-size: 9pt; text-decoration: underline;">
                    {{ $analyst->name ?? '.........................................' }}
                </div>
                <div style="font-size: 8pt; color: #475569;">
                    NIP. {{ $analyst->nip ?? '.........................................' }}
                </div>
            </td>
            <td style="width: 50%; text-align: center; vertical-align: bottom;">
                <div style="font-size: 8.5pt; color: #475569;">
                    Ambon, {{ now()->translatedFormat('d F Y') }}
                </div>
                <div style="font-size: 8.5pt; font-weight: bold; color: #0f172a;">
                    Kepala Instalasi / Penanggung Jawab Teknis
                </div>
                <div style="height: 48px;"></div>
                <div style="font-weight: bold; font-size: 9pt; text-decoration: underline;">
                    {{ $assignedBy->name ?? '.........................................' }}
                </div>
                <div style="font-size: 8pt; color: #475569;">
                    NIP. {{ $assignedBy->nip ?? '.........................................' }}
                </div>
            </td>
        </tr>
    </table>
@endsection
