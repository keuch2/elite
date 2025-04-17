<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Evaluations at') }}: {{ $institution->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('institutions.show', $institution->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    {{ __('View Institution') }}
                </a>
                <a href="{{ route('institutions.reports', $institution->id) }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                    {{ __('View Reports') }}
                </a>
                <a href="{{ route('institutions.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    {{ __('Back to Institutions') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-medium">All Evaluations</h3>
                        
                        <div class="flex space-x-2">
                            <form action="{{ route('institutions.evaluations', $institution->id) }}" method="GET" class="flex items-center space-x-2">
                                <select name="sport" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">All Sports</option>
                                    @foreach($evaluations->pluck('sport')->unique()->filter()->sort() as $sport)
                                        <option value="{{ $sport }}" @selected(request('sport') == $sport)>{{ $sport }}</option>
                                    @endforeach
                                </select>
                                
                                <button type="submit" class="px-3 py-1 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                    {{ __('Filter') }}
                                </button>
                                
                                @if(request()->has('sport'))
                                    <a href="{{ route('institutions.evaluations', $institution->id) }}" class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">
                                        {{ __('Clear') }}
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Athlete</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Evaluation Date</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Age</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Sport</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Category</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Height</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Weight</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($evaluations as $evaluation)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            <a href="{{ route('athletes.show', $evaluation->id) }}" class="text-blue-600 hover:underline">
                                                {{ $evaluation->first_name }} {{ $evaluation->last_name }}
                                            </a>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ date('d/m/Y', strtotime($evaluation->evaluation_date)) }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->age }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->sport ?? '-' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->category ?? '-' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->anthropometricData->standing_height ?? '-' }} cm
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->anthropometricData->weight ?? '-' }} kg
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            <a href="{{ route('athletes.evaluation', $evaluation->id) }}" class="text-blue-600 hover:underline">View Details</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">
                                            No evaluations found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $evaluations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>