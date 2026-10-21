@extends('exports.pdf.layouts.official-document')

@section('content')
    <div class="doc-title-box">
        <h1 class="doc-main-title">KUITANSI TANDA BUKTI PEMBAYARAN RETRIBUSI</h1>
        <div class="doc-number" style="margin-top: 4px;">
            <strong>Nomor Bukti Kas:</strong> KWT/{{ date('Ym') }}/{{ sprintf('%05d', $payment->id) }}
            &bull; <strong>Nomor FPPS:</strong> <span style="color: #047857; font-weight: bold;">{{ $testRequest->fpps_number ?? '-' }}</span>
        </div>
    </div>

    {{-- Tabel Rincian Pembayaran --}}
    <table style="width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; font-size: 9pt;">
        <tr>
            <td style="width: 25%; padding: 6px 4px; color: #475569;">Telah Diterima Dari</td>
            <td style="width: 2%; padding: 6px 0;">:</td>
            <td style="width: 73%; padding: 6px 4px; font-weight: bold; color: #0f172a;">
                {{ $customer->name ?? 'Pemohon Uji' }}
                @if(!empty($customer?->organization_name))
                    ({{ $customer->organization_name }})
                @endif
            </td>
        </tr>
        <tr>
            <td style="padding: 6px 4px; color: #475569;">Alamat / Lokasi</td>
            <td style="padding: 6px 0;">:</td>
            <td style="padding: 6px 4px; color: #1e293b;">{{ $customer->address ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 4px; color: #475569;">Uang Sejumlah</td>
            <td style="padding: 6px 0;">:</td>
            <td style="padding: 6px 4px; font-style: italic; font-weight: bold; color: #047857; background-color: #ecfdf5; border-radius: 4px;">
                " {{ $terbilang }} "
            </td>
        </tr>
        <tr>
            <td style="padding: 6px 4px; color: #475569;">Untuk Pembayaran</td>
            <td style="padding: 6px 0;">:</td>
            <td style="padding: 6px 4px; color: #1e293b;">
                Retribusi Pelayanan Pengujian Laboratorium Kelompok <strong>{{ $testRequest->serviceGroup->name ?? 'Layanan Lab' }}</strong>
                sesuai Formulir Permintaan Pemeriksaan Sampel No. <strong>{{ $testRequest->fpps_number }}</strong>.
            </td>
        </tr>
        <tr>
            <td style="padding: 6px 4px; color: #475569;">Waktu Pembayaran</td>
            <td style="padding: 6px 0;">:</td>
            <td style="padding: 6px 4px; color: #1e293b;">
                {{ $payment->paid_at ? \Illuminate\Support\Carbon::parse($payment->paid_at)->translatedFormat('d F Y, H:i') : now()->translatedFormat('d F Y, H:i') }} WIT
            </td>
        </tr>
        <tr>
            <td style="padding: 6px 4px; color: #475569;">Status Transaksi</td>
            <td style="padding: 6px 0;">:</td>
            <td style="padding: 6px 4px;">
                <span style="font-weight: bold; color: #047857; border: 1.5px solid #047857; padding: 2px 8px; border-radius: 3px; font-size: 8pt; display: inline-block;">
                    LUNAS - RETRIBUSI RESMI PEMERINTAH
                </span>
            </td>
        </tr>
    </table>

    {{-- Kotak Besar Nominal Rupiah --}}
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">
        <tr>
            <td style="width: 45%; background-color: #f1f5f9; border: 2px solid #cbd5e1; padding: 12px 16px; border-radius: 4px;">
                <div style="font-size: 8pt; color: #64748b; font-weight: bold; text-transform: uppercase;">JUMLAH NOMINAL:</div>
                <div style="font-size: 16pt; font-weight: bold; color: #0f172a; margin-top: 3px;">
                    Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
                </div>
            </td>
            <td style="width: 55%; padding-left: 20px; font-size: 7.5pt; color: #64748b; vertical-align: middle;">
                * Bukti kuitansi ini adalah tanda penerimaan retribusi resmi Balai Laboratorium Kesehatan Masyarakat Ambon yang disetorkan ke Kas Negara / Daerah sesuai peraturan perundang-undangan tarif retribusi yang berlaku.
            </td>
        </tr>
    </table>

    {{-- Tanda Tangan Kasir & Bendahara --}}
    <table class="signature-table no-split">
        <tr>
            <td style="width: 50%; text-align: center; vertical-align: bottom;">
                <div style="font-size: 8.5pt; color: #475569;">Penyetor / Wajib Retribusi,</div>
                <div style="height: 52px;"></div>
                <div style="font-weight: bold; font-size: 9pt; text-decoration: underline;">
                    ( {{ $customer->name ?? 'Pemohon' }} )
                </div>
            </td>
            <td style="width: 50%; text-align: center; vertical-align: bottom;">
                <div style="font-size: 8.5pt; color: #475569;">
                    Ambon, {{ $payment->paid_at ? \Illuminate\Support\Carbon::parse($payment->paid_at)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
                </div>
                <div style="font-size: 8.5pt; font-weight: bold; color: #0f172a;">
                    Kasir / Petugas Penerima Retribusi
                </div>
                <div style="height: 52px;"></div>
                <div style="font-weight: bold; font-size: 9pt; text-decoration: underline;">
                    ( {{ $payment->recordedBy?->name ?? (auth('employee')->user()?->name ?? 'Petugas Kasir Balai') }} )
                </div>
                <div style="font-size: 8pt; color: #475569;">
                    NIP. {{ $payment->recordedBy?->nip ?? '19870315 201102 1 003' }}
                </div>
            </td>
        </tr>
    </table>
@endsection
