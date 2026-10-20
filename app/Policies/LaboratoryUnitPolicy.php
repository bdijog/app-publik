<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Employee;
use App\Models\LaboratoryUnit;

class LaboratoryUnitPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function view(Employee $employee, LaboratoryUnit $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function create(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function update(Employee $employee, LaboratoryUnit $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function delete(Employee $employee, LaboratoryUnit $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }
}
