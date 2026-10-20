<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Employee;
use App\Models\TestJob;

class TestJobPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::JobManage->value)
            || $employee->hasRole(RoleEnum::Leader);
    }

    public function view(Employee $employee, TestJob $testJob): bool
    {
        return $employee->can(PermissionEnum::JobManage->value)
            || $employee->hasRole(RoleEnum::Leader);
    }

    public function create(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::JobManage->value);
    }

    public function update(Employee $employee, TestJob $testJob): bool
    {
        return $employee->can(PermissionEnum::JobManage->value);
    }

    public function delete(Employee $employee, TestJob $testJob): bool
    {
        return $employee->can(PermissionEnum::JobManage->value);
    }

    public function restore(Employee $employee, TestJob $testJob): bool
    {
        return $employee->can(PermissionEnum::JobManage->value);
    }

    public function forceDelete(Employee $employee, TestJob $testJob): bool
    {
        return $employee->can(PermissionEnum::JobManage->value);
    }
}
