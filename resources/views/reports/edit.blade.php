<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editar Informe') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('reports.show', $report->id) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    {{ __('Cancelar') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form action="{{ route('reports.update', $report->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-8 text-center">
                            <h1 class="text-2xl font-bold mb-2">Editar Informe de Evaluación Deportiva</h1>
                            @if($report->athlete && $report->athlete->athleteProfile)
                                <h2 class="text-xl">
                                    {{ $report->athlete->athleteProfile->first_name ?? '' }} 
                                    {{ $report->athlete->athleteProfile->last_name ?? '' }}
                                </h2>
                            @endif
                            
                            <div class="mt-4">
                                <label for="template_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plantilla:</label>
                                <select id="template_id" name="template_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                    @foreach($templates as $templateOption)
                                        <option value="{{ $templateOption->id }}" {{ $template->id == $templateOption->id ? 'selected' : '' }}>
                                            {{ $templateOption->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if($report->report_data)
                            @php
                                // Categorizar campos de datos
                                $categories = [
                                    'Antropometría' => [
                                        'peso' => 'Peso',
                                        'talla_parado' => 'Talla Parado',
                                        'talla_sentado' => 'Talla Sentado',
                                        'envergadura' => 'Envergadura',
                                        'indice_cormico' => 'Índice Córmico',
                                        'phv' => 'PHV',
                                        'sumatoria_de_pliegues' => 'Sumatoria de Pliegues',
                                        'masa_adiposa_en_porcentaje' => 'Masa Adiposa (%)',
                                        'masa_adiposa_en_kg' => 'Masa Adiposa (kg)',
                                        'masa_muscular_en_porcentaje' => 'Masa Muscular (%)',
                                        'masa_muscular_en_kg' => 'Masa Muscular (kg)',
                                        'masa_residual_en_kg' => 'Masa Residual (kg)',
                                        'masa_osea_en_kg' => 'Masa Ósea (kg)'
                                    ],
                                    'Lateralidad' => [
                                        'ojo' => 'Ojo',
                                        'hombro' => 'Hombro',
                                        'mano' => 'Mano',
                                        'cadera' => 'Cadera',
                                        'pie' => 'Pie'
                                    ],
                                    'Movilidad' => [
                                        'sit_and_reach' => 'Sit & Reach',
                                        'movilidad_tobillo_derecha' => 'Movilidad Tobillo Derecha',
                                        'movilidad_tobillo_izquierda' => 'Movilidad Tobillo Izquierda'
                                    ],
                                    'Rendimiento' => [
                                        'sentadilla_arranque' => 'Sentadilla Arranque',
                                        'fuerza_agarre' => 'Fuerza de Agarre',
                                        'potencia_piernas' => 'Potencia de Piernas',
                                        'potencia_brazos' => 'Potencia de Brazos',
                                        'velocidad' => 'Velocidad',
                                        'resistencia' => 'Resistencia'
                                    ],
                                    'Orientación' => [
                                        'orientacion_deportiva' => 'Orientación Deportiva'
                                    ]
                                ];
                            @endphp

                            <!-- Sección de Antropometría -->
                            <div class="mb-8 border border-gray-300">
                                <h3 class="text-lg font-semibold p-2 bg-gray-100 border-b border-gray-300 text-center">ANTROPOMETRÍA</h3>
                                <div class="grid grid-cols-2 p-2">
                                    @foreach($categories['Antropometría'] as $key => $label)
                                        <div class="p-2">
                                            <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}:</label>
                                            <input type="text" id="{{ $key }}" name="report_data[{{ $key }}]" value="{{ $report->report_data[$key] ?? '' }}" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Sección de Lateralidad -->
                            <div class="mb-8 border border-gray-300">
                                <h3 class="text-lg font-semibold p-2 bg-gray-100 border-b border-gray-300 text-center">LATERALIDAD</h3>
                                <div class="grid grid-cols-2 p-2">
                                    @foreach($categories['Lateralidad'] as $key => $label)
                                        <div class="p-2">
                                            <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}:</label>
                                            <select id="{{ $key }}" name="report_data[{{ $key }}]" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                                <option value="Derecho" {{ ($report->report_data[$key] ?? '') == 'Derecho' ? 'selected' : '' }}>Derecho</option>
                                                <option value="Izquierdo" {{ ($report->report_data[$key] ?? '') == 'Izquierdo' ? 'selected' : '' }}>Izquierdo</option>
                                                <option value="Ambidiestro" {{ ($report->report_data[$key] ?? '') == 'Ambidiestro' ? 'selected' : '' }}>Ambidiestro</option>
                                            </select>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Sección de Movilidad -->
                            <div class="mb-8 border border-gray-300">
                                <h3 class="text-lg font-semibold p-2 bg-gray-100 border-b border-gray-300 text-center">MOVILIDAD</h3>
                                <div class="grid grid-cols-2 p-2">
                                    @foreach($categories['Movilidad'] as $key => $label)
                                        <div class="p-2">
                                            <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}:</label>
                                            <input type="text" id="{{ $key }}" name="report_data[{{ $key }}]" value="{{ $report->report_data[$key] ?? '' }}" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Sección de Rendimiento -->
                            <div class="mb-8 border border-gray-300">
                                <h3 class="text-lg font-semibold p-2 bg-gray-100 border-b border-gray-300 text-center">RENDIMIENTO</h3>
                                <div class="grid grid-cols-2 p-2">
                                    @foreach($categories['Rendimiento'] as $key => $label)
                                        <div class="p-2">
                                            <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}:</label>
                                            <input type="text" id="{{ $key }}" name="report_data[{{ $key }}]" value="{{ $report->report_data[$key] ?? '' }}" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Sección de Orientación Deportiva -->
                            <div class="mb-8 border border-gray-300">
                                <h3 class="text-lg font-semibold p-2 bg-gray-100 border-b border-gray-300 text-center">Orientación Deportiva - Detección de Talentos</h3>
                                <div class="p-4">
                                    <p>En base a las características físicas evaluadas recomendamos la práctica de los siguientes deportes:</p>
                                    <div class="mt-4">
                                        <textarea name="report_data[orientacion_deportiva]" class="w-full p-2 border border-gray-300 rounded" rows="4">{{ $report->report_data['orientacion_deportiva'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección de Configuración de Gráfico Radar -->
                            <div class="mb-8 border border-gray-300">
                                <h3 class="text-lg font-semibold p-2 bg-gray-100 border-b border-gray-300 text-center">Configuración de Gráfico Radar</h3>
                                <div class="p-4">
                                    <p class="mb-4">Seleccione los datos que desea mostrar en el gráfico radar:</p>
                                    <div class="grid grid-cols-2 gap-4">
                                        @php
                                            // Definir todos los campos posibles para el radar
                                            $radarFields = [
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
                                            
                                            // Filtrar solo los campos que existen en los datos del informe y tienen valor numérico
                                            $availableRadarFields = [];
                                            foreach ($radarFields as $key => $label) {
                                                if (isset($report->report_data[$key]) && is_numeric($report->report_data[$key])) {
                                                    $availableRadarFields[$key] = $label;
                                                }
                                            }
                                            
                                            // Recuperar campos seleccionados o establecer predeterminados
                                            $selectedRadarFields = $report->report_data['radar_fields'] ?? array_keys($availableRadarFields);
                                            
                                            // Si no hay suficientes datos numéricos, mostrar un mensaje
                                            $hasNumericData = !empty($availableRadarFields);
                                        @endphp
                                        
                                        @if($hasNumericData)
                                            @foreach($availableRadarFields as $key => $label)
                                                <div class="flex items-center">
                                                    <input type="checkbox" id="radar_{{ $key }}" name="report_data[radar_fields][]" value="{{ $key }}" 
                                                        {{ in_array($key, $selectedRadarFields) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <label for="radar_{{ $key }}" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                        {{ $label }} ({{ $report->report_data[$key] }})
                                                    </label>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-span-2 text-yellow-600">
                                                <p>No hay suficientes datos numéricos disponibles para generar un gráfico radar. 
                                                Agregue valores numéricos en los campos de rendimiento.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Verificar campos adicionales no incluidos en las categorías anteriores --}}
                            @php
                                $categorizedKeys = collect($categories)->flatten()->keys()->toArray();
                                $categorizedKeys[] = 'orientacion_deportiva';
                                $categorizedKeys[] = 'radar_fields';
                                $uncategorizedData = collect($report->report_data)
                                    ->filter(function($value, $key) use ($categorizedKeys) {
                                        return !in_array($key, $categorizedKeys);
                                    });
                            @endphp

                            @if($uncategorizedData->isNotEmpty())
                                <div class="mb-8 border border-gray-300">
                                    <h3 class="text-lg font-semibold p-2 bg-gray-100 border-b border-gray-300 text-center">Datos Adicionales</h3>
                                    <div class="p-2 grid grid-cols-2">
                                        @foreach($uncategorizedData as $key => $value)
                                            <div class="p-2">
                                                <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $key }}:</label>
                                                <input type="text" id="{{ $key }}" name="report_data[{{ $key }}]" value="{{ $value }}" 
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <p>No hay datos disponibles para editar en este informe.</p>
                            </div>
                        @endif

                        <div class="mt-8 flex justify-center space-x-4">
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                {{ __('Guardar Cambios') }}
                            </button>
                            <a href="{{ route('reports.show', $report->id) }}" class="px-6 py-3 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 