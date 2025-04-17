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
        Schema::create('athletes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_profile_id')->nullable();
            $table->uuid('evaluation_id')->unique();
            $table->date('evaluation_date');
            $table->integer('age')->nullable();
            $table->string('grade')->nullable();
            $table->string('sport')->nullable();
            $table->string('category')->nullable();
            $table->foreignId('institution_id')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('athlete_profile_id')->references('id')->on('athlete_profiles')->cascadeOnDelete();
            $table->foreign('institution_id')->references('id')->on('institutions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athletes');
    }
};
