<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\Athlete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the institutions.
     */
    public function index()
    {
        $institutions = Institution::withCount([
            'athletes' => function($query) {
                // Count unique athletes based on identity or name+birthdate
                $query->select(DB::raw('COUNT(DISTINCT COALESCE(identity_document, CONCAT(first_name, last_name, birth_date)))'));
            }
        ])->get();
        
        return view('institutions.index', compact('institutions'));
    }

    /**
     * Show the form for creating a new institution.
     */
    public function create()
    {
        return view('institutions.create');
    }

    /**
     * Store a newly created institution in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:institutions',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $institution = Institution::create($request->all());

        return redirect()->route('institutions.index')
            ->with('success', 'Institution created successfully.');
    }

    /**
     * Display the specified institution with its athletes.
     */
    public function show(string $id)
    {
        $institution = Institution::findOrFail($id);
        
        // Get unique athlete profiles from this institution
        // by grouping athletes by identity document or name+birthdate
        $athleteProfiles = DB::table('athletes')
            ->select(
                DB::raw('MIN(athletes.id) as id'), // Use the first athlete record as reference
                'athletes.first_name',
                'athletes.last_name',
                'athletes.gender',
                'athletes.birth_date',
                'athletes.identity_document',
                DB::raw('COUNT(*) as evaluations_count'),
                DB::raw('MAX(athletes.evaluation_date) as latest_evaluation')
            )
            ->where('institution_id', $id)
            ->groupBy(function($athlete) {
                if (!empty($athlete->identity_document)) {
                    return $athlete->identity_document;
                }
                return $athlete->first_name . $athlete->last_name . $athlete->birth_date;
            })
            ->orderBy('athletes.last_name')
            ->paginate(15);

        return view('institutions.show', [
            'institution' => $institution,
            'athleteProfiles' => $athleteProfiles,
        ]);
    }

    /**
     * Show the form for editing the specified institution.
     */
    public function edit(string $id)
    {
        $institution = Institution::findOrFail($id);
        
        return view('institutions.edit', compact('institution'));
    }

    /**
     * Update the specified institution in storage.
     */
    public function update(Request $request, string $id)
    {
        $institution = Institution::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:institutions,name,'.$id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $institution->update($request->all());

        return redirect()->route('institutions.index')
            ->with('success', 'Institution updated successfully.');
    }

    /**
     * Remove the specified institution from storage.
     */
    public function destroy(string $id)
    {
        $institution = Institution::findOrFail($id);
        
        // Check if the institution has athletes
        if ($institution->athletes()->exists()) {
            return back()->with('error', 'Cannot delete institution with associated athletes.');
        }
        
        $institution->delete();

        return redirect()->route('institutions.index')
            ->with('success', 'Institution deleted successfully.');
    }
    
    /**
     * List all evaluations for athletes from this institution.
     */
    public function evaluations(string $id)
    {
        $institution = Institution::findOrFail($id);
        
        $evaluations = Athlete::with('anthropometricData')
            ->where('institution_id', $id)
            ->orderBy('evaluation_date', 'desc')
            ->paginate(20);
            
        return view('institutions.evaluations', [
            'institution' => $institution,
            'evaluations' => $evaluations,
        ]);
    }
    
    /**
     * Show statistical reports about the athletes in this institution.
     */
    public function reports(string $id)
    {
        $institution = Institution::findOrFail($id);
        
        // Get counts by gender
        $genderStats = DB::table('athletes')
            ->select('gender', DB::raw('COUNT(DISTINCT COALESCE(identity_document, CONCAT(first_name, last_name, birth_date))) as count'))
            ->where('institution_id', $id)
            ->groupBy('gender')
            ->get();
            
        // Get counts by age groups
        $ageStats = DB::table('athletes')
            ->select(
                DB::raw('CASE 
                    WHEN age < 10 THEN "Under 10" 
                    WHEN age BETWEEN 10 AND 14 THEN "10-14"
                    WHEN age BETWEEN 15 AND 18 THEN "15-18"
                    ELSE "Over 18" 
                END as age_group'),
                DB::raw('COUNT(*) as count')
            )
            ->where('institution_id', $id)
            ->groupBy('age_group')
            ->orderBy('age_group')
            ->get();
            
        // Get counts by sport
        $sportStats = DB::table('athletes')
            ->select('sport', DB::raw('COUNT(*) as count'))
            ->where('institution_id', $id)
            ->whereNotNull('sport')
            ->groupBy('sport')
            ->orderBy('count', 'desc')
            ->get();
            
        return view('institutions.reports', [
            'institution' => $institution,
            'genderStats' => $genderStats,
            'ageStats' => $ageStats, 
            'sportStats' => $sportStats,
        ]);
    }
}
