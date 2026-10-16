<?php

namespace App\Models;

use Database\Factories\LaboratoryUnitFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name'])]
class LaboratoryUnit extends Model
{
    /** @use HasFactory<LaboratoryUnitFactory> */
    use HasFactory;

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function serviceGroups(): HasMany
    {
        return $this->hasMany(ServiceGroup::class);
    }

    public function testJobs(): HasMany
    {
        return $this->hasMany(TestJob::class);
    }
}
