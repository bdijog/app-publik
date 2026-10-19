<?php

namespace App\Policies;

use App\Models\Employee;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('user.manage');
    }

    public function view(Employee $employee, Permission $permission): bool
    {
        return $employee->can('user.manage');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('user.manage');
    }

    public function update(Employee $employee, Permission $permission): bool
    {
        return $employee->can('user.manage');
    }

    public function delete(Employee $employee, Permission $permission): bool
    {
        return $employee->can('user.manage');
    }
}
