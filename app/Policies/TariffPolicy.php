<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\Tariff;

class TariffPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('catalog.manage');
    }

    public function view(Employee $employee, Tariff $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('catalog.manage');
    }

    public function update(Employee $employee, Tariff $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function delete(Employee $employee, Tariff $model): bool
    {
        return $employee->can('catalog.manage');
    }
}
