<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

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
        Schema::create('supervisor_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisor_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // approve_user, run_job, retry_failed_job, delete_job, etc.
            $table->string('resource_type')->nullable(); // User, Job, FailedJob, etc.
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Additional data about the action
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisor_activity_logs');
    }
};
