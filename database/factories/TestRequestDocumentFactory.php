<?php

namespace Database\Factories;

use App\Models\TestRequest;
use App\Models\TestRequestDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TestRequestDocument>
 */
class TestRequestDocumentFactory extends Factory
{
    protected $model = TestRequestDocument::class;

    public function definition(): array
    {
        return [
            'test_request_id' => TestRequest::factory(),
            'name' => 'Dokumen Pendukung '.fake()->word().'.pdf',
            'file_path' => 'documents/'.fake()->uuid().'.pdf',
        ];
    }
}
