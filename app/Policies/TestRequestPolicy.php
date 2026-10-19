<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\TestRequest;

class TestRequestPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('request.view');
    }

    public function view(Employee $employee, TestRequest $testRequest): bool
    {
        return $employee->can('request.view');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('request.create');
    }

    public function update(Employee $employee, TestRequest $testRequest): bool
    {
        return $employee->can('request.update');
    }

    public function delete(Employee $employee, TestRequest $testRequest): bool
    {
        return $employee->can('request.delete');
    }

    public function restore(Employee $employee, TestRequest $testRequest): bool
    {
        return $employee->can('request.delete');
    }

    public function forceDelete(Employee $employee, TestRequest $testRequest): bool
    {
        return $employee->can('request.delete');
    }
}
