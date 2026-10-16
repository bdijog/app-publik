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
        Schema::create('sample_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_id')->constrained('samples')->cascadeOnDelete();
            $table->foreignId('received_by')->constrained('employees')->restrictOnDelete();
            $table->timestamp('received_at');
            $table->decimal('received_volume', 10, 2)->nullable();
            $table->string('condition');
            $table->string('decision', 10); // accepted | rejected
            $table->text('rejection_reason')->nullable();
            $table->text('follow_up')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_receipts');
    }
};
