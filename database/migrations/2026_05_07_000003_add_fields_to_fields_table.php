<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->string('image')->nullable()->after('location');
            $table->json('facilities')->nullable()->after('price_per_hour');
            $table->float('rating', 3, 1)->default(0)->after('facilities');
            $table->integer('review_count')->default(0)->after('rating');
        });
    }

    public function down(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->dropColumn(['image', 'facilities', 'rating', 'review_count']);
        });
    }
};
