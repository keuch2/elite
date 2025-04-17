<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, clean up any temporary tables that might exist from failed migrations
        Schema::dropIfExists('athletes_temp');
        
        // 1. Create institutions table if it doesn't exist
        if (!Schema::hasTable('institutions')) {
            Schema::create('institutions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('address')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->timestamps();
            });
        }

        // 2. Create tutors table if it doesn't exist
        if (!Schema::hasTable('tutors')) {
            Schema::create('tutors', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('relationship')->nullable(); // parent, guardian, etc.
                $table->timestamps();
            });
        }

        // 3. Create athlete_profiles table if it doesn't exist
        // Temporarily disable foreign key checks for this operation
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        if (!Schema::hasTable('athlete_profiles')) {
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
        }

        // 4. Restructure athletes table if it exists, or create it
        if (Schema::hasTable('athletes')) {
            // First check if related tables reference athletes table
            $hasRelatedTables = Schema::hasTable('anthropometric_data');
            
            // Store anthropometric data temporarily if it exists
            $anthropometricData = null;
            if ($hasRelatedTables) {
                $anthropometricData = DB::table('anthropometric_data')->get();
                // Drop the related tables
                Schema::dropIfExists('anthropometric_data');
            }
            
            // Step 1: Create temporary table to store existing athlete data
            Schema::create('athletes_temp', function (Blueprint $table) {
                $table->id();
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
                $table->foreignId('institution_id')->nullable();
                $table->string('father_name')->nullable();
                $table->string('mother_name')->nullable();
                $table->foreignId('tutor_id')->nullable();
                $table->timestamps();
            });

            // Step 2: Copy all existing athlete data to temp table
            DB::statement('INSERT INTO athletes_temp SELECT * FROM athletes');

            // Step 3: Drop the existing athletes table
            Schema::dropIfExists('athletes');

            // Step 4: Create the new athletes table as evaluations
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

            // Step 5: Create anthropometric_data table
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

            // Step 6: Migrate data from temp to new structure
            $athletes = DB::table('athletes_temp')->get();
            $athleteIdMapping = []; // To map old athlete IDs to new ones
            
            foreach ($athletes as $athlete) {
                // Create athlete profile first
                $profileId = DB::table('athlete_profiles')->insertGetId([
                    'first_name' => $athlete->first_name,
                    'last_name' => $athlete->last_name,
                    'gender' => $athlete->gender,
                    'identity_document' => $athlete->identity_document,
                    'birth_date' => $athlete->birth_date,
                    'institution_id' => $athlete->institution_id,
                    'tutor_id' => $athlete->tutor_id,
                    'father_name' => $athlete->father_name,
                    'mother_name' => $athlete->mother_name,
                    'created_at' => $athlete->created_at,
                    'updated_at' => $athlete->updated_at,
                ]);

                // Create athlete evaluation record
                $newAthleteId = DB::table('athletes')->insertGetId([
                    'athlete_profile_id' => $profileId,
                    'evaluation_id' => (string) Str::uuid(),
                    'evaluation_date' => $athlete->evaluation_date ?: now(),
                    'age' => $athlete->age,
                    'grade' => $athlete->grade,
                    'sport' => $athlete->sport,
                    'category' => $athlete->category,
                    'institution_id' => $athlete->institution_id,
                    'created_at' => $athlete->created_at,
                    'updated_at' => $athlete->updated_at,
                ]);
                
                // Store mapping of old ID to new ID
                $athleteIdMapping[$athlete->id] = $newAthleteId;
            }
            
            // Step 7: Restore anthropometric data if it existed
            if ($anthropometricData) {
                foreach ($anthropometricData as $data) {
                    if (isset($athleteIdMapping[$data->athlete_id])) {
                        $newAthleteId = $athleteIdMapping[$data->athlete_id];
                        
                        // Remove the id field to let the database auto-increment
                        $dataArray = (array) $data;
                        unset($dataArray['id']);
                        
                        // Update the athlete_id to the new ID
                        $dataArray['athlete_id'] = $newAthleteId;
                        
                        DB::table('anthropometric_data')->insert($dataArray);
                    }
                }
            }
            
            // Step 8: Clean up
            Schema::dropIfExists('athletes_temp');
        } else {
            // Just create the athletes table as there's no existing data
            Schema::create('athletes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('athlete_profile_id');
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
            
            // Create anthropometric_data table
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
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not implementing reverse migration as it would be complex and potentially destructive
        // You would need to restore the original schema and merge evaluation data
    }
};
