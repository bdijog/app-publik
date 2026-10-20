<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Employee;
use App\Models\Sample;

class SamplePolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::SampleManage->value)
            || $employee->can(PermissionEnum::RequestManage->value)
            || $employee->hasRole(RoleEnum::Leader);
    }

    public function view(Employee $employee, Sample $sample): bool
    {
        return $employee->can(PermissionEnum::SampleManage->value)
            || $employee->can(PermissionEnum::RequestManage->value)
            || $employee->hasRole(RoleEnum::Leader);
    }

    public function create(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::SampleManage->value)
            || $employee->can(PermissionEnum::RequestManage->value);
    }

    public function update(Employee $employee, Sample $sample): bool
    {
        return $employee->can(PermissionEnum::SampleManage->value);
    }

    public function delete(Employee $employee, Sample $sample): bool
    {
        return $employee->can(PermissionEnum::SampleManage->value);
    }
}
