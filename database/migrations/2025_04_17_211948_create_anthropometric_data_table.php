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
            $table->id();
            $table->foreignId('athlete_id');
            $table->float('standing_height')->nullable();
            $table->float('sitting_height')->nullable();
            $table->float('wingspan')->nullable();
            $table->float('weight')->nullable();
            $table->float('cormic_index')->nullable();
            $table->float('phv')->nullable();
            $table->float('skinfold_sum')->nullable();
            $table->float('fat_mass_percentage')->nullable();
            $table->float('fat_mass_kg')->nullable();
            $table->float('muscle_mass_percentage')->nullable();
            $table->float('muscle_mass_kg')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('athlete_id')->references('id')->on('athletes')->cascadeOnDelete();
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
