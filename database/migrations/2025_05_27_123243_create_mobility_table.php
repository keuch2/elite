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
        Schema::create('mobility', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('athlete_id')->index('idx_mobility_athlete');
            $table->decimal('sit_and_reach', 5)->nullable();
            $table->decimal('right_ankle_mobility', 5)->nullable();
            $table->decimal('left_ankle_mobility', 5)->nullable();
            $table->decimal('right_anterior_motor_control', 5)->nullable();
            $table->decimal('left_anterior_motor_control', 5)->nullable();
            $table->decimal('right_posterolateral_motor_control', 5)->nullable();
            $table->decimal('left_posterolateral_motor_control', 5)->nullable();
            $table->decimal('right_posteromedial_motor_control', 5)->nullable();
            $table->decimal('left_posteromedial_motor_control', 5)->nullable();
            $table->decimal('right_shoulder_mobility', 5)->nullable();
            $table->decimal('left_shoulder_mobility', 5)->nullable();
            $table->decimal('right_thoracic_mobility', 5)->nullable();
            $table->decimal('left_thoracic_mobility', 5)->nullable();
            $table->decimal('snatch_squat', 5)->nullable();
            $table->decimal('right_hurdle_step', 5)->nullable();
            $table->decimal('left_hurdle_step', 5)->nullable();
            $table->decimal('right_inline_lunge', 5)->nullable();
            $table->decimal('left_inline_lunge', 5)->nullable();
            $table->decimal('right_straight_leg_raise', 5)->nullable();
            $table->decimal('left_straight_leg_raise', 5)->nullable();
            $table->decimal('trunk_stability_extension', 5)->nullable();
            $table->decimal('right_rotation_stability', 5)->nullable();
            $table->decimal('left_rotation_stability', 5)->nullable();
            $table->decimal('right_internal_hip_rotation', 5)->nullable();
            $table->decimal('left_internal_hip_rotation', 5)->nullable();
            $table->decimal('right_external_hip_rotation', 5)->nullable();
            $table->decimal('left_external_hip_rotation', 5)->nullable();
            $table->decimal('sit_and_reach_category_avg', 5)->nullable();
            $table->decimal('sit_and_reach_category_high', 5)->nullable();
            $table->decimal('sit_and_reach_category_low', 5)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobility');
    }
};
