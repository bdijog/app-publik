<?php

namespace Database\Seeders;

use App\Models\ServiceGroup;
use App\Models\ServicePackage;
use App\Models\Tariff;
use App\Models\TestParameter;
use Illuminate\Database\Seeder;

class TariffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Data tarif resmi/indikatif dari PRD Bagian 3 (Brosur Tahun 2025):
     * 1. Tarif paket per kelompok layanan (Air Laut s.d. Kalibrasi).
     * 2. Tarif paket khusus Penelitian: Studi D3, S1, S2.
     * 3. Tarif satuan parameter (asumsi proporsional) untuk pemesanan parameter lepasan.
     */
    public function run(): void
    {
        $groups = ServiceGroup::pluck('id', 'code');

        // 1. Tarif kelompok layanan dari brosur 2025 (PRD Bagian 3)
        $groupTariffs = [
            'AIR_LAUT' => 484000.00,
            'AIR_LIMBAH_DOMESTIK' => 169000.00,
            'AIR_LIMBAH_FASKES' => 373000.00,
            'VEKTOR' => 100000.00,
            'MAKANAN' => 680000.00,
            'SWAB_ALAT' => 228000.00,
            'AIR_BERSIH' => 343000.00,
            'AIR_SUNGAI' => 708000.00,
            'AIR_KOLAM_RENANG' => 103000.00,
            'BAKTERIOLOGI_UDARA' => 60000.00,
            'UDARA_AMBIEN' => 734000.00,
            'UDARA_RUANGAN' => 54000.00,
            'PATOLOGI_KLINIS' => 90000.00,
            'AIR_MINUM' => 503000.00,
            'MIKRO_PARASIT_BIOMOL' => 2229000.00,
            'KALIBRASI' => 1244000.00,
        ];

        foreach ($groupTariffs as $code => $amount) {
            $groupId = $groups[$code] ?? null;
            if (! $groupId) {
                continue;
            }

            Tariff::updateOrCreate(
                [
                    'service_group_id' => $groupId,
                    'service_package_id' => null,
                    'test_parameter_id' => null,
                    'effective_from' => '2025-01-01',
                ],
                [
                    'amount' => $amount,
                    'effective_until' => null,
                ]
            );
        }

        // 2. Tarif paket Penelitian: Studi D3, S1, S2 (PRD Hal. 2)
        $penelitianGroup = $groups['PENELITIAN'] ?? null;
        if ($penelitianGroup) {
            $packages = ServicePackage::where('service_group_id', $penelitianGroup)->get();
            foreach ($packages as $pkg) {
                $amount = match ($pkg->name) {
                    'Studi D3' => 80000.00,
                    'Studi S1' => 120000.00,
                    'Studi S2' => 500000.00,
                    default => 100000.00,
                };

                Tariff::updateOrCreate(
                    [
                        'service_group_id' => $penelitianGroup,
                        'service_package_id' => $pkg->id,
                        'test_parameter_id' => null,
                        'effective_from' => '2025-01-01',
                    ],
                    [
                        'amount' => $amount,
                        'effective_until' => null,
                    ]
                );
            }
        }

        // 3. Tarif per parameter uji (asumsi tarif satuan untuk pemesanan mandiri)
        $parameters = TestParameter::all();
        foreach ($parameters as $parameter) {
            $fee = match (true) {
                str_contains($parameter->name, 'Sequencing') => 1500000.00,
                str_contains($parameter->name, 'RT-PCR') || str_contains($parameter->name, 'TB') => 350000.00,
                str_contains($parameter->name, 'Kalibrasi') => 250000.00,
                str_contains($parameter->name, 'TSP') || str_contains($parameter->name, 'Logam') => 120000.00,
                str_contains($parameter->name, 'Salmonella') || str_contains($parameter->name, 'Vibrio') => 95000.00,
                str_contains($parameter->name, 'Coliform') || str_contains($parameter->name, 'coli') => 65000.00,
                str_contains($parameter->name, 'BOD') || str_contains($parameter->name, 'COD') => 55000.00,
                str_contains($parameter->name, 'Kolesterol') || str_contains($parameter->name, 'Gula') => 35000.00,
                default => 40000.00,
            };

            Tariff::updateOrCreate(
                [
                    'service_group_id' => null,
                    'service_package_id' => null,
                    'test_parameter_id' => $parameter->id,
                    'effective_from' => '2025-01-01',
                ],
                [
                    'amount' => $fee,
                    'effective_until' => null,
                ]
            );
        }
    }
}
