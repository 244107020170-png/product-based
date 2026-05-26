<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'paid_at')) {
                $table->dateTime('paid_at')->nullable()->after('expired_at');
            }
            if (! Schema::hasColumn('bookings', 'confirmed_at')) {
                $table->dateTime('confirmed_at')->nullable()->after('paid_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'confirmed_at')) {
                $table->dropColumn('confirmed_at');
            }
            if (Schema::hasColumn('bookings', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
        });
    }
};
