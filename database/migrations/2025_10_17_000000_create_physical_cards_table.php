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
        Schema::create('physical_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('set')->nullable();
            $table->string('language')->default('English');
            $table->string('edition')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('condition')->default('Near Mint');
            $table->decimal('price_per_unit', 10, 2)->nullable();
            $table->string('currency')->default('USD');
            $table->boolean('tradeable')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physical_cards');
    }
};
