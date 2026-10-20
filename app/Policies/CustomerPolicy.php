<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Customer;
use App\Models\Employee;

class CustomerPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::RequestManage->value);
    }

    public function view(Employee $employee, Customer $customer): bool
    {
        return $employee->can(PermissionEnum::RequestManage->value);
    }

    public function create(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::RequestManage->value);
    }

    public function update(Employee $employee, Customer $customer): bool
    {
        return $employee->can(PermissionEnum::RequestManage->value);
    }

    public function delete(Employee $employee, Customer $customer): bool
    {
        return $employee->can(PermissionEnum::RequestManage->value);
    }
}
