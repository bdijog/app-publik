<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Employee;
use App\Models\TestRequest;

class TestRequestPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::RequestManage->value)
            || $employee->hasRole(RoleEnum::Leader);
    }

    public function view(Employee $employee, TestRequest $testRequest): bool
    {
        return $employee->can(PermissionEnum::RequestManage->value)
            || $employee->hasRole(RoleEnum::Leader);
    }

    public function create(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::RequestManage->value);
    }

    public function update(Employee $employee, TestRequest $testRequest): bool
    {
        return $employee->can(PermissionEnum::RequestManage->value);
    }

    public function delete(Employee $employee, TestRequest $testRequest): bool
    {
        return $employee->can(PermissionEnum::RequestManage->value);
    }

    public function restore(Employee $employee, TestRequest $testRequest): bool
    {
        return $employee->can(PermissionEnum::RequestManage->value);
    }

    public function forceDelete(Employee $employee, TestRequest $testRequest): bool
    {
        return $employee->can(PermissionEnum::RequestManage->value);
    }
}
