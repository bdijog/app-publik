<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\TestReport;

class TestReportPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('report.generate')
            || $employee->can('report.issue')
            || $employee->can('report.print')
            || $employee->can('report.handover');
    }

    public function view(Employee $employee, TestReport $testReport): bool
    {
        return $employee->can('report.generate')
            || $employee->can('report.issue')
            || $employee->can('report.print')
            || $employee->can('report.handover');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('report.generate');
    }

    public function update(Employee $employee, TestReport $testReport): bool
    {
        return $employee->can('report.generate') || $employee->can('report.issue');
    }

    public function delete(Employee $employee, TestReport $testReport): bool
    {
        return $employee->hasRole('admin');
    }

    public function restore(Employee $employee, TestReport $testReport): bool
    {
        return $employee->hasRole('admin');
    }

    public function forceDelete(Employee $employee, TestReport $testReport): bool
    {
        return $employee->hasRole('admin');
    }
}
