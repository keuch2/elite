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
        Schema::create('athlete_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', ['m', 'f', 'other'])->nullable();
            $table->string('identity_document')->unique()->nullable();
            $table->date('birth_date')->nullable();
            $table->foreignId('institution_id')->nullable();
            $table->foreignId('tutor_id')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('institution_id')->references('id')->on('institutions')->nullOnDelete();
            $table->foreign('tutor_id')->references('id')->on('tutors')->nullOnDelete();
        });

        // Check if athletes table exists before modifying it
        if (Schema::hasTable('athletes')) {
            Schema::table('athletes', function (Blueprint $table) {
                // Add athlete_profile_id to link to the profile
                $table->foreignId('athlete_profile_id')->after('id')->nullable();
                // Add evaluation_id field to differentiate evaluations
                $table->uuid('evaluation_id')->after('athlete_profile_id')->unique();
                
                // Add foreign key constraint if athlete_profiles table exists
                if (Schema::hasTable('athlete_profiles')) {
                    $table->foreign('athlete_profile_id')->references('id')->on('athlete_profiles')->cascadeOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athlete_profiles');

        Schema::table('athletes', function (Blueprint $table) {
            $table->dropForeign(['athlete_profile_id']);
            $table->dropColumn('athlete_profile_id');
            $table->dropColumn('evaluation_id');
            $table->date('evaluation_date')->nullable()->change();
        });
    }
};
