<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\ServicePackage;

class ServicePackagePolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('catalog.manage');
    }

    public function view(Employee $employee, ServicePackage $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('catalog.manage');
    }

    public function update(Employee $employee, ServicePackage $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function delete(Employee $employee, ServicePackage $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function restore(Employee $employee, ServicePackage $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function forceDelete(Employee $employee, ServicePackage $model): bool
    {
        return $employee->can('catalog.manage');
    }
}
