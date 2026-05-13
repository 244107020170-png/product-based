<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom match_id & user_id ke match_players yang sudah ada
        Schema::table('match_players', function (Blueprint $table) {
            $table->foreignId('match_id')
                  ->after('id')
                  ->constrained('matches')
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                  ->after('match_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->unique(['match_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('match_players', function (Blueprint $table) {
            $table->dropUnique(['match_id', 'user_id']);
            $table->dropForeign(['match_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['match_id', 'user_id']);
        });
    }
};
