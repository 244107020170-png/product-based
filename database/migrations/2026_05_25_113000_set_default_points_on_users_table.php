<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Use raw statement to avoid requiring doctrine/dbal for change().
     */
    public function up(): void
    {
        if (Schema::hasColumn('users', 'points')) {
            DB::statement("ALTER TABLE `users` MODIFY `points` INT NOT NULL DEFAULT 0");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('points')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'points')) {
            DB::statement("ALTER TABLE `users` MODIFY `points` INT NULL");
        }
    }
};
