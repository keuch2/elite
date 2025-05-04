<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Athlete') }}
            </h2>
            <div>
                <a href="{{ route('athletes.show', $athlete->id) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 mr-2">
                    {{ __('Back to Details') }}
                </a>
                <a href="{{ route('athletes.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    {{ __('All Athletes') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('athletes.update', $athlete->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name <span class="text-red-600">*</span></label>
                                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $athlete->first_name) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                                
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name <span class="text-red-600">*</span></label>
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $athlete->last_name) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                                
                                <div>
                                    <label for="identity_document" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Identity Document</label>
                                    <input type="text" name="identity_document" id="identity_document" value="{{ old('identity_document', $athlete->identity_document) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">National ID, passport, etc.</p>
                                </div>
                                
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender <span class="text-red-600">*</span></label>
                                    <select name="gender" id="gender" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        <option value="">Select Gender</option>
                                        <option value="m" {{ old('gender', $athlete->gender) == 'm' ? 'selected' : '' }}>Male</option>
                                        <option value="f" {{ old('gender', $athlete->gender) == 'f' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $athlete->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Birth Date <span class="text-red-600">*</span></label>
                                    <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $athlete->birth_date) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                                
                                <div>
                                    <label for="institution_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Institution</label>
                                    <select name="institution_id" id="institution_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        <option value="">Select Institution</option>
                                        @foreach($institutions as $institution)
                                            <option value="{{ $institution->id }}" {{ old('institution_id', $athlete->institution_id) == $institution->id ? 'selected' : '' }}>
                                                {{ $institution->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">School, club, etc. that the athlete belongs to</p>
                                </div>
                                
                                <div>
                                    <label for="father_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Father's Name</label>
                                    <input type="text" name="father_name" id="father_name" value="{{ old('father_name', $athlete->father_name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                                
                                <div>
                                    <label for="mother_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mother's Name</label>
                                    <input type="text" name="mother_name" id="mother_name" value="{{ old('mother_name', $athlete->mother_name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Evaluation Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="evaluation_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Evaluation Date <span class="text-red-600">*</span></label>
                                    <input type="date" name="evaluation_date" id="evaluation_date" value="{{ old('evaluation_date', $athlete->evaluation_date) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                                
                                <div>
                                    <label for="age" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Age <span class="text-red-600">*</span></label>
                                    <input type="number" name="age" id="age" value="{{ old('age', $athlete->age) }}" required min="1" max="120"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                                
                                <div>
                                    <label for="grade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grade/Year</label>
                                    <input type="text" name="grade" id="grade" value="{{ old('grade', $athlete->grade) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Academic grade or year</p>
                                </div>
                                
                                <div>
                                    <label for="sport" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sport</label>
                                    <input type="text" name="sport" id="sport" value="{{ old('sport', $athlete->sport) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Primary sport of the athlete</p>
                                </div>
                                
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                    <input type="text" name="category" id="category" value="{{ old('category', $athlete->category) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Age group, weight class, or competitive division</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                {{ __('Update Athlete') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>