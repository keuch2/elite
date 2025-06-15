<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Evaluación: {{ $evaluation->first_name }} {{ $evaluation->last_name }} - {{ date('d/m/Y', strtotime($evaluation->evaluation_date)) }}
            </h2>
            <div class="flex space-x-2">
                @php
                    $athleteId = $evaluation->id ?? null;
                @endphp
                @if($athleteId)
                <a href="{{ route('reports.athlete', ['athleteId' => $athleteId]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    {{ __('Ver Informes') }}
                </a>
                @else
                <span class="px-4 py-2 bg-gray-400 text-white rounded-md cursor-not-allowed">{{ __('Ver Informes') }}</span>
                @endif
                <a href="{{ route('athletes.show', ['id' => $evaluation->athlete_profile_id]) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    {{ __('Volver al Perfil') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Información Básica</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre Completo</p>
                            <p class="mt-1">{{ $evaluation->first_name }} {{ $evaluation->last_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Género</p>
                            <p class="mt-1">{{ $evaluation->gender == 'm' ? 'Masculino' : ($evaluation->gender == 'f' ? 'Femenino' : 'Otro') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de Nacimiento</p>
                            <p class="mt-1">{{ date('d/m/Y', strtotime($evaluation->birth_date)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Edad en la Evaluación</p>
                            <p class="mt-1">{{ $evaluation->age }} años</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Documento de Identidad</p>
                            <p class="mt-1">{{ $evaluation->identity_document ?? 'No especificado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Deporte</p>
                            <p class="mt-1">{{ $evaluation->sport ?? 'No especificado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Categoría</p>
                            <p class="mt-1">{{ $evaluation->category ?? 'No especificada' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Institución</p>
                            <p class="mt-1">{{ $evaluation->institution_name ?? 'No especificada' }}</p>
                        </div>
                    </div>

                    @if(isset($evaluation->weight) || isset($evaluation->standing_height) || isset($evaluation->sitting_height))
                        <h3 class="text-lg font-medium mb-4 mt-8 border-t pt-8">Datos Antropométricos</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Peso</p>
                                <p class="mt-1">{{ $evaluation->weight ?? '-' }} kg</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Altura de Pie</p>
                                <p class="mt-1">{{ $evaluation->standing_height ?? '-' }} cm</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Altura Sentado</p>
                                <p class="mt-1">{{ $evaluation->sitting_height ?? '-' }} cm</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Envergadura</p>
                                <p class="mt-1">{{ $evaluation->wingspan ?? '-' }} cm</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Índice Córmico</p>
                                <p class="mt-1">{{ $evaluation->cormic_index ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">PHV</p>
                                <p class="mt-1">{{ $evaluation->phv ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Suma de Pliegues</p>
                                <p class="mt-1">{{ $evaluation->skinfold_sum ?? '-' }} mm</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">% Masa Grasa</p>
                                <p class="mt-1">{{ $evaluation->fat_mass_percentage ?? '-' }}%</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Masa Grasa</p>
                                <p class="mt-1">{{ $evaluation->fat_mass_kg ?? '-' }} kg</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">% Masa Muscular</p>
                                <p class="mt-1">{{ $evaluation->muscle_mass_percentage ?? '-' }}%</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Masa Muscular</p>
                                <p class="mt-1">{{ $evaluation->muscle_mass_kg ?? '-' }} kg</p>
                            </div>
                        </div>
                    @else
                        <div class="mt-8 border-t pt-8">
                            <p class="text-gray-500 dark:text-gray-400">No hay datos antropométricos disponibles para esta evaluación.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>