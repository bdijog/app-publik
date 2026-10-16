<?php

namespace App\Models;

use App\Enums\EstimateStartPoint;
use Database\Factories\CompletionEstimateFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['service_group_id', 'days', 'start_point', 'effective_from'])]
class CompletionEstimate extends Model
{
    /** @use HasFactory<CompletionEstimateFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'days' => 'integer',
            'start_point' => EstimateStartPoint::class,
            'effective_from' => 'date',
        ];
    }

    public function serviceGroup(): BelongsTo
    {
        return $this->belongsTo(ServiceGroup::class);
    }
}
