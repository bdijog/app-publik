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
        Schema::create('report_handovers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_report_id')->constrained('test_reports')->cascadeOnDelete();
            $table->foreignId('handed_by')->constrained('employees')->restrictOnDelete();
            $table->string('received_by_name');
            $table->timestamp('handed_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_handovers');
    }
};
