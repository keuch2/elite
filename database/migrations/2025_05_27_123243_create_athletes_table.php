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
            $table->bigIncrements('id');
            $table->date('evaluation_date');
            $table->integer('age')->nullable();
            $table->string('grade')->nullable();
            $table->string('sport')->nullable();
            $table->string('category')->nullable();
            $table->unsignedBigInteger('institution_id')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('athlete_profile_id')->nullable()->index('idx_athlete_profile_id');
            $table->string('evaluation_id', 36)->unique('unique_evaluation_id');
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
