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
                'athletes.first_name',
                'athletes.last_name',
                'athletes.gender',
                'athletes.birth_date',
                'athletes.identity_document',
                'institutions.name as institution_name',
                DB::raw('COUNT(*) as evaluations_count'),
                DB::raw('MAX(athletes.evaluation_date) as latest_evaluation')
            )
            ->leftJoin('institutions', 'athletes.institution_id', '=', 'institutions.id')
            ->groupBy(
                'athletes.first_name',
                'athletes.last_name',
                'athletes.birth_date',
                'athletes.gender',
                'athletes.identity_document',
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
            'identity_document' => 'nullable|string|max:255|unique:athletes',
            'institution_id' => 'nullable|exists:institutions,id',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'evaluation_date' => 'required|date',
            'age' => 'required|numeric',
            'grade' => 'nullable|string|max:255',
            'sport' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        // Create the athlete record
        $athlete = Athlete::create($request->all());

        // Optionally create anthropometric data if provided
        if ($request->has('anthropometric_data')) {
            $anthropometricData = $request->input('anthropometric_data');
            $anthropometricData['athlete_id'] = $athlete->id;
            AnthropometricData::create($anthropometricData);
        }

        return redirect()->route('athletes.show', $athlete)
            ->with('success', 'Athlete created successfully.');
    }

    /**
     * Display the specified athlete profile with all evaluations.
     */
    public function show(string $id)
    {
        // Get the athlete record
        $athlete = Athlete::findOrFail($id);
        
        // Find all evaluations for the same athlete (same identity or name+birthdate)
        $query = Athlete::query();
        
        if ($athlete->identity_document) {
            // If identity document exists, use it for matching
            $query->where('identity_document', $athlete->identity_document);
        } else {
            // Otherwise use name and birth date
            $query->where('first_name', $athlete->first_name)
                ->where('last_name', $athlete->last_name)
                ->where('birth_date', $athlete->birth_date);
        }
        
        // Get all evaluations ordered by date
        $evaluations = $query->with('anthropometricData')
                            ->orderBy('evaluation_date', 'desc')
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
        $evaluation = Athlete::with('anthropometricData')->findOrFail($id);
        
        return view('athletes.evaluation', [
            'evaluation' => $evaluation,
        ]);
    }

    /**
     * Show the form for editing the specified athlete.
     */
    public function edit(string $id)
    {
        return view('athletes.edit', [
            'athlete' => Athlete::findOrFail($id),
            'institutions' => Institution::all(),
        ]);
    }

    /**
     * Update the specified athlete in storage.
     */
    public function update(Request $request, string $id)
    {
        $athlete = Athlete::findOrFail($id);
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|in:m,f,other',
            'birth_date' => 'required|date',
            'identity_document' => 'nullable|string|max:255|unique:athletes,identity_document,'.$id,
            'institution_id' => 'nullable|exists:institutions,id',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'evaluation_date' => 'required|date',
            'age' => 'required|numeric',
            'grade' => 'nullable|string|max:255',
            'sport' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        $athlete->update($request->all());

        // Update anthropometric data if it exists
        if ($athlete->anthropometricData && $request->has('anthropometric_data')) {
            $athlete->anthropometricData->update($request->input('anthropometric_data'));
        } 
        // Create new anthropometric data if it doesn't exist but is provided
        elseif ($request->has('anthropometric_data')) {
            $anthropometricData = $request->input('anthropometric_data');
            $anthropometricData['athlete_id'] = $athlete->id;
            AnthropometricData::create($anthropometricData);
        }

        return redirect()->route('athletes.show', $athlete)
            ->with('success', 'Athlete updated successfully.');
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
