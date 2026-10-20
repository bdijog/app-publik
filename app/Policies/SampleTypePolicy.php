<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Employee;
use App\Models\SampleType;

class SampleTypePolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function view(Employee $employee, SampleType $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function create(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function update(Employee $employee, SampleType $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function delete(Employee $employee, SampleType $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function restore(Employee $employee, SampleType $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function forceDelete(Employee $employee, SampleType $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }
}
