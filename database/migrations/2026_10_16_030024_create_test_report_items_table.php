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
        Schema::create('test_report_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_report_id')->constrained('test_reports')->cascadeOnDelete();
            $table->foreignId('test_result_id')->constrained('test_results')->cascadeOnDelete();
            $table->string('sample_name');
            $table->string('sample_number');
            $table->string('parameter');
            $table->string('unit')->nullable();
            $table->string('result');
            $table->string('method')->nullable();
            $table->string('threshold')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_report_items');
    }
};
