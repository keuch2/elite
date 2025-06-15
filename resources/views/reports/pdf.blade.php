<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Evaluación Deportiva</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #2563eb;
        }
        .header h2 {
            font-size: 20px;
            margin-top: 0;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .section {
            margin-bottom: 30px;
            border: 1px solid #ddd;
        }
        .section-header {
            background-color: #f3f4f6;
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        .section-header h3 {
            margin: 0;
            font-size: 18px;
            color: #1e40af;
        }
        .section-content {
            padding: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table.data-table td {
            padding: 8px;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .grid-5 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
            gap: 10px;
            text-align: center;
        }
        .label {
            font-weight: bold;
            margin-right: 5px;
        }
        .data-row {
            margin-bottom: 10px;
        }
        .results-table th, .results-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .results-table th {
            background-color: #f3f4f6;
        }
        .low {
            background-color: #fecaca;
        }
        .medium {
            background-color: #fef3c7;
        }
        .high {
            background-color: #d1fae5;
        }
        .radar-chart {
            width: 100%;
            height: 300px;
            background-color: #f9fafb;
            border: 1px dashed #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 15px;
        }
        .ranking-table th, .ranking-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .ranking-table th {
            background-color: #f3f4f6;
            text-align: left;
        }
        .ranking-table td:last-child {
            text-align: center;
        }
        .recommendations {
            width: 100%;
            min-height: 100px;
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 15px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Informe de Evaluación Deportiva</h1>
            @if($report->athlete && $report->athlete->athlete_profile)
                <h2>{{ ($report->athlete->athlete_profile->first_name ?? '') . ' ' . ($report->athlete->athlete_profile->last_name ?? '') }}</h2>
            @endif
            @if($template)
                <p>Plantilla: {{ $template->name }}</p>
            @endif
        </div>

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
        <div class="section">
            <div class="section-header">
                <h3>DATOS PERSONALES</h3>
            </div>
            <div class="section-content">
                <div class="grid-2">
                    <div>
                        <div class="data-row">
                            <span class="label">Nombre:</span>
                            <span>{{ isset($report->athlete->athlete_profile) ? ($report->athlete->athlete_profile->first_name ?? '') . ' ' . ($report->athlete->athlete_profile->last_name ?? '') : '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="label">Fecha Nacimiento:</span>
                            <span>{{ isset($report->athlete->athlete_profile) && $report->athlete->athlete_profile->birth_date ? \Carbon\Carbon::parse($report->athlete->athlete_profile->birth_date)->format('d/m/Y') : '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="label">Fecha de Evaluación:</span>
                            <span>{{ $report->athlete->evaluation_date ? \Carbon\Carbon::parse($report->athlete->evaluation_date)->format('d/m/Y') : '-' }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="data-row">
                            <span class="label">Edad:</span>
                            <span>{{ $report->athlete->age ?? '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="label">Sexo:</span>
                            <span>{{ isset($report->athlete->athlete_profile) ? ($report->athlete->athlete_profile->gender == 'm' ? 'Masculino' : ($report->athlete->athlete_profile->gender == 'f' ? 'Femenino' : '-')) : '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="label">Deporte:</span>
                            <span>{{ $report->athlete->sport ?? '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="label">Grado:</span>
                            <span>{{ $report->athlete->grade ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Anthropometry Table -->
        <div class="section">
            <div class="section-header">
                <h3>ANTROPOMETRÍA</h3>
            </div>
            <div class="section-content">
                <div class="grid-2">
                    <div>
                        <div class="data-row">
                            <span class="label">Peso:</span>
                            <span>{{ $report->report_data['peso'] ?? '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="label">Talla Parado:</span>
                            <span>{{ $report->report_data['talla_parado'] ?? '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="label">Talla Sentado:</span>
                            <span>{{ $report->report_data['talla_sentado'] ?? '-' }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="data-row">
                            <span class="label">Envergadura:</span>
                            <span>{{ $report->report_data['envergadura'] ?? '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="label">Índice Córmico:</span>
                            <span>{{ $report->report_data['indice_cormico'] ?? '-' }}</span>
                        </div>
                        <div class="data-row">
                            <span class="label">PHV:</span>
                            <span>{{ $report->report_data['phv'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laterality Table -->
        <div class="section">
            <div class="section-header">
                <h3>LATERALIDAD</h3>
            </div>
            <div class="section-content">
                <div class="grid-5">
                    <div><strong>Ojo</strong></div>
                    <div><strong>Hombro</strong></div>
                    <div><strong>Mano</strong></div>
                    <div><strong>Cadera</strong></div>
                    <div><strong>Pie</strong></div>
                    <div>{{ $report->report_data['ojo'] ?? 'Derecho' }}</div>
                    <div>{{ $report->report_data['hombro'] ?? 'Derecho' }}</div>
                    <div>{{ $report->report_data['mano'] ?? 'Derecha' }}</div>
                    <div>{{ $report->report_data['cadera'] ?? 'Derecha' }}</div>
                    <div>{{ $report->report_data['pie'] ?? 'Derecho' }}</div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="section">
            <div class="section-header">
                <h3>RESULTADOS GENERALES</h3>
            </div>
            <div class="section-content">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Evaluación</th>
                            <th class="low">Bajo</th>
                            <th class="medium">Promedio</th>
                            <th class="high">Alto</th>
                            <th>Tu Score</th>
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
                                <tr>
                                    <td>{{ $label }}</td>
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
                                    <td class="low">{{ $category == 'bajo' ? 'X' : '' }}</td>
                                    <td class="medium">{{ $category == 'promedio' ? 'X' : '' }}</td>
                                    <td class="high">{{ $category == 'alto' ? 'X' : '' }}</td>
                                    <td>{{ $report->report_data[$key] }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Radar and Ranking Section -->
        <div class="grid-container">
            <!-- Radar Chart -->
            <div class="section">
                <div class="section-header">
                    <h3>RADAR</h3>
                </div>
                <div class="section-content">
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
                        
                        // Obtener campos seleccionados para el radar
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
                        
                        // Verificar si hay suficientes datos para el gráfico (mínimo 3 puntos)
                        $hasEnoughData = count($validRadarFields) >= 3;
                    @endphp
                    
                    @if($hasEnoughData)
                        <p>Resultados seleccionados:</p>
                        <ul>
                            @foreach($validRadarFields as $field)
                                <li>{{ $radarFieldsMap[$field] ?? $field }}: {{ $report->report_data[$field] ?? '-' }}</li>
                            @endforeach
                        </ul>
                        
                        <!-- En el PDF, incluiremos una visualización simple del radar -->
                        <div class="radar-chart">
                            <style>
                                /* Crear una visualización simple del radar para PDF */
                                .radar-circle {
                                    position: relative;
                                    width: 300px;
                                    height: 300px;
                                    border-radius: 50%;
                                    margin: 0 auto;
                                    border: 1px solid #ccc;
                                    overflow: hidden;
                                }
                                .radar-axis {
                                    position: absolute;
                                    top: 50%;
                                    left: 50%;
                                    width: 300px;
                                    height: 1px;
                                    background: #ddd;
                                    transform-origin: center left;
                                }
                                .radar-label {
                                    position: absolute;
                                    font-size: 12px;
                                    text-align: center;
                                    font-weight: bold;
                                }
                                .radar-point {
                                    position: absolute;
                                    width: 6px;
                                    height: 6px;
                                    border-radius: 50%;
                                    background: #3b82f6;
                                    transform: translate(-50%, -50%);
                                }
                                .radar-point-line {
                                    position: absolute;
                                    height: 1px;
                                    background: rgba(59, 130, 246, 0.5);
                                    transform-origin: left center;
                                }
                            </style>
                            
                            <div class="radar-circle">
                                @php
                                    $count = count($validRadarFields);
                                    $angle = 360 / $count;
                                @endphp
                                
                                @for($i = 0; $i < $count; $i++)
                                    <div class="radar-axis" style="transform: rotate({{ $i * $angle }}deg);"></div>
                                    <div class="radar-label" style="
                                        top: {{ 50 - 45 * sin(deg2rad($i * $angle)) }}%;
                                        left: {{ 50 + 45 * cos(deg2rad($i * $angle)) }}%;
                                    ">{{ $radarFieldsMap[$validRadarFields[$i]] ?? $validRadarFields[$i] }}</div>
                                @endfor
                                
                                <!-- Puntos que representan los valores aproximados -->
                                @for($i = 0; $i < $count; $i++)
                                    @php
                                        // Normalizar los valores a un rango de 0 a 1 para visualización simple
                                        // En un caso real, necesitarías definir valores mínimos y máximos para cada campo
                                        $value = $report->report_data[$validRadarFields[$i]];
                                        $normalizedValue = min(max($value / 100, 0.1), 0.9); // Asegurar que está entre 0.1 y 0.9
                                        $distance = $normalizedValue * 45; // Distancia desde el centro (45% del radio)
                                        
                                        $pointX = 50 + $distance * cos(deg2rad($i * $angle));
                                        $pointY = 50 - $distance * sin(deg2rad($i * $angle));
                                    @endphp
                                    <div class="radar-point" style="top: {{ $pointY }}%; left: {{ $pointX }}%;"></div>
                                @endfor
                            </div>
                            <p style="text-align: center; margin-top: 10px;">Gráfico representativo - Valores exactos en la lista</p>
                        </div>
                    @else
                        <p style="text-align: center; color: #b45309;">No hay suficientes datos numéricos para generar el gráfico radar.</p>
                    @endif
                </div>
            </div>
            
            <!-- Ranking Table -->
            <div class="section">
                <div class="section-header">
                    <h3>RANKING</h3>
                </div>
                <div class="section-content">
                    <table class="ranking-table">
                        <thead>
                            <tr>
                                <th>Evaluación</th>
                                <th>Ranking</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Definir datos de ranking (en un caso real, estos datos vendrían de la base de datos)
                                $rankingData = [
                                    'sit_and_reach' => 1,
                                    'sentadilla_arranque' => 10,
                                    'fuerza_agarre' => 8,
                                    'potencia_piernas' => 6,
                                    'potencia_brazos' => 10,
                                    'velocidad_10m' => 5,
                                    'velocidad_20m' => 7,
                                    'velocidad_30m' => 9,
                                    'course_navette' => 12,
                                    'vo2_max' => 3,
                                    'abalakov_altura_cm' => 4,
                                    'cmj_altura_cm' => 2,
                                    'reaccion_oculo_manual' => 11,
                                    'reaccion_oculo_podal' => 15
                                ];
                            @endphp
                            
                            @foreach($validRadarFields as $field)
                                <tr>
                                    <td>{{ $radarFieldsMap[$field] ?? $field }}</td>
                                    <td>{{ $rankingData[$field] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sports Orientation Section -->
        <div class="section">
            <div class="section-header">
                <h3>Orientación Deportiva - Detección de Talentos</h3>
            </div>
            <div class="section-content">
                <p>En base a las características físicas evaluadas recomendamos la práctica de los siguientes deportes:</p>
                <div class="recommendations">
                    {{ $report->report_data['orientacion_deportiva'] ?? 'No hay recomendaciones disponibles.' }}
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Informe generado el {{ $report->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
