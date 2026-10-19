<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\ReportHandover;

class ReportHandoverPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('report.handover') || $employee->can('report.issue');
    }

    public function view(Employee $employee, ReportHandover $reportHandover): bool
    {
        return $employee->can('report.handover') || $employee->can('report.issue');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('report.handover');
    }

    public function update(Employee $employee, ReportHandover $reportHandover): bool
    {
        return $employee->can('report.handover');
    }

    public function delete(Employee $employee, ReportHandover $reportHandover): bool
    {
        return $employee->hasRole('admin');
    }

    public function restore(Employee $employee, ReportHandover $reportHandover): bool
    {
        return $employee->hasRole('admin');
    }

    public function forceDelete(Employee $employee, ReportHandover $reportHandover): bool
    {
        return $employee->hasRole('admin');
    }
}
