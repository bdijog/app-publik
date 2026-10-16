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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('organization_name')->nullable();
            $table->string('customer_type', 30)->default('individual');
            $table->string('email')->nullable()->unique();
            $table->text('phone'); // Encrypted
            $table->string('phone_hash', 64)->index(); // SHA256 / HMAC for download verification
            $table->text('address')->nullable();
            $table->string('password')->nullable(); // Nullable for counter/WhatsApp pendaftar
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
