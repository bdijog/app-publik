<?php

namespace App\Models;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Database\Factories\EmployeeFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['laboratory_unit_id', 'name', 'email', 'phone', 'nip', 'password', 'is_active', 'last_login_at'])]
#[Hidden(['password', 'remember_token'])]
class Employee extends Authenticatable implements FilamentUser, HasName
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected string $guard_name = 'employee';

    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_active;
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(RoleEnum::Admin);
    }

    public function isServiceOfficer(): bool
    {
        return $this->hasRole(RoleEnum::ServiceOfficer);
    }

    public function isLabOfficer(): bool
    {
        return $this->hasRole(RoleEnum::LabOfficer);
    }

    public function isLeader(): bool
    {
        return $this->hasRole(RoleEnum::Leader);
    }

    public function isSampleReceiver(): bool
    {
        return $this->hasRole(RoleEnum::SampleReceiver) || $this->hasRole(RoleEnum::ServiceOfficer);
    }

    public function canPerform(PermissionEnum $permission): bool
    {
        return $this->can($permission->value);
    }

    /**
     * @return array<RoleEnum>
     */
    public function getRoleEnums(): array
    {
        return $this->roles
            ->map(fn ($role) => RoleEnum::tryFrom($role->name))
            ->filter()
            ->values()
            ->all();
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function laboratoryUnit(): BelongsTo
    {
        return $this->belongsTo(LaboratoryUnit::class);
    }

    public function createdTestRequests(): HasMany
    {
        return $this->hasMany(TestRequest::class, 'created_by');
    }

    public function recordedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'recorded_by');
    }

    public function receivedSampleReceipts(): HasMany
    {
        return $this->hasMany(SampleReceipt::class, 'received_by');
    }

    public function assignedTestJobs(): HasMany
    {
        return $this->hasMany(TestJob::class, 'assigned_to');
    }

    public function analyzedTestResults(): HasMany
    {
        return $this->hasMany(TestResult::class, 'analyst_id');
    }

    public function validations(): HasMany
    {
        return $this->hasMany(ResultValidation::class, 'validator_id');
    }

    public function verifiedReports(): HasMany
    {
        return $this->hasMany(TestReport::class, 'verifier_id');
    }

    public function validatedReports(): HasMany
    {
        return $this->hasMany(TestReport::class, 'validator_id');
    }

    public function issuedReports(): HasMany
    {
        return $this->hasMany(TestReport::class, 'issued_by');
    }

    public function handedReports(): HasMany
    {
        return $this->hasMany(ReportHandover::class, 'handed_by');
    }

    public function documentAccessLogs(): HasMany
    {
        return $this->hasMany(DocumentAccessLog::class);
    }
}
