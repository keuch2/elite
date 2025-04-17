<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $athlete->first_name }} {{ $athlete->last_name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('athletes.add-evaluation', $athlete->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    {{ __('Add New Evaluation') }}
                </a>
                <a href="{{ route('athletes.edit', $athlete->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                    {{ __('Edit Profile') }}
                </a>
                <a href="{{ route('athletes.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    {{ __('Back to Athletes') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h3 class="text-lg font-medium mb-4">Athlete Profile</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</p>
                            <p class="mt-1">{{ $athlete->first_name }} {{ $athlete->last_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender</p>
                            <p class="mt-1">{{ $athlete->gender == 'm' ? 'Male' : ($athlete->gender == 'f' ? 'Female' : 'Other') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Document</p>
                            <p class="mt-1">{{ $athlete->identity_document ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Birth Date</p>
                            <p class="mt-1">{{ $athlete->birth_date ? date('d/m/Y', strtotime($athlete->birth_date)) : 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Father's Name</p>
                            <p class="mt-1">{{ $athlete->father_name ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Mother's Name</p>
                            <p class="mt-1">{{ $athlete->mother_name ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Institution</p>
                            <p class="mt-1">{{ $athlete->institution->name ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Evaluations History</h3>
                        @if($evaluations->count() > 1)
                            <form action="{{ route('athletes.compare') }}" method="GET" class="flex items-center">
                                <input type="hidden" name="evaluations" id="selected-evaluations" value="">
                                <button type="submit" id="compare-btn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                                    {{ __('Compare Selected') }}
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                            <thead>
                                <tr>
                                    @if($evaluations->count() > 1)
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">
                                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </th>
                                    @endif
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Evaluation Date</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Age</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Grade</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Sport</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Category</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Weight</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Height</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($evaluations as $evaluation)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 {{ $athlete->id == $evaluation->id ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                        @if($evaluations->count() > 1)
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                                <input type="checkbox" name="evaluation_ids[]" value="{{ $evaluation->id }}" class="evaluation-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            </td>
                                        @endif
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ date('d/m/Y', strtotime($evaluation->evaluation_date)) }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->age }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->grade ?? '-' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->sport ?? '-' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->category ?? '-' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->anthropometricData->weight ?? '-' }} kg
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->anthropometricData->standing_height ?? '-' }} cm
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            <a href="{{ route('athletes.evaluation', $evaluation->id) }}" class="text-blue-600 hover:underline mr-2">Details</a>
                                            @if($athlete->id != $evaluation->id)
                                                <form action="{{ route('athletes.destroy', $evaluation->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this evaluation?')">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $evaluations->count() > 1 ? 9 : 8 }}" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">
                                            No evaluations found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($evaluations->count() > 1)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectAll = document.getElementById('select-all');
                const checkboxes = document.querySelectorAll('.evaluation-checkbox');
                const compareBtn = document.getElementById('compare-btn');
                const selectedEvaluations = document.getElementById('selected-evaluations');
                
                // Select all functionality
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = selectAll.checked;
                    });
                    updateCompareButton();
                });
                
                // Individual checkbox functionality
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateCompareButton();
                    });
                });
                
                function updateCompareButton() {
                    const selected = Array.from(checkboxes).filter(checkbox => checkbox.checked);
                    
                    if (selected.length >= 2) {
                        compareBtn.disabled = false;
                        selectedEvaluations.value = selected.map(checkbox => checkbox.value).join(',');
                    } else {
                        compareBtn.disabled = true;
                        selectedEvaluations.value = '';
                    }
                    
                    selectAll.checked = checkboxes.length === selected.length && checkboxes.length > 0;
                }
            });
        </script>
    @endif
</x-app-layout>