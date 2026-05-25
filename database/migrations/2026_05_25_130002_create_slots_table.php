<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_id')->constrained('fields')->cascadeOnDelete();
            $table->date('date');
            $table->unsignedTinyInteger('hour');
            $table->string('status')->default('tersedia');
            $table->timestamps();

            $table->unique(['field_id', 'date', 'hour']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
