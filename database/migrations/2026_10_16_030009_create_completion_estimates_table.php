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
        Schema::create('completion_estimates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_group_id')->nullable()->constrained('service_groups')->cascadeOnDelete();
            $table->unsignedSmallInteger('days');
            $table->string('start_point', 20)->default('payment'); // payment | receipt
            $table->date('effective_from');
            $table->timestamps();

            $table->index(['service_group_id', 'effective_from']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('completion_estimates');
    }
};
