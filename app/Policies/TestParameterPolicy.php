<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\TestParameter;

class TestParameterPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('catalog.manage');
    }

    public function view(Employee $employee, TestParameter $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('catalog.manage');
    }

    public function update(Employee $employee, TestParameter $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function delete(Employee $employee, TestParameter $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function restore(Employee $employee, TestParameter $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function forceDelete(Employee $employee, TestParameter $model): bool
    {
        return $employee->can('catalog.manage');
    }
}
