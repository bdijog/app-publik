<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\Sample;

class SamplePolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('sample.view');
    }

    public function view(Employee $employee, Sample $sample): bool
    {
        return $employee->can('sample.view');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('sample.receive') || $employee->can('request.create');
    }

    public function update(Employee $employee, Sample $sample): bool
    {
        return $employee->can('sample.receive') || $employee->can('request.update');
    }

    public function delete(Employee $employee, Sample $sample): bool
    {
        return $employee->can('admin') || $employee->can('user.manage');
    }
}
