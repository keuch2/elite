<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('velocity', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('athlete_id')->index('idx_velocity_athlete');
            $table->integer('distance_m');
            $table->decimal('time_seconds', 5)->nullable();
            $table->decimal('speed_kmh', 5)->nullable();
            $table->decimal('speed_ms', 5)->nullable();
            $table->decimal('category_avg_time', 5)->nullable();
            $table->decimal('category_high_time', 5)->nullable();
            $table->decimal('category_low_time', 5)->nullable();
            $table->decimal('category_avg_kmh', 5)->nullable();
            $table->decimal('category_high_kmh', 5)->nullable();
            $table->decimal('category_low_kmh', 5)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('velocity');
    }
};
