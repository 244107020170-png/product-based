<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('fields', 'number_of_courts')) {
            Schema::table('fields', function (Blueprint $table) {
                $table->unsignedTinyInteger('number_of_courts')->default(1)->after('close_time');
            });
        }

        if (! Schema::hasColumn('slots', 'court_number')) {
            Schema::table('slots', function (Blueprint $table) {
                $table->unsignedTinyInteger('court_number')->default(1)->after('field_id');
            });

            // Drop the old unique key that didn't include court_number
            Schema::table('slots', function (Blueprint $table) {
                $table->dropUnique(['field_id', 'date', 'hour']);
            });

            // Add new unique key that includes court_number
            Schema::table('slots', function (Blueprint $table) {
                $table->unique(['field_id', 'court_number', 'date', 'hour'], 'slots_field_court_date_hour_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::table('slots', function (Blueprint $table) {
            $table->dropUnique('slots_field_court_date_hour_unique');
            $table->dropColumn('court_number');
        });

        Schema::table('fields', function (Blueprint $table) {
            $table->dropColumn('number_of_courts');
        });
    }
};
