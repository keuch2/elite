<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Import Athletes Data') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-4">Upload File</h3>
                        <p class="mb-4 text-gray-600 dark:text-gray-400">
                            Upload a CSV or Excel file containing athlete data. The system supports both formats and will process the data accordingly.
                        </p>

                        <form action="{{ route('import.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select File (CSV, XLSX, XLS) <span class="text-red-600">*</span></label>
                                <input type="file" name="file" id="file" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 dark:border-gray-700 dark:bg-gray-900" required accept=".csv,.xlsx,.xls">
                                @error('file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    {{ __('Upload & Process') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                        <h3 class="text-lg font-medium mb-4">File Requirements</h3>

                        <div class="prose dark:prose-invert">
                            <h4>Required Columns for Athletes Import</h4>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>first_name - The athlete's first name</li>
                                <li>last_name - The athlete's last name</li>
                                <li>gender - Gender as 'm' for male, 'f' for female, or 'o' for other</li>
                                <li>identity_document - National ID or identification number (must be unique)</li>
                                <li>birth_date - Birth date in YYYY-MM-DD format</li>
                                <li>institution_name - The institution name (will be created if it doesn't exist)</li>
                            </ul>

                            <h4 class="mt-4">Optional Columns</h4>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>father_name - Father's full name</li>
                                <li>mother_name - Mother's full name</li>
                                <li>grade - Current school grade/year</li>
                                <li>sport - Main sport practiced</li>
                                <li>category - Sport category/division</li>
                                <li>weight - Athlete weight in kg</li>
                                <li>standing_height - Standing height in cm</li>
                                <li>sitting_height - Sitting height in cm</li>
                                <li>wingspan - Wingspan measurement in cm</li>
                            </ul>
                            
                            <h4 class="mt-4">Example</h4>
                            <p>You can <a href="#" class="text-blue-600 hover:underline">download a sample CSV template here</a> to see the expected format.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>