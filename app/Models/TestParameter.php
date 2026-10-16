<?php

namespace App\Models;

use App\Enums\ResultType;
use Database\Factories\TestParameterFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['service_group_id', 'name', 'unit', 'method', 'result_type', 'threshold_min', 'threshold_max', 'threshold_text', 'is_active'])]
class TestParameter extends Model
{
    /** @use HasFactory<TestParameterFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'result_type' => ResultType::class,
            'threshold_min' => 'decimal:4',
            'threshold_max' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function serviceGroup(): BelongsTo
    {
        return $this->belongsTo(ServiceGroup::class);
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(ServicePackage::class, 'package_parameters');
    }

    public function tariffs(): HasMany
    {
        return $this->hasMany(Tariff::class);
    }

    public function testRequestParameters(): HasMany
    {
        return $this->hasMany(TestRequestParameter::class);
    }
}
