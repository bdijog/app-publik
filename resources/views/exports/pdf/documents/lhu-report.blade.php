@extends('exports.pdf.layouts.official-document')

@section('content')
    {{-- Kotak Judul Dokumen --}}
    <div class="doc-title-box">
        <h1 class="doc-main-title">LAPORAN HASIL PENGUJIAN LABORATORIUM</h1>
        <div style="font-size: 8.5pt; font-style: italic; color: #475569; margin-top: 1px;">(Certificate of Laboratory Analysis)</div>
        <div class="doc-number">
            <strong>Nomor LHU:</strong> {{ $report->report_number }} 
            &bull; <strong>Edisi/Revisi:</strong> {{ $report->version ?? 1 }}
        </div>
    </div>

    {{-- Bagian I & II: Identitas Pemohon & Informasi Sampel --}}
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 8.5pt;">
        <tr>
            {{-- Kolom Kiri: Pemohon --}}
            <td style="width: 50%; vertical-align: top; padding-right: 8px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="3" style="font-weight: bold; background: #f1f5f9; padding: 3px 6px; border: 1px solid #cbd5e1; color: #0f172a;">
                            I. IDENTITAS PEMOHON / PELANGGAN
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 38%; padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Nama Pemohon</td>
                        <td style="width: 2%; padding: 3px 0; border: 1px solid #e2e8f0;">:</td>
                        <td style="width: 60%; padding: 3px 4px; border: 1px solid #e2e8f0; font-weight: bold;">
                            {{ $customer->name ?? '-' }}
                        </td>
                    </tr>
                    @if (!empty($customer?->organization_name))
                    <tr>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Instansi / Perusahaan</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0;">{{ $customer->organization_name }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Alamat</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0;">{{ $customer->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Nomor Registrasi FPPS</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; font-weight: bold; color: #047857;">
                            {{ $testRequest->fpps_number ?? '-' }}
                        </td>
                    </tr>
                </table>
            </td>

            {{-- Kolom Kanan: Sampel --}}
            <td style="width: 50%; vertical-align: top; padding-left: 8px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="3" style="font-weight: bold; background: #f1f5f9; padding: 3px 6px; border: 1px solid #cbd5e1; color: #0f172a;">
                            II. KETERANGAN SAMPEL UJI
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 38%; padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Kelompok Layanan</td>
                        <td style="width: 2%; padding: 3px 0; border: 1px solid #e2e8f0;">:</td>
                        <td style="width: 60%; padding: 3px 4px; border: 1px solid #e2e8f0; font-weight: bold;">
                            {{ $testRequest->serviceGroup->name ?? 'Pengujian Laboratorium' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Lokasi Pengambilan</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0;">{{ $testRequest->sampling_location ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Tgl. Pengambilan Sampel</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0;">
                            {{ $testRequest->sampling_date ? \Illuminate\Support\Carbon::parse($testRequest->sampling_date)->translatedFormat('d F Y') : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Tgl. Pengujian Selesai</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0;">
                            {{ $report->validated_on ? $report->validated_on->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Bagian III: Tabel Hasil Uji --}}
    <div style="font-weight: bold; font-size: 9pt; color: #0f172a; margin-top: 6px; margin-bottom: 4px;">
        III. HASIL PENGUJIAN PARAMETER FISIK, KIMIA, & MIKROBIOLOGI
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No.</th>
                <th style="width: 25%;">Parameter Uji</th>
                <th style="width: 10%; text-align: center;">Satuan</th>
                <th style="width: 15%; text-align: center;">Baku Mutu *</th>
                <th style="width: 15%; text-align: center;">Hasil Uji</th>
                <th style="width: 30%;">Metode Analisis</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $idx => $item)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="font-weight: 500;">{{ $item->parameter }}</td>
                    <td style="text-align: center;">{{ $item->unit ?? '-' }}</td>
                    <td style="text-align: center; color: #475569;">{{ $item->threshold ?? '-' }}</td>
                    <td style="text-align: center; font-weight: bold; color: #0f172a;">
                        {{ $item->result ?? '-' }}
                    </td>
                    <td style="font-size: 7.5pt; color: #334155;">{{ $item->method ?? 'Standard Methods / SNI' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #64748b; padding: 12px;">
                        Data rincian hasil pengujian belum diinput atau sedang dalam proses.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="font-size: 7.5pt; color: #64748b; margin-top: -6px; margin-bottom: 10px;">
        * Nilai Ambang Batas / Baku Mutu mengacu pada standar regulasi Menteri Kesehatan RI yang berlaku untuk jenis spesimen tersebut.
    </div>

    {{-- Bagian IV: Kesimpulan & Pertimbangan Teknis --}}
    <div class="no-split" style="border: 1px solid #cbd5e1; background-color: #f8fafc; padding: 8px 10px; margin-bottom: 12px;">
        <div style="font-weight: bold; font-size: 8.5pt; color: #0f172a; margin-bottom: 3px;">
            IV. KESIMPULAN / INTERPRETASI HASIL PENGUJIAN:
        </div>
        <div style="font-size: 8.5pt; color: #1e293b; line-height: 1.4;">
            {{ $report->considerations ?? 'Berdasarkan parameter pengujian yang dilakukan, seluruh nilai parameter berada dalam rentang ambang batas yang dipersyaratkan oleh standar baku mutu.' }}
        </div>
    </div>

    {{-- Catatan Hukum ISO 17025 --}}
    <div style="font-size: 7pt; color: #64748b; line-height: 1.3; margin-bottom: 15px; border-top: 1px dashed #cbd5e1; padding-top: 5px;">
        <strong>Catatan Laboratorium:</strong>
        1. Laporan Pengujian ini hanya berlaku untuk sampel yang diterima dan diuji di laboratorium Balai Labkesmas Ambon.
        2. Pengambilan sampel di luar kewenangan laboratorium menjadi tanggung jawab pemohon sepenuhnya.
        3. Laporan Hasil Uji (LHU) ini tidak boleh digandakan atau dikutip sebagian tanpa persetujuan tertulis dari Kepala Balai Labkesmas Ambon.
    </div>

    {{-- Bagian V: Pengesahan & Tanda Tangan --}}
    <table class="signature-table no-split">
        <tr>
            {{-- Kolom QR Code Verifikasi --}}
            <td style="width: 25%; text-align: center; vertical-align: bottom;">
                @if (!empty($qrCodeDataUri))
                    <img src="{{ $qrCodeDataUri }}" alt="QR Verifikasi" style="width: 75px; height: 75px; border: 1px solid #e2e8f0; padding: 2px;" />
                    <div style="font-size: 6.5pt; color: #475569; margin-top: 3px;">
                        Pindai untuk verifikasi<br>keaslian LHU secara online
                    </div>
                    @if(!empty($verificationUrl))
                        <div style="font-size: 5.5pt; color: #0284c7; margin-top: 2px; word-break: break-all; font-family: monospace;">
                            {{ $verificationUrl }}
                        </div>
                    @endif
                @endif
            </td>

            {{-- Kolom Validator Teknis --}}
            <td style="width: 35%; text-align: center; vertical-align: bottom;">
                <div style="font-size: 8.5pt; color: #475569;">Diperiksa oleh:</div>
                <div style="font-size: 8.5pt; font-weight: bold; color: #0f172a;">Validator Teknis / Manager Lab</div>
                <div style="height: 48px;"></div>
                <div style="font-weight: bold; font-size: 9pt; text-decoration: underline;">
                    {{ $report->validator?->name ?? 'A. Rahman, S.Si., M.Sc.' }}
                </div>
                <div style="font-size: 8pt; color: #475569;">
                    NIP. {{ $report->validator?->nip ?? '19840512 201001 1 008' }}
                </div>
            </td>

            {{-- Kolom Pengesahan Pimpinan --}}
            <td style="width: 40%; text-align: center; vertical-align: bottom;">
                <div style="font-size: 8.5pt; color: #475569;">
                    Ditetapkan di Ambon, {{ $report->issued_at ? $report->issued_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
                </div>
                <div style="font-size: 8.5pt; font-weight: bold; color: #0f172a;">
                    Kepala Balai Labkesmas Ambon
                </div>
                <div style="height: 48px;">
                    {{-- Stempel digital bila status final --}}
                    @if ($isFinal)
                        <div style="font-size: 7.5pt; color: #047857; font-weight: bold; border: 1.5px solid #047857; display: inline-block; padding: 3px 8px; border-radius: 4px; transform: rotate(-5deg); margin-top: 10px;">
                            TERTANDATANGANI SECARA ELEKTRONIK
                        </div>
                    @endif
                </div>
                <div style="font-weight: bold; font-size: 9pt; text-decoration: underline;">
                    {{ $report->issuer?->name ?? 'dr. Hendrikus Patty, M.Kes.' }}
                </div>
                <div style="font-size: 8pt; color: #475569;">
                    NIP. {{ $report->issuer?->nip ?? '19750821 200212 1 002' }}
                </div>
            </td>
        </tr>
    </table>
@endsection
