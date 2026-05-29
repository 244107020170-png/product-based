<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->after('id');
            $table->foreignId('field_id')->constrained()->cascadeOnDelete()->after('user_id');
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete()->after('field_id');
            $table->tinyInteger('rating')->unsigned()->after('booking_id');
            $table->text('review')->nullable()->after('rating');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['field_id']);
            $table->dropForeign(['booking_id']);
            $table->dropColumn(['user_id', 'field_id', 'booking_id', 'rating', 'review']);
        });
    }
};
