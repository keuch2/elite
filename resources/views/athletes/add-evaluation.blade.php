<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add New Evaluation for') }}: {{ $athlete->first_name }} {{ $athlete->last_name }}
            </h2>
            <a href="{{ route('athletes.show', $athlete->id) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                {{ __('Back to Profile') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('athletes.store-evaluation', $athlete->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-8">
                            <h3 class="text-lg font-medium mb-4">Basic Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="evaluation_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Evaluation Date <span class="text-red-600">*</span></label>
                                    <input type="date" name="evaluation_date" id="evaluation_date" value="{{ old('evaluation_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                                    @error('evaluation_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="age" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Age <span class="text-red-600">*</span></label>
                                    <input type="number" name="age" id="age" value="{{ old('age') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                                    @error('age')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="grade" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grade</label>
                                    <input type="text" name="grade" id="grade" value="{{ old('grade') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    @error('grade')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="sport" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sport</label>
                                    <input type="text" name="sport" id="sport" value="{{ old('sport', $athlete->sport) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    @error('sport')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                                    <input type="text" name="category" id="category" value="{{ old('category', $athlete->category) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    @error('category')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-8">
                            <h3 class="text-lg font-medium mb-4 border-t pt-4">Anthropometric Data</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="anthropometric_data[weight]" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Weight (kg)</label>
                                    <input type="number" name="anthropometric_data[weight]" id="anthropometric_data[weight]" value="{{ old('anthropometric_data.weight') }}" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                
                                <div>
                                    <label for="anthropometric_data[standing_height]" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Standing Height (cm)</label>
                                    <input type="number" name="anthropometric_data[standing_height]" id="anthropometric_data[standing_height]" value="{{ old('anthropometric_data.standing_height') }}" step="0.1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                
                                <div>
                                    <label for="anthropometric_data[sitting_height]" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sitting Height (cm)</label>
                                    <input type="number" name="anthropometric_data[sitting_height]" id="anthropometric_data[sitting_height]" value="{{ old('anthropometric_data.sitting_height') }}" step="0.1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                
                                <div>
                                    <label for="anthropometric_data[wingspan]" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Wingspan (cm)</label>
                                    <input type="number" name="anthropometric_data[wingspan]" id="anthropometric_data[wingspan]" value="{{ old('anthropometric_data.wingspan') }}" step="0.1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                
                                <div>
                                    <label for="anthropometric_data[cormic_index]" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cormic Index</label>
                                    <input type="number" name="anthropometric_data[cormic_index]" id="anthropometric_data[cormic_index]" value="{{ old('anthropometric_data.cormic_index') }}" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                
                                <div>
                                    <label for="anthropometric_data[phv]" class="block text-sm font-medium text-gray-700 dark:text-gray-300">PHV</label>
                                    <input type="number" name="anthropometric_data[phv]" id="anthropometric_data[phv]" value="{{ old('anthropometric_data.phv') }}" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                
                                <div>
                                    <label for="anthropometric_data[skinfold_sum]" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Skinfold Sum (mm)</label>
                                    <input type="number" name="anthropometric_data[skinfold_sum]" id="anthropometric_data[skinfold_sum]" value="{{ old('anthropometric_data.skinfold_sum') }}" step="0.1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                
                                <div>
                                    <label for="anthropometric_data[fat_mass_percentage]" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fat Mass (%)</label>
                                    <input type="number" name="anthropometric_data[fat_mass_percentage]" id="anthropometric_data[fat_mass_percentage]" value="{{ old('anthropometric_data.fat_mass_percentage') }}" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                
                                <div>
                                    <label for="anthropometric_data[fat_mass_kg]" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fat Mass (kg)</label>
                                    <input type="number" name="anthropometric_data[fat_mass_kg]" id="anthropometric_data[fat_mass_kg]" value="{{ old('anthropometric_data.fat_mass_kg') }}" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                
                                <div>
                                    <label for="anthropometric_data[muscle_mass_percentage]" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Muscle Mass (%)</label>
                                    <input type="number" name="anthropometric_data[muscle_mass_percentage]" id="anthropometric_data[muscle_mass_percentage]" value="{{ old('anthropometric_data.muscle_mass_percentage') }}" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                                
                                <div>
                                    <label for="anthropometric_data[muscle_mass_kg]" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Muscle Mass (kg)</label>
                                    <input type="number" name="anthropometric_data[muscle_mass_kg]" id="anthropometric_data[muscle_mass_kg]" value="{{ old('anthropometric_data.muscle_mass_kg') }}" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                {{ __('Save Evaluation') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>