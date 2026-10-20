<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Employee;
use App\Models\TestResult;

class TestResultPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::ResultManage->value)
            || $employee->can(PermissionEnum::ResultValidate->value)
            || $employee->can(PermissionEnum::JobManage->value);
    }

    public function view(Employee $employee, TestResult $testResult): bool
    {
        return $employee->can(PermissionEnum::ResultManage->value)
            || $employee->can(PermissionEnum::ResultValidate->value)
            || $employee->can(PermissionEnum::JobManage->value);
    }

    public function create(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::ResultManage->value);
    }

    public function update(Employee $employee, TestResult $testResult): bool
    {
        return $employee->can(PermissionEnum::ResultManage->value)
            || $employee->can(PermissionEnum::ResultValidate->value)
            || $employee->can(PermissionEnum::ResultRevise->value);
    }

    public function delete(Employee $employee, TestResult $testResult): bool
    {
        return $employee->can(PermissionEnum::ResultManage->value);
    }

    public function restore(Employee $employee, TestResult $testResult): bool
    {
        return $employee->can(PermissionEnum::ResultManage->value);
    }

    public function forceDelete(Employee $employee, TestResult $testResult): bool
    {
        return $employee->can(PermissionEnum::ResultManage->value);
    }
}
