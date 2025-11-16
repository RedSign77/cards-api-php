<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify status enum to include new statuses
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'packing', 'paid', 'shipped', 'delivered', 'cancelled', 'completed') NOT NULL DEFAULT 'pending'");

        Schema::table('orders', function (Blueprint $table) {
            // Add confirmation timestamps
            $table->timestamp('seller_confirmed_at')->nullable()->after('status');
            $table->timestamp('buyer_confirmed_at')->nullable()->after('seller_confirmed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['seller_confirmed_at', 'buyer_confirmed_at']);
        });

        // Revert status enum to original values
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};
