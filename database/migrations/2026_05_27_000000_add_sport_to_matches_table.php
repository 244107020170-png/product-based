<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('matches', 'sport')) {
            return;
        }

        Schema::table('matches', function (Blueprint $table) {
            $table->string('sport', 100)->nullable()->after('title');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('matches', 'sport')) {
            return;
        }

        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('sport');
        });
    }
};
