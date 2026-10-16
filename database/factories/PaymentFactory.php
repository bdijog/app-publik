<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\TestRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'test_request_id' => TestRequest::factory(),
            'recorded_by' => Employee::factory(),
            'amount' => fake()->numberBetween(100, 500) * 1000,
            'status' => PaymentStatus::Paid,
            'paid_at' => now(),
        ];
    }
}
