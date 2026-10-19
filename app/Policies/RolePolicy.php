<?php

namespace App\Policies;

use App\Models\Employee;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('user.manage');
    }

    public function view(Employee $employee, Role $role): bool
    {
        return $employee->can('user.manage');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('user.manage');
    }

    public function update(Employee $employee, Role $role): bool
    {
        return $employee->can('user.manage');
    }

    public function delete(Employee $employee, Role $role): bool
    {
        return $employee->can('user.manage');
    }
}
