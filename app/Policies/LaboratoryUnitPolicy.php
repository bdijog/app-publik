<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\LaboratoryUnit;

class LaboratoryUnitPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('catalog.manage');
    }

    public function view(Employee $employee, LaboratoryUnit $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('catalog.manage');
    }

    public function update(Employee $employee, LaboratoryUnit $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function delete(Employee $employee, LaboratoryUnit $model): bool
    {
        return $employee->can('catalog.manage');
    }
}
