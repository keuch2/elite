<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Report;
use App\Models\ReportConfig;
use App\Models\Institution;
use App\Services\RankingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Muestra un listado de los informes.
     */
    public function index(Request $request)
    {
        $query = Report::with(['athlete.athleteProfile', 'template']);
        
        // Filtrar por nombre de atleta
        if ($request->has('athlete') && !empty($request->athlete)) {
            $query->whereHas('athlete.athleteProfile', function($q) use ($request) {
                $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', '%' . $request->athlete . '%');
            });
        }
        
        // Filtrar por institución
        if ($request->has('institution_id') && !empty($request->institution_id)) {
            $query->whereHas('athlete', function($q) use ($request) {
                $q->where('institution_id', $request->institution_id);
            });
        }
        
        // Filtrar por plantilla
        if ($request->has('template_id') && !empty($request->template_id)) {
            $query->where('template_id', $request->template_id);
        }
        
        // Ordenar resultados
        $sortField = $request->sort ?? 'created_at';
        $sortDirection = $request->direction ?? 'desc';
        
        $allowedSortFields = ['created_at', 'id'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }
        
        $query->orderBy($sortField, $sortDirection);
        
        $reports = $query->paginate(10)->withQueryString();
        $institutions = Institution::orderBy('name')->get();
        $templates = ReportConfig::orderBy('name')->get();
        
        return view('reports.index', compact('reports', 'institutions', 'templates'));
    }

    /**
     * Muestra el formulario para crear un nuevo informe.
     */
    public function create()
    {
        $institutions = Institution::all();
        $templates = ReportConfig::all();
        return view('reports.create', compact('institutions', 'templates'));
    }
    
    /**
     * Muestra un formulario simplificado para crear un nuevo informe.
     */
    public function simpleCreate()
    {
        return view('reports.simple-create');
    }

    /**
     * Obtiene los atletas por institución.
     */
    public function getAthletesByInstitution($institution_id)
    {
        // Validar que la institución exista
        if(!DB::table('institutions')->where('id', $institution_id)->exists()) {
            return response()->json(['error' => 'Institución no encontrada'], 404);
        }

        $athletes = DB::table('athletes')
            ->join('athlete_profiles', 'athletes.athlete_profile_id', '=', 'athlete_profiles.id')
            ->select(
                'athletes.id',
                'athletes.evaluation_id',
                'athletes.evaluation_date',
                'athlete_profiles.first_name',
                'athlete_profiles.last_name'
            )
            ->where('athletes.institution_id', $institution_id)
            ->orderBy('athlete_profiles.last_name')
            ->orderBy('athlete_profiles.first_name')
            ->orderBy('athletes.evaluation_date', 'desc')
            ->get();

        return response()->json($athletes);
    }
    
    /**
     * Obtiene los detalles del atleta para autocompletar el informe.
     */
    public function getAthleteDetails($athlete_id)
    {
        try {
            \Log::info('getAthleteDetails llamado con athlete_id: ' . $athlete_id);
            
            // Validar que el atleta exista
            if(!DB::table('athletes')->where('id', $athlete_id)->exists()) {
                return response()->json(['error' => 'Atleta no encontrado'], 404);
            }
            
            \Log::info('Validación aprobada, athlete_id: ' . $athlete_id);

            // Primero, obtener info básica del atleta sin joins para asegurar que existe
            $basicAthlete = DB::table('athletes')
                ->where('id', $athlete_id)
                ->first();
            
            \Log::info('Resultado de consulta de atleta básico: ' . ($basicAthlete ? 'Encontrado' : 'No encontrado'));
                
            if (!$basicAthlete) {
                \Log::warning('Atleta no encontrado con ID: ' . $athlete_id);
                return response()->json(['error' => 'Atleta no encontrado'], 404);
            }
            
            \Log::info('Datos básicos del atleta: ' . json_encode($basicAthlete));
            
            // Obtener perfil del atleta
            $profile = DB::table('athlete_profiles')
                ->where('id', $basicAthlete->athlete_profile_id)
                ->first();
            
            \Log::info('Resultado de consulta del perfil: ' . ($profile ? 'Encontrado' : 'No encontrado'));
                
            if (!$profile) {
                \Log::warning('Perfil de atleta no encontrado con ID: ' . $basicAthlete->athlete_profile_id);
                return response()->json(['error' => 'Perfil de atleta no encontrado'], 404);
            }
            
            \Log::info('Datos del perfil: ' . json_encode($profile));
            
            // Obtener institución si está disponible
            $institution = null;
            if ($basicAthlete->institution_id) {
                $institution = DB::table('institutions')
                    ->where('id', $basicAthlete->institution_id)
                    ->first();
                
                \Log::info('Resultado de consulta de institución: ' . ($institution ? 'Encontrado' : 'No encontrado'));
                if ($institution) {
                    \Log::info('Datos de institución: ' . json_encode($institution));
                }
            }
            
            // Obtener datos antropométricos si están disponibles
            $anthropometricData = DB::table('anthropometric_data')
                ->where('athlete_id', $athlete_id)
                ->first();
            
            \Log::info('Resultado de consulta de datos antropométricos: ' . ($anthropometricData ? 'Encontrado' : 'No encontrado'));
            if ($anthropometricData) {
                \Log::info('Datos antropométricos: ' . json_encode($anthropometricData));
            }
            
            // Formatear datos para los campos del informe
            $reportData = [
                'nombre' => $profile->first_name ?? '',
                'apellido' => $profile->last_name ?? '',
                'documento_de_identidad' => $profile->identity_document ?? '',
                'fecha_de_nacimiento' => $profile->birth_date ?? '',
                'fecha_de_evaluacion' => $basicAthlete->evaluation_date ?? '',
                'edad' => $basicAthlete->age ?? '',
                'grado' => $basicAthlete->grade ?? '',
                'deporte' => $basicAthlete->sport ?? '',
                'categoria' => $basicAthlete->category ?? '',
                'institucion' => $institution ? $institution->name : '',
                'nombre_del_padre' => $profile->father_name ?? '',
                'nombre_de_la_madre' => $profile->mother_name ?? '',
            ];
            
            // Añadir datos antropométricos si están disponibles
            if ($anthropometricData) {
                if (isset($anthropometricData->standing_height)) {
                    $reportData['talla_parado'] = $anthropometricData->standing_height;
                }
                if (isset($anthropometricData->sitting_height)) {
                    $reportData['talla_sentado'] = $anthropometricData->sitting_height;
                }
                if (isset($anthropometricData->wingspan)) {
                    $reportData['envergadura'] = $anthropometricData->wingspan;
                }
                if (isset($anthropometricData->weight)) {
                    $reportData['peso'] = $anthropometricData->weight;
                }
                if (isset($anthropometricData->cormic_index)) {
                    $reportData['indice_cormico'] = $anthropometricData->cormic_index;
                }
                if (isset($anthropometricData->phv)) {
                    $reportData['phv'] = $anthropometricData->phv;
                }
                if (isset($anthropometricData->skinfold_sum)) {
                    $reportData['sumatoria_de_pliegues'] = $anthropometricData->skinfold_sum;
                }
                if (isset($anthropometricData->fat_mass_percentage)) {
                    $reportData['masa_adiposa_en_porcentaje'] = $anthropometricData->fat_mass_percentage;
                }
                if (isset($anthropometricData->fat_mass_kg)) {
                    $reportData['masa_adiposa_en_kg'] = $anthropometricData->fat_mass_kg;
                }
                if (isset($anthropometricData->muscle_mass_percentage)) {
                    $reportData['masa_muscular_en_porcentaje'] = $anthropometricData->muscle_mass_percentage;
                }
                if (isset($anthropometricData->muscle_mass_kg)) {
                    $reportData['masa_muscular_en_kg'] = $anthropometricData->muscle_mass_kg;
                }
            }
            
            \Log::info('Devolviendo datos del informe: ' . json_encode($reportData));
            return response()->json($reportData);
            
        } catch (\Exception $e) {
            // Registrar el error para depuración
            \Log::error('Error en getAthleteDetails: ' . $e->getMessage());
            \Log::error('Línea de error: ' . $e->getLine());
            \Log::error('Archivo de error: ' . $e->getFile());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'error' => 'Error al recuperar los detalles del atleta',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Almacena un nuevo informe en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'athlete_id' => 'required|exists:athletes,id',
            'template_id' => 'required|exists:report_configs,id',
            'report_data' => 'nullable|array',
        ]);

        // Crear el informe
        $report = Report::create([
            'athlete_id' => $request->athlete_id,
            'template_id' => $request->template_id,
            'report_data' => $request->report_data,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('reports.show', $report->id)
            ->with('success', 'Informe creado correctamente');
    }

    /**
     * Muestra un informe específico.
     */
    public function show($id)
    {
        $report = Report::with([
            'athlete', 
            'athlete.athleteProfile'
        ])->findOrFail($id);
        
        $template = ReportConfig::findOrFail($report->template_id);
        
        // Calculate rankings for this athlete compared to peers
        $rankingService = new RankingService();
        $rankings = $rankingService->calculateRankings($report);
        
        return view('reports.show', compact('report', 'template', 'rankings'));
    }

    /**
     * Muestra el formulario para editar un informe específico.
     */
    public function edit($id)
    {
        $report = Report::with([
            'athlete', 
            'athlete.athleteProfile'
        ])->findOrFail($id);
        
        $template = ReportConfig::findOrFail($report->template_id);
        $templates = ReportConfig::all();
        
        return view('reports.edit', compact('report', 'template', 'templates'));
    }

    /**
     * Actualiza un informe específico en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'template_id' => 'required|exists:report_configs,id',
            'report_data' => 'nullable|array',
        ]);

        $report = Report::findOrFail($id);
        
        // Actualizar el informe
        $report->update([
            'template_id' => $request->template_id,
            'report_data' => $request->report_data,
        ]);

        return redirect()->route('reports.show', $report->id)
            ->with('success', 'Informe actualizado correctamente');
    }

    /**
     * Exporta el informe a PDF.
     */
    public function exportPdf($id)
    {
        $report = Report::with(['athlete' => function($query) {
            $query->join('athlete_profiles', 'athletes.athlete_profile_id', '=', 'athlete_profiles.id')
                  ->select('athletes.*', 'athlete_profiles.first_name', 'athlete_profiles.last_name');
        }])->findOrFail($id);
        
        $template = ReportConfig::findOrFail($report->template_id);
        
        // Configurar opciones para el PDF
        $options = [
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ];
        
        $pdf = PDF::loadView('reports.pdf', compact('report', 'template'))
                  ->setPaper('a4')
                  ->setOptions($options);
        
        return $pdf->download('informe-' . $report->id . '.pdf');
    }
    
    /**
     * Obtiene informes para una evaluación específica de atleta.
     */
    public function getReportsByAthlete($athleteId)
    {
        $reports = Report::where('athlete_id', $athleteId)
            ->with('template')
            ->latest()
            ->get();
            
        $athlete = DB::table('athletes')
            ->join('athlete_profiles', 'athletes.athlete_profile_id', '=', 'athlete_profiles.id')
            ->select(
                'athletes.*',
                'athlete_profiles.first_name',
                'athlete_profiles.last_name'
            )
            ->where('athletes.id', $athleteId)
            ->first();
            
        if (!$athlete) {
            abort(404, 'Atleta no encontrado');
        }
        
        return view('reports.athlete-reports', compact('reports', 'athlete'));
    }
    
    /**
     * Compara informes de diferentes evaluaciones del mismo atleta.
     */
    public function compareReports(Request $request)
    {
        $reportIds = $request->input('report_ids', []);
        
        if (empty($reportIds)) {
            return redirect()->back()->with('error', 'No se seleccionaron informes para comparar');
        }
        
        $reports = Report::whereIn('id', $reportIds)
            ->with(['athlete' => function($query) {
                $query->join('athlete_profiles', 'athletes.athlete_profile_id', '=', 'athlete_profiles.id')
                      ->select('athletes.*', 'athlete_profiles.first_name', 'athlete_profiles.last_name');
            }])
            ->with('template')
            ->get();
            
        // Agrupar informes por plantilla para asegurar que estamos comparando datos similares
        $reportsByTemplate = $reports->groupBy('template_id');
        
        return view('reports.compare', compact('reportsByTemplate'));
    }

    /**
     * Elimina un informe existente.
     */
    public function destroy($id)
    {
        try {
            $report = Report::findOrFail($id);
            $report->delete();
            
            return redirect()->route('reports.index')
                           ->with('success', 'Informe eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('reports.index')
                           ->with('error', 'Error al eliminar el informe: ' . $e->getMessage());
        }
    }
}
