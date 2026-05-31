<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create the many-to-many pivot table
        Schema::create('discount_field', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained()->onDelete('cascade');
            $table->foreignId('field_id')->constrained()->onDelete('cascade');
            $table->unique(['discount_id', 'field_id']);
        });

        // 2. Migrate existing data
        //    — discounts with a specific field_id → create one pivot row
        DB::statement('
            INSERT INTO discount_field (discount_id, field_id)
            SELECT id, field_id FROM discounts WHERE field_id IS NOT NULL
        ');

        //    — discounts with field_id IS NULL (global "Semua Lapangan")
        //      → create pivot rows for every field that owner owns
        DB::statement('
            INSERT INTO discount_field (discount_id, field_id)
            SELECT d.id, f.id
            FROM discounts d
            INNER JOIN fields f ON f.owner_id = d.owner_id
            WHERE d.field_id IS NULL
        ');

        // 3. Drop the old single-field column and its FK
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropForeign(['field_id']);
            $table->dropColumn('field_id');
        });
    }

    public function down(): void
    {
        // Re-add the column
        Schema::table('discounts', function (Blueprint $table) {
            $table->foreignId('field_id')->nullable()->constrained()->onDelete('cascade');
        });

        // Restore field_id from the pivot (take the first linked field per discount)
        DB::statement('
            UPDATE discounts d
            SET d.field_id = (
                SELECT df.field_id FROM discount_field df
                WHERE df.discount_id = d.id
                LIMIT 1
            )
        ');

        Schema::dropIfExists('discount_field');
    }
};
