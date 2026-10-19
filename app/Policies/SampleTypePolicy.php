<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\SampleType;

class SampleTypePolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('catalog.manage');
    }

    public function view(Employee $employee, SampleType $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('catalog.manage');
    }

    public function update(Employee $employee, SampleType $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function delete(Employee $employee, SampleType $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function restore(Employee $employee, SampleType $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function forceDelete(Employee $employee, SampleType $model): bool
    {
        return $employee->can('catalog.manage');
    }
}
