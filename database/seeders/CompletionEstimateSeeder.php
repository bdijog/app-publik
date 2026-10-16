<?php

namespace Database\Seeders;

use App\Enums\EstimateStartPoint;
use App\Models\CompletionEstimate;
use App\Models\ServiceGroup;
use Illuminate\Database\Seeder;

class CompletionEstimateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Berdasarkan PRD Hal. 2 & Catatan Bagian 4:
     * - Brosur menyebut estimasi standar 10 hari kalender dari tanggal penerimaan sampel.
     * - Sistem pelacakan lama menyebut 8 hari kalender dari registrasi pembayaran.
     * - Disediakan estimasi realistis spesifik per kelompok layanan sesuai waktu inkubasi/analisis teknis.
     */
    public function run(): void
    {
        // 1. Konfigurasi umum global (null service_group_id)
        CompletionEstimate::updateOrCreate(
            [
                'service_group_id' => null,
                'start_point' => EstimateStartPoint::Receipt,
                'effective_from' => '2025-01-01',
            ],
            [
                'days' => 10,
            ]
        );

        CompletionEstimate::updateOrCreate(
            [
                'service_group_id' => null,
                'start_point' => EstimateStartPoint::Payment,
                'effective_from' => '2025-01-01',
            ],
            [
                'days' => 8,
            ]
        );

        // 2. Estimasi waktu penyelesaian spesifik per kelompok layanan
        $groups = ServiceGroup::pluck('id', 'code');

        $durations = [
            'PATOLOGI_KLINIS' => 1,       // Hasil dapat selesai dalam 1 hari
            'BAKTERIOLOGI_UDARA' => 3,    // Inkubasi lempeng 48-72 jam
            'SWAB_ALAT' => 3,             // Inkubasi kuman 72 jam
            'UDARA_RUANGAN' => 3,         // Pengukuran lapangan & rekap data
            'AIR_MINUM' => 5,             // Analisis mikrobiologi & kimia dasar
            'AIR_BERSIH' => 5,            // Analisis kimia & bakteriologis
            'AIR_KOLAM_RENANG' => 5,      // Kimia klorin & mikrobiologi
            'VEKTOR' => 5,                // Identifikasi taksonomi & mikroskopis
            'MAKANAN' => 7,               // Kultur Salmonella & uji bahan berbahaya
            'AIR_LIMBAH_DOMESTIK' => 7,   // Inkubasi BOD 5 hari + verifikasi
            'AIR_LIMBAH_FASKES' => 7,     // Preparasi destruksi AAS & logam
            'UDARA_AMBIEN' => 7,          // Gravimetri HVAS & spektrofotometri gas
            'AIR_LAUT' => 10,             // Multi-parameter kimia, logam, mikrobiologi
            'AIR_SUNGAI' => 10,           // Baku mutu air sungai
            'MIKRO_PARASIT_BIOMOL' => 10, // Ekstraksi PCR, sequencing, konfirmasi
            'PENELITIAN' => 14,           // Pelaksanaan riset & bimbingan lab
            'KALIBRASI' => 14,            // Pengujian multi-titik & penerbitan sertifikat
        ];

        foreach ($durations as $code => $days) {
            $groupId = $groups[$code] ?? null;
            if (! $groupId) {
                continue;
            }

            CompletionEstimate::updateOrCreate(
                [
                    'service_group_id' => $groupId,
                    'start_point' => EstimateStartPoint::Receipt,
                    'effective_from' => '2025-01-01',
                ],
                [
                    'days' => $days,
                ]
            );
        }
    }
}
