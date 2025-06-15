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
        Schema::create('strength', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('athlete_id')->index('idx_strength_athlete');
            $table->decimal('grip_strength', 5)->nullable();
            $table->decimal('grip_strength_category_high', 5)->nullable();
            $table->decimal('grip_strength_category_avg', 5)->nullable();
            $table->decimal('grip_strength_category_low', 5)->nullable();
            $table->integer('push_ups')->nullable();
            $table->integer('push_ups_category_avg')->nullable();
            $table->integer('push_ups_category_high')->nullable();
            $table->integer('push_ups_category_low')->nullable();
            $table->integer('pull_ups')->nullable();
            $table->integer('pull_ups_category_avg')->nullable();
            $table->integer('pull_ups_category_high')->nullable();
            $table->integer('pull_ups_category_low')->nullable();
            $table->integer('inverted_row')->nullable();
            $table->integer('inverted_row_category_avg')->nullable();
            $table->integer('inverted_row_category_high')->nullable();
            $table->integer('inverted_row_category_low')->nullable();
            $table->decimal('medicine_ball_throw_distance', 5)->nullable();
            $table->decimal('medicine_ball_throw_category_avg', 5)->nullable();
            $table->decimal('medicine_ball_throw_category_high', 5)->nullable();
            $table->decimal('medicine_ball_throw_category_low', 5)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strength');
    }
};
