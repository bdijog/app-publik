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
        Schema::create('test_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_request_id')->constrained('test_requests')->cascadeOnDelete();
            $table->foreignId('report_template_id')->constrained('report_templates')->restrictOnDelete();
            $table->foreignId('parent_report_id')->nullable()->constrained('test_reports')->nullOnDelete();
            $table->foreignId('verifier_id')->constrained('employees')->restrictOnDelete();
            $table->foreignId('validator_id')->constrained('employees')->restrictOnDelete();
            $table->foreignId('issued_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('report_number', 40)->unique();
            $table->unsignedSmallInteger('version')->default(1);
            $table->string('status', 10)->default('draft'); // draft | final | revised
            $table->text('considerations')->nullable();
            $table->string('file_path')->nullable();
            $table->date('validated_on')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();

            $table->index(['test_request_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_reports');
    }
};
