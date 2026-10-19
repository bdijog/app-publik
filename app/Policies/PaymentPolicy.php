<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\Payment;

class PaymentPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('payment.view');
    }

    public function view(Employee $employee, Payment $payment): bool
    {
        return $employee->can('payment.view');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('payment.record');
    }

    public function update(Employee $employee, Payment $payment): bool
    {
        return $employee->can('payment.record');
    }

    public function delete(Employee $employee, Payment $payment): bool
    {
        return $employee->can('admin') || $employee->can('user.manage');
    }
}
