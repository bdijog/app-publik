<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\TestJob;

class TestJobPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('job.view');
    }

    public function view(Employee $employee, TestJob $testJob): bool
    {
        return $employee->can('job.view');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('job.assign');
    }

    public function update(Employee $employee, TestJob $testJob): bool
    {
        return $employee->can('job.assign');
    }

    public function delete(Employee $employee, TestJob $testJob): bool
    {
        return $employee->can('job.assign');
    }

    public function restore(Employee $employee, TestJob $testJob): bool
    {
        return $employee->can('job.assign');
    }

    public function forceDelete(Employee $employee, TestJob $testJob): bool
    {
        return $employee->can('job.assign');
    }
}
