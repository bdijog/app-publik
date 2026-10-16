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
        Schema::create('test_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_group_id')->constrained('service_groups')->cascadeOnDelete();
            $table->string('name');
            $table->string('unit', 30)->nullable();
            $table->string('method')->nullable();
            $table->string('result_type', 10)->default('numeric'); // numeric | text
            $table->decimal('threshold_min', 12, 4)->nullable();
            $table->decimal('threshold_max', 12, 4)->nullable();
            $table->string('threshold_text')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_parameters');
    }
};
