<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cron_job_logs', function (Blueprint $table) {
            $table->id();
            $table->string('job_key'); // unique identifier for the cron job
            $table->enum('status', ['success', 'failed', 'running'])->default('running');
            $table->timestamp('ran_at'); // when job was executed
            $table->timestamps();

            $table->index(['job_key', 'ran_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cron_job_logs');
    }
};