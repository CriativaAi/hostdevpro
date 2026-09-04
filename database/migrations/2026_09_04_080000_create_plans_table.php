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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->default('hosting'); // hosting, reseller, vps, cloud
            $table->unsignedInteger('price_cents')->default(0); // em centavos (ex: 5999 = R$ 59,99)
            $table->string('billing_cycle')->default('monthly'); // monthly, quarterly, semiannual, annual
            $table->unsignedInteger('disk_quota_mb')->default(10240);
            $table->unsignedInteger('bandwidth_quota_mb')->default(100000);
            $table->text('description')->nullable();
            $table->json('features')->nullable();
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
        Schema::dropIfExists('plans');
    }
};
