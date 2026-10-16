<?php

namespace Database\Factories;

use App\Enums\SampleDecision;
use App\Models\Employee;
use App\Models\Sample;
use App\Models\SampleReceipt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SampleReceipt>
 */
class SampleReceiptFactory extends Factory
{
    protected $model = SampleReceipt::class;

    public function definition(): array
    {
        return [
            'sample_id' => Sample::factory(),
            'received_by' => Employee::factory(),
            'received_at' => now(),
            'received_volume' => fake()->randomFloat(2, 200, 1000),
            'condition' => 'Baik, segel utuh, suhu 4°C',
            'decision' => SampleDecision::Accepted,
            'rejection_reason' => null,
            'follow_up' => 'Diteruskan ke laboratorium untuk diuji',
        ];
    }

    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'decision' => SampleDecision::Accepted,
            'rejection_reason' => null,
            'follow_up' => 'Diteruskan ke laboratorium untuk diuji',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'condition' => 'Wadah rusak, volume kurang dari batas minimum',
            'decision' => SampleDecision::Rejected,
            'rejection_reason' => 'Volume sampel tidak mencukupi dan wadah bocor saat pengiriman.',
            'follow_up' => 'Konfirmasi ke pelanggan untuk pengambilan sampel ulang.',
        ]);
    }
}
