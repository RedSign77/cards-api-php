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
        Schema::table('users', function (Blueprint $table) {
            $table->string('seller_location')->nullable()->after('phone');
            $table->text('shipping_options')->nullable()->after('seller_location');
            $table->decimal('shipping_price', 10, 2)->nullable()->after('shipping_options');
            $table->string('delivery_time')->nullable()->after('shipping_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['seller_location', 'shipping_options', 'shipping_price', 'delivery_time']);
        });
    }
};
