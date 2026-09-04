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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hostname')->nullable();
            $table->string('ip_address')->unique();
            $table->string('provider')->nullable(); // Integrator Host, Hetzner, AWS, etc.
            $table->string('datacenter_location')->nullable(); // São Paulo - BR, etc.
            $table->string('os')->nullable(); // Ubuntu 24.04 LTS, etc.
            $table->unsignedInteger('cpu_cores')->default(2);
            $table->unsignedInteger('ram_mb')->default(4096);
            $table->unsignedInteger('disk_gb')->default(80);
            $table->unsignedInteger('ssh_port')->default(22);
            $table->string('status')->default('online'); // online, offline, maintenance
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
        Schema::dropIfExists('servers');
    }
};
