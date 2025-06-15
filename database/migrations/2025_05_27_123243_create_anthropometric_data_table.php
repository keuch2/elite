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
        Schema::create('anthropometric_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('athlete_id')->index('athlete_id');
            $table->decimal('standing_height', 5)->nullable();
            $table->decimal('sitting_height', 5)->nullable();
            $table->decimal('wingspan', 5)->nullable();
            $table->decimal('weight', 5)->nullable();
            $table->decimal('cormic_index', 5)->nullable();
            $table->decimal('phv', 5)->nullable();
            $table->decimal('skinfold_sum', 5)->nullable();
            $table->decimal('fat_mass_percentage', 5)->nullable();
            $table->decimal('fat_mass_kg', 5)->nullable();
            $table->decimal('muscle_mass_percentage', 5)->nullable();
            $table->decimal('muscle_mass_kg', 5)->nullable();
            $table->decimal('residual_mass_percentage', 5)->nullable();
            $table->decimal('residual_mass_kg', 5)->nullable();
            $table->decimal('bone_mass_percentage', 5)->nullable();
            $table->decimal('bone_mass_kg', 5)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anthropometric_data');
    }
};
