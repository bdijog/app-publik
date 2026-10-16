<?php

namespace App\Models;

use Database\Factories\SampleTypeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['service_group_id', 'name', 'min_volume', 'container_requirement', 'requirements'])]
class SampleType extends Model
{
    /** @use HasFactory<SampleTypeFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'min_volume' => 'decimal:2',
        ];
    }

    public function serviceGroup(): BelongsTo
    {
        return $this->belongsTo(ServiceGroup::class);
    }

    public function samples(): HasMany
    {
        return $this->hasMany(Sample::class);
    }
}
