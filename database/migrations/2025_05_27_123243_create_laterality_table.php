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
        Schema::create('laterality', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('athlete_id')->index('idx_laterality_athlete');
            $table->string('eye', 50)->nullable();
            $table->string('shoulder', 50)->nullable();
            $table->string('hand', 50)->nullable();
            $table->string('hip', 50)->nullable();
            $table->string('foot', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laterality');
    }
};
