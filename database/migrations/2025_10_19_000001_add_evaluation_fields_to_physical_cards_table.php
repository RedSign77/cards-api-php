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
        Schema::table('physical_cards', function (Blueprint $table) {
            $table->boolean('is_critical')->default(false)->after('status');
            $table->foreignId('approved_by')->nullable()->after('reviewed_by')->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->json('evaluation_flags')->nullable()->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('physical_cards', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['is_critical', 'approved_by', 'approved_at', 'evaluation_flags']);
        });
    }
};
