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
        Schema::create('hosting_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('server_id')->constrained('servers')->cascadeOnDelete();
            $table->string('domain')->unique();
            $table->string('username')->nullable();
            $table->string('plan')->default('pro'); // basic, pro, enterprise
            $table->string('php_version')->default('8.5'); // 8.2, 8.3, 8.4, 8.5
            $table->unsignedInteger('disk_quota_mb')->default(5120);
            $table->unsignedInteger('disk_used_mb')->default(0);
            $table->unsignedInteger('bandwidth_quota_mb')->default(50000);
            $table->string('ssl_status')->default('active'); // active, pending, expired, none
            $table->string('status')->default('active'); // active, suspended, pending, terminated
            $table->string('suspended_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hosting_accounts');
    }
};
