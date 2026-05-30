<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE reviews MODIFY COLUMN rating DECIMAL(2,1) NOT NULL DEFAULT 0');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE reviews MODIFY COLUMN rating TINYINT UNSIGNED NOT NULL DEFAULT 0');
    }
};
