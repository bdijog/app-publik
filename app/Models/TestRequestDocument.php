<?php

namespace App\Models;

use Database\Factories\TestRequestDocumentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['test_request_id', 'name', 'file_path'])]
class TestRequestDocument extends Model
{
    /** @use HasFactory<TestRequestDocumentFactory> */
    use HasFactory;

    public function testRequest(): BelongsTo
    {
        return $this->belongsTo(TestRequest::class);
    }
}
