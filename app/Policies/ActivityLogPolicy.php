<?php

namespace App\Policies;

use App\Models\ActivityLog;
use App\Models\Employee;

class ActivityLogPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('audit.view');
    }

    public function view(Employee $employee, ActivityLog $activityLog): bool
    {
        return $employee->can('audit.view');
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
