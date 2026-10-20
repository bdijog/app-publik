<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Employee;
use App\Models\Payment;

class PaymentPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::PaymentManage->value);
    }

    public function view(Employee $employee, Payment $payment): bool
    {
        return $employee->can(PermissionEnum::PaymentManage->value);
    }

    public function create(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::PaymentManage->value);
    }

    public function update(Employee $employee, Payment $payment): bool
    {
        return $employee->can(PermissionEnum::PaymentManage->value);
    }

    public function delete(Employee $employee, Payment $payment): bool
    {
        return $employee->can(PermissionEnum::PaymentManage->value);
    }
}
