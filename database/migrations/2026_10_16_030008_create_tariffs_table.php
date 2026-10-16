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
        Schema::create('tariffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_group_id')->nullable()->constrained('service_groups')->cascadeOnDelete();
            $table->foreignId('service_package_id')->nullable()->constrained('service_packages')->cascadeOnDelete();
            $table->foreignId('test_parameter_id')->nullable()->constrained('test_parameters')->cascadeOnDelete();
            $table->decimal('amount', 14, 2);
            $table->date('effective_from');
            $table->date('effective_until')->nullable();
            $table->timestamps();

            $table->index(['service_group_id', 'effective_from']);
            $table->index(['service_package_id', 'effective_from']);
            $table->index(['test_parameter_id', 'effective_from']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tariffs');
    }
};
