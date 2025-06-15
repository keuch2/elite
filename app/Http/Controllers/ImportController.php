<?php

namespace App\Http\Controllers;

use App\Imports\AthletesImport;
use App\Models\Athlete;
use App\Models\AthleteProfile;
use App\Models\Report;
use App\Models\ReportConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    // Show the upload form
    public function showUploadForm()
    {
        $templates = ReportConfig::all();
        return view('import', compact('templates'));
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

                // Get available templates
                $templates = ReportConfig::all();

                // Pass data to a view for analysis
                return view('analyze-csv', compact('headers', 'rows', 'path', 'templates'));
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
        $request->validate([
            'csv_path' => 'required',
            'template_id' => 'required|exists:report_configs,id'
        ]);

        $csvPath = $request->input('csv_path');
        $templateId = $request->input('template_id');
        $template = ReportConfig::findOrFail($templateId);
        
        $csvData = array_map('str_getcsv', file(storage_path('app/csv_uploads/' . $csvPath)));

        // Extract headers and rows
        $headers = $csvData[0];
        $rows = array_slice($csvData, 1);
        
        // Convert headers to lowercase and replace spaces with underscores
        $normalizedHeaders = array_map(function($header) {
            return strtolower(str_replace(' ', '_', $header));
        }, $headers);
        
        $reportsGenerated = 0;
        $errors = [];

        // Begin transaction to ensure data consistency
        DB::beginTransaction();
        
        try {
            // Process each row and generate reports
            foreach ($rows as $row) {
                // Combine headers with row data
                $athleteData = array_combine($normalizedHeaders, $row);
                
                // Extract identity document to find or create the athlete profile
                $identityDocument = $athleteData['identity_document'] ?? $athleteData['documento_de_identidad'] ?? null;
                
                if (!$identityDocument) {
                    $errors[] = "Row missing identity document: " . implode(', ', $row);
                    continue;
                }
                
                // Find or create athlete profile
                $profile = AthleteProfile::firstOrCreate(
                    ['identity_document' => $identityDocument],
                    [
                        'first_name' => $athleteData['first_name'] ?? $athleteData['nombre'] ?? '',
                        'last_name' => $athleteData['last_name'] ?? $athleteData['apellido'] ?? '',
                        'gender' => $athleteData['gender'] ?? $athleteData['sexo'] ?? null,
                        'birth_date' => $athleteData['birth_date'] ?? $athleteData['fecha_de_nacimiento'] ?? null,
                    ]
                );
                
                // Create athlete evaluation record
                $athlete = Athlete::create([
                    'athlete_profile_id' => $profile->id,
                    'evaluation_date' => $athleteData['evaluation_date'] ?? $athleteData['fecha_de_evaluacion'] ?? now(),
                    'age' => $athleteData['age'] ?? $athleteData['edad'] ?? null,
                    'grade' => $athleteData['grade'] ?? $athleteData['grado'] ?? null,
                    'sport' => $athleteData['sport'] ?? $athleteData['deporte'] ?? null,
                    'category' => $athleteData['category'] ?? $athleteData['categoria'] ?? null,
                    'institution_id' => $athleteData['institution_id'] ?? $athleteData['institucion_id'] ?? null,
                    'evaluation_id' => \Illuminate\Support\Str::uuid(),
                ]);
                
                // Filter data based on template fields
                $reportData = [];
                foreach ($template->fields as $field) {
                    if (isset($athleteData[$field])) {
                        $reportData[$field] = $athleteData[$field];
                    }
                }
                
                // Create report
                Report::create([
                    'athlete_id' => $athlete->id,
                    'template_id' => $templateId,
                    'report_data' => $reportData,
                    'created_by' => auth()->id(),
                ]);
                
                $reportsGenerated++;
            }
            
            // Commit transaction if everything was successful
            DB::commit();
            
            return redirect()->route('reports.index')
                ->with('success', "Generated $reportsGenerated reports successfully!" . 
                    (count($errors) > 0 ? " There were " . count($errors) . " errors." : ""));
                    
        } catch (\Exception $e) {
            // Rollback transaction if there was an error
            DB::rollBack();
            
            return redirect()->route('import.form')
                ->with('error', 'Error generating reports: ' . $e->getMessage());
        }
    }
}