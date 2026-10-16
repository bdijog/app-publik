<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            LaboratoryUnitSeeder::class,
            EmployeeSeeder::class,
            ServiceGroupSeeder::class,
            TestParameterSeeder::class,
            ServicePackageSeeder::class,
            SampleTypeSeeder::class,
            TariffSeeder::class,
            CompletionEstimateSeeder::class,
            ReportTemplateSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
