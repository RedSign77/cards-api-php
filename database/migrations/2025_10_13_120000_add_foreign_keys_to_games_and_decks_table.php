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
        // Add foreign key constraint to games table
        Schema::table('games', function (Blueprint $table) {
            // Change creator_id to bigInteger unsigned to match users.id type
            $table->unsignedBigInteger('creator_id')->change();

            // Add foreign key with cascade delete
            $table->foreign('creator_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        // Add foreign key constraint to decks table
        Schema::table('decks', function (Blueprint $table) {
            // Change creator_id to bigInteger unsigned to match users.id type
            $table->unsignedBigInteger('creator_id')->change();

            // Add foreign key with cascade delete
            $table->foreign('creator_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
        });

        Schema::table('decks', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
        });
    }
};
