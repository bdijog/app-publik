<?php

namespace Database\Factories;

use App\Models\ServiceGroup;
use App\Models\ServicePackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServicePackage>
 */
class ServicePackageFactory extends Factory
{
    protected $model = ServicePackage::class;

    public function definition(): array
    {
        return [
            'service_group_id' => ServiceGroup::factory(),
            'name' => 'Paket '.fake()->words(2, true),
            'is_active' => true,
        ];
    }
}
