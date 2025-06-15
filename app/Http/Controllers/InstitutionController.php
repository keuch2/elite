<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the institutions.
     */
    public function index()
    {
        $institutions = Institution::paginate(15);
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
            'name' => 'required|string|max:255|unique:institutions,name',
        ]);

        $institution = Institution::create($request->all());

        return redirect()->route('institutions.index')
            ->with('success', 'Institution created successfully.');
    }

    /**
     * Display the specified institution and its athletes.
     */
    public function show(string $id)
    {
        $institution = Institution::with('athletes')->findOrFail($id);
        
        // Get athletes with pagination and search functionality
        $query = $institution->athletes()->with('athleteProfile');
        
        // Apply search filter if provided
        if (request()->has('search')) {
            $search = request('search');
            $query->whereHas('athleteProfile', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }
        
        $athletes = $query->paginate(15);
        
        return view('institutions.show', compact('institution', 'athletes'));
    }

    /**
     * Show the form for editing the institution.
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
            'name' => 'required|string|max:255|unique:institutions,name,' . $id,
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
        // Check if there are athletes associated with this institution
        if ($institution->athletes()->count() > 0) {
            return redirect()->route('institutions.index')
                ->with('error', 'Cannot delete institution because it has athletes associated with it.');
        }

        $institution->delete();

        return redirect()->route('institutions.index')
            ->with('success', 'Institution deleted successfully.');
    }

    /**
     * Show reports for an institution.
     */
    public function reports(string $id)
    {
        $institution = Institution::findOrFail($id);
        
        // Determine which view to use based on the requested route
        $routeName = request()->route()->getName();
        
        if ($routeName === 'institutions.evaluations') {
            // If accessing via the old 'evaluations' route, use the evaluations view
            // but populate it with the same data
            $evaluations = $institution->athletes()->with('anthropometricData')->paginate(15);
            
            // Apply sport filter if specified
            if (request()->has('sport') && !empty(request('sport'))) {
                $evaluations = $institution->athletes()
                    ->where('sport', request('sport'))
                    ->with('anthropometricData')
                    ->paginate(15);
            }
            
            return view('institutions.evaluations', [
                'institution' => $institution,
                'evaluations' => $evaluations,
            ]);
        }
        
        // Default reports view
        $reports = $institution->athletes()->with('reports')->paginate(15);
        
        // Get gender statistics
        $genderStats = $institution->athletes()
            ->join('athlete_profiles', 'athletes.athlete_profile_id', '=', 'athlete_profiles.id')
            ->selectRaw('athlete_profiles.gender, COUNT(*) as count')
            ->groupBy('athlete_profiles.gender')
            ->get();
            
        // Get age statistics
        $ageStats = $institution->athletes()
            ->join('athlete_profiles', 'athletes.athlete_profile_id', '=', 'athlete_profiles.id')
            ->selectRaw("
                CASE 
                    WHEN TIMESTAMPDIFF(YEAR, athlete_profiles.birth_date, CURDATE()) < 12 THEN 'Under 12'
                    WHEN TIMESTAMPDIFF(YEAR, athlete_profiles.birth_date, CURDATE()) BETWEEN 12 AND 14 THEN '12-14'
                    WHEN TIMESTAMPDIFF(YEAR, athlete_profiles.birth_date, CURDATE()) BETWEEN 15 AND 17 THEN '15-17'
                    WHEN TIMESTAMPDIFF(YEAR, athlete_profiles.birth_date, CURDATE()) BETWEEN 18 AND 20 THEN '18-20'
                    ELSE 'Over 20'
                END as age_group,
                COUNT(*) as count
            ")
            ->groupBy('age_group')
            ->get();
            
        // Get sport statistics
        $sportStats = $institution->athletes()
            ->join('athlete_profiles', 'athletes.athlete_profile_id', '=', 'athlete_profiles.id')
            ->selectRaw('athletes.sport, COUNT(*) as count')
            ->groupBy('athletes.sport')
            ->get();
        
        return view('institutions.reports', [
            'institution' => $institution,
            'reports' => $reports,
            'genderStats' => $genderStats,
            'ageStats' => $ageStats,
            'sportStats' => $sportStats,
        ]);
    }
}
