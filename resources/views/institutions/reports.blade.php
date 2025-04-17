<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Reports for') }}: {{ $institution->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('institutions.show', $institution->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    {{ __('View Institution') }}
                </a>
                <a href="{{ route('institutions.evaluations', $institution->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    {{ __('View Evaluations') }}
                </a>
                <a href="{{ route('institutions.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    {{ __('Back to Institutions') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Gender Distribution Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Gender Distribution</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Gender</th>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Count</th>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalGender = $genderStats->sum('count');
                                    @endphp
                                    @forelse($genderStats as $stat)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                                {{ $stat->gender == 'm' ? 'Male' : ($stat->gender == 'f' ? 'Female' : 'Other') }}
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                                {{ $stat->count }}
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                                {{ round(($stat->count / $totalGender) * 100, 1) }}%
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">
                                                No data available.
                                            </td>
                                        </tr>
                                    @endforelse
                                    @if($totalGender > 0)
                                        <tr class="bg-gray-100 dark:bg-gray-700 font-medium">
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">Total</td>
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">{{ $totalGender }}</td>
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">100%</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Gender Chart placeholder - would use Chart.js in real implementation -->
                        <div class="h-48 mt-4 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Gender distribution chart would appear here.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Age Distribution Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Age Distribution</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Age Group</th>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Count</th>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalAge = $ageStats->sum('count');
                                    @endphp
                                    @forelse($ageStats as $stat)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                                {{ $stat->age_group }}
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                                {{ $stat->count }}
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                                {{ round(($stat->count / $totalAge) * 100, 1) }}%
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">
                                                No data available.
                                            </td>
                                        </tr>
                                    @endforelse
                                    @if($totalAge > 0)
                                        <tr class="bg-gray-100 dark:bg-gray-700 font-medium">
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">Total</td>
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">{{ $totalAge }}</td>
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">100%</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Age Chart placeholder - would use Chart.js in real implementation -->
                        <div class="h-48 mt-4 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Age distribution chart would appear here.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Sport Distribution Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Sport Distribution</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Sport</th>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Count</th>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalSport = $sportStats->sum('count');
                                    @endphp
                                    @forelse($sportStats as $stat)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                                {{ $stat->sport }}
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                                {{ $stat->count }}
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                                {{ round(($stat->count / $totalSport) * 100, 1) }}%
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">
                                                No data available.
                                            </td>
                                        </tr>
                                    @endforelse
                                    @if($totalSport > 0)
                                        <tr class="bg-gray-100 dark:bg-gray-700 font-medium">
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">Total</td>
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">{{ $totalSport }}</td>
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">100%</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Sport Chart placeholder - would use Chart.js in real implementation -->
                        <div class="h-48 mt-4 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Sport distribution chart would appear here.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Generate Institution Reports</h3>
                        
                        <div class="space-y-4">
                            <p>You can generate various reports for {{ $institution->name }} using the options below:</p>
                            
                            <form action="#" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="institution_id" value="{{ $institution->id }}">
                                
                                <div>
                                    <label for="report_type" class="block text-sm font-medium">Report Type</label>
                                    <select name="report_type" id="report_type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                        <option value="summary">Institution Summary</option>
                                        <option value="detailed">Detailed Athlete Data</option>
                                        <option value="progress">Progress Tracking</option>
                                        <option value="custom">Custom Report</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="format" class="block text-sm font-medium">Format</label>
                                    <select name="format" id="format" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                        <option value="pdf">PDF</option>
                                        <option value="excel">Excel</option>
                                        <option value="csv">CSV</option>
                                    </select>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        Generate Report
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>