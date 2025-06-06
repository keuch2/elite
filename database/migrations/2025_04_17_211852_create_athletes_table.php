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
        if (!Schema::hasTable('athletes')) {
            Schema::create('athletes', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->enum('gender', ['m', 'f', 'other'])->nullable();
                $table->string('identity_document')->nullable();
                $table->date('birth_date')->nullable();
                $table->string('father_name')->nullable();
                $table->string('mother_name')->nullable();
                $table->date('evaluation_date');
                $table->integer('age')->nullable();
                $table->string('grade')->nullable();
                $table->string('sport')->nullable();
                $table->string('category')->nullable();
                $table->unsignedBigInteger('institution_id')->nullable();
                $table->timestamps();
                
                // No foreign keys for now
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athletes');
    }
};
