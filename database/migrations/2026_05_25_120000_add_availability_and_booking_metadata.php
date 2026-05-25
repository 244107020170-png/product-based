<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            if (! Schema::hasColumn('fields', 'is_available')) {
                $table->boolean('is_available')->default(true)->after('price_per_hour');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'payment_deadline')) {
                $table->dateTime('payment_deadline')->nullable()->after('status');
            }
            if (! Schema::hasColumn('bookings', 'expired_at')) {
                $table->dateTime('expired_at')->nullable()->after('payment_deadline');
            }
            $table->index(['field_id', 'date', 'start_time'], 'bookings_field_date_start_idx');
            $table->index(['field_id', 'status'], 'bookings_field_status_idx');
            $table->index('date', 'bookings_date_idx');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_field_date_start_idx');
            $table->dropIndex('bookings_field_status_idx');
            $table->dropIndex('bookings_date_idx');
            $table->dropColumn(['payment_deadline', 'expired_at']);
        });

        Schema::table('fields', function (Blueprint $table) {
            $table->dropColumn('is_available');
        });
    }
};
