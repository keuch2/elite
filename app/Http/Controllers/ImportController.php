<?php

namespace App\Http\Controllers;

use App\Imports\AthletesImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    // Show the upload form
    public function showUploadForm()
    {
        return view('import');
    }

    // Handle CSV and Excel file imports
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:2048', // Allow CSV, XLSX, and XLS
        ]);

        try {
            // Check file type
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            if ($extension === 'csv') {
                // Create directory if it doesn't exist
                if (!Storage::disk('csv_uploads')->exists('')) {
                    Storage::disk('csv_uploads')->makeDirectory('');
                }

                // Handle CSV file
                $path = $file->store('', 'csv_uploads');
                $csvData = array_map('str_getcsv', file(storage_path('app/csv_uploads/' . $path)));

                // Extract headers and rows
                $headers = $csvData[0];
                $rows = array_slice($csvData, 1);

                // Pass data to a view for analysis
                return view('analyze-csv', compact('headers', 'rows', 'path'));
            } else {
                // Handle Excel file
                Excel::import(new AthletesImport, $file);
                return redirect()->back()->with('success', 'Data imported successfully!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }

    // Generate reports based on CSV data
    public function generateReports(Request $request)
    {
        $csvPath = $request->input('csv_path');
        $csvData = array_map('str_getcsv', file(storage_path('app/csv_uploads/' . $csvPath)));

        // Extract headers and rows
        $headers = $csvData[0];
        $rows = array_slice($csvData, 1);

        // Process each row and generate reports
        foreach ($rows as $row) {
            $athleteData = array_combine($headers, $row);

            // Find or create the athlete
            $athlete = \App\Models\Athlete::firstOrCreate(
                ['identity_document' => $athleteData['identity_document']],
                $athleteData
            );

            // Generate a report for the athlete
            \App\Models\Report::create([
                'athlete_id' => $athlete->id,
                'file_path' => 'path/to/generated/report.pdf', // Placeholder
                'sent_to_tutor' => false,
                'sent_to_institution' => false,
            ]);
        }

        return redirect()->route('import.form')->with('success', 'Reports generated successfully!');
    }
}