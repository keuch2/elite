<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $institution->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('institutions.evaluations', $institution->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    {{ __('View Evaluations') }}
                </a>
                <a href="{{ route('institutions.reports', $institution->id) }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                    {{ __('View Reports') }}
                </a>
                <a href="{{ route('institutions.edit', $institution->id) }}" class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700">
                    {{ __('Edit Institution') }}
                </a>
                <a href="{{ route('institutions.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    {{ __('Back to Institutions') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Institution Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded">
                                <div class="mb-2">
                                    <span class="block text-sm text-gray-500 dark:text-gray-400">Name</span>
                                    <span class="font-medium">{{ $institution->name }}</span>
                                </div>
                                
                                <div class="mb-2">
                                    <span class="block text-sm text-gray-500 dark:text-gray-400">Address</span>
                                    <span class="font-medium">{{ $institution->address ?? 'Not specified' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded">
                                <div class="mb-2">
                                    <span class="block text-sm text-gray-500 dark:text-gray-400">Email</span>
                                    <span class="font-medium">{{ $institution->email ?? 'Not specified' }}</span>
                                </div>
                                
                                <div class="mb-2">
                                    <span class="block text-sm text-gray-500 dark:text-gray-400">Phone</span>
                                    <span class="font-medium">{{ $institution->phone ?? 'Not specified' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex items-center space-x-2">
                        <form action="{{ route('institutions.destroy', $institution->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this institution? This will not delete associated athletes but will remove the association.')">
                                Delete Institution
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Athletes ({{ $athletes->total() }})</h3>
                        <div>
                            <form action="{{ route('institutions.show', $institution->id) }}" method="GET" class="flex space-x-2">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}"
                                    placeholder="Search athletes..." 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    {{ __('Search') }}
                                </button>
                                @if(request()->has('search'))
                                    <a href="{{ route('institutions.show', $institution->id) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
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
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Name</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">ID</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Gender</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Age</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Sport</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($athletes as $athlete)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $athlete->first_name }} {{ $athlete->last_name }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $athlete->identity_document }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            @if($athlete->gender === 'm')
                                                Male
                                            @elseif($athlete->gender === 'f') 
                                                Female
                                            @else 
                                                Other
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            @if($athlete->birth_date)
                                                {{ \Carbon\Carbon::parse($athlete->birth_date)->age }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $athlete->sport ?? '-' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('athletes.show', $athlete->id) }}" class="text-blue-600 hover:underline">View</a>
                                                <a href="{{ route('athletes.edit', $athlete->id) }}" class="text-amber-600 hover:underline">Edit</a>
                                                <a href="{{ route('athletes.evaluations.create', $athlete->id) }}" class="text-green-600 hover:underline">Evaluate</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">
                                            No athletes found for this institution.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $athletes->links() }}
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('athletes.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Add New Athlete
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>