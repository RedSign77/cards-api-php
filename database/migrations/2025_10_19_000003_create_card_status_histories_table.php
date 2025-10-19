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
        Schema::create('card_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('physical_card_id')->constrained()->onDelete('cascade');
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->string('action_type'); // auto_evaluation, supervisor_approval, supervisor_rejection, user_edit
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // evaluation_flags, rejection_reason, etc.
            $table->timestamps();

            $table->index(['physical_card_id', 'created_at']);
            $table->index('action_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_status_histories');
    }
};
