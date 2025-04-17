<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Evaluation: {{ $evaluation->first_name }} {{ $evaluation->last_name }} - {{ date('d/m/Y', strtotime($evaluation->evaluation_date)) }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('athletes.show', $evaluation->id) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    {{ __('Back to Profile') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Basic Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Evaluation Date</p>
                            <p class="mt-1">{{ date('d/m/Y', strtotime($evaluation->evaluation_date)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Age</p>
                            <p class="mt-1">{{ $evaluation->age }} years</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Grade</p>
                            <p class="mt-1">{{ $evaluation->grade ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sport</p>
                            <p class="mt-1">{{ $evaluation->sport ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</p>
                            <p class="mt-1">{{ $evaluation->category ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Institution</p>
                            <p class="mt-1">{{ $evaluation->institution->name ?? 'Not specified' }}</p>
                        </div>
                    </div>

                    @if($evaluation->anthropometricData)
                        <h3 class="text-lg font-medium mb-4 mt-8 border-t pt-8">Anthropometric Data</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Weight</p>
                                <p class="mt-1">{{ $evaluation->anthropometricData->weight ?? '-' }} kg</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Standing Height</p>
                                <p class="mt-1">{{ $evaluation->anthropometricData->standing_height ?? '-' }} cm</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sitting Height</p>
                                <p class="mt-1">{{ $evaluation->anthropometricData->sitting_height ?? '-' }} cm</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Wingspan</p>
                                <p class="mt-1">{{ $evaluation->anthropometricData->wingspan ?? '-' }} cm</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Cormic Index</p>
                                <p class="mt-1">{{ $evaluation->anthropometricData->cormic_index ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">PHV</p>
                                <p class="mt-1">{{ $evaluation->anthropometricData->phv ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Skinfold Sum</p>
                                <p class="mt-1">{{ $evaluation->anthropometricData->skinfold_sum ?? '-' }} mm</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Fat Mass %</p>
                                <p class="mt-1">{{ $evaluation->anthropometricData->fat_mass_percentage ?? '-' }}%</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Fat Mass</p>
                                <p class="mt-1">{{ $evaluation->anthropometricData->fat_mass_kg ?? '-' }} kg</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Muscle Mass %</p>
                                <p class="mt-1">{{ $evaluation->anthropometricData->muscle_mass_percentage ?? '-' }}%</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Muscle Mass</p>
                                <p class="mt-1">{{ $evaluation->anthropometricData->muscle_mass_kg ?? '-' }} kg</p>
                            </div>
                        </div>
                    @else
                        <div class="mt-8 border-t pt-8">
                            <p class="text-gray-500 dark:text-gray-400">No anthropometric data available for this evaluation.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>