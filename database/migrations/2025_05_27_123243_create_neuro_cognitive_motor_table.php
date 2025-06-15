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
        Schema::create('neuro_cognitive_motor', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('athlete_id')->index('idx_neuro_athlete');
            $table->decimal('oculo_manual_reaction', 5)->nullable();
            $table->decimal('oculo_podal_reaction', 5)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('neuro_cognitive_motor');
    }
};
