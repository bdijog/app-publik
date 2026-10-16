<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
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
class Employee extends Authenticatable
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected string $guard_name = 'employee';

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
