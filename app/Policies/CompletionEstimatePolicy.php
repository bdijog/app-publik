<?php

namespace App\Policies;

use App\Models\CompletionEstimate;
use App\Models\Employee;

class CompletionEstimatePolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('catalog.manage');
    }

    public function view(Employee $employee, CompletionEstimate $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('catalog.manage');
    }

    public function update(Employee $employee, CompletionEstimate $model): bool
    {
        return $employee->can('catalog.manage');
    }

    public function delete(Employee $employee, CompletionEstimate $model): bool
    {
        return $employee->can('catalog.manage');
    }
}
