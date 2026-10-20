<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\ActivityLog;
use App\Models\Employee;

class ActivityLogPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can(PermissionEnum::AuditView->value);
    }

    public function view(Employee $employee, ActivityLog $activityLog): bool
    {
        return $employee->can(PermissionEnum::AuditView->value);
    }

    public function create(Employee $employee): bool
    {
        return false;
    }

    public function update(Employee $employee, ActivityLog $activityLog): bool
    {
        return false;
    }

    public function delete(Employee $employee, ActivityLog $activityLog): bool
    {
        return false;
    }

    public function restore(Employee $employee, ActivityLog $activityLog): bool
    {
        return false;
    }

    public function forceDelete(Employee $employee, ActivityLog $activityLog): bool
    {
        return false;
    }
}
