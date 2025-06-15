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
        Schema::create('athletes_temp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('identity_document')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('evaluation_date')->nullable();
            $table->integer('age')->nullable();
            $table->string('grade')->nullable();
            $table->string('sport')->nullable();
            $table->string('category')->nullable();
            $table->unsignedBigInteger('institution_id')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->unsignedBigInteger('tutor_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athletes_temp');
    }
};
