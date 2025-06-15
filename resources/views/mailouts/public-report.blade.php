<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Evaluación Deportiva</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Informe de Evaluación Deportiva</h1>
                    <a href="{{ route('public.report.pdf', $report->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Exportar PDF
                    </a>
                </div>
                
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-2">
                        @if($report->athlete && $report->athlete->athleteProfile)
                            {{ $report->athlete->athleteProfile->first_name ?? '' }} 
                            {{ $report->athlete->athleteProfile->last_name ?? '' }}
                        @endif
                    </h2>
                    @if($template)
                        <p class="text-gray-600">Plantilla: {{ $template->name }}</p>
                    @endif
                    <p class="text-gray-500 text-sm">Fecha: {{ $report->created_at->format('d/m/Y') }}</p>
                </div>

                @if($report->athlete && $report->athlete->athleteProfile)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Datos Personales</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="w-1/3 px-4 py-2 text-left text-sm font-semibold text-gray-600">Campo</th>
                                        <th class="w-2/3 px-4 py-2 text-left text-sm font-semibold text-gray-600">Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t">
                                        <td class="px-4 py-2 text-sm text-gray-600">Nombre completo</td>
                                        <td class="px-4 py-2 font-medium">
                                            {{ $report->athlete->athleteProfile->first_name ?? '' }} 
                                            {{ $report->athlete->athleteProfile->last_name ?? '' }}
                                        </td>
                                    </tr>
                                    <tr class="border-t">
                                        <td class="px-4 py-2 text-sm text-gray-600">Documento de Identidad</td>
                                        <td class="px-4 py-2 font-medium">{{ $report->athlete->athleteProfile->identity_document ?? 'No disponible' }}</td>
                                    </tr>
                                    <tr class="border-t">
                                        <td class="px-4 py-2 text-sm text-gray-600">Fecha de Nacimiento</td>
                                        <td class="px-4 py-2 font-medium">
                                            @if($report->athlete->athleteProfile->birth_date)
                                                {{ \Carbon\Carbon::parse($report->athlete->athleteProfile->birth_date)->format('d/m/Y') }}
                                            @else
                                                No disponible
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="border-t">
                                        <td class="px-4 py-2 text-sm text-gray-600">Género</td>
                                        <td class="px-4 py-2 font-medium">
                                            @if($report->athlete->athleteProfile->gender == 'm')
                                                Masculino
                                            @elseif($report->athlete->athleteProfile->gender == 'f')
                                                Femenino
                                            @else
                                                {{ $report->athlete->athleteProfile->gender ?? 'No disponible' }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="border-t">
                                        <td class="px-4 py-2 text-sm text-gray-600">Edad</td>
                                        <td class="px-4 py-2 font-medium">{{ $report->athlete->age ?? 'No disponible' }}</td>
                                    </tr>
                                    <tr class="border-t">
                                        <td class="px-4 py-2 text-sm text-gray-600">Deporte</td>
                                        <td class="px-4 py-2 font-medium">{{ $report->athlete->sport ?? 'No disponible' }}</td>
                                    </tr>
                                    <tr class="border-t">
                                        <td class="px-4 py-2 text-sm text-gray-600">Categoría</td>
                                        <td class="px-4 py-2 font-medium">{{ $report->athlete->category ?? 'No disponible' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($report->report_data)
                    @php
                        // Group data by category based on field names
                        $categories = [
                            'Datos Personales' => [
                                'nombre', 'apellido', 'documento_de_identidad', 'fecha_de_nacimiento', 
                                'genero', 'edad', 'deporte', 'categoria', 'institucion'
                            ],
                            'Datos Antropométricos' => [
                                'talla_parado', 'talla_sentado', 'envergadura', 'peso', 'indice_cormico', 
                                'phv', 'sumatoria_de_pliegues', 'masa_adiposa_en_porcentaje', 'masa_adiposa_en_kg',
                                'masa_muscular_en_kg', 'masa_residual_en_kg', 'masa_osea_en_kg'
                            ],
                            'Lateralidad' => [
                                'ojo', 'hombro', 'mano', 'cadera', 'pie'
                            ],
                            'Movilidad' => [
                                'sit_and_reach', 'movilidad_tobillo_derecha', 'movilidad_tobillo_izquierda',
                                'control_motor_anterior_derecha', 'control_motor_anterior_izquierda'
                            ],
                            'Saltabilidad' => [
                                'abalakov_altura_cm', 'abalakov_impulso', 'cmj_altura_cm', 'cmj_impulso',
                                'cmj_unipodal_derecha_altura_cm', 'cmj_unipodal_izquierda_altura_cm'
                            ],
                            'Fuerza' => [
                                'agarre', 'agarre_alto_categoria', 'agarre_promedio_categoria', 'agarre_bajo_categoria'
                            ],
                            'Neuro Cognitivo Motor' => [
                                'reaccion_oculo_manual', 'reaccion_oculo_podal'
                            ]
                        ];
                        
                        // Get field labels from ReportFields helper
                        $fieldLabels = [];
                        foreach (App\Helpers\ReportFields::getAvailableFields() as $category => $fields) {
                            foreach ($fields as $key => $label) {
                                $fieldLabels[$key] = $label;
                            }
                        }
                    @endphp

                    @foreach($categories as $category => $fieldKeys)
                        @php
                            $hasData = false;
                            foreach($fieldKeys as $key) {
                                if(isset($report->report_data[$key])) {
                                    $hasData = true;
                                    break;
                                }
                            }
                        @endphp

                        @if($hasData)
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ $category }}</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white">
                                        <thead>
                                            <tr>
                                                <th class="w-1/3 px-4 py-2 text-left text-sm font-semibold text-gray-600">Campo</th>
                                                <th class="w-2/3 px-4 py-2 text-left text-sm font-semibold text-gray-600">Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($fieldKeys as $key)
                                                @if(isset($report->report_data[$key]))
                                                    <tr class="border-t">
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ $fieldLabels[$key] ?? $key }}</td>
                                                        <td class="px-4 py-2 font-medium">{{ $report->report_data[$key] }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    {{-- Display any remaining fields that weren't categorized --}}
                    @php
                        $categorizedKeys = collect($categories)->flatten()->toArray();
                        $uncategorizedData = collect($report->report_data)
                            ->filter(function($value, $key) use ($categorizedKeys) {
                                return !in_array($key, $categorizedKeys);
                            });
                    @endphp

                    @if($uncategorizedData->isNotEmpty())
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Datos Adicionales</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr>
                                            <th class="w-1/3 px-4 py-2 text-left text-sm font-semibold text-gray-600">Campo</th>
                                            <th class="w-2/3 px-4 py-2 text-left text-sm font-semibold text-gray-600">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($uncategorizedData as $key => $value)
                                            <tr class="border-t">
                                                <td class="px-4 py-2 text-sm text-gray-600">{{ $fieldLabels[$key] ?? $key }}</td>
                                                <td class="px-4 py-2 font-medium">{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>No hay datos adicionales disponibles para este informe.</p>
                    </div>
                @endif

                <div class="mt-8 text-center text-sm text-gray-500">
                    <p>Informe generado el {{ $report->created_at->format('d/m/Y H:i') }}</p>
                    <p class="mt-2">Elite Sports Tracker</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
