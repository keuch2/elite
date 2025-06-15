<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Institution;
use App\Models\AnthropometricData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AthleteController extends Controller
{
    /**
     * Display a listing of athletes grouped by profile.
     */
    public function index(Request $request)
    {
        $query = DB::table('athletes')
            ->select(
                'athletes.id',
                'athlete_profiles.first_name',
                'athlete_profiles.last_name',
                'athlete_profiles.gender',
                'athlete_profiles.birth_date',
                'athlete_profiles.identity_document',
                'institutions.name as institution_name',
                DB::raw('COUNT(*) as evaluations_count'),
                DB::raw('MAX(athletes.evaluation_date) as latest_evaluation')
            )
            ->join('athlete_profiles', 'athletes.athlete_profile_id', '=', 'athlete_profiles.id')
            ->leftJoin('institutions', 'athletes.institution_id', '=', 'institutions.id')
            ->groupBy(
                'athlete_profiles.first_name',
                'athlete_profiles.last_name',
                'athlete_profiles.birth_date',
                'athlete_profiles.gender',
                'athlete_profiles.identity_document',
                'athletes.id',
                'institutions.name'
            );

        // Filter by institution if specified
        if ($request->has('institution_id')) {
            $query->where('athletes.institution_id', $request->institution_id);
        }

        $athleteProfiles = $query->paginate(15);

        return view('athletes.index', [
            'athletes' => $athleteProfiles,
            'institutions' => Institution::all()
        ]);
    }

    /**
     * Show the form for creating a new athlete.
     */
    public function create()
    {
        return view('athletes.create', [
            'institutions' => Institution::all()
        ]);
    }

    /**
     * Store a newly created athlete in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|in:m,f,other',
            'birth_date' => 'required|date',
            'identity_document' => 'nullable|string|max:255|unique:athlete_profiles',
            'institution_id' => 'nullable|exists:institutions,id',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'evaluation_date' => 'required|date',
            'age' => 'required|numeric',
            'grade' => 'nullable|string|max:255',
            'sport' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        // Begin transaction to ensure data consistency
        DB::beginTransaction();
        
        try {
            // Create athlete profile first
            $profileData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'identity_document' => $request->identity_document,
                'institution_id' => $request->institution_id,
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
            ];
            
            $profile = DB::table('athlete_profiles')->insertGetId($profileData);
            
            // Create athlete evaluation record
            $athleteData = [
                'athlete_profile_id' => $profile,
                'evaluation_date' => $request->evaluation_date,
                'age' => $request->age,
                'grade' => $request->grade,
                'sport' => $request->sport,
                'category' => $request->category,
                'institution_id' => $request->institution_id,
                'evaluation_id' => \Illuminate\Support\Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $athleteId = DB::table('athletes')->insertGetId($athleteData);
            
            // Optionally create anthropometric data if provided
            if ($request->has('anthropometric_data')) {
                $anthropometricData = $request->input('anthropometric_data');
                $anthropometricData['athlete_id'] = $athleteId;
                AnthropometricData::create($anthropometricData);
            }
            
            DB::commit();
            
            return redirect()->route('athletes.show', $athleteId)
                ->with('success', 'Athlete created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error creating athlete: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified athlete profile with all evaluations.
     */
    public function show(string $id)
    {
        // Get the athlete record with its profile
        $athlete = DB::table('athletes')
            ->join('athlete_profiles', 'athletes.athlete_profile_id', '=', 'athlete_profiles.id')
            ->leftJoin('institutions', 'athletes.institution_id', '=', 'institutions.id')
            ->select(
                'athletes.*',
                'athlete_profiles.first_name',
                'athlete_profiles.last_name',
                'athlete_profiles.gender',
                'athlete_profiles.birth_date',
                'athlete_profiles.identity_document',
                'athlete_profiles.father_name',
                'athlete_profiles.mother_name',
                'institutions.name as institution_name'
            )
            ->where('athletes.id', $id)
            ->first();
            
        if (!$athlete) {
            abort(404, 'Athlete not found');
        }
        
        // Find all evaluations for the same athlete profile
        $evaluations = DB::table('athletes')
            ->join('athlete_profiles', 'athletes.athlete_profile_id', '=', 'athlete_profiles.id')
            ->leftJoin('anthropometric_data', 'athletes.id', '=', 'anthropometric_data.athlete_id')
            ->select(
                'athletes.*',
                'athlete_profiles.first_name',
                'athlete_profiles.last_name',
                'athlete_profiles.gender',
                'athlete_profiles.birth_date',
                'athlete_profiles.identity_document',
                'anthropometric_data.*'
            )
            ->where('athletes.athlete_profile_id', $athlete->athlete_profile_id)
            ->orderBy('athletes.evaluation_date', 'desc')
            ->get();

        return view('athletes.show', [
            'athlete' => $athlete,
            'evaluations' => $evaluations,
        ]);
    }

    /**
     * Show a specific evaluation for an athlete.
     */
    public function showEvaluation(string $id)
    {
        $evaluation = DB::table('athletes')
            ->join('athlete_profiles', 'athletes.athlete_profile_id', '=', 'athlete_profiles.id')
            ->leftJoin('institutions', 'athletes.institution_id', '=', 'institutions.id')
            ->leftJoin('anthropometric_data', 'athletes.id', '=', 'anthropometric_data.athlete_id')
            ->select(
                'athletes.*',
                'athlete_profiles.first_name',
                'athlete_profiles.last_name',
                'athlete_profiles.gender',
                'athlete_profiles.birth_date',
                'athlete_profiles.identity_document',
                'athlete_profiles.father_name',
                'athlete_profiles.mother_name',
                'institutions.name as institution_name',
                'anthropometric_data.*'
            )
            ->where('athletes.id', $id)
            ->first();
            
        if (!$evaluation) {
            abort(404, 'Evaluation not found');
        }
        
        return view('athletes.evaluation', [
            'evaluation' => $evaluation,
        ]);
    }

    /**
     * Show the form for editing the specified athlete.
     */
    public function edit(string $id)
    {
        $athlete = DB::table('athletes')
            ->join('athlete_profiles', 'athletes.athlete_profile_id', '=', 'athlete_profiles.id')
            ->select(
                'athletes.*',
                'athlete_profiles.id as profile_id',
                'athlete_profiles.first_name',
                'athlete_profiles.last_name',
                'athlete_profiles.gender',
                'athlete_profiles.birth_date',
                'athlete_profiles.identity_document',
                'athlete_profiles.father_name',
                'athlete_profiles.mother_name'
            )
            ->where('athletes.id', $id)
            ->first();
            
        if (!$athlete) {
            abort(404, 'Athlete not found');
        }
        
        return view('athletes.edit', [
            'athlete' => $athlete,
            'institutions' => Institution::all(),
        ]);
    }

    /**
     * Update the specified athlete in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|in:m,f,other',
            'birth_date' => 'required|date',
            'identity_document' => 'nullable|string|max:255',
            'institution_id' => 'nullable|exists:institutions,id',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'evaluation_date' => 'required|date',
            'age' => 'required|numeric',
            'grade' => 'nullable|string|max:255',
            'sport' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        // Begin transaction to ensure data consistency
        DB::beginTransaction();
        
        try {
            // Get the athlete record
            $athlete = DB::table('athletes')->where('id', $id)->first();
            
            if (!$athlete) {
                abort(404, 'Athlete not found');
            }
            
            // Update athlete profile
            $profileData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'identity_document' => $request->identity_document,
                'institution_id' => $request->institution_id,
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                'updated_at' => now(),
            ];
            
            DB::table('athlete_profiles')
                ->where('id', $athlete->athlete_profile_id)
                ->update($profileData);
            
            // Update athlete evaluation record
            $athleteData = [
                'evaluation_date' => $request->evaluation_date,
                'age' => $request->age,
                'grade' => $request->grade,
                'sport' => $request->sport,
                'category' => $request->category,
                'institution_id' => $request->institution_id,
                'updated_at' => now(),
            ];
            
            DB::table('athletes')
                ->where('id', $id)
                ->update($athleteData);
            
            // Update anthropometric data if provided
            if ($request->has('anthropometric_data')) {
                $anthropometricData = $request->input('anthropometric_data');
                
                // Check if anthropometric data exists
                $existingData = DB::table('anthropometric_data')
                    ->where('athlete_id', $id)
                    ->first();
                
                if ($existingData) {
                    // Update existing data
                    DB::table('anthropometric_data')
                        ->where('athlete_id', $id)
                        ->update($anthropometricData);
                } else {
                    // Create new data
                    $anthropometricData['athlete_id'] = $id;
                    $anthropometricData['created_at'] = now();
                    $anthropometricData['updated_at'] = now();
                    
                    DB::table('anthropometric_data')->insert($anthropometricData);
                }
            }
            
            DB::commit();
            
            return redirect()->route('athletes.show', $id)
                ->with('success', 'Athlete updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error updating athlete: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified athlete evaluation from storage.
     */
    public function destroy(string $id)
    {
        $athlete = Athlete::findOrFail($id);
        $athlete->delete();

        return redirect()->route('athletes.index')
            ->with('success', 'Athlete evaluation deleted successfully.');
    }

    /**
     * Add a new evaluation for an existing athlete.
     */
    public function addEvaluation(string $id)
    {
        $athlete = Athlete::findOrFail($id);
        
        return view('athletes.add-evaluation', [
            'athlete' => $athlete,
        ]);
    }

    /**
     * Store a new evaluation for an existing athlete.
     */
    public function storeEvaluation(Request $request, string $id)
    {
        $athlete = Athlete::findOrFail($id);
        
        $request->validate([
            'evaluation_date' => 'required|date',
            'age' => 'required|numeric',
            'grade' => 'nullable|string|max:255',
            'sport' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        // Create new athlete record with the same profile data
        $newEvaluation = new Athlete();
        $newEvaluation->first_name = $athlete->first_name;
        $newEvaluation->last_name = $athlete->last_name;
        $newEvaluation->gender = $athlete->gender;
        $newEvaluation->identity_document = $athlete->identity_document;
        $newEvaluation->birth_date = $athlete->birth_date;
        $newEvaluation->father_name = $athlete->father_name;
        $newEvaluation->mother_name = $athlete->mother_name;
        $newEvaluation->institution_id = $athlete->institution_id;
        $newEvaluation->tutor_id = $athlete->tutor_id;
        
        // Add the new evaluation data
        $newEvaluation->evaluation_date = $request->evaluation_date;
        $newEvaluation->age = $request->age;
        $newEvaluation->grade = $request->grade;
        $newEvaluation->sport = $request->sport;
        $newEvaluation->category = $request->category;
        $newEvaluation->save();

        // Create anthropometric data if provided
        if ($request->has('anthropometric_data')) {
            $anthropometricData = $request->input('anthropometric_data');
            $anthropometricData['athlete_id'] = $newEvaluation->id;
            AnthropometricData::create($anthropometricData);
        }

        return redirect()->route('athletes.show', $athlete->id)
            ->with('success', 'New evaluation added successfully.');
    }

    /**
     * Compare multiple evaluations for the same athlete.
     */
    public function compareEvaluations(Request $request)
    {
        $evaluationIds = explode(',', $request->evaluations);
        $evaluations = Athlete::with('anthropometricData')
                            ->whereIn('id', $evaluationIds)
                            ->get();

        // Group evaluations by athlete
        $athleteGroups = $evaluations->groupBy(function($evaluation) {
            if ($evaluation->identity_document) {
                return $evaluation->identity_document;
            }
            return $evaluation->first_name . '-' . $evaluation->last_name . '-' . $evaluation->birth_date;
        });

        return view('athletes.compare', [
            'athleteGroups' => $athleteGroups,
        ]);
    }
}
