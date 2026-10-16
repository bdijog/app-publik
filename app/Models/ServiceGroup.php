<?php

namespace App\Models;

use Database\Factories\ServiceGroupFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['laboratory_unit_id', 'code', 'name', 'description', 'is_active'])]
class ServiceGroup extends Model
{
    /** @use HasFactory<ServiceGroupFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function laboratoryUnit(): BelongsTo
    {
        return $this->belongsTo(LaboratoryUnit::class);
    }

    public function servicePackages(): HasMany
    {
        return $this->hasMany(ServicePackage::class);
    }

    public function testParameters(): HasMany
    {
        return $this->hasMany(TestParameter::class);
    }

    public function sampleTypes(): HasMany
    {
        return $this->hasMany(SampleType::class);
    }

    public function tariffs(): HasMany
    {
        return $this->hasMany(Tariff::class);
    }

    public function completionEstimates(): HasMany
    {
        return $this->hasMany(CompletionEstimate::class);
    }

    public function testRequests(): HasMany
    {
        return $this->hasMany(TestRequest::class);
    }
}
