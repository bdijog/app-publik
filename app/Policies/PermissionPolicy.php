<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Employee;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::UserManage->value);
    }

    public function view(Employee $employee, Permission $permission): bool
    {
        return $employee->can(PermissionEnum::UserManage->value);
    }

    public function create(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::UserManage->value);
    }

    public function update(Employee $employee, Permission $permission): bool
    {
        return $employee->can(PermissionEnum::UserManage->value);
    }

    public function delete(Employee $employee, Permission $permission): bool
    {
        return $employee->can(PermissionEnum::UserManage->value);
    }
}
