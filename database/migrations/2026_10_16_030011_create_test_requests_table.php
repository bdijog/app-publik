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
        Schema::create('test_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number', 30)->unique();
            $table->string('fpps_number', 30)->unique();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->foreignId('service_group_id')->constrained('service_groups')->restrictOnDelete();
            $table->foreignId('service_package_id')->nullable()->constrained('service_packages')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('purpose');
            $table->string('channel', 20)->default('counter'); // online | whatsapp | counter
            $table->string('sampling_location')->nullable();
            $table->date('sampling_date')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->string('payment_status', 20)->default('unpaid');
            $table->string('status', 30)->default('draft')->index();
            $table->date('estimated_completion_date')->nullable();
            $table->timestamp('registered_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_requests');
    }
};
