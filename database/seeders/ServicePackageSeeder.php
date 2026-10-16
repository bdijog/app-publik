<?php

namespace Database\Seeders;

use App\Models\ServiceGroup;
use App\Models\ServicePackage;
use App\Models\TestParameter;
use Illuminate\Database\Seeder;

class ServicePackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Data paket pemeriksaan untuk seluruh kelompok layanan pada brosur,
     * termasuk paket khusus Penelitian (Studi D3, Studi S1, Studi S2).
     */
    public function run(): void
    {
        $groups = ServiceGroup::pluck('id', 'code');

        $packagesConfig = [
            'AIR_LAUT' => [
                'Paket Pengujian Kualitas Air Laut Standar',
            ],
            'AIR_LIMBAH_DOMESTIK' => [
                'Paket Baku Mutu Limbah Cair Domestik',
            ],
            'AIR_LIMBAH_FASKES' => [
                'Paket Logam Berat & Limbah Cair Faskes',
            ],
            'VEKTOR' => [
                'Paket Identifikasi & Kepadatan Vektor Penyakit',
            ],
            'MAKANAN' => [
                'Paket Skrining Bahan Berbahaya (Boraks, Formalin, Pewarna)',
                'Paket Uji Mikrobiologi Keamanan Pangan',
            ],
            'SWAB_ALAT' => [
                'Paket Higiene Sanitasi Alat Makan & Medis',
            ],
            'AIR_BERSIH' => [
                'Paket Uji Lengkap Air Bersih Permenkes',
            ],
            'AIR_SUNGAI' => [
                'Paket Pemantauan Kualitas Air Sungai & Badan Air',
            ],
            'AIR_KOLAM_RENANG' => [
                'Paket Pengujian Air Kolam Renang & Pemandian',
            ],
            'BAKTERIOLOGI_UDARA' => [
                'Paket Pemeriksaan Bakteriologi Udara Ruangan',
            ],
            'UDARA_AMBIEN' => [
                'Paket Pemantauan Kualitas Udara Ambien 24 Jam',
            ],
            'UDARA_RUANGAN' => [
                'Paket Pengujian Lingkungan Fisik Ruang Kerja',
            ],
            'PATOLOGI_KLINIS' => [
                'Paket Skrining Profil Lipid & Gula Darah',
            ],
            'AIR_MINUM' => [
                'Paket Standar Wajib Air Minum (Permenkes 2023)',
            ],
            'PENELITIAN' => [
                'Studi D3',
                'Studi S1',
                'Studi S2',
            ],
            'MIKRO_PARASIT_BIOMOL' => [
                'Paket Diagnosis Molekuler & Penyakit Menular Tropis',
            ],
            'KALIBRASI' => [
                'Paket Kalibrasi Peralatan Laboratorium Terintegrasi',
            ],
        ];

        foreach ($packagesConfig as $groupCode => $packageNames) {
            $groupId = $groups[$groupCode] ?? null;
            if (! $groupId) {
                continue;
            }

            $allGroupParamIds = TestParameter::where('service_group_id', $groupId)->pluck('id');

            foreach ($packageNames as $pkgName) {
                $package = ServicePackage::updateOrCreate(
                    [
                        'service_group_id' => $groupId,
                        'name' => $pkgName,
                    ],
                    [
                        'is_active' => true,
                    ]
                );

                // Hubungkan parameter ke paket
                if ($allGroupParamIds->isNotEmpty()) {
                    if ($pkgName === 'Paket Skrining Bahan Berbahaya (Boraks, Formalin, Pewarna)') {
                        $specificIds = TestParameter::where('service_group_id', $groupId)
                            ->whereIn('name', ['Boraks', 'Formalin', 'Rhodamin B', 'Methanil Yellow'])
                            ->pluck('id');
                        $package->parameters()->sync($specificIds);
                    } elseif ($pkgName === 'Paket Uji Mikrobiologi Keamanan Pangan') {
                        $specificIds = TestParameter::where('service_group_id', $groupId)
                            ->whereIn('name', ['Angka Lempeng Total (ALT)', 'Coliform', 'Salmonella sp.', 'Vibrio cholerae / parahaemolyticus'])
                            ->pluck('id');
                        $package->parameters()->sync($specificIds);
                    } else {
                        // Hubungkan seluruh parameter relevan di grup ini
                        $package->parameters()->sync($allGroupParamIds);
                    }
                }
            }
        }
    }
}
