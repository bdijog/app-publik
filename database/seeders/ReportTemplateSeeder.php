<?php

namespace Database\Seeders;

use App\Models\ReportTemplate;
use Illuminate\Database\Seeder;

class ReportTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReportTemplate::updateOrCreate(
            ['name' => 'Template Resmi Laporan Hasil Uji (LHU) - Balai Labkesmas Ambon'],
            [
                'version' => '1.0',
                'content' => <<<'HTML'
<div class="lhu-document font-sans text-gray-800">
    <div class="border-b-2 border-black pb-4 mb-4 text-center">
        <h2 class="text-xl font-bold tracking-wide">KEMENTERIAN KESEHATAN REPUBLIK INDONESIA</h2>
        <h1 class="text-2xl font-black">BALAI LABORATORIUM KESEHATAN MASYARAKAT AMBON</h1>
        <p class="text-xs text-gray-600">Jl. Wolter Monginsidi, Lateri, Kec. Baguala, Kota Ambon, Maluku</p>
    </div>

    <div class="text-center my-4">
        <h3 class="text-lg font-bold underline">LAPORAN HASIL UJI (LHU)</h3>
        <p class="text-sm font-semibold">Nomor: {{ report_number }}</p>
    </div>

    <div class="grid grid-cols-2 gap-4 text-sm mb-6">
        <div>
            <p><strong>Nama Pemohon / Pelanggan:</strong> {{ customer_name }}</p>
            <p><strong>Instansi:</strong> {{ customer_organization }}</p>
            <p><strong>Nomor FPPS / Permohonan:</strong> {{ fpps_number }}</p>
            <p><strong>Jenis / Nama Sampel:</strong> {{ sample_name }}</p>
        </div>
        <div>
            <p><strong>Tanggal Penerimaan:</strong> {{ received_date }}</p>
            <p><strong>Tanggal Pengujian:</strong> {{ tested_date }}</p>
            <p><strong>Kondisi Sampel:</strong> {{ sample_condition }}</p>
            <p><strong>Titik / Lokasi Sampling:</strong> {{ sampling_location }}</p>
        </div>
    </div>

    <table class="w-full border-collapse border border-gray-400 text-sm mb-6">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-400 px-3 py-2 text-left">No.</th>
                <th class="border border-gray-400 px-3 py-2 text-left">Parameter Uji</th>
                <th class="border border-gray-400 px-3 py-2 text-left">Satuan</th>
                <th class="border border-gray-400 px-3 py-2 text-left">Baku Mutu</th>
                <th class="border border-gray-400 px-3 py-2 text-left">Hasil Uji</th>
                <th class="border border-gray-400 px-3 py-2 text-left">Metode Uji</th>
            </tr>
        </thead>
        <tbody>
            {{ table_rows }}
        </tbody>
    </table>

    <div class="text-sm mb-6">
        <p><strong>Pertimbangan / Kesimpulan:</strong></p>
        <p class="italic bg-gray-50 p-2 border border-gray-200 rounded">{{ considerations }}</p>
    </div>

    <div class="flex justify-between text-center text-sm pt-8">
        <div>
            <p>Verifikator Hasil</p>
            <div class="h-16"></div>
            <p class="font-bold underline">{{ verifier_name }}</p>
            <p class="text-xs text-gray-500">NIP. {{ verifier_nip }}</p>
        </div>
        <div>
            <p>Ambon, {{ validated_date }}</p>
            <p>Validator Hasil / Ka. Instalasi</p>
            <div class="h-16"></div>
            <p class="font-bold underline">{{ validator_name }}</p>
            <p class="text-xs text-gray-500">NIP. {{ validator_nip }}</p>
        </div>
    </div>
</div>
HTML,
                'is_active' => true,
            ]
        );
    }
}
