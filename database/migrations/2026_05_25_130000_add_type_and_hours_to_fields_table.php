<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            if (! Schema::hasColumn('fields', 'type')) {
                $table->string('type')->nullable()->after('description');
            }
            if (! Schema::hasColumn('fields', 'open_time')) {
                $table->string('open_time')->default('08:00')->after('price_per_hour');
            }
            if (! Schema::hasColumn('fields', 'close_time')) {
                $table->string('close_time')->default('22:00')->after('open_time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->dropColumn(['type', 'open_time', 'close_time']);
        });
    }
};
