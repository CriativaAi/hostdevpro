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
        Schema::create('ai_generated_sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hosting_account_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('business_name');
            $table->string('niche');
            $table->text('description')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('style')->default('dark_frosted'); // dark_frosted, clean_minimal, corporate_blue, luxury_gold, vibrant_modern
            $table->json('sections')->nullable();
            $table->longText('generated_html');
            $table->json('prompt_history')->nullable();
            $table->string('status')->default('draft'); // draft, published
            $table->timestamp('published_at')->nullable();
            $table->string('published_path')->nullable();
            $table->unsignedInteger('revisions_count')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_generated_sites');
    }
};
