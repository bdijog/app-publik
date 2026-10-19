<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Employee;

class CustomerPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('request.view') || $employee->can('user.manage');
    }

    public function view(Employee $employee, Customer $customer): bool
    {
        return $employee->can('request.view') || $employee->can('user.manage');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('request.create') || $employee->can('user.manage');
    }

    public function update(Employee $employee, Customer $customer): bool
    {
        return $employee->can('request.update') || $employee->can('user.manage');
    }

    public function delete(Employee $employee, Customer $customer): bool
    {
        return $employee->can('request.delete') || $employee->can('user.manage');
    }
}
