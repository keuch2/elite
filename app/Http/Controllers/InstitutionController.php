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
        return view('institutions.show', compact('institution'));
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
     * Show all evaluations from an institution.
     */
    public function evaluations(string $id)
    {
        $institution = Institution::findOrFail($id);
        $evaluations = $institution->athletes()->with('anthropometricData')->paginate(15);
        
        return view('institutions.evaluations', [
            'institution' => $institution,
            'evaluations' => $evaluations,
        ]);
    }

    /**
     * Show reports for an institution.
     */
    public function reports(string $id)
    {
        $institution = Institution::findOrFail($id);
        $reports = $institution->athletes()->with('reports')->paginate(15);
        
        return view('institutions.reports', [
            'institution' => $institution,
            'reports' => $reports,
        ]);
    }
}
