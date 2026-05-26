<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('match_players', function (Blueprint $table) {
            if (! Schema::hasColumn('match_players', 'contribution_amount')) {
                $table->unsignedBigInteger('contribution_amount')->default(0)->after('user_id');
            }
            if (! Schema::hasColumn('match_players', 'payment_status')) {
                $table->string('payment_status')->default('waiting')->after('contribution_amount');
            }
            if (! Schema::hasColumn('match_players', 'paid_at')) {
                $table->dateTime('paid_at')->nullable()->after('payment_status');
            }
            if (! Schema::hasColumn('match_players', 'confirmed_at')) {
                $table->dateTime('confirmed_at')->nullable()->after('paid_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('match_players', function (Blueprint $table) {
            if (Schema::hasColumn('match_players', 'confirmed_at')) {
                $table->dropColumn('confirmed_at');
            }
            if (Schema::hasColumn('match_players', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
            if (Schema::hasColumn('match_players', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('match_players', 'contribution_amount')) {
                $table->dropColumn('contribution_amount');
            }
        });
    }
};
