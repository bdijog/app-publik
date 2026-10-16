<?php

namespace Database\Seeders;

use App\Models\LaboratoryUnit;
use App\Models\ServiceGroup;
use Illuminate\Database\Seeder;

class ServiceGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = LaboratoryUnit::pluck('id', 'code');

        $groups = [
            [
                'code' => 'AIR_LAUT',
                'name' => 'Air Laut',
                'unit_code' => 'LAB-KIM',
                'description' => 'Pemeriksaan kualitas air laut meliputi parameter fisik, kimia, dan mikrobiologi.',
            ],
            [
                'code' => 'AIR_LIMBAH_DOMESTIK',
                'name' => 'Air Limbah Domestik',
                'unit_code' => 'LAB-KIM',
                'description' => 'Pemeriksaan kualitas air limbah domestik (pH, BOD, COD, TSS, amoniak, coliform).',
            ],
            [
                'code' => 'AIR_LIMBAH_FASKES',
                'name' => 'Air Limbah Faskes',
                'unit_code' => 'LAB-KIM',
                'description' => 'Pemeriksaan air limbah fasilitas pelayanan kesehatan dan logam berat.',
            ],
            [
                'code' => 'VEKTOR',
                'name' => 'Vektor',
                'unit_code' => 'LAB-VEK',
                'description' => 'Pengujian dan identifikasi vektor penyakit (lalat, tikus, nyamuk, kecoa).',
            ],
            [
                'code' => 'MAKANAN',
                'name' => 'Makanan',
                'unit_code' => 'LAB-MIK',
                'description' => 'Pemeriksaan keamanan pangan, bahan berbahaya (boraks, formalin, pewarna) dan cemaran mikroba.',
            ],
            [
                'code' => 'SWAB_ALAT',
                'name' => 'Swab Alat',
                'unit_code' => 'LAB-MIK',
                'description' => 'Uji usap alat makan dan medis untuk angka kuman, coliform, dan E. coli.',
            ],
            [
                'code' => 'AIR_BERSIH',
                'name' => 'Air Bersih',
                'unit_code' => 'LAB-KIM',
                'description' => 'Pemeriksaan air untuk keperluan higiene dan sanitasi.',
            ],
            [
                'code' => 'AIR_SUNGAI',
                'name' => 'Air Sungai',
                'unit_code' => 'LAB-KIM',
                'description' => 'Pemeriksaan air sungai dan badan air permukaan.',
            ],
            [
                'code' => 'AIR_KOLAM_RENANG',
                'name' => 'Air Kolam Renang',
                'unit_code' => 'LAB-KIM',
                'description' => 'Pemeriksaan kualitas air kolam renang dan pemandian umum.',
            ],
            [
                'code' => 'BAKTERIOLOGI_UDARA',
                'name' => 'Bakteriologi Udara',
                'unit_code' => 'LAB-MIK',
                'description' => 'Pemeriksaan angka kuman udara ruangan.',
            ],
            [
                'code' => 'UDARA_AMBIEN',
                'name' => 'Udara Ambien',
                'unit_code' => 'LAB-FIS',
                'description' => 'Pemeriksaan kualitas udara ambien dan kebisingan lingkungan.',
            ],
            [
                'code' => 'UDARA_RUANGAN',
                'name' => 'Udara Ruangan',
                'unit_code' => 'LAB-FIS',
                'description' => 'Pemeriksaan kualitas fisik udara dalam ruangan (suhu, kelembapan, pencahayaan).',
            ],
            [
                'code' => 'PATOLOGI_KLINIS',
                'name' => 'Patologi Klinis',
                'unit_code' => 'LAB-PAT',
                'description' => 'Pemeriksaan kimia darah dan parameter klinis (kolesterol, asam urat, gula darah).',
            ],
            [
                'code' => 'AIR_MINUM',
                'name' => 'Air Minum',
                'unit_code' => 'LAB-KIM',
                'description' => 'Pemeriksaan air minum sesuai standar Permenkes kualitas air minum.',
            ],
            [
                'code' => 'PENELITIAN',
                'name' => 'Penelitian',
                'unit_code' => 'LAB-PAT',
                'description' => 'Layanan fasilitas laboratorium untuk penelitian akademik D3, S1, dan S2.',
            ],
            [
                'code' => 'MIKRO_PARASIT_BIOMOL',
                'name' => 'Mikrobiologi, Parasitologi, dan Biomolekuler',
                'unit_code' => 'LAB-PAT',
                'description' => 'Pemeriksaan biomolekuler (PCR), sequencing, TB, malaria, difteri, pertusis, parasitologi.',
            ],
            [
                'code' => 'KALIBRASI',
                'name' => 'Kalibrasi',
                'unit_code' => 'LAB-KAL',
                'description' => 'Kalibrasi peralatan laboratorium kesehatan (inkubator, centrifuge, photometer, dll.).',
            ],
        ];

        foreach ($groups as $group) {
            ServiceGroup::updateOrCreate(
                ['code' => $group['code']],
                [
                    'laboratory_unit_id' => $units[$group['unit_code']] ?? 1,
                    'name' => $group['name'],
                    'description' => $group['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
