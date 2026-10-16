<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_job_id')->constrained('test_jobs')->cascadeOnDelete();
            $table->foreignId('test_request_parameter_id')->constrained('test_request_parameters')->cascadeOnDelete();
            $table->foreignId('analyst_id')->constrained('employees')->restrictOnDelete();
            $table->decimal('value_numeric', 18, 6)->nullable();
            $table->text('value_text')->nullable();
            $table->string('unit', 30)->nullable();
            $table->string('method')->nullable();
            $table->date('tested_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('draft'); // draft | submitted | returned | validated
            $table->boolean('is_locked')->default(false);
            $table->timestamps();

            $table->index(['test_job_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
