<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\ReportTemplate;

class ReportTemplatePolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('report.generate') || $employee->can('catalog.manage');
    }

    public function view(Employee $employee, ReportTemplate $reportTemplate): bool
    {
        return $employee->can('report.generate') || $employee->can('catalog.manage');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('catalog.manage');
    }

    public function update(Employee $employee, ReportTemplate $reportTemplate): bool
    {
        return $employee->can('catalog.manage');
    }

    public function delete(Employee $employee, ReportTemplate $reportTemplate): bool
    {
        return $employee->can('catalog.manage');
    }

    public function restore(Employee $employee, ReportTemplate $reportTemplate): bool
    {
        return $employee->can('catalog.manage');
    }

    public function forceDelete(Employee $employee, ReportTemplate $reportTemplate): bool
    {
        return $employee->can('catalog.manage');
    }
}
