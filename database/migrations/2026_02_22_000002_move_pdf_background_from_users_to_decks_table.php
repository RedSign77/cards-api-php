<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('decks', function (Blueprint $table) {
            $table->string('pdf_background')->nullable()->after('deck_description');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('pdf_background');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pdf_background')->nullable()->after('avatar_url');
        });

        Schema::table('decks', function (Blueprint $table) {
            $table->dropColumn('pdf_background');
        });
    }
};
