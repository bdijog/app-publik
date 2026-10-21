@extends('exports.pdf.layouts.official-document')

@section('content')
    <div class="doc-title-box">
        <h1 class="doc-main-title" style="color: #b91c1c;">BERITA ACARA PENOLAKAN SAMPEL UJI</h1>
        <div style="font-size: 8.5pt; color: #475569; margin-top: 1px;">Keterangan Sampel Tidak Memenuhi Persyaratan Uji Teknis Laboratorium</div>
        <div class="doc-number">
            <strong>Nomor Berita Acara:</strong> BAP-TOLAK/{{ date('Ym') }}/{{ sprintf('%04d', $receipt->id) }}
            &bull; <strong>Kode Sampel:</strong> <span style="color: #b91c1c; font-weight: bold;">{{ $sample->sample_code ?? '-' }}</span>
        </div>
    </div>

    <div style="font-size: 9pt; line-height: 1.5; margin-bottom: 12px; color: #1e293b;">
        Pada hari ini, <strong>{{ $receipt->received_at ? \Illuminate\Support\Carbon::parse($receipt->received_at)->translatedFormat('l, d F Y') : now()->translatedFormat('l, d F Y') }}</strong>, bertempat di Ruang Penerimaan Sampel Balai Laboratorium Kesehatan Masyarakat Ambon, telah dilakukan pemeriksaan fisik dan verifikasi kelayakan terhadap sampel uji yang diserahkan oleh pemohon:
    </div>

    {{-- Identitas Sampel & Pemohon --}}
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 8.5pt;">
        <tr>
            <td style="width: 30%; padding: 4px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569;">Nama Pemohon / Faskes</td>
            <td style="width: 2%; padding: 4px; border: 1px solid #cbd5e1;">:</td>
            <td style="width: 68%; padding: 4px; border: 1px solid #cbd5e1; font-weight: bold;">
                {{ $customer->name ?? '-' }}
                @if(!empty($customer?->organization_name))
                    ({{ $customer->organization_name }})
                @endif
            </td>
        </tr>
        <tr>
            <td style="padding: 4px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569;">Nomor Registrasi FPPS</td>
            <td style="padding: 4px; border: 1px solid #cbd5e1;">:</td>
            <td style="padding: 4px; border: 1px solid #cbd5e1; font-weight: bold; color: #047857;">{{ $testRequest->fpps_number ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569;">Jenis Layanan / Pengujian</td>
            <td style="padding: 4px; border: 1px solid #cbd5e1;">:</td>
            <td style="padding: 4px; border: 1px solid #cbd5e1;">{{ $testRequest->serviceGroup->name ?? 'Pengujian Laboratorium' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569;">Nama / Deskripsi Sampel</td>
            <td style="padding: 4px; border: 1px solid #cbd5e1;">:</td>
            <td style="padding: 4px; border: 1px solid #cbd5e1;">{{ $sample->sample_name ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569;">Volume / Jumlah Diterima</td>
            <td style="padding: 4px; border: 1px solid #cbd5e1;">:</td>
            <td style="padding: 4px; border: 1px solid #cbd5e1;">{{ $receipt->received_volume ?? $sample->quantity }} {{ $sample->unit ?? 'Sampel' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569;">Kondisi Fisik Saat Tiba</td>
            <td style="padding: 4px; border: 1px solid #cbd5e1;">:</td>
            <td style="padding: 4px; border: 1px solid #cbd5e1; color: #b91c1c; font-weight: 500;">
                {{ $receipt->condition ?? 'Tidak memenuhi kriteria uji fisik' }}
            </td>
        </tr>
    </table>

    {{-- Kotak Alasan Penolakan Resmi --}}
    <div class="no-split" style="border: 2px solid #b91c1c; background-color: #fef2f2; padding: 12px 14px; border-radius: 4px; margin-bottom: 15px;">
        <div style="font-weight: bold; font-size: 9pt; color: #991b1b; margin-bottom: 5px; text-transform: uppercase;">
            ALASAN TEKNIS PENOLAKAN SAMPEL:
        </div>
        <div style="font-size: 9pt; color: #7f1d1d; line-height: 1.45; font-weight: 500;">
            {{ $receipt->rejection_reason ?? 'Volume sampel tidak mencukupi batas minimum metode uji atau kemasan sampel mengalami kerusakan / kebocoran / terputusnya rantai dingin (cold chain) selama pengiriman.' }}
        </div>
    </div>

    {{-- Rekomendasi Tindak Lanjut --}}
    <div class="no-split" style="border: 1px solid #cbd5e1; background-color: #f8fafc; padding: 10px 12px; margin-bottom: 20px; font-size: 8.5pt; color: #334155; line-height: 1.4;">
        <strong>Rekomendasi & Tindak Lanjut:</strong><br>
        {{ $receipt->follow_up ?? 'Pemohon disarankan untuk melakukan pengambilan sampel ulang (resampling) sesuai petunjuk teknis wadah dan volume standar Balai Labkesmas Ambon serta menyerahkan spesimen pengganti ke loket pelayanan.' }}
    </div>

    {{-- Tanda Tangan Dua Pihak --}}
    <table class="signature-table no-split">
        <tr>
            <td style="width: 50%; text-align: center; vertical-align: bottom;">
                <div style="font-size: 8.5pt; color: #475569;">Kurir / Pemohon yang Menyerahkan,</div>
                <div style="height: 52px;"></div>
                <div style="font-weight: bold; font-size: 9pt; text-decoration: underline;">
                    ( {{ $customer->name ?? 'Pemohon / Kurir' }} )
                </div>
            </td>
            <td style="width: 50%; text-align: center; vertical-align: bottom;">
                <div style="font-size: 8.5pt; color: #475569;">
                    Ambon, {{ $receipt->received_at ? \Illuminate\Support\Carbon::parse($receipt->received_at)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
                </div>
                <div style="font-size: 8.5pt; font-weight: bold; color: #0f172a;">
                    Petugas Penerima Sampel Laboratorium
                </div>
                <div style="height: 52px;"></div>
                <div style="font-weight: bold; font-size: 9pt; text-decoration: underline;">
                    ( {{ $receipt->receivedBy?->name ?? (auth('employee')->user()?->name ?? 'Petugas Penerima Sampel') }} )
                </div>
                <div style="font-size: 8pt; color: #475569;">
                    NIP. {{ $receipt->receivedBy?->nip ?? '19900812 201403 2 004' }}
                </div>
            </td>
        </tr>
    </table>
@endsection
