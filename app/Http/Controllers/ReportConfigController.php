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

        return redirect()->back()->with('success', 'Report configuration saved!');
    }

    public function download($id)
    {
        $config = ReportConfig::findOrFail($id);
        return Excel::download(new ReportTemplateExport($config->fields), "{$config->name}_template.xlsx");
    }
}