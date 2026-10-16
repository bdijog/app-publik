<?php

namespace Database\Factories;

use App\Models\ReportTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReportTemplate>
 */
class ReportTemplateFactory extends Factory
{
    protected $model = ReportTemplate::class;

    public function definition(): array
    {
        return [
            'name' => 'Format LHU Standar Balai Labkesmas Ambon',
            'version' => '1.0',
            'content' => '<h1>Laporan Hasil Uji (LHU)</h1><p>Nomor: {{ report_number }}</p><p>Pelanggan: {{ customer_name }}</p>',
            'is_active' => true,
        ];
    }
}
