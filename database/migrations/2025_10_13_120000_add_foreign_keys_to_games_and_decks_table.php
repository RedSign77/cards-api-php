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
        // Clean up orphaned records in games table (where creator_id doesn't exist in users)
        DB::statement('DELETE FROM games WHERE creator_id NOT IN (SELECT id FROM users)');

        // Clean up orphaned records in decks table (where creator_id doesn't exist in users)
        DB::statement('DELETE FROM decks WHERE creator_id NOT IN (SELECT id FROM users)');

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
