<?php

namespace App\Policies;

use App\Models\DocumentAccessLog;
use App\Models\Employee;

class DocumentAccessLogPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('audit.view');
    }

    public function view(Employee $employee, DocumentAccessLog $documentAccessLog): bool
    {
        return $employee->can('audit.view');
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
