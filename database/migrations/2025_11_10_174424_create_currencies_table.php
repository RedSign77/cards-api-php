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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique(); // e.g., USD, EUR, GBP
            $table->string('name'); // e.g., US Dollar, Euro
            $table->string('symbol', 10); // e.g., $, €, £
            $table->decimal('exchange_rate', 12, 6)->default(1.000000); // Rate relative to base currency (USD)
            $table->boolean('is_active')->default(true);
            $table->boolean('is_base')->default(false); // Only one currency should be base
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('code');
            $table->index('is_active');
            $table->index('is_base');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
