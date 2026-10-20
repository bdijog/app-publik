<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\DocumentAccessLog;
use App\Models\Employee;

class DocumentAccessLogPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::AuditView->value);
    }

    public function view(Employee $employee, DocumentAccessLog $documentAccessLog): bool
    {
        return $employee->can(PermissionEnum::AuditView->value);
    }

    public function create(Employee $employee): bool
    {
        return false;
    }

    public function update(Employee $employee, DocumentAccessLog $documentAccessLog): bool
    {
        return false;
    }

    public function delete(Employee $employee, DocumentAccessLog $documentAccessLog): bool
    {
        return false;
    }

    public function restore(Employee $employee, DocumentAccessLog $documentAccessLog): bool
    {
        return false;
    }

    public function forceDelete(Employee $employee, DocumentAccessLog $documentAccessLog): bool
    {
        return false;
    }
}
