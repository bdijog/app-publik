<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\ServiceGroup;

class ServiceGroupPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('catalog.manage');
    }

    public function view(Employee $employee, ServiceGroup $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('catalog.manage');
    }

    public function update(Employee $employee, ServiceGroup $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function delete(Employee $employee, ServiceGroup $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function restore(Employee $employee, ServiceGroup $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function forceDelete(Employee $employee, ServiceGroup $model): bool
    {
        return $employee->can('catalog.manage');
    }
}
