<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Employee;
use App\Models\ReportHandover;

class ReportHandoverPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value)
            || $employee->hasRole(RoleEnum::Leader);
    }

    public function view(Employee $employee, ReportHandover $reportHandover): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value)
            || $employee->hasRole(RoleEnum::Leader);
    }

    public function create(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value);
    }

    public function update(Employee $employee, ReportHandover $reportHandover): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value);
    }

    public function delete(Employee $employee, ReportHandover $reportHandover): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value);
    }

    public function restore(Employee $employee, ReportHandover $reportHandover): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value);
    }

    public function forceDelete(Employee $employee, ReportHandover $reportHandover): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value);
    }
}
