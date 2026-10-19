<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\TestResult;

class TestResultPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('result.input')
            || $employee->can('result.validate')
            || $employee->can('result.edit')
            || $employee->can('job.view');
    }

    public function view(Employee $employee, TestResult $testResult): bool
    {
        return $employee->can('result.input')
            || $employee->can('result.validate')
            || $employee->can('result.edit')
            || $employee->can('job.view');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('result.input');
    }

    public function update(Employee $employee, TestResult $testResult): bool
    {
        return $employee->can('result.input')
            || $employee->can('result.edit')
            || $employee->can('result.validate')
            || $employee->can('result.revise');
    }

    public function delete(Employee $employee, TestResult $testResult): bool
    {
        return $employee->hasRole('admin');
    }

    public function restore(Employee $employee, TestResult $testResult): bool
    {
        return $employee->hasRole('admin');
    }

    public function forceDelete(Employee $employee, TestResult $testResult): bool
    {
        return $employee->hasRole('admin');
    }
}
