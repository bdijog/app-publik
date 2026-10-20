<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Employee;
use App\Models\ResultRevision;

class ResultRevisionPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::AuditView->value)
            || $employee->can(PermissionEnum::ResultRevise->value);
    }

    public function view(Employee $employee, ResultRevision $resultRevision): bool
    {
        return $employee->can(PermissionEnum::AuditView->value)
            || $employee->can(PermissionEnum::ResultRevise->value);
    }

    public function create(Employee $employee): bool
    {
        return false;
    }

    public function update(Employee $employee, ResultRevision $resultRevision): bool
    {
        return false;
    }

    public function delete(Employee $employee, ResultRevision $resultRevision): bool
    {
        return false;
    }

    public function restore(Employee $employee, ResultRevision $resultRevision): bool
    {
        return false;
    }

    public function forceDelete(Employee $employee, ResultRevision $resultRevision): bool
    {
        return false;
    }
}
