<?php

namespace App\Policies;

use App\Models\Employee;

class EmployeePolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('user.manage');
    }

    public function view(Employee $employee, Employee $target): bool
    {
        return $employee->can('user.manage') || $employee->id === $target->id;
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('user.manage');
    }

    public function update(Employee $employee, Employee $target): bool
    {
        return $employee->can('user.manage') || $employee->id === $target->id;
    }

    public function delete(Employee $employee, Employee $target): bool
    {
        return $employee->can('user.manage') && $employee->id !== $target->id;
    }

    public function restore(Employee $employee, Employee $target): bool
    {
        return $employee->can('user.manage');
    }

    public function forceDelete(Employee $employee, Employee $target): bool
    {
        return $employee->can('user.manage') && $employee->id !== $target->id;
    }
}
