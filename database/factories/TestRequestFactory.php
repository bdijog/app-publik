<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Enums\RegistrationChannel;
use App\Enums\TestRequestStatus;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\ServiceGroup;
use App\Models\TestRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TestRequest>
 */
class TestRequestFactory extends Factory
{
    protected $model = TestRequest::class;

    public function definition(): array
    {
        return [
            'request_number' => 'REQ-'.date('Ymd').'-'.fake()->unique()->numerify('#####'),
            'fpps_number' => 'FPPS-'.date('Ymd').'-'.fake()->unique()->numerify('#####'),
            'customer_id' => Customer::factory(),
            'service_group_id' => ServiceGroup::factory(),
            'service_package_id' => null,
            'created_by' => Employee::factory(),
            'purpose' => 'Uji Kualitas Lingkungan & Kesehatan',
            'channel' => RegistrationChannel::Counter,
            'sampling_location' => fake()->address(),
            'sampling_date' => fake()->dateTimeBetween('-10 days', 'now')->format('Y-m-d'),
            'notes' => fake()->sentence(),
            'total_amount' => fake()->numberBetween(150, 750) * 1000,
            'payment_status' => PaymentStatus::Unpaid,
            'status' => TestRequestStatus::Submitted,
            'estimated_completion_date' => now()->addDays(10)->format('Y-m-d'),
            'registered_at' => now(),
        ];
    }

    public function online(): static
    {
        return $this->state(fn (array $attributes) => [
            'channel' => RegistrationChannel::Online,
            'created_by' => null,
        ]);
    }

    public function whatsapp(): static
    {
        return $this->state(fn (array $attributes) => [
            'channel' => RegistrationChannel::WhatsApp,
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => PaymentStatus::Paid,
            'status' => TestRequestStatus::AwaitingSample,
        ]);
    }
}
