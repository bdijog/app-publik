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
        Schema::create('sample_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_group_id')->constrained('service_groups')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('min_volume', 10, 2)->nullable();
            $table->string('container_requirement')->nullable();
            $table->text('requirements')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_types');
    }
};
