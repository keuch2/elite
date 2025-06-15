<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Ver Informe') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('reports.edit', $report->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    {{ __('Editar Informe') }}
                </a>
                <a href="{{ route('reports.export-pdf', $report->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    {{ __('Exportar PDF') }}
                </a>
                <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    {{ __('Volver a Informes') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="mb-8 text-center">
                        <h1 class="text-2xl font-bold mb-2">Informe de Evaluación Deportiva</h1>
                        @if($report->athlete && $report->athlete->athlete_profile)
                            <h2 class="text-xl">
                                {{ $report->athlete->athlete_profile->first_name ?? '' }} 
                                {{ $report->athlete->athlete_profile->last_name ?? '' }}
                            </h2>
                        @endif
                        @if($template)
                            <p class="text-gray-600 dark:text-gray-400">Plantilla: {{ $template->name }}</p>
                        @endif
                    </div>

                    @if($report->report_data)
                        @php
                            // Group data by category based on field names
                            $categories = [
                                'Datos Personales' => [
                                    'nombre', 'apellido', 'documento_de_identidad', 'fecha_de_nacimiento', 
                                    'genero', 'edad', 'deporte', 'categoria', 'institucion', 'grado', 'fecha_evaluacion'
                                ],
                                'Antropometría' => [
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
                                    'agarre', 'agarre_alto_categoria', 'agarre_promedio_categoria', 'agarre_bajo_categoria',
                                    'potencia_piernas', 'potencia_brazos'
                                ],
                                'Velocidad' => [
                                    'velocidad_10m', 'velocidad_20m', 'velocidad_30m'
                                ],
                                'Resistencia' => [
                                    'course_navette', 'vo2_max'
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

                            // Define performance categories for color coding
                            $performanceCategories = [
                                'bajo' => 'bg-red-200',
                                'promedio' => 'bg-yellow-200',
                                'alto' => 'bg-green-200'
                            ];

                            // Define metrics for radar chart and ranking
                            $radarMetrics = [
                                'sit_and_reach' => 'Sit & Reach',
                                'sentadilla_arranque' => 'Sentadilla Arranque',
                                'fuerza_agarre' => 'Fuerza de Agarre',
                                'potencia_piernas' => 'Potencia de Piernas',
                                'potencia_brazos' => 'Potencia de Brazos',
                                'velocidad' => 'Velocidad',
                                'resistencia' => 'Resistencia'
                            ];

                            // Mock ranking data (in a real application, this would come from the database)
                            $rankingData = [
                                'sit_and_reach' => 1,
                                'sentadilla_arranque' => 10,
                                'fuerza_agarre' => 8,
                                'potencia_piernas' => 6,
                                'potencia_brazos' => 10,
                                'velocidad' => 5,
                                'resistencia' => 12
                            ];
                        @endphp

                        <!-- Personal Data Table -->
                        <div class="mb-8 border border-gray-300">
                            <h3 class="text-lg font-semibold p-2 bg-gray-100 border-b border-gray-300 text-center">DATOS PERSONALES</h3>
                            <div class="grid grid-cols-2 p-2">
                                <div class="grid grid-cols-1 gap-2">
                                    <div>
                                        <span class="font-semibold">Nombre:</span>
                                        <span>{{ isset($report->athlete->athleteProfile) ? ($report->athlete->athleteProfile->first_name ?? '') . ' ' . ($report->athlete->athleteProfile->last_name ?? '') : '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold">Fecha Nacimiento:</span>
                                        <span>{{ isset($report->athlete->athleteProfile) && $report->athlete->athleteProfile->birth_date ? \Carbon\Carbon::parse($report->athlete->athleteProfile->birth_date)->format('d/m/Y') : '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold">Fecha de Evaluación:</span>
                                        <span>{{ $report->athlete->evaluation_date ? \Carbon\Carbon::parse($report->athlete->evaluation_date)->format('d/m/Y') : '-' }}</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-2">
                                    <div>
                                        <span class="font-semibold">Edad:</span>
                                        <span>{{ $report->athlete->age ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold">Sexo:</span>
                                        <span>{{ isset($report->athlete->athleteProfile) ? ($report->athlete->athleteProfile->gender == 'm' ? 'Masculino' : ($report->athlete->athleteProfile->gender == 'f' ? 'Femenino' : '-')) : '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold">Deporte:</span>
                                        <span>{{ $report->athlete->sport ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold">Grado:</span>
                                        <span>{{ $report->athlete->grade ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Anthropometry Table -->
                        <div class="mb-8 border border-gray-300">
                            <h3 class="text-lg font-semibold p-2 bg-gray-100 border-b border-gray-300 text-center">ANTROPOMETRÍA</h3>
                            <div class="grid grid-cols-2 p-2">
                                <div class="grid grid-cols-1 gap-2">
                                    <div>
                                        <span class="font-semibold">Peso:</span>
                                        <span>{{ $report->report_data['peso'] ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold">Talla Parado:</span>
                                        <span>{{ $report->report_data['talla_parado'] ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold">Talla Sentado:</span>
                                        <span>{{ $report->report_data['talla_sentado'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-2">
                                    <div>
                                        <span class="font-semibold">Envergadura:</span>
                                        <span>{{ $report->report_data['envergadura'] ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold">Índice Córmico:</span>
                                        <span>{{ $report->report_data['indice_cormico'] ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold">PHV:</span>
                                        <span>{{ $report->report_data['phv'] ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Laterality Table -->
                        <div class="mb-8 border border-gray-300">
                            <h3 class="text-lg font-semibold p-2 bg-gray-100 border-b border-gray-300 text-center">LATERALIDAD</h3>
                            <div class="grid grid-cols-5 p-2 text-center">
                                <div class="font-semibold">Ojo</div>
                                <div class="font-semibold">Hombro</div>
                                <div class="font-semibold">Mano</div>
                                <div class="font-semibold">Cadera</div>
                                <div class="font-semibold">Pie</div>
                                <div>{{ $report->report_data['ojo'] ?? 'Derecho' }}</div>
                                <div>{{ $report->report_data['hombro'] ?? 'Derecho' }}</div>
                                <div>{{ $report->report_data['mano'] ?? 'Derecha' }}</div>
                                <div>{{ $report->report_data['cadera'] ?? 'Derecha' }}</div>
                                <div>{{ $report->report_data['pie'] ?? 'Derecho' }}</div>
                            </div>
                        </div>

                        <!-- Results Table -->
                        <div class="mb-8 border border-gray-300">
                            <h3 class="text-lg font-semibold p-2 bg-gray-100 border-b border-gray-300 text-center">RESULTADOS GENERALES</h3>
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="p-2 text-left">Evaluación</th>
                                        <th class="p-2 text-center bg-red-200">Bajo</th>
                                        <th class="p-2 text-center bg-yellow-200">Promedio</th>
                                        <th class="p-2 text-center bg-green-200">Alto</th>
                                        <th class="p-2 text-center">Tu Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $resultFields = [
                                            'sit_and_reach' => 'Sit & Reach',
                                            'sentadilla_arranque' => 'Sentadilla Arranque',
                                            'fuerza_agarre' => 'Fuerza de Agarre',
                                            'potencia_piernas' => 'Potencia de Piernas',
                                            'potencia_brazos' => 'Potencia de Brazos',
                                            'velocidad_10m' => 'Velocidad 10m',
                                            'velocidad_20m' => 'Velocidad 20m',
                                            'velocidad_30m' => 'Velocidad 30m',
                                            'course_navette' => 'Course Navette',
                                            'vo2_max' => 'VO2 Max'
                                        ];
                                        
                                        // Rangos de categorías de rendimiento (ejemplo)
                                        $performanceRanges = [
                                            'sit_and_reach' => ['bajo' => 0, 'promedio' => 5, 'alto' => 10],
                                            'sentadilla_arranque' => ['bajo' => 0, 'promedio' => 10, 'alto' => 20],
                                            // Agregar rangos para otros campos si es necesario
                                        ];
                                    @endphp
                                    
                                    @foreach($resultFields as $key => $label)
                                        @if(isset($report->report_data[$key]) && is_numeric($report->report_data[$key]))
                                            <tr class="border-b">
                                                <td class="p-2">{{ $label }}</td>
                                                <td class="p-2 text-center bg-red-100">
                                                    @php
                                                        $category = '';
                                                        $value = (float)$report->report_data[$key];
                                                        if (isset($performanceRanges[$key])) {
                                                            if ($value <= $performanceRanges[$key]['bajo']) {
                                                                $category = 'bajo';
                                                            } elseif ($value <= $performanceRanges[$key]['promedio']) {
                                                                $category = 'promedio';
                                                            } else {
                                                                $category = 'alto';
                                                            }
                                                        }
                                                    @endphp
                                                    {{ $category == 'bajo' ? 'X' : '' }}
                                                </td>
                                                <td class="p-2 text-center bg-yellow-100">{{ $category == 'promedio' ? 'X' : '' }}</td>
                                                <td class="p-2 text-center bg-green-100">{{ $category == 'alto' ? 'X' : '' }}</td>
                                                <td class="p-2 text-center">{{ $report->report_data[$key] }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Radar and Ranking Section -->
                        <div class="mb-8 grid grid-cols-2 gap-4">
                            <!-- Radar Chart -->
                            <div class="border border-gray-300">
                                <h3 class="text-lg font-semibold p-2 bg-gray-100 border-b border-gray-300 text-center">RADAR</h3>
                                <div class="p-4">
                                    @php
                                        $radarFieldsMap = [
                                            'sit_and_reach' => 'Sit & Reach',
                                            'sentadilla_arranque' => 'Sentadilla Arranque',
                                            'fuerza_agarre' => 'Fuerza de Agarre',
                                            'potencia_piernas' => 'Potencia de Piernas',
                                            'potencia_brazos' => 'Potencia de Brazos',
                                            'velocidad_10m' => 'Velocidad 10m',
                                            'velocidad_20m' => 'Velocidad 20m',
                                            'velocidad_30m' => 'Velocidad 30m',
                                            'course_navette' => 'Course Navette',
                                            'vo2_max' => 'VO2 Max',
                                            'abalakov_altura_cm' => 'Abalakov Altura',
                                            'cmj_altura_cm' => 'CMJ Altura',
                                            'reaccion_oculo_manual' => 'Reacción Óculo Manual',
                                            'reaccion_oculo_podal' => 'Reacción Óculo Podal'
                                        ];
                                        
                                        // Obtener campos seleccionados para el radar, o usar predeterminados si no hay
                                        $selectedRadarFields = $report->report_data['radar_fields'] ?? [];
                                        
                                        // Filtrar solo los campos que existen y tienen valores numéricos
                                        $validRadarFields = [];
                                        foreach ($selectedRadarFields as $field) {
                                            if (isset($report->report_data[$field]) && is_numeric($report->report_data[$field])) {
                                                $validRadarFields[] = $field;
                                            }
                                        }
                                        
                                        // Si no hay campos válidos, buscar cualquier campo numérico en los datos
                                        if (empty($validRadarFields)) {
                                            foreach ($radarFieldsMap as $key => $label) {
                                                if (isset($report->report_data[$key]) && is_numeric($report->report_data[$key])) {
                                                    $validRadarFields[] = $key;
                                                }
                                            }
                                        }
                                        
                                        // Preparar datos para el gráfico
                                        $radarData = [];
                                        $radarLabels = [];
                                        
                                        foreach ($validRadarFields as $field) {
                                            $radarData[] = floatval($report->report_data[$field]);
                                            $radarLabels[] = $radarFieldsMap[$field] ?? $field;
                                        }
                                        
                                        // Verificar si hay suficientes datos para el gráfico (mínimo 3 puntos)
                                        $hasEnoughData = count($validRadarFields) >= 3;
                                    @endphp
                                    
                                    @if($hasEnoughData)
                                        <p class="mb-2">Resultados seleccionados:</p>
                                        <ul class="list-disc pl-5 mb-4">
                                            @foreach($validRadarFields as $field)
                                                <li>{{ $radarFieldsMap[$field] ?? $field }}: {{ $report->report_data[$field] ?? '-' }}</li>
                                            @endforeach
                                        </ul>
                                        
                                        <!-- Gráfico radar -->
                                        <div class="w-full h-64 mt-4">
                                            <canvas id="radarChart"></canvas>
                                        </div>
                                        
                                        <!-- Información de depuración sobre datos del radar -->
                                        <div class="bg-gray-100 p-2 mt-4 text-xs">
                                            <details>
                                                <summary class="cursor-pointer font-bold">Información de Depuración</summary>
                                                <p>Campos Válidos del Radar: {{ count($validRadarFields) }}</p>
                                                <p>Puntos de Datos: {{ count($radarData) }}</p>
                                                <pre class="overflow-auto max-h-40">{{ json_encode(['etiquetas' => $radarLabels, 'datos' => $radarData], JSON_PRETTY_PRINT) }}</pre>
                                            </details>
                                        </div>
                                        
                                        <!-- Incluir Chart.js desde CDN -->
                                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                        
                                        <!-- Script para generar el gráfico radar -->
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                // Registrar información de depuración en la consola
                                                console.log('Depuración del Gráfico Radar:');
                                                console.log('Etiquetas:', @json($radarLabels));
                                                console.log('Datos:', @json($radarData));
                                                console.log('Campos Válidos:', @json($validRadarFields));
                                                console.log('Datos Brutos del Informe:', @json($report->report_data));
                                                
                                                try {
                                                    var ctx = document.getElementById('radarChart').getContext('2d');
                                                    
                                                    var data = {
                                                        labels: @json($radarLabels),
                                                        datasets: [{
                                                            label: 'Resultados del atleta',
                                                            data: @json($radarData),
                                                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                                            borderColor: 'rgb(54, 162, 235)',
                                                            pointBackgroundColor: 'rgb(54, 162, 235)',
                                                            pointBorderColor: '#fff',
                                                            pointHoverBackgroundColor: '#fff',
                                                            pointHoverBorderColor: 'rgb(54, 162, 235)'
                                                        }]
                                                    };
                                                    
                                                    var radarChart = new Chart(ctx, {
                                                        type: 'radar',
                                                        data: data,
                                                        options: {
                                                            elements: {
                                                                line: {
                                                                    borderWidth: 3
                                                                }
                                                            },
                                                            scales: {
                                                                r: {
                                                                    angleLines: {
                                                                        display: true
                                                                    },
                                                                    suggestedMin: 0
                                                                }
                                                            }
                                                        }
                                                    });
                                                } catch (error) {
                                                    console.error('Error al crear el gráfico radar:', error);
                                                    document.getElementById('radarChart').parentNode.innerHTML += 
                                                        '<div class="text-red-600 mt-2">Error: ' + error.message + '</div>';
                                                }
                                            });
                                        </script>
                                    @else
                                         <div class="text-yellow-600 text-center p-4">
                                            <p>No hay suficientes datos numéricos para generar el gráfico radar.</p>
                                            <p class="mt-2">Edite el informe para agregar más datos y seleccionar los campos para el gráfico.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Ranking Table -->
                            <div class="border border-gray-300">
                                <h3 class="text-lg font-semibold p-2 bg-gray-100 border-b border-gray-300 text-center">RANKING</h3>
                                <div class="p-4">
                                    <table class="min-w-full">
                                        <thead>
                                            <tr class="border-b">
                                                <th class="p-2 text-left">Evaluación</th>
                                                <th class="p-2 text-center">Posición</th>
                                                <th class="p-2 text-center">Total</th>
                                                <th class="p-2 text-center">Percentil</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($validRadarFields as $field)
                                                @php
                                                    $rankInfo = $rankings[$field] ?? null;
                                                    $rankStyle = '';
                                                    
                                                    // Color coding based on percentile
                                                    if ($rankInfo) {
                                                        if ($rankInfo['percentile'] >= 80) {
                                                            $rankStyle = 'bg-green-100';
                                                        } elseif ($rankInfo['percentile'] >= 50) {
                                                            $rankStyle = 'bg-blue-100';
                                                        } elseif ($rankInfo['percentile'] >= 30) {
                                                            $rankStyle = 'bg-yellow-100';
                                                        } else {
                                                            $rankStyle = 'bg-red-100';
                                                        }
                                                    }
                                                @endphp
                                                <tr class="border-b {{ $rankStyle }}">
                                                    <td class="p-2">{{ $radarFieldsMap[$field] ?? $field }}</td>
                                                    @if($rankInfo)
                                                        <td class="p-2 text-center font-bold">{{ $rankInfo['rank'] }}</td>
                                                        <td class="p-2 text-center">{{ $rankInfo['total'] }}</td>
                                                        <td class="p-2 text-center">{{ $rankInfo['percentile'] }}%</td>
                                                    @else
                                                        <td class="p-2 text-center" colspan="3">Sin datos suficientes</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Sports Orientation Section -->
                        <div class="mb-8 border border-gray-300">
                            <h3 class="text-lg font-semibold p-2 bg-gray-100 border-b border-gray-300 text-center">Orientación Deportiva - Detección de Talentos</h3>
                            <div class="p-4">
                                <p>En base a las características físicas evaluadas recomendamos la práctica de los siguientes deportes:</p>
                                <div class="mt-4">
                                    <textarea class="w-full p-2 border border-gray-300 rounded" rows="4" readonly>{{ $report->report_data['orientacion_deportiva'] ?? 'No hay recomendaciones disponibles.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Display any remaining fields that weren't categorized --}}
                        @php
                            // Obtener todas las claves de las categorías
                            $categorizedKeys = [];
                            foreach ($categories as $categoryFields) {
                                $categorizedKeys = array_merge($categorizedKeys, $categoryFields);
                            }
                            
                            // Agregar orientacion_deportiva y radar_fields a las claves categorizadas
                            $categorizedKeys[] = 'orientacion_deportiva';
                            $categorizedKeys[] = 'radar_fields';
                            
                            // Filtrar campos no categorizados
                            $uncategorizedData = collect($report->report_data)
                                ->filter(function($value, $key) use ($categorizedKeys) {
                                    return !in_array($key, $categorizedKeys);
                                });
                        @endphp

                        @if($uncategorizedData->isNotEmpty())
                            <div class="mb-8 border border-gray-300">
                                <h3 class="text-lg font-semibold p-2 bg-gray-100 border-b border-gray-300 text-center">Datos Adicionales</h3>
                                <div class="p-4">
                                    <table class="min-w-full">
                                        <thead>
                                            <tr class="border-b">
                                                <th class="p-2 text-left">Campo</th>
                                                <th class="p-2 text-left">Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($uncategorizedData as $key => $value)
                                                <tr class="border-b">
                                                    <td class="p-2">{{ $fieldLabels[$key] ?? $key }}</td>
                                                    <td class="p-2">{{ $value }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>No hay datos adicionales disponibles para este informe.</p>
                        </div>
                    @endif

                    <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
                        <p>Informe generado el {{ $report->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
