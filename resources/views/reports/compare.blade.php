<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Comparación de Informes
            </h2>
            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                {{ __('Volver') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($reportsByTemplate->isEmpty())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                No hay informes disponibles para comparar.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                @foreach($reportsByTemplate as $templateId => $reports)
                    @php
                        $template = $reports->first()->template;
                        $athlete = $reports->first()->athlete;
                        $profileFields = ['nombre', 'apellido', 'documento_de_identidad', 'fecha_de_nacimiento', 'edad', 'grado', 'deporte', 'categoria', 'institucion'];
                    @endphp
                    
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-medium mb-4">Plantilla: {{ $template->name }}</h3>
                            <p class="mb-4">Atleta: {{ $athlete->first_name }} {{ $athlete->last_name }}</p>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Campo</th>
                                            @foreach($reports as $report)
                                                <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">
                                                    {{ date('d/m/Y', strtotime($report->athlete->evaluation_date)) }}
                                                </th>
                                            @endforeach
                                            <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Diferencia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($template->fields && is_array($template->fields))
                                            @foreach($template->fields as $category => $fields)
                                                <tr>
                                                    <td colspan="{{ $reports->count() + 2 }}" class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 font-medium">
                                                        {{ $category }}
                                                    </td>
                                                </tr>
                                                
                                                @foreach($fields as $field)
                                                    <tr>
                                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                                            {{ str_replace('_', ' ', ucfirst($field)) }}
                                                        </td>
                                                        
                                                        @php
                                                            $values = [];
                                                            $isNumeric = true;
                                                            
                                                            // Collect values and check if they're numeric
                                                            foreach($reports as $report) {
                                                                $value = $report->report_data[$field] ?? '-';
                                                                $values[] = $value;
                                                                
                                                                if($value !== '-' && !is_numeric(str_replace(',', '.', $value))) {
                                                                    $isNumeric = false;
                                                                }
                                                            }
                                                            
                                                            // Calculate difference for numeric values
                                                            $difference = '-';
                                                            if($isNumeric && count($values) >= 2 && $values[0] !== '-' && $values[count($values)-1] !== '-') {
                                                                $firstValue = (float) str_replace(',', '.', $values[0]);
                                                                $lastValue = (float) str_replace(',', '.', $values[count($values)-1]);
                                                                $difference = $lastValue - $firstValue;
                                                                
                                                                // Format the difference
                                                                if($difference > 0) {
                                                                    $difference = '+' . number_format($difference, 2);
                                                                } else {
                                                                    $difference = number_format($difference, 2);
                                                                }
                                                            }
                                                        @endphp
                                                        
                                                        @foreach($values as $index => $value)
                                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                                                {{ $value }}
                                                            </td>
                                                        @endforeach
                                                        
                                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 
                                                            @if($difference !== '-')
                                                                @if(strpos($difference, '+') === 0)
                                                                    text-green-600 dark:text-green-400
                                                                @elseif(strpos($difference, '-') === 0)
                                                                    text-red-600 dark:text-red-400
                                                                @else
                                                                    text-gray-600 dark:text-gray-400
                                                                @endif
                                                            @endif
                                                        ">
                                                            {{ $difference }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
