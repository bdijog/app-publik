<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Employee;
use App\Models\ReportTemplate;

class ReportTemplatePolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value)
            || $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function view(Employee $employee, ReportTemplate $reportTemplate): bool
    {
        return $employee->can(PermissionEnum::ReportManage->value)
            || $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function create(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function update(Employee $employee, ReportTemplate $reportTemplate): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function delete(Employee $employee, ReportTemplate $reportTemplate): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function restore(Employee $employee, ReportTemplate $reportTemplate): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }

    public function forceDelete(Employee $employee, ReportTemplate $reportTemplate): bool
    {
        return $employee->can(PermissionEnum::CatalogManage->value);
    }
}
