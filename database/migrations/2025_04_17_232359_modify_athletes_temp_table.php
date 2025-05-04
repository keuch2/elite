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
        // Make sure the athletes_temp table has all required columns
        if (Schema::hasTable('athletes_temp')) {
            Schema::table('athletes_temp', function (Blueprint $table) {
                // Check and add columns if they don't exist
                if (!Schema::hasColumn('athletes_temp', 'first_name')) {
                    $table->string('first_name')->nullable();
                }
                if (!Schema::hasColumn('athletes_temp', 'last_name')) {
                    $table->string('last_name')->nullable();
                }
                if (!Schema::hasColumn('athletes_temp', 'gender')) {
                    $table->string('gender')->nullable();
                }
                if (!Schema::hasColumn('athletes_temp', 'birth_date')) {
                    $table->date('birth_date')->nullable();
                }
                if (!Schema::hasColumn('athletes_temp', 'identity_document')) {
                    $table->string('identity_document')->nullable();
                }
                if (!Schema::hasColumn('athletes_temp', 'father_name')) {
                    $table->string('father_name')->nullable();
                }
                if (!Schema::hasColumn('athletes_temp', 'mother_name')) {
                    $table->string('mother_name')->nullable();
                }
                if (!Schema::hasColumn('athletes_temp', 'evaluation_date')) {
                    $table->date('evaluation_date')->nullable();
                }
                if (!Schema::hasColumn('athletes_temp', 'age')) {
                    $table->integer('age')->nullable();
                }
                if (!Schema::hasColumn('athletes_temp', 'grade')) {
                    $table->string('grade')->nullable();
                }
                if (!Schema::hasColumn('athletes_temp', 'sport')) {
                    $table->string('sport')->nullable();
                }
                if (!Schema::hasColumn('athletes_temp', 'category')) {
                    $table->string('category')->nullable();
                }
                if (!Schema::hasColumn('athletes_temp', 'institution_id')) {
                    $table->unsignedBigInteger('institution_id')->nullable();
                }
            });
        } else {
            // If the table doesn't exist, create it
            Schema::create('athletes_temp', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('gender')->nullable();
                $table->date('birth_date')->nullable();
                $table->string('identity_document')->nullable();
                $table->string('father_name')->nullable();
                $table->string('mother_name')->nullable();
                $table->date('evaluation_date')->nullable();
                $table->integer('age')->nullable();
                $table->string('grade')->nullable();
                $table->string('sport')->nullable();
                $table->string('category')->nullable();
                $table->unsignedBigInteger('institution_id')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We're not dropping the table in the down method since it's an existing table
    }
};
