<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Employee;
use App\Models\TestReport;

class TestReportPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value)
            || $employee->hasRole(RoleEnum::Leader);
    }

    public function view(Employee $employee, TestReport $testReport): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value)
            || $employee->hasRole(RoleEnum::Leader);
    }

    public function create(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value);
    }

    public function update(Employee $employee, TestReport $testReport): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value);
    }

    public function delete(Employee $employee, TestReport $testReport): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value);
    }

    public function restore(Employee $employee, TestReport $testReport): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value);
    }

    public function forceDelete(Employee $employee, TestReport $testReport): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value);
    }
}
