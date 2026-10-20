<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\CompletionEstimate;
use App\Models\Employee;

class CompletionEstimatePolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function view(Employee $employee, CompletionEstimate $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function create(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function update(Employee $employee, CompletionEstimate $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function delete(Employee $employee, CompletionEstimate $model): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }
}
