<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Employee;
use App\Models\TestParameter;

class TestParameterPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function view(Employee $employee, TestParameter $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function create(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function update(Employee $employee, TestParameter $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function delete(Employee $employee, TestParameter $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function restore(Employee $employee, TestParameter $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function forceDelete(Employee $employee, TestParameter $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }
}
