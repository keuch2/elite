<?php

namespace App\Http\Controllers;

use App\Exports\ReportTemplateExport;
use App\Helpers\ReportFields;
use App\Models\ReportConfig;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportConfigController extends Controller
{
    public function create()
    {
        $fields = ReportFields::getAvailableFields();
        $configs = ReportConfig::all();
        return view('report-config.create', compact('fields', 'configs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:report_configs,name',
            'fields' => 'required|array|min:1',
        ]);

        // Define mandatory fields that should always be included
        $mandatoryFields = [
            'nombre',
            'apellido', 
            'documento_de_identidad',
            'fecha_de_nacimiento',
            'institucion'
        ];

        // Ensure mandatory fields are included in the submitted fields
        $fields = $request->fields ?? [];
        $fields = array_unique(array_merge($fields, $mandatoryFields));

        ReportConfig::create([
            'name' => $request->name,
            'fields' => $fields,
        ]);

        return redirect()->back()->with('success', '¡Plantilla guardada correctamente!');
    }

    /**
     * Show the form for editing the specified report config.
     */
    public function edit($id)
    {
        $config = ReportConfig::findOrFail($id);
        $fields = ReportFields::getAvailableFields();
        $configs = ReportConfig::all();
        
        return view('report-config.edit', compact('config', 'fields', 'configs'));
    }

    /**
     * Update the specified report config in storage.
     */
    public function update(Request $request, $id)
    {
        $config = ReportConfig::findOrFail($id);
        
        $request->validate([
            'name' => 'required|unique:report_configs,name,' . $id,
            'fields' => 'required|array|min:1',
        ]);

        // Define mandatory fields that should always be included
        $mandatoryFields = [
            'nombre',
            'apellido', 
            'documento_de_identidad',
            'fecha_de_nacimiento',
            'institucion'
        ];

        // Ensure mandatory fields are included in the submitted fields
        $fields = $request->fields ?? [];
        $fields = array_unique(array_merge($fields, $mandatoryFields));

        $config->update([
            'name' => $request->name,
            'fields' => $fields,
        ]);

        return redirect()->route('report-config.create')
            ->with('success', '¡Plantilla actualizada correctamente!');
    }

    /**
     * Remove the specified report config from storage.
     */
    public function destroy($id)
    {
        $config = ReportConfig::findOrFail($id);
        
        // Check if the template is being used by any reports
        // This requires a relationship between ReportConfig and Report models
        // If such relationship exists, uncomment and use this check
        
        // if ($config->reports()->count() > 0) {
        //     return redirect()->route('report-config.create')
        //         ->with('error', 'No se puede eliminar la plantilla porque está siendo utilizada por reportes.');
        // }
        
        $config->delete();
        
        return redirect()->route('report-config.create')
            ->with('success', '¡Plantilla eliminada correctamente!');
    }

    public function download($id)
    {
        $config = ReportConfig::findOrFail($id);
        return Excel::download(new ReportTemplateExport($config->fields), "{$config->name}_template.xlsx");
    }
    
    /**
     * Get fields for a specific report configuration
     */
    public function getFields($id)
    {
        $config = ReportConfig::findOrFail($id);
        $allAvailableFields = ReportFields::getAvailableFields();
        
        if (!$config || !$config->fields) {
            return response()->json([]);
        }
        
        // Organize fields by category
        $fieldsByCategory = [];
        
        foreach ($config->fields as $field) {
            $found = false;
            
            // Find which category this field belongs to
            foreach ($allAvailableFields as $category => $categoryFields) {
                if (array_key_exists($field, $categoryFields)) {
                    if (!isset($fieldsByCategory[$category])) {
                        $fieldsByCategory[$category] = [];
                    }
                    $fieldsByCategory[$category][] = $field;
                    $found = true;
                    break;
                }
            }
            
            // If field wasn't found in any category, add to "Otros"
            if (!$found) {
                if (!isset($fieldsByCategory['Otros'])) {
                    $fieldsByCategory['Otros'] = [];
                }
                $fieldsByCategory['Otros'][] = $field;
            }
        }
        
        return response()->json($fieldsByCategory);
    }
}