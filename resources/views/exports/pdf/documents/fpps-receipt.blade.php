@extends('exports.pdf.layouts.official-document')

@section('content')
    <div class="doc-title-box">
        <h1 class="doc-main-title">FORMULIR PERMINTAAN PEMERIKSAAN SAMPEL (FPPS)</h1>
        <div style="font-size: 8.5pt; color: #475569; margin-top: 1px;">Lembar Tanda Terima Registrasi Pelayanan Pengujian</div>
        <div class="doc-number">
            <strong>Nomor FPPS:</strong> <span style="color: #047857; font-size: 11pt; font-weight: bold;">{{ $testRequest->fpps_number }}</span>
            &bull; <strong>Nomor Registrasi:</strong> {{ $testRequest->request_number }}
        </div>
    </div>

    {{-- Detail Data Pemohon & Layanan --}}
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 8.5pt;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 8px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="3" style="font-weight: bold; background: #f1f5f9; padding: 4px 6px; border: 1px solid #cbd5e1; color: #0f172a;">
                            DATA PEMOHON
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 38%; padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Nama Pemohon</td>
                        <td style="width: 2%; padding: 3px 0; border: 1px solid #e2e8f0;">:</td>
                        <td style="width: 60%; padding: 3px 4px; border: 1px solid #e2e8f0; font-weight: bold;">{{ $customer->name ?? '-' }}</td>
                    </tr>
                    @if(!empty($customer?->organization_name))
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
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Kanal Pendaftaran</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; text-transform: capitalize;">
                            {{ $testRequest->channel?->value ?? (string) $testRequest->channel }}
                        </td>
                    </tr>
                </table>
            </td>

            <td style="width: 50%; vertical-align: top; padding-left: 8px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="3" style="font-weight: bold; background: #f1f5f9; padding: 4px 6px; border: 1px solid #cbd5e1; color: #0f172a;">
                            INFORMASI TRANSAKSI & SLA
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 42%; padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Kelompok Layanan</td>
                        <td style="width: 2%; padding: 3px 0; border: 1px solid #e2e8f0;">:</td>
                        <td style="width: 56%; padding: 3px 4px; border: 1px solid #e2e8f0; font-weight: bold;">
                            {{ $serviceGroup->name ?? 'Pelayanan Laboratorium' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Waktu Registrasi</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0;">
                            {{ $testRequest->registered_at ? \Illuminate\Support\Carbon::parse($testRequest->registered_at)->translatedFormat('d F Y, H:i') : now()->translatedFormat('d F Y') }} WIT
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Target Selesai (SLA)</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; font-weight: bold; color: #047857;">
                            {{ $testRequest->estimated_completion_date ? \Illuminate\Support\Carbon::parse($testRequest->estimated_completion_date)->translatedFormat('d F Y') : '10 Hari Kalender' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; color: #475569;">Total Biaya Retribusi</td>
                        <td style="border: 1px solid #e2e8f0;">:</td>
                        <td style="padding: 3px 4px; border: 1px solid #e2e8f0; font-weight: bold; color: #0f172a;">
                            Rp {{ number_format((float) $testRequest->total_amount, 0, ',', '.') }}
                            ({{ $testRequest->payment_status?->value === 'paid' ? 'LUNAS' : 'BELUM BAYAR' }})
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Daftar Sampel Fisik --}}
    <div style="font-weight: bold; font-size: 9pt; color: #0f172a; margin-top: 8px; margin-bottom: 4px;">
        DAFTAR SPESIMEN / SAMPEL YANG DISERAHKAN
    </div>
    <table class="table-data" style="margin-top: 4px; margin-bottom: 10px;">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No.</th>
                <th style="width: 25%;">Kode Sampel</th>
                <th style="width: 35%;">Nama / Deskripsi Sampel</th>
                <th style="width: 20%;">Jenis Spesimen</th>
                <th style="width: 15%; text-align: center;">Jumlah / Volume</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($samples as $idx => $sample)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="font-weight: bold; color: #047857;">{{ $sample->sample_code }}</td>
                    <td>{{ $sample->sample_name }}</td>
                    <td>{{ $sample->sampleType?->name ?? 'Spesimen Lab' }}</td>
                    <td style="text-align: center;">{{ $sample->quantity }} {{ $sample->unit ?? 'Sampel' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #64748b; padding: 8px;">
                        Data spesimen belum ditambahkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Rincian Parameter Uji --}}
    @if ($parameters->isNotEmpty())
        <div style="font-weight: bold; font-size: 9pt; color: #0f172a; margin-top: 8px; margin-bottom: 4px;">
            PARAMETER PENGUJIAN YANG DIMOHONKAN
        </div>
        <table class="table-data" style="margin-top: 4px; margin-bottom: 12px;">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No.</th>
                    <th style="width: 45%;">Parameter Pemeriksaan</th>
                    <th style="width: 25%;">Instalasi Laboratorium</th>
                    <th style="width: 25%; text-align: right;">Tarif Per Parameter</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($parameters as $pidx => $param)
                    <tr>
                        <td style="text-align: center;">{{ $pidx + 1 }}</td>
                        <td>{{ $param->testParameter?->name ?? 'Parameter Uji' }}</td>
                        <td>{{ $param->testParameter?->serviceGroup?->name ?? 'Lab Lingkungan / Klinik' }}</td>
                        <td style="text-align: right;">
                            Rp {{ number_format((float) ($param->price ?? $param->testParameter?->base_price ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Ketentuan Pengambilan Hasil --}}
    <div class="no-split" style="border: 1px solid #cbd5e1; background-color: #f8fafc; padding: 8px 10px; margin-bottom: 15px; font-size: 7.5pt; color: #334155; line-height: 1.35;">
        <strong>Ketentuan Pelayanan & Pengambilan Laporan Hasil Uji (LHU):</strong><br>
        1. Pemohon wajib menyimpan lembar FPPS ini sebagai bukti sah untuk pengambilan dokumen fisik atau pelacakan mandiri.<br>
        2. Progres pengerjaan sampel dapat dipantau mandiri secara online melalui tautan pada kode QR di bawah.<br>
        3. Laporan Hasil Uji (LHU) final hanya dapat diserahkan/diunduh apabila biaya retribusi pengujian telah dinyatakan lunas.<br>
        4. Apabila terdapat sampel yang ditolak saat uji kelayakan fisik, petugas pelayanan akan mengonfirmasi kepada pemohon.
    </div>

    {{-- Tanda Tangan Dua Belah Pihak --}}
    <table class="signature-table no-split">
        <tr>
            <td style="width: 30%; text-align: center; vertical-align: bottom;">
                @if (!empty($qrCodeDataUri))
                    <img src="{{ $qrCodeDataUri }}" alt="QR Pelacakan" style="width: 75px; height: 75px; border: 1px solid #e2e8f0; padding: 2px;" />
                    <div style="font-size: 6.5pt; color: #475569; margin-top: 3px;">
                        Pindai untuk memantau<br>progres pengujian sampel
                    </div>
                    @if(!empty($trackingUrl))
                        <div style="font-size: 5.5pt; color: #0284c7; margin-top: 2px; word-break: break-all; font-family: monospace;">
                            {{ $trackingUrl }}
                        </div>
                    @endif
                @endif
            </td>
            <td style="width: 35%; text-align: center; vertical-align: bottom;">
                <div style="font-size: 8.5pt; color: #475569;">Pemohon / Yang Menyerahkan,</div>
                <div style="height: 48px;"></div>
                <div style="font-weight: bold; font-size: 8.5pt; text-decoration: underline;">
                    ( {{ $customer->name ?? 'Pemohon' }} )
                </div>
            </td>
            <td style="width: 35%; text-align: center; vertical-align: bottom;">
                <div style="font-size: 8.5pt; color: #475569;">Petugas Loket Pelayanan,</div>
                <div style="height: 48px;"></div>
                <div style="font-weight: bold; font-size: 8.5pt; text-decoration: underline;">
                    ( {{ auth('employee')->user()?->name ?? 'Petugas Loket Balai' }} )
                </div>
            </td>
        </tr>
    </table>
@endsection
