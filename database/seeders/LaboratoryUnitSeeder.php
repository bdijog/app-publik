<?php

namespace Database\Seeders;

use App\Models\LaboratoryUnit;
use Illuminate\Database\Seeder;

class LaboratoryUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['code' => 'LAB-KIM', 'name' => 'Instalasi Kimia Lingkungan & Pangan'],
            ['code' => 'LAB-MIK', 'name' => 'Instalasi Mikrobiologi & Parasitologi'],
            ['code' => 'LAB-VEK', 'name' => 'Instalasi Biologi & Pengendalian Vektor'],
            ['code' => 'LAB-FIS', 'name' => 'Instalasi Fisika & Udara'],
            ['code' => 'LAB-PAT', 'name' => 'Instalasi Patologi Klinis & Biomolekuler'],
            ['code' => 'LAB-KAL', 'name' => 'Instalasi Kalibrasi & Penunjang'],
        ];

        foreach ($units as $unit) {
            LaboratoryUnit::updateOrCreate(
                ['code' => $unit['code']],
                ['name' => $unit['name']]
            );
        }
    }
}
