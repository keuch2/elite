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

        ReportConfig::create([
            'name' => $request->name,
            'fields' => $request->fields,
        ]);

        return redirect()->back()->with('success', 'Report configuration saved!');
    }

    public function download($id)
    {
        $config = ReportConfig::findOrFail($id);
        return Excel::download(new ReportTemplateExport($config->fields), "{$config->name}_template.xlsx");
    }
}