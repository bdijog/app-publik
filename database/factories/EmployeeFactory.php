<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\LaboratoryUnit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'laboratory_unit_id' => LaboratoryUnit::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('0812########'),
            'nip' => fake()->unique()->numerify('1990##########001'),
            'password' => static::$password ??= Hash::make('password'),
            'is_active' => true,
            'last_login_at' => null,
            'remember_token' => Str::random(10),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
