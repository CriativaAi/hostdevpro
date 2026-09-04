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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('referral_code', 32)->unique();
            $table->decimal('commission_percentage', 5, 2)->default(15.00);
            $table->unsignedInteger('cookie_duration_days')->default(90);
            $table->unsignedBigInteger('balance_cents')->default(0);
            $table->unsignedBigInteger('total_earned_cents')->default(0);
            $table->unsignedBigInteger('total_withdrawn_cents')->default(0);
            $table->unsignedInteger('visitors_count')->default(0);
            $table->unsignedInteger('conversions_count')->default(0);
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->string('pix_key')->nullable();
            $table->string('pix_key_type', 20)->nullable(); // cpf, cnpj, email, phone, random
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
