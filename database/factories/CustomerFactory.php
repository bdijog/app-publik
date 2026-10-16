<?php

namespace Database\Factories;

use App\Enums\CustomerType;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    protected static ?string $password;

    public function definition(): array
    {
        $phone = fake()->numerify('0813########');

        return [
            'name' => fake()->name(),
            'organization_name' => fake()->optional()->company(),
            'customer_type' => fake()->randomElement(CustomerType::cases()),
            'email' => fake()->unique()->safeEmail(),
            'phone' => $phone,
            'phone_hash' => Customer::hashPhone($phone),
            'address' => fake()->address(),
            'password' => static::$password ??= Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    public function withoutAccount(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => null,
            'password' => null,
            'email_verified_at' => null,
        ]);
    }
}
