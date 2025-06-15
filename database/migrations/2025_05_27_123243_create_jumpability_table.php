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
        Schema::create('jumpability', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('athlete_id')->index('idx_jumpability_athlete');
            $table->enum('test_type', ['Abalakov', 'CMJ', 'CMJ_Unipodal_Right', 'CMJ_Unipodal_Left', 'Deep_Jump_30cm', 'Deep_Jump_45cm', 'Deep_Jump_60cm', 'Deep_Jump_75cm']);
            $table->decimal('height_cm', 5)->nullable();
            $table->decimal('impulse')->nullable();
            $table->decimal('max_propulsive_force')->nullable();
            $table->decimal('right_propulsive_asymmetry', 5)->nullable();
            $table->decimal('left_propulsive_asymmetry', 5)->nullable();
            $table->decimal('max_braking_force')->nullable();
            $table->decimal('right_braking_asymmetry', 5)->nullable();
            $table->decimal('left_braking_asymmetry', 5)->nullable();
            $table->decimal('max_landing_force')->nullable();
            $table->decimal('right_landing_asymmetry', 5)->nullable();
            $table->decimal('left_landing_asymmetry', 5)->nullable();
            $table->decimal('rsi', 5)->nullable();
            $table->decimal('ground_contact_time', 5)->nullable();
            $table->decimal('flight_time', 5)->nullable();
            $table->decimal('category_avg_height', 5)->nullable();
            $table->decimal('category_high_height', 5)->nullable();
            $table->decimal('category_low_height', 5)->nullable();
            $table->decimal('category_avg_impulse', 5)->nullable();
            $table->decimal('category_std_dev_height', 5)->nullable();
            $table->decimal('category_std_dev_impulse', 5)->nullable();
            $table->decimal('category_max_propulsive_force', 5)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jumpability');
    }
};
